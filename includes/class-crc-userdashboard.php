<?php

class CRC_UserDashboard{



  public function interfaz_userdashboard(){
    $user_actual = get_current_user_id();
    $user_data = get_userdata( absint( $user_actual ) );
    $invitecode = get_user_meta($user_actual,'invitecode',true);
    $status = get_user_meta($user_actual,'status',true);
    $interes = ((int) $status / 100);
    $noncedep = wp_create_nonce( 'mi_nonce_dep' );
    $nonceret = wp_create_nonce( 'mi_nonce_ret' );

    global $wpdb;
    $tabla = $wpdb->prefix . 'depositos';
    $tabla2 = $wpdb->prefix . 'retiros';
    $tabla3 = $wpdb->prefix . 'mesesinv';
    $depositos = $wpdb->get_results("SELECT month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidad_real, fecha_cuando, fecha_termino FROM $tabla WHERE usuario = $user_actual AND status = 2 ORDER BY fecha_termino", ARRAY_A);
    $totaldep = $wpdb->get_results("SELECT ROUND(SUM(cantidad_real), 2) AS totaldep FROM $tabla WHERE usuario = $user_actual AND status = 2 ", ARRAY_A);
    $retiros = $wpdb->get_results("SELECT day(fecha_termino) AS dia, month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidadfin, urgente, fecha_cuando FROM $tabla2 WHERE usuario = $user_actual AND status = 2 ORDER BY fecha_termino", ARRAY_A);
    $mesesinv = $wpdb->get_results("SELECT mes, interes FROM $tabla3 WHERE usuario = $user_actual AND status = 1 ORDER BY id", ARRAY_A);
    $totalmeses = count($mesesinv);
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
      $mesesquedan = $totalmeses - $totalMonths;

      for ($i = 0 ; $i < $totalMonths; $i++) {
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

        // echo $meshoy;
        // echo "<br>";
        // echo $mes . "mes";
        // echo "<br>";

        if($totalMonths == 0){
          $tsaldini = number_format($totalacu, 2, '.', ',');
          $tintacu = 0;
          $ttotaldep = number_format($totaldep[0]['totaldep'], 2, '.', ',');
          $totaldisp = 0;
          $i = $totalMonths;
        }else{
          if (($i+1) == $totalMonths ) {
            $tsaldini = number_format($totalacu, 2, '.', ',');
            $tintacu = number_format($intacu, 2, '.', ',');
            $totaldisp = $totalacu;
            $ttotaldep = number_format($totaldep[0]['totaldep'], 2, '.', ',');
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
    ?>
    <div class="wrap">
      <div class="col-titulo">
        <h1>Dashboard Principal</h1>
        <img class="logo-theinc" src="" alt="logo_theinc">
      </div>
      <hr>
      <div class="cuerpo-dashboard">
        <div class="col-dashboard col-status">
          <div class="caja-invitecode">
            Tu código de Invitación: <span><?php echo $invitecode; ?></span>
          </div>
          <div class="caja-interes">
            Inter&eacute;s: <span><?php echo (($status != '') ?  $status : "0" ) ?></span>%
          </div>
        </div>
        <?php add_thickbox(); ?>
        <?php if($status != ''){
          $calculos = new CRC_Calculo();
          $utilidadref = $calculos->crc_utilidad_referidos($user_actual);
          $tutilidadref = number_format($utilidadref, 2, '.', ',');
          // echo "<pre>";
          // echo var_dump($utilidadref);
          // echo "</pre>";
        ?>
        <div class="col-dashboard col-cajitas">
          <div class="caja caja-inicial">
            <span class='material-icons inicial'>flag</span>
            <div class="info-box-content">
              <span class="info-box-text">Capital principal</span>
              <span class="info-box-number">$<?php echo $ttotaldep ?></span>
            </div>
          </div>
          <div class="caja caja-utilidad">
            <span class='material-icons utilidad'>insights</span>
            <div class="info-box-content">
              <span class="info-box-text ">Utilidad generada</span>
              <span class="info-box-number">$<?php echo $tintacu ?></span>
            </div>
          </div>
          <div class="caja caja-referido">
            <span class='material-icons referidos'>people</span>
            <div class="info-box-content">
              <span class="info-box-text">Utilidad referidos</span>
              <span class="info-box-number">$<?php echo $tutilidadref ?></span>
            </div>
          </div>
        </div>
        <div class="col-dashboard col-botones">
          <input alt="#TB_inline?width=400&inlineId=modal-deposito" title="Solicitar un dep&oacute;sito" class="thickbox button button-primary button-large button-depositar" type="button" value="Depósito" />

          <div id="modal-deposito" style="display:none;" >
            <form id="form-solicitadep" class="" action="" method="post">
              <input type="hidden" id="userdep" name="userdep" value="<?php echo $user_actual ?>">
              <input type="hidden" id="userint" name="userint" value="<?php echo $status ?>">
              <input type="hidden" name="nonce" value="<?php echo $noncedep ?>">
              <div class="campo">
                <label for="fechadep">Fecha del depósito: </label>
                <select id="fechadep" name="fechadep">
                  <option value="" selected>Seleccione una fecha</option>
                  <option value="1">Día 1 del mes</option>
                  <option value="15">Día 15 del mes</option>
                </select>
              </div>
              <div class="campo">
                <label for="cantidaddep"><i class="fa fa-user"></i>Cantidad a depositar: </label>
                <input id="cantidaddep" type="text" name="cantidad" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
              </div>
              <div class="campo-especial">
                <input id="registrardep" type="submit" name="depositar" class="button button-primary" value="Depositar">
                <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
              </div>
            </form>
          </div>

          <input alt="#TB_inline?width=400&inlineId=modal-retiro" title="Solicitar un retiro" class="thickbox button button-primary button-large button-retirar" type="button" value="Retiro" />

          <div id="modal-retiro" style="display:none;" >
            <form id="form-solicitaret" class="" action="" method="post">
              <input type="hidden" id="userret" name="userret" value="<?php echo $user_actual ?>">
              <input type="hidden" name="nonce" value="<?php echo $nonceret ?>">
              <input type="hidden" id="totaldisp" name="totaldisp" value="<?php echo $totaldisp ?>">
              <div class="campo">
                <label for="urgenteret">¿Es un retiro urgente?</label>
                <input type="checkbox" id="urgenteret" name="urgenteret" value="urgente">
              </div>
              <div class="campo">
                <label for="fecharet">Fecha del retiro: </label>
                <select id="fecharet" name="fecharet">
                  <option value="" selected>Seleccione una fecha</option>
                  <option value="15">Día 15 del mes</option>
                  <option value="30">Día último del mes</option>
                </select>
              </div>
              <div class="campo">
                <label for="cantidadret"><i class="fa fa-user"></i>Cantidad a retirar: </label>
                <input id="cantidadret" type="text" name="cantidad" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
              </div>
              <div class="campo-especial">
                <input id="registrarret" type="submit" name="registrar" class="button button-primary" value="Solicitar">
                <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
              </div>
            </form>
          </div>
        </div>
      <?php } ?>

      <h3>Proyeccion del inversionista</h3>
      <br>
      <br>
      <?php  if($status != ''){  ?>
      <?php
      // echo "<pre>";
      // echo var_dump($totalMonths);
      // echo "</pre>";
      ?>
      <table class="wp-list-table widefat tab-dark striped tab-proyecinv tab-userproyecinv">
        <thead>
          <tr>
            <th class="manage_column" >O</th>
            <th class="manage_column" >#</th>
            <th class="manage_column" >Año - Mes</th>
            <th class="manage_column" >Depósitos</th>
            <th class="manage_column" >Cap Inicial Mes</th>
            <th class="manage_column" >Utilidad</th>
            <th class="manage_column" >Utilidad acumulada</th>
            <th class="manage_column" >Retiros</th>
            <th class="manage_column" >Total</th>
            <th class="manage_column" >Status</th>

          </tr>
        </thead>

        <tbody>
          <?php
          if (!$nohay) {

            $mesuno = $depositos[0]["mes"];
            $agnouno = (int) $depositos[0]["agno"];
            $agno = $agnouno;
            $fechaini = date($depositos[0]["agno"]."-".$depositos[0]["mes"]."-01");
            $inicio = 0;
            $intacu = 0;
            $totalacu = 0;
            $mes = (int) $mesuno;
            $contorden = 13;
            if ($totalMonths < 1 ) {
              $mesesprint = 12;
            }else{
              if($mesesquedan < 12){
                $mesesprint = $totalMonths + $mesesquedan;
              }else {
                $mesesprint = $totalMonths + 11;
              }

            }

            for ($i = 0 ; $i < $mesesprint; $i++) {
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
                        if ( $v['mes'] == $strmes &&  $v['agno'] == $agno ) {
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

              /*echo $meshoy;
              echo "<br>";
              echo $mes . "mes";
              echo "<br>";*/

              if (($i+1) <= $totalMonths ) { ?>
              <tr class="mes-pasado">
              <?php }else { ?>
              <tr >
              <?php  }
              ?>
                <td><?php echo $contorden ?></td>
                <td><?php echo $i+1 ?></td>
                <td><?php echo $agno." - ".$tmes ?></td>
                <td><span class="verde btn-ver-dep" data-userid="<?php echo $user_actual ?>" data-mes="<?php echo $mes ?>" data-agno="<?php echo $agno ?>">+ $<?php echo number_format($depmes, 2, '.', ','); ?></span></td>
                <td>$<?php echo number_format($capprin, 2, '.', ','); ?></td>
                <td>$<?php echo number_format($totalintmes, 2, '.', ',');?></td>
                <td>$<?php echo number_format($intacu, 2, '.', ','); ?></td>
                <td><span class="rojo btn-ver-ret" data-userid="<?php echo $user_actual ?>" data-mes="<?php echo $mes ?>" data-agno="<?php echo $agno ?>">- $<?php echo number_format($retmes, 2, '.', ','); ?></span></td>
                <td><span class="final">$<?php echo number_format($totalacu, 2, '.', ','); ?></span></td>
                <td><?php echo $statusmes ?>%</td>
              </tr>
              <?php
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


           ?>
        </tbody>
      </table>
      <?php } ?>
      <br>
      <hr>
      <p>Notas: <br>
        <ol>
          <li>La tabla muestra la proyección de los meses cerrados al día de hoy más 12 meses en el futuro. En caso de tener autorizados por el administrador menos de 12 meses a futuro, entonces solo se mostrarán los meses a futuro restantes.</li>
          <li>Los depósitos del día 15 de mes generarán unicamente el 5% de interés durante el mes en que se autorizó el depósito.</li>
          <li>Los retiros del día 15 de mes generarán unicamente el 5% de interés durante el mes en que se utorizó  el retiro.</li>
        </ol>
      </p>
      <br>
      <div class="caja-morada mt-0">
          <?php
          $calculos = new CRC_Calculo();
          $utilidadref = $calculos->totalcuentas_utilref_individual_detalle($user_actual);

          //calculamos la diferencia de meses
          $fechahoy = date("Y-m-d");
          $fechaSeparada = explode("-", $fechahoy);
          $meshoy = (int) $fechaSeparada[1];
          $agnohoy = (int) $fechaSeparada[0];

          if ($meshoy == 1) {
            $mesint = 12;
            $agno = $agnohoy-1;
          }else{
            $mesint = $meshoy-1;
            $agno = $agnohoy;
          }

           ?>
          <h3 class="mt-3 mb-4">Lista de Usuarios Referidos activos:</h3>
          <br>
          <table class="wp-list-table widefat tab-dark striped tab-admininvrefmes">
            <thead>
              <tr>
                <th class="manage_column" >#</th>
                <th class="manage_column" >Usuario Referido</th>
                <th class="manage_column" >Utilidad Generada</th>
              </tr>
            </thead>

            <tbody>
              <?php
              if ( count($utilidadref[0]["totalxuserrefxmes"]) == 0) {              // code...
              } else {
                $numref = 1;
                //Si tiene invitados entonces recorremos el array de invitados
                foreach ($utilidadref[0]["totalxuserrefxmes"] as $code => $invitado) {

                  // Revisamos si los invitados tienen alguna generacion de utilidades para el user
                  if (count($invitado["utilidadxmes"]) == 0) {

                  }else {
                    // Si la tienen recorremos meses y checamos que sea del mismo mes para sumarla
                    foreach ($invitado["utilidadxmes"] as $chiave => $mesdelref) {

                      if ($mesdelref["mes"] == $mesint && $mesdelref["year"] == $agno) {
                        $totalref = number_format($mesdelref["total"], 2, '.', ',');
                        ?>
                        <tr>
                          <td><?php echo $numref ; ?></td>
                          <td><?php echo $invitado["nombre"]; ?></td>
                          <td><?php echo "$".$totalref; ?></td>
                        </tr>
                        <?php
                        $numref++;
                      }

                    }
                  }

                }
              } ?>
            </tbody>
          </table>
          <br>
          <hr>
          <p>Notas: <br>
            <ol>
              <li>La utilidad de referido corresponde a la utilidad acumulada que el usuario le ha generado como referido hasta el cierre del ultimo mes finalizado anterior.</li>
              <li>Solo se muestran los usuarios referidos que esten activos y tengan algun depósito validado, es decir que ya hayan empezado a generar utilidades. </li>
            </ol>
          </p>
        </div>
      </div>


    </div>


    <?php
  }

  public function interfaz_verdepmes(){
    global $wpdb;
    $user_actual = get_current_user_id();
    $mesint = (int) $_GET['m'];
    //$mes = printf('%02d', $mesint);
    $agno = $_GET['p'];
    $user_data = get_userdata( absint( $user_actual ) );
    $unombre = $user_data->first_name ." ". $user_data->last_name;
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );
    $tmes = $mesesNombre[$mesint];
    $depositos = $wpdb->prefix . 'depositos';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $depositos WHERE usuario = $user_actual AND status = 2 AND MONTH(fecha_termino) = $mesint AND YEAR(fecha_termino) = $agno ORDER BY id ASC", ARRAY_A);
    /*echo "<pre>";
    var_dump($mes);
    var_dump($agno);
    var_dump($registros);
    echo "</pre>";*/
    ?>

    <div class="wrap">
      <div class="col-titulo">
        <h1>Depósitos <?php echo $tmes ." ". $agno ?></h1>
        <img class="logo-theinc" src="" alt="logo_theinc">
      </div>
      <hr>
      <div class="cuerpo-dashboard">
        <br>
        <h2>Lista de dep&oacute;sitos válidos:</h2>
        <table class="wp-list-table widefat tab-dark dt-responsive striped tab-invmesdepositos">
          <thead>
            <tr>
              <th class="manage_column" >#</th>
              <th class="manage_column" >Cantidad</th>
              <th class="manage_column" >Cantidad final</th>
              <th class="manage_column" >Notas</th>
              <th class="manage_column" >Fecha deposito</th>
              <th class="manage_column" >Status</th>
              <th class="manage_column" >Id Depósito a TD</th>
              <th class="manage_column" >Id Depósito a Master</th>
              <th class="manage_column" >Fecha solicitud</th>
              <th class="manage_column" >Fecha autorización</th>
            </tr>
          </thead>

          <tbody>
            <?php
              if(count($registros) != 0){
                foreach ($registros as $key => $value) {

                  $fecha_cuando = $value["fecha_cuando"];
                  if($fecha_cuando == 1){
                    $fechadep = 'Día 1 del mes';
                  }else{
                    $fechadep = 'Día 15 del mes';
                  }

                  $status = $value["status"];
                  if ($status == 0) {
                    $statusc = "Generada";
                  }else if($status == 1){
                    $statusc = "Confirmada";
                  }else if($status == 3){
                    $statusc = "Cancelada";
                  }else{
                    $statusc = "Autorizada";
                  }
                  if(!$value["idmov_ind"]){
                    $idmov_ind = "";
                  }else{
                    $idmov_ind = $value["idmov_ind"];
                  }

                  if(!$value["idmov_gral"]){
                    $idmov_gral = "";
                  }else{
                    $idmov_gral = $value["idmov_gral"];
                  }

                  if(!$value["fecha_termino"]){
                    $fechafin = "";
                  }else{
                    $fechafin = $value["fecha_termino"];
                  }

                  if($value["cantidad_real"] == 0){
                    $cantidadreal = "";
                  }else{
                    $cantidadreal = "$".number_format($value["cantidad_real"], 2);
                  }

                  $fecha = substr($value["fecha_deposito"], 0, 10);

                  $cantidad  = "$".number_format($value["cantidad"], 2);
                  ?>
                  <tr>
                    <td><?php echo $key+1 ?></td>
                    <td><?php echo $cantidad; ?></td>
                    <td><?php echo $cantidadreal; ?></td>
                    <td><button aria-label='<?php echo $value["notas"]; ?>' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button></td>
                    <td><?php echo $fechadep; ?></td>
                    <td><?php echo $statusc; ?></td>
                    <td><?php echo $idmov_ind; ?></td>
                    <td><?php echo $idmov_gral; ?></td>
                    <td><?php echo $fecha; ?></td>
                    <td><?php echo $fechafin; ?></td>
                  </tr>
                  <?php
                }
              }
             ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php
  }

  public function interfaz_verretmes(){
    global $wpdb;
    $user_actual = get_current_user_id();
    $mesint = (int) $_GET['m'];
    //$mes = printf('%02d', $mesint);
    $agno = $_GET['p'];
    $user_data = get_userdata( absint( $user_actual ) );
    $unombre = $user_data->first_name ." ". $user_data->last_name;
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );
    $tmes = $mesesNombre[$mesint];
    $retiros = $wpdb->prefix . 'retiros';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $retiros WHERE usuario = $user_actual AND status = 2 AND MONTH(fecha_termino) = $mesint AND YEAR(fecha_termino) = $agno ORDER BY id ASC", ARRAY_A);
    /*echo "<pre>";
    var_dump($mes);
    var_dump($agno);
    var_dump($registros);
    echo "</pre>";*/
    ?>

    <div class="wrap">
      <div class="col-titulo">
        <h1>Retiros <?php echo $tmes ." ". $agno ?> </h1>
        <img class="logo-theinc" src="" alt="logo_theinc">
      </div>
      <hr>
      <div class="cuerpo-dashboard">
        <br>
        <h2>Lista de retiros válidos:</h2>
        <table class="wp-list-table widefat tab-dark dt-responsive striped tab-invmesretiros">
          <thead>
            <tr>
              <th class="manage_column" >#</th>
              <th class="manage_column" >Cantidad</th>
              <th class="manage_column" >Cantidad final</th>
              <th class="manage_column" >Notas</th>
              <th class="manage_column" >Urgente</th>
              <th class="manage_column" >Fecha retiro</th>
              <th class="manage_column" >Status</th>
              <th class="manage_column" >Id Depósito a TD</th>
              <th class="manage_column" >Fecha solicitud</th>
              <th class="manage_column" >Fecha autorización</th>
            </tr>
          </thead>

          <tbody>
            <?php
              if(count($registros) != 0){
                foreach ($registros as $key => $value) {

                  $urgente = $value["urgente"];
                  $fecha_cuando = $value["fecha_cuando"];
                  if($urgente == 1){
                    $esurgente = "Sí";
                    $fecharet = 'Urgente (Inmediato)';
                  }else{
                    $esurgente = "No";
                    if($fecha_cuando == 1){
                      $fecharet = 'Día 15 del mes';
                    }else{
                      $fecharet = 'Finales del mes';
                    }
                  }

                  $cantidad  = "$".number_format($value["cantidad"], 2);
                  $cantidadi  = number_format($value["cantidad"], 2);

                  if(!$value["cantidadfin"]){
                    $cantidadreal = "";
                    $cantidadfini = "0.00";
                  }else{
                    $cantidadreal = "$".number_format($value["cantidadfin"], 2);
                    $cantidadfini = $value["cantidadfin"];
                  }

                  if(!$value["notas"]){
                    $notas = "";
                  }else{
                    $notas = $value["notas"];
                  }

                  $status = $value["status"];
                  if ($status == 0) {
                    $statusc = "Generada";
                  }else if($status == 1){
                    $statusc = "Confirmada";
                  }else if($status == 3){
                    $statusc = "Cancelada";
                  }else{
                    $statusc = "Autorizada";
                  }

                  if(!$value["idmov_ind"]){
                    $idmov_ind = "";
                  }else{
                    $idmov_ind = $value["idmov_ind"];
                  }

                  if(!$value["idmov_gral"]){
                    $idmov_gral = "";
                  }else{
                    $idmov_gral = $value["idmov_gral"];
                  }

                  if(!$value["fecha_termino"]){
                    $fechafin = "";
                  }else{
                    $fechafin = $value["fecha_termino"];
                  }

                  $fecha = substr($value["fecha_retiro"], 0, 10);

                  ?>
                  <tr>
                    <td><?php echo $key+1 ?></td>
                    <td><?php echo $cantidad; ?></td>
                    <td><?php echo $cantidadreal; ?></td>
                    <td><button aria-label='<?php echo $value["notas"]; ?>' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button></td>
                    <td><?php echo $esurgente; ?></td>
                    <td><?php echo $fecharet; ?></td>
                    <td><?php echo $statusc; ?></td>
                    <td><?php echo $idmov_ind; ?></td>
                    <td><?php echo $fecha; ?></td>
                    <td><?php echo $fechafin; ?></td>
                  </tr>
                  <?php
                }
              }
             ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php
  }

  public function interfaz_historetiros(){
    ?>
    <div class="wrap">
      <div class="col-titulo">
        <h1>Historial de retiros</h1>
        <img class="logo-theinc" src="" alt="logo_theinc">
      </div>
    <hr>
    <br>
    <br>
    <table class="wp-list-table widefat tab-dark dt-responsive striped tab-historetiros">
      <thead>
        <tr>
          <th class="manage_column" >#</th>
          <th class="manage_column" >Cantidad</th>
          <th class="manage_column" >Cantidad real</th>
          <th class="manage_column" >Urgente</th>
          <th class="manage_column" >Fecha retiro</th>
          <th class="manage_column" >Status</th>
          <th class="manage_column" >Id Depósito a TD</th>
          <th class="manage_column" >Fecha solicitud</th>
          <th class="manage_column" >Fecha autorización</th>
        </tr>
      </thead>

      <tbody>

      </tbody>
    </table>

    </div>
    <?php
  }

  public function interfaz_histodepositos(){
    ?>
    <div class="wrap">
      <div class="col-titulo">
        <h1>Historial de dep&oacute;sitos</h1>
        <img class="logo-theinc" src="" alt="logo_theinc">
      </div>

    <hr>
    <br>
    <br>
    <table class="wp-list-table widefat tab-dark dt-responsive striped tab-histodepositos">
      <thead>
        <tr>
          <th class="manage_column" >#</th>
          <th class="manage_column" >Cantidad</th>
          <th class="manage_column" >Cantidad final</th>
          <th class="manage_column" >Fecha deposito</th>
          <th class="manage_column" >Status</th>
          <th class="manage_column" >Id Depósito a TD</th>
          <th class="manage_column" >Id Depósito a Master</th>
          <th class="manage_column" >Fecha solicitud</th>
          <th class="manage_column" >Fecha autorización</th>
        </tr>
      </thead>

      <tbody>

      </tbody>
    </table>

    </div>
    <?php
  }

  public function interfaz_agredashboard(){
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    $modagresivo = get_user_meta( $user, 'modagresivo', true);
    $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "agresivo";
    $submenu = "agredashboard";

    // Si no tiene acceso lo regresamos al dashboard
    if ($modagresivo != 1) {
      ?>
      <script type="text/javascript">
        let protocol = window.location.protocol;
        let url = "admin.php?page=crc_userdashboard";
        window.location.replace(protocol+url);
      </script>
      <?php
      die();
    }

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // echo "<pre>";
    // var_dump($modagresivo);
    // var_dump($modagrepart);
    // echo "</pre>";
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-user.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Dashboard - Agresivo</h1>
              </div>

              <div class="ui-dashboard">
                <!-- cajas superiores -->
                <div class="container-fluid px-0 my-4 pt-5 pb-4">
                  <div class="row">
                    <div class="col-12 col-md-3 ">
                      <div class="rd-cajasup rd-cajasup-azul">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-flag"></i></span>
                        <h4>Capital Principal</h4>
                        <span class="rd-cajasup-cifra">$</span>
                      </div>
                    </div>
                    <div class="col-12 col-md-3 ">
                      <div class="rd-cajasup rd-cajasup-verde">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-chart-line"></i></span>
                        <h4>Utilidad generada</h4>
                        <span class="rd-cajasup-cifra">$</span>
                      </div>
                    </div>
                    <div class="col-12 col-md-3 ">
                      <div class="rd-cajasup rd-cajasup-gris">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-user-group"></i></span>
                        <h4>Utilidad referidos</h4>
                        <span class="rd-cajasup-cifra">$</span>
                      </div>
                    </div>
                    <div class="col-12 col-md-3 ">
                      <div class="rd-cajasup rd-cajasup-naranja">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-file-circle-check"></i></span>
                        <h4>Total</h4>
                        <span class="rd-cajasup-cifra">$</span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Botones de deposito y retiro -->
                <div class="oper-master row mt-4 pt-3">
                  <div class="oper-mes oper-mes1 col-12 col-md-6 d-flex flex-column ">
                    <div class="caja-agredep d-flex ">
                      <button class='btn-addagredep d-flex' id='btn-addagredep' type='button' data-bs-toggle='modal' data-bs-target='#modal-addagredep' >
                        <span class="oper-agredep"><i class="fa-solid fa-arrow-down"></i></span>
                        <div class="d-flex flex-column">
                          <span class="oper-titulo">Depósito</span>
                        </div>
                      </button>
                    </div>
                    <table class="wp-list-table widefat tab-ui striped tab-agredep">
                      <thead>
                        <tr>
                          <th class="manage_column" >Fecha</th>
                          <th class="manage_column" >Cantidad</th>
                          <th class="manage_column" >Status</th>
                        </tr>
                      </thead>

                      <tbody>

                      </tbody>
                    </table>
                  </div>
                  <div class="oper-mes oper-mes2 col-12 col-md-6 d-flex flex-column mt-4 mt-md-0">
                    <div class="caja-agreret d-flex">
                      <button class='btn-addagreret d-flex' id='btn-addagreret' type='button' data-bs-toggle='modal' data-bs-target='#modal-addagreret' >
                        <span class="oper-agreret"><i class="fa-solid fa-arrow-up"></i></span>
                        <div class="d-flex flex-column">
                          <span class="oper-titulo">Retiro</span>
                        </div>
                      </button>
                    </div>
                    <table class="wp-list-table widefat tab-ui striped tab-agreret">
                      <thead>
                        <tr>
                          <th class="manage_column" >Fecha</th>
                          <th class="manage_column" >Cantidad</th>
                          <th class="manage_column" >Status</th>
                        </tr>
                      </thead>

                      <tbody>

                      </tbody>
                    </table>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-addagredep" tabindex="-1" aria-labelledby="modal-addagredepLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-addagredepLabel">Solicitar depósito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-addagredep" class="form-addagredep" action="#" method="post">
                          <input type="hidden" id="userdep" name="userdep" value="<?php echo $user ?>">
                          <div class="campo">
                            <label for="dagr_cantidad">*Cantidad: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="dagr_cantidad" class="form-control" type="text" name="dagr_cantidad" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo-especial">
                            <input id="agregaragredep" type="submit" name="agregaragredep" class="button button-primary" value="Solicitar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-addagreret" tabindex="-1" aria-labelledby="modal-addagreretLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-addagreretLabel">Solicitar retiro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-addagreret" class="form-addagreret" action="#" method="post">
                          <input type="hidden" id="userret" name="userret" value="<?php echo $user ?>">
                          <input type="hidden" id="totaldisp" name="totaldisp" value="10000">
                          <div class="campo">
                            <label for="ragr_cantidad">*Cantidad: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="ragr_cantidad" class="form-control" type="text" name="ragr_cantidad" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo-especial">
                            <input id="agregaragreret" type="submit" name="agregaragreret" class="button button-primary" value="Solicitar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

              </div><!-- ui-dashboard -->
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_agrehistodepositos(){
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    $modagresivo = get_user_meta( $user, 'modagresivo', true);
    $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "agresivo";
    $submenu = "agrehistodepositos";

    // Si no tiene acceso lo regresamos al dashboard
    if ($modagresivo != 1) {
      ?>
      <script type="text/javascript">
        let protocol = window.location.protocol;
        let url = "admin.php?page=crc_userdashboard";
        window.location.replace(protocol+url);
      </script>
      <?php
      die();
    }

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // echo "<pre>";
    // var_dump($modagresivo);
    // var_dump($modagrepart);
    // echo "</pre>";
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-user.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Historial de dep&oacute;sitos</h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_agresivo" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>
              </div><!-- ui-dashboard -->

              <div class="box-transparent mb-3">
                <h4 class="mb-5">Historial de depósitos</h4>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-agrehistodepositos">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Cantidad final</th>
                      <th class="manage_column" >Status</th>
                      <th class="manage_column" >Id Depósito a TD</th>
                      <th class="manage_column" >Id Depósito a Master</th>
                      <th class="manage_column" >Fecha solicitud</th>
                      <th class="manage_column" >Fecha autorización</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
              </div>

            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php

  }

  public function interfaz_agrehistoretiros(){
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    $modagresivo = get_user_meta( $user, 'modagresivo', true);
    $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "agresivo";
    $submenu = "agrehistoretiros";

    // Si no tiene acceso lo regresamos al dashboard
    if ($modagresivo != 1) {
      ?>
      <script type="text/javascript">
        let protocol = window.location.protocol;
        let url = "admin.php?page=crc_userdashboard";
        window.location.replace(protocol+url);
      </script>
      <?php
      die();
    }

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // echo "<pre>";
    // var_dump($modagresivo);
    // var_dump($modagrepart);
    // echo "</pre>";
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-user.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Historial de retiros</h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_agresivo" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>
              </div><!-- ui-dashboard -->

              <div class="box-transparent mb-3">
                <h4 class="mb-5">Historial de retiros</h4>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-agrehistoretiros">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Cantidad final</th>
                      <th class="manage_column" >Status</th>
                      <th class="manage_column" >Id Depósito a TD</th>
                      <th class="manage_column" >Fecha solicitud</th>
                      <th class="manage_column" >Fecha autorización</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
              </div>

            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php

  }

  public function interfaz_consdashboard(){
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    $modconservador = get_user_meta( $user, 'modconservador', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "conservador";
    $submenu = "consdashboard";

    // Si no tiene acceso lo regresamos al dashboard
    if ($modconservador != 1) {
      ?>
      <script type="text/javascript">
        let protocol = window.location.protocol;
        let url = "admin.php?page=crc_userdashboard";
        window.location.replace(protocol+url);
      </script>
      <?php
      die();
    }

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }

    global  $wpdb;
    $tabladep = $wpdb->prefix . 'depositos_con';
    $tablaret = $wpdb->prefix . 'retiros_con';
    // $usw = 54;
    $calculos = new CRC_ConsCalculo();
    $detalleregistros = $calculos->crc_datosfull_consinvestor($user);


    if(count($detalleregistros) == 1){

      $ultmescerrado = $detalleregistros[count($detalleregistros)-1];
      $ultmesc = (int)$ultmescerrado["mes"];
      $ultyearc = (int)$ultmescerrado["year"];

      if ($ultmesc == 12) {
        $mesfut = 1;
        $yearfut = $ultyearc+1;
      }else {
        $mesfut = $ultmesc+1;
        $yearfut = $ultyearc;
      }

      $fechafut = $yearfut."-".$mesfut."-01";

      // Obtener depositos futuros
      $totalfuturdep = $wpdb->get_results("SELECT ROUND(SUM(dcon_cantidad_real), 2) AS totalfuturdep FROM $tabladep WHERE dcon_usuario = $user AND dcon_status = 2 AND dcon_fecha_termino >= '".$fechafut."'" , ARRAY_A);
      $ttotalfuturdep = (float) $totalfuturdep[0]["totalfuturdep"];

      // Obtener retiros futuros
      $totalfuturret = $wpdb->get_results("SELECT ROUND(SUM(rcon_cantidad_real), 2) AS totalfuturret FROM $tablaret WHERE rcon_usuario = $user AND rcon_status = 2 AND rcon_fecha_termino >= '".$fechafut."'" , ARRAY_A);
      $ttotalfuturret = (float) $totalfuturret[0]["totalfuturret"];

      $capini = $ultmescerrado["capini"];
      $tcapini = number_format($capini+$ttotalfuturdep,2);
      $utilacum = 0.00;
      $tutilacum = number_format($utilacum,2);
      $totalcierremes = $capini ;
      $ttotalcierremes = number_format($totalcierremes+$ttotalfuturdep-$ttotalfuturret,2);

    }else if (count($detalleregistros) >= 1) {

      $ultmescerrado = $detalleregistros[count($detalleregistros)-2];
      $ultmesc = (int)$ultmescerrado["mes"];
      $ultyearc = (int)$ultmescerrado["year"];

      if ($ultmesc == 12) {
        $mesfut = 1;
        $yearfut = $ultyearc+1;
      }else {
        $mesfut = $ultmesc+1;
        $yearfut = $ultyearc;
      }

      $fechafut = $yearfut."-".$mesfut."-01";

      // Obtener depositos futuros
      $totalfuturdep = $wpdb->get_results("SELECT ROUND(SUM(dcon_cantidad_real), 2) AS totalfuturdep FROM $tabladep WHERE dcon_usuario = $user AND dcon_status = 2 AND dcon_fecha_termino >= '".$fechafut."'" , ARRAY_A);
      $ttotalfuturdep = (float) $totalfuturdep[0]["totalfuturdep"];

      // Obtener retiros futuros
      $totalfuturret = $wpdb->get_results("SELECT ROUND(SUM(rcon_cantidad_real), 2) AS totalfuturret FROM $tablaret WHERE rcon_usuario = $user AND rcon_status = 2 AND rcon_fecha_termino >= '".$fechafut."'" , ARRAY_A);
      $ttotalfuturret = (float) $totalfuturret[0]["totalfuturret"];

      $capini = $ultmescerrado["capini"];
      $tcapini = number_format($capini+$ttotalfuturdep,2);
      $utilacum = $ultmescerrado["utilacumulada"];
      $tutilacum = number_format($utilacum,2);
      $totalcierremes = $ultmescerrado["totalcierremes"];
      $ttotalcierremes = number_format($totalcierremes+$ttotalfuturdep-$ttotalfuturret,2);

    }else{
      $capini = 0.00;
      $tcapini = number_format($capini,2);
      $utilacum = 0.00;
      $tutilacum = number_format($utilacum,2);
      $totalcierremes = 0.00;
      $ttotalcierremes = number_format($totalcierremes,2);
    }
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-user.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Dashboard - Conservador</h1>
              </div>

              <div class="ui-dashboard">
                <!-- cajas superiores -->
                <div class="container-fluid px-0 my-4 pt-5 pb-4">
                  <div class="row">
                    <div class="col-12 col-md-4 ">
                      <div class="rd-cajasup rd-cajasup-azul">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-flag"></i></span>
                        <h4>Capital Principal</h4>
                        <span class="rd-cajasup-cifra">$<?php echo $tcapini ; ?></span>
                      </div>
                    </div>
                    <div class="col-12 col-md-4 ">
                      <div class="rd-cajasup rd-cajasup-verde">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-chart-line"></i></span>
                        <h4>Utilidad acumulada</h4>
                        <span class="rd-cajasup-cifra">$<?php echo $tutilacum ; ?></span>
                      </div>
                    </div>
                    <div class="col-12 col-md-4 ">
                      <div class="rd-cajasup rd-cajasup-naranja">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-file-circle-check"></i></span>
                        <h4>Total</h4>
                        <span class="rd-cajasup-cifra">$<?php echo $ttotalcierremes ; ?></span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Botones de deposito y retiro -->
                <div class="oper-master row mt-4 pt-3">
                  <div class="oper-mes oper-mes1 col-12 col-md-6 d-flex flex-column ">
                    <div class="caja-consdep d-flex ">
                      <button class='btn-addconsdep d-flex' id='btn-addconsdep' type='button' data-bs-toggle='modal' data-bs-target='#modal-addconsdep' >
                        <span class="oper-consdep"><i class="fa-solid fa-arrow-down"></i></span>
                        <div class="d-flex flex-column">
                          <span class="oper-titulo">Depósito</span>
                        </div>
                      </button>
                    </div>
                    <table class="wp-list-table widefat tab-ui striped tab-consdep" data-user="<?php echo $user; ?>">
                      <thead>
                        <tr>
                          <th class="manage_column" >Fecha</th>
                          <th class="manage_column" >Cantidad</th>
                          <th class="manage_column" >Status</th>
                        </tr>
                      </thead>

                      <tbody>

                      </tbody>
                    </table>
                  </div>
                  <div class="oper-mes oper-mes2 col-12 col-md-6 d-flex flex-column mt-4 mt-md-0">
                    <div class="caja-consret d-flex">
                      <button class='btn-addconsret d-flex' id='btn-addconsret' type='button' data-bs-toggle='modal' data-bs-target='#modal-addconsret' >
                        <span class="oper-consret"><i class="fa-solid fa-arrow-up"></i></span>
                        <div class="d-flex flex-column">
                          <span class="oper-titulo">Retiro</span>
                        </div>
                      </button>
                    </div>
                    <table class="wp-list-table widefat tab-ui striped tab-consret" data-user="<?php echo $user; ?>">
                      <thead>
                        <tr>
                          <th class="manage_column" >Fecha</th>
                          <th class="manage_column" >Cantidad</th>
                          <th class="manage_column" >Status</th>
                        </tr>
                      </thead>

                      <tbody>

                      </tbody>
                    </table>
                  </div>
                </div>

                <br>
                <br>

                <div class="box-transparent">
                  <h3>Registro de utilidades:</h3>
                  <br>
                  <table class="wp-list-table widefat tab-ui dt-responsive striped tab-consutiluser">
                    <thead>
                      <tr>
                        <th class="manage_column" >#</th>
                        <th class="manage_column" >Año - Mes</th>
                        <th class="manage_column" >Depósitos</th>
                        <th class="manage_column" >Cap Principal</th>
                        <th class="manage_column" >Util</th>
                        <th class="manage_column" >Util Acum</th>
                        <th class="manage_column" >Retiros</th>
                        <th class="manage_column" >Total</th>
                        <th class="manage_column" >Status</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php
                      if (count($detalleregistros) != 0) {
                        foreach ($detalleregistros as $key => $value) { ?>
                          <tr>
                          <td><?php echo $key+1; ?></td>
                          <td><?php echo $value["year"]." - ".$value["tmes"]; ?></td>
                          <td><span class="verde btn-ver-dep" data-userid="<?php echo $user ?>" data-mes="<?php echo $value["mes"] ?>" data-agno="<?php echo $value["year"] ?>">+ $<?php echo number_format($value["depmes"], 2, '.', ','); ?></span></td>
                          <td>$<?php echo number_format($value["capini"], 2, '.', ','); ?></td>
                          <td>$<?php echo number_format($value["utilmes"], 2, '.', ','); ?></td>
                          <td>$<?php echo number_format($value["utilacumulada"], 2, '.', ','); ?></td>
                          <td><span class="rojo btn-ver-ret" data-userid="<?php echo $user ?>" data-mes="<?php echo $value["mes"] ?>" data-agno="<?php echo $value["year"]?>">- $<?php echo number_format($value["retmes"], 2, '.', ','); ?></span></td>
                          <td>$<?php echo number_format($value["totalcierremes"], 2, '.', ','); ?></td>
                          <td>% <?php echo $value["statusmes"]; ?></td>
                          </tr>
                        <?php }
                      } ?>
                    </tbody>
                  </table>
                  <br>
                  <hr>
                  <p>Notas: <br>
                    <ol>
                      <li>Los datos de las cajas superiores hacen referencia a los datos almacenados en el ultimo mes finalizado inmediato anterior.</li>
                      <li>La caja superior de "Capital Principal", representa el capital principal en el ultimo mes finalizado inmediato anterior más depositos autorizados futuros .</li>
                      <li>La caja superior de "Total", representa el total al cierre del ultimo mes finalizado inmediato anterior más depositos autorizados futuros menos retiros autorizados futuros.</li>
                      <li>Los datos de la columna "% Status", representan  el porcentaje de utilidad para dicho mes. Tome en cuenta que dicho status puede ser modificado por TheIncProject bajo criterio reservado.</li>
                    </ol>
                  </p>
                </div>


                <div class="modal fade modal-ui" id="modal-addconsdep" tabindex="-1" aria-labelledby="modal-addconsdepLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-addconsdepLabel">Solicitar depósito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-addconsdep" class="form-addconsdep" action="#" method="post">
                          <input type="hidden" id="userdep" name="userdep" value="<?php echo $user ?>">
                          <div class="campo">
                            <label for="dcon_cantidad">*Cantidad: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="dcon_cantidad" class="form-control" type="text" name="dcon_cantidad" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo-especial">
                            <input id="agregarconsdep" type="submit" name="agregarconsdep" class="button button-primary" value="Solicitar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-addconsret" tabindex="-1" aria-labelledby="modal-addconsretLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-addconsretLabel">Solicitar retiro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-addconsret" class="form-addconsret" action="#" method="post">
                          <input type="hidden" id="userret" name="userret" value="<?php echo $user ?>">
                          <input type="hidden" id="totaldisp" name="totaldisp" value="10000">
                          <div class="campo">
                            <label for="rcon_cantidad">*Cantidad: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rcon_cantidad" class="form-control" type="text" name="rcon_cantidad" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo-especial">
                            <input id="agregarconsret" type="submit" name="agregarconsret" class="button button-primary" value="Solicitar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

              </div><!-- ui-dashboard -->
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_conshistodepositos(){
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    $modconservador = get_user_meta( $user, 'modconservador', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "conservador";
    $submenu = "conshistodepositos";

    // Si no tiene acceso lo regresamos al dashboard
    if ($modconservador != 1) {
      ?>
      <script type="text/javascript">
        let protocol = window.location.protocol;
        let url = "admin.php?page=crc_userdashboard";
        window.location.replace(protocol+url);
      </script>
      <?php
      die();
    }

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // echo "<pre>";
    // var_dump($modagresivo);
    // var_dump($modagrepart);
    // echo "</pre>";
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-user.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Historial de dep&oacute;sitos</h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_conservador" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>
              </div><!-- ui-dashboard -->

              <div class="box-transparent mb-3">
                <h4 class="mb-5">Historial de depósitos</h4>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-conshistodepositos">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Cantidad final</th>
                      <th class="manage_column" >Status</th>
                      <th class="manage_column" >Id Depósito a TD</th>
                      <th class="manage_column" >Id Depósito a Master</th>
                      <th class="manage_column" >Fecha solicitud</th>
                      <th class="manage_column" >Fecha autorización</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
              </div>

            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php

  }

  public function interfaz_conshistoretiros(){
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    $modconservador = get_user_meta( $user, 'modconservador', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "conservador";
    $submenu = "conshistoretiros";

    // Si no tiene acceso lo regresamos al dashboard
    if ($modconservador != 1) {
      ?>
      <script type="text/javascript">
        let protocol = window.location.protocol;
        let url = "admin.php?page=crc_userdashboard";
        window.location.replace(protocol+url);
      </script>
      <?php
      die();
    }

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // echo "<pre>";
    // var_dump($modagresivo);
    // var_dump($modagrepart);
    // echo "</pre>";
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-user.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Historial de retiros</h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_conservador" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>
              </div><!-- ui-dashboard -->

              <div class="box-transparent mb-3">
                <h4 class="mb-5">Historial de retiros</h4>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-conshistoretiros" data-user="<?php echo $user; ?>">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Cantidad final</th>
                      <th class="manage_column" >Status</th>
                      <th class="manage_column" >Id Depósito a TD</th>
                      <th class="manage_column" >Fecha solicitud</th>
                      <th class="manage_column" >Fecha autorización</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
              </div>

            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php

  }

  public function interfaz_consdepositosmes(){
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    $modconservador = get_user_meta( $user, 'modconservador', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "conservador";
    $submenu = "";

    // Si no tiene acceso lo regresamos al dashboard
    if ($modconservador != 1) {
      ?>
      <script type="text/javascript">
        let protocol = window.location.protocol;
        let url = "admin.php?page=crc_userdashboard";
        window.location.replace(protocol+url);
      </script>
      <?php
      die();
    }

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }

    $mesint = (int) $_GET['m'];
    //$mes = printf('%02d', $mesint);
    $agno = $_GET['p'];
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );
    $tmes = $mesesNombre[$mesint];

    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-user.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Depósitos - <?php echo $agno." ".$tmes; ?> </h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_conservador" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>

                <div class="box-transparent">
                  <br>
                  <h4 class="mb-5">Lista de dep&oacute;sitos válidos:</h4>
                  <table class="wp-list-table widefat tab-ui dt-responsive striped tab-consuserdepmes" data-user="<?php echo $user; ?>">
                    <thead>
                      <tr>
                        <th class="manage_column" >#</th>
                        <th class="manage_column" >Cantidad</th>
                        <th class="manage_column" >Cantidad final</th>
                        <th class="manage_column" >Notas</th>
                        <th class="manage_column" >Status Dep</th>
                        <th class="manage_column" >Id Depósito a TD</th>
                        <th class="manage_column" >Id Depósito a Master</th>
                        <th class="manage_column" >Fecha solicitud</th>
                        <th class="manage_column" >Fecha autorización</th>
                      </tr>
                    </thead>

                    <tbody>
                    </tbody>
                  </table>

                </div>
              </div><!-- ui-dashboard -->
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_consretirosmes(){
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    $modconservador = get_user_meta( $user, 'modconservador', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "conservador";
    $submenu = "";

    // Si no tiene acceso lo regresamos al dashboard
    if ($modconservador != 1) {
      ?>
      <script type="text/javascript">
        let protocol = window.location.protocol;
        let url = "admin.php?page=crc_userdashboard";
        window.location.replace(protocol+url);
      </script>
      <?php
      die();
    }

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }

    $mesint = (int) $_GET['m'];
    //$mes = printf('%02d', $mesint);
    $agno = $_GET['p'];
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );
    $tmes = $mesesNombre[$mesint];

    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-user.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Retiros - <?php echo $agno." ".$tmes; ?> </h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_conservador" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>

                <div class="box-transparent">
                  <br>
                  <h4 class="mb-5">Lista de retiros válidos:</h4>
                  <table class="wp-list-table widefat tab-ui dt-responsive striped tab-consuserretmes">
                    <thead>
                      <tr>
                        <th class="manage_column" >#</th>
                        <th class="manage_column" >Cantidad</th>
                        <th class="manage_column" >Cantidad final</th>
                        <th class="manage_column" >Notas</th>
                        <th class="manage_column" >Status Ret</th>
                        <th class="manage_column" >Id Retiro a TD</th>
                        <th class="manage_column" >Fecha solicitud</th>
                        <th class="manage_column" >Fecha autorización</th>
                      </tr>
                    </thead>

                    <tbody>
                    </tbody>
                  </table>

                </div>
              </div><!-- ui-dashboard -->
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function agregar_menuinversionistas(){
    add_menu_page(
      "Inversiones",
      "Inversiones",
      "inversionista",
      "crc_inversiones",
      [ $this, "interfaz_userdashboard" ],
      "dashicons-chart-area",
      15
    );

    $user = wp_get_current_user();
    if ( in_array( 'inversionista', (array) $user->roles ) ) {
      remove_menu_page('index.php');
    }

  }

  public function agregar_menuhistoretiros(){
    add_submenu_page( 'crc_inversiones',
    'Historial de Retiros',
    'Historial de Retiros',
    'inversionista',
    'crc_retiros',
    [ $this, 'interfaz_historetiros' ] );

  }

  public function agregar_menuhistodepositos(){
    add_submenu_page( 'crc_inversiones',
    'Historial de Depósitos',
    'Historial de Depósitos',
    'inversionista',
    'crc_depositos',
    [ $this, 'interfaz_histodepositos' ] );

  }

  public function agregar_menuverdepmes(){
    add_submenu_page( null,
    'User Depositos Mes',
    'User Depositos Mes',
    'inversionista',
    'crc_verdepmes',
    [ $this, 'interfaz_verdepmes' ] );

  }

  public function agregar_menuverretmes(){
    add_submenu_page( null,
    'User Retiros Mes',
    'User Retiros Mes',
    'inversionista',
    'crc_verretmes',
    [ $this, 'interfaz_verretmes' ] );

  }

  public function remove_menusinversionistas(){
    global $current_user;


  }

  public function agregar_agremenuinversionistas(){
    add_menu_page(
      "Agresivo",
      "Agresivo",
      "inversionista",
      "crc_agresivo",
      [ $this, "interfaz_agredashboard" ],
      "dashicons-chart-area",
      20
    );

    $user = wp_get_current_user();
    if ( in_array( 'inversionista', (array) $user->roles ) ) {
      remove_menu_page('index.php');
    }

  }

  public function agregar_consmenuinversionistas(){
    add_menu_page(
      "Conservador",
      "Conservador",
      "inversionista",
      "crc_conservador",
      [ $this, "interfaz_consdashboard" ],
      "dashicons-chart-area",
      25
    );

    $user = wp_get_current_user();
    if ( in_array( 'inversionista', (array) $user->roles ) ) {
      remove_menu_page('index.php');
    }

  }

  public function agregar_agremenuhistodepositos(){
    add_submenu_page( 'crc_agresivo',
    'Historial de Depósitos',
    'Historial de Depósitos',
    'inversionista',
    'crc_agre_depositos',
    [ $this, 'interfaz_agrehistodepositos' ] );

  }

  public function agregar_agremenuhistoretiros(){
    add_submenu_page( 'crc_agresivo',
    'Historial de Retiros',
    'Historial de Retiros',
    'inversionista',
    'crc_agre_retiros',
    [ $this, 'interfaz_agrehistoretiros' ] );

  }

  public function agregar_consmenuhistodepositos(){
    add_submenu_page( 'crc_conservador',
    'Historial de Depósitos',
    'Historial de Depósitos',
    'inversionista',
    'crc_cons_depositos',
    [ $this, 'interfaz_conshistodepositos' ] );

  }

  public function agregar_consmenuhistoretiros(){
    add_submenu_page( 'crc_conservador',
    'Historial de Retiros',
    'Historial de Retiros',
    'inversionista',
    'crc_cons_retiros',
    [ $this, 'interfaz_conshistoretiros' ] );

  }

  public function agregar_consverdepmes(){
    add_submenu_page( null,
    'User Depósitos Mes',
    'User Depósitos Mes',
    'inversionista',
    'crc_consverdepmes',
    [ $this, 'interfaz_consdepositosmes' ] );
  }

  public function agregar_consverretmes(){
    add_submenu_page( null,
    'User Retiros Mes',
    'User Retiros Mes',
    'inversionista',
    'crc_consverretmes',
    [ $this, 'interfaz_consretirosmes' ] );
  }


}
