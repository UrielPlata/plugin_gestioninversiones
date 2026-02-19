<?php


$inicio = 0;
$intacu = 0;
$mesestotales = count($registros);
$meses = 0;
$fechahoy = date("Y-m-d");
$fechaSeparada = explode("-", $fechahoy);
$meshoy = (int) $fechaSeparada[1];
$agnohoy = (int) $fechaSeparada[0];

foreach ($registros as $key => $value) {
  $mes = $key+1;
  $summes =  $value['total'];
  $retmes = 0;
  $statusmes = $mesesinv[$mes]["interes"];
  foreach ($retiros as $llave => $valor) {
    if ($valor["mes"] == $value["mes"] && $valor["agno"] == $value["agno"] ) {
      $retmes = $valor['total'];
    }else{

    }
  }
  $inicial =  round(($summes + $inicio), 2);
  $intmes =  round(($interes * $inicial), 2);
  $totalacu =  round(($inicial + $intmes)- $retmes, 2);
  $intacu = round(($intacu + $intmes), 2);

  $tsummes =  number_format($summes, 2);
  $tinicial =  number_format($inicial, 2);
  $tintmes =  number_format($intmes, 2);
  $ttotalacu =  number_format($totalacu, 2);
  $tintacu = number_format($intacu, 2);
  $tretmes = number_format($retmes, 2);

  // Extrae el año de una fecha dada
  $mesbd = (int) $value['mes'];
  $agnobd = (int) $value['agno'];

  if ($mesbd < $meshoy && $agnobd == $agnohoy) { ?>
  <tr class="mes-pasado">
  <?php }else if ($agnobd < $agnohoy) { ?>
  <tr class="mes-pasado">
  <?php }else { ?>
  <tr >
  <?php  } ?>

    <td><?php echo $mes ?></td>
    <td>$<?php echo $tsummes ?></td>
    <td>$<?php echo $tinicial ?></td>
    <td>$<?php echo $tintmes ?></td>
    <td>$<?php echo $tintacu ?></td>
    <td>$<?php echo $tretmes ?></td>
    <td>$<?php echo $ttotalacu ?></td>
    <td>$<?php echo $statusmes ?>%</td>
  </tr>
  <?php
  $inicio =  $totalacu;
  $meses++;
}

if ($meses < 12) {
  for ($i=$meses; $i < 12; $i++) {
    $summes =  0;
    $meses++;
    $retmes = 0;
    $statusmes = $mesesinv[$i]["interes"];
    $inicial =  round(($summes + $inicio), 2);
    $intmes =  round(($interes * $inicial), 2);
    $totalacu =  round(($inicial + $intmes) - $retmes, 2);
    $intacu = round(($intacu + $intmes), 2);

    $tsummes =  number_format($summes, 2);
    $tinicial =  number_format($inicial, 2);
    $tintmes =  number_format($intmes, 2);
    $ttotalacu =  number_format($totalacu, 2);
    $tintacu = number_format($intacu, 2);
    $tretmes = number_format($retmes, 2);

    ?>
    <tr>
      <td><?php echo $meses ?></td>
      <td>$<?php echo $tsummes ?></td>
      <td>$<?php echo $tinicial ?></td>
      <td>$<?php echo $tintmes ?></td>
      <td>$<?php echo $tintacu ?></td>
      <td>$<?php echo $tretmes ?></td>
      <td>$<?php echo $ttotalacu ?></td>
      <td><?php echo $statusmes ?>%</td>
    </tr>
    <?php
    $inicio =  $totalacu;

  }
}

$primerdepogral = $wpdb->get_results("SELECT month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidad_real, fecha_cuando, fecha_termino FROM $reghistorico WHERE status = 2 ORDER BY fecha_termino LIMIT 1 ", ARRAY_A);

$mesuno = $primerdepogral[0]["mes"];
$agnouno = (int) $primerdepogral[0]["year"];
$agno = $agnouno;
$fechaini = date($primerdepogral[0]["year"]."-".$primerdepogral[0]["mes"]."-01");
$inicio = 0;
$intacu = 0;
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
$meseshanpasado = $yearsInMonths + $months + 1;

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
        $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
        '8' => 'Agosto',
        '9' => 'Septiembre',
        '10' => 'Octubre',
        '11' => 'Noviembre',
        '12' => 'Diciembre' );
        $totalrdep = (float)$totaldep[0]['totaldep'];
        $totalrret = (float)$totalret[0]['totalret'];
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

          $mesuno = $depositos[0]["mes"];
          $agnouno = (int) $depositos[0]["agno"];
          $agno = $agnouno;
          $fechaini = date($depositos[0]["agno"]."-".$depositos[0]["mes"]."-01");
          $inicio = 0;
          $intacu = 0;
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
                      if ( $v['mes'] == $strmes &&  $v['agno'] == $agno ) {
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

            if($totalMonths == 0){
              $tsaldini = number_format($totalacu, 2, '.', ',');
              $tintacu = 0;
              $ttotaldep = number_format($totaldep[0]['totaldep'], 2, '.', ',');
              $totaldisp = 0;
              //Evaluamos si es un mes futuro
              if($fecha2 < $fecha1){
                $totalreal = 0;
                $ttotalreal = number_format($totalreal, 2, '.', ',');
                $totalrhoy = round((0 + $totalrdep)-$totalrret, 2);
                $ttotalrhoy = number_format($totalrhoy, 2, '.', ',');
              }else{
                $totalreal = 0;
                $ttotalreal = number_format($totalreal, 2, '.', ',');
                $totalrhoy = round(($intacu + $totalrdep)-$totalrret, 2);
                $ttotalrhoy = number_format($totalrhoy, 2, '.', ',');
              }
              $i = 12;
            }else{
              if (($i+1) == $totalMonths ) {
                $tsaldini = number_format($totalacu, 2, '.', ',');
                $tintacu = number_format($intacu, 2, '.', ',');
                $totaldisp = $totalacu;
                $ttotaldep = number_format($totaldep[0]['totaldep'], 2, '.', ',');
              }else{

              }


              if($totalMonths > 11){
                //cuando son +12  el realhoy es el ultimo y realmespasado son iguales
                $totalrhoy = round(($intacu + $totalrdep)-$totalrret, 2);
                $ttotalrhoy = number_format($totalrhoy, 2, '.', ',');
                $totalreal = round(($intacu + $totalrdep) - $totalrret, 2);
                $ttotalreal = number_format($totalreal, 2, '.', ',');
              }else if($totalMonths == 11){
                //cuando son 12 meses  el realhoy es el ultimo y realmespasado el del mes pasado
                $totalrhoy = round(($intacu + $totalrdep)-$totalrret, 2);
                $ttotalrhoy = number_format($totalrhoy, 2, '.', ',');
                if (($i+1) == 11) {
                  $totalreal = round(($intacu + $totalrdep) - $totalrret, 2);
                  $ttotalreal = number_format($totalreal, 2, '.', ',');
                }
              }else if(($i+1) == ($totalMonths+1)){
                $totalrhoy = round(($intacu + $totalrdep)-$totalrret, 2);
                $ttotalrhoy = number_format($totalrhoy, 2, '.', ',');
              }else if(($i+1) == ($totalMonths)){
                $totalreal = round(($intacu + $totalrdep) - $totalrret, 2);
                $ttotalreal = number_format($totalreal, 2, '.', ',');
              }else{

              }

            }


            if ($mes == 12) {
              $mes = 1;
              $agno++;
            }else{
              $mes++;
            }


          }

        }

        /*if ($userid == 8) {
          echo $ttotalreal;
          echo "<br>";
          echo $ttotalrhoy;
        }*/

        /*
        echo $userid;
        echo "<br>";
        echo $ttotalreal;
        echo "<br>";
        echo $ttotalrhoy;
        echo "<br>";
        */
        $calculos = new CRC_Calculo();
        $utilidadref = $calculos->crc_utilidad_referidos_cierre($userid);

        $utilidadporuser[] = array(
            'id'=> $userid,
            'utilidad'=> $utilidadref);

        //Vamos a llenar un array para ver cuanto esta aportando cada inversor al final del mes:
        $aportacionporuser[] = array(
            'id'=> $userid,
            'nombre'=>$nombre,
            'totalestemes'=> $totalrhoy,
            'utilidad'=> $utilidadref);

        $totutilref = $totutilref + $utilidadref;
        $totinvlm = $totinvlm + $totalreal;
        $totinvhoy = $totinvhoy + $totalrhoy ;
      }
    }


}
 ?>
