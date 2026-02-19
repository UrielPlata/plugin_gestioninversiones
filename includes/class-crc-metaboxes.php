<?php

class CRC_Metaboxes{

  /** Crear shortcode, utiliza [mid_registrar_mascota_shortcode] */
  public function campos_inversionista(){

    $user = wp_get_current_user();
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {

        if ( in_array( 'inversionista', $user->roles ) ) {
          $user = get_current_user_id();
          $invitecode = get_user_meta( $user, 'invitecode', true);
          $status = get_user_meta( $user, 'status', true);
          $wallet = get_user_meta( $user, 'wallet', true);
          $walletcode = get_user_meta( $user, 'walletcode', true);
          $wallet2 = get_user_meta( $user, 'wallet2', true);
          $walletcode2 = get_user_meta( $user, 'walletcode2', true);
          $referido = get_user_meta( $user, 'referido', true);
          $pais = get_user_meta( $user, 'pais', true);
          $estado = get_user_meta( $user, 'estado', true);
          $municipio = get_user_meta( $user, 'municipio', true);
          $calle = get_user_meta( $user, 'calle', true);
          $zipcode = get_user_meta( $user, 'zipcode', true);
          $activo = get_user_meta( $user, 'activo', true);
          $foto = get_user_meta( $user, 'fotografia', true );
          $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
          '8' => 'Agosto',
          '9' => 'Septiembre',
          '10' => 'Octubre',
          '11' => 'Noviembre',
          '12' => 'Diciembre' );

          if ($foto) {
            if (substr($foto['url'], -4) == ".pdf" ) {
              $porciones = explode("/", $foto['url']);
              $fotoimg = "<a href='".$foto['url']."' target='_blank'><img src='".plugin_dir_url( __DIR__ ) . 'assets/img/pdf.png'."' style='max-width:20px' /> ".$porciones[sizeof($porciones) - 1]."</a>";
            }else{
              $fotoimg = "<img src='".$foto['url']."' style='max-width:250px' />";
            }
          } else {
              $fotoimg = "<img src='".plugin_dir_url( __DIR__ ) . 'assets/img/image-empty.png'."' style='max-width:250px' />";
          }

          if(!isset($pais) || $pais == ''){
            $paist = '';

          }else {
            $paist = $pais;
          }

          if($pais == 'USA'){
            $municipion = "Ciudad";
          }else{
            $municipion = "Municipio";
          }

          switch ($estado) {
              case "IL":
                  $estadot= "<option value='IL' selected>Illinois</option>";
                  break;
              case "AGS":
                  $estadot = "<option value='AGS' selected>Aguascalientes</option>";
                  break;
              case "BC":
                  $estadot = "<option value='BC' selected>Baja California</option>";
                  break;
              case "BCS":
                  $estadot = "<option value='BCS' selected>Baja California Sur</option>";
                  break;
              case "CHI":
                  $estadot = "<option value='CHI' selected>Chihuahua</option>";
                  break;
              case "CHS":
                  $estadot = "<option value='CHS' selected>Chiapas</option>";
                  break;
              case "CMP":
                  $estadot = "<option value='CMP' selected>Campeche</option>";
                  break;
              case "CMX":
                  $estadot = "<option value='CMX' selected>Ciudad de México</option>";
                  break;
              case "COA":
                  $estadot = "<option value='COA' selected>Coahuila</option>";
                  break;
              case "COL":
                  $estadot = "<option value='COL' selected>Colima</option>";
                  break;
              case "DGO":
                  $estadot = "<option value='DGO' selected>Durango</option>";
                  break;
              case "GRO":
                  $estadot = "<option value='GRO' selected>Guerrero</option>";
                  break;
              case "GTO":
                  $estadot = "<option value='GTO' selected>Guanajuato</option>";
                  break;
              case "HGO":
                  $estadot = "<option value='HGO' selected>Hidalgo</option>";
                  break;
              case "JAL":
                  $estadot = "<option value='JAL' selected>Jalisco</option>";
                  break;
              case "MCH":
                  $estadot = "<option value='MCH' selected>Michoacán</option>";
                  break;
              case "MEX":
                  $estadot = "<option value='MEX' selected>Estado de México</option>";
                  break;
              case "MOR":
                  $estadot = "<option value='MOR' selected>Morelos</option>";
                  break;
              case "NAY":
                  $estadot = "<option value='NAY' selected>Nayarit</option>";
                  break;
              case "NL":
                  $estadot = "<option value='NL' selected>Nuevo León</option>";
                  break;
              case "OAX":
                  $estadot = "<option value='OAX' selected>Oaxaca</option>";
                  break;
              case "PUE":
                  $estadot = "<option value='PUE' selected>Puebla</option>";
                  break;
              case "QR":
                  $estadot = "<option value='QR' selected>Quintana Roo</option>";
                  break;
              case "QRO":
                  $estadot = "<option value='QRO' selected>Querétaro</option>";
                  break;
              case "SIN":
                  $estadot = "<option value='SIN' selected>Sinaloa</option>";
                  break;
              case "SLP":
                  $estadot = "<option value='SLP' selected>San Luis Potosí</option>";
                  break;
              case "SON":
                  $estadot = "<option value='SON' selected>Sonora</option>";
                  break;
              case "TAB":
                  $estadot = "<option value='TAB' selected>Tabasco</option>";
                  break;
              case "TLX":
                  $estadot = "<option value='TLX' selected>Tlaxcala</option>";
                  break;
              case "TMS":
                  $estadot = "<option value='TMS' selected>Tamaulipas</option>";
                  break;
              case "VER":
                  $estadot = "<option value='VER' selected>Veracruz</option>";
                  break;
              case "YUC":
                  $estadot = "<option value='YUC' selected>Yucatán</option>";
                  break;
              case "AL":
                  $estadot = "<option value='AL' selected>Alabama</option>";
                  break;
              case "AK":
                  $estadot = "<option value='AK' selected>Alaska</option>";
                  break;
              case "AZ":
                  $estadot = "<option value='AZ' selected>Arizona</option>";
                  break;
              case "AS":
                  $estadot = "<option value='AS' selected>American Samoa</option>";
                  break;
              case "AR":
                  $estadot = "<option value='AR' selected>Arkansas</option>";
                  break;
              case "CA":
                  $estadot = "<option value='CA' selected>California</option>";
                  break;
              case "CT":
                  $estadot = "<option value='CT' selected>Connecticut</option>";
                  break;
              case "CO":
                  $estadot = "<option value='CO' selected>Colorado</option>";
                  break;
              case "DE":
                  $estadot = "<option value='DE' selected>Delaware</option>";
                  break;
              case "DC":
                  $estadot = "<option value='DC' selected>District of Columbia</option>";
                  break;
              case "FM":
                  $estadot = "<option value='FM' selected>Federated States Of Micronesia</option>";
                  break;
              case "FL":
                  $estadot = "<option value='FL' selected>Florida</option>";
                  break;
              case "GA":
                  $estadot = "<option value='GA' selected>Georgia</option>";
                  break;
              case "GU":
                  $estadot = "<option value='GU' selected>Guam</option>";
                  break;
              case "HI":
                  $estadot = "<option value='HI' selected>Hawaii</option>";
                  break;
              case "ID":
                  $estadot = "<option value='ID' selected>Idaho</option>";
                  break;
              case "IN":
                  $estadot = "<option value='IN' selected>Indiana</option>";
                  break;
              case "IA":
                  $estadot = "<option value='IA' selected>Iowa</option>";
                  break;
              case "KS":
                  $estadot = "<option value='KS' selected>Kansas</option>";
                  break;
              case "KY":
                  $estadot = "<option value='KY' selected>Kentucky</option>";
                  break;
              case "LA":
                  $estadot = "<option value='LA' selected>Louisiana</option>";
                  break;
              case "ME":
                  $estadot = "<option value='ME' selected>Maine</option>";
                  break;
              case "MH":
                  $estadot = "<option value='MH' selected>Marshall Islands</option>";
                  break;
              case "MD":
                  $estadot = "<option value='MD' selected>Maryland</option>";
                  break;
              case "MA":
                  $estadot = "<option value='MA' selected>Massachusetts</option>";
                  break;
              case "MI":
                  $estadot = "<option value='MI' selected>Michigan</option>";
                  break;
              case "MN":
                  $estadot = "<option value='MN' selected>Minnesota</option>";
                  break;
              case "MS":
                  $estadot = "<option value='MS' selected>Mississippi</option>";
                  break;
              case "MO":
                  $estadot = "<option value='MO' selected>Missouri</option>";
                  break;
              case "MT":
                  $estadot = "<option value='MT' selected>Montana</option>";
                  break;
              case "NE":
                  $estadot = "<option value='NE' selected>Nebraska</option>";
                  break;
              case "NV":
                  $estadot = "<option value='NV' selected>Nevada</option>";
                  break;
              case "NH":
                  $estadot = "<option value='NH' selected>New Hampshire</option>";
                  break;
              case "NJ":
                  $estadot = "<option value='NJ' selected>New Jersey</option>";
                  break;
              case "NM":
                  $estadot = "<option value='NM' selected>New Mexico</option>";
                  break;
              case "NY":
                  $estadot = "<option value='NY' selected>New York</option>";
                  break;
              case "NC":
                  $estadot = "<option value='NC' selected>North Carolina</option>";
                  break;
              case "ND":
                  $estadot = "<option value='ND' selected>North Dakota</option>";
                  break;
              case "MP":
                  $estadot = "<option value='MP' selected>Northern Mariana Islands</option>";
                  break;
              case "OH":
                  $estadot = "<option value='OH' selected>Ohio</option>";
                  break;
              case "OK":
                  $estadot = "<option value='OK' selected>Oklahoma</option>";
                  break;
              case "OR":
                  $estadot = "<option value='OR' selected>Oregon</option>";
                  break;
              case "PW":
                  $estadot = "<option value='PW' selected>Palau</option>";
                  break;
              case "PA":
                  $estadot = "<option value='PA' selected>Pennsylvania</option>";
                  break;
              case "PR":
                  $estadot = "<option value='PR' selected>Puerto Rico</option>";
                  break;
              case "RI":
                  $estadot = "<option value='RI' selected>Rhode Island</option>";
                  break;
              case "SC":
                  $estadot = "<option value='SC' selected>South Carolina</option>";
                  break;
              case "SD":
                  $estadot = "<option value='SD' selected>South Dakota</option>";
                  break;
              case "TN":
                  $estadot = "<option value='TN' selected>Tennessee</option>";
                  break;
              case "TX":
                  $estadot = "<option value='TX' selected>Texas</option>";
                  break;
              case "UT":
                  $estadot = "<option value='UT' selected>Utah</option>";
                  break;
              case "VI":
                  $estadot = "<option value='VI' selected>Virgin Islands</option>";
                  break;
              case "VA":
                  $estadot = "<option value='VA' selected>Virginia</option>";
                  break;
              case "WA":
                  $estadot = "<option value='WA' selected>Washington</option>";
                  break;
              case "VT":
                  $estadot = "<option value='VT' selected>Vermont</option>";
                  break;
              case "WV":
                  $estadot = "<option value='WV' selected>West Virginia</option>";
                  break;
              case "WI":
                  $estadot = "<option value='WI' selected>Wisconsin</option>";
                  break;
              case "WY":
                  $estadot = "<option value='WY' selected>Wyoming</option>";
                  break;
              default:
                $estadot = "<option value='' selected>Seleccione un estado</option>";
                break;
          }

          global $wpdb;
          $userid = $user;
          $tablamesinv = $wpdb->prefix . 'mesesinv';
          $registros = $wpdb->get_results(" SELECT * FROM $tablamesinv WHERE usuario = $userid AND status = 1 ORDER BY id ASC", ARRAY_A);
          $tabladep = $wpdb->prefix . 'depositos';
          $depvalidos = $wpdb->get_results("SELECT month(fecha_termino) AS mes, year(fecha_termino) AS agno, ROUND(SUM(cantidad_real),2) AS total FROM $tabladep WHERE usuario = $userid AND status = 2 GROUP BY mes ORDER BY agno, mes", ARRAY_A);

          if(count($depvalidos) == 0){
            $mesinversor = 0;
            $mesuno = 0;
            $agnouno = 0;
          }else{
            $mesuno = $depvalidos[0]["mes"];
            $agnouno = (int) $depvalidos[0]["agno"];
            $fechaini = date($depvalidos[0]["agno"]."-".$depvalidos[0]["mes"]."-01");
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
            $mesinversor = $yearsInMonths + $months + 1;
          }
          add_thickbox();
          if($status){
            $tablameses = "<table class='tab-mesesinv tab-dark' >
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Mes</th>
                                <th>Año</th>
                                <th>Interes</th>
                                <th>Notas</th>
                              </tr>
                            </thead>";

            //comprobamos existan meses sino se imprime la tabla vacia
            if(count($registros) == 0){

            }else{
              $tablameses .= "<tbody>";
              foreach ($registros as $key => $value) {

                if ($value['mes'] <= $mesinversor ) {
                  $tablameses .= "<tr class='mes-pasado'>";
                } else {
                  $tablameses .= "<tr >";
                }

                $tablameses .= "<td>".$value['mes']."</td>";

                // Impresion año y mes segun si hay deposito
                if ($mesuno > 0) {
                  $tmes = $mesesNombre[$mesuno];
                  $tablameses .= "<td>".$tmes."</td>";
                  $tablameses .= "<td>".$agnouno."</td>";
                  if ($mesuno == 12) {
                    $agnouno++;
                    $mesuno = 1;
                  }else {
                    $mesuno++;
                  }

                } else {
                  $tablameses .= "<td> -.- </td>";
                  $tablameses .= "<td> -.- </td>";
                }
                $tablameses .= "<td class='td-".$value['id']."'>".$value['interes']."%</td>
                                  <td><button aria-label='".$value['notas']."' data-microtip-position='top' data-microtip-size='medium' class='microtexto td-".$value['id']."-notas' role='tooltip'><span class='material-icons'>speaker_notes</span></button></td>
                                </tr>";
              }
              $tablameses .= "</tbody>";
            }

            $tablameses .=  "</table>";

          }else{
            $tablameses ="<table class='tab-mesesinv' >
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Mes</th>
                              <th>Año</th>
                              <th>Interes</th>
                              <th>Notas</th>
                            </tr>
                          </thead>
                        </table>";
          }

          $output = "
            <h3> Datos del inversionista </h3>
            <table class='form-table'>
              <tr class='user-status-wrap'>
                <th scope='row'><label for='status'>Status</label></th>
                <td>
                  <input class='regular-text' type='text' name='status' id='status' size='40' value='$status' readonly disabled='disabled'>
                  <input type='hidden' name='mesactual' id='mesactual' value='$mesinversor' >
                  <p class='description'>Porcentaje asignado por el administrador</p>
                  <br>
                  <p class='description'>Mes actual del inversionista: <span class='mesactual'>".$mesinversor."</span> </p>
                  <br>
                  <br>
                  ".$tablameses."
                </td>
              </tr>
              <tr class='user-referido-wrap'>
                <th scope='row'><label for='referido'>Código de quien te invitó</label></th>
                <td>
                  <input class='regular-text' type='text' name='referido' id='referido' size='40' value='$referido' readonly disabled='disabled'>
                  <p class='description'>Si no ingresaste a alguien se muestra el código por default.</p>
                </td>
              </tr>
              <tr class='user-invitecode-wrap'>
                <th scope='row'><label for='invitecode'>Tu código de invitación</label></th>
                <td>
                  <input class='regular-text' type='text' name='invitecode' id='invitecode' size='40' value='$invitecode' readonly disabled='disabled'>
                  <p class='description'>Tu código de invitación</p>
                </td>
              </tr>
              <tr class='form-required user-wallet-wrap'>
                <th scope='row'><label for='wallet'>Tipo de Wallet</label></th>
                <td>
                  <select name='wallet' id='wallet' >". (($wallet == '') ? '<option value="" selected>Seleccione una opción</option>' : '<option value="" >Seleccione una opción</option>') .
                  (($wallet == 'bitcoin') ? '<option value="bitcoin" selected>Bitcoin</option>' : '<option value="bitcoin">Bitcoin</option>') .
                  (($wallet == 'usdt') ? '<option value="usdt" selected>USDT (Tether)</option>' : '<option value="usdt">USDT (Tether)</option>') .
                  (($wallet == 'ethereum') ? '<option value="ethereum" selected>Ethereum</option>' : '<option value="ethereum">Ethereum</option>') .
                  "</select>
                  <p class='description'></p>
                </td>
              </tr>
              <tr class='form-required user-walletcode-wrap'>
                <th scope='row'><label for='walletcode'>Wallet Address</label></th>
                <td>
                  <input class='regular-text' type='text' name='walletcode' id='walletcode' size='40' value='$walletcode' >
                  <p class='description'>Tu wallet Addres donde recibiras los retiros.</p>
                </td>
              </tr>
              <tr class='form-required user-wallet2-wrap'>
                <th scope='row'><label for='wallet2'>Tipo de Wallet Adicional</label></th>
                <td>
                <select name='wallet2' id='wallet2' >". (($wallet2 == '') ? '<option value="" selected>Seleccione una opción</option>' : '<option value="" >Seleccione una opción</option>') .
                (($wallet2 == 'bitcoin') ? '<option value="bitcoin" selected>Bitcoin</option>' : '<option value="bitcoin">Bitcoin</option>') .
                (($wallet2 == 'usdt') ? '<option value="usdt" selected>USDT (Tether)</option>' : '<option value="usdt">USDT (Tether)</option>') .
                (($wallet2 == 'ethereum') ? '<option value="ethereum" selected>Ethereum</option>' : '<option value="ethereum">Ethereum</option>') .
                "</select>
                <p class='description'></p>
                </td>
              </tr>
              <tr class='form-required user-walletcode2-wrap'>
                <th scope='row'><label for='walletcode2'>Wallet Address Adicional</label></th>
                <td>
                  <input class='regular-text' type='text' name='walletcode2' id='walletcode2' size='40' value='$walletcode2' >
                  <p class='description'>Tu wallet Addres adicional.</p>
                </td>
              </tr>
              <tr class='form-required user-pais-wrap'>
                <th scope='row'><label for='pais'>Pais</label></th>
                <td>
                  <select id='pais' name='pais' readonly disabled='disabled'>". (($paist == '') ? '<option value="" selected>Seleccione un pais</option>' : '<option value="" >Seleccione una fecha</option>') .
                  (($paist == 'MEX') ? '<option value="MEX" selected>México</option>' : '<option value="MEX">México</option>') .
                  (($paist == 'USA') ? '<option value="USA" selected>Estados Unidos</option>' : '<option value="USA">Estados Unidos</option>') ."
                  </select>
                </td>
              </tr>
              <tr class='form-required user-estado-wrap'>
                <th scope='row'><label for='estado'>Estado</label></th>
                <td>
                  <select id='estado' name='estado' readonly disabled='disabled'>". (($estado == '') ? '<option value="" selected>Seleccione un estado/option>' : '<option value="" >Seleccione una fecha</option>') .
                  $estadot."
                  </select>
                </td>
              </tr>
              <tr class='form-required user-municipio-wrap'>
                <th scope='row'><label for='municipio'>$municipion</label></th>
                <td>
                  <input class='regular-text' type='text' name='municipio' id='municipio' size='40' value='$municipio' >
                </td>
              </tr>
              <tr class='form-required user-calle-wrap'>
                <th scope='row'><label for='calle'>Calle</label></th>
                <td>
                  <input class='regular-text' type='text' name='calle' id='calle' size='40' value='$calle' >
                </td>
              </tr>
              <tr class='form-required user-zipcode-wrap'>
                <th scope='row'><label for='zipcode'>Código postal</label></th>
                <td>
                  <input class='regular-text' type='text' name='zipcode' id='zipcode' size='40' value='$zipcode' >
                </td>
              </tr>
              <tr class='form-required user-fotografia-wrap'>
                 <th scope='row'><label for='fotografia'>Fotografía de perfil</label></th>
                 <td>
                 ".$fotoimg."
                 <input type='file' name='fotografia' value='' />
                 </td>
              </tr>
            </table>
          ";

          echo $output;
        }
      }

  }

  /** Crear shortcode, utiliza [mid_registrar_mascota_shortcode] */
  public function campos_admininversionista(){

    $user = (int) $_GET["user_id"];
    $invitecode = get_user_meta( $user, 'invitecode', true);
    $status = get_user_meta( $user, 'status', true);
    $wallet = get_user_meta( $user, 'wallet', true);
    $walletcode = get_user_meta( $user, 'walletcode', true);
    $wallet2 = get_user_meta( $user, 'wallet2', true);
    $walletcode2 = get_user_meta( $user, 'walletcode2', true);
    $referido = get_user_meta( $user, 'referido', true);
    $pais = get_user_meta( $user, 'pais', true);
    $estado = get_user_meta( $user, 'estado', true);
    $municipio = get_user_meta( $user, 'municipio', true);
    $calle = get_user_meta( $user, 'calle', true);
    $zipcode = get_user_meta( $user, 'zipcode', true);
    $activo = get_user_meta( $user, 'activo', true);
    $modagresivo = get_user_meta( $user, 'modagresivo', true);
    $modagresivopart = get_user_meta( $user, 'modagresivopart', true);
    $modinteres = get_user_meta( $user, 'modinteres', true);
    $modconservador = get_user_meta( $user, 'modconservador', true);
    $foto = get_user_meta( $user, 'fotografia', true );
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );

    if ($foto) {
      if (substr($foto['url'], -4) == ".pdf" ) {
        $porciones = explode("/", $foto['url']);
        $fotoimg = "<a href='".$foto['url']."' target='_blank'><img src='".plugin_dir_url( __DIR__ ) . 'assets/img/pdf.png'."' style='max-width:20px' /> ".$porciones[sizeof($porciones) - 1]."</a>";
      }else{
        $fotoimg = "<img src='".$foto['url']."' style='max-width:250px' />";
      }
    } else {
        $fotoimg = "<img src='".plugin_dir_url( __DIR__ ) . 'assets/img/image-empty.png'."' style='max-width:250px' />";
    }

    if(!isset($pais) || $pais == ''){
      $paist = '';

    }else {
      $paist = $pais;
    }

    if($pais == 'USA'){
      $municipion = "Ciudad";
    }else{
      $municipion = "Municipio";
    }

    switch ($estado) {
        case "IL":
            $estadot= "<option value='IL' selected>Illinois</option>";
            break;
        case "AGS":
            $estadot = "<option value='AGS' selected>Aguascalientes</option>";
            break;
        case "BC":
            $estadot = "<option value='BC' selected>Baja California</option>";
            break;
        case "BCS":
            $estadot = "<option value='BCS' selected>Baja California Sur</option>";
            break;
        case "CHI":
            $estadot = "<option value='CHI' selected>Chihuahua</option>";
            break;
        case "CHS":
            $estadot = "<option value='CHS' selected>Chiapas</option>";
            break;
        case "CMP":
            $estadot = "<option value='CMP' selected>Campeche</option>";
            break;
        case "CMX":
            $estadot = "<option value='CMX' selected>Ciudad de México</option>";
            break;
        case "COA":
            $estadot = "<option value='COA' selected>Coahuila</option>";
            break;
        case "COL":
            $estadot = "<option value='COL' selected>Colima</option>";
            break;
        case "DGO":
            $estadot = "<option value='DGO' selected>Durango</option>";
            break;
        case "GRO":
            $estadot = "<option value='GRO' selected>Guerrero</option>";
            break;
        case "GTO":
            $estadot = "<option value='GTO' selected>Guanajuato</option>";
            break;
        case "HGO":
            $estadot = "<option value='HGO' selected>Hidalgo</option>";
            break;
        case "JAL":
            $estadot = "<option value='JAL' selected>Jalisco</option>";
            break;
        case "MCH":
            $estadot = "<option value='MCH' selected>Michoacán</option>";
            break;
        case "MEX":
            $estadot = "<option value='MEX' selected>Estado de México</option>";
            break;
        case "MOR":
            $estadot = "<option value='MOR' selected>Morelos</option>";
            break;
        case "NAY":
            $estadot = "<option value='NAY' selected>Nayarit</option>";
            break;
        case "NL":
            $estadot = "<option value='NL' selected>Nuevo León</option>";
            break;
        case "OAX":
            $estadot = "<option value='OAX' selected>Oaxaca</option>";
            break;
        case "PUE":
            $estadot = "<option value='PUE' selected>Puebla</option>";
            break;
        case "QR":
            $estadot = "<option value='QR' selected>Quintana Roo</option>";
            break;
        case "QRO":
            $estadot = "<option value='QRO' selected>Querétaro</option>";
            break;
        case "SIN":
            $estadot = "<option value='SIN' selected>Sinaloa</option>";
            break;
        case "SLP":
            $estadot = "<option value='SLP' selected>San Luis Potosí</option>";
            break;
        case "SON":
            $estadot = "<option value='SON' selected>Sonora</option>";
            break;
        case "TAB":
            $estadot = "<option value='TAB' selected>Tabasco</option>";
            break;
        case "TLX":
            $estadot = "<option value='TLX' selected>Tlaxcala</option>";
            break;
        case "TMS":
            $estadot = "<option value='TMS' selected>Tamaulipas</option>";
            break;
        case "VER":
            $estadot = "<option value='VER' selected>Veracruz</option>";
            break;
        case "YUC":
            $estadot = "<option value='YUC' selected>Yucatán</option>";
            break;
        case "AL":
            $estadot = "<option value='AL' selected>Alabama</option>";
            break;
        case "AK":
            $estadot = "<option value='AK' selected>Alaska</option>";
            break;
        case "AZ":
            $estadot = "<option value='AZ' selected>Arizona</option>";
            break;
        case "AS":
            $estadot = "<option value='AS' selected>American Samoa</option>";
            break;
        case "AR":
            $estadot = "<option value='AR' selected>Arkansas</option>";
            break;
        case "CA":
            $estadot = "<option value='CA' selected>California</option>";
            break;
        case "CT":
            $estadot = "<option value='CT' selected>Connecticut</option>";
            break;
        case "CO":
            $estadot = "<option value='CO' selected>Colorado</option>";
            break;
        case "DE":
            $estadot = "<option value='DE' selected>Delaware</option>";
            break;
        case "DC":
            $estadot = "<option value='DC' selected>District of Columbia</option>";
            break;
        case "FM":
            $estadot = "<option value='FM' selected>Federated States Of Micronesia</option>";
            break;
        case "FL":
            $estadot = "<option value='FL' selected>Florida</option>";
            break;
        case "GA":
            $estadot = "<option value='GA' selected>Georgia</option>";
            break;
        case "GU":
            $estadot = "<option value='GU' selected>Guam</option>";
            break;
        case "HI":
            $estadot = "<option value='HI' selected>Hawaii</option>";
            break;
        case "ID":
            $estadot = "<option value='ID' selected>Idaho</option>";
            break;
        case "IN":
            $estadot = "<option value='IN' selected>Indiana</option>";
            break;
        case "IA":
            $estadot = "<option value='IA' selected>Iowa</option>";
            break;
        case "KS":
            $estadot = "<option value='KS' selected>Kansas</option>";
            break;
        case "KY":
            $estadot = "<option value='KY' selected>Kentucky</option>";
            break;
        case "LA":
            $estadot = "<option value='LA' selected>Louisiana</option>";
            break;
        case "ME":
            $estadot = "<option value='ME' selected>Maine</option>";
            break;
        case "MH":
            $estadot = "<option value='MH' selected>Marshall Islands</option>";
            break;
        case "MD":
            $estadot = "<option value='MD' selected>Maryland</option>";
            break;
        case "MA":
            $estadot = "<option value='MA' selected>Massachusetts</option>";
            break;
        case "MI":
            $estadot = "<option value='MI' selected>Michigan</option>";
            break;
        case "MN":
            $estadot = "<option value='MN' selected>Minnesota</option>";
            break;
        case "MS":
            $estadot = "<option value='MS' selected>Mississippi</option>";
            break;
        case "MO":
            $estadot = "<option value='MO' selected>Missouri</option>";
            break;
        case "MT":
            $estadot = "<option value='MT' selected>Montana</option>";
            break;
        case "NE":
            $estadot = "<option value='NE' selected>Nebraska</option>";
            break;
        case "NV":
            $estadot = "<option value='NV' selected>Nevada</option>";
            break;
        case "NH":
            $estadot = "<option value='NH' selected>New Hampshire</option>";
            break;
        case "NJ":
            $estadot = "<option value='NJ' selected>New Jersey</option>";
            break;
        case "NM":
            $estadot = "<option value='NM' selected>New Mexico</option>";
            break;
        case "NY":
            $estadot = "<option value='NY' selected>New York</option>";
            break;
        case "NC":
            $estadot = "<option value='NC' selected>North Carolina</option>";
            break;
        case "ND":
            $estadot = "<option value='ND' selected>North Dakota</option>";
            break;
        case "MP":
            $estadot = "<option value='MP' selected>Northern Mariana Islands</option>";
            break;
        case "OH":
            $estadot = "<option value='OH' selected>Ohio</option>";
            break;
        case "OK":
            $estadot = "<option value='OK' selected>Oklahoma</option>";
            break;
        case "OR":
            $estadot = "<option value='OR' selected>Oregon</option>";
            break;
        case "PW":
            $estadot = "<option value='PW' selected>Palau</option>";
            break;
        case "PA":
            $estadot = "<option value='PA' selected>Pennsylvania</option>";
            break;
        case "PR":
            $estadot = "<option value='PR' selected>Puerto Rico</option>";
            break;
        case "RI":
            $estadot = "<option value='RI' selected>Rhode Island</option>";
            break;
        case "SC":
            $estadot = "<option value='SC' selected>South Carolina</option>";
            break;
        case "SD":
            $estadot = "<option value='SD' selected>South Dakota</option>";
            break;
        case "TN":
            $estadot = "<option value='TN' selected>Tennessee</option>";
            break;
        case "TX":
            $estadot = "<option value='TX' selected>Texas</option>";
            break;
        case "UT":
            $estadot = "<option value='UT' selected>Utah</option>";
            break;
        case "VI":
            $estadot = "<option value='VI' selected>Virgin Islands</option>";
            break;
        case "VA":
            $estadot = "<option value='VA' selected>Virginia</option>";
            break;
        case "WA":
            $estadot = "<option value='WA' selected>Washington</option>";
            break;
        case "VT":
            $estadot = "<option value='VT' selected>Vermont</option>";
            break;
        case "WV":
            $estadot = "<option value='WV' selected>West Virginia</option>";
            break;
        case "WI":
            $estadot = "<option value='WI' selected>Wisconsin</option>";
            break;
        case "WY":
            $estadot = "<option value='WY' selected>Wyoming</option>";
            break;
        default:
          $estadot = "<option value='' selected>Seleccione un estado</option>";
          break;
    }

    global $wpdb;
    $userid = $user;
    $tablamesinv = $wpdb->prefix . 'mesesinv';
    $registros = $wpdb->get_results(" SELECT * FROM $tablamesinv WHERE usuario = $userid AND status = 1 ORDER BY id ASC", ARRAY_A);
    $tablanuevosstatus = $wpdb->prefix . 'nuevosstatus_con';
    $nuevosstatus = $wpdb->get_results(" SELECT * FROM $tablanuevosstatus WHERE nscon_usuario = $userid ORDER BY nscon_year, nscon_mes ASC", ARRAY_A);
    $tabladep = $wpdb->prefix . 'depositos';
    $depvalidos = $wpdb->get_results("SELECT month(fecha_termino) AS mes, year(fecha_termino) AS agno, ROUND(SUM(cantidad_real),2) AS total FROM $tabladep WHERE usuario = $userid AND status = 2 GROUP BY mes ORDER BY agno,mes ", ARRAY_A);

    $mesact = (int) date("m");
    $agnoact = (int) date("Y");

    if(count($depvalidos) == 0){
      $mesinversor = 0;
      $mesuno = 0;
      $agnouno = 0;
    }else{
      $mesuno = $depvalidos[0]["mes"];
      $agnouno = (int) $depvalidos[0]["agno"];
      $fechaini = date($depvalidos[0]["agno"]."-".$depvalidos[0]["mes"]."-01");
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
      $mesinversor = $yearsInMonths + $months + 1;


    }
    add_thickbox();

    // Empezamos a meter y comprobar el acceso a modulos

    if($modagresivo == 1){
      $toggleagresivo = "<div class=''>
      <input type='checkbox' id='modagresivo' class='mybox' name='modagresivo' checked>
      </div>";
    }else {
      $toggleagresivo = "<div class=''>
      <input type='checkbox' id='modagresivo' name='modagresivo' class='mybox' >
      </div>";
    }

    if($modagresivopart == 1){
      $toggleagresivopart = "<div class=''>
      <input type='checkbox' id='modagresivopart' class='mybox' name='modagresivopart' checked>
      </div>";
    }else {
      $toggleagresivopart = "<div class=''>
      <input type='checkbox' id='modagresivopart' name='modagresivopart' class='mybox' >
      </div>";
    }

    if($modconservador == 1){
      $toggleconservador = "<div class=''>
      <input type='checkbox' id='modconservador' class='mybox' name='modconservador' checked>
      </div>";
    }else {
      $toggleconservador = "<div class=''>
      <input type='checkbox' id='modconservador' name='modconservador' class='mybox' >
      </div>";
    }

    if($status){
      $tablameses = "<table class='tab-mesesinv  widefat tab-dark striped' >
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Mes</th>
                          <th>Año</th>
                          <th>Interes</th>
                          <th>Notas</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>";

      //comprobamos existan meses sino se imprime la tabla vacia
      if(count($registros) == 0){

      }else{
        $tablameses .= "<tbody class=''>";
        foreach ($registros as $key => $value) {


          if ($value['mes'] <= $mesinversor ) {
            $tablameses .= "<tr class='mes-pasado'>";
          } else {
            $tablameses .= "<tr >";
          }

          $tablameses .= "<td>".$value['mes']."</td>";

          // Impresion año y mes segun si hay deposito
          if ($mesuno > 0) {
            $tmes = $mesesNombre[$mesuno];
            $tablameses .= "<td>".$tmes."</td>";
            $tablameses .= "<td>".$agnouno."</td>";
            if ($mesuno == 12) {
              $agnouno++;
              $mesuno = 1;
            }else {
              $mesuno++;
            }

          } else {
            $tablameses .= "<td> -.- </td>";
            $tablameses .= "<td> -.- </td>";
          }

          $tablameses .= "<td class='td-".$value['id']."'>".$value['interes']."%</td>
                            <td><button aria-label='".$value['notas']."' data-microtip-position='top' data-microtip-size='medium' class='microtexto td-".$value['id']."-notas' role='tooltip'><span class='material-icons'>speaker_notes</span></button></td>
                            <td><input alt='#TB_inline?width=400&inlineId=modal-editmes' title='Editar status del mes' data-interes='".$value['interes']."' data-mes='".$value['id']."'  class='thickbox button button-primary button-large btn-editmes' type='button' value='Editar' /></td>
                          </tr>";
        }
        $tablameses .= "</tbody>";
      }

      $tablameses .=  "</table>";

    }else{
      $tablameses ="<table class='tab-mesesinv tab-mesesinv  widefat tab-dark striped' >
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Mes</th>
                        <th>Año</th>
                        <th>Interes</th>
                        <th>Notas</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                  </table>";
    }

    // Tabla de status nuevos

    $tablaconstatus = "<table class='tab-constatus  widefat tab-ui striped' >
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Año</th>
                        <th>Mes</th>
                        <th>Porcentaje</th>
                        <th>Tipo</th>
                        <th>Notas</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>";

    //comprobamos existan meses sino se imprime la tabla vacia

    if(count($nuevosstatus) == 0){

    }else{
      $tablaconstatus .= "<tbody class=''>";
      foreach ($nuevosstatus as $key => $value) {

        $tablaconstatus .= "<tr >";

        $tablaconstatus .= "<td>". $key+1 ."</td>";

        // Impresion año y mes segun si hay deposito
        $tmes = $mesesNombre[$value["nscon_mes"]];
        $tablaconstatus .= "<td>".$value["nscon_year"]."</td>";
        $tablaconstatus .= "<td>".$tmes."</td>";

        if($value["nscon_tipo"] == 1){
          $tipostatus = "ESP";
          $porcstatus = $value['nscon_porcentaje']."%";
        }else {
          $tipostatus = "REG";
          $porcstatus = "NA";
        }

        $tablaconstatus .= "<td class='td-".$value['nscon_id']."'>".$porcstatus."</td><td class='td-".$value['nscon_id']."'>".$tipostatus."</td>
                          <td><button aria-label='".$value['nscon_notas']."' data-microtip-position='top' data-microtip-size='medium' class='microtexto td-".$value['nscon_id']."-notas' role='tooltip'><span class='material-icons'>speaker_notes</span></button></td>
                          <td><input alt='#TB_inline?width=400&inlineId=modal-editconstatus' title='Editar status del mes' data-id='".$value['nscon_id']."' data-porcentaje='".$value['nscon_porcentaje']."' data-mes='".$value['nscon_mes']."' data-year='".$value['nscon_year']."' data-tipo='".$value['nscon_tipo']."' class='thickbox button button-primary button-large btn-editmes btn-editconstatus' type='button' value='Editar' /> <button aria-label='Eliminar' data-microtip-position='top' data-microtip-size='small' class='btn-elimbal btn-elimstatus' data-id='".$value['nscon_id']."' role='tooltip'><span class='material-icons'>close</span></button></td>
                        </tr>";
      }
      $tablaconstatus .= "</tbody>";
    }

    $tablaconstatus .=  "</table>";

    $output = "
      <h3> Datos del inversionista </h3>
      <table class='form-table'>
        <tr class='user-modaccesinteres-wrap'>
          <th scope='row'><label for='modaccesinteres'>Acceso Módulo Interes Compuesto:</label></th>
          <td>
          </td>
        </tr>
        <tr class='user-status-wrap'>
          <th scope='row'><label for='status'>Status</label></th>
          <td>
            <select name='status' id='status' data-status='$status' >
              <option value='' ". (($status == '') ? "selected" : "") .">Seleccione una opción</option>
              <option value='5' ". (($status == '5') ? "selected" : "") .">5%</option>
              <option value='10' ". (($status == '10') ? "selected" : "") .">10%</option>
              <option value='12' ". (($status == '12') ? "selected" : "") .">12%</option>
              <option value='15' ". (($status == '15') ? "selected" : "") .">15%</option>
            </select>
            <p class='description'>Porcentaje asignado por el administrador</p>
            <br>
            <p class='description'>Notas para el cambio de status.</p>
            <textarea class='regular-text notasstatus' name='notasstatus' id='notasstatus' rows='5' cols='14' value='' ></textarea>
            <input type='hidden' name='mesactual' id='mesactual' value='$mesinversor' >
            <br>
            <br>
            <p class='description'>Mes actual del inversionista: <span class='mesactual'>".$mesinversor."</span> </p>
            <br>";

    if($status){
      $output .=   "<h3 class='text-white'>Agregar meses adicionales</h3>
        <br>
        <p>Si desea agregar mas meses para el inversionista dentro de la plataforma, ingrese el numero de meses a continuación y presione 'Agregar': </p>
        <br>
        <div>
        <input type='number' name='mesesextra' id='mesesextra' value='0' min=1 max=60 data-user='".$user."'>
        <button class='crearmeses button-primary' id='crearmeses'>Agregar</button>
        </div>
        <br>";
    }

    $output .= "<br>
            ".$tablameses."
          </td>
        </tr>
        <tr class='user-modaccesagresivo'>
          <th scope='row'><label for='modagresivo'>Acceso a Módulo Agresivo</label></th>
          <td>".$toggleagresivo."</td>
        </tr>
        <tr class='user-modaccesagresivo'>
          <th scope='row'><label for='modagresivopart'>Participa en Módulo Agresivo</label></th>
          <td>".$toggleagresivopart."<br></td>
        </tr>
        <tr class='user-modaccesconservador'>
          <th scope='row'><label for='modconservador'>Participa en Módulo Conservador</label></th>
          <td>".$toggleconservador;

    $output .=   "
          <br>
          <h3 class='text-white'>Agregar porcentaje de utilidades especiales</h3>
          <br>
          <p>Si desea modificar el porcentaje de utilidad que el usuario va a recibir en el modulo conservador por uno en especial. Ingrese el porcentaje de utilidad, mes y año partir del cual empezará a aplicar en el dashboard del inversionista y presione 'Agregar': </p>
          <br>
          <div>
          <p class='description'>Mes y año a partir del cual empezará a aplicar el nuevo porcentaje:</p><br>
          <input type='number' name='constatusmes' id='constatusmes' value='".$mesact."' min=1 max=12 step=1 data-user='".$user."'>
          <input type='number' name='constatusyear' id='constatusyear' value='".$agnoact."' min=2023 max=2100 step=1 data-user='".$user."'><br>
          <br>
          <p class='description'>Tipo porcentaje de utilidad a registrar:</p><br>
          <input type='radio' class='cambio-cuenta' id='espsta' name='tipostatus' value='1' checked >
          <label for='espsta'>Especial</label>
          <input type='radio' class='cambio-cuenta' id='regsta' name='tipostatus' value='0' >
          <label for='siact' class='regsta' >Regular</label><br>
          <br>
          <p class='description'>Nuevo porcentaje de utilidad a aplicar:</p><br>
          <input type='number' name='constatusporcentaje' id='constatusporcentaje' value='10' min=0 max=100 step=0.01 data-user='".$user."'><br>
          <br>
          <p class='description'>Notas:</p><br>
          <textarea class='regular-text constatusnotas' name='constatusnotas' id='constatusnotas' rows='5' cols='14' value='' ></textarea>
          <br>
          <br>
          <button class='crearconstatus button-primary' id='crearconstatus'>Agregar</button>
          </div>
          <br>
          <p>El porcentaje de utilidad especial aplicará siempre por encima del porcentaje de utilidad regular, y solo dejará de aplicar cuando ingrese un nuevo porcentaje de utilidad de tipo 'Ordinario'</p>
          <br>
          ".$tablaconstatus."<br></td>";

    $output .= "
        </tr>
        <tr class='user-referido-wrap'>
          <th scope='row'><label for='referido'>Código de quien te invitó</label></th>
          <td>
            <input class='regular-text' type='text' name='referido' id='referido' size='40' value='$referido' >
            <p class='description'>Si no ingresaste a alguien se muestra el código por default.</p>
          </td>
        </tr>
        <tr class='user-invitecode-wrap'>
          <th scope='row'><label for='invitecode'>Tu código de invitación</label></th>
          <td>
            <input class='regular-text' type='text' name='invitecode' id='invitecode' size='40' value='$invitecode' readonly disabled='disabled' >
            <p class='description'>Tu código de invitación</p>
          </td>
        </tr>
        <tr class='form-required user-wallet-wrap'>
          <th scope='row'><label for='wallet'>Tipo de Wallet</label></th>
          <td>
            <select name='wallet' id='wallet' >". (($wallet == '') ? '<option value="" selected>Seleccione una opción</option>' : '<option value="" >Seleccione una opción</option>') .
            (($wallet == 'bitcoin') ? '<option value="bitcoin" selected>Bitcoin</option>' : '<option value="bitcoin">Bitcoin</option>') .
            (($wallet == 'usdt') ? '<option value="usdt" selected>USDT (Tether)</option>' : '<option value="usdt">USDT (Tether)</option>') .
            (($wallet == 'ethereum') ? '<option value="ethereum" selected>Ethereum</option>' : '<option value="ethereum">Ethereum</option>') .
            "</select>
            <p class='description' data-wallet='$wallet'></p>
          </td>
        </tr>
        <tr class='form-required user-walletcode-wrap'>
          <th scope='row'><label for='walletcode'>Wallet Address</label></th>
          <td>
            <input class='regular-text' type='text' name='walletcode' id='walletcode' size='40' value='$wallet' >
            <p class='description'>Tu wallet Addres donde recibiras los retiros.</p>
          </td>
        </tr>
        <tr class='form-required user-wallet2-wrap'>
          <th scope='row'><label for='walle2t'>Tipo de Wallet Adicional</label></th>
          <td>
            <select name='wallet2' id='wallet2' >". (($wallet2 == '') ? '<option value="" selected>Seleccione una opción</option>' : '<option value="" >Seleccione una opción</option>') .
            (($wallet2 == 'bitcoin') ? '<option value="bitcoin" selected>Bitcoin</option>' : '<option value="bitcoin">Bitcoin</option>') .
            (($wallet2 == 'usdt') ? '<option value="usdt" selected>USDT (Tether)</option>' : '<option value="usdt">USDT (Tether)</option>') .
            (($wallet2 == 'ethereum') ? '<option value="ethereum" selected>Ethereum</option>' : '<option value="ethereum">Ethereum</option>') .
            "</select>
            <p class='description'></p>
          </td>
        </tr>
        <tr class='form-required user-walletcode2-wrap'>
          <th scope='row'><label for='walletcode2'>Wallet Address Adicional</label></th>
          <td>
            <input class='regular-text' type='text' name='walletcode2' id='walletcode2' size='40' value='$walletcode2' >
            <p class='description'>Tu wallet Addres adicional.</p>
          </td>
        </tr>
        <tr class='form-required user-pais-wrap'>
          <th scope='row'><label for='pais'>Pais</label></th>
          <td>
            <select id='pais' name='pais' >". (($paist == '') ? '<option value="" selected>Seleccione un pais</option>' : '<option value="" >Seleccione un pais</option>') .
            (($paist == 'MEX') ? '<option value="MEX" selected>México</option>' : '<option value="MEX">México</option>') .
            (($paist == 'USA') ? '<option value="USA" selected>Estados Unidos</option>' : '<option value="USA">Estados Unidos</option>') ."
            </select>
          </td>
        </tr>
        <tr class='form-required user-estado-wrap'>
          <th scope='row'><label for='estado'>Estado</label></th>
          <td>
            <select id='estado' name='estado' disabled='disabled'>". (($estado == '') ? '<option value="" selected>Seleccione un estado/option>' : '<option value="" >Seleccione un estado</option>') .
            $estadot."
            </select>
          </td>
        </tr>
        <tr class='form-required user-municipio-wrap'>
          <th scope='row'><label for='municipio'>$municipion</label></th>
          <td>
            <input class='regular-text' type='text' name='municipio' id='municipio' size='40' value='$municipio' >
          </td>
        </tr>
        <tr class='form-required user-calle-wrap'>
          <th scope='row'><label for='calle'>Calle</label></th>
          <td>
            <input class='regular-text' type='text' name='calle' id='calle' size='40' value='$calle' >
          </td>
        </tr>
        <tr class='form-required user-zipcode-wrap'>
          <th scope='row'><label for='zipcode'>Código postal</label></th>
          <td>
            <input class='regular-text' type='text' name='zipcode' id='zipcode' size='40' value='$zipcode' >
          </td>
        </tr>
        <tr class='form-required user-fotografia-wrap'>
           <th scope='row'><label for='fotografia'>Fotografía de perfil</label></th>
           <td>
           ".$fotoimg."
           <input type='file' name='fotografia' value='' />
           </td>
        </tr>
        <tr class='form-required user-activo-wrap'>
          <th scope='row'><label for='activo'>Usuario con acceso</label></th>
          <td>
            <input type='radio' id='siact'name='activo' value='1' ". (($activo == '1') ? "checked" : "") .">
            <label for='siact' class='siact' >Sí</label>
            <input type='radio' id='noact'name='activo' value='0' ". (($activo == '0') ? "checked" : "") .">
            <label for='noact'>No</label>
            <p class='description'>Marque la opcion ( Sí ) si desea que el usuario tenga acceso a la plataforma.</p>
          </td>
        </tr>
      </table>

    ";

    echo $output;

  }

  public function add_diveditmes(){

    $output = "<div id='modal-editmes' style='display:none;' >
      <form id='form-editmes' class='' action='' method='post'>
        <input type='hidden' id='idmes' name='idmes' value=''>
        <div class='campo'>
          <label for='intmes'>Status del mes: </label>
          <select id='intmes' name='intmes'>
            <option value='' selected>Seleccione una opción</option>
            <option value='0'>0%</option>
            <option value='5'>5%</option>
            <option value='10'>10%</option>
            <option value='12'>12%</option>
            <option value='15'>15%</option>
          </select>
        </div>
        <div class='campo'>
          <label for='menotas'><i class='fa fa-user'></i>Notas: </label>
          <textarea name='menotas' id='menotas' rows='5' cols='18' style='resize: none;' ></textarea>
        </div>
        <div class='campo-especial'>
          <input id='editarintmes' type='submit' name='editarintmes' class='button ' value='Editar'>
          <input type='hidden' name='oculto' value='1'><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
        </div>
      </form>
    </div>
    <div id='modal-editconstatus' style='display:none;' >
      <form id='form-editconstatus' class='' action='' method='post'>
        <input type='hidden' id='idconstatus' name='idconstatus' value=''>
        <div class='campo'>
          <label for='statusmes'>Mes: </label>
          <input type='number' name='statusmes' id='statusmes' value='' min=1 max=12 step=1 >
        </div>
        <div class='campo'>
          <label for='statusyear'>Año: </label>
          <input type='number' name='statusyear' id='statusyear' value='' min=2022 max=2100 step=1 >
        </div>
        <div class='campo'>
          <label for='statusporcentaje'>Porcentaje del mes: </label>
          <input type='number' name='statusporcentaje' id='statusporcentaje' value='' min=0 max=100 step=0.01 >
        </div>
        <div class='campo'>
          <label for='statustipo'>Tipo: </label>
          <input type='radio' class='cambio-cuenta-e' id='eespsta' name='tipostatuse' value='1' >
          <label for='eespsta'>Especial</label>
          <input type='radio' class='cambio-cuenta-e' id='eregsta' name='tipostatuse' value='0' >
          <label for='eregsta' class='eregsta' >Regular</label><br>
        </div>
        <div class='campo'>
          <label for='statusnotas'>Notas: </label>
          <textarea name='statusnotas' id='statusnotas' rows='5' cols='18' style='resize: none;' ></textarea>
        </div>
        <div class='campo-especial'>
          <input id='editarconstatus' type='submit' name='editarconstatus' class='button ' value='Editar'>
          <input type='hidden' name='oculto' value='1'><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
        </div>
      </form>
    </div>";

    echo $output;
  }

  public function save_meta_fields($user_id){

    if( !current_user_can( 'inversionista' ) ) {
      return;
    }
    if( isset($_POST['wallet']) ){
      $_POST['wallet'] = sanitize_text_field($_POST['wallet']);
      update_user_meta( $user_id, 'wallet', $_POST['wallet']);
    }
    if( isset($_POST['walletcode']) ){
      $_POST['walletcode'] = sanitize_text_field($_POST['walletcode']);
      update_user_meta( $user_id, 'walletcode', $_POST['walletcode']);
    }
    if( isset($_POST['wallet2']) ){
      $_POST['wallet2'] = sanitize_text_field($_POST['wallet2']);
      update_user_meta( $user_id, 'wallet2', $_POST['wallet2']);
    }
    if( isset($_POST['walletcode2']) ){
      $_POST['walletcode2'] = sanitize_text_field($_POST['walletcode2']);
      update_user_meta( $user_id, 'walletcode2', $_POST['walletcode2']);
    }
    if( isset($_POST['municipio']) ){
      $_POST['municipio'] = sanitize_text_field($_POST['municipio']);
      update_user_meta( $user_id, 'municipio', $_POST['municipio']);
    }
    if( isset($_POST['calle']) ){
      $_POST['calle'] = sanitize_text_field($_POST['calle']);
      update_user_meta( $user_id, 'calle', $_POST['calle']);
    }
    if( isset($_POST['zipcode']) ){
      $_POST['zipcode'] = sanitize_text_field($_POST['zipcode']);
      update_user_meta( $user_id, 'zipcode', $_POST['zipcode']);
    }
    $foto = get_user_meta( $user_id, 'fotografia', true );

    if( $_FILES['fotografia']['error'] === UPLOAD_ERR_OK ) {
        if ($foto) {
            $foto  = $foto['file'];
            unset( $foto );
        }
        $upload_overrides = array( 'test_form' => false );
        $upload = wp_handle_upload( $_FILES['fotografia'], $upload_overrides );
        update_user_meta( $user_id, 'fotografia', $upload );


    }
  }

  public function save_adminmeta_fields($user_id){


    if( isset($_POST['status']) ){
      $_POST['status'] = sanitize_text_field($_POST['status']);

      if( isset($_POST['notasstatus']) ){
        $notas = sanitize_text_field($_POST['notasstatus']);
      }else {
        $notas = '';
      }

      update_user_meta( $user_id, 'status', $_POST['status']);

      if($_POST['status'] != "0" && $_POST['status'] != ""){
        //revisamos que no haya meses activos, si no hay le vamos a crear 12 con el interes del staus que se paso.
        global $wpdb;
        $userid = $user_id;
        $tablamesinv = $wpdb->prefix . 'mesesinv';
        $registros = $wpdb->get_results(" SELECT * FROM $tablamesinv WHERE usuario = $userid AND status = 1 ORDER BY id DESC", ARRAY_A);
        $interes = (int)$_POST['status'];


        if(count($registros) == 0){

          for ($i=1; $i < 61 ; $i++) {
            $datos = array(
                'mes'=>$i,
                'usuario'=>$userid,
                'interes'=>$interes,
                'status'=>1,
                'notas'=>$notas
                );

            $formato = array(
            '%d',
            '%d',
            '%d',
            '%d',
            '%s'
            );

            $resultado =  $wpdb->insert($tablamesinv, $datos, $formato);
          }
        }else{
          $mesactual = (int)$_POST['mesactual'];
          foreach ($registros as $key => $value) {

            //checamos que sea el mes o posterior el mes para aplicar el status nuevo.
            $mesbd = (int) $value['mes'];
            if ($mesbd >= $mesactual) {
              $datos = [
                'interes'=>$interes,
                'notas'=>$notas
              ];

              $formato = [
                '%d',
                '%s'
              ];

              $donde = [
                'id' => $value['id']
              ];

              $donde_formato = [
                '%d'
              ];

              $actualizar = $wpdb->update($tablamesinv, $datos, $donde, $formato, $donde_formato);
            }

          }
        }
      }

    }
    if( isset($_POST['notasstatus']) ){
      $_POST['notasstatus'] = sanitize_text_field($_POST['notasstatus']);
      update_user_meta( $user_id, 'notasstatus', $_POST['notasstatus']);
    }
    if( isset($_POST['referido']) ){
      $_POST['referido'] = sanitize_text_field($_POST['referido']);
      update_user_meta( $user_id, 'referido', $_POST['referido']);
    }
    if( isset($_POST['invitecode']) ){
      $_POST['invitecode'] = sanitize_text_field($_POST['invitecode']);
      update_user_meta( $user_id, 'invitecode', $_POST['invitecode']);
    }
    if( isset($_POST['wallet']) ){
      $_POST['wallet'] = sanitize_text_field($_POST['wallet']);
      update_user_meta( $user_id, 'wallet', $_POST['wallet']);
    }
    if( isset($_POST['walletcode']) ){
      $_POST['walletcode'] = sanitize_text_field($_POST['walletcode']);
      update_user_meta( $user_id, 'walletcode', $_POST['walletcode']);
    }
    if( isset($_POST['wallet2']) ){
      $_POST['wallet2'] = sanitize_text_field($_POST['wallet2']);
      update_user_meta( $user_id, 'wallet2', $_POST['wallet2']);
    }
    if( isset($_POST['walletcode2']) ){
      $_POST['walletcode2'] = sanitize_text_field($_POST['walletcode2']);
      update_user_meta( $user_id, 'walletcode2', $_POST['walletcode2']);
    }
    if( isset($_POST['pais']) ){
      $_POST['pais'] = sanitize_text_field($_POST['pais']);
      update_user_meta( $user_id, 'pais', $_POST['pais']);
    }
    if( isset($_POST['estado']) ){
      $_POST['estado'] = sanitize_text_field($_POST['estado']);
      update_user_meta( $user_id, 'estado', $_POST['estado']);
    }
    if( isset($_POST['municipio']) ){
      $_POST['municipio'] = sanitize_text_field($_POST['municipio']);
      update_user_meta( $user_id, 'municipio', $_POST['municipio']);
    }
    if( isset($_POST['calle']) ){
      $_POST['calle'] = sanitize_text_field($_POST['calle']);
      update_user_meta( $user_id, 'calle', $_POST['calle']);
    }
    if( isset($_POST['zipcode']) ){
      $_POST['zipcode'] = sanitize_text_field($_POST['zipcode']);
      update_user_meta( $user_id, 'zipcode', $_POST['zipcode']);
    }
    if( isset($_POST['activo']) ){
      $_POST['activo'] = sanitize_text_field($_POST['activo']);
      update_user_meta( $user_id, 'activo', $_POST['activo']);
    }
    if (isset($_POST['modagresivo'])) {
      $modagre = 1;
      update_user_meta( $user_id, 'modagresivo', $modagre);
    }else{
      $modagre = 0;
      update_user_meta( $user_id, 'modagresivo', $modagre);
    }
    if (isset($_POST['modagresivopart'])) {
      $modagrepart = 1;
      update_user_meta( $user_id, 'modagresivopart', $modagrepart);
    }else{
      $modagrepart = 0;
      update_user_meta( $user_id, 'modagresivopart', $modagrepart);
    }
    if (isset($_POST['modconservador'])) {
      $modcons = 1;
      update_user_meta( $user_id, 'modconservador', $modcons);
    }else{
      $modcons = 0;
      update_user_meta( $user_id, 'modconservador', $modcons);
    }

    $foto = get_user_meta( $user_id, 'fotografia', true );

    if( $_FILES['fotografia']['error'] === UPLOAD_ERR_OK ) {
        if ($foto) {
            $foto  = $foto['file'];
            unset( $foto );
        }
        $upload_overrides = array( 'test_form' => false );
        $upload = wp_handle_upload( $_FILES['fotografia'], $upload_overrides );
        update_user_meta( $user_id, 'fotografia', $upload );


    }
  }

}
