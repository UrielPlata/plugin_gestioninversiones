<?php

class CRC_ConsCalculo{

//Funcion para traer los datos de todos los meses de un usuario desde que empezo a participar
 public function crc_datosfull_consinvestor($user) {

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
   $tabladep = $wpdb->prefix . 'depositos_con';
   $totaldep = $wpdb->get_results("SELECT month(dcon_fecha_termino) AS mes, year(dcon_fecha_termino) AS agno, ROUND(SUM(dcon_cantidad_real), 2) AS totaldep FROM $tabladep WHERE dcon_usuario = $user AND dcon_status = 2 GROUP BY mes, agno", ARRAY_A);

   // traer retiros
   $tablaret = $wpdb->prefix . 'retiros_con';
   $totalret = $wpdb->get_results("SELECT month(rcon_fecha_termino) AS mes, year(rcon_fecha_termino) AS agno, ROUND(SUM(rcon_cantidad_real), 2) AS totalret FROM $tablaret WHERE rcon_usuario = $user AND  rcon_status = 2 GROUP BY mes, agno", ARRAY_A);

   // $tabla = $wpdb->prefix . 'registros_agr';
   $ruta = get_site_url();
   // $registros = $wpdb->get_results(" SELECT * FROM $tabla ORDER BY reagr_mes, reagr_year  ", ARRAY_A);

   $datosconsusermes = array();

   // vemos si hay depositos autorizados y fijamos la fecha de arranque
   if(count($totaldep) != 0 && $totaldep[0]["mes"] != NULL ){

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

     $tablastatus = $wpdb->prefix . 'nuevosstatus_con';
     $registrossta = $wpdb->get_results("SELECT * FROM $tablastatus WHERE nscon_usuario = $user ORDER BY nscon_year, nscon_mes ASC", ARRAY_A);

     $capiniprimes = (float)$totaldep[0]["totaldep"];

     if ($capiniprimes >= 10000) {
       $status = 8.33;
     }else {
       $status = 6.66;
     }

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

       // Calculamos utilidad del mes

       // definimos status inicial


       if(count($registrossta) >= 0){

         foreach ($registrossta as $key => $value) {

           if ( $value["nscon_mes"] == $mes &&  $value["nscon_year"] == $agno) {

             if ($value["nscon_tipo"] == 1) {
               $status = (float)$value["nscon_porcentaje"];
             }else {
               if ($capini >= 10000) {
                 $status = 8.33;
               }else {
                 $status = 6.66;
               }
             }
             break;
           }

         }

       }else{

       }

       $utilmes = 0.00;

       $utilmes = round($capini*($status/100),2);

       // Vemos si hay retiros del mes
       $retmes = 0.00;

       foreach ($totalret as $key => $value) {
         if ($mes == $value["mes"] && $agno == $value["agno"]) {
           $retmes = (float)$value["totalret"];
         }
       }

       $utilidadacum = $utilmes + $utilidadacum;
       $total = $capini + $utilmes;

       $totalcierremes = $capini + $utilmes - $retmes;

       $fstatusmes = $status;

       $datosconsusermes[] = array(
           'mes'=>$mes,
           'tmes'=> $tmes,
           'year'=> $agno,
           'capini'=> $capini,
           'utilmes'=> $utilmes,
           'utilacumulada'=> $utilidadacum,
           'depmes'=> $depmes,
           'totalcierremes'=>$totalcierremes,
           'retmes'=> $retmes,
           'statusmes'=> $fstatusmes
         );

         if ($mes == 12) {
           $mes = 1;
           $agno++;
         }else{
           $mes++;
         }
     }

   }

   return  $datosconsusermes;
   // return  $mesinversor;
 }

}
