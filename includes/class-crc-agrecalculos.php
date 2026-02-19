<?php

class CRC_AgreCalculo{

//Funcion para traer los datos de todos los meses de un usuario desde que empezo a participar
 public function crc_datosfull_agreinvestor($user) {

   $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
   '8' => 'Agosto',
   '9' => 'Septiembre',
   '10' => 'Octubre',
   '11' => 'Noviembre',
   '12' => 'Diciembre' );

   // $mes = (int)$mes;
   // $tmes = $mesesNombre[$mes];
   // $agno = (int)$year;

   global $wpdb;


   // traer depositos
   $tabladep = $wpdb->prefix . 'depositos_agr';
   $totaldep = $wpdb->get_results("SELECT month(dagr_fecha_termino) AS mes, year(dagr_fecha_termino) AS agno, ROUND(SUM(dagr_cantidad_real), 2) AS totaldep FROM $tabladep WHERE dagr_usuario = $user AND dagr_status = 2 ", ARRAY_A);

   // traer retiros
   $tablaret = $wpdb->prefix . 'retiros_agr';
   $totalret = $wpdb->get_results("SELECT month(ragr_fecha_termino) AS mes, year(ragr_fecha_termino) AS agno, ROUND(SUM(ragr_cantidad_real), 2) AS totalret FROM $tablaret WHERE ragr_usuario = $user AND  ragr_status = 2 ", ARRAY_A);

   $tabla = $wpdb->prefix . 'registros_agr';
   $ruta = get_site_url();
   // $registros = $wpdb->get_results(" SELECT * FROM $tabla ORDER BY reagr_mes, reagr_year  ", ARRAY_A);

   $datosagreusermes = array();

   // vemos si hay depositos autorizados y fijamos la fecha de arranque
   if(count($totaldep) != 0){

     // Recorremos los registros en los que participa el usuario desde el mes de su primer deposito hasta hoy
     $mesuno = $totaldep[0]["mes"];
     $agnouno = (int) $totaldep[0]["agno"];
     $agno = $agnouno;
     $fechaini = date($totaldep[0]["agno"]."-".$totaldep[0]["mes"]."-01");
     $mes = (int) $mesuno;

     //calculamos la diferencia de meses
     $fechahoy = date("Y-m-d");
     $fechaSeparada = explode("-", $fechahoy);
     $meshoy = (int) $fechaSeparada[1];
     $agnohoy = (int) $fechaSeparada[0];

     $fecha1= new DateTime($fechaini);
     $fecha2= new DateTime($fechahoy);
     $diff = $fecha1->diff($fecha2);

     $yearsInMonths = $diff->format('%r%y') * 12;
     $months = $diff->format('%r%m');
     $totalMonths = $yearsInMonths + $months;
     $mesinversor = $yearsInMonths + $months + 1;

     // Variables de inicio
     $capini = 0.00;
     $totalcierremes = 0.00;
     $total= 0.00;
     $utilidadacum = 0.00;

     // Recorremos los meses que han pasado hasta hoy
     for ($i=0; $i < $mesinversor ; $i++) {

       // $utilinicial = (float)$registros[0]['reagr_util_mes'];
       $tmes = $mesesNombre[$mes];

       // Vemos si hay depositos del mes
       $depmes = 0.00;

       foreach ($totaldep as $key => $value) {
         if ($mes == $value["mes"] && $agno == $value["agno"]) {
           $depmes = (float)$value["totaldep"];
         }
       }

       $capini = $depmes + $totalcierremes;

       $utilmes = 0.00;
       $combroker = 0.00;
       $utilreal = 0.00;

       $rendimiento = 0.00;

       // Recorremos los registros para ver si uno coincide con este
       // Si hay regisrtos

       $registros = $this->crc_datosproyeccion_agreregistros();
       $porparticipuser = 0;
       $utilinvestors = 0;
       $uutilmes = 0;
       $rendimientomes = 0;


       if (count($registros) != 0) {
         foreach ($registros as $key => $value) {
           if ($value["mes"] == $mes && $value["year"] == $agno) {
             // Ahora checamos si la utilidad se reparte entre admins o si hay usuarios
             $listausers = $value["usuarios"];
             $capinitotal = (float)$value["capiniusers"];
             $utilinvestors = (float)$value["investors"];
             $porparticipuser = round(($capini/$capinitotal)*100,4);
             $uutilmes = round(($porparticipuser/100)*$utilinvestors,2);
             if ($capini == 0) {
               $rendimientomes = 100;
             }else {
               $rendimientomes = round(($uutilmes/$capini)*100,2);
             }

           }
         }
       }

       // Vemos si hay retiros del mes
       $retmes = 0.00;

       foreach ($totalret as $key => $value) {
         if ($mes == $value["mes"] && $agno == $value["agno"]) {
           $retmes = (float)$value["totalret"];
         }
       }

       $utilidadacum = $uutilmes + $utilidadacum;
       $total = $capini + $uutilmes;

       $totalcierremes = $capini + $uutilmes - $retmes;

       $datosagreusermes[] = array(
           'mes'=>$mes,
           'tmes'=> $tmes,
           'year'=> $agno,
           'capini'=> $capini,
           'porparticipuser'=>$porparticipuser,
           'total'=>$total,
           'utilidad'=> $uutilmes,
           'utilacumulada'=> $utilidadacum,
           'utilmes' => $utilmes,
           'combroker' => $combroker,
           'utilreal' => $utilreal,
           'depmes'=> $depmes,
           'rendimientomes' => $rendimientomes,
           'totalcierremes'=>$totalcierremes,
           'retmes'=> $retmes
         );

         if ($mes == 12) {
           $mes = 1;
           $agno++;
         }else{
           $mes++;
         }
     }

   }

   return  $datosagreusermes;
   // return  $mesinversor;
 }

// Funcion que te da los datos de un usuario para un mes y año especifico
 public function crc_datosmes_agreinvestor($user,$month,$year) {

  $datosagreusermes = array();

  $registros = $this->crc_datosfull_agreinvestor($user);

  if (count($registros) != 0) {
    foreach ($registros as $key => $value) {
      if ($value["mes"] == $month && $value["year"] == $year) {
        $datosagreusermes[] = $value;
      }
    }
  }

   return  $datosagreusermes;
   // return  $mesinversor;
 }

 public function crc_datosproyeccion_agreregistros() {

   $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
   '8' => 'Agosto',
   '9' => 'Septiembre',
   '10' => 'Octubre',
   '11' => 'Noviembre',
   '12' => 'Diciembre' );

   // $mes = (int)$mes;
   // $tmes = $mesesNombre[$mes];
   // $agno = (int)$year;

   global $wpdb;

   // traer depositos
   $tabladep = $wpdb->prefix . 'depositos_agr';
   $totaldep = $wpdb->get_results("SELECT month(dagr_fecha_termino) AS mes, year(dagr_fecha_termino) AS agno, ROUND(SUM(dagr_cantidad_real), 2) AS totaldep FROM $tabladep WHERE  dagr_status = 2 GROUP BY mes, agno ", ARRAY_A);

   // traer retiros
   $tablaret = $wpdb->prefix . 'retiros_agr';
   $totalret = $wpdb->get_results("SELECT month(ragr_fecha_termino) AS mes, year(ragr_fecha_termino) AS agno, ROUND(SUM(ragr_cantidad_real), 2) AS totalret FROM $tablaret WHERE  ragr_status = 2 GROUP BY mes, agno", ARRAY_A);

   $tabla = $wpdb->prefix . 'registros_agr';
   $ruta = get_site_url();
   $registros = $wpdb->get_results(" SELECT * FROM $tabla ORDER BY reagr_mes, reagr_year ", ARRAY_A);

   $datosagreregistros = array();



   // vemos si hay depositos autorizados y fijamos la fecha de arranque
   if(count($totaldep) != 0){

   // Variables de inicio
   $capini = 0.00;
   $totalcierremes = 0.00;


   // Recorremos los registros para ver si uno coincide con este
   // Si hay regisrtos
   if (count($registros) != 0) {
     $totalcierretheinc = 0.00;
     $totalcierregopro = 0.00;
     foreach ($registros as $key => $value) {

       // $utilinicial = (float)$registros[0]['reagr_util_mes'];
       $mes = (int)$value["reagr_mes"];
       $tmes = $mesesNombre[$mes];
       $agno = (int)$value["reagr_year"];

       $notas = "<button aria-label='".$value["reagr_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";

       $fechaSeparada = explode("-", $value["reagr_fecha_control"]);
       $fechareg = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

       // Vemos si hay depositos del mes
       $depmes = 0.00;

       foreach ($totaldep as $llave => $valor) {
         if ($mes == $valor["mes"] && $agno == $valor["agno"]) {
           $depmes = (float)$valor["totaldep"];
         }
       }

       $capini = $depmes + $totalcierremes;

       $utilmes = 0.00;
       $combroker = 0.00;
       $utilreal = 0.00;

       $utilmes = (float)$value['reagr_util_mes'];
       $combroker = (float)$value['reagr_com_bro'];
       $utilreal = $utilmes - $combroker ;

       // Ahora checamos si la utilidad se reparte entre admins o si hay usuarios
       $listausers = json_decode($value['reagr_usuarios'],true);

       // Checamos cuantos users hay
       if (count($listausers) == 0 ) {
         $porinver = 0;
         $poradmins = 100 ;
       }else{
         $porinver = (float)$value['reagr_por_inver'];
         $poradmins = 100 - $porinver ;
       }

       //INVESTORS

       $utilinvestors = round($utilreal*($porinver/100),2);
       // $tutilinvestors = number_format($utilinvestors, 2, '.', ',')


       // Vemos si hay retiros del mes
       $retmes = 0.00;

       foreach ($totalret as $llave => $valor) {
         if ($mes == $valor["mes"] && $agno == $valor["agno"]) {
           $retmes = (float)$valor["totalret"];
         }
       }

       $totalcierremes = $capini + $utilinvestors - $retmes;


       // REFERIDOS

       $utilrefers = 0;


       // THEINC Y GOPRO

       $utilrealresto = $utilreal - $utilinvestors - $utilrefers;

       // Checamos depositos master del mes
       $deptheinc = 0;
       $depgopro = 0;

       $tabladepmas = $wpdb->prefix . 'depositos_master_agr';
       $depmas = $wpdb->get_results("SELECT month(dmagr_fecha_termino) AS mes, year(dmagr_fecha_termino) AS agno, ROUND(SUM(dmagr_cantidad_real), 2) AS totaldep, dmagr_usuario FROM $tabladepmas WHERE month(dmagr_fecha_termino) = $mes AND year(dmagr_fecha_termino) = $agno AND dmagr_status = 2 GROUP BY dmagr_usuario ", ARRAY_A);

       if(count($depmas) != 0 ){
         foreach ($depmas as $llave => $valor) {
           if ($valor["dmagr_usuario"] == 16) {
             $deptheinc = (float)$valor["totaldep"];
           }else{
             $depgopro = (float)$valor["totaldep"];
           }
         }
       }

       $depmasmes = $deptheinc + $depgopro;

       $theinc = $deptheinc + $totalcierretheinc;
       $gopro = $depgopro + $totalcierregopro;

       $totaladmins = $theinc + $gopro;

       $capinitotal = $capini + $totaladmins;

       if ($totaladmins == 0) {
         $partictheinc = 50;
         $particgopro = 50;
       }else{
         $partictheinc = round(($theinc*100)/$totaladmins, 2);
         $particgopro = round(($gopro*100)/$totaladmins, 2);
       }

       $repartotheinc = round($utilrealresto*($partictheinc/100),2);
       $trepartotheinc = number_format($repartotheinc, 2, '.', ',');
       $repartogopro = round($utilrealresto*($particgopro/100),2);
       $trepartogopro = number_format($repartogopro, 2, '.', ',');

       $utilmestheinc = 0.00;
       $utilmesgopro = 0.00;

       // Checamos retiros master del mes
       $rettheinc = 0;
       $retgopro = 0;

       $tablaretmas = $wpdb->prefix . 'retiros_master_agr';
       $retmas = $wpdb->get_results("SELECT month(rmagr_fecha_termino) AS mes, year(rmagr_fecha_termino) AS agno, ROUND(SUM(rmagr_cantidad_real), 2) AS totalret, rmagr_usuario FROM $tablaretmas WHERE month(rmagr_fecha_termino) = $mes AND year(rmagr_fecha_termino) = $agno AND rmagr_status = 2 GROUP BY rmagr_usuario ", ARRAY_A);

       if(count($retmas) != 0 ){
         foreach ($retmas as $llave => $valor) {
           if ($valor["dmagr_usuario"] == 16) {
             $rettheinc = (float)$valor["totalret"];
           }else{
             $retgopro = (float)$valor["totalret"];
           }
         }
       }

       $retmasmes = $rettheinc + $retgopro;

       $totalcierretheinc = $theinc + $repartotheinc - $rettheinc;
       $totalcierregopro = $gopro + $repartogopro - $retgopro;
       $totalcierreadmins = $totalcierretheinc + $totalcierregopro;

       $porutilreal = round(($utilreal/$capinitotal)*100,2);

       $totalcierremestodo =  $totalcierremes + $totalcierreadmins;



      // ARRAY DE LOS USUARIOS DEL MES

      $datosagreusermes = array();
      $promgananciasxuser = 0;

      if (count($listausers) == 0 ) {

      }else{

        foreach ($listausers as $ukey => $uvalue) {
          $user = $uvalue;
          // traer depositos
          $tabladep = $wpdb->prefix . 'depositos_agr';
          $totaldep = $wpdb->get_results("SELECT month(dagr_fecha_termino) AS mes, year(dagr_fecha_termino) AS agno, ROUND(SUM(dagr_cantidad_real), 2) AS totaldep FROM $tabladep WHERE dagr_usuario = $user AND dagr_status = 2 ", ARRAY_A);

          // traer retiros
          $tablaret = $wpdb->prefix . 'retiros_agr';
          $totalret = $wpdb->get_results("SELECT month(ragr_fecha_termino) AS mes, year(ragr_fecha_termino) AS agno, ROUND(SUM(ragr_cantidad_real), 2) AS totalret FROM $tablaret WHERE ragr_usuario = $user AND  ragr_status = 2 ", ARRAY_A);

          $tabla = $wpdb->prefix . 'registros_agr';
          $ruta = get_site_url();
          // $registros = $wpdb->get_results(" SELECT * FROM $tabla ORDER BY reagr_mes, reagr_year  ", ARRAY_A);

          // vemos si hay depositos autorizados y fijamos la fecha de arranque
          if(count($totaldep) != 0){

            // Recorremos los registros en los que participa el usuario desde el mes de su primer deposito hasta hoy
            $mesuno = $totaldep[0]["mes"];
            $agnouno = (int) $totaldep[0]["agno"];
            $uagno = $agnouno;
            $fechaini = date($totaldep[0]["agno"]."-".$totaldep[0]["mes"]."-01");
            $umes = (int) $mesuno;

            //calculamos la diferencia de meses
            $fechahoy = date("Y-m-d");
            $fechaSeparada = explode("-", $fechahoy);
            $meshoy = (int) $fechaSeparada[1];
            $agnohoy = (int) $fechaSeparada[0];

            $fecha1= new DateTime($fechaini);
            $fecha2= new DateTime($fechahoy);
            $diff = $fecha1->diff($fecha2);

            $yearsInMonths = $diff->format('%r%y') * 12;
            $months = $diff->format('%r%m');
            $totalMonths = $yearsInMonths + $months;
            $mesinversor = $yearsInMonths + $months + 1;

            // Variables de inicio
            $ucapini = 0.00;
            $utotalcierremes = 0.00;
            $utotal= 0.00;

            // Recorremos los meses que han pasado hasta hoy
            for ($i=0; $i < $mesinversor ; $i++) {

              // $utilinicial = (float)$registros[0]['reagr_util_mes'];
              $utmes = $mesesNombre[$umes];

              // Vemos si hay depositos del mes
              $udepmes = 0.00;

              foreach ($totaldep as $wkey => $wvalue) {
                if ($umes == $wvalue["mes"] && $uagno == $wvalue["agno"]) {
                  $udepmes = (float)$wvalue["totaldep"];
                }
              }

              $ucapini = $udepmes + $utotalcierremes;

              $uutilmes = 0.00;
              $ucombroker = 0.00;
              $uutilreal = 0.00;
              $uutilidadacum = 0.00;
              $urendimiento = 0.00;

              // Llenamos la info con la info del registro si hay

              $uporparticipuser = 0;
              $uutilinvestors = 0;

              // Ahora checamos si la utilidad se reparte entre admins o si hay usuarios

              $ucapinitotal = $capini;
              $uutilinvestors = $utilinvestors;
              $uporparticipuser = round(($ucapini/$ucapinitotal)*100,4);
              $uutilmes = round(($uporparticipuser/100)*$uutilinvestors,2);


              if ($ucapini == 0) {
                $urendimientomes = 100;
              }else {
                $urendimientomes = round(($uutilmes/$ucapini)*100,2);
              }

              // Valoramos si el porcentaje es mayor de cero sera el que sacamos
              if ($uporparticipuser > 0 ) {
                $promgananciasxuser = round($urendimientomes,2);
              }

              // Vemos si hay retiros del mes
              $uretmes = 0.00;

              foreach ($totalret as $key => $value) {
                if ($umes == $value["mes"] && $uagno == $value["agno"]) {
                  $uretmes = (float)$value["totalret"];
                }
              }

              $uutilidadacum = $uutilmes + $uutilidadacum;
              $utotal = $ucapini + $uutilmes;

              $utotalcierremes = $ucapini + $uutilmes - $uretmes;

              if ($umes == $mes && $uagno == $agno) {
                $datosagreusermes[] = array(
                    'id' => $user,
                    'mes'=>$umes,
                    'tmes'=> $utmes,
                    'year'=> $uagno,
                    'capini'=> $ucapini,
                    'porparticipuser'=>$uporparticipuser,
                    'total'=>$utotal,
                    'utilidad'=> $uutilmes,
                    'utilacumulada'=> $uutilidadacum,
                    'utilmes' => $utilmes,
                    'combroker' => $combroker,
                    'utilreal' => $uutilreal,
                    'depmes'=> $udepmes,
                    'rendimientomes' => $urendimientomes,
                    'totalcierremes'=>$utotalcierremes,
                    'retmes'=> $uretmes
                  );
              }

                if ($umes == 12) {
                  $umes = 1;
                  $uagno++;
                }else{
                  $umes++;
                }
            }

          }

        }//fin foreach usuarios


      } //fin if si hay user



       $datosagreregistros[] = array(
           'mes'=>$mes,
           'tmes'=> $tmes,
           'year'=> $agno,
           'capini'=>$capinitotal,
           'capiniusers'=>$capini,
           'capinitheinc' =>$theinc,
           'capinigopro' =>$gopro,
           'total'=>"",
           'utilidad'=>"",
           'utilacumulada'=>"0",
           'utilmes' => $utilmes,
           'combroker' => $combroker,
           'utilreal' => $utilreal,
           'investors' => $utilinvestors,
           'theinc' => $repartotheinc,
           'gopro' => $repartogopro,
           'utilrealpor' => $porutilreal,
           'utilinvpor' => $promgananciasxuser,
           'totalcierreinv' => $totalcierremes,
           'totalcierretheinc' => $totalcierretheinc,
           'totalcierregopro' => $totalcierregopro,
           'totalcierremes' => $totalcierremestodo,
           'depmes'=> $depmes,
           'retmes'=> $retmes,
           'depmasmes'=> $depmasmes,
           'retmasmes'=> $retmasmes,
           'notas' => $notas,
           'fecharegistro' => $fechareg,
           'usuarios' => $listausers,
           'detallesusers' => $datosagreusermes
         );

     }
   }

  }

   return  $datosagreregistros;
 }

 public function crc_datoscajasuperiores_admin() {
   // Recorremos los registros para ver si uno coincide con este
   // Si hay regisrtos
   global $wpdb;

   $registros = $this->crc_datosproyeccion_agreregistros();
   $reversed = array_reverse($registros);

   //calculamos la fecha de hoy
   $fechahoy = date("Y-m-d");
   $fechaSeparada = explode("-", $fechahoy);
   $meshoy = (int) $fechaSeparada[1];
   $agnohoy = (int) $fechaSeparada[0];

   $fechasfuturas = "";


   if (count($reversed) > 0) {

     // checamos mes y año del ultimo registro
     $mesu = (int)$reversed[0]["mes"];
     $agnou = (int)$reversed[0]["year"];

     if ($mesu == 12) {
       $mesf = 1;
       $agnof = $agnou + 1;
     }else{
       $mesf = $mesu + 1;
       $agnof = $agnou;
     }

     $fechasfuturas = $agnof."-".$mesf."-01";
     // $fechasfuturas = $agnof."-1-01";

     // traer depositos
     $tabladep = $wpdb->prefix . 'depositos_agr';
     $totaldep = $wpdb->get_results("SELECT ROUND(SUM(dagr_cantidad_real), 2) AS totaldep FROM $tabladep WHERE dagr_status = 2 AND dagr_fecha_termino >= '".$fechasfuturas."'" , ARRAY_A);
     //
     if (empty($totaldep[0])) {
       $depmesf = 0;
     }else {
       $depmesf = (float)$totaldep[0]["totaldep"];
     }

     // traer depositos master
     $tabladepmas = $wpdb->prefix . 'depositos_master_agr';
     $totaldepmas = $wpdb->get_results("SELECT dmagr_usuario, ROUND(SUM(dmagr_cantidad_real), 2) AS totaldep FROM $tabladepmas WHERE dmagr_status = 2 AND dmagr_fecha_termino >= '".$fechasfuturas."' GROUP BY dmagr_usuario "  , ARRAY_A);

     $dftheinc = 0;
     $dfgopro = 0;
     //Separamos los depositos master
     if(!empty($totaldepmas)){
       foreach ($totaldepmas as $llave => $valor) {
         if ($valor["dmagr_usuario"] == 16) {
           $dftheinc = (float)$valor["totaldep"];
         }else{
           $dfgopro = (float)$valor["totaldep"];
         }
       }
     }

     $depmasmesf = $dftheinc + $dfgopro;


     //Total cuenta
     $totalcierreultmes =  (float)$reversed[0]["totalcierremes"];

     $totalcuenta = $totalcierreultmes + $depmesf + $depmasmesf;

     //total inversionistas
     $totalinvultmes  =  (float)$reversed[0]["totalcierreinv"];

     $totalinvestors = $totalinvultmes + $depmesf ;

     //total theinc
     $totaltheincultmes  =  (float)$reversed[0]["totalcierretheinc"];

     $totaltheinc = $totaltheincultmes + $dftheinc ;

     //total gopro
     $totalgoproultmes  =  (float)$reversed[0]["totalcierregopro"];

     $totalgopro = $totalgoproultmes + $dfgopro ;


   }else {
     $totalcuenta = 0;
     $totalinvestors = 0;
     $totaltheinc = 0;
     $totalgopro = 0;
     $dftheinc = 0;
     $dfgopro = 0;
     $depmasmesf = 0;
     $depmesf = 0;
   }




   $datoscajas = array();

   $datoscajas[] = array(
     "mes" => $meshoy,
     "agno" => $agnohoy,
     "totalcuenta" => $totalcuenta,
     "totalinvestors" => $totalinvestors,
     "totaltheinc" => $totaltheinc,
     "totalgopro" => $totalgopro,
     "depmesfuturos" => $depmesf,
     "depmasfuturos" => $depmasmesf,
     "deptheincf" => $dftheinc,
     "depgoprof" => $dfgopro
   );

   return $datoscajas;

 }

}
