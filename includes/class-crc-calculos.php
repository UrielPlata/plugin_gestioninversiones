<?php

class CRC_Calculo{
//inicializa la creacion de las tablas nuevas
 public function crc_utilidad_referidos($user) {
   date_default_timezone_set('America/Tijuana');
   $user_data = get_userdata( absint( $user) );
   $invitecode = get_user_meta($user,'invitecode',true);
   if(!$invitecode){
     $invitenum = 'AAAAAA';
   }else{
     $invitenum = $invitecode;
   }
   $referidos = get_users(array(
    'meta_key' => 'referido',
    'meta_value' => $invitenum
  ));

  $idreferidos = array();
  $utilidadintacu = array();
  $utilidadref = 0;

  global $wpdb;
  $tabla = $wpdb->prefix . 'depositos';
  $tabla2 = $wpdb->prefix . 'retiros';
  $tabla3 = $wpdb->prefix . 'mesesinv';

  foreach ($referidos as $key => $value) {
    // $id = $value->ID;
    $user_actual = $value->ID;
    $user_data = get_userdata( absint( $user_actual ) );
    //$invitecode = get_user_meta($user_actual,'invitecode',true);
    $status = get_user_meta($user_actual,'status',true);
    $interes = ((int) $status / 100);

    if ($status != '') {
      $depositos = $wpdb->get_results("SELECT month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidad_real, fecha_cuando, fecha_termino FROM $tabla WHERE usuario = $user_actual AND status = 2 ORDER BY fecha_termino", ARRAY_A);
      $totaldep = $wpdb->get_results("SELECT ROUND(SUM(cantidad_real), 2) AS totaldep FROM $tabla WHERE usuario = $user_actual AND status = 2 ", ARRAY_A);
      $retiros = $wpdb->get_results("SELECT day(fecha_termino) AS dia, month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidadfin, urgente, fecha_cuando FROM $tabla2 WHERE usuario = $user_actual AND status = 2 ORDER BY fecha_termino", ARRAY_A);
      $mesesinv = $wpdb->get_results("SELECT mes, interes FROM $tabla3 WHERE usuario = $user_actual AND status = 1 ORDER BY id", ARRAY_A);
      $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
      '8' => 'Agosto',
      '9' => 'Septiembre',
      '10' => 'Octubre',
      '11' => 'Noviembre',
      '12' => 'Diciembre' );
      if(count($depositos) == 0){
        $nohay = true;
        $tsaldini = 0;
        $tintacu = 0;
        //Necesito el mismo num de tintacu pero en num, a este le vamos a aplicar si es posterior a julio del 2022
        $nintacu = 0;
        $totaldisp = 0;
        $ttotaldep = 0;
      }else {
        $nohay = false;

        $mesuno = $depositos[0]["mes"];
        $agnouno = (int) $depositos[0]["agno"];
        $agno = $agnouno;
        $fechaini = date($depositos[0]["agno"]."-".$depositos[0]["mes"]."-01");
        $inicio = 0;
        $intacu = 0;
        $nintacu = 0;
        $totalacu = 0;
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

        for ($i = 0 ; $i < 12; $i++) {
          $statusmes = $mesesinv[$i]["interes"];
          $intmes = ((int) $statusmes / 100);
          $tmes = $mesesNombre[$mes];
          $strmes = (string) $mes;
          $capprin = $totalacu;
          $capprinstart = 0;
          $retmes = 0;
          $depmes = 0;
          $intacumes = 0;
          //Evaluamos el interes de los retiros de este mes
          if(count($retiros) == 0){
          }else {

            //Filtramos los depositos de este mes
            $retirosmes = [];
            foreach ( $retiros as $k => $v ) {
                    if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                            $retirosmes[$k] = $v;
                    }
            }

            //Verificamos cada retiro por separado
            foreach ($retirosmes as $key => $value) {
              $diames = (int)$value["dia"];
              //Vemos si es urgente o no
              if ($value["urgente"] == 1) {
                if ($diames <= 14) {
                  $intgen = 0;
                  $intacumes = round(($intacumes + $intgen), 2);
                  $intacu = round(($intacu + $intgen), 2);
                  $retmes = round(($retmes + $value["cantidadfin"]), 2);
                } else if ($diames >= 15 && $diames < 30) {
                  $intgen = round((0.05 * $value["cantidadfin"]), 2);
                  $intacumes = round(($intacumes + $intgen), 2);
                  $intacu = round(($intacu + $intgen), 2);
                  $retmes = round(($retmes + $value["cantidadfin"]), 2);
                }else{
                  $intgen = round(($intmes * $value["cantidadfin"]), 2);
                  $intacumes = round(($intacumes + $intgen), 2);
                  $intacu = round(($intacu + $intgen), 2);
                  $retmes = round(($retmes + $value["cantidadfin"]), 2);
                }
              }else{
                //Vemos si es de dia 15 o dia 30
                if ($value["fecha_cuando"] == 1) {
                  $intgen = round((0.05 * $value["cantidadfin"]), 2);
                  $intacumes = round(($intacumes + $intgen), 2);
                  $intacu = round(($intacu + $intgen), 2);
                  $retmes = round(($retmes + $value["cantidadfin"]), 2);


                }else{
                  $intgen = round(($intmes * $value["cantidadfin"]), 2);
                  $intacumes = round(($intacumes + $intgen), 2);
                  $intacu = round(($intacu + $intgen), 2);
                  $retmes = round(($retmes + $value["cantidadfin"]), 2);
                }
              }

            }
          }


          $capprin = round(($capprin - $retmes ), 2);
          $intgencapprin = round(($intmes * $capprin ), 2);



          //Evaluamos el interes de los depositos de este mes
          if(count($depositos) == 0){
          }else {

            //Filtramos los depositos de este mes
            $depositosmes = [];
            foreach ( $depositos as $k => $v ) {
                    if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                            $depositosmes[$k] = $v;
                    }
            }


            $capprin = round(($capprin + $retmes ), 2);

            //Verificamos cada deposito por separado
            foreach ($depositosmes as $key => $value) {

              //Vemos si es de dia 1 o dia 15
              if ($value["fecha_cuando"] == 1) {
                $intgen = round(($intmes * $value["cantidad_real"]), 2);
                $intacumes = round(($intacumes + $intgen), 2);
                $intacu = round(($intacu + $intgen), 2);
                $depmes = round(($depmes + $value["cantidad_real"]), 2);


              }else{
                $intgen = round((0.05 * $value["cantidad_real"]), 2);
                $intacumes = round(($intacumes + $intgen), 2);
                $intacu = round(($intacu + $intgen), 2);
                $depmes = round(($depmes + $value["cantidad_real"]), 2);
              }
            }
          }


          $totalintmes = round(($intgencapprin + $intacumes ), 2);
          $intacu = round(($intgencapprin + $intacu), 2);
          $capprin = round(($capprin + $depmes ), 2);
          // $capprinstart = round(($capprin + $retmes ), 2);
          $capprinstart = $capprin;
          $totalacu =  round(($capprin + $totalintmes ) - $retmes, 2);

          //Evaluamos si este mes es posterior a julio-2022 para sumar la utilidad referida del 1%
          //Aqui hay que meter algo en la condicional para que se detenga en el mes actual
          // if($agno >= 2023){
          //   $porutilmes = round(($totalintmes * 0.01 ), 2);
          //   $nintacu += $porutilmes;
          // }else if ($mes >= 7 && $agno >= 2022) {
          //   $porutilmes = round(($totalintmes * 0.01 ), 2);
          //   $nintacu += $porutilmes;
          // }else {
          //   // code...
          // }

          // echo $meshoy;
          // echo "<br>";
          // echo $mes . "mes";
          // echo "<br>";

          if($totalMonths == 0){
            $tsaldini = number_format($totalacu, 2, '.', ',');
            $tintacu = 0;
            $ttotaldep = number_format($totaldep[0]['totaldep'], 2, '.', ',');
            $totaldisp = 0;
            $i = 12;
          }else{
            if (($i+1) == $totalMonths ) {
              $tsaldini = number_format($totalacu, 2, '.', ',');
              $tintacu = number_format($intacu, 2, '.', ',');
              $totaldisp = $totalacu;
              $ttotaldep = number_format($totaldep[0]['totaldep'], 2, '.', ',');
            }else{

            }

            if(($i+1) <= $totalMonths ){
              if($agno >= 2023){
                if (($agno == 2023 && $mes == 2) || ($agno == 2023 && $mes == 4) || ($agno == 2023 && $mes == 5) || ($agno == 2023 && $mes == 6) || ($agno == 2023 && $mes == 7) ) {
                  $porutilmes = 0;
                  $nintacu += $porutilmes;
                }else{
                  $porutilmes = round(($capprinstart * 0.01 ), 2);
                  $nintacu += $porutilmes;
                }
              }else if ($mes >= 7 && $agno >= 2022) {
                $porutilmes = round(($capprinstart * 0.01 ), 2);
                $nintacu += $porutilmes;
              }else {
                // code...
              }
            }
          }

          if ($mes == 12) {
            $mes = 1;
            $agno++;
          }else{
            $mes++;
          }

        }

        //Volvemos tintacu a float y lo sumamos al total de utilref
        // $nintacu = floatval(preg_replace('/[^\d.]/', '', $tintacu));
        $utilidadref += $nintacu;
        $utilidadintacu[] = $nintacu;
      }


    }else{

    }
  }

   return $utilidadref;
 }

 public function crc_utilidad_referidos_cierre($user) {
   date_default_timezone_set('America/Tijuana');
   $user_data = get_userdata( absint( $user) );
   $invitecode = get_user_meta($user,'invitecode',true);
   if(!$invitecode){
     $invitenum = 'AAAAAA';
   }else{
     $invitenum = $invitecode;
   }
   $referidos = get_users(array(
    'meta_key' => 'referido',
    'meta_value' => $invitenum
  ));

  $idreferidos = array();
  $utilidadintacu = array();
  $utilidadref = 0;

  global $wpdb;
  $tabla = $wpdb->prefix . 'depositos';
  $tabla2 = $wpdb->prefix . 'retiros';
  $tabla3 = $wpdb->prefix . 'mesesinv';

  foreach ($referidos as $key => $value) {
    // $id = $value->ID;
    $user_actual = $value->ID;
    $user_data = get_userdata( absint( $user_actual ) );
    //$invitecode = get_user_meta($user_actual,'invitecode',true);
    $status = get_user_meta($user_actual,'status',true);
    $interes = ((int) $status / 100);

    if ($status != '') {
      $depositos = $wpdb->get_results("SELECT month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidad_real, fecha_cuando, fecha_termino FROM $tabla WHERE usuario = $user_actual AND status = 2 ORDER BY fecha_termino", ARRAY_A);
      $totaldep = $wpdb->get_results("SELECT ROUND(SUM(cantidad_real), 2) AS totaldep FROM $tabla WHERE usuario = $user_actual AND status = 2 ", ARRAY_A);
      $retiros = $wpdb->get_results("SELECT day(fecha_termino) AS dia, month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidadfin, urgente, fecha_cuando FROM $tabla2 WHERE usuario = $user_actual AND status = 2 ORDER BY fecha_termino", ARRAY_A);
      $mesesinv = $wpdb->get_results("SELECT mes, interes FROM $tabla3 WHERE usuario = $user_actual AND status = 1 ORDER BY id", ARRAY_A);
      $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
      '8' => 'Agosto',
      '9' => 'Septiembre',
      '10' => 'Octubre',
      '11' => 'Noviembre',
      '12' => 'Diciembre' );
      if(count($depositos) == 0){
        $nohay = true;
        $tsaldini = 0;
        $tintacu = 0;
        //Necesito el mismo num de tintacu pero en num, a este le vamos a aplicar si es posterior a julio del 2022
        $nintacu = 0;
        $totaldisp = 0;
        $ttotaldep = 0;
      }else {
        $nohay = false;

        $mesuno = $depositos[0]["mes"];
        $agnouno = (int) $depositos[0]["agno"];
        $agno = $agnouno;
        $fechaini = date($depositos[0]["agno"]."-".$depositos[0]["mes"]."-01");
        $inicio = 0;
        $intacu = 0;
        $nintacu = 0;
        $totalacu = 0;
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

        for ($i = 0 ; $i < 12; $i++) {
          $statusmes = $mesesinv[$i]["interes"];
          $intmes = ((int) $statusmes / 100);
          $tmes = $mesesNombre[$mes];
          $strmes = (string) $mes;
          $capprin = $totalacu;
          $capprinstart = 0;
          $retmes = 0;
          $depmes = 0;
          $intacumes = 0;
          //Evaluamos el interes de los retiros de este mes
          if(count($retiros) == 0){
          }else {

            //Filtramos los depositos de este mes
            $retirosmes = [];
            foreach ( $retiros as $k => $v ) {
                    if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                            $retirosmes[$k] = $v;
                    }
            }

            //Verificamos cada retiro por separado
            foreach ($retirosmes as $key => $value) {
              $diames = (int)$value["dia"];
              //Vemos si es urgente o no
              if ($value["urgente"] == 1) {
                if ($diames <= 14) {
                  $intgen = 0;
                  $intacumes = round(($intacumes + $intgen), 2);
                  $intacu = round(($intacu + $intgen), 2);
                  $retmes = round(($retmes + $value["cantidadfin"]), 2);
                } else if ($diames >= 15 && $diames < 30) {
                  $intgen = round((0.05 * $value["cantidadfin"]), 2);
                  $intacumes = round(($intacumes + $intgen), 2);
                  $intacu = round(($intacu + $intgen), 2);
                  $retmes = round(($retmes + $value["cantidadfin"]), 2);
                }else{
                  $intgen = round(($intmes * $value["cantidadfin"]), 2);
                  $intacumes = round(($intacumes + $intgen), 2);
                  $intacu = round(($intacu + $intgen), 2);
                  $retmes = round(($retmes + $value["cantidadfin"]), 2);
                }
              }else{
                //Vemos si es de dia 15 o dia 30
                if ($value["fecha_cuando"] == 1) {
                  $intgen = round((0.05 * $value["cantidadfin"]), 2);
                  $intacumes = round(($intacumes + $intgen), 2);
                  $intacu = round(($intacu + $intgen), 2);
                  $retmes = round(($retmes + $value["cantidadfin"]), 2);


                }else{
                  $intgen = round(($intmes * $value["cantidadfin"]), 2);
                  $intacumes = round(($intacumes + $intgen), 2);
                  $intacu = round(($intacu + $intgen), 2);
                  $retmes = round(($retmes + $value["cantidadfin"]), 2);
                }
              }

            }
          }


          $capprin = round(($capprin - $retmes ), 2);
          $intgencapprin = round(($intmes * $capprin ), 2);



          //Evaluamos el interes de los depositos de este mes
          if(count($depositos) == 0){
          }else {

            //Filtramos los depositos de este mes
            $depositosmes = [];
            foreach ( $depositos as $k => $v ) {
                    if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                            $depositosmes[$k] = $v;
                    }
            }


            $capprin = round(($capprin + $retmes ), 2);

            //Verificamos cada deposito por separado
            foreach ($depositosmes as $key => $value) {

              //Vemos si es de dia 1 o dia 15
              if ($value["fecha_cuando"] == 1) {
                $intgen = round(($intmes * $value["cantidad_real"]), 2);
                $intacumes = round(($intacumes + $intgen), 2);
                $intacu = round(($intacu + $intgen), 2);
                $depmes = round(($depmes + $value["cantidad_real"]), 2);


              }else{
                $intgen = round((0.05 * $value["cantidad_real"]), 2);
                $intacumes = round(($intacumes + $intgen), 2);
                $intacu = round(($intacu + $intgen), 2);
                $depmes = round(($depmes + $value["cantidad_real"]), 2);
              }
            }
          }


          $totalintmes = round(($intgencapprin + $intacumes ), 2);
          $intacu = round(($intgencapprin + $intacu), 2);
          $capprin = round(($capprin + $depmes ), 2);
          // $capprinstart = round(($capprin + $retmes ), 2);
          $capprinstart = $capprin;
          $totalacu =  round(($capprin + $totalintmes ) - $retmes, 2);

          //Evaluamos si este mes es posterior a julio-2022 para sumar la utilidad referida del 1%
          //Aqui hay que meter algo en la condicional para que se detenga en el mes actual
          // if($agno >= 2023){
          //   $porutilmes = round(($totalintmes * 0.01 ), 2);
          //   $nintacu += $porutilmes;
          // }else if ($mes >= 7 && $agno >= 2022) {
          //   $porutilmes = round(($totalintmes * 0.01 ), 2);
          //   $nintacu += $porutilmes;
          // }else {
          //   // code...
          // }

          // echo $meshoy;
          // echo "<br>";
          // echo $mes . "mes";
          // echo "<br>";

          if($totalMonths == 0){
            $tsaldini = number_format($totalacu, 2, '.', ',');
            $tintacu = 0;
            $ttotaldep = number_format($totaldep[0]['totaldep'], 2, '.', ',');
            $totaldisp = 0;
            $i = 12;
            // if($mesinversor == 1 ){
            //   if($agno >= 2023){
            //     $porutilmes = round(($capprinstart * 0.01 ), 2);
            //     $nintacu += $porutilmes;
            //   }else if ($mes >= 7 && $agno >= 2022) {
            //     $porutilmes = round(($capprinstart * 0.01 ), 2);
            //     $nintacu += $porutilmes;
            //   }else {
            //     // code...
            //   }
            // }
          }else{
            if (($i+1) == $totalMonths ) {
              $tsaldini = number_format($totalacu, 2, '.', ',');
              $tintacu = number_format($intacu, 2, '.', ',');
              $totaldisp = $totalacu;
              $ttotaldep = number_format($totaldep[0]['totaldep'], 2, '.', ',');
            }else{

            }

            if(($i+1) <= $mesinversor ){
              if($agno >= 2023){
                if (($agno == 2023 && $mes == 2) || ($agno == 2023 && $mes == 4) || ($agno == 2023 && $mes == 5) || ($agno == 2023 && $mes == 6) || ($agno == 2023 && $mes == 7) ) {
                  $porutilmes = 0;
                  $nintacu += $porutilmes;
                }else{
                  $porutilmes = round(($capprinstart * 0.01 ), 2);
                  $nintacu += $porutilmes;
                }
              }else if ($mes >= 7 && $agno >= 2022) {
                $porutilmes = round(($capprinstart * 0.01 ), 2);
                $nintacu += $porutilmes;
              }else {
                // code...
              }
            }
          }

          if ($mes == 12) {
            $mes = 1;
            $agno++;
          }else{
            $mes++;
          }

        }

        //Volvemos tintacu a float y lo sumamos al total de utilref
        // $nintacu = floatval(preg_replace('/[^\d.]/', '', $tintacu));
        $utilidadref += $nintacu;
        $utilidadintacu[] = $nintacu;
      }


    }else{

    }

  }

   return $utilidadref;
 }

 public function totalcuentas_mensual_hoy() {
   date_default_timezone_set('America/Tijuana');

   // Año y mes actual
   $year = date("Y");
   $month = date("m");
   $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
   '8' => 'Agosto',
   '9' => 'Septiembre',
   '10' => 'Octubre',
   '11' => 'Noviembre',
   '12' => 'Diciembre' );

   // Llamamos a la base de datos para traer todos los balances grals desde el primero
   global $wpdb;
   $usuarios = $wpdb->prefix . 'users';
   $ruta = get_site_url();
   $registros = $wpdb->get_results(" SELECT * FROM $usuarios ORDER BY id DESC", ARRAY_A);

   // Llamamos a los balances grales
   $balances = $wpdb->prefix . 'controlmaster';
   $balregistros = $wpdb->get_results("SELECT * FROM $balances WHERE status = 1 ORDER BY mes, agno ", ARRAY_A);

   //Vemos cuando fue el primer deposito
   $tabdepositos = $wpdb->prefix . 'depositos';
   $primerdepogral = $wpdb->get_results("SELECT month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidad_real, fecha_cuando, fecha_termino FROM $tabdepositos WHERE status = 2 ORDER BY fecha_termino LIMIT 1 ", ARRAY_A);

   if(count($primerdepogral) == 0){
     $primerdeposito = false;
   }else{
     $pdmesuno = $primerdepogral[0]["mes"];
     $pdmes = (int) $pdmesuno;
     $pdagnouno = (int) $primerdepogral[0]["agno"];
     $pdagno = $pdagnouno;
     $pdfechaini = date($primerdepogral[0]["agno"]."-".$primerdepogral[0]["mes"]."-01");

     //calculamos la diferencia de meses
     $pdfechahoy = date("Y-m-d");
     $fechaSeparada = explode("-", $pdfechahoy);
     $meshoy = (int) $fechaSeparada[1];
     $agnohoy = (int) $fechaSeparada[0];

     $fecha1= new DateTime($pdfechaini);
     $fecha2= new DateTime($pdfechahoy);
     $diff = $fecha1->diff($fecha2);

     $yearsInMonths = $diff->format('%r%y') * 12;
     $months = $diff->format('%r%m');
     // $totalMonths = $yearsInMonths + $months;
     $mesesaconsiderar = $yearsInMonths + $months + 1;

     $primerdeposito = true;
   }

   $totinvlm = 0;
   $totinvhoy = 0;
   $totutilref = 0;

   $totalpormes = array();
   $utilidadporuser = array();
   $aportacionporuser = array();

   $totalinvestors = 0;
   $totalprofit = 0;

   $return_json = array();

   if(count($balregistros) == 0){
     $ultbalfinal = 0;
   }else{
     $ultimobal = end($balregistros);
     $ultbalfinal = (float)$ultimobal['balance_final'];
     $totalinvestors = (float)$ultimobal['total_cuentas'];
     $totalprofit = $ultbalfinal - $totalinvestors;
   }

   if(count($registros) == 0){


   }

   // vamos a recorre a todos los usuarios para saber si tienen depositos
   foreach ($registros as $key => $value) {
     $userid = absint( $value["ID"] );
     $user = get_userdata( absint( $value["ID"] ) );
     $nombre = $user->first_name . ' ' .$user->last_name ;

     if ( isset( $user->roles ) && is_array( $user->roles ) ) {
         if ( in_array( 'inversionista', $user->roles ) ) {

           $wallet = get_user_meta( $user->ID, 'wallet', true);
           $walletcode = get_user_meta( $user->ID, 'walletcode', true);
           $email = $user->user_email;
           $pais = get_user_meta( $user->ID, 'pais', true);
           $status = get_user_meta( $user->ID, 'status', true);

           if(!$status){
             $statusc = "--";
             $status = 0;
           }else {
             $statusc = $status."%";
           }

           // En este array vamos a sumar el total que obtuvo el usuario cada mes
           $totalpormesxuser = array();

           // vemos si el usaer esta activo
           $activo = get_user_meta( $user->ID, 'activo', true);

           $interes = ((int) $status / 100);


           $tabla = $wpdb->prefix . 'depositos';
           $tabla2 = $wpdb->prefix . 'retiros';
           $tabla3 = $wpdb->prefix . 'mesesinv';
           $depositos = $wpdb->get_results("SELECT month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidad_real, fecha_cuando, fecha_termino FROM $tabla WHERE usuario = $userid AND status = 2 ORDER BY fecha_termino", ARRAY_A);
           $totaldep = $wpdb->get_results("SELECT ROUND(SUM(cantidad_real), 2) AS totaldep FROM $tabla WHERE usuario = $userid AND status = 2 ", ARRAY_A);
           $retiros = $wpdb->get_results("SELECT day(fecha_termino) AS dia, month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidadfin, urgente, fecha_cuando FROM $tabla2 WHERE usuario = $userid AND status = 2 ORDER BY fecha_termino", ARRAY_A);
           $totalret = $wpdb->get_results("SELECT ROUND(SUM(cantidadfin), 2) AS totalret FROM $tabla2 WHERE usuario = $userid AND status = 2 ", ARRAY_A);
           $mesesinv = $wpdb->get_results("SELECT mes, interes FROM $tabla3 WHERE usuario = $userid AND status = 1 ORDER BY id", ARRAY_A);
           $totalmeses = count($mesesinv);
           $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
           '8' => 'Agosto',
           '9' => 'Septiembre',
           '10' => 'Octubre',
           '11' => 'Noviembre',
           '12' => 'Diciembre' );
           $totalrdep = (float)$totaldep[0]['totaldep'];
           $totalrret = (float)$totalret[0]['totalret'];

           // Si no hay depositos solo tachamos todo en 0
           if(count($depositos) == 0){
             $nohay = true;
             $tsaldini = 0;
             $tintacu = 0;
             $totaldisp = 0;
             $ttotaldep = 0;
             $totalreal = 0;
             $ttotalreal = 0;
             $totalrhoy = 0;
             $totalacu = 0;
             $ttotalrhoy = 0;
           }else {
             $nohay = false;

             // $mesuno = $depositos[0]["mes"];
             // $agnouno = (int) $depositos[0]["agno"];
             // $agno = $agnouno;
             // $fechaini = date($depositos[0]["agno"]."-".$depositos[0]["mes"]."-01");
             // $inicio = 0;
             // $intacu = 0;
             // $totalacu = 0;
             // $mes = (int) $mesuno;
             $mesuno = $depositos[0]["mes"];
             $agnouno = (int) $depositos[0]["agno"];
             $agno = $agnouno;
             $fechaini = date($depositos[0]["agno"]."-".$depositos[0]["mes"]."-01");
             $inicio = 0;
             $intacu = 0;
             $totalacu = 0;
             $mes = (int) $mesuno;
             $contorden = 13;

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
             $mesesquedan = $totalmeses - $totalMonths;


             if ($totalMonths < 1 ) {
               $mesesprint = 12;
             }else{
               if($mesesquedan < 12){
                 $mesesprint = $totalMonths + $mesesquedan;
               }else {
                 $mesesprint = $totalMonths + 11;
               }

             }



             for ($i = 0 ; $i < $mesinversor ; $i++) {

               // Si el numero de meses que han pasado es mayor que los que tiene autorizados, ya no genera interes alguno futuro
               if ($totalmeses < $i+1) {
                 $statusmes = 0;
               }else {
                 $statusmes = $mesesinv[$i]["interes"];
               }

               // Si ya no hay status de inversiones, el intmes pasa a ser 0
               if ($statusmes) {
                 $intmes = ((int) $statusmes / 100);
               }else{
                 $intmes = 0;
               }

               $tmes = $mesesNombre[$mes];
               $strmes = (string) $mes;
               $capprin = $totalacu;
               $retmes = 0;
               $depmes = 0;
               $intacumes = 0;

               // Si ya no hay int mes, la utilidad se detiene para ese inversor y se vuelve 0 en cualquier tipo de deposito o retiro.
               if ($statusmes) {

                 //Evaluamos el interes de los retiros de este mes
                 if(count($retiros) == 0){
                 }else {

                   //Filtramos los depositos de este mes
                   $retirosmes = [];
                   foreach ( $retiros as $k => $v ) {
                           if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                                   $retirosmes[$k] = $v;
                           }
                   }

                   //Verificamos cada retiro por separado
                   foreach ($retirosmes as $key => $value) {
                     $diames = (int)$value["dia"];
                     //Vemos si es urgente o no
                     if ($value["urgente"] == 1) {
                       if ($diames <= 14) {
                         $intgen = 0;
                         $intacumes = round(($intacumes + $intgen), 2);
                         $intacu = round(($intacu + $intgen), 2);
                         $retmes = round(($retmes + $value["cantidadfin"]), 2);
                       } else if ($diames >= 15 && $diames < 30) {
                         $intgen = round((0.05 * $value["cantidadfin"]), 2);
                         $intacumes = round(($intacumes + $intgen), 2);
                         $intacu = round(($intacu + $intgen), 2);
                         $retmes = round(($retmes + $value["cantidadfin"]), 2);
                       }else{
                         $intgen = round(($intmes * $value["cantidadfin"]), 2);
                         $intacumes = round(($intacumes + $intgen), 2);
                         $intacu = round(($intacu + $intgen), 2);
                         $retmes = round(($retmes + $value["cantidadfin"]), 2);
                       }
                     }else{
                       //Vemos si es de dia 15 o dia 30
                       if ($value["fecha_cuando"] == 1) {
                         $intgen = round((0.05 * $value["cantidadfin"]), 2);
                         $intacumes = round(($intacumes + $intgen), 2);
                         $intacu = round(($intacu + $intgen), 2);
                         $retmes = round(($retmes + $value["cantidadfin"]), 2);


                       }else{
                         $intgen = round(($intmes * $value["cantidadfin"]), 2);
                         $intacumes = round(($intacumes + $intgen), 2);
                         $intacu = round(($intacu + $intgen), 2);
                         $retmes = round(($retmes + $value["cantidadfin"]), 2);
                       }
                     }
                   }
                 }

                 $capprin = round(($capprin - $retmes ), 2);
                 $intgencapprin = round(($intmes * $capprin ), 2);



                 //Evaluamos el interes de los depositos de este mes
                 if(count($depositos) == 0){
                 }else {

                   //Filtramos los depositos de este mes
                   $depositosmes = [];
                   foreach ( $depositos as $k => $v ) {
                           if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                                   $depositosmes[$k] = $v;
                           }
                   }


                   $capprin = round(($capprin + $retmes ), 2);

                   //Verificamos cada deposito por separado
                   foreach ($depositosmes as $key => $value) {

                     //Vemos si es de dia 1 o dia 15
                     if ($value["fecha_cuando"] == 1) {
                       $intgen = round(($intmes * $value["cantidad_real"]), 2);
                       $intacumes = round(($intacumes + $intgen), 2);
                       $intacu = round(($intacu + $intgen), 2);
                       $depmes = round(($depmes + $value["cantidad_real"]), 2);


                     }else{
                       $intgen = round((0.05 * $value["cantidad_real"]), 2);
                       $intacumes = round(($intacumes + $intgen), 2);
                       $intacu = round(($intacu + $intgen), 2);
                       $depmes = round(($depmes + $value["cantidad_real"]), 2);
                     }
                   }
                 }


                 $totalintmes = round(($intgencapprin + $intacumes ), 2);
                 $intacu = round(($intgencapprin + $intacu), 2);
                 $capprin = round(($capprin + $depmes ), 2);
                 $totalacu =  round(($capprin + $totalintmes ) - $retmes, 2);

                 /*echo $meshoy;
                 echo "<br>";
                 echo $mes . "mes";
                 echo "<br>";*/

                 if (($i+1) <= $totalMonths ) {
                  }else {
                  }

                 // Antes de avanzar al siguiente mes vamos a capturar ese total generado por mes en un array
                 $totalpormesxuser[] = array(
                     'mes'=>$mes,
                     'tmes'=> $tmes,
                     'year'=> $agno,
                     'total'=>$totalacu,
                     'utilacumulada'=>$intacu);

                 if ($mes == 12) {
                   $mes = 1;
                   $agno++;
                 }else{
                   $mes++;
                 }

                 if($mesesquedan < 11){
                   // checamos diferencia para saber con cuanto armo 12 mesesin
                   $difmeses = 12 - $mesesquedan;

                   if (($i+1) == $totalMonths-$difmeses ) {
                     $contorden = 1;
                   }else {
                     $contorden++;
                   }
                 }else{
                   if (($i+1) == $totalMonths-1 ) {
                     $contorden = 1;
                   }else {
                     $contorden++;
                   }
                 }

               }else{

                 //Evaluamos el interes de los retiros de este mes
                 if(count($retiros) == 0){
                 }else {

                   //Filtramos los depositos de este mes
                   $retirosmes = [];
                   foreach ( $retiros as $k => $v ) {
                           if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                                   $retirosmes[$k] = $v;
                           }
                   }

                   //Verificamos cada retiro por separado
                   foreach ($retirosmes as $key => $value) {
                     $diames = (int)$value["dia"];
                     //Vemos si es urgente o no
                     if ($value["urgente"] == 1) {
                       if ($diames <= 14) {
                         $intgen = 0;
                         $intacumes = round(($intacumes + $intgen), 2);
                         $intacu = round(($intacu + $intgen), 2);
                         $retmes = round(($retmes + $value["cantidadfin"]), 2);
                       } else if ($diames >= 15 && $diames < 30) {
                         $intgen = round((0 * $value["cantidadfin"]), 2);
                         $intacumes = round(($intacumes + $intgen), 2);
                         $intacu = round(($intacu + $intgen), 2);
                         $retmes = round(($retmes + $value["cantidadfin"]), 2);
                       }else{
                         $intgen = round(($intmes * $value["cantidadfin"]), 2);
                         $intacumes = round(($intacumes + $intgen), 2);
                         $intacu = round(($intacu + $intgen), 2);
                         $retmes = round(($retmes + $value["cantidadfin"]), 2);
                       }
                     }else{
                       //Vemos si es de dia 15 o dia 30
                       if ($value["fecha_cuando"] == 1) {
                         $intgen = round((0 * $value["cantidadfin"]), 2);
                         $intacumes = round(($intacumes + $intgen), 2);
                         $intacu = round(($intacu + $intgen), 2);
                         $retmes = round(($retmes + $value["cantidadfin"]), 2);


                       }else{
                         $intgen = round(($intmes * $value["cantidadfin"]), 2);
                         $intacumes = round(($intacumes + $intgen), 2);
                         $intacu = round(($intacu + $intgen), 2);
                         $retmes = round(($retmes + $value["cantidadfin"]), 2);
                       }
                     }
                   }
                 }

                 $capprin = round(($capprin - $retmes ), 2);
                 $intgencapprin = round(($intmes * $capprin ), 2);



                 //Evaluamos el interes de los depositos de este mes
                 if(count($depositos) == 0){
                 }else {

                   //Filtramos los depositos de este mes
                   $depositosmes = [];
                   foreach ( $depositos as $k => $v ) {
                           if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                                   $depositosmes[$k] = $v;
                           }
                   }


                   $capprin = round(($capprin + $retmes ), 2);

                   //Verificamos cada deposito por separado
                   foreach ($depositosmes as $key => $value) {

                     //Vemos si es de dia 1 o dia 15
                     if ($value["fecha_cuando"] == 1) {
                       $intgen = round(($intmes * $value["cantidad_real"]), 2);
                       $intacumes = round(($intacumes + $intgen), 2);
                       $intacu = round(($intacu + $intgen), 2);
                       $depmes = round(($depmes + $value["cantidad_real"]), 2);


                     }else{
                       $intgen = round((0 * $value["cantidad_real"]), 2);
                       $intacumes = round(($intacumes + $intgen), 2);
                       $intacu = round(($intacu + $intgen), 2);
                       $depmes = round(($depmes + $value["cantidad_real"]), 2);
                     }
                   }
                 }


                 $totalintmes = round(($intgencapprin + $intacumes ), 2);
                 $intacu = round(($intgencapprin + $intacu), 2);
                 $capprin = round(($capprin + $depmes ), 2);
                 $totalacu =  round(($capprin + $totalintmes ) - $retmes, 2);

                 /*echo $meshoy;
                 echo "<br>";
                 echo $mes . "mes";
                 echo "<br>";*/

                 if (($i+1) <= $totalMonths ) {
                  }else {
                  }

                 // Antes de avanzar al siguiente mes vamos a capturar ese total generado por mes en un array
                 $totalpormesxuser[] = array(
                     'mes'=>$mes,
                     'tmes'=> $tmes,
                     'year'=> $agno,
                     'total'=>$totalacu,
                     'utilacumulada'=>$intacu);

                 if ($mes == 12) {
                   $mes = 1;
                   $agno++;
                 }else{
                   $mes++;
                 }

                 if($mesesquedan < 11){
                   // checamos diferencia para saber con cuanto armo 12 mesesin
                   $difmeses = 12 - $mesesquedan;

                   if (($i+1) == $totalMonths-$difmeses ) {
                     $contorden = 1;
                   }else {
                     $contorden++;
                   }
                 }else{
                   if (($i+1) == $totalMonths-1 ) {
                     $contorden = 1;
                   }else {
                     $contorden++;
                   }
                 }

               }

             }

             // $totalpormes[] = array(
             //   'id' => , );

             // for ($i = 0 ; $i < 12; $i++) {
             //   $statusmes = $mesesinv[$i]["interes"];
             //   $intmes = ((int) $statusmes / 100);
             //   $tmes = $mesesNombre[$mes];
             //   $strmes = (string) $mes;
             //   $capprin = $totalacu;
             //   $retmes = 0;
             //   $depmes = 0;
             //   $intacumes = 0;
             //   //Evaluamos el interes de los retiros de este mes
             //   if(count($retiros) == 0){
             //   }else {
             //
             //     //Filtramos los depositos de este mes
             //     $retirosmes = [];
             //     foreach ( $retiros as $k => $v ) {
             //             if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
             //                     $retirosmes[$k] = $v;
             //             }
             //     }
             //
             //     //Verificamos cada retiro por separado
             //     foreach ($retirosmes as $key => $value) {
             //       $diames = (int)$value["dia"];
             //       //Vemos si es urgente o no
             //       if ($value["urgente"] == 1) {
             //         if ($diames <= 14) {
             //           $intgen = 0;
             //           $intacumes = round(($intacumes + $intgen), 2);
             //           $intacu = round(($intacu + $intgen), 2);
             //           $retmes = round(($retmes + $value["cantidadfin"]), 2);
             //         } else if ($diames >= 15 && $diames < 30) {
             //           $intgen = round((0.05 * $value["cantidadfin"]), 2);
             //           $intacumes = round(($intacumes + $intgen), 2);
             //           $intacu = round(($intacu + $intgen), 2);
             //           $retmes = round(($retmes + $value["cantidadfin"]), 2);
             //         }else{
             //           $intgen = round(($intmes * $value["cantidadfin"]), 2);
             //           $intacumes = round(($intacumes + $intgen), 2);
             //           $intacu = round(($intacu + $intgen), 2);
             //           $retmes = round(($retmes + $value["cantidadfin"]), 2);
             //         }
             //       }else{
             //         //Vemos si es de dia 15 o dia 30
             //         if ($value["fecha_cuando"] == 1) {
             //           $intgen = round((0.05 * $value["cantidadfin"]), 2);
             //           $intacumes = round(($intacumes + $intgen), 2);
             //           $intacu = round(($intacu + $intgen), 2);
             //           $retmes = round(($retmes + $value["cantidadfin"]), 2);
             //
             //
             //         }else{
             //           $intgen = round(($intmes * $value["cantidadfin"]), 2);
             //           $intacumes = round(($intacumes + $intgen), 2);
             //           $intacu = round(($intacu + $intgen), 2);
             //           $retmes = round(($retmes + $value["cantidadfin"]), 2);
             //         }
             //       }
             //     }
             //   }
             //
             //   $capprin = round(($capprin - $retmes ), 2);
             //   $intgencapprin = round(($intmes * $capprin ), 2);
             //
             //   //Evaluamos el interes de los depositos de este mes
             //   if(count($depositos) == 0){
             //   }else {
             //
             //     //Filtramos los depositos de este mes
             //     $depositosmes = [];
             //     foreach ( $depositos as $k => $v ) {
             //             if ( $v['mes'] == $strmes &&  $v['agno'] == $agno ) {
             //                     $depositosmes[$k] = $v;
             //             }
             //     }
             //
             //
             //     $capprin = round(($capprin + $retmes ), 2);
             //
             //     //Verificamos cada deposito por separado
             //     foreach ($depositosmes as $key => $value) {
             //
             //       //Vemos si es de dia 1 o dia 15
             //       if ($value["fecha_cuando"] == 1) {
             //         $intgen = round(($intmes * $value["cantidad_real"]), 2);
             //         $intacumes = round(($intacumes + $intgen), 2);
             //         $intacu = round(($intacu + $intgen), 2);
             //         $depmes = round(($depmes + $value["cantidad_real"]), 2);
             //
             //
             //       }else{
             //         $intgen = round((0.05 * $value["cantidad_real"]), 2);
             //         $intacumes = round(($intacumes + $intgen), 2);
             //         $intacu = round(($intacu + $intgen), 2);
             //         $depmes = round(($depmes + $value["cantidad_real"]), 2);
             //       }
             //     }
             //   }
             //
             //
             //   $totalintmes = round(($intgencapprin + $intacumes ), 2);
             //   $intacu = round(($intgencapprin + $intacu), 2);
             //   $capprin = round(($capprin + $depmes ), 2);
             //   $totalacu =  round(($capprin + $totalintmes ) - $retmes, 2);
             //
             //   // Si los meses son iguales a 0 el inv no ha generado nada
             //   if($totalMonths == 0){
             //     $tsaldini = number_format($totalacu, 2, '.', ',');
             //     $tintacu = 0;
             //     $ttotaldep = number_format($totaldep[0]['totaldep'], 2, '.', ',');
             //     $totaldisp = 0;
             //
             //     //Evaluamos si es un mes futuro en el tiempo.
             //     if($fecha2 < $fecha1){
             //       $totalreal = 0;
             //       $ttotalreal = number_format($totalreal, 2, '.', ',');
             //       $totalrhoy = round((0 + $totalrdep)-$totalrret, 2);
             //       $ttotalrhoy = number_format($totalrhoy, 2, '.', ',');
             //     }else{
             //       $totalreal = 0;
             //       $ttotalreal = number_format($totalreal, 2, '.', ',');
             //       $totalrhoy = round(($intacu + $totalrdep)-$totalrret, 2);
             //       $ttotalrhoy = number_format($totalrhoy, 2, '.', ',');
             //     }
             //     $i = 12;
             //
             //   }else{
             //     if (($i+1) == $totalMonths ) {
             //       $tsaldini = number_format($totalacu, 2, '.', ',');
             //       $tintacu = number_format($intacu, 2, '.', ',');
             //       $totaldisp = $totalacu;
             //       $ttotaldep = number_format($totaldep[0]['totaldep'], 2, '.', ',');
             //     }else{
             //
             //     }
             //
             //
             //     if($totalMonths > 11){
             //       //cuando son +12  el realhoy es el ultimo y realmespasado son iguales
             //       $totalrhoy = round(($intacu + $totalrdep)-$totalrret, 2);
             //       $ttotalrhoy = number_format($totalrhoy, 2, '.', ',');
             //       $totalreal = round(($intacu + $totalrdep) - $totalrret, 2);
             //       $ttotalreal = number_format($totalreal, 2, '.', ',');
             //     }else if($totalMonths == 11){
             //
             //       //cuando son 12 meses  el realhoy es el ultimo y realmespasado el del mes pasado
             //       $totalrhoy = round(($intacu + $totalrdep)-$totalrret, 2);
             //       $ttotalrhoy = number_format($totalrhoy, 2, '.', ',');
             //       if (($i+1) == 11) {
             //         $totalreal = round(($intacu + $totalrdep) - $totalrret, 2);
             //         $ttotalreal = number_format($totalreal, 2, '.', ',');
             //       }
             //     }else if(($i+1) == ($totalMonths+1)){
             //       $totalrhoy = round(($intacu + $totalrdep)-$totalrret, 2);
             //       $ttotalrhoy = number_format($totalrhoy, 2, '.', ',');
             //     }else if(($i+1) == ($totalMonths)){
             //       $totalreal = round(($intacu + $totalrdep) - $totalrret, 2);
             //       $ttotalreal = number_format($totalreal, 2, '.', ',');
             //     }else{
             //
             //     }
             //
             //   }
             //
             //   // Antes de avanzar al siguiente mes vamos a capturar ese total generado por mes en un array
             //   $totalpormes[][] = array(
             //       'mes'=> $mes,
             //       'year'=> $agno,
             //       'total'=>$totalreal );
             //
             //   if ($mes == 12) {
             //     $mes = 1;
             //     $agno++;
             //   }else{
             //     $mes++;
             //   }
             //
             // }

           }

           // Ahora calculamos la utilidad que le han dado sus referidos
           // $calculos = new CRC_Calculo();
           // $utilidadref = $calculos->crc_utilidad_referidos_cierre($userid);

           // En un array vamos a poner cuanta utilidad de referido trajo al final cada usuairo si solo queremos saber eso
           // $utilidadporuser[] = array(
           //     'id'=> $userid,
           //     'utilidad'=> $utilidadref);

           //Vamos a llenar un array para ver cuanto esta aportando cada inversor al final del mes:
           //ESTO ES LO QUE ME DICE CUANTO SE CERRO EL USUARIO POR MES
           $aportacionporuser[] = array(
               'id'=> $userid,
               'nombre'=>$nombre,
               'totalxuserxmes'=> $totalpormesxuser);

           // $totutilref = $totutilref + $utilidadref;
           // $totinvlm = $totinvlm + $totalreal;
           // $totinvhoy = $totinvhoy + $totalrhoy ;
         }
       }


   }

   $resultado = null;
   if($primerdeposito){

     for ($i=0; $i < $mesesaconsiderar ; $i++) {

       $tmes = $mesesNombre[$pdmes];
       $totalcierremes = 0;
       $totalutilacummes = 0;

       foreach ($aportacionporuser as $key => $usuario) {
         if (count($usuario['totalxuserxmes']) > 0) {

           // Vamos a recorrer los totales por mes del usuario para ver si alguno corresponde al mes en el que vamos segun el bucle for
            foreach ($usuario['totalxuserxmes'] as $llave => $usermes ) {
              // lo convertimos a decimal para que no haya problema
              $tusermes = (float)$usermes['total'];
              $tutilacummes = (float)$usermes['utilacumulada'];

              if ($usermes['mes'] == $pdmes && $usermes['year'] == $pdagno ) {
                $totalcierremes = $totalcierremes+$tusermes ;
                $totalutilacummes = $totalutilacummes+$tutilacummes ;
              }
            }

         }
       }

       $totalpormes[] = array(
         'mes'=>$pdmes,
         'tmes'=> $tmes,
         'year'=> $pdagno,
         'total'=> round($totalcierremes, 2),
         'utilacumulada' => round($totalutilacummes, 2) );

         if ($pdmes == 12) {
           $pdmes = 1;
           $pdagno++;
         }else{
           $pdmes++;
         }

     }

     // Lo que vamos a imprimir es el array totalpormes, porque si hay deposito primero en el sistema ya no es null
     $resultado = $totalpormes;

   }

   // Si lo quiero ver desglosado por user por mes
   // return  $aportacionporuser;
   return $resultado;
 }

 public function totalcuentas_mensual_hoy_detalle() {
   date_default_timezone_set('America/Tijuana');

   // Año y mes actual
   $year = date("Y");
   $month = date("m");
   $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
   '8' => 'Agosto',
   '9' => 'Septiembre',
   '10' => 'Octubre',
   '11' => 'Noviembre',
   '12' => 'Diciembre' );

   // Llamamos a la base de datos para traer todos los balances grals desde el primero
   global $wpdb;
   $usuarios = $wpdb->prefix . 'users';
   $ruta = get_site_url();
   $registros = $wpdb->get_results(" SELECT * FROM $usuarios ORDER BY id DESC", ARRAY_A);

   // Llamamos a los balances grales
   $balances = $wpdb->prefix . 'controlmaster';
   $balregistros = $wpdb->get_results("SELECT * FROM $balances WHERE status = 1 ORDER BY mes, agno ", ARRAY_A);

   //Vemos cuando fue el primer deposito
   $tabdepositos = $wpdb->prefix . 'depositos';
   $primerdepogral = $wpdb->get_results("SELECT month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidad_real, fecha_cuando, fecha_termino FROM $tabdepositos WHERE status = 2 ORDER BY fecha_termino LIMIT 1 ", ARRAY_A);

   if(count($primerdepogral) == 0){
     $primerdeposito = false;
   }else{
     $pdmesuno = $primerdepogral[0]["mes"];
     $pdmes = (int) $pdmesuno;
     $pdagnouno = (int) $primerdepogral[0]["agno"];
     $pdagno = $pdagnouno;
     $pdfechaini = date($primerdepogral[0]["agno"]."-".$primerdepogral[0]["mes"]."-01");

     //calculamos la diferencia de meses
     $pdfechahoy = date("Y-m-d");
     $fechaSeparada = explode("-", $pdfechahoy);
     $meshoy = (int) $fechaSeparada[1];
     $agnohoy = (int) $fechaSeparada[0];

     $fecha1= new DateTime($pdfechaini);
     $fecha2= new DateTime($pdfechahoy);
     $diff = $fecha1->diff($fecha2);

     $yearsInMonths = $diff->format('%r%y') * 12;
     $months = $diff->format('%r%m');
     // $totalMonths = $yearsInMonths + $months;
     $mesesaconsiderar = $yearsInMonths + $months + 1;

     $primerdeposito = true;
   }

   $totinvlm = 0;
   $totinvhoy = 0;
   $totutilref = 0;

   $totalpormes = array();
   $utilidadporuser = array();
   $aportacionporuser = array();

   $totalinvestors = 0;
   $totalprofit = 0;

   $return_json = array();

   if(count($balregistros) == 0){
     $ultbalfinal = 0;
   }else{
     $ultimobal = end($balregistros);
     $ultbalfinal = (float)$ultimobal['balance_final'];
     $totalinvestors = (float)$ultimobal['total_cuentas'];
     $totalprofit = $ultbalfinal - $totalinvestors;
   }

   if(count($registros) == 0){


   }

   // vamos a recorre a todos los usuarios para saber si tienen depositos
   foreach ($registros as $key => $value) {
     $userid = absint( $value["ID"] );
     $user = get_userdata( absint( $value["ID"] ) );
     $nombre = $user->first_name . ' ' .$user->last_name ;

     if ( isset( $user->roles ) && is_array( $user->roles ) ) {
         if ( in_array( 'inversionista', $user->roles ) ) {

           $wallet = get_user_meta( $user->ID, 'wallet', true);
           $walletcode = get_user_meta( $user->ID, 'walletcode', true);
           $email = $user->user_email;
           $pais = get_user_meta( $user->ID, 'pais', true);
           $status = get_user_meta( $user->ID, 'status', true);

           if(!$status){
             $statusc = "--";
             $status = 0;
           }else {
             $statusc = $status."%";
           }

           // En este array vamos a sumar el total que obtuvo el usuario cada mes
           $totalpormesxuser = array();

           // vemos si el usaer esta activo
           $activo = get_user_meta( $user->ID, 'activo', true);

           $interes = ((int) $status / 100);


           $tabla = $wpdb->prefix . 'depositos';
           $tabla2 = $wpdb->prefix . 'retiros';
           $tabla3 = $wpdb->prefix . 'mesesinv';
           $depositos = $wpdb->get_results("SELECT month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidad_real, fecha_cuando, fecha_termino FROM $tabla WHERE usuario = $userid AND status = 2 ORDER BY fecha_termino", ARRAY_A);
           $totaldep = $wpdb->get_results("SELECT ROUND(SUM(cantidad_real), 2) AS totaldep FROM $tabla WHERE usuario = $userid AND status = 2 ", ARRAY_A);
           $retiros = $wpdb->get_results("SELECT day(fecha_termino) AS dia, month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidadfin, urgente, fecha_cuando FROM $tabla2 WHERE usuario = $userid AND status = 2 ORDER BY fecha_termino", ARRAY_A);
           $totalret = $wpdb->get_results("SELECT ROUND(SUM(cantidadfin), 2) AS totalret FROM $tabla2 WHERE usuario = $userid AND status = 2 ", ARRAY_A);
           $mesesinv = $wpdb->get_results("SELECT mes, interes FROM $tabla3 WHERE usuario = $userid AND status = 1 ORDER BY id", ARRAY_A);
           $totalmeses = count($mesesinv);
           $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
           '8' => 'Agosto',
           '9' => 'Septiembre',
           '10' => 'Octubre',
           '11' => 'Noviembre',
           '12' => 'Diciembre' );
           $totalrdep = (float)$totaldep[0]['totaldep'];
           $totalrret = (float)$totalret[0]['totalret'];

           // Si no hay depositos solo tachamos todo en 0
           if(count($depositos) == 0){
             $nohay = true;
             $tsaldini = 0;
             $tintacu = 0;
             $totaldisp = 0;
             $ttotaldep = 0;
             $totalreal = 0;
             $ttotalreal = 0;
             $totalrhoy = 0;
             $totalacu = 0;
             $ttotalrhoy = 0;
           }else {
             $nohay = false;

             // $mesuno = $depositos[0]["mes"];
             // $agnouno = (int) $depositos[0]["agno"];
             // $agno = $agnouno;
             // $fechaini = date($depositos[0]["agno"]."-".$depositos[0]["mes"]."-01");
             // $inicio = 0;
             // $intacu = 0;
             // $totalacu = 0;
             // $mes = (int) $mesuno;
             $mesuno = $depositos[0]["mes"];
             $agnouno = (int) $depositos[0]["agno"];
             $agno = $agnouno;
             $fechaini = date($depositos[0]["agno"]."-".$depositos[0]["mes"]."-01");
             $inicio = 0;
             $intacu = 0;
             $totalacu = 0;
             $mes = (int) $mesuno;
             $contorden = 13;

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
             $mesesquedan = $totalmeses - $totalMonths;


             if ($totalMonths < 1 ) {
               $mesesprint = 12;
             }else{
               if($mesesquedan < 12){
                 $mesesprint = $totalMonths + $mesesquedan;
               }else {
                 $mesesprint = $totalMonths + 11;
               }

             }



             for ($i = 0 ; $i < $mesinversor ; $i++) {

               // Si el numero de meses que han pasado es mayor que los que tiene autorizados, ya no genera interes alguno futuro
               if ($totalmeses < $i+1) {
                 $statusmes = 0;
               }else {
                 $statusmes = $mesesinv[$i]["interes"];
               }

               // Si ya no hay status de inversiones, el intmes pasa a ser 0
               if ($statusmes) {
                 $intmes = ((int) $statusmes / 100);
               }else{
                 $intmes = 0;
               }

               $tmes = $mesesNombre[$mes];
               $strmes = (string) $mes;
               $capprin = $totalacu;
               $retmes = 0;
               $depmes = 0;
               $intacumes = 0;

               // Si ya no hay int mes, la utilidad se detiene para ese inversor y se vuelve 0 en cualquier tipo de deposito o retiro.
               if ($statusmes) {

                 //Evaluamos el interes de los retiros de este mes
                 if(count($retiros) == 0){
                 }else {

                   //Filtramos los depositos de este mes
                   $retirosmes = [];
                   foreach ( $retiros as $k => $v ) {
                           if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                                   $retirosmes[$k] = $v;
                           }
                   }

                   //Verificamos cada retiro por separado
                   foreach ($retirosmes as $key => $value) {
                     $diames = (int)$value["dia"];
                     //Vemos si es urgente o no
                     if ($value["urgente"] == 1) {
                       if ($diames <= 14) {
                         $intgen = 0;
                         $intacumes = round(($intacumes + $intgen), 2);
                         $intacu = round(($intacu + $intgen), 2);
                         $retmes = round(($retmes + $value["cantidadfin"]), 2);
                       } else if ($diames >= 15 && $diames < 30) {
                         $intgen = round((0.05 * $value["cantidadfin"]), 2);
                         $intacumes = round(($intacumes + $intgen), 2);
                         $intacu = round(($intacu + $intgen), 2);
                         $retmes = round(($retmes + $value["cantidadfin"]), 2);
                       }else{
                         $intgen = round(($intmes * $value["cantidadfin"]), 2);
                         $intacumes = round(($intacumes + $intgen), 2);
                         $intacu = round(($intacu + $intgen), 2);
                         $retmes = round(($retmes + $value["cantidadfin"]), 2);
                       }
                     }else{
                       //Vemos si es de dia 15 o dia 30
                       if ($value["fecha_cuando"] == 1) {
                         $intgen = round((0.05 * $value["cantidadfin"]), 2);
                         $intacumes = round(($intacumes + $intgen), 2);
                         $intacu = round(($intacu + $intgen), 2);
                         $retmes = round(($retmes + $value["cantidadfin"]), 2);


                       }else{
                         $intgen = round(($intmes * $value["cantidadfin"]), 2);
                         $intacumes = round(($intacumes + $intgen), 2);
                         $intacu = round(($intacu + $intgen), 2);
                         $retmes = round(($retmes + $value["cantidadfin"]), 2);
                       }
                     }
                   }
                 }

                 $capprin = round(($capprin - $retmes ), 2);
                 $intgencapprin = round(($intmes * $capprin ), 2);



                 //Evaluamos el interes de los depositos de este mes
                 if(count($depositos) == 0){
                 }else {

                   //Filtramos los depositos de este mes
                   $depositosmes = [];
                   foreach ( $depositos as $k => $v ) {
                           if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                                   $depositosmes[$k] = $v;
                           }
                   }


                   $capprin = round(($capprin + $retmes ), 2);

                   //Verificamos cada deposito por separado
                   foreach ($depositosmes as $key => $value) {

                     //Vemos si es de dia 1 o dia 15
                     if ($value["fecha_cuando"] == 1) {
                       $intgen = round(($intmes * $value["cantidad_real"]), 2);
                       $intacumes = round(($intacumes + $intgen), 2);
                       $intacu = round(($intacu + $intgen), 2);
                       $depmes = round(($depmes + $value["cantidad_real"]), 2);


                     }else{
                       $intgen = round((0.05 * $value["cantidad_real"]), 2);
                       $intacumes = round(($intacumes + $intgen), 2);
                       $intacu = round(($intacu + $intgen), 2);
                       $depmes = round(($depmes + $value["cantidad_real"]), 2);
                     }
                   }
                 }


                 $totalintmes = round(($intgencapprin + $intacumes ), 2);
                 $intacu = round(($intgencapprin + $intacu), 2);
                 $capprin = round(($capprin + $depmes ), 2);
                 $totalacu =  round(($capprin + $totalintmes ) - $retmes, 2);
                 // $botdep = '<span class="verde btn-ver-dep" data-userid="'.$user->ID.'" data-mes="'.$mes.'" data-agno="'.$agno.'">+ $'.number_format($depmes, 2, '.', ',').'</span>';
                 // $botret = '<span class="rojo btn-ver-ret" data-userid="'.$user->ID.'" data-mes="'.$mes.'" data-agno="'.$agno.'">'.'- $'.number_format($retmes, 2, '.', ',').'</span>';
                 $botdep = $depmes;
                 $botret = $retmes;
                 /*echo $meshoy;
                 echo "<br>";
                 echo $mes . "mes";
                 echo "<br>";*/

                 if (($i+1) <= $totalMonths ) {
                  }else {
                  }

                 // Antes de avanzar al siguiente mes vamos a capturar ese total generado por mes en un array
                 $totalpormesxuser[] = array(
                     'mes'=>$mes,
                     'tmes'=> $tmes,
                     'year'=> $agno,
                     'capini'=>$capprin,
                     'total'=>$totalacu,
                     'utilidad'=>$totalintmes,
                     'utilacumulada'=>$intacu,
                     'depmes'=> $botdep,
                     'retmes'=> $botret
                   );

                 if ($mes == 12) {
                   $mes = 1;
                   $agno++;
                 }else{
                   $mes++;
                 }

                 if($mesesquedan < 11){
                   // checamos diferencia para saber con cuanto armo 12 mesesin
                   $difmeses = 12 - $mesesquedan;

                   if (($i+1) == $totalMonths-$difmeses ) {
                     $contorden = 1;
                   }else {
                     $contorden++;
                   }
                 }else{
                   if (($i+1) == $totalMonths-1 ) {
                     $contorden = 1;
                   }else {
                     $contorden++;
                   }
                 }

               }else{

                 //Evaluamos el interes de los retiros de este mes
                 if(count($retiros) == 0){
                 }else {

                   //Filtramos los depositos de este mes
                   $retirosmes = [];
                   foreach ( $retiros as $k => $v ) {
                           if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                                   $retirosmes[$k] = $v;
                           }
                   }

                   //Verificamos cada retiro por separado
                   foreach ($retirosmes as $key => $value) {
                     $diames = (int)$value["dia"];
                     //Vemos si es urgente o no
                     if ($value["urgente"] == 1) {
                       if ($diames <= 14) {
                         $intgen = 0;
                         $intacumes = round(($intacumes + $intgen), 2);
                         $intacu = round(($intacu + $intgen), 2);
                         $retmes = round(($retmes + $value["cantidadfin"]), 2);
                       } else if ($diames >= 15 && $diames < 30) {
                         $intgen = round((0 * $value["cantidadfin"]), 2);
                         $intacumes = round(($intacumes + $intgen), 2);
                         $intacu = round(($intacu + $intgen), 2);
                         $retmes = round(($retmes + $value["cantidadfin"]), 2);
                       }else{
                         $intgen = round(($intmes * $value["cantidadfin"]), 2);
                         $intacumes = round(($intacumes + $intgen), 2);
                         $intacu = round(($intacu + $intgen), 2);
                         $retmes = round(($retmes + $value["cantidadfin"]), 2);
                       }
                     }else{
                       //Vemos si es de dia 15 o dia 30
                       if ($value["fecha_cuando"] == 1) {
                         $intgen = round((0 * $value["cantidadfin"]), 2);
                         $intacumes = round(($intacumes + $intgen), 2);
                         $intacu = round(($intacu + $intgen), 2);
                         $retmes = round(($retmes + $value["cantidadfin"]), 2);


                       }else{
                         $intgen = round(($intmes * $value["cantidadfin"]), 2);
                         $intacumes = round(($intacumes + $intgen), 2);
                         $intacu = round(($intacu + $intgen), 2);
                         $retmes = round(($retmes + $value["cantidadfin"]), 2);
                       }
                     }
                   }
                 }

                 $capprin = round(($capprin - $retmes ), 2);
                 $intgencapprin = round(($intmes * $capprin ), 2);



                 //Evaluamos el interes de los depositos de este mes
                 if(count($depositos) == 0){
                 }else {

                   //Filtramos los depositos de este mes
                   $depositosmes = [];
                   foreach ( $depositos as $k => $v ) {
                           if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                                   $depositosmes[$k] = $v;
                           }
                   }


                   $capprin = round(($capprin + $retmes ), 2);

                   //Verificamos cada deposito por separado
                   foreach ($depositosmes as $key => $value) {

                     //Vemos si es de dia 1 o dia 15
                     if ($value["fecha_cuando"] == 1) {
                       $intgen = round(($intmes * $value["cantidad_real"]), 2);
                       $intacumes = round(($intacumes + $intgen), 2);
                       $intacu = round(($intacu + $intgen), 2);
                       $depmes = round(($depmes + $value["cantidad_real"]), 2);


                     }else{
                       $intgen = round((0 * $value["cantidad_real"]), 2);
                       $intacumes = round(($intacumes + $intgen), 2);
                       $intacu = round(($intacu + $intgen), 2);
                       $depmes = round(($depmes + $value["cantidad_real"]), 2);
                     }
                   }
                 }


                 $totalintmes = round(($intgencapprin + $intacumes ), 2);
                 $intacu = round(($intgencapprin + $intacu), 2);
                 $capprin = round(($capprin + $depmes ), 2);
                 $totalacu =  round(($capprin + $totalintmes ) - $retmes, 2);

                 /*echo $meshoy;
                 echo "<br>";
                 echo $mes . "mes";
                 echo "<br>";*/

                 if (($i+1) <= $totalMonths ) {
                  }else {
                  }

                  $botdep = $depmes;
                  $botret = $retmes;
                 // Antes de avanzar al siguiente mes vamos a capturar ese total generado por mes en un array
                 $totalpormesxuser[] = array(
                     'mes'=>$mes,
                     'tmes'=> $tmes,
                     'year'=> $agno,
                     'total'=>$totalacu,
                     'capini'=>$capprin,
                     'utilidad'=>$totalintmes,
                     'utilacumulada'=>$intacu,
                     'depmes'=> $botdep,
                     'retmes'=> $botret);

                 if ($mes == 12) {
                   $mes = 1;
                   $agno++;
                 }else{
                   $mes++;
                 }

                 if($mesesquedan < 11){
                   // checamos diferencia para saber con cuanto armo 12 mesesin
                   $difmeses = 12 - $mesesquedan;

                   if (($i+1) == $totalMonths-$difmeses ) {
                     $contorden = 1;
                   }else {
                     $contorden++;
                   }
                 }else{
                   if (($i+1) == $totalMonths-1 ) {
                     $contorden = 1;
                   }else {
                     $contorden++;
                   }
                 }

               }

             }

           }

           // Ahora calculamos la utilidad que le han dado sus referidos
           // $calculos = new CRC_Calculo();
           // $utilidadref = $calculos->crc_utilidad_referidos_cierre($userid);

           // En un array vamos a poner cuanta utilidad de referido trajo al final cada usuairo si solo queremos saber eso
           // $utilidadporuser[] = array(
           //     'id'=> $userid,
           //     'utilidad'=> $utilidadref);

           //Vamos a llenar un array para ver cuanto esta aportando cada inversor al final del mes:
           //ESTO ES LO QUE ME DICE CUANTO SE CERRO EL USUARIO POR MES
           $aportacionporuser[] = array(
               'id'=> $userid,
               'nombre'=>$nombre,
               'totalxuserxmes'=> $totalpormesxuser);

           // $totutilref = $totutilref + $utilidadref;
           // $totinvlm = $totinvlm + $totalreal;
           // $totinvhoy = $totinvhoy + $totalrhoy ;
         }
       }


   }

   $resultado = null;
   if($primerdeposito){

     for ($i=0; $i < $mesesaconsiderar ; $i++) {

       $tmes = $mesesNombre[$pdmes];
       $totalcierremes = 0;
       $totalutilacummes = 0;

       foreach ($aportacionporuser as $key => $usuario) {
         if (count($usuario['totalxuserxmes']) > 0) {

           // Vamos a recorrer los totales por mes del usuario para ver si alguno corresponde al mes en el que vamos segun el bucle for
            foreach ($usuario['totalxuserxmes'] as $llave => $usermes ) {
              // lo convertimos a decimal para que no haya problema
              $tusermes = (float)$usermes['total'];
              $tutilacummes = (float)$usermes['utilacumulada'];

              if ($usermes['mes'] == $pdmes && $usermes['year'] == $pdagno ) {
                $totalcierremes = $totalcierremes+$tusermes ;
                $totalutilacummes = $totalutilacummes+$tutilacummes ;
              }
            }

         }
       }

       $totalpormes[] = array(
         'mes'=>$pdmes,
         'tmes'=> $tmes,
         'year'=> $pdagno,
         'total'=> round($totalcierremes, 2),
         'utilacumulada' => round($totalutilacummes, 2) );

         if ($pdmes == 12) {
           $pdmes = 1;
           $pdagno++;
         }else{
           $pdmes++;
         }

     }

     // Lo que vamos a imprimir es el array totalpormes, porque si hay deposito primero en el sistema ya no es null
     $resultado = $totalpormes;

   }

   // Si lo quiero ver desglosado por user por mes
   return  $aportacionporuser;
   // return $resultado;
 }

 public function totalcuentas_utilref_cierre() {

   date_default_timezone_set('America/Tijuana');

   // Año y mes actual
   $year = date("Y");
   $month = date("m");
   $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
   '8' => 'Agosto',
   '9' => 'Septiembre',
   '10' => 'Octubre',
   '11' => 'Noviembre',
   '12' => 'Diciembre' );

   // Llamamos a la base de datos para traer todos los balances grals desde el primero
   global $wpdb;
   $usuarios = $wpdb->prefix . 'users';
   $ruta = get_site_url();
   $registros = $wpdb->get_results(" SELECT * FROM $usuarios ORDER BY id DESC", ARRAY_A);

   //Vemos cuando fue el primer deposito
   $tabdepositos = $wpdb->prefix . 'depositos';
   $primerdepogral = $wpdb->get_results("SELECT month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidad_real, fecha_cuando, fecha_termino FROM $tabdepositos WHERE status = 2 ORDER BY fecha_termino LIMIT 1 ", ARRAY_A);


   if(count($primerdepogral) == 0){
     $primerdeposito = false;
   }else{
     $pdmesuno = $primerdepogral[0]["mes"];
     $pdmes = (int) $pdmesuno;
     $pdagnouno = (int) $primerdepogral[0]["agno"];
     $pdagno = $pdagnouno;
     $pdfechaini = date($primerdepogral[0]["agno"]."-".$primerdepogral[0]["mes"]."-01");

     //calculamos la diferencia de meses
     $pdfechahoy = date("Y-m-d");
     $fechaSeparada = explode("-", $pdfechahoy);
     $meshoy = (int) $fechaSeparada[1];
     $agnohoy = (int) $fechaSeparada[0];

     $fecha1= new DateTime($pdfechaini);
     $fecha2= new DateTime($pdfechahoy);
     $diff = $fecha1->diff($fecha2);

     $yearsInMonths = $diff->format('%r%y') * 12;
     $months = $diff->format('%r%m');
     // $totalMonths = $yearsInMonths + $months;
     $mesesaconsiderar = $yearsInMonths + $months + 1;

     $primerdeposito = true;
   }

   $totinvlm = 0;
   $totinvhoy = 0;
   $totutilref = 0;

   $totalpormes = array();
   $utilidadporuserref = array();
   $aportacionporuserref = array();

   $totalinvestors = 0;
   $totalprofit = 0;

   $return_json = array();

   if(count($registros) == 0){
   }

   // vamos a recorre a todos los usuarios para saber si tienen referidos
   foreach ($registros as $key => $value) {
     $userid = absint( $value["ID"] );
     $user = get_userdata( absint( $value["ID"] ) );
     $nombre = $user->first_name . ' ' .$user->last_name ;

     if ( isset( $user->roles ) && is_array( $user->roles ) ) {
         if ( in_array( 'inversionista', $user->roles ) ) {

           $wallet = get_user_meta( $user->ID, 'wallet', true);
           $walletcode = get_user_meta( $user->ID, 'walletcode', true);
           $email = $user->user_email;
           $pais = get_user_meta( $user->ID, 'pais', true);
           $status = get_user_meta( $user->ID, 'status', true);

           if(!$status){
             $statusc = "--";
             $status = 0;
           }else {
             $statusc = $status."%";
           }

           // En este array vamos a sumar el total que obtuvo el usuario cada mes
           $totalrefpormesxuser = array();

           // vemos si el usaer esta activo
           $activo = get_user_meta( $user->ID, 'activo', true);

           $interes = ((int) $status / 100);

           //COMENZAMOS A VER SI TIENE REFERIDOS
           $invitecode = get_user_meta($userid,'invitecode',true);
           // El array de referidos vuelve a cero
           $utilidadporuserref = array();

           if(!$invitecode){
             $invitenum = 'AAAAAA';
           }else{
             $invitenum = $invitecode;
           }
           $referidos = get_users(array(
            'meta_key' => 'referido',
            'meta_value' => $invitenum
          ));

          $idreferidos = array();
          $utilidadintacu = array();
          $utilidadref = 0;

          global $wpdb;
          $tabla = $wpdb->prefix . 'depositos';
          $tabla2 = $wpdb->prefix . 'retiros';
          $tabla3 = $wpdb->prefix . 'mesesinv';

          foreach ($referidos as $key => $value) {
            // $id = $value->ID;
            $userrefid = $value->ID ;
            $userref = get_userdata( absint( $value->ID ) );
            $nombreref = $userref->first_name . ' ' .$userref->last_name ;

            if ( isset( $userref->roles ) && is_array( $userref->roles ) ) {
                if ( in_array( 'inversionista', $userref->roles ) ) {

                  $wallet = get_user_meta( $userref->ID, 'wallet', true);
                  $walletcode = get_user_meta( $userref->ID, 'walletcode', true);
                  $email = $userref->user_email;
                  $pais = get_user_meta( $userref->ID, 'pais', true);
                  $status = get_user_meta( $userref->ID, 'status', true);

                  if(!$status){
                    $statusc = "--";
                    $status = 0;
                  }else {
                    $statusc = $status."%";
                  }

                  // En este array vamos a sumar el total que obtuvo el usuario ref cada mes en utilidad aportada
                  $totalrefxmesxuser = array();

                  // vemos si el usaer esta activo
                  $activo = get_user_meta( $userref->ID, 'activo', true);

                  $interes = ((int) $status / 100);


                  $tabla = $wpdb->prefix . 'depositos';
                  $tabla2 = $wpdb->prefix . 'retiros';
                  $tabla3 = $wpdb->prefix . 'mesesinv';
                  $depositos = $wpdb->get_results("SELECT month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidad_real, fecha_cuando, fecha_termino FROM $tabla WHERE usuario = $userrefid AND status = 2 ORDER BY fecha_termino", ARRAY_A);
                  $totaldep = $wpdb->get_results("SELECT ROUND(SUM(cantidad_real), 2) AS totaldep FROM $tabla WHERE usuario = $userrefid AND status = 2 ", ARRAY_A);
                  $retiros = $wpdb->get_results("SELECT day(fecha_termino) AS dia, month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidadfin, urgente, fecha_cuando FROM $tabla2 WHERE usuario = $userrefid AND status = 2 ORDER BY fecha_termino", ARRAY_A);
                  $totalret = $wpdb->get_results("SELECT ROUND(SUM(cantidadfin), 2) AS totalret FROM $tabla2 WHERE usuario = $userrefid AND status = 2 ", ARRAY_A);
                  $mesesinv = $wpdb->get_results("SELECT mes, interes FROM $tabla3 WHERE usuario = $userrefid AND status = 1 ORDER BY id", ARRAY_A);
                  $totalmeses = count($mesesinv);
                  $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
                  '8' => 'Agosto',
                  '9' => 'Septiembre',
                  '10' => 'Octubre',
                  '11' => 'Noviembre',
                  '12' => 'Diciembre' );
                  $totalrdep = (float)$totaldep[0]['totaldep'];
                  $totalrret = (float)$totalret[0]['totalret'];

                  // Si no hay depositos solo tachamos todo en 0
                  if(count($depositos) == 0){
                    $nohay = true;
                    $tsaldini = 0;
                    $tintacu = 0;
                    $nintacu = 0;
                    $totaldisp = 0;
                    $ttotaldep = 0;
                    $totalreal = 0;
                    $ttotalreal = 0;
                    $totalrhoy = 0;
                    $totalacu = 0;
                    $ttotalrhoy = 0;
                  }else {
                    $nohay = false;

                    // $mesuno = $depositos[0]["mes"];
                    // $agnouno = (int) $depositos[0]["agno"];
                    // $agno = $agnouno;
                    // $fechaini = date($depositos[0]["agno"]."-".$depositos[0]["mes"]."-01");
                    // $inicio = 0;
                    // $intacu = 0;
                    // $totalacu = 0;
                    // $mes = (int) $mesuno;
                    $mesuno = $depositos[0]["mes"];
                    $agnouno = (int) $depositos[0]["agno"];
                    $agno = $agnouno;
                    $fechaini = date($depositos[0]["agno"]."-".$depositos[0]["mes"]."-01");
                    $inicio = 0;
                    $intacu = 0;
                    $nintacu = 0;
                    $totalacu = 0;
                    $mes = (int) $mesuno;
                    $contorden = 13;

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
                    $mesesquedan = $totalmeses - $totalMonths;


                    if ($totalMonths < 1 ) {
                      $mesesprint = 12;
                    }else{
                      if($mesesquedan < 12){
                        $mesesprint = $totalMonths + $mesesquedan;
                      }else {
                        $mesesprint = $totalMonths + 11;
                      }

                    }



                    for ($i = 0 ; $i < $mesinversor ; $i++) {

                      // Si el numero de meses que han pasado es mayor que los que tiene autorizados, ya no genera interes alguno futuro
                      if ($totalmeses < $i+1) {
                        $statusmes = 0;
                      }else {
                        $statusmes = $mesesinv[$i]["interes"];
                      }

                      // Si ya no hay status de inversiones, el intmes pasa a ser 0
                      if ($statusmes) {
                        $intmes = ((int) $statusmes / 100);
                      }else{
                        $intmes = 0;
                      }

                      $tmes = $mesesNombre[$mes];
                      $strmes = (string) $mes;
                      $capprin = $totalacu;
                      $capprinstart = 0;
                      $retmes = 0;
                      $depmes = 0;
                      $intacumes = 0;

                      // Si ya no hay int mes, la utilidad se detiene para ese inversor y se vuelve 0 en cualquier tipo de deposito o retiro.
                      if ($statusmes) {

                        //Evaluamos el interes de los retiros de este mes
                        if(count($retiros) == 0){
                        }else {

                          //Filtramos los depositos de este mes
                          $retirosmes = [];
                          foreach ( $retiros as $k => $v ) {
                                  if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                                          $retirosmes[$k] = $v;
                                  }
                          }

                          //Verificamos cada retiro por separado
                          foreach ($retirosmes as $key => $value) {
                            $diames = (int)$value["dia"];
                            //Vemos si es urgente o no
                            if ($value["urgente"] == 1) {
                              if ($diames <= 14) {
                                $intgen = 0;
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $retmes = round(($retmes + $value["cantidadfin"]), 2);
                              } else if ($diames >= 15 && $diames < 30) {
                                $intgen = round((0.05 * $value["cantidadfin"]), 2);
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $retmes = round(($retmes + $value["cantidadfin"]), 2);
                              }else{
                                $intgen = round(($intmes * $value["cantidadfin"]), 2);
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $retmes = round(($retmes + $value["cantidadfin"]), 2);
                              }
                            }else{
                              //Vemos si es de dia 15 o dia 30
                              if ($value["fecha_cuando"] == 1) {
                                $intgen = round((0.05 * $value["cantidadfin"]), 2);
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $retmes = round(($retmes + $value["cantidadfin"]), 2);


                              }else{
                                $intgen = round(($intmes * $value["cantidadfin"]), 2);
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $retmes = round(($retmes + $value["cantidadfin"]), 2);
                              }
                            }
                          }
                        }

                        $capprin = round(($capprin - $retmes ), 2);
                        $intgencapprin = round(($intmes * $capprin ), 2);



                        //Evaluamos el interes de los depositos de este mes
                        if(count($depositos) == 0){
                        }else {

                          //Filtramos los depositos de este mes
                          $depositosmes = [];
                          foreach ( $depositos as $k => $v ) {
                                  if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                                          $depositosmes[$k] = $v;
                                  }
                          }


                          $capprin = round(($capprin + $retmes ), 2);

                          //Verificamos cada deposito por separado
                          foreach ($depositosmes as $key => $value) {

                            //Vemos si es de dia 1 o dia 15
                            if ($value["fecha_cuando"] == 1) {
                              $intgen = round(($intmes * $value["cantidad_real"]), 2);
                              $intacumes = round(($intacumes + $intgen), 2);
                              $intacu = round(($intacu + $intgen), 2);
                              $depmes = round(($depmes + $value["cantidad_real"]), 2);


                            }else{
                              $intgen = round((0.05 * $value["cantidad_real"]), 2);
                              $intacumes = round(($intacumes + $intgen), 2);
                              $intacu = round(($intacu + $intgen), 2);
                              $depmes = round(($depmes + $value["cantidad_real"]), 2);
                            }
                          }
                        }


                        $totalintmes = round(($intgencapprin + $intacumes ), 2);
                        $intacu = round(($intgencapprin + $intacu), 2);
                        $capprin = round(($capprin + $depmes ), 2);
                        $capprinstart = $capprin;
                        $totalacu =  round(($capprin + $totalintmes ) - $retmes, 2);

                        /*echo $meshoy;
                        echo "<br>";
                        echo $mes . "mes";
                        echo "<br>";*/

                        if (($i+1) <= $totalMonths ) {
                         }else {
                         }

                         if(($i+1) <= $mesinversor ){
                           if($agno >= 2023){
                             if (($agno == 2023 && $mes == 2) || ($agno == 2023 && $mes == 4) || ($agno == 2023 && $mes == 5) || ($agno == 2023 && $mes == 6) || ($agno == 2023 && $mes == 7) ) {
                               $porutilmes = 0;
                               $nintacu += $porutilmes;
                             }else{
                               $porutilmes = round(($capprinstart * 0.01 ), 2);
                               $nintacu += $porutilmes;
                             }
                           }else if ($mes >= 7 && $agno >= 2022) {
                             $porutilmes = round(($capprinstart * 0.01 ), 2);
                             $nintacu += $porutilmes;
                           }else {
                             // code...
                           }
                         }

                        // Antes de avanzar al siguiente mes vamos a capturar ese total generado por mes en un array
                        $totalrefxmesxuser[] = array(
                            'mes'=>$mes,
                            'tmes'=> $tmes,
                            'year'=> $agno,
                            'total'=>round($nintacu,2));

                        if ($mes == 12) {
                          $mes = 1;
                          $agno++;
                        }else{
                          $mes++;
                        }

                        if($mesesquedan < 11){
                          // checamos diferencia para saber con cuanto armo 12 mesesin
                          $difmeses = 12 - $mesesquedan;

                          if (($i+1) == $totalMonths-$difmeses ) {
                            $contorden = 1;
                          }else {
                            $contorden++;
                          }
                        }else{
                          if (($i+1) == $totalMonths-1 ) {
                            $contorden = 1;
                          }else {
                            $contorden++;
                          }
                        }

                      }else{

                        //Evaluamos el interes de los retiros de este mes
                        if(count($retiros) == 0){
                        }else {

                          //Filtramos los depositos de este mes
                          $retirosmes = [];
                          foreach ( $retiros as $k => $v ) {
                                  if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                                          $retirosmes[$k] = $v;
                                  }
                          }

                          //Verificamos cada retiro por separado
                          foreach ($retirosmes as $key => $value) {
                            $diames = (int)$value["dia"];
                            //Vemos si es urgente o no
                            if ($value["urgente"] == 1) {
                              if ($diames <= 14) {
                                $intgen = 0;
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $retmes = round(($retmes + $value["cantidadfin"]), 2);
                              } else if ($diames >= 15 && $diames < 30) {
                                $intgen = round((0 * $value["cantidadfin"]), 2);
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $retmes = round(($retmes + $value["cantidadfin"]), 2);
                              }else{
                                $intgen = round(($intmes * $value["cantidadfin"]), 2);
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $retmes = round(($retmes + $value["cantidadfin"]), 2);
                              }
                            }else{
                              //Vemos si es de dia 15 o dia 30
                              if ($value["fecha_cuando"] == 1) {
                                $intgen = round((0 * $value["cantidadfin"]), 2);
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $retmes = round(($retmes + $value["cantidadfin"]), 2);


                              }else{
                                $intgen = round(($intmes * $value["cantidadfin"]), 2);
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $retmes = round(($retmes + $value["cantidadfin"]), 2);
                              }
                            }
                          }
                        }

                        $capprin = round(($capprin - $retmes ), 2);
                        $intgencapprin = round(($intmes * $capprin ), 2);



                        //Evaluamos el interes de los depositos de este mes
                        if(count($depositos) == 0){
                        }else {

                          //Filtramos los depositos de este mes
                          $depositosmes = [];
                          foreach ( $depositos as $k => $v ) {
                                  if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                                          $depositosmes[$k] = $v;
                                  }
                          }


                          $capprin = round(($capprin + $retmes ), 2);

                          //Verificamos cada deposito por separado
                          foreach ($depositosmes as $key => $value) {

                            //Vemos si es de dia 1 o dia 15
                            if ($value["fecha_cuando"] == 1) {
                              $intgen = round(($intmes * $value["cantidad_real"]), 2);
                              $intacumes = round(($intacumes + $intgen), 2);
                              $intacu = round(($intacu + $intgen), 2);
                              $depmes = round(($depmes + $value["cantidad_real"]), 2);


                            }else{
                              $intgen = round((0 * $value["cantidad_real"]), 2);
                              $intacumes = round(($intacumes + $intgen), 2);
                              $intacu = round(($intacu + $intgen), 2);
                              $depmes = round(($depmes + $value["cantidad_real"]), 2);
                            }
                          }
                        }


                        $totalintmes = round(($intgencapprin + $intacumes ), 2);
                        $intacu = round(($intgencapprin + $intacu), 2);
                        $capprin = round(($capprin + $depmes ), 2);
                        $capprinstart = $capprin;
                        $totalacu =  round(($capprin + $totalintmes ) - $retmes, 2);

                        /*echo $meshoy;
                        echo "<br>";
                        echo $mes . "mes";
                        echo "<br>";*/

                        if (($i+1) <= $totalMonths ) {
                         }else {
                         }

                         if(($i+1) <= $mesinversor ){
                           if($agno >= 2023){
                             if (($agno == 2023 && $mes == 2) || ($agno == 2023 && $mes == 4) || ($agno == 2023 && $mes == 5) || ($agno == 2023 && $mes == 6) || ($agno == 2023 && $mes == 7) ) {
                               $porutilmes = 0;
                               $nintacu += $porutilmes;
                             }else{
                               $porutilmes = round(($capprinstart * 0.01 ), 2);
                               $nintacu += $porutilmes;
                             }
                           }else if ($mes >= 7 && $agno >= 2022) {
                             $porutilmes = round(($capprinstart * 0.01 ), 2);
                             $nintacu += $porutilmes;
                           }else {
                             // code...
                           }
                         }

                        // Antes de avanzar al siguiente mes vamos a capturar ese total generado por mes en un array
                        $totalrefxmesxuser[] = array(
                            'mes'=>$mes,
                            'tmes'=> $tmes,
                            'year'=> $agno,
                            'total'=>round($nintacu,2));

                        if ($mes == 12) {
                          $mes = 1;
                          $agno++;
                        }else{
                          $mes++;
                        }

                        if($mesesquedan < 11){
                          // checamos diferencia para saber con cuanto armo 12 mesesin
                          $difmeses = 12 - $mesesquedan;

                          if (($i+1) == $totalMonths-$difmeses ) {
                            $contorden = 1;
                          }else {
                            $contorden++;
                          }
                        }else{
                          if (($i+1) == $totalMonths-1 ) {
                            $contorden = 1;
                          }else {
                            $contorden++;
                          }
                        }

                      }

                    }


                  }//si gay depositos

                  // En un array vamos a poner cuanta utilidad de referido (El array de sus meses trajo al final cada usuairo ref
                  $utilidadporuserref[] = array(
                      'idref'=> $userrefid,
                      'nombre'=>$nombreref,
                      'utilidadxmes'=> $totalrefxmesxuser);

                }
              }

          }//foreach de todos los referidos


           //Vamos a llenar un array para ver cuanto esta aportando cada inversor al final del mes:
           //ESTO ES LO QUE ME DICE CUANTO SE CERRO EL USUARIO POR MES
           $aportacionporuserref[] = array(
               'id'=> $userid,
               'nombre'=>$nombre,
               'totalxuserrefxmes'=> $utilidadporuserref);

         } // if si es inversionista
       }// if si user roles
     }//foreach de todos los usuarios

     $resultado = null;
     if($primerdeposito){

       for ($i=0; $i < $mesesaconsiderar ; $i++) {

         $tmes = $mesesNombre[$pdmes];
         $totalcierremes = 0;

         foreach ($aportacionporuserref as $key => $usuario) {
           if (count($usuario['totalxuserrefxmes']) > 0) {

             // Vamos a recorrer los user referidos que tenga el usuario para ver si alguno ya ha generado algo de utilidad.
              foreach ($usuario['totalxuserrefxmes'] as $llave => $userref ) {
                if (count($userref['utilidadxmes']) > 0) {

                  // Vamos a recorrer los totales por mes del usuario ref para ver si alguno corresponde al mes en el que vamos segun el bucle for
                   foreach ($userref['utilidadxmes'] as $clave => $userrefmes ) {
                     // lo convertimos a decimal para que no haya problema
                     $tusermes = (float)$userrefmes['total'];

                     if ($userrefmes['mes'] == $pdmes && $userrefmes['year'] == $pdagno ) {
                       $totalcierremes = $totalcierremes+$tusermes ;

                     }

                   }
                 }

              }

           }
         }

         $totalpormes[] = array(
           'mes'=>$pdmes,
           'tmes'=> $tmes,
           'year'=> $pdagno,
           'total'=> round($totalcierremes, 2) );

           if ($pdmes == 12) {
             $pdmes = 1;
             $pdagno++;
           }else{
             $pdmes++;
           }

       }

       // Lo que vamos a imprimir es el array totalpormes, porque si hay deposito primero en el sistema ya no es null
       $resultado = $totalpormes;

     }
   // return $utilidadref;
   // Si quiero ver la utilidad desglosada por usuario:
   // return $aportacionporuserref;
   return $resultado;
 }

 public function totalcuentas_utilref_cierre_detalle() {

   date_default_timezone_set('America/Tijuana');

   // Año y mes actual
   $year = date("Y");
   $month = date("m");
   $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
   '8' => 'Agosto',
   '9' => 'Septiembre',
   '10' => 'Octubre',
   '11' => 'Noviembre',
   '12' => 'Diciembre' );

   // Llamamos a la base de datos para traer todos los balances grals desde el primero
   global $wpdb;
   $usuarios = $wpdb->prefix . 'users';
   $ruta = get_site_url();
   $registros = $wpdb->get_results(" SELECT * FROM $usuarios ORDER BY id DESC", ARRAY_A);

   //Vemos cuando fue el primer deposito
   $tabdepositos = $wpdb->prefix . 'depositos';
   $primerdepogral = $wpdb->get_results("SELECT month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidad_real, fecha_cuando, fecha_termino FROM $tabdepositos WHERE status = 2 ORDER BY fecha_termino LIMIT 1 ", ARRAY_A);


   if(count($primerdepogral) == 0){
     $primerdeposito = false;
   }else{
     $pdmesuno = $primerdepogral[0]["mes"];
     $pdmes = (int) $pdmesuno;
     $pdagnouno = (int) $primerdepogral[0]["agno"];
     $pdagno = $pdagnouno;
     $pdfechaini = date($primerdepogral[0]["agno"]."-".$primerdepogral[0]["mes"]."-01");

     //calculamos la diferencia de meses
     $pdfechahoy = date("Y-m-d");
     $fechaSeparada = explode("-", $pdfechahoy);
     $meshoy = (int) $fechaSeparada[1];
     $agnohoy = (int) $fechaSeparada[0];

     $fecha1= new DateTime($pdfechaini);
     $fecha2= new DateTime($pdfechahoy);
     $diff = $fecha1->diff($fecha2);

     $yearsInMonths = $diff->format('%r%y') * 12;
     $months = $diff->format('%r%m');
     // $totalMonths = $yearsInMonths + $months;
     $mesesaconsiderar = $yearsInMonths + $months + 1;

     $primerdeposito = true;
   }

   $totinvlm = 0;
   $totinvhoy = 0;
   $totutilref = 0;

   $totalpormes = array();
   $utilidadporuserref = array();
   $aportacionporuserref = array();

   $totalinvestors = 0;
   $totalprofit = 0;

   $return_json = array();

   if(count($registros) == 0){
   }

   // vamos a recorre a todos los usuarios para saber si tienen referidos
   foreach ($registros as $key => $value) {
     $userid = absint( $value["ID"] );
     $user = get_userdata( absint( $value["ID"] ) );
     $nombre = $user->first_name . ' ' .$user->last_name ;

     if ( isset( $user->roles ) && is_array( $user->roles ) ) {
         if ( in_array( 'inversionista', $user->roles ) ) {

           $wallet = get_user_meta( $user->ID, 'wallet', true);
           $walletcode = get_user_meta( $user->ID, 'walletcode', true);
           $email = $user->user_email;
           $pais = get_user_meta( $user->ID, 'pais', true);
           $status = get_user_meta( $user->ID, 'status', true);

           if(!$status){
             $statusc = "--";
             $status = 0;
           }else {
             $statusc = $status."%";
           }

           // En este array vamos a sumar el total que obtuvo el usuario cada mes
           $totalrefpormesxuser = array();

           // vemos si el usaer esta activo
           $activo = get_user_meta( $user->ID, 'activo', true);

           $interes = ((int) $status / 100);

           //COMENZAMOS A VER SI TIENE REFERIDOS
           $invitecode = get_user_meta($userid,'invitecode',true);
           // El array de referidos vuelve a cero
           $utilidadporuserref = array();

           if(!$invitecode){
             $invitenum = 'AAAAAA';
           }else{
             $invitenum = $invitecode;
           }
           $referidos = get_users(array(
            'meta_key' => 'referido',
            'meta_value' => $invitenum
          ));

          $idreferidos = array();
          $utilidadintacu = array();
          $utilidadref = 0;

          global $wpdb;
          $tabla = $wpdb->prefix . 'depositos';
          $tabla2 = $wpdb->prefix . 'retiros';
          $tabla3 = $wpdb->prefix . 'mesesinv';

          foreach ($referidos as $key => $value) {
            // $id = $value->ID;
            $userrefid = $value->ID ;
            $userref = get_userdata( absint( $value->ID ) );
            $nombreref = $userref->first_name . ' ' .$userref->last_name ;

            if ( isset( $userref->roles ) && is_array( $userref->roles ) ) {
                if ( in_array( 'inversionista', $userref->roles ) ) {

                  $wallet = get_user_meta( $userref->ID, 'wallet', true);
                  $walletcode = get_user_meta( $userref->ID, 'walletcode', true);
                  $email = $userref->user_email;
                  $pais = get_user_meta( $userref->ID, 'pais', true);
                  $status = get_user_meta( $userref->ID, 'status', true);

                  if(!$status){
                    $statusc = "--";
                    $status = 0;
                  }else {
                    $statusc = $status."%";
                  }

                  // En este array vamos a sumar el total que obtuvo el usuario ref cada mes en utilidad aportada
                  $totalrefxmesxuser = array();

                  // vemos si el usaer esta activo
                  $activo = get_user_meta( $userref->ID, 'activo', true);

                  $interes = ((int) $status / 100);


                  $tabla = $wpdb->prefix . 'depositos';
                  $tabla2 = $wpdb->prefix . 'retiros';
                  $tabla3 = $wpdb->prefix . 'mesesinv';
                  $depositos = $wpdb->get_results("SELECT month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidad_real, fecha_cuando, fecha_termino FROM $tabla WHERE usuario = $userrefid AND status = 2 ORDER BY fecha_termino", ARRAY_A);
                  $totaldep = $wpdb->get_results("SELECT ROUND(SUM(cantidad_real), 2) AS totaldep FROM $tabla WHERE usuario = $userrefid AND status = 2 ", ARRAY_A);
                  $retiros = $wpdb->get_results("SELECT day(fecha_termino) AS dia, month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidadfin, urgente, fecha_cuando FROM $tabla2 WHERE usuario = $userrefid AND status = 2 ORDER BY fecha_termino", ARRAY_A);
                  $totalret = $wpdb->get_results("SELECT ROUND(SUM(cantidadfin), 2) AS totalret FROM $tabla2 WHERE usuario = $userrefid AND status = 2 ", ARRAY_A);
                  $mesesinv = $wpdb->get_results("SELECT mes, interes FROM $tabla3 WHERE usuario = $userrefid AND status = 1 ORDER BY id", ARRAY_A);
                  $totalmeses = count($mesesinv);
                  $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
                  '8' => 'Agosto',
                  '9' => 'Septiembre',
                  '10' => 'Octubre',
                  '11' => 'Noviembre',
                  '12' => 'Diciembre' );
                  $totalrdep = (float)$totaldep[0]['totaldep'];
                  $totalrret = (float)$totalret[0]['totalret'];

                  // Si no hay depositos solo tachamos todo en 0
                  if(count($depositos) == 0){
                    $nohay = true;
                    $tsaldini = 0;
                    $tintacu = 0;
                    $nintacu = 0;
                    $totaldisp = 0;
                    $ttotaldep = 0;
                    $totalreal = 0;
                    $ttotalreal = 0;
                    $totalrhoy = 0;
                    $totalacu = 0;
                    $ttotalrhoy = 0;
                  }else {
                    $nohay = false;

                    // $mesuno = $depositos[0]["mes"];
                    // $agnouno = (int) $depositos[0]["agno"];
                    // $agno = $agnouno;
                    // $fechaini = date($depositos[0]["agno"]."-".$depositos[0]["mes"]."-01");
                    // $inicio = 0;
                    // $intacu = 0;
                    // $totalacu = 0;
                    // $mes = (int) $mesuno;
                    $mesuno = $depositos[0]["mes"];
                    $agnouno = (int) $depositos[0]["agno"];
                    $agno = $agnouno;
                    $fechaini = date($depositos[0]["agno"]."-".$depositos[0]["mes"]."-01");
                    $inicio = 0;
                    $intacu = 0;
                    $nintacu = 0;
                    $totalacu = 0;
                    $mes = (int) $mesuno;
                    $contorden = 13;

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
                    $mesesquedan = $totalmeses - $totalMonths;


                    if ($totalMonths < 1 ) {
                      $mesesprint = 12;
                    }else{
                      if($mesesquedan < 12){
                        $mesesprint = $totalMonths + $mesesquedan;
                      }else {
                        $mesesprint = $totalMonths + 11;
                      }

                    }



                    for ($i = 0 ; $i < $mesinversor ; $i++) {

                      // Si el numero de meses que han pasado es mayor que los que tiene autorizados, ya no genera interes alguno futuro
                      if ($totalmeses < $i+1) {
                        $statusmes = 0;
                      }else {
                        $statusmes = $mesesinv[$i]["interes"];
                      }

                      // Si ya no hay status de inversiones, el intmes pasa a ser 0
                      if ($statusmes) {
                        $intmes = ((int) $statusmes / 100);
                      }else{
                        $intmes = 0;
                      }

                      $tmes = $mesesNombre[$mes];
                      $strmes = (string) $mes;
                      $capprin = $totalacu;
                      $capprinstart = 0;
                      $retmes = 0;
                      $depmes = 0;
                      $intacumes = 0;

                      // Si ya no hay int mes, la utilidad se detiene para ese inversor y se vuelve 0 en cualquier tipo de deposito o retiro.
                      if ($statusmes) {

                        //Evaluamos el interes de los retiros de este mes
                        if(count($retiros) == 0){
                        }else {

                          //Filtramos los depositos de este mes
                          $retirosmes = [];
                          foreach ( $retiros as $k => $v ) {
                                  if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                                          $retirosmes[$k] = $v;
                                  }
                          }

                          //Verificamos cada retiro por separado
                          foreach ($retirosmes as $key => $value) {
                            $diames = (int)$value["dia"];
                            //Vemos si es urgente o no
                            if ($value["urgente"] == 1) {
                              if ($diames <= 14) {
                                $intgen = 0;
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $retmes = round(($retmes + $value["cantidadfin"]), 2);
                              } else if ($diames >= 15 && $diames < 30) {
                                $intgen = round((0.05 * $value["cantidadfin"]), 2);
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $retmes = round(($retmes + $value["cantidadfin"]), 2);
                              }else{
                                $intgen = round(($intmes * $value["cantidadfin"]), 2);
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $retmes = round(($retmes + $value["cantidadfin"]), 2);
                              }
                            }else{
                              //Vemos si es de dia 15 o dia 30
                              if ($value["fecha_cuando"] == 1) {
                                $intgen = round((0.05 * $value["cantidadfin"]), 2);
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $retmes = round(($retmes + $value["cantidadfin"]), 2);


                              }else{
                                $intgen = round(($intmes * $value["cantidadfin"]), 2);
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $retmes = round(($retmes + $value["cantidadfin"]), 2);
                              }
                            }
                          }
                        }

                        $capprin = round(($capprin - $retmes ), 2);
                        $intgencapprin = round(($intmes * $capprin ), 2);



                        //Evaluamos el interes de los depositos de este mes
                        if(count($depositos) == 0){
                        }else {

                          //Filtramos los depositos de este mes
                          $depositosmes = [];
                          foreach ( $depositos as $k => $v ) {
                                  if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                                          $depositosmes[$k] = $v;
                                  }
                          }


                          $capprin = round(($capprin + $retmes ), 2);

                          //Verificamos cada deposito por separado
                          foreach ($depositosmes as $key => $value) {

                            //Vemos si es de dia 1 o dia 15
                            if ($value["fecha_cuando"] == 1) {
                              $intgen = round(($intmes * $value["cantidad_real"]), 2);
                              $intacumes = round(($intacumes + $intgen), 2);
                              $intacu = round(($intacu + $intgen), 2);
                              $depmes = round(($depmes + $value["cantidad_real"]), 2);


                            }else{
                              $intgen = round((0.05 * $value["cantidad_real"]), 2);
                              $intacumes = round(($intacumes + $intgen), 2);
                              $intacu = round(($intacu + $intgen), 2);
                              $depmes = round(($depmes + $value["cantidad_real"]), 2);
                            }
                          }
                        }


                        $totalintmes = round(($intgencapprin + $intacumes ), 2);
                        $intacu = round(($intgencapprin + $intacu), 2);
                        $capprin = round(($capprin + $depmes ), 2);
                        $capprinstart = $capprin;
                        $totalacu =  round(($capprin + $totalintmes ) - $retmes, 2);

                        /*echo $meshoy;
                        echo "<br>";
                        echo $mes . "mes";
                        echo "<br>";*/

                        if (($i+1) <= $totalMonths ) {
                         }else {
                         }

                         if(($i+1) <= $mesinversor ){
                           if($agno >= 2023){
                             if (($agno == 2023 && $mes == 2) || ($agno == 2023 && $mes == 4) || ($agno == 2023 && $mes == 5) || ($agno == 2023 && $mes == 6) || ($agno == 2023 && $mes == 7) ) {
                               $porutilmes = 0;
                               $nintacu += $porutilmes;
                             }else{
                               $porutilmes = round(($capprinstart * 0.01 ), 2);
                               $nintacu += $porutilmes;
                             }
                           }else if ($mes >= 7 && $agno >= 2022) {
                             $porutilmes = round(($capprinstart * 0.01 ), 2);
                             $nintacu += $porutilmes;
                           }else {
                             // code...
                           }
                         }

                        // Antes de avanzar al siguiente mes vamos a capturar ese total generado por mes en un array
                        $totalrefxmesxuser[] = array(
                            'mes'=>$mes,
                            'tmes'=> $tmes,
                            'year'=> $agno,
                            'total'=>round($nintacu,2));

                        if ($mes == 12) {
                          $mes = 1;
                          $agno++;
                        }else{
                          $mes++;
                        }

                        if($mesesquedan < 11){
                          // checamos diferencia para saber con cuanto armo 12 mesesin
                          $difmeses = 12 - $mesesquedan;

                          if (($i+1) == $totalMonths-$difmeses ) {
                            $contorden = 1;
                          }else {
                            $contorden++;
                          }
                        }else{
                          if (($i+1) == $totalMonths-1 ) {
                            $contorden = 1;
                          }else {
                            $contorden++;
                          }
                        }

                      }else{

                        //Evaluamos el interes de los retiros de este mes
                        if(count($retiros) == 0){
                        }else {

                          //Filtramos los depositos de este mes
                          $retirosmes = [];
                          foreach ( $retiros as $k => $v ) {
                                  if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                                          $retirosmes[$k] = $v;
                                  }
                          }

                          //Verificamos cada retiro por separado
                          foreach ($retirosmes as $key => $value) {
                            $diames = (int)$value["dia"];
                            //Vemos si es urgente o no
                            if ($value["urgente"] == 1) {
                              if ($diames <= 14) {
                                $intgen = 0;
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $retmes = round(($retmes + $value["cantidadfin"]), 2);
                              } else if ($diames >= 15 && $diames < 30) {
                                $intgen = round((0 * $value["cantidadfin"]), 2);
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $retmes = round(($retmes + $value["cantidadfin"]), 2);
                              }else{
                                $intgen = round(($intmes * $value["cantidadfin"]), 2);
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $retmes = round(($retmes + $value["cantidadfin"]), 2);
                              }
                            }else{
                              //Vemos si es de dia 15 o dia 30
                              if ($value["fecha_cuando"] == 1) {
                                $intgen = round((0 * $value["cantidadfin"]), 2);
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $retmes = round(($retmes + $value["cantidadfin"]), 2);


                              }else{
                                $intgen = round(($intmes * $value["cantidadfin"]), 2);
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $retmes = round(($retmes + $value["cantidadfin"]), 2);
                              }
                            }
                          }
                        }

                        $capprin = round(($capprin - $retmes ), 2);
                        $intgencapprin = round(($intmes * $capprin ), 2);



                        //Evaluamos el interes de los depositos de este mes
                        if(count($depositos) == 0){
                        }else {

                          //Filtramos los depositos de este mes
                          $depositosmes = [];
                          foreach ( $depositos as $k => $v ) {
                                  if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                                          $depositosmes[$k] = $v;
                                  }
                          }


                          $capprin = round(($capprin + $retmes ), 2);

                          //Verificamos cada deposito por separado
                          foreach ($depositosmes as $key => $value) {

                            //Vemos si es de dia 1 o dia 15
                            if ($value["fecha_cuando"] == 1) {
                              $intgen = round(($intmes * $value["cantidad_real"]), 2);
                              $intacumes = round(($intacumes + $intgen), 2);
                              $intacu = round(($intacu + $intgen), 2);
                              $depmes = round(($depmes + $value["cantidad_real"]), 2);


                            }else{
                              $intgen = round((0 * $value["cantidad_real"]), 2);
                              $intacumes = round(($intacumes + $intgen), 2);
                              $intacu = round(($intacu + $intgen), 2);
                              $depmes = round(($depmes + $value["cantidad_real"]), 2);
                            }
                          }
                        }


                        $totalintmes = round(($intgencapprin + $intacumes ), 2);
                        $intacu = round(($intgencapprin + $intacu), 2);
                        $capprin = round(($capprin + $depmes ), 2);
                        $capprinstart = $capprin;
                        $totalacu =  round(($capprin + $totalintmes ) - $retmes, 2);

                        /*echo $meshoy;
                        echo "<br>";
                        echo $mes . "mes";
                        echo "<br>";*/

                        if (($i+1) <= $totalMonths ) {
                         }else {
                         }

                         if(($i+1) <= $mesinversor ){
                           if($agno >= 2023){
                             if (($agno == 2023 && $mes == 2) || ($agno == 2023 && $mes == 4) || ($agno == 2023 && $mes == 5) || ($agno == 2023 && $mes == 6) || ($agno == 2023 && $mes == 7) ) {
                               $porutilmes = 0;
                               $nintacu += $porutilmes;
                             }else{
                               $porutilmes = round(($capprinstart * 0.01 ), 2);
                               $nintacu += $porutilmes;
                             }
                           }else if ($mes >= 7 && $agno >= 2022) {
                             $porutilmes = round(($capprinstart * 0.01 ), 2);
                             $nintacu += $porutilmes;
                           }else {
                             // code...
                           }
                         }

                        // Antes de avanzar al siguiente mes vamos a capturar ese total generado por mes en un array
                        $totalrefxmesxuser[] = array(
                            'mes'=>$mes,
                            'tmes'=> $tmes,
                            'year'=> $agno,
                            'total'=>round($nintacu,2));

                        if ($mes == 12) {
                          $mes = 1;
                          $agno++;
                        }else{
                          $mes++;
                        }

                        if($mesesquedan < 11){
                          // checamos diferencia para saber con cuanto armo 12 mesesin
                          $difmeses = 12 - $mesesquedan;

                          if (($i+1) == $totalMonths-$difmeses ) {
                            $contorden = 1;
                          }else {
                            $contorden++;
                          }
                        }else{
                          if (($i+1) == $totalMonths-1 ) {
                            $contorden = 1;
                          }else {
                            $contorden++;
                          }
                        }

                      }

                    }


                  }//si gay depositos

                  // En un array vamos a poner cuanta utilidad de referido (El array de sus meses trajo al final cada usuairo ref
                  $utilidadporuserref[] = array(
                      'idref'=> $userrefid,
                      'nombre'=>$nombreref,
                      'utilidadxmes'=> $totalrefxmesxuser);

                }
              }

          }//foreach de todos los referidos


           //Vamos a llenar un array para ver cuanto esta aportando cada inversor al final del mes:
           //ESTO ES LO QUE ME DICE CUANTO SE CERRO EL USUARIO POR MES
           $aportacionporuserref[] = array(
               'id'=> $userid,
               'nombre'=>$nombre,
               'totalxuserrefxmes'=> $utilidadporuserref);

         } // if si es inversionista
       }// if si user roles
     }//foreach de todos los usuarios

     $resultado = null;
     if($primerdeposito){

       for ($i=0; $i < $mesesaconsiderar ; $i++) {

         $tmes = $mesesNombre[$pdmes];
         $totalcierremes = 0;

         foreach ($aportacionporuserref as $key => $usuario) {
           if (count($usuario['totalxuserrefxmes']) > 0) {

             // Vamos a recorrer los user referidos que tenga el usuario para ver si alguno ya ha generado algo de utilidad.
              foreach ($usuario['totalxuserrefxmes'] as $llave => $userref ) {
                if (count($userref['utilidadxmes']) > 0) {

                  // Vamos a recorrer los totales por mes del usuario ref para ver si alguno corresponde al mes en el que vamos segun el bucle for
                   foreach ($userref['utilidadxmes'] as $clave => $userrefmes ) {
                     // lo convertimos a decimal para que no haya problema
                     $tusermes = (float)$userrefmes['total'];

                     if ($userrefmes['mes'] == $pdmes && $userrefmes['year'] == $pdagno ) {
                       $totalcierremes = $totalcierremes+$tusermes ;

                     }

                   }
                 }

              }

           }
         }

         $totalpormes[] = array(
           'mes'=>$pdmes,
           'tmes'=> $tmes,
           'year'=> $pdagno,
           'total'=> round($totalcierremes, 2) );

           if ($pdmes == 12) {
             $pdmes = 1;
             $pdagno++;
           }else{
             $pdmes++;
           }

       }

       // Lo que vamos a imprimir es el array totalpormes, porque si hay deposito primero en el sistema ya no es null
       $resultado = $totalpormes;

     }
   // return $utilidadref;
   // Si quiero ver la utilidad desglosada por usuario:
   return $aportacionporuserref;
   // return $resultado;
 }

 public function totalcuentas_utilref_individual_detalle($iduser) {

   date_default_timezone_set('America/Tijuana');

   // Año y mes actual
   $year = date("Y");
   $month = date("m");
   $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
   '8' => 'Agosto',
   '9' => 'Septiembre',
   '10' => 'Octubre',
   '11' => 'Noviembre',
   '12' => 'Diciembre' );

   // Llamamos a la base de datos para traer todos los balances grals desde el primero
   global $wpdb;
   $usuarios = $wpdb->prefix . 'users';
   $ruta = get_site_url();
   $registros = $wpdb->get_results(" SELECT * FROM $usuarios ORDER BY id DESC", ARRAY_A);

   //Vemos cuando fue el primer deposito
   $tabdepositos = $wpdb->prefix . 'depositos';
   $primerdepogral = $wpdb->get_results("SELECT month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidad_real, fecha_cuando, fecha_termino FROM $tabdepositos WHERE status = 2 ORDER BY fecha_termino LIMIT 1 ", ARRAY_A);


   if(count($primerdepogral) == 0){
     $primerdeposito = false;
   }else{
     $pdmesuno = $primerdepogral[0]["mes"];
     $pdmes = (int) $pdmesuno;
     $pdagnouno = (int) $primerdepogral[0]["agno"];
     $pdagno = $pdagnouno;
     $pdfechaini = date($primerdepogral[0]["agno"]."-".$primerdepogral[0]["mes"]."-01");

     //calculamos la diferencia de meses
     $pdfechahoy = date("Y-m-d");
     $fechaSeparada = explode("-", $pdfechahoy);
     $meshoy = (int) $fechaSeparada[1];
     $agnohoy = (int) $fechaSeparada[0];

     $fecha1= new DateTime($pdfechaini);
     $fecha2= new DateTime($pdfechahoy);
     $diff = $fecha1->diff($fecha2);

     $yearsInMonths = $diff->format('%r%y') * 12;
     $months = $diff->format('%r%m');
     // $totalMonths = $yearsInMonths + $months;
     $mesesaconsiderar = $yearsInMonths + $months + 1;

     $primerdeposito = true;
   }

   $totinvlm = 0;
   $totinvhoy = 0;
   $totutilref = 0;

   $totalpormes = array();
   $utilidadporuserref = array();
   $aportacionporuserref = array();

   $totalinvestors = 0;
   $totalprofit = 0;

   $return_json = array();

   if(count($registros) == 0){
   }

   // vamos a recorre a todos los usuarios para saber si tienen referidos
   foreach ($registros as $key => $value) {
     $userid = absint( $value["ID"] );

     if ($userid == $iduser) {
       $user = get_userdata( absint( $value["ID"] ) );
       $nombre = $user->first_name . ' ' .$user->last_name ;

       if ( isset( $user->roles ) && is_array( $user->roles ) ) {
           if ( in_array( 'inversionista', $user->roles ) ) {

             $wallet = get_user_meta( $user->ID, 'wallet', true);
             $walletcode = get_user_meta( $user->ID, 'walletcode', true);
             $email = $user->user_email;
             $pais = get_user_meta( $user->ID, 'pais', true);
             $status = get_user_meta( $user->ID, 'status', true);

             if(!$status){
               $statusc = "--";
               $status = 0;
             }else {
               $statusc = $status."%";
             }

             // En este array vamos a sumar el total que obtuvo el usuario cada mes
             $totalrefpormesxuser = array();

             // vemos si el usaer esta activo
             $activo = get_user_meta( $user->ID, 'activo', true);

             $interes = ((int) $status / 100);

             //COMENZAMOS A VER SI TIENE REFERIDOS
             $invitecode = get_user_meta($userid,'invitecode',true);
             // El array de referidos vuelve a cero
             $utilidadporuserref = array();

             if(!$invitecode){
               $invitenum = 'AAAAAA';
             }else{
               $invitenum = $invitecode;
             }
             $referidos = get_users(array(
              'meta_key' => 'referido',
              'meta_value' => $invitenum
            ));

            $idreferidos = array();
            $utilidadintacu = array();
            $utilidadref = 0;

            // global $wpdb;
            $tabla = $wpdb->prefix . 'depositos';
            $tabla2 = $wpdb->prefix . 'retiros';
            $tabla3 = $wpdb->prefix . 'mesesinv';

            foreach ($referidos as $key => $value) {
              // $id = $value->ID;
              $userrefid = $value->ID ;
              $userref = get_userdata( absint( $value->ID ) );
              $nombreref = $userref->first_name . ' ' .$userref->last_name ;

              if ( isset( $userref->roles ) && is_array( $userref->roles ) ) {
                  if ( in_array( 'inversionista', $userref->roles ) ) {

                    $wallet = get_user_meta( $userref->ID, 'wallet', true);
                    $walletcode = get_user_meta( $userref->ID, 'walletcode', true);
                    $email = $userref->user_email;
                    $pais = get_user_meta( $userref->ID, 'pais', true);
                    $status = get_user_meta( $userref->ID, 'status', true);

                    if(!$status){
                      $statusc = "--";
                      $status = 0;
                    }else {
                      $statusc = $status."%";
                    }

                    // En este array vamos a sumar el total que obtuvo el usuario ref cada mes en utilidad aportada
                    $totalrefxmesxuser = array();

                    // vemos si el usaer esta activo
                    $activo = get_user_meta( $userref->ID, 'activo', true);

                    $interes = ((int) $status / 100);


                    $tabla = $wpdb->prefix . 'depositos';
                    $tabla2 = $wpdb->prefix . 'retiros';
                    $tabla3 = $wpdb->prefix . 'mesesinv';
                    $depositos = $wpdb->get_results("SELECT month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidad_real, fecha_cuando, fecha_termino FROM $tabla WHERE usuario = $userrefid AND status = 2 ORDER BY fecha_termino", ARRAY_A);
                    $totaldep = $wpdb->get_results("SELECT ROUND(SUM(cantidad_real), 2) AS totaldep FROM $tabla WHERE usuario = $userrefid AND status = 2 ", ARRAY_A);
                    $retiros = $wpdb->get_results("SELECT day(fecha_termino) AS dia, month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidadfin, urgente, fecha_cuando FROM $tabla2 WHERE usuario = $userrefid AND status = 2 ORDER BY fecha_termino", ARRAY_A);
                    $totalret = $wpdb->get_results("SELECT ROUND(SUM(cantidadfin), 2) AS totalret FROM $tabla2 WHERE usuario = $userrefid AND status = 2 ", ARRAY_A);
                    $mesesinv = $wpdb->get_results("SELECT mes, interes FROM $tabla3 WHERE usuario = $userrefid AND status = 1 ORDER BY id", ARRAY_A);
                    $totalmeses = count($mesesinv);
                    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
                    '8' => 'Agosto',
                    '9' => 'Septiembre',
                    '10' => 'Octubre',
                    '11' => 'Noviembre',
                    '12' => 'Diciembre' );
                    $totalrdep = (float)$totaldep[0]['totaldep'];
                    $totalrret = (float)$totalret[0]['totalret'];

                    // Si no hay depositos solo tachamos todo en 0
                    if(count($depositos) == 0){
                      $nohay = true;
                      $tsaldini = 0;
                      $tintacu = 0;
                      $nintacu = 0;
                      $totaldisp = 0;
                      $ttotaldep = 0;
                      $totalreal = 0;
                      $ttotalreal = 0;
                      $totalrhoy = 0;
                      $totalacu = 0;
                      $ttotalrhoy = 0;
                    }else {
                      $nohay = false;

                      // $mesuno = $depositos[0]["mes"];
                      // $agnouno = (int) $depositos[0]["agno"];
                      // $agno = $agnouno;
                      // $fechaini = date($depositos[0]["agno"]."-".$depositos[0]["mes"]."-01");
                      // $inicio = 0;
                      // $intacu = 0;
                      // $totalacu = 0;
                      // $mes = (int) $mesuno;
                      $mesuno = $depositos[0]["mes"];
                      $agnouno = (int) $depositos[0]["agno"];
                      $agno = $agnouno;
                      $fechaini = date($depositos[0]["agno"]."-".$depositos[0]["mes"]."-01");
                      $inicio = 0;
                      $intacu = 0;
                      $nintacu = 0;
                      $totalacu = 0;
                      $mes = (int) $mesuno;
                      $contorden = 13;

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
                      $mesesquedan = $totalmeses - $totalMonths;


                      if ($totalMonths < 1 ) {
                        $mesesprint = 12;
                      }else{
                        if($mesesquedan < 12){
                          $mesesprint = $totalMonths + $mesesquedan;
                        }else {
                          $mesesprint = $totalMonths + 11;
                        }

                      }



                      for ($i = 0 ; $i < $mesinversor ; $i++) {

                        // Si el numero de meses que han pasado es mayor que los que tiene autorizados, ya no genera interes alguno futuro
                        if ($totalmeses < $i+1) {
                          $statusmes = 0;
                        }else {
                          $statusmes = $mesesinv[$i]["interes"];
                        }

                        // Si ya no hay status de inversiones, el intmes pasa a ser 0
                        if ($statusmes) {
                          $intmes = ((int) $statusmes / 100);
                        }else{
                          $intmes = 0;
                        }

                        $tmes = $mesesNombre[$mes];
                        $strmes = (string) $mes;
                        $capprin = $totalacu;
                        $capprinstart = 0;
                        $retmes = 0;
                        $depmes = 0;
                        $intacumes = 0;

                        // Si ya no hay int mes, la utilidad se detiene para ese inversor y se vuelve 0 en cualquier tipo de deposito o retiro.
                        if ($statusmes) {

                          //Evaluamos el interes de los retiros de este mes
                          if(count($retiros) == 0){
                          }else {

                            //Filtramos los depositos de este mes
                            $retirosmes = [];
                            foreach ( $retiros as $k => $v ) {
                                    if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                                            $retirosmes[$k] = $v;
                                    }
                            }

                            //Verificamos cada retiro por separado
                            foreach ($retirosmes as $key => $value) {
                              $diames = (int)$value["dia"];
                              //Vemos si es urgente o no
                              if ($value["urgente"] == 1) {
                                if ($diames <= 14) {
                                  $intgen = 0;
                                  $intacumes = round(($intacumes + $intgen), 2);
                                  $intacu = round(($intacu + $intgen), 2);
                                  $retmes = round(($retmes + $value["cantidadfin"]), 2);
                                } else if ($diames >= 15 && $diames < 30) {
                                  $intgen = round((0.05 * $value["cantidadfin"]), 2);
                                  $intacumes = round(($intacumes + $intgen), 2);
                                  $intacu = round(($intacu + $intgen), 2);
                                  $retmes = round(($retmes + $value["cantidadfin"]), 2);
                                }else{
                                  $intgen = round(($intmes * $value["cantidadfin"]), 2);
                                  $intacumes = round(($intacumes + $intgen), 2);
                                  $intacu = round(($intacu + $intgen), 2);
                                  $retmes = round(($retmes + $value["cantidadfin"]), 2);
                                }
                              }else{
                                //Vemos si es de dia 15 o dia 30
                                if ($value["fecha_cuando"] == 1) {
                                  $intgen = round((0.05 * $value["cantidadfin"]), 2);
                                  $intacumes = round(($intacumes + $intgen), 2);
                                  $intacu = round(($intacu + $intgen), 2);
                                  $retmes = round(($retmes + $value["cantidadfin"]), 2);


                                }else{
                                  $intgen = round(($intmes * $value["cantidadfin"]), 2);
                                  $intacumes = round(($intacumes + $intgen), 2);
                                  $intacu = round(($intacu + $intgen), 2);
                                  $retmes = round(($retmes + $value["cantidadfin"]), 2);
                                }
                              }
                            }
                          }

                          $capprin = round(($capprin - $retmes ), 2);
                          $intgencapprin = round(($intmes * $capprin ), 2);



                          //Evaluamos el interes de los depositos de este mes
                          if(count($depositos) == 0){
                          }else {

                            //Filtramos los depositos de este mes
                            $depositosmes = [];
                            foreach ( $depositos as $k => $v ) {
                                    if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                                            $depositosmes[$k] = $v;
                                    }
                            }


                            $capprin = round(($capprin + $retmes ), 2);

                            //Verificamos cada deposito por separado
                            foreach ($depositosmes as $key => $value) {

                              //Vemos si es de dia 1 o dia 15
                              if ($value["fecha_cuando"] == 1) {
                                $intgen = round(($intmes * $value["cantidad_real"]), 2);
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $depmes = round(($depmes + $value["cantidad_real"]), 2);


                              }else{
                                $intgen = round((0.05 * $value["cantidad_real"]), 2);
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $depmes = round(($depmes + $value["cantidad_real"]), 2);
                              }
                            }
                          }


                          $totalintmes = round(($intgencapprin + $intacumes ), 2);
                          $intacu = round(($intgencapprin + $intacu), 2);
                          $capprin = round(($capprin + $depmes ), 2);
                          $capprinstart = $capprin;
                          $totalacu =  round(($capprin + $totalintmes ) - $retmes, 2);

                          /*echo $meshoy;
                          echo "<br>";
                          echo $mes . "mes";
                          echo "<br>";*/

                          if (($i+1) <= $totalMonths ) {
                           }else {
                           }

                           if(($i+1) <= $mesinversor ){
                             if($agno >= 2023){
                               if (($agno == 2023 && $mes == 2) || ($agno == 2023 && $mes == 4) || ($agno == 2023 && $mes == 5) || ($agno == 2023 && $mes == 6) || ($agno == 2023 && $mes == 7) ) {
                                 $porutilmes = 0;
                                 $nintacu += $porutilmes;
                               }else{
                                 $porutilmes = round(($capprinstart * 0.01 ), 2);
                                 $nintacu += $porutilmes;
                               }
                             }else if ($mes >= 7 && $agno >= 2022) {
                               $porutilmes = round(($capprinstart * 0.01 ), 2);
                               $nintacu += $porutilmes;
                             }else {
                               // code...
                             }
                           }

                          // Antes de avanzar al siguiente mes vamos a capturar ese total generado por mes en un array
                          $totalrefxmesxuser[] = array(
                              'mes'=>$mes,
                              'tmes'=> $tmes,
                              'year'=> $agno,
                              'total'=>round($nintacu,2));

                          if ($mes == 12) {
                            $mes = 1;
                            $agno++;
                          }else{
                            $mes++;
                          }

                          if($mesesquedan < 11){
                            // checamos diferencia para saber con cuanto armo 12 mesesin
                            $difmeses = 12 - $mesesquedan;

                            if (($i+1) == $totalMonths-$difmeses ) {
                              $contorden = 1;
                            }else {
                              $contorden++;
                            }
                          }else{
                            if (($i+1) == $totalMonths-1 ) {
                              $contorden = 1;
                            }else {
                              $contorden++;
                            }
                          }

                        }else{

                          //Evaluamos el interes de los retiros de este mes
                          if(count($retiros) == 0){
                          }else {

                            //Filtramos los depositos de este mes
                            $retirosmes = [];
                            foreach ( $retiros as $k => $v ) {
                                    if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                                            $retirosmes[$k] = $v;
                                    }
                            }

                            //Verificamos cada retiro por separado
                            foreach ($retirosmes as $key => $value) {
                              $diames = (int)$value["dia"];
                              //Vemos si es urgente o no
                              if ($value["urgente"] == 1) {
                                if ($diames <= 14) {
                                  $intgen = 0;
                                  $intacumes = round(($intacumes + $intgen), 2);
                                  $intacu = round(($intacu + $intgen), 2);
                                  $retmes = round(($retmes + $value["cantidadfin"]), 2);
                                } else if ($diames >= 15 && $diames < 30) {
                                  $intgen = round((0 * $value["cantidadfin"]), 2);
                                  $intacumes = round(($intacumes + $intgen), 2);
                                  $intacu = round(($intacu + $intgen), 2);
                                  $retmes = round(($retmes + $value["cantidadfin"]), 2);
                                }else{
                                  $intgen = round(($intmes * $value["cantidadfin"]), 2);
                                  $intacumes = round(($intacumes + $intgen), 2);
                                  $intacu = round(($intacu + $intgen), 2);
                                  $retmes = round(($retmes + $value["cantidadfin"]), 2);
                                }
                              }else{
                                //Vemos si es de dia 15 o dia 30
                                if ($value["fecha_cuando"] == 1) {
                                  $intgen = round((0 * $value["cantidadfin"]), 2);
                                  $intacumes = round(($intacumes + $intgen), 2);
                                  $intacu = round(($intacu + $intgen), 2);
                                  $retmes = round(($retmes + $value["cantidadfin"]), 2);


                                }else{
                                  $intgen = round(($intmes * $value["cantidadfin"]), 2);
                                  $intacumes = round(($intacumes + $intgen), 2);
                                  $intacu = round(($intacu + $intgen), 2);
                                  $retmes = round(($retmes + $value["cantidadfin"]), 2);
                                }
                              }
                            }
                          }

                          $capprin = round(($capprin - $retmes ), 2);
                          $intgencapprin = round(($intmes * $capprin ), 2);



                          //Evaluamos el interes de los depositos de este mes
                          if(count($depositos) == 0){
                          }else {

                            //Filtramos los depositos de este mes
                            $depositosmes = [];
                            foreach ( $depositos as $k => $v ) {
                                    if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
                                            $depositosmes[$k] = $v;
                                    }
                            }


                            $capprin = round(($capprin + $retmes ), 2);

                            //Verificamos cada deposito por separado
                            foreach ($depositosmes as $key => $value) {

                              //Vemos si es de dia 1 o dia 15
                              if ($value["fecha_cuando"] == 1) {
                                $intgen = round(($intmes * $value["cantidad_real"]), 2);
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $depmes = round(($depmes + $value["cantidad_real"]), 2);


                              }else{
                                $intgen = round((0 * $value["cantidad_real"]), 2);
                                $intacumes = round(($intacumes + $intgen), 2);
                                $intacu = round(($intacu + $intgen), 2);
                                $depmes = round(($depmes + $value["cantidad_real"]), 2);
                              }
                            }
                          }


                          $totalintmes = round(($intgencapprin + $intacumes ), 2);
                          $intacu = round(($intgencapprin + $intacu), 2);
                          $capprin = round(($capprin + $depmes ), 2);
                          $capprinstart = $capprin;
                          $totalacu =  round(($capprin + $totalintmes ) - $retmes, 2);

                          /*echo $meshoy;
                          echo "<br>";
                          echo $mes . "mes";
                          echo "<br>";*/

                          if (($i+1) <= $totalMonths ) {
                           }else {
                           }

                           if(($i+1) <= $mesinversor ){
                             if($agno >= 2023){
                               if (($agno == 2023 && $mes == 2) || ($agno == 2023 && $mes == 4) || ($agno == 2023 && $mes == 5) || ($agno == 2023 && $mes == 6) || ($agno == 2023 && $mes == 7) ) {
                                 $porutilmes = 0;
                                 $nintacu += $porutilmes;
                               }else{
                                 $porutilmes = round(($capprinstart * 0.01 ), 2);
                                 $nintacu += $porutilmes;
                               }
                             }else if ($mes >= 7 && $agno >= 2022) {
                               $porutilmes = round(($capprinstart * 0.01 ), 2);
                               $nintacu += $porutilmes;
                             }else {
                               // code...
                             }
                           }

                          // Antes de avanzar al siguiente mes vamos a capturar ese total generado por mes en un array
                          $totalrefxmesxuser[] = array(
                              'mes'=>$mes,
                              'tmes'=> $tmes,
                              'year'=> $agno,
                              'total'=>round($nintacu,2));

                          if ($mes == 12) {
                            $mes = 1;
                            $agno++;
                          }else{
                            $mes++;
                          }

                          if($mesesquedan < 11){
                            // checamos diferencia para saber con cuanto armo 12 mesesin
                            $difmeses = 12 - $mesesquedan;

                            if (($i+1) == $totalMonths-$difmeses ) {
                              $contorden = 1;
                            }else {
                              $contorden++;
                            }
                          }else{
                            if (($i+1) == $totalMonths-1 ) {
                              $contorden = 1;
                            }else {
                              $contorden++;
                            }
                          }

                        }

                      }


                    }//si gay depositos

                    // En un array vamos a poner cuanta utilidad de referido (El array de sus meses trajo al final cada usuairo ref
                    $utilidadporuserref[] = array(
                        'idref'=> $userrefid,
                        'nombre'=>$nombreref,
                        'utilidadxmes'=> $totalrefxmesxuser);

                  }
                }

            }//foreach de todos los referidos


             //Vamos a llenar un array para ver cuanto esta aportando cada inversor al final del mes:
             //ESTO ES LO QUE ME DICE CUANTO SE CERRO EL USUARIO POR MES
             $aportacionporuserref[] = array(
                 'id'=> $userid,
                 'nombre'=>$nombre,
                 'totalxuserrefxmes'=> $utilidadporuserref);

           } // if si es inversionista
         }// if si user roles
     }

     }//foreach de todos los usuarios

     $resultado = null;
     if($primerdeposito){

       for ($i=0; $i < $mesesaconsiderar ; $i++) {

         $tmes = $mesesNombre[$pdmes];
         $totalcierremes = 0;

         foreach ($aportacionporuserref as $key => $usuario) {
           if (count($usuario['totalxuserrefxmes']) > 0) {

             // Vamos a recorrer los user referidos que tenga el usuario para ver si alguno ya ha generado algo de utilidad.
              foreach ($usuario['totalxuserrefxmes'] as $llave => $userref ) {
                if (count($userref['utilidadxmes']) > 0) {

                  // Vamos a recorrer los totales por mes del usuario ref para ver si alguno corresponde al mes en el que vamos segun el bucle for
                   foreach ($userref['utilidadxmes'] as $clave => $userrefmes ) {
                     // lo convertimos a decimal para que no haya problema
                     $tusermes = (float)$userrefmes['total'];

                     if ($userrefmes['mes'] == $pdmes && $userrefmes['year'] == $pdagno ) {
                       $totalcierremes = $totalcierremes+$tusermes ;

                     }

                   }
                 }

              }

           }
         }

         $totalpormes[] = array(
           'mes'=>$pdmes,
           'tmes'=> $tmes,
           'year'=> $pdagno,
           'total'=> round($totalcierremes, 2) );

           if ($pdmes == 12) {
             $pdmes = 1;
             $pdagno++;
           }else{
             $pdmes++;
           }

       }

       // Lo que vamos a imprimir es el array totalpormes, porque si hay deposito primero en el sistema ya no es null
       $resultado = $totalpormes;

     }
   // return $utilidadref;
   // Si quiero ver la utilidad desglosada por usuario:
   return $aportacionporuserref;
   // return $resultado;
 }

}
