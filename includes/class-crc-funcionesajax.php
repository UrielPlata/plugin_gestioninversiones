<?php

class CRC_Funcionesajax{

  public function buscarUserRegEmail(){
    $codigo = $_POST['codigo'];
    $tipo = $_POST['tipo'];

    $existe = email_exists( $codigo );

    $listado = array();

    $listado[] = array(
      'existe' => $existe
    );

    header('Content-type: application/json');
    echo json_encode( $listado );
    die;
  }

  public function buscarUserRegNick(){
    $codigo = $_POST['codigo'];
    $existe = username_exists( $codigo );

    $listado2 = array();

    $listado2[] = array(
      'existe' => $existe
    );

    header('Content-type: application/json');
    echo json_encode( $listado2 );
    die;
  }

  public function solicitar_retiro(){
    if(isset($_POST['user'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'retiros';

      date_default_timezone_set("America/Mexico_City");

      $cantidad = (float)$_POST['cantidad'];
      $usuario = (int)$_POST['user'];
      if($_POST['urgente'] == 'no'){
        $urgente = 0;
      }else{
        $urgente = 1;
      }

      if ($_POST['fecha_cuando'] == '') {
        $fecha_cuando = 0;
      }else if($_POST['fecha_cuando'] == '15'){
        $fecha_cuando = 1;
      }else{
        $fecha_cuando = 2;
      }
      $fecha_retiro = date("Y-m-d");
      $codigo = $this->generar_codigo();

      $datos = array(
          'cantidad'=>$cantidad,
          'usuario'=>$usuario,
          'urgente'=>$urgente,
          'fecha_cuando'=>$fecha_cuando,
          'fecha_retiro'=>$fecha_retiro,
          'codigo'=>$codigo,
          'status'=>0
          );

      $formato = array(
      '%f',
      '%d',
      '%d',
      '%d',
      '%s',
      '%s',
      '%d'
      );

      $resultado =  $wpdb->insert($tabla, $datos, $formato);

      if($resultado==1){

        //correo de generacion de solicitud de retiro
        $url = home_url('/confirmacion');
        $urlcompleta = $url . '/?code_submitted='.$codigo.'&tipo=ret';
        $user_data = get_userdata( $usuario );

         wp_mail( $user_data->user_email, 'Solicitud de confirmación de retiro', '<p>Una solicitud de retiro ha sido generada. Por favor de click en el siguiente enlace para confirmar su solicitud: <a href="'.$urlcompleta.'" target="_blank">Confirmar retiro</a> .</p><br><p> En caso de no funcionar el link, por favor copie y pegue la siguiente URL en su navegador para visitarla: '.$urlcompleta.'</p>');
        $respuesta = array(
          'respuesta'=>1,
          'codigo'=>$codigo
        );
      }else{
        $respuesta=array(
          'respuesta'=>'error'
        );
      }

    }
    die(json_encode($respuesta));
  }

  public function solicitar_deposito(){
    if(isset($_POST['user'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'depositos';

      date_default_timezone_set("America/Mexico_City");

      $cantidad = (float)$_POST['cantidad'];
      $usuario = (int)$_POST['user'];
      $interes = (int)$_POST['interes'];

      if ($_POST['fecha_cuando'] == '') {
        $fecha_cuando = 0;
      }else if($_POST['fecha_cuando'] == '1'){
        $fecha_cuando = 1;
      }else{
        $fecha_cuando = 2;
      }
      $fecha_deposito = date("Y-m-d");
      $codigo = $this->generar_codigo();


      $datos = array(
          'cantidad'=>$cantidad,
          'usuario'=>$usuario,
          'fecha_cuando'=>$fecha_cuando,
          'fecha_deposito'=>$fecha_deposito,
          'codigo'=>$codigo,
          'status'=>0,
          'interes'=> $interes
          );

      $formato = array(
      '%f',
      '%d',
      '%d',
      '%s',
      '%s',
      '%d',
      '%d'
      );

      $resultado =  $wpdb->insert($tabla, $datos, $formato);

      if($resultado==1){

        //correo de generacion de solicitud de deposito
        $url = home_url('/confirmacion');
        $urlcompleta = $url . '/?code_submitted='.$codigo.'&tipo=dep';
        $user_data = get_userdata( $usuario );

         wp_mail( $user_data->user_email, 'Solicitud de confirmación de depósito', '<p>Una solicitud de depósito ha sido generada. Por favor de click en el siguiente enlace para confirmar su solicitud: <a href="'.$urlcompleta.'" target="_blank">Confirmar depósito</a> .</p><br><p> En caso de no funcionar el link, por favor copie y pegue la siguiente URL en su navegador para visitarla: '.$urlcompleta.'</p>');
        $respuesta = array(
          'respuesta'=>1,
          'codigo'=>$codigo
        );
      }else{
        $respuesta=array(
          'respuesta'=>'error'
        );
      }

    }
    die(json_encode($respuesta));
  }

  public function operacion_newbalance(){
    if(isset($_POST['balfinal'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'controlmaster';

      date_default_timezone_set("America/Mexico_City");

      $mes = (int)$_POST['mes'];
      $agno = (int)$_POST['agno'];
      $start_balance = (float)$_POST['startbal'];
      $balance_bef_com = (float)$_POST['balbefcom'];
      $com_broker = (float)$_POST['combroker'];
      $com_trader = (float)$_POST['comtrader'];
      $balance_final = (float)$_POST['balfinal'];
      $total_cuentas = (float)$_POST['totalcuentas'];

      $datos = array(
          'mes'=> $mes,
          'agno'=> $agno,
          'start_balance'=> $start_balance,
          'balance_bef_com'=> $balance_bef_com,
          'com_broker'=> $com_broker,
          'com_trader'=> $com_trader,
          'balance_final'=> $balance_final,
          'total_cuentas'=> $total_cuentas,
          'notas'=> sanitize_text_field($_POST['notas']),
          'status' => 1
          );

      $formato = array(
      '%d',
      '%d',
      '%f',
      '%f',
      '%f',
      '%f',
      '%f',
      '%f',
      '%s',
      '%d'
      );

      $resultado = $wpdb->insert($tabla, $datos, $formato);

      if($resultado==1){

        $respuesta = array(
          'respuesta'=>1,
          'codigo'=>$codigo
        );
      }else{
        $respuesta=array(
          'respuesta'=>'error',
          'data'=>$resultado
        );
      }
    }

    die(json_encode($respuesta));
  }

  public function operacion_editbalance(){
    if(isset($_POST['id'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'controlmaster';

      date_default_timezone_set("America/Mexico_City");

      $id = (int)$_POST['id'];
      $mes = (int)$_POST['mes'];
      $agno = (int)$_POST['agno'];
      $start_balance = (float)$_POST['startbal'];
      $balance_bef_com = (float)$_POST['balbefcom'];
      $com_broker = (float)$_POST['combroker'];
      $com_trader = (float)$_POST['comtrader'];
      $balance_final = (float)$_POST['balfinal'];
      $total_cuentas = (float)$_POST['totalcuentas'];

      $datos = array(
          'mes'=> $mes,
          'agno'=> $agno,
          'start_balance'=> $start_balance,
          'balance_bef_com'=> $balance_bef_com,
          'com_broker'=> $com_broker,
          'com_trader'=> $com_trader,
          'balance_final'=> $balance_final,
          'total_cuentas'=> $total_cuentas,
          'notas'=> sanitize_text_field($_POST['notas'])
          );

      $formato = array(
      '%d',
      '%d',
      '%f',
      '%f',
      '%f',
      '%f',
      '%f',
      '%f',
      '%s'
      );

      $donde = [
        'id' => $id
      ];

      $donde_formato = [
        '%d'
      ];

      $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

        if($actualizar !== false){
          if( $actualizar != 0){

            $respuesta = array(
              'respuesta'=>1,
              'balfinal' => $balance_final,
              'totalinv' => $total_cuentas
            );
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }
        }else{
          $respuesta=array(
            'respuesta'=>'error'
          );
        }
    }

    die(json_encode($respuesta));
  }

  public function operacion_elimbal(){
    if(isset($_POST['id'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'controlmaster';

      date_default_timezone_set("America/Mexico_City");

      $id = (int)$_POST['id'];
      $resultado = $wpdb->delete($tabla, array('id'=>$id), array('%d'));

        if($resultado==1){
          $respuesta = array(
            'respuesta'=>1,
            'id'=>$id_codigo
          );
        }else{
          $respuesta=array(
            'respuesta'=>'error'
          );
        }
    }

    die(json_encode($respuesta));
  }

  public function operacion_editreghist(){
    if(isset($_POST['mes'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'registro_historico';

      date_default_timezone_set("America/Mexico_City");
      $mes = (int)$_POST['mes'];
      $year = (int)$_POST['year'];
      $external = (float)$_POST['utilext'];

      $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE mes = $mes AND year = $year ORDER BY id DESC LIMIT 1", ARRAY_A);

      $return_json = array();

      if(count($registros) == 0){

        $datos = array(
            'mes'=> $mes,
            'year'=> $year,
            'external'=> $external,
            'notas'=> sanitize_text_field($_POST['notas'])
            );

        $formato = array(
          '%d',
          '%d',
          '%f',
          '%s'
        );

        $resultado = $wpdb->insert($tabla, $datos, $formato);

        if($resultado==1){

          $respuesta = array(
            'respuesta'=>1
          );
        }else{
          $respuesta=array(
            'respuesta'=>'error',
            'data'=>$resultado
          );
        }

      }else {
        $datos = array(
            'mes'=> $mes,
            'year'=> $year,
            'external'=> $external,
            'notas'=> sanitize_text_field($_POST['notas'])
            );

        $formato = array(
        '%d',
        '%d',
        '%f',
        '%s'
        );

        $donde = [
          'mes' =>$mes,
          'year' =>$year
        ];

        $donde_formato = [
          '%d',
          '%d'
        ];

        $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

          if($actualizar !== false){
            if( $actualizar != 0){

              $respuesta = array(
                'respuesta'=>1
              );
            }else{
              $respuesta=array(
                'respuesta'=>'error'
              );
            }
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }
      }


    }

    die(json_encode($respuesta));
  }

  public function crear_opemaster(){
    if(isset($_POST['tipo'])){
      global $wpdb;
      $tipo = $_POST['tipo'];

      if($tipo == 'deposito'){
        $tabla = $wpdb->prefix.'depositos_master';

        $idmov_ind = $_POST['idmov_ind'];
        $idmov_gral = $_POST['idmov_gral'];
        $cantidad = (float)$_POST['cantidad'];
        $cantidad_real = (float)$_POST['cantidad_real'];
        $fecha_deposito = $_POST['fecha_deposito'];
        $notas = sanitize_textarea_field($_POST['notas']);

        $datos = array(
            'cantidad'=> $cantidad,
            'cantidad_real'=> $cantidad_real,
            'fecha_deposito'=> $fecha_deposito,
            'status'=> 1,
            'idmov_ind'=> $idmov_ind,
            'notas'=> $notas,
            'idmov_gral'=> $idmov_gral
            );

        $formato = array(
        '%f',
        '%f',
        '%s',
        '%d',
        '%s',
        '%s',
        '%s'
        );

        $resultado = $wpdb->insert($tabla, $datos, $formato);

        if($resultado==1){

          $respuesta = array(
            'respuesta'=>1,
            'codigo'=>$codigo
          );
        }else{
          $respuesta=array(
            'respuesta'=>'error',
            'data'=>$resultado
          );
        }

      }else{
        $tabla = $wpdb->prefix.'retiros_master';

        $idmov_ind = $_POST['idmov_ind'];
        $cantidad = (float)$_POST['cantidad'];
        $cantidad_real = (float)$_POST['cantidad_real'];
        $fecha_retiro = $_POST['fecha_retiro'];
        $notas = sanitize_textarea_field($_POST['notas']);

        $datos = array(
            'cantidad'=> $cantidad,
            'cantidad_real'=> $cantidad_real,
            'fecha_retiro'=> $fecha_retiro,
            'status'=> 1,
            'idmov_ind'=> $idmov_ind,
            'notas'=> $notas
            );

        $formato = array(
        '%f',
        '%f',
        '%s',
        '%d',
        '%s',
        '%s'
        );

        $resultado = $wpdb->insert($tabla, $datos, $formato);

        if($resultado==1){

          $respuesta = array(
            'respuesta'=>1,
            'codigo'=>$codigo
          );
        }else{
          $respuesta=array(
            'respuesta'=>'error',
            'data'=>$resultado
          );
        }
      }
    }
    die(json_encode($respuesta));
  }

  public function editar_opemaster(){
    if(isset($_POST['tipo'])){
      global $wpdb;
      $tipo = $_POST['tipo'];

      if($tipo == 'deposito'){
        $tabla = $wpdb->prefix.'depositos_master';

        $id = (int)$_POST['id'];
        $idmov_ind = $_POST['idmov_ind'];
        $idmov_gral = $_POST['idmov_gral'];
        $cantidad = (float)$_POST['cantidad'];
        $cantidad_real = (float)$_POST['cantidad_real'];
        $fecha_deposito = $_POST['fecha_deposito'];
        $notas = sanitize_textarea_field($_POST['notas']);

        $datos = array(
            'cantidad'=> $cantidad,
            'cantidad_real'=> $cantidad_real,
            'fecha_deposito'=> $fecha_deposito,
            'idmov_ind'=> $idmov_ind,
            'notas'=> $notas,
            'idmov_gral'=> $idmov_gral
            );

        $formato = array(
        '%f',
        '%f',
        '%s',
        '%s',
        '%s',
        '%s'
        );

        $donde = [
          'id' => $id
        ];

        $donde_formato = [
          '%d'
        ];

        $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

          if($actualizar !== false){
            if( $actualizar != 0){

              $respuesta = array(
                'respuesta'=>1
              );
            }else{
              $respuesta=array(
                'respuesta'=>'error'
              );
            }
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }

      }else{
        $tabla = $wpdb->prefix.'retiros_master';

        $id = (int)$_POST['id'];
        $idmov_ind = $_POST['idmov_ind'];
        $cantidad = (float)$_POST['cantidad'];
        $cantidad_real = (float)$_POST['cantidad_real'];
        $fecha_retiro = $_POST['fecha_retiro'];
        $notas = sanitize_textarea_field($_POST['notas']);

        $datos = array(
            'cantidad'=> $cantidad,
            'cantidad_real'=> $cantidad_real,
            'fecha_retiro'=> $fecha_retiro,
            'idmov_ind'=> $idmov_ind,
            'notas'=> $notas
            );

        $formato = array(
        '%f',
        '%f',
        '%s',
        '%s',
        '%s'
        );

        $donde = [
          'id' => $id
        ];

        $donde_formato = [
          '%d'
        ];

        $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

          if($actualizar !== false){
            if( $actualizar != 0){

              $respuesta = array(
                'respuesta'=>1
              );
            }else{
              $respuesta=array(
                'respuesta'=>'error'
              );
            }
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }
      }
    }
    die(json_encode($respuesta));
  }

  public function cancelar_opemaster(){
    if(isset($_POST['tipo'])){
      global $wpdb;
      $tipo = $_POST['tipo'];

      if($tipo == 'deposito'){
        $tabla = $wpdb->prefix.'depositos_master';

        $id = (int)$_POST['id'];

        $datos = array(
            'status'=> 0
            );

        $formato = array(
        '%d'
        );

        $donde = [
          'id' => $id
        ];

        $donde_formato = [
          '%d'
        ];

        $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

          if($actualizar !== false){
            if( $actualizar != 0){

              $respuesta = array(
                'respuesta'=>1
              );
            }else{
              $respuesta=array(
                'respuesta'=>'error'
              );
            }
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }

      }else{
        $tabla = $wpdb->prefix.'retiros_master';

        $id = (int)$_POST['id'];

        $datos = array(
            'status'=> 0
            );

        $formato = array(
        '%d'
        );

        $donde = [
          'id' => $id
        ];

        $donde_formato = [
          '%d'
        ];

        $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

          if($actualizar !== false){
            if( $actualizar != 0){

              $respuesta = array(
                'respuesta'=>1
              );
            }else{
              $respuesta=array(
                'respuesta'=>'error'
              );
            }
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }
      }
    }
    die(json_encode($respuesta));
  }

  public function operacion_finalizar(){
    if(isset($_POST['id'])){
      global $wpdb;

      date_default_timezone_set("America/Mexico_City");

      $id = $_POST['id'];
      $tipo = $_POST['tipo'];

      if ($tipo == 'deposito') {
        $tabla = $wpdb->prefix.'depositos';

        //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));
        $fechacuando = (int)$_POST['fecha_cuando'];
        $fechafin = $_POST['fechafin'];
        $fechasol = $_POST['fechasol'];
        $cantidadfin = (float)$_POST['cantidadfin'];

        $datos = [
        'status'=> 2,
        'idmov_ind'=> $_POST['idmovind'],
        'idmov_gral'=> $_POST['idmovgral'],
        'cantidad_real'=> $cantidadfin,
        'fecha_cuando'=> $fechacuando,
        'fecha_deposito' => $fechasol,
        'fecha_termino' => $fechafin,
        'notas' => sanitize_text_field($_POST['notas'])
        ];

        $formato = [
          '%d',
          '%s',
          '%s',
          '%f',
          '%d',
          '%s',
          '%s',
          '%s'
        ];

        $donde = [
          'id' => $id
        ];

        $donde_formato = [
          '%d'
        ];

        $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

          if($actualizar !== false){
            if( $actualizar != 0){

              $respuesta = array(
                'respuesta'=>1
              );
            }else{
              $respuesta=array(
                'respuesta'=>'error'
              );
            }
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }

      }else {
        $tabla = $wpdb->prefix.'retiros';

        //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));
        $urgente = (int)$_POST['urgente'];
        $fechacuando = (int)$_POST['fecha_cuando'];
        $fechafin = $_POST['fechafin'];
        $fechasol = $_POST['fechasol'];
        $cantidadfin = (float)$_POST['cantidadfin'];

        $datos = [
        'urgente' => $urgente,
        'fecha_cuando' => $fechacuando,
        'status'=> 2,
        'idmov_ind'=> $_POST['idmovind'],
        'idmov_gral'=> '',
        'cantidadfin'=> $cantidadfin,
        'fecha_retiro' => $fechasol,
        'fecha_termino' => $fechafin,
        'notas' => sanitize_text_field($_POST['notas'])
        ];

        $formato = [
          '%d',
          '%d',
          '%d',
          '%s',
          '%s',
          '%f',
          '%s',
          '%s',
          '%s'
        ];

        $donde = [
          'id' => $id
        ];

        $donde_formato = [
          '%d'
        ];

        $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

          if($actualizar !== false){
            if( $actualizar != 0){

              $respuesta = array(
                'respuesta'=>1
              );
            }else{
              $respuesta=array(
                'respuesta'=>'error'
              );
            }
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }
      }

    }

    die(json_encode($respuesta));
  }

  public function operacion_editar(){
    if(isset($_POST['id'])){
      global $wpdb;

      date_default_timezone_set("America/Mexico_City");

      $id = $_POST['id'];
      $tipo = $_POST['tipo'];

      if ($tipo == 'deposito') {
        $tabla = $wpdb->prefix.'depositos';

        //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));

        $fechacuando = (int)$_POST['fecha_cuando'];
        $fechafin = $_POST['fechafin'];
        $fechasol = $_POST['fechasol'];
        $cantidadfin = (float)$_POST['cantidadfin'];

        $datos = [
        'status'=> 2,
        'idmov_ind'=> $_POST['idmovind'],
        'idmov_gral'=> $_POST['idmovgral'],
        'cantidad_real'=> $cantidadfin,
        'fecha_cuando' => $fechacuando,
        'fecha_deposito' => $fechasol,
        'fecha_termino' => $fechafin,
        'notas' => sanitize_text_field($_POST['notas'])
        ];

        $formato = [
          '%d',
          '%s',
          '%s',
          '%f',
          '%d',
          '%s',
          '%s',
          '%s'
        ];

        $donde = [
          'id' => $id
        ];

        $donde_formato = [
          '%d'
        ];

        $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

          if($actualizar !== false){
            if( $actualizar != 0){

              $respuesta = array(
                'respuesta'=>1
              );
            }else{
              $respuesta=array(
                'respuesta'=>'error'
              );
            }
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }

      }else {
        $tabla = $wpdb->prefix.'retiros';

        //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));
        $urgente = (int)$_POST['urgente'];
        $fechacuando = (int)$_POST['fecha_cuando'];
        $fechafin = $_POST['fechafin'];
        $fechasol = $_POST['fechasol'];
        $cantidadfin = (float)$_POST['cantidadfin'];

        $datos = [
        'urgente' => $urgente,
        'fecha_cuando' => $fechacuando,
        'status'=> 2,
        'idmov_ind'=> $_POST['idmovind'],
        'cantidadfin'=> $cantidadfin,
        'fecha_retiro' => $fechasol,
        'fecha_termino' => $fechafin,
        'notas' => sanitize_text_field($_POST['notas'])
        ];

        $formato = [
          '%d',
          '%d',
          '%d',
          '%s',
          '%f',
          '%s',
          '%s',
          '%s'
        ];

        $donde = [
          'id' => $id
        ];

        $donde_formato = [
          '%d'
        ];

        $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

          if($actualizar !== false){
            if( $actualizar != 0){

              $respuesta = array(
                'respuesta'=>1
              );
            }else{
              $respuesta=array(
                'respuesta'=>'error'
              );
            }
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }
      }

    }

    die(json_encode($respuesta));
  }

  public function operacion_cancelar(){
    if(isset($_POST['id'])){
      global $wpdb;

      date_default_timezone_set("America/Mexico_City");

      $id = $_POST['id'];
      $tipo = $_POST['tipo'];

      if ($tipo == 'deposito') {
        $tabla = $wpdb->prefix.'depositos';

        //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));
        $fechafin = $_POST['fechafin'];
        $fechasol = $_POST['fechasol'];

        $datos = [
        'status'=> 3,
        'fecha_deposito' => $fechasol,
        'fecha_termino' => $fechafin,
        'notas' => sanitize_text_field($_POST['notas'])
        ];

        $formato = [
          '%d',
          '%s',
          '%s',
          '%s'
        ];

        $donde = [
          'id' => $id
        ];

        $donde_formato = [
          '%d'
        ];

        $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

          if($actualizar !== false){
            if( $actualizar != 0){

              $respuesta = array(
                'respuesta'=>1
              );
            }else{
              $respuesta=array(
                'respuesta'=>'error'
              );
            }
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }

      }else {
        $tabla = $wpdb->prefix.'retiros';

        //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));
        $fechafin = $_POST['fechafin'];
        $fechasol = $_POST['fechasol'];

        $datos = [
        'status'=> 3,
        'fecha_retiro' => $fechasol,
        'fecha_termino' => $fechafin,
        'notas' => sanitize_text_field($_POST['notas'])
        ];

        $formato = [
          '%d',
          '%s',
          '%s',
          '%s'
        ];

        $donde = [
          'id' => $id
        ];

        $donde_formato = [
          '%d'
        ];

        $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

          if($actualizar !== false){
            if( $actualizar != 0){

              $respuesta = array(
                'respuesta'=>1
              );
            }else{
              $respuesta=array(
                'respuesta'=>'error'
              );
            }
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }
      }

    }

    die(json_encode($respuesta));
  }

  public function operacion_editmes(){
    if(isset($_POST['id'])){
      global $wpdb;

      date_default_timezone_set("America/Mexico_City");

      $id = (int) $_POST['id'];

      $tabla = $wpdb->prefix.'mesesinv';

      //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ))
      $interes = (int)$_POST['interes'];
      $notas = $_POST['notas'];

      $datos = [
      'interes'=> $interes,
      'notas' => $notas
      ];

      $formato = [
        '%d',
        '%s'
      ];

      $donde = [
        'id' => $id
      ];

      $donde_formato = [
        '%d'
      ];

      $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

        if($actualizar !== false){
          if( $actualizar != 0){

            $respuesta = array(
              'respuesta'=>1
            );
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }
        }else{
          $respuesta=array(
            'respuesta'=>'error'
          );
        }

    }

    die(json_encode($respuesta));
  }

  public function operacion_agregarmes(){
    if(isset($_POST['id'])){
      global $wpdb;

      $id = (int) $_POST['id'];

      $tabla = $wpdb->prefix.'mesesinv';

      //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ))
      $nummeses = (int)$_POST['nummeses'];
      $userstatus = (int)$_POST['usersta'];
      $notas = "";

      $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE usuario = $id AND status = 1 ORDER BY id ASC", ARRAY_A);
      $ultelem = array_pop( $registros);
      $ultmes = (int) $ultelem['mes'] ;

      for ($i=0; $i < $nummeses; $i++) {
        $ultmes++;

        $datos = array(
            'mes'=>$ultmes,
            'usuario'=>$id,
            'interes'=>$userstatus,
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

        $resultado =  $wpdb->insert($tabla, $datos, $formato);
      }

        if($resultado !== false){
          if( $resultado != 0){

            $respuesta = array(
              'respuesta'=>1
            );
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }
        }else{
          $respuesta=array(
            'respuesta'=>'error'
          );
        }

    }

    die(json_encode($respuesta));
  }

  public function traer_datos(){
    if(isset($_POST['id'])){
      global $wpdb;

      date_default_timezone_set("America/Mexico_City");

      $id = $_POST['id'];
      $tipo = $_POST['tipo'];

      if ($tipo == 'deposito') {
        $tabla = $wpdb->prefix.'depositos';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE id = $id ORDER BY id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){

          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {

          $fecha_cuando = $value["fecha_cuando"];
          if($fecha_cuando == 1){
            $fechadep = 'Día 1 del mes';
          }else{
            $fechadep = 'Día 15 del mes';
          }

          $cantidad = $value['cantidad'];
          $cantidadreal = $value['cantidad_real'];
          $idmovind = $value['idmov_ind'];
          $idmovgral = $value['idmov_gral'];
          $fecha = $value['fecha_deposito'];
          $fechafin = $value['fecha_termino'];
          $interes = $value['interes'];
          $notas = $value['notas'];

          $row = array(
              'idmovind' => $idmovind,
              'idmovgral' => $idmovgral,
              'cantidad' => $cantidad,
              'cantidadfin' => $cantidadreal,
              'fecha' => $fecha,
              'fecha_cuando' => $fecha_cuando,
              'fechadep' => $fechadep,
              'fechafin' => $fechafin,
              'interes' => $interes,
              'notas' => $notas
            );
          $return_json[] = $row;

        }
        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();

      }else {
        $tabla = $wpdb->prefix.'retiros';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE id = $id ORDER BY id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){

          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {

          $cantidad = $value['cantidad'];
          $cantidadreal = $value['cantidadfin'];
          $idmovind = $value['idmov_ind'];
          $urgente = $value['urgente'];
          $fecha_cuando = $value["fecha_cuando"];
          $fecha = $value['fecha_retiro'];
          $fechafin = $value['fecha_termino'];
          $notas = $value['notas'];

          $row = array(
              'idmovind' => $idmovind,
              'cantidad' => $cantidad,
              'cantidadfin' => $cantidadreal,
              'urgente' => $urgente,
              'fecha_cuando' => $fecha_cuando,
              'fecha' => $fecha,
              'fechafin' => $fechafin,
              'notas' => $notas
            );
          $return_json[] = $row;

        }
        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
      }
      die(json_encode($respuesta));
    }

  }

  public function traer_datosbal(){
    if(isset($_POST['id'])){
      global $wpdb;

      date_default_timezone_set("America/Mexico_City");

      $id = $_POST['id'];
      $tipo = $_POST['tipo'];

      if($tipo == 'editbalance'){
        $tabla = $wpdb->prefix.'controlmaster';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE id = $id ORDER BY id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){

          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {


          $mes = (int)$value['mes'];
          $agno = (int)$value['agno'];
          $start_balance = (float)$value['start_balance'];
          $balance_bef_com = (float)$value['balance_bef_com'];
          $com_broker = (float)$value['com_broker'];
          $com_trader = (float)$value['com_trader'];
          $balance_final = (float)$value['balance_final'];
          $total_cuentas = (float)$value['total_cuentas'];
          $notas = $value['notas'];

          $tabla1 = $wpdb->prefix.'depositos_master';
          $tabla2 = $wpdb->prefix.'retiros_master';
          // $imes = (int)$mes;
          // $iagno = (int)$agno;
          $totaldep = $wpdb->get_results("SELECT month(fecha_deposito) AS mes, year(fecha_deposito) AS agno, ROUND(SUM(cantidad_real), 2) AS totaldep FROM $tabla1 WHERE month(fecha_deposito) = $mes AND year(fecha_deposito) = $agno AND status = 1 ", ARRAY_A);
          $ttotaldep = number_format($totaldep[0]['totaldep'], 2, '.', ',');
          if(!$totaldep[0]['totaldep']){
            $itotaldep = 0.0;
          }else {
            $itotaldep = (float)$totaldep[0]['totaldep'];
          }
          // $ttdp = '<span class="verde btn-ver-depmas" data-mes='.$mes.' data-agno='.$agno.'>+ $'.$ttotaldep.'</span>';

          $totalret = $wpdb->get_results("SELECT month(fecha_retiro) AS mes, year(fecha_retiro) AS agno, ROUND(SUM(cantidad_real), 2) AS totalret FROM $tabla2 WHERE month(fecha_retiro) = $mes AND year(fecha_retiro) = $agno AND status = 1 ", ARRAY_A);
          $ttotalret = number_format($totalret[0]['totalret'], 2, '.', ',');
          if(!$totalret[0]['totalret']){
            $itotalret = 0.0;
          }else {
            $itotalret = (float)$totalret[0]['totalret'];
          }

          $row = array(
            'mes'=> $mes,
            'agno'=> $agno,
            'startbal'=> $start_balance,
            'balbefcom'=> $balance_bef_com,
            'combroker'=> $com_broker,
            'comtrader'=> $com_trader,
            'balfinal'=> $balance_final,
            'totalcuentas'=> $total_cuentas,
            'depmes'=>$itotaldep,
            'retmes'=>$itotalret,
            'notas'=> $notas,
            );
          $return_json[] = $row;

        }
        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
      }else{
        $mes = (int)$_POST['mes'];
        $agno = (int)$_POST['agno'];

        $tabla = $wpdb->prefix.'controlmaster';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE mes = $mes AND agno = $agno ORDER BY id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){

          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {

          $id = (int)$value['id'];
          $mes = (int)$value['mes'];
          $agno = (int)$value['agno'];
          $start_balance = (float)$value['start_balance'];
          $balance_bef_com = (float)$value['balance_bef_com'];
          $com_broker = (float)$value['com_broker'];
          $com_trader = (float)$value['com_trader'];
          $balance_final = (float)$value['balance_final'];
          $total_cuentas = (float)$value['total_cuentas'];
          $notas = $value['notas'];

          $row = array(
            'id' => $id,
            'mes'=> $mes,
            'agno'=> $agno,
            'startbal'=> $start_balance,
            'balbefcom'=> $balance_bef_com,
            'combroker'=> $com_broker,
            'comtrader'=> $com_trader,
            'balfinal'=> $balance_final,
            'totalcuentas'=> $total_cuentas,
            'notas'=> $notas,
            );
          $return_json[] = $row;

        }
        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
      }

      die(json_encode($respuesta));
    }

  }

  public function traer_datos_opemaster(){
    if(isset($_POST['id'])){
      global $wpdb;

      date_default_timezone_set("America/Mexico_City");

      $id = $_POST['id'];
      $tipo = $_POST['tipo'];

      if($tipo == 'deposito'){
        $tabla = $wpdb->prefix.'depositos_master';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE id = $id ORDER BY id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){

          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {

          $cantidad = (float)$value['cantidad'];
          $cantidadfin = (float)$value['cantidad_real'];
          $fechadep = $value['fecha_deposito'];
          $idmovind = $value['idmov_ind'];
          $idmovgral = $value['idmov_gral'];
          $notas = $value['notas'];

          $row = array(
              'id' => $id,
              'cantidad' => $cantidad,
              'cantidadfin' => $cantidadfin,
              'fechadep' => $fechadep,
              'idmov_ind' => $idmovind,
              'idmov_gral' => $idmovgral,
              'notas' => $notas
            );

          $return_json[] = $row;

        }
        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
      }else{
        $tabla = $wpdb->prefix.'retiros_master';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE id = $id ORDER BY id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){

          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {

          $cantidad = (float)$value['cantidad'];
          $cantidadfin = (float)$value['cantidad_real'];
          $fecharet = $value['fecha_retiro'];
          $idmovind = $value['idmov_ind'];
          $notas = $value['notas'];

          $row = array(
              'id' => $id,
              'cantidad' => $cantidad,
              'cantidadfin' => $cantidadfin,
              'fecharet' => $fecharet,
              'idmov_ind' => $idmovind,
              'notas' => $notas
            );

          $return_json[] = $row;

        }
        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
      }

      die(json_encode($respuesta));
    }

  }

  public function traer_datos_referraluser(){
    if(isset($_POST['id'])){
      global $wpdb;

      date_default_timezone_set("America/Tijuana");

      $id = $_POST['id'];

      $tabla = $wpdb->prefix.'usuarios_bl';

      $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE ubl_id = $id ORDER BY ubl_id DESC", ARRAY_A);

      $return_json = array();

      if(count($registros) == 0){

        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
        return;
      }

      foreach ($registros as $key => $value) {

        $nombre = $value['ubl_nombre'];
        $apellidos = $value['ubl_apellidos'];
        $email = $value['ubl_correo'];
        $tipo = $value['ubl_tipo'];
        $notas = $value['ubl_notas'];

        $row = array(
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'email' => $email,
            'tipo' => $tipo,
            'notas' => $notas
          );
        $return_json[] = $row;

      }
      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();

      die(json_encode($respuesta));
    }

  }

  public function traer_datos_referralregistro(){
    if(isset($_POST['tipo'])){
      global $wpdb;

      date_default_timezone_set("America/Tijuana");

      $id = $_POST['id'];
      $tipo = $_POST['tipo'];

      if ($tipo == "editRegistro") {
        $tabla = $wpdb->prefix.'registros_bl';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE rbl_id = $id ORDER BY rbl_id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){

          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {

          $utilmes = $value['rbl_utilmes'];
          $combro = $value['rbl_combro'];
          $comtra = $value['rbl_comtra'];
          $salini = $value['rbl_salini'];
          $mes = $value['rbl_mes'];
          $year = $value['rbl_year'];
          $notas = $value['rbl_notas'];

          $row = array(
              'mes' => $mes,
              'year' => $year,
              'utilmes' => $utilmes,
              'combro' => $combro,
              'comtra'=> $comtra,
              'salini'=>$salini,
              'notas' => $notas
            );
          $return_json[] = $row;

        }
        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();

      }else{
        $cid = (int)$_POST['cid'];
        $mes = (int)$_POST['mes'];
        $agno = (int)$_POST['year'];

        $tabla = $wpdb->prefix.'registros_bl';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE rbl_cuenta = $cid AND rbl_mes = $mes AND rbl_year = $agno ORDER BY rbl_id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){
          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {

          $id = (int)$value['rbl_id'];
          $mes = (int)$value['rbl_mes'];
          $year = (int)$value['rbl_year'];
          $utilmes = (float)$value['rbl_utilmes'];
          $combro = (float)$value['rbl_combro'];
          $comtra = (float)$value['rbl_comtra'];
          $salini = (float)$value['rbl_salini'];
          $notas = $value['rbl_notas'];

          $row = array(
            'rid' => $id,
            'mes'=> $mes,
            'year'=> $year,
            'utilmes'=> $utilmes,
            'combro'=> $combro,
            'comtra'=> $comtra,
            'salini'=> $salini,
            'notas'=> $notas
            );
          $return_json[] = $row;

        }
        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
      }

      die(json_encode($respuesta));

    }

  }

  public function traer_datos_referralregistronft(){
    if(isset($_POST['tipo'])){
      global $wpdb;

      date_default_timezone_set("America/Tijuana");

      $id = $_POST['id'];
      $tipo = $_POST['tipo'];

      if ($tipo == "editRegistro") {
        $tabla = $wpdb->prefix.'registros_nft';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE rnft_id = $id ORDER BY rnft_id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){

          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {

          $total = (float)$value['rnft_total'];
          $team = (float)$value['rnft_team'];
          $semana = (int)$value['rnft_semana'];
          $mes = (int)$value['rnft_mes'];
          $year = (int)$value['rnft_year'];
          $notas = $value['rnft_notas'];
          $status = $value['rnft_status'];
          $id = $value['rnft_id'];

          $row = array(
              'mes' => $mes,
              'year' => $year,
              'semana' => $semana,
              'total' => $total,
              'team'=> $team,
              'status'=>$status,
              'notas' => $notas,
              'id'=>$id
            );
          $return_json[] = $row;

        }
        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();

      }else{
        $pid = (int)$_POST['pid'];
        $mes = (int)$_POST['mes'];
        $agno = (int)$_POST['year'];

        $tabla = $wpdb->prefix.'registros_nft';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE rnft_proyecto = $pid AND rnft_mes = $mes AND rnft_year = $agno ORDER BY rnft_id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){
          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {

          $total = (float)$value['rnft_total'];
          $team = (float)$value['rnft_team'];
          $semana = (int)$value['rnft_semana'];
          $mes = (int)$value['rnft_mes'];
          $year = (int)$value['rnft_year'];
          $notas = $value['rnft_notas'];
          $status = $value['rnft_status'];
          $id = $value['rnft_id'];

          $row = array(
              'mes' => $mes,
              'year' => $year,
              'semana' => $semana,
              'total' => $total,
              'team'=> $team,
              'status'=>$status,
              'notas' => $notas,
              'id'=>$id
            );
          $return_json[] = $row;

        }
        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
      }

      die(json_encode($respuesta));

    }

  }

  public function traer_datos_referralregistrovar(){
    if(isset($_POST['tipo'])){
      global $wpdb;

      date_default_timezone_set("America/Tijuana");

      $id = $_POST['id'];
      $tipo = $_POST['tipo'];

      if ($tipo == "editRegistro") {
        $tabla = $wpdb->prefix.'registros_var';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE rvar_id = $id ORDER BY rvar_id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){

          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {

          $total = (float)$value['rvar_cantidad'];
          $titulo = $value['rvar_titulo'];
          $mes = (int)$value['rvar_mes'];
          $year = (int)$value['rvar_year'];
          $notas = $value['rvar_notas'];
          $id = $value['rvar_id'];

          $row = array(
              'mes' => $mes,
              'year' => $year,
              'titulo' => $titulo,
              'total' => $total,
              'notas' => $notas,
              'id'=>$id
            );
          $return_json[] = $row;

        }
        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();

      }else{

        $tabla = $wpdb->prefix.'registros_var';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE rvar_id = $id ORDER BY rvar_id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){
          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {

          $total = (float)$value['rvar_cantidad'];
          $titulo = $value['rvar_titulo'];
          $mes = (int)$value['rvar_mes'];
          $year = (int)$value['rvar_year'];
          $notas = $value['rvar_notas'];
          $id = $value['rvar_id'];

          $row = array(
              'mes' => $mes,
              'year' => $year,
              'titulo' => $titulo,
              'total' => $total,
              'notas' => $notas,
              'id'=>$id
            );
          $return_json[] = $row;

        }
        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
      }

      die(json_encode($respuesta));

    }

  }

  public function traer_datos_referralretironft(){
    if(isset($_POST['tipo'])){
      global $wpdb;

      date_default_timezone_set("America/Tijuana");

      $id = $_POST['id'];
      $tipo = $_POST['tipo'];

      if ($tipo == "editRetiro") {
        $tabla = $wpdb->prefix.'retiros_nft';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE rtnft_id = $id ORDER BY rtnft_id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){

          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {

          $cantidad = (float)$value['rtnft_cantidad'];
          $valusd = (float)$value['rtnft_usdactual'];
          $notas = $value['rtnft_notas'];
          $id = $value['rtnft_id'];
          $fecharetiro = $value['rtnft_fecha_retiro'];

          $row = array(
              'cantidad'=>$cantidad,
              'notas' => $notas,
              'valusd' => $valusd,
              'id'=>$id,
              'fecharetiro' => $fecharetiro
            );
          $return_json[] = $row;

        }
        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();

      }else{
        $tabla = $wpdb->prefix.'retiros_nft';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE rtnft_id = $id ORDER BY rtnft_id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){

          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {

          $cantidad = (float)$value['rtnft_cantidad'];
          $notas = $value['rtnft_notas'];
          $id = $value['rtnft_id'];

          $row = array(
              'cantidad'=>$cantidad,
              'notas' => $notas,
              'id'=>$id
            );
          $return_json[] = $row;

        }
        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
      }

      die(json_encode($respuesta));

    }

  }

  public function confirmSolicitud(){
    if(isset($_POST['codigo'])){
      global $wpdb;

      date_default_timezone_set("America/Mexico_City");

      $codigo = $_POST['codigo'];
      $tipo = $_POST['tipo'];



      if ($tipo == 'deposito') {
        $tabla = $wpdb->prefix.'depositos';
        $operacion = $wpdb->get_results(" SELECT * FROM $tabla WHERE codigo = '$codigo' ORDER BY id DESC", ARRAY_A);

        if(count($operacion) == 0){
          $respuesta=array(
            'respuesta'=>'no-encontrado',
            'texto'=>'La URL de confirmación es inválida, favor de verificarla.',
            'error'=> 'SELECT * FROM $tabla WHERE codigo = $codigo ORDER BY id DESC'
          );
        }else{
          if($operacion[0]["status"] == 0 ){
            //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));

            $datos = [
            'status' => 1
            ];

            $formato = [
              '%d'
            ];

            $donde = [
              'codigo' => $codigo
            ];

            $donde_formato = [
              '%s'
            ];

            $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

              if($actualizar !== false){
                if( $actualizar != 0){
                  $fila = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $tabla WHERE codigo = %s", $codigo) );

                  $user = get_userdata( absint( $fila->usuario ) );
                  $usermail = $user->user_email;
                  $user_login = $user->user_login;
                  $wallet = get_user_meta( $user->ID, 'wallet', true);
                  $walletcode = get_user_meta( $user->ID, 'walletcode', true);

                  if ($user) {
                    $nombre = $user->first_name . ' ' .$user->last_name ;
                  }else{
                    $nombre = 'Usuario no encontrado';
                  }

                  $fecha_cuando = $fila->fecha_cuando;

                  if($fecha_cuando == 1){
                    $fecha = 'Día 1 del mes';
                  }else{
                    $fecha = 'Día 15 del mes';
                  }

                  $cantavi = number_format($fila->cantidad, 2);


                  $respuesta = array(
                    'cantidad'=>$fila->cantidad,
                    'tipo'=>'deposito',
                    'nombre'=>$nombre,
                    'fecha_cuando'=>$fecha,
                    'respuesta'=>'bien'
                  );

                  //ENVIAR CORREO AL ADMIN
                  $adminemail = "admin@theincproject.com";
                  wp_mail( $adminemail, 'Nuevo Deposito Solicitado', 'Se ha registrado un nuevo deposito por: '.$cantavi.' .Para el usuario con email: '.$usermail.' su nombre completo es: '.$nombre.' , y su nombre de usuario es: '.$user_login.' . <br><br> Tipo de wallet: '.$wallet.' <br> Wallet Address: '.$walletcode );

                }else{
                  $respuesta=array(
                    'respuesta'=>'error'
                  );
                }
              }else{
                $respuesta=array(
                  'respuesta'=>'error'
                );
              }
          }else{
            $respuesta = array(
              'texto'=>'La acción de confirmación para este depósito ya no esta disponible. Le sugerimos revisar en su historial de depósitos, el estatus del mismo.',
              'respuesta'=>'imposible'
            );
          }
        }


      }else if ($tipo == 'agrdeposito') {
        $tabla = $wpdb->prefix.'depositos_agr';
        $operacion = $wpdb->get_results(" SELECT * FROM $tabla WHERE dagr_codigo = '$codigo' ORDER BY dagr_id DESC", ARRAY_A);

        if(count($operacion) == 0){
          $respuesta=array(
            'respuesta'=>'no-encontrado',
            'texto'=>'La URL de confirmación es inválida, favor de verificarla.',
            'error'=> 'SELECT * FROM $tabla WHERE dagr_codigo = $codigo ORDER BY dagr_id DESC'
          );
        }else{
          if($operacion[0]["dagr_status"] == 0 ){
            //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));

            $datos = [
            'dagr_status' => 1
            ];

            $formato = [
              '%d'
            ];

            $donde = [
              'dagr_codigo' => $codigo
            ];

            $donde_formato = [
              '%s'
            ];

            $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

              if($actualizar !== false){
                if( $actualizar != 0){
                  $fila = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $tabla WHERE dagr_codigo = %s", $codigo) );

                  $user = get_userdata( absint( $fila->dagr_usuario ) );
                  $usermail = $user->user_email;
                  $user_login = $user->user_login;
                  $wallet = get_user_meta( $user->ID, 'wallet', true);
                  $walletcode = get_user_meta( $user->ID, 'walletcode', true);

                  if ($user) {
                    $nombre = $user->first_name . ' ' .$user->last_name ;
                  }else{
                    $nombre = 'Usuario no encontrado';
                  }

                  // $fecha_cuando = $fila->fecha_cuando;
                  //
                  // if($fecha_cuando == 1){
                  //   $fecha = 'Día 1 del mes';
                  // }else{
                  //   $fecha = 'Día 15 del mes';
                  // }
                  $fechaSeparada = explode("-", $fila->dagr_fecha_deposito );
                  $fechadep = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

                  $cantavi = number_format($fila->dagr_cantidad, 2);


                  $respuesta = array(
                    'cantidad'=>$fila->dagr_cantidad,
                    'tipo'=>'deposito',
                    'nombre'=>$nombre,
                    'fecha_cuando'=>$fechadep,
                    'respuesta'=>'bien'
                  );

                  //ENVIAR CORREO AL ADMIN
                  $adminemail = "admin@theincproject.com";
                  wp_mail( $adminemail, 'Nuevo Deposito Solicitado', 'Se ha registrado un nuevo deposito por: '.$cantavi.' .Para el usuario con email: '.$usermail.' su nombre completo es: '.$nombre.' , y su nombre de usuario es: '.$user_login.' . <br><br> Tipo de wallet: '.$wallet.' <br> Wallet Address: '.$walletcode );

                }else{
                  $respuesta=array(
                    'respuesta'=>'error'
                  );
                }
              }else{
                $respuesta=array(
                  'respuesta'=>'error'
                );
              }
          }else{
            $respuesta = array(
              'texto'=>'La acción de confirmación para este depósito ya no esta disponible. Le sugerimos revisar en su historial de depósitos, el estatus del mismo.',
              'respuesta'=>'imposible'
            );
          }
        }


      }else if ($tipo == 'agrretiro') {
        $tabla = $wpdb->prefix.'retiros_agr';
        $operacion = $wpdb->get_results(" SELECT * FROM $tabla WHERE ragr_codigo = '$codigo' ORDER BY ragr_id DESC", ARRAY_A);

        if(count($operacion) == 0){
          $respuesta=array(
            'respuesta'=>'no-encontrado',
            'texto'=>'La URL de confirmación es inválida, favor de verificarla.',
            'error'=> 'SELECT * FROM $tabla WHERE ragr_codigo = $codigo ORDER BY ragr_id DESC'
          );
        }else{
          if($operacion[0]["ragr_status"] == 0 ){
            //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));

            $datos = [
            'ragr_status' => 1
            ];

            $formato = [
              '%d'
            ];

            $donde = [
              'ragr_codigo' => $codigo
            ];

            $donde_formato = [
              '%s'
            ];

            $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

              if($actualizar !== false){
                if( $actualizar != 0){
                  $fila = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $tabla WHERE ragr_codigo = %s", $codigo) );

                  $user = get_userdata( absint( $fila->ragr_usuario ) );
                  $usermail = $user->user_email;
                  $user_login = $user->user_login;
                  $wallet = get_user_meta( $user->ID, 'wallet', true);
                  $walletcode = get_user_meta( $user->ID, 'walletcode', true);

                  if ($user) {
                    $nombre = $user->first_name . ' ' .$user->last_name ;
                  }else{
                    $nombre = 'Usuario no encontrado';
                  }

                  // $fecha_cuando = $fila->ragr_fecha_cuando;
                  //
                  // if($fecha_cuando == 1){
                  //   $fecha = 'Día 1 del mes';
                  // }else{
                  //   $fecha = 'Día 15 del mes';
                  // }
                  $fechaSeparada = explode("-", $fila->ragr_fecha_retiro );
                  $fecharet = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

                  $cantavi = number_format($fila->ragr_cantidad, 2);


                  $respuesta = array(
                    'cantidad'=>$fila->ragr_cantidad,
                    'tipo'=>'deposito',
                    'nombre'=>$nombre,
                    'fecha_cuando'=>$fecharet,
                    'respuesta'=>'bien'
                  );

                  //ENVIAR CORREO AL ADMIN
                  $adminemail = "admin@theincproject.com";
                  wp_mail( $adminemail, 'Nuevo Retiro Solicitado', 'Se ha registrado un nuevo retiro por: '.$cantavi.' .Para el usuario con email: '.$usermail.' su nombre completo es: '.$nombre.' , y su nombre de usuario es: '.$user_login.' . <br><br> Tipo de wallet: '.$wallet.' <br> Wallet Address: '.$walletcode );

                }else{
                  $respuesta=array(
                    'respuesta'=>'error'
                  );
                }
              }else{
                $respuesta=array(
                  'respuesta'=>'error'
                );
              }
          }else{
            $respuesta = array(
              'texto'=>'La acción de confirmación para este retiro ya no esta disponible. Le sugerimos revisar en su historial de retiros, el estatus del mismo.',
              'respuesta'=>'imposible'
            );
          }
        }


      } else if ($tipo == 'condeposito') {
        $tabla = $wpdb->prefix.'depositos_con';
        $operacion = $wpdb->get_results(" SELECT * FROM $tabla WHERE dcon_codigo = '$codigo' ORDER BY dcon_id DESC", ARRAY_A);

        if(count($operacion) == 0){
          $respuesta=array(
            'respuesta'=>'no-encontrado',
            'texto'=>'La URL de confirmación es inválida, favor de verificarla.',
            'error'=> 'SELECT * FROM $tabla WHERE dcon_codigo = $codigo ORDER BY dcon_id DESC'
          );
        }else{
          if($operacion[0]["dcon_status"] == 0 ){
            //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));

            $datos = [
            'dcon_status' => 1
            ];

            $formato = [
              '%d'
            ];

            $donde = [
              'dcon_codigo' => $codigo
            ];

            $donde_formato = [
              '%s'
            ];

            $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

              if($actualizar !== false){
                if( $actualizar != 0){
                  $fila = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $tabla WHERE dcon_codigo = %s", $codigo) );

                  $user = get_userdata( absint( $fila->dcon_usuario ) );
                  $usermail = $user->user_email;
                  $user_login = $user->user_login;
                  $wallet = get_user_meta( $user->ID, 'wallet', true);
                  $walletcode = get_user_meta( $user->ID, 'walletcode', true);

                  if ($user) {
                    $nombre = $user->first_name . ' ' .$user->last_name ;
                  }else{
                    $nombre = 'Usuario no encontrado';
                  }

                  // $fecha_cuando = $fila->fecha_cuando;
                  //
                  // if($fecha_cuando == 1){
                  //   $fecha = 'Día 1 del mes';
                  // }else{
                  //   $fecha = 'Día 15 del mes';
                  // }
                  $fechaSeparada = explode("-", $fila->dcon_fecha_deposito );
                  $fechadep = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

                  $cantavi = number_format($fila->dcon_cantidad, 2);


                  $respuesta = array(
                    'cantidad'=>$fila->dcon_cantidad,
                    'tipo'=>'deposito',
                    'nombre'=>$nombre,
                    'fecha_cuando'=>$fechadep,
                    'respuesta'=>'bien'
                  );

                  //ENVIAR CORREO AL ADMIN
                  $adminemail = "admin@theincproject.com";
                  wp_mail( $adminemail, 'Nuevo Deposito Mod Conservador Solicitado', 'Se ha registrado un nuevo deposito en modulo conservador por: '.$cantavi.' .Para el usuario con email: '.$usermail.' su nombre completo es: '.$nombre.' , y su nombre de usuario es: '.$user_login.' . <br><br> Tipo de wallet: '.$wallet.' <br> Wallet Address: '.$walletcode );

                }else{
                  $respuesta=array(
                    'respuesta'=>'error'
                  );
                }
              }else{
                $respuesta=array(
                  'respuesta'=>'error'
                );
              }
          }else{
            $respuesta = array(
              'texto'=>'La acción de confirmación para este depósito ya no esta disponible. Le sugerimos revisar en su historial de depósitos, el estatus del mismo.',
              'respuesta'=>'imposible'
            );
          }
        }


      }else if ($tipo == 'conretiro') {
        $tabla = $wpdb->prefix.'retiros_con';
        $operacion = $wpdb->get_results(" SELECT * FROM $tabla WHERE rcon_codigo = '$codigo' ORDER BY rcon_id DESC", ARRAY_A);

        if(count($operacion) == 0){
          $respuesta=array(
            'respuesta'=>'no-encontrado',
            'texto'=>'La URL de confirmación es inválida, favor de verificarla.',
            'error'=> 'SELECT * FROM $tabla WHERE rcon_codigo = $codigo ORDER BY rcon_id DESC'
          );
        }else{
          if($operacion[0]["rcon_status"] == 0 ){
            //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));

            $datos = [
            'rcon_status' => 1
            ];

            $formato = [
              '%d'
            ];

            $donde = [
              'rcon_codigo' => $codigo
            ];

            $donde_formato = [
              '%s'
            ];

            $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

              if($actualizar !== false){
                if( $actualizar != 0){
                  $fila = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $tabla WHERE rcon_codigo = %s", $codigo) );

                  $user = get_userdata( absint( $fila->rcon_usuario ) );
                  $usermail = $user->user_email;
                  $user_login = $user->user_login;
                  $wallet = get_user_meta( $user->ID, 'wallet', true);
                  $walletcode = get_user_meta( $user->ID, 'walletcode', true);

                  if ($user) {
                    $nombre = $user->first_name . ' ' .$user->last_name ;
                  }else{
                    $nombre = 'Usuario no encontrado';
                  }

                  // $fecha_cuando = $fila->ragr_fecha_cuando;
                  //
                  // if($fecha_cuando == 1){
                  //   $fecha = 'Día 1 del mes';
                  // }else{
                  //   $fecha = 'Día 15 del mes';
                  // }
                  $fechaSeparada = explode("-", $fila->rcon_fecha_retiro );
                  $fecharet = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

                  $cantavi = number_format($fila->rcon_cantidad, 2);


                  $respuesta = array(
                    'cantidad'=>$fila->rcon_cantidad,
                    'tipo'=>'deposito',
                    'nombre'=>$nombre,
                    'fecha_cuando'=>$fecharet,
                    'respuesta'=>'bien'
                  );

                  //ENVIAR CORREO AL ADMIN
                  $adminemail = "admin@theincproject.com";
                  wp_mail( $adminemail, 'Nuevo Retiro Modulo Conservador Solicitado', 'Se ha registrado un nuevo retiro en Modulo Conservador por: '.$cantavi.' .Para el usuario con email: '.$usermail.' su nombre completo es: '.$nombre.' , y su nombre de usuario es: '.$user_login.' . <br><br> Tipo de wallet: '.$wallet.' <br> Wallet Address: '.$walletcode );

                }else{
                  $respuesta=array(
                    'respuesta'=>'error'
                  );
                }
              }else{
                $respuesta=array(
                  'respuesta'=>'error'
                );
              }
          }else{
            $respuesta = array(
              'texto'=>'La acción de confirmación para este retiro ya no esta disponible. Le sugerimos revisar en su historial de retiros, el estatus del mismo.',
              'respuesta'=>'imposible'
            );
          }
        }


      }else {
        $tabla = $wpdb->prefix.'retiros';
        $operacion = $wpdb->get_results(" SELECT * FROM $tabla WHERE codigo = '$codigo' ORDER BY id DESC", ARRAY_A);

        if(count($operacion) == 0){
          $respuesta=array(
            'respuesta'=>'no-encontrado',
            'texto'=>'La URL de confirmación es inválida, favor de verificarla.'
          );
        }else{
          if($operacion[0]["status"] == 0 ){
            $datos = [
            'status' => 1
            ];

            $formato = [
              '%d'
            ];

            $donde = [
              'codigo' => $codigo
            ];

            $donde_formato = [
              '%s'
            ];

            $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

              if($actualizar !== false){
                if( $actualizar != 0){
                  $filar = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $tabla WHERE codigo = %s", $codigo) );

                  $user = get_userdata( absint( $filar[0]->usuario ) );
                  $usermail = $user->user_email;
                  $user_login = $user->user_login;
                  $wallet = get_user_meta( $user->ID, 'wallet', true);
                  $walletcode = get_user_meta( $user->ID, 'walletcode', true);

                  if ($user) {
                    $nombre = $user->first_name . ' ' .$user->last_name ;
                  }else{
                    $nombre = 'Usuario no encontrado';
                  }

                  $urgente = $filar[0]->urgente;
                  $fecha_cuando = $filar[0]->fecha_cuando;

                  if($urgente == 1){
                    $fecha = 'Urgente (Inmediato)';
                  }else{
                    if($fecha_cuando == 1){
                      $fecha = 'Día 15 del mes';
                    }else{
                      $fecha = 'Finales del mes';
                    }
                  }

                  $cantavi = number_format($fila->cantidad, 2);


                  $respuesta = array(
                    'cantidad'=>$filar[0]->cantidad,
                    'tipo'=>'retiro',
                    'nombre'=>$nombre,
                    'fecha_cuando'=>$fecha,
                    'urgente'=>$urgente,
                    'respuesta'=>'bien'
                  );

                  //ENVIAR CORREO AL ADMIN
                  $adminemail = "admin@theincproject.com";
                  wp_mail( $adminemail, 'Nuevo Retiro Solicitado', 'Se ha registrado un nuevo retiro por: '.$cantavi.' .Para el usuario con email: '.$usermail.' su nombre completo es: '.$nombre.' , y su nombre de usuario es: '.$user_login.' . <br><br> Tipo de wallet: '.$wallet.' <br> Wallet Address: '.$walletcode );
                }else{
                  $respuesta=array(
                    'respuesta'=>'error'
                  );
                }
              }else{
                $respuesta=array(
                  'respuesta'=>'error'
                );
              }
          }else{
            $respuesta = array(
              'texto'=>'La acción de confirmación para este retiro ya no esta disponible. Le sugerimos revisar en su historial de retiros, el estatus del mismo.',
              'respuesta'=>'imposible'
            );
          }
        }

      }

    }

    die(json_encode($respuesta));
  }

  // COMIENZAN LAS TABLAS

  public function mostrarTablaHistoRet(){
    global $wpdb;
    $user = wp_get_current_user();
    $userid = $user->ID;
    $retiros = $wpdb->prefix . 'retiros';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $retiros WHERE usuario = $userid ORDER BY id DESC", ARRAY_A);

    $return_json = array();

    if(count($registros) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

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
      $status = $value["status"];
      if ($status == 0) {
        $statusc = "Generada";
        $acciones = "<button class='btn-finalizar-inac' type='button' name='button'>Finalizar</button>";
      }else if($status == 1){
        $statusc = "Confirmada";
        $acciones = "<input alt='#TB_inline?width=400&inlineId=modal-finret' title='Finalizar un retiro' data-retiro='".$value['id']."' class='thickbox button button-primary button-large btn-finalizar' type='button' value='Finalizar' />";
      }else if($status == 3){
        $statusc = "Cancelada";
        $acciones = "";
      }else{
        $statusc = "Autorizada";
        $acciones = "<button class='btn-finalizar-inac' type='button' name='button'>Finalizar</button>";
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

      if(!$value["cantidadfin"]){
        $cantidadreal = "";
      }else{
        $cantidadreal = "$".number_format($value["cantidadfin"], 2);
      }

      $cantidad  = "$".number_format($value["cantidad"], 2);

      $fecha = substr($value["fecha_retiro"], 0, 10);

      $row = array(
          'id' => ($key+1),
          'cantidad' => $cantidad,
          'cantidadfin' => $cantidadreal,
          'esurgente' => $esurgente,
          'fecharet' => $fecharet,
          'status' => $statusc,
          'idmov_ind' => $idmov_ind,
          'fecha' => $fecha,
          'fechafin' => $fechafin
        );
      $return_json[] = $row;

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }

  public function mostrarTablaHistoDep(){
    global $wpdb;
    $user = wp_get_current_user();
    $userid = $user->ID;
    $depositos = $wpdb->prefix . 'depositos';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $depositos WHERE usuario = $userid ORDER BY id DESC", ARRAY_A);

    $return_json = array();

    if(count($registros) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

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

      if(!$value["cantidad_real"]){
        $cantidadreal = "";
      }else{
        $cantidadreal = "$".number_format($value["cantidad_real"], 2);
      }

      $cantidad  = "$".number_format($value["cantidad"], 2);

      $fecha = substr($value["fecha_deposito"], 0, 10);
      $interes = $value["interes"]."%";

      $row = array(
          'id' => ($key+1),
          'cantidad' => $cantidad,
          'cantidadfin' => $cantidadreal,
          'fechadep' => $fechadep,
          'status' => $statusc,
          'idmov_ind' => $idmov_ind,
          'idmov_gral' => $idmov_gral,
          'fecha' => $fecha,
          'fechafin' => $fechafin
        );
      $return_json[] = $row;

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }

  public function mostrarTablaAdminRet(){
    global $wpdb;
    $retiros = $wpdb->prefix . 'retiros';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $retiros ORDER BY id DESC", ARRAY_A);

    $return_json = array();

    if(count($registros) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

    foreach ($registros as $key => $value) {

      $user = get_userdata( absint( $value["usuario"] ) );
      $wallet = get_user_meta( $user->ID, 'wallet', true);
      $walletcode = get_user_meta( $user->ID, 'walletcode', true);
      $email = $user->user_email;
      $pais = get_user_meta( $user->ID, 'pais', true);

      if ($user) {
        $nombre = $user->first_name . ' ' .$user->last_name ;
      }else{
        $nombre = 'Usuario no encontrado';
      }
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
        $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-finret' data-retiro='".$value['id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-cancret' data-retiro='".$value['id']."'>Cancelar</button>";
      }else if($status == 1){
        $statusc = "Confirmada";
        $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-finret' data-retiro='".$value['id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-cancret' data-retiro='".$value['id']."'>Cancelar</button>";
      }else if($status == 3){
        $statusc = "Cancelada";
        $acciones = "<button class='btn-finalizar-inac'>Editar</button>";
      }else{
        $statusc = "Autorizada";
        $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-editret' data-retiro='".$value['id']."'>Editar</button>";
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


      $notas = "<button aria-label='".$value["notas"]."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";


      $fecha = substr($value["fecha_retiro"], 0, 10);

      $row = array(
          'id' => ($key+1),
          'nombre' => $nombre,
          'cantidad' => $cantidad,
          'cantidadfin' => $cantidadreal,
          'notas' => $notas,
          'esurgente' => $esurgente,
          'fecharet' => $fecharet,
          'status' => $statusc,
          'idmov_ind' => $idmov_ind,
          'fecha' => $fecha,
          'fechafin' => $fechafin,
          'wallet' => $wallet,
          'walletcode' => $walletcode,
          'acciones' => $acciones
        );
      $return_json[] = $row;

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }


  public function mostrarTablaAdminDep(){
    global $wpdb;
    $depositos = $wpdb->prefix . 'depositos';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $depositos ORDER BY id DESC", ARRAY_A);

    $return_json = array();

    if(count($registros) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

    foreach ($registros as $key => $value) {

      $user = get_userdata( absint( $value["usuario"] ) );
      $wallet = get_user_meta( $user->ID, 'wallet', true);
      $walletcode = get_user_meta( $user->ID, 'walletcode', true);
      $email = $user->user_email;
      $pais = get_user_meta( $user->ID, 'pais', true);

      if ($user) {
        $nombre = $user->first_name . ' ' .$user->last_name ;
      }else{
        $nombre = 'Usuario no encontrado';
      }

      $fecha_cuando = $value["fecha_cuando"];
      if($fecha_cuando == 1){
        $fechadep = 'Día 1 del mes';
      }else{
        $fechadep = 'Día 15 del mes';
      }

      $status = $value["status"];
      if ($status == 0) {
        $statusc = "Generada";
        $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-findep' data-deposito='".$value['id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-cancdep' data-deposito='".$value['id']."'>Cancelar</button>";
      }else if($status == 1){
        $statusc = "Confirmada";
        $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-findep' data-deposito='".$value['id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-cancdep' data-deposito='".$value['id']."'>Cancelar</button>";
      }else if($status == 3){
        $statusc = "Cancelada";
        $acciones = "<button class='btn-finalizar-inac'>Editar</button>";
      }else{
        $statusc = "Autorizada";
        $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-editdep' data-deposito='".$value['id']."'>Editar</button>";
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
      $interes = $value["interes"]."%";

      $notas = "<button aria-label='".$value["notas"]."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";

      $row = array(
          'id' => ($key+1),
          'nombre' => $nombre,
          'cantidad' => $cantidad,
          'cantidadfin' => $cantidadreal,
          'notas' => $notas,
          'fechadep' => $fechadep,
          'status' => $statusc,
          'idmov_ind' => $idmov_ind,
          'idmov_gral' => $idmov_gral,
          'fecha' => $fecha,
          'fechafin' => $fechafin,
          'wallet' => $wallet,
          'walletcode' => $walletcode,
          'acciones' => $acciones
        );
      $return_json[] = $row;

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }

  public function mostrarTablaAdminDepMas(){
    global $wpdb;
    $depositos = $wpdb->prefix . 'depositos_master';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $depositos ORDER BY id DESC", ARRAY_A);

    $return_json = array();

    if(count($registros) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

    foreach ($registros as $key => $value) {

      $status = $value["status"];
      if($status == 0){
        $statusc = "Cancelado";
        $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-edepmaster' data-deposito='".$value['id']."'>Editar</button>";
      }else{
        $statusc = "Registrado";
        $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-edepmaster' data-deposito='".$value['id']."'>Editar</button><button aria-label='Cancelar' data-microtip-position='top' data-microtip-size='small' class=' btn-elimdepmas' data-deposito='".$value['id']."' role='tooltip'><span class='material-icons'>close</span></button>";
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

      if($value["cantidad_real"] == 0){
        $cantidadreal = "";
      }else{
        $cantidadreal = "$".number_format($value["cantidad_real"], 2);
      }

      $fecha = substr($value["fecha_deposito"], 0, 10);

      $cantidad  = "$".number_format($value["cantidad"], 2);

      $notas = "<button aria-label='".$value["notas"]."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";

      $row = array(
          'id' => ($key+1),
          'cantidad' => $cantidad,
          'cantidadfin' => $cantidadreal,
          'fechadep' => $fecha,
          'status' => $statusc,
          'idmov_ind' => $idmov_ind,
          'idmov_gral' => $idmov_gral,
          'notas' => $notas,
          'acciones' => $acciones
        );
      $return_json[] = $row;

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }

  public function mostrarTablaAdminRetMas(){
    global $wpdb;
    $depositos = $wpdb->prefix . 'retiros_master';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $depositos ORDER BY id DESC", ARRAY_A);

    $return_json = array();

    if(count($registros) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

    foreach ($registros as $key => $value) {

      $status = $value["status"];
      if($status == 0){
        $statusc = "Cancelado";
        $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-eretmaster' data-retiro='".$value['id']."'>Editar</button>";
      }else{
        $statusc = "Registrado";
        $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-eretmaster' data-retiro='".$value['id']."'>Editar</button><button aria-label='Cancelar' data-microtip-position='top' data-microtip-size='small' class=' btn-elimretmas' data-retiro='".$value['id']."' role='tooltip'><span class='material-icons'>close</span></button>";
      }

      if(!$value["idmov_ind"]){
        $idmov_ind = "";
      }else{
        $idmov_ind = $value["idmov_ind"];
      }

      if($value["cantidad_real"] == 0){
        $cantidadreal = "";
      }else{
        $cantidadreal = "$".number_format($value["cantidad_real"], 2);
      }

      $fecha = substr($value["fecha_retiro"], 0, 10);

      $cantidad  = "$".number_format($value["cantidad"], 2);

      $notas = "<button aria-label='".$value["notas"]."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";

      $row = array(
          'id' => ($key+1),
          'cantidad' => $cantidad,
          'cantidadfin' => $cantidadreal,
          'fecharet' => $fecha,
          'status' => $statusc,
          'idmov_ind' => $idmov_ind,
          'notas' => $notas,
          'acciones' => $acciones
        );
      $return_json[] = $row;

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }

  public function mostrarTablaAdminUsers(){
    global $wpdb;
    $usuarios = $wpdb->prefix . 'users';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $usuarios ORDER BY id DESC", ARRAY_A);

    $return_json = array();

    if(count($registros) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

    foreach ($registros as $key => $value) {
      $userid = absint( $value["ID"] );
      $user = get_userdata( absint( $value["ID"] ) );
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
            $mesesinv = $wpdb->get_results("SELECT mes, interes FROM $tabla3 WHERE usuario = $userid AND status = 1 ORDER BY id", ARRAY_A);
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

                /*echo $meshoy;
                echo "<br>";
                echo $mes . "mes";
                echo "<br>";*/

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
                }


                if ($mes == 12) {
                  $mes = 1;
                  $agno++;
                }else{
                  $mes++;
                }

              }


            }

            if ($user) {
              $nombre = $user->first_name . ' ' .$user->last_name ;
            }else{
              $nombre = 'Usuario no encontrado';
            }



            if(!$wallet){
              $walletc = "--";
            }else {
              if($wallet == "usdt"){
                $walletc = "USDT";
              }else{
                $walletc = ucfirst($wallet);
              }
            }

            if(!$walletcode){
              $walletcodec = "--";
            }else {
              $walletcodec = $walletcode;
            }

            if(!$pais){
              $paisc = "--";
            }else {
              $paisc = $pais;
            }


            if ($activo == '1') {
              $acceso = "Sí";
            }else{
              $acceso = "No";
            }

            $acciones = "<button class='button button-primary btn-proyeccion' data-usuario='".$userid."'>Ver</button>";



            $row = array(
                'id' => ($key+1),
                'userid' => $userid,
                'nombre' => $nombre,
                'email' => $email,
                'acceso' => $acceso,
                'status' => $statusc,
                'acciones' => $acciones,
                'wallet' => $walletc,
                'walletcode' => $walletcodec,
                'pais' => $paisc,
                'inicial' => "$".$ttotaldep,
                'intacu' => "$".$tintacu
              );
            $return_json[] = $row;
          }
        }

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }

  public function mostrarTablaAdmConMaster(){
    global $wpdb;
    $balances = $wpdb->prefix . 'controlmaster';
    $ruta = get_site_url();
    $registros = $wpdb->get_results("SELECT * FROM $balances WHERE status = 1 ORDER BY agno, mes   ", ARRAY_A);
    $x = 1;
    $length = count($registros);
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );

    $return_json = array();

    if(count($registros) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

    $tabla = $wpdb->prefix.'depositos_master';
    $tabla1 = $wpdb->prefix.'retiros_master';

    $profitpasado = 0;

    foreach ($registros as $key => $value) {

      $mes = (int)$value['mes'];
      $tmes = $mesesNombre[$mes];
      $agno = (int)$value['agno'];
      $start_balance = (float)$value['start_balance'];
      $balance_bef_com = (float)$value['balance_bef_com'];
      $com_broker = (float)$value['com_broker'];
      $com_trader = (float)$value['com_trader'];

      $total_cuentas = (float)$value['total_cuentas'];

      $totaldep = $wpdb->get_results("SELECT month(fecha_deposito) AS mes, year(fecha_deposito) AS agno, ROUND(SUM(cantidad_real), 2) AS totaldep FROM $tabla WHERE month(fecha_deposito) = $mes AND year(fecha_deposito) = $agno AND status = 1 ", ARRAY_A);
      $ttotaldep = number_format($totaldep[0]['totaldep'], 2, '.', ',');
      $ttdp = '<span class="verde btn-ver-depmas" data-mes='.$mes.' data-agno='.$agno.'>+ $'.$ttotaldep.'</span>';

      $totalret = $wpdb->get_results("SELECT month(fecha_retiro) AS mes, year(fecha_retiro) AS agno, ROUND(SUM(cantidad_real), 2) AS totalret FROM $tabla1 WHERE month(fecha_retiro) = $mes AND year(fecha_retiro) = $agno AND status = 1 ", ARRAY_A);
      $ttotalret = number_format($totalret[0]['totalret'], 2, '.', ',');
      $ttrt = '<span class="rojo btn-ver-retmas" data-mes='.$mes.' data-agno='.$agno.'>- $'.$ttotalret.'</span>';

      $balance_final = (float)$value['balance_final'];

      $tstart_balance = number_format($start_balance, 2, '.', ',');
      $tbalance_bef_com = number_format($balance_bef_com, 2, '.', ',');
      $tcom_broker = number_format($com_broker, 2, '.', ',');
      $tcom_trader = number_format($com_trader, 2, '.', ',');
      $tbalance_final = number_format($balance_final, 2, '.', ',');
      $ttotal_cuentas = number_format($total_cuentas, 2, '.', ',');

      $notas = "<button aria-label='".$value['notas']."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";
      $acciones = "<button aria-label='".$value['notas']."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><input alt='#TB_inline?width=400&inlineId=modal-editbal' title='Editar balance' data-balance='".$value['id']."'  class='thickbox button button-primary button-large btn-editar' type='button' value='Editar' /><button aria-label='Eliminar' data-microtip-position='top' data-microtip-size='small' class=' btn-elimbal' data-balance='".$value['id']."' role='tooltip'><span class='material-icons'>close</span></button>";

      if($key == 0){
        $tprofit = "--";
        $profitanterior = 0;
      }else{
        $profitanterior = $profitpasado;
        $lastbal_final = (float)$registros[$key-1]["balance_final"];
        $profit = round(($balance_final * 100) / $lastbal_final, 2);
        $diff = round($profit - 100, 2);
        if($diff >= 0){
          $tprofit = "<span class='diffpos'>".$diff."%</span>";
        }else{
          $tprofit = "<span class='diffmin'>".$diff."%</span>";
        }

      }

      $ganancia = number_format(($balance_final - $total_cuentas), 2, '.', ',');
      $gananciames = number_format((($balance_final - $total_cuentas) - $profitanterior), 2, '.', ',');


      $row = array(
          'id' => ($key+1),
          'mes'=> $tmes,
          'agno'=> $agno,
          'startbal'=> "$".$tstart_balance,
          'balbefcom'=> "$".$tbalance_bef_com,
          'combroker'=> "$".$tcom_broker,
          'comtrader'=> "$".$tcom_trader,
          'depositos'=> $ttdp,
          'retiros'=> $ttrt,
          'balfinal'=> "$".$tbalance_final,
          'totalcuentas'=> "$".$ttotal_cuentas,
          'notas'=> $notas,
          'profit'=> "$".$ganancia,
          'profitmes'=> "$".$gananciames,
          'acciones'=> $acciones
        );
      $return_json[] = $row;

      $x++;
      $profitpasado = ($balance_final - $total_cuentas);

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }

  public function mostrarTablaRepHistGral(){
    $calculos = new CRC_Calculo();
    $utilidadref = $calculos->totalcuentas_mensual_hoy();
    $utilidadxinvit = $calculos->totalcuentas_utilref_cierre();
    global $wpdb;
    $reghistorico = $wpdb->prefix . 'registro_historico';
    $depositos = $wpdb->prefix . 'depositos';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $reghistorico ORDER BY id ASC", ARRAY_A);

    $return_json = array();

    if (!$utilidadref) {
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }else{
      if(count($utilidadref) == 0 ){

        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
        return;
      }else{
        $primerdepogral = $wpdb->get_results("SELECT month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidad_real, fecha_cuando, fecha_termino FROM $depositos WHERE status = 2 ORDER BY fecha_termino LIMIT 1 ", ARRAY_A);

        $mesuno = $primerdepogral[0]["mes"];
        $agnouno = (int) $primerdepogral[0]["agno"];
        $agno = $agnouno;
        $fechaini = date($primerdepogral[0]["agno"]."-".$primerdepogral[0]["mes"]."-01");
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
        $mesescambio = $meseshanpasado - 12;

        if ($meseshanpasado > 12) {
          $cont = 13;
        }else {
          $cont = 1;
        }
      }

      if(count($utilidadxinvit) == 0){

      }

      foreach ($utilidadref as $key => $value) {


        $tsubtotalinv = number_format($value["total"], 2, '.', ',');
        $tutilgenerada = number_format($value["utilacumulada"], 2, '.', ',');
        $tutilref = 0;
        $utiltot = $value["utilacumulada"];
        $external = 0;
        $texternal = "-.-";
        $notas = "";
        $totalinv = (float)$value["total"];

        // Aqui vamos a revisar si hay utilidades para ese mes en el que vamos de los mese generales
        foreach ($utilidadxinvit as $llave => $valor) {
          if ($value["mes"] == $valor["mes"] && $value["year"] == $valor["year"] ) {

            $utilref = number_format($valor["total"], 2, '.', ',');
            $tutilref = $utilref;

            $utiltot = $utiltot + $valor["total"];

            $totalinv = $totalinv + $valor["total"];

          }
        }

        // Vamos a ver si hay un registro external para ese mes
        if(count($registros) != 0){
            // vemos si hay uno correspondientes a ese mes
            foreach ($registros as $clave => $reghist) {
              if ($value["mes"] == $reghist["mes"] && $value["year"] == $reghist["year"] ) {
                $external = $reghist["external"];
                if($reghist["notas"]){
                  $notas = $reghist["notas"];
                }
                $texternal = "$".number_format($reghist["external"], 2, '.', ',');
              }
            }
        }

        $tutiltot = number_format($utiltot, 2, '.', ',');
        $tutilfinal = "-.-";
        $ttotalinv = number_format($totalinv, 2, '.', ',');

        //Si no hay utilidad external la util final no se va a calcular
        if ($external != 0) {
          $utilfinal = $external - $utiltot;
          if($utilfinal >= 0){
            $tutilfinal = "<span class='diffpos'>$".number_format($utilfinal, 2, '.', ',')."</span>";
          }else{
            $tutilfinal = "<span class='diffmin'>$".number_format($utilfinal, 2, '.', ',')."</span>";
          }

        }

        $acciones = "<button aria-label='".$notas."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><input alt='#TB_inline?width=400&inlineId=modal-editreg' title='Editar balance' data-mes='".$value['mes']."' data-year='".$value['year']."' class='thickbox button button-primary button-large btn-editar' type='button' value='Editar' />";

        $row = array(
            'id' => $cont ,
            'agno'=> $value["year"],
            'mes'=> $value["tmes"],
            'utilacum' => "$".$tutilgenerada,
            'subtotalinv'=> "$".$tsubtotalinv,
            'utilref'=> "$".$tutilref,
            'utiltot'=> "$".$tutiltot,
            'external'=> $texternal,
            'utilfinal'=> $tutilfinal,
            'totalinv'=> "$".$ttotalinv,
            'acciones'=> $acciones
          );
        $return_json[] = $row;

        if ($meseshanpasado > 12) {
          if(($key+1) == $mesescambio){
            $cont = 1;
          }else{
            $cont++;
          }
        }else {
          $cont++;
        }



      }
    }


    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }

  public function mostrarTablaRepMensual(){
    if(isset($_POST['mes'])){
      $calculos = new CRC_Calculo();
      $utilidadref = $calculos->totalcuentas_mensual_hoy_detalle();
      $utilidadxinvit = $calculos->totalcuentas_utilref_cierre_detalle();
      global $wpdb;
      $reghistorico = $wpdb->prefix . 'registro_historico';
      $depositos = $wpdb->prefix . 'depositos';
      $month = (int) $_POST['mes'];
      $year = (int) $_POST['agno'];
      $ruta = get_site_url();

      $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
      '8' => 'Agosto',
      '9' => 'Septiembre',
      '10' => 'Octubre',
      '11' => 'Noviembre',
      '12' => 'Diciembre' );

      $tabla = $wpdb->prefix.'depositos_master';
      $tabla1 = $wpdb->prefix.'retiros_master';
      // $imes = (int)$mes;
      // $iagno = (int)$agno;
      $totaldep = $wpdb->get_results("SELECT month(fecha_deposito) AS mes, year(fecha_deposito) AS agno, ROUND(SUM(cantidad_real), 2) AS totaldep FROM $tabla WHERE month(fecha_deposito) = $month AND year(fecha_deposito) = $year AND status = 1 ", ARRAY_A);
      $totaldepdatos = $wpdb->get_results("SELECT month(fecha_deposito) AS mes, year(fecha_deposito) AS agno, cantidad_real AS cantidad FROM $tabla WHERE month(fecha_deposito) = $month  AND year(fecha_deposito) = $year ", ARRAY_A);
      $ttotaldep = number_format($totaldep[0]['totaldep'], 2, '.', ',');
      if(!$totaldep[0]['totaldep']){
        $itotaldep = 0.0;
        $tdepmas = "0.00";
      }else {
        $itotaldep = (float)$totaldep[0]['totaldep'];
        $tdepmas = number_format($itotaldep, 2, '.', ',');
      }
      // $ttdp = '<span class="verde btn-ver-depmas" data-mes='.$mes.' data-agno='.$agno.'>+ $'.$ttotaldep.'</span>';

      $totalret = $wpdb->get_results("SELECT month(fecha_retiro) AS mes, year(fecha_retiro) AS agno, ROUND(SUM(cantidad_real), 2) AS totalret FROM $tabla1 WHERE month(fecha_retiro) = $month AND year(fecha_retiro) = $year AND status = 1 ", ARRAY_A);
      $totalretdatos = $wpdb->get_results("SELECT month(fecha_retiro) AS mes, year(fecha_retiro) AS agno, cantidad_real AS cantidad FROM $tabla1 WHERE month(fecha_retiro) = $month AND year(fecha_retiro) = $year  ", ARRAY_A);
      $ttotalret = number_format($totalret[0]['totalret'], 2, '.', ',');
      if(!$totalret[0]['totalret']){
        $itotalret = 0.0;
        $tretmas = "0.00";
      }else {
        $itotalret = (float)$totalret[0]['totalret'];
        $tretmas = number_format($itotalret, 2, '.', ',');
      }
      // $registros = $wpdb->get_results(" SELECT * FROM $reghistorico ORDER BY id ASC", ARRAY_A);

      $gral_json = array(
        'depmaster' => "$".$tdepmas,
        'retmaster' => "$".$tretmas,
      );

      $finaltotalmes = 0;

      $return_json = array();

      if (!$utilidadref) {
        echo json_encode(array('data' => $return_json));
        wp_die();
        return;
      }else{
        if(count($utilidadref) == 0 ){
          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }else{
          // Recorremos el array de los inverisonistas y vemos si tiene utilidades por mes
          foreach ($utilidadref as $key => $inversor) {
            if (count($inversor["totalxuserxmes"]) == 0) {

            }else{
              // Si tiene utilidades por mes recorremos los meses y comprobamos que sea igual en el que estoy checando
              foreach ($inversor["totalxuserxmes"] as $llave => $mesesdelinv) {
                if ($mesesdelinv["mes"] == $month && $mesesdelinv["year"] == $year) {

                  $totalref = 0;
                  // Ahora vamos a revisar el array de usuarios invitados por inversor si no hay nadie pues utilref sera 0, si no buscamos coincidencias de id
                  if(!$utilidadxinvit){

                  }else{
                    foreach ($utilidadxinvit as $clave => $investor) {
                      // Vemos si el mismo user y revisamos que tenga invitados
                      if ($investor["id"] == $inversor["id"]) {
                        if (count($investor["totalxuserrefxmes"]) == 0) {

                        }else{
                          //Si tiene invitados entonces recorremos el array de invitados
                          foreach ($investor["totalxuserrefxmes"] as $code => $invitado) {

                            // Revisamos si los invitados tienen alguna generacion de utilidades para el user
                            if (count($invitado["utilidadxmes"]) == 0) {

                            }else {
                              // Si la tienen recorremos meses y checamos que sea del mismo mes para sumarla
                              foreach ($invitado["utilidadxmes"] as $chiave => $mesdelref) {

                                if ($mesdelref["mes"] == $month && $mesdelref["year"] == $year) {
                                  $totalref = $totalref+$mesdelref["total"];
                                }

                              }
                            }

                          }
                        }
                      }
                    }
                  }

                  $tutil = number_format($mesesdelinv["utilidad"], 2, '.', ',');
                  $tutilacum = number_format($mesesdelinv["utilacumulada"], 2, '.', ',');
                  $tcapini = number_format($mesesdelinv["capini"], 2, '.', ',');
                  $tsubtotalinv = number_format($mesesdelinv["total"], 2, '.', ',');
                  $ttotalref = number_format($totalref, 2, '.', ',');
                  $linkref = "<span class='verde btn-ver-refmesuser' data-userid='".$inversor["id"]."' data-mes='".$month."' data-agno='".$year."'>$".$ttotalref."</span>";
                  $ttotal = number_format(($mesesdelinv["total"]+$totalref), 2, '.', ',');
                  $finaltotalmes = $finaltotalmes + ($mesesdelinv["total"]+$totalref);
                  $tutiltot = number_format(($mesesdelinv["utilacumulada"]+$totalref), 2, '.', ',');
                  $acciones = "<button aria-label='Ver dashboard' data-microtip-position='top' data-microtip-size='medium' role='tooltip' class='button btn-proyec-user' data-usuario='".$inversor["id"]."'><i class='fa-solid fa-magnifying-glass-chart'></i></button>";
                  $botdep = '<span class="verde btn-ver-dep" data-userid="'.$inversor["id"].'" data-mes="'.$month.'" data-agno="'.$year.'">+ $'.number_format($mesesdelinv["depmes"], 2, '.', ',').'</span>';
                  $botret = '<span class="rojo btn-ver-ret" data-userid="'.$inversor["id"].'" data-mes="'.$month.'" data-agno="'.$year.'">- $'.number_format($mesesdelinv["retmes"], 2, '.', ',').'</span>';

                  $row = array(
                      'nombre' => $inversor["nombre"],
                      'depmes'=> $botdep,
                      'capini'=> "$".$tcapini,
                      'util'=> "$".$tutil,
                      'utilacum'=> "$".$tutilacum,
                      'retmes'=> $botret,
                      'subtotalinv'=> "$".$tsubtotalinv,
                      'utilref'=> $linkref,
                      'utiltot'=> "$".$tutiltot,
                      'total'=> "$".$ttotal,
                      'acciones'=>$acciones
                    );
                  $return_json[] = $row;

                }
              }
            }
          }
        }
      }

      $return1_json = array();

      if (count($totaldepdatos) == 0) {

      }else {
        foreach ($totaldepdatos as $key => $value) {

          $tcant = number_format($value["cantidad"], 2, '.', ',');
          $mes = $value["mes"];
          $tmes = $mesesNombre[$mes];
          $agno = (int)$value['agno'];
          $accio = "<span class='accio'><i class='fa-solid fa-circle-check'></i> Registrado</span>";

          $row = array(
              'mes' => $tmes." ".$agno,
              'cantidad'=> "$".$tcant,
              'status'=> $accio
            );
          $return1_json[] = $row;
        }
      }

      $return2_json = array();

      if (count($totalretdatos) == 0) {

      }else {
        foreach ($totalretdatos as $key => $value) {

          $tcant = number_format($value["cantidad"], 2, '.', ',');
          $mes = $value["mes"];
          $tmes = $mesesNombre[$mes];
          $agno = (int)$value['agno'];
          $accio = "<span class='accio'><i class='fa-solid fa-circle-check'></i> Registrado</span>";

          $row = array(
              'mes' => $tmes." ".$agno,
              'cantidad'=> "$".$tcant,
              'status'=> $accio
            );
          $return2_json[] = $row;
        }
      }

      $gral_json['tabla'] = $return_json;
      $gral_json['tabla1'] = $return1_json;
      $gral_json['tabla2'] = $return2_json;
      $gral_json['totalmes'] = "$".number_format($finaltotalmes, 2, '.', ',');

      //return the result to the ajax request and die
      echo json_encode(array('data' => $gral_json));
      wp_die();
    }

  }

  public function mostrarTablaRefRepMes(){
    if(isset($_POST['mes'])){
      global $wpdb;
      $usuariosbl = $wpdb->prefix . 'usuarios_bl';
      $cuentasbl = $wpdb->prefix . 'cuentas_bl';
      $registrosbl = $wpdb->prefix . 'registros_bl';
      $projectsbl = $wpdb->prefix . 'projects_bl';
      // $uid = (int) $_POST['uid'];
      $rbl_year = (int) $_POST['year'];
      $rbl_mes = (int) $_POST['mes'];
      $pbl_id = (int) $_POST['pbl'];
      $ruta = get_site_url();
      // $registros = $wpdb->get_results(" SELECT * FROM $registrosbl WHERE rbl_cuenta = $cid AND rbl_status = 1 ORDER BY rbl_year, rbl_mes", ARRAY_A);
      $registros = $wpdb->get_results(" SELECT * FROM $registrosbl INNER JOIN $cuentasbl ON $registrosbl.rbl_cuenta = $cuentasbl.cbl_id INNER JOIN $usuariosbl ON $cuentasbl.cbl_usuario = $usuariosbl.ubl_id INNER JOIN $projectsbl ON $usuariosbl.ubl_project = $projectsbl.pbl_id WHERE $cuentasbl.cbl_tipo = 0 AND $usuariosbl.ubl_project = $pbl_id AND rbl_year = $rbl_year AND rbl_mes = $rbl_mes ORDER BY rbl_id", ARRAY_A);

      $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
      '8' => 'Agosto',
      '9' => 'Septiembre',
      '10' => 'Octubre',
      '11' => 'Noviembre',
      '12' => 'Diciembre' );

      $gral_json = array();

      $return_json = array();

      if(count($registros) == 0){

        $gral_json['tabla'] = $return_json;
        $gral_json['totalcom'] = "$".number_format(0, 2, '.', ',');
        $gral_json['totalutlreal'] = "$".number_format(0, 2, '.', ',');
        //return the result to the ajax request and die
        echo json_encode(array('data' => $gral_json));
        wp_die();
        return;
      }

      $comtotal = 0;
      $totalutlreal = 0;
      foreach ($registros as $key => $value) {

        $utilmes  = (float) $value["rbl_utilmes"];
        $combro  = (float) $value["rbl_combro"];
        $utilreal = $utilmes - $combro;
        if ($value["pbl_comision"]) {
          $comision = (float) $value["pbl_comision"];
        }else{
          $comision = 0;
        }
        $comfinal = ($utilreal*$comision)/100;
        $tutilreal = "$".number_format($utilreal, 2);
        $tcomfinal = "$".number_format($comfinal, 2);
        $tutilmes  = "$".number_format($value["rbl_utilmes"], 2);
        $tcombro  = "$".number_format($value["rbl_combro"], 2);
        $mes = (int)$value['rbl_mes'];
        $tmes = $mesesNombre[$mes];
        $agno = (int)$value['rbl_year'];

        if ($value["cbl_numero"] == null || $value["cbl_numero"] == "") {
          $tcuenta = "<a class='link-mes' href='".$ruta."/wp-admin/admin.php?page=crc_admin_vercuentabl&id=".$value["cbl_id"]."'>000000</a>";
        }else{
          $tcuenta = "<a class='link-mes' href='".$ruta."/wp-admin/admin.php?page=crc_admin_vercuentabl&id=".$value["cbl_id"]."'>".$value["cbl_numero"]."</a>";
        }

        $totalutlreal = $totalutlreal+$utilreal;
        $comtotal = $comtotal+$comfinal;

        // $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['rbl_id']."' data-bs-toggle='modal' data-bs-target='#modal-editregnbl'><i class='fa-solid fa-pen'></i></button><button aria-label='".$value["rbl_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-eliminar btn-deleteregnbl' type='button' data-id='".$value['rbl_id']."'><i class='fa-solid fa-x'></i></button></div>";

        // $ublid = $value['ubl_id'];

        $row = array(
            'id' => ($key+1),
            'cuenta'=> $tcuenta,
            'comfinal' => $tcomfinal,
            'utilmes' => $tutilmes,
            'combro' => $tcombro,
            'utilreal' => $tutilreal
          );
        $return_json[] = $row;

      }

      $gral_json['tabla'] = $return_json;
      $gral_json['totalcom'] = "$".number_format($comtotal, 2, '.', ',');
      $gral_json['totalutlreal'] = "$".number_format($totalutlreal, 2, '.', ',');
      //return the result to the ajax request and die
      echo json_encode(array('data' => $gral_json));
      wp_die();

    }
  }

  public function mostrarTablaRefRepMesSpe(){
    if(isset($_POST['mes'])){
      global $wpdb;
      $usuariosbl = $wpdb->prefix . 'usuarios_bl';
      $cuentasbl = $wpdb->prefix . 'cuentas_bl';
      $registrosbl = $wpdb->prefix . 'registros_bl';
      $projectsbl = $wpdb->prefix . 'projects_bl';
      // $uid = (int) $_POST['uid'];
      $rbl_year = (int) $_POST['year'];
      $rbl_mes = (int) $_POST['mes'];
      $pbl_id = (int) $_POST['pbl'];
      // $cid = 1;
      $ruta = get_site_url();
      // $registros = $wpdb->get_results(" SELECT * FROM $registrosbl WHERE rbl_cuenta = $cid AND rbl_status = 1 ORDER BY rbl_year, rbl_mes", ARRAY_A);
      $registros = $wpdb->get_results(" SELECT * FROM $registrosbl INNER JOIN $cuentasbl ON $registrosbl.rbl_cuenta = $cuentasbl.cbl_id INNER JOIN $usuariosbl ON $cuentasbl.cbl_usuario = $usuariosbl.ubl_id INNER JOIN $projectsbl ON $usuariosbl.ubl_project = $projectsbl.pbl_id WHERE  $cuentasbl.cbl_tipo = 1 AND rbl_year = $rbl_year AND rbl_mes = $rbl_mes ORDER BY rbl_id", ARRAY_A);

      $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
      '8' => 'Agosto',
      '9' => 'Septiembre',
      '10' => 'Octubre',
      '11' => 'Noviembre',
      '12' => 'Diciembre' );

      $gral_json = array();

      $return_json = array();

      if(count($registros) == 0){

        $gral_json['tabla'] = $return_json;
        $gral_json['totalcom'] = "$".number_format(0, 2, '.', ',');
        $gral_json['totalutlaft'] = "$".number_format(0, 2, '.', ',');
        //return the result to the ajax request and die
        echo json_encode(array('data' => $gral_json));
        wp_die();
        return;
      }

      $comtotal = 0;
      $totalutlaft = 0;
      foreach ($registros as $key => $value) {

        $utilmes  = (float) $value["rbl_utilmes"];
        $comtra  = (float) $value["rbl_comtra"];
        $combro  = (float) $value["rbl_combro"];
        $salini  = (float) $value["rbl_salini"];
        $utilmesfinal = $utilmes - $combro;
        $comtrapor = round(($comtra*100)/$utilmesfinal,2);

        $comandresent = (float) $value["pbl_comandres"];
        $comtigerent = (float) $value["pbl_comtiger"];
        $comfinpor = $comtrapor+$comandresent+$comtigerent;
        $utilafterpor = 100-$comfinpor;
        $utilafter = round($utilmesfinal*($utilafterpor/100),2);
        // if($salini == 0){
        //   $utilrealfinpor = 100;
        // }else{
        //   $utilrealfinpor = round(($utilafter*100)/$salini,2);
        // }
        $utilrealfinpor = round(($utilafter*100)/$salini,2);
        $comandres = round(($utilmesfinal*($comandresent/100)),2);
        $comtiger = round(($utilmesfinal*($comtigerent/100)),2);
        // $utilreal = $utilmes - $combro;
        // $comfinal = ($utilreal*2.5)/100;
        // $tutilreal = "$".number_format($utilreal, 2);
        // $tcomfinal = "$".number_format($comfinal, 2);
        $tutilmes  = "$".number_format($value["rbl_utilmes"], 2);
        $tcomtra  = "$".number_format($value["rbl_comtra"], 2);
        $tcombro  = "$".number_format($value["rbl_combro"], 2);
        $tutilafter = "$".number_format($utilafter, 2);
        $tsaldini  = "$".number_format($value["rbl_salini"], 2);
        $tcomandres = "$".number_format($comandres, 2);
        $tcomtiger = "$".number_format($comtiger, 2);
        $mes = (int)$value['rbl_mes'];
        $tmes = $mesesNombre[$mes];
        $agno = (int)$value['rbl_year'];

        if ($value["cbl_numero"] == null || $value["cbl_numero"] == "") {
          $tcuenta = "<a class='link-mes' href='".$ruta."/wp-admin/admin.php?page=crc_admin_vercuentablspe&id=".$value["cbl_id"]."'>000000</a>";
        }else{
          $tcuenta = "<a class='link-mes' href='".$ruta."/wp-admin/admin.php?page=crc_admin_vercuentablspe&id=".$value["cbl_id"]."'>".$value["cbl_numero"]."</a>";
        }
        $comtotal = $comtotal+$comandres;
        $totalutlaft = $totalutlaft+$utilafter;
        // $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['rbl_id']."' data-bs-toggle='modal' data-bs-target='#modal-editregnbl'><i class='fa-solid fa-pen'></i></button><button aria-label='".$value["rbl_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-eliminar btn-deleteregnbl' type='button' data-id='".$value['rbl_id']."'><i class='fa-solid fa-x'></i></button></div>";

        // $ublid = $value['ubl_id'];

        $row = array(
            'id' => ($key+1),
            'cuenta'=> $tcuenta,
            'comandres' => $tcomandres,
            'utilmes' => $tutilmes,
            'combro' => $tcombro,
            'comtra' => $tcomtra,
            'pcomtra' => $comtrapor."%",
            'utilafter' => $tutilafter,
            'salini' => $tsaldini,
            'putilrealfin' => $utilrealfinpor."%",
            'comtiger' => $tcomtiger
          );
        $return_json[] = $row;

      }

      $gral_json['tabla'] = $return_json;
      $gral_json['totalcom'] = "$".number_format($comtotal, 2, '.', ',');
      $gral_json['totalutlaft'] = "$".number_format($totalutlaft, 2, '.', ',');
      //return the result to the ajax request and die
      echo json_encode(array('data' => $gral_json));
      wp_die();

    }
  }

  public function mostrarTablaReferralUsers(){
    global $wpdb;
    $usersbl = $wpdb->prefix . 'usuarios_bl';
    $cuentasbl = $wpdb->prefix . 'cuentas_bl';
    $ruta = get_site_url();
    $pbl_id = (int) $_POST['pid'];
    $registros = $wpdb->get_results(" SELECT * FROM $usersbl WHERE ubl_project = $pbl_id ORDER BY ubl_id DESC", ARRAY_A);

    $return_json = array();

    if(count($registros) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

    foreach ($registros as $key => $value) {

      $nombre = $value["ubl_nombre"]." ".$value["ubl_apellidos"];

      if(!$value["ubl_correo"]){
        $email = "";
      }else{
        $email = $value["ubl_correo"];
      }

      if($value["ubl_tipo"] == 1 ){
        $tipo = "<i class='fa-solid fa-star star-amarilla'></i>";
      }else{
        $tipo = "";
      }



      $ublid = $value['ubl_id'];

      $cuentaslista = $wpdb->get_results(" SELECT * FROM $cuentasbl WHERE cbl_usuario = $ublid ORDER BY cbl_id ASC", ARRAY_A);

      if(count($cuentaslista) == 0){
        $cuentas = "<div class='btn-group'>
                    <button type='button' class='btn btn-secondary dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                      Sin cuentas
                    </button>

                  </div>";
        if ($value["ubl_tipo"] == 0) {
          $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['ubl_id']."' data-bs-toggle='modal' data-bs-target='#modal-edituserbl'><i class='fa-solid fa-user-pen'></i></button><button class='btn btn-addcuenta' type='button' data-id='".$value['ubl_id']."' data-Tipo='0' data-bs-toggle='modal' data-bs-target='#modal-addcuentabl'><i class='fa-solid fa-file-invoice-dollar'></i></button><button aria-label='".$value["ubl_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn btn-microtexto microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-verreporte' type='button' data-id='".$value['ubl_id']."'><i class='fa-solid fa-book'></i></button><button class='btn btn-eliminar' type='button' data-id='".$value['ubl_id']."'><i class='fa-solid fa-user-slash'></i></button></div>";
        }else {
          $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['ubl_id']."' data-bs-toggle='modal' data-bs-target='#modal-edituserbl'><i class='fa-solid fa-user-pen'></i></button><button class='btn btn-addcuenta' type='button' data-id='".$value['ubl_id']."' data-tipo='1' data-bs-toggle='modal' data-bs-target='#modal-addcuentabl'><i class='fa-solid fa-file-invoice-dollar'></i></button><button aria-label='".$value["ubl_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn btn-microtexto microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-verreportespe' type='button' data-id='".$value['ubl_id']."'><i class='fa-solid fa-book'></i></button><button class='btn btn-eliminar' type='button' data-id='".$value['ubl_id']."'><i class='fa-solid fa-user-slash'></i></button></div>";
        }

      }else{
        $cuentas = "<div class='btn-group'>
                    <button type='button' class='btn btn-vercuentas dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                      Ver cuentas
                    </button>
                    <ul class='dropdown-menu'>";
        foreach ($cuentaslista as $llave => $valor) {
          //vemos de que tipo es la cuenta para agregar la vista correcta

          if ($valor['cbl_numero'] == null || $valor['cbl_numero'] == "") {
            $numcuenta = "000000";
          }else{
            $numcuenta = $valor['cbl_numero'];
          }

          if ($valor['cbl_tipo'] == 0) {
            $cuentas .=  "<li><a class='dropdown-item' href='".$ruta."/wp-admin/admin.php?page=crc_admin_vercuentabl&id=".$valor['cbl_id']."'>".$numcuenta." - ".$valor['cbl_nombre']."</a></li>";
          }else {
            $cuentas .=  "<li><a class='dropdown-item' href='".$ruta."/wp-admin/admin.php?page=crc_admin_vercuentablspe&id=".$valor['cbl_id']."'>".$numcuenta." - ".$valor['cbl_nombre']."</a></li>";
          }

        }
        $cuentas .=  "</ul>
                  </div>";

        if ($value["ubl_tipo"] == 0) {
          $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['ubl_id']."' data-bs-toggle='modal' data-bs-target='#modal-edituserbl'><i class='fa-solid fa-user-pen'></i></button><button class='btn btn-addcuenta' type='button' data-id='".$value['ubl_id']."' data-Tipo='0' data-bs-toggle='modal' data-bs-target='#modal-addcuentabl'><i class='fa-solid fa-file-invoice-dollar'></i></button><button aria-label='".$value["ubl_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn btn-microtexto microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-verreporte' type='button' data-id='".$value['ubl_id']."'><i class='fa-solid fa-book'></i></button><button class='btn btn-eliminar-gris' type='button' data-id='".$value['ubl_id']."'><i class='fa-solid fa-user-slash'></i></button></div>";
        }else {
          $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['ubl_id']."' data-bs-toggle='modal' data-bs-target='#modal-edituserbl'><i class='fa-solid fa-user-pen'></i></button><button class='btn btn-addcuenta' type='button' data-id='".$value['ubl_id']."' data-Tipo='1' data-bs-toggle='modal' data-bs-target='#modal-addcuentabl'><i class='fa-solid fa-file-invoice-dollar'></i></button><button aria-label='".$value["ubl_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn btn-microtexto microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-verreportespe' type='button' data-id='".$value['ubl_id']."'><i class='fa-solid fa-book'></i></button><button class='btn btn-eliminar-gris' type='button' data-id='".$value['ubl_id']."'><i class='fa-solid fa-user-slash'></i></button></div>";
        }

      }



      $row = array(
          'id' => ($key+1),
          // 'tipo'=> $tipo,
          'nombre' => $nombre,
          // 'email' => $email,
          'cuentas' => $cuentas,
          'acciones' => $acciones,
          'namec' => "Activa"
        );
      $return_json[] = $row;

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }

  public function mostrarTablaReferralUsersSpecial(){
    global $wpdb;
    $usersbl = $wpdb->prefix . 'usuarios_bl';
    $cuentasbl = $wpdb->prefix . 'cuentas_bl';
    $ruta = get_site_url();
    $pbl_id = (int) $_POST['pid'];
    $registros = $wpdb->get_results(" SELECT * FROM $usersbl WHERE ubl_project = $pbl_id ORDER BY ubl_id DESC", ARRAY_A);

    $return_json = array();

    if(count($registros) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

    foreach ($registros as $key => $value) {

      $nombre = $value["ubl_nombre"]." ".$value["ubl_apellidos"];

      if(!$value["ubl_correo"]){
        $email = "";
      }else{
        $email = $value["ubl_correo"];
      }

      if($value["ubl_tipo"] == 1 ){
        $tipo = "<i class='fa-solid fa-star star-amarilla'></i>";
      }else{
        $tipo = "";
      }



      $ublid = $value['ubl_id'];

      $cuentaslista = $wpdb->get_results(" SELECT * FROM $cuentasbl WHERE cbl_usuario = $ublid ORDER BY cbl_id ASC", ARRAY_A);

      if(count($cuentaslista) == 0){
        $cuentas = "<div class='btn-group'>
                    <button type='button' class='btn btn-secondary dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                      Sin cuentas
                    </button>

                  </div>";
        if ($value["ubl_tipo"] == 0) {
          $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['ubl_id']."' data-bs-toggle='modal' data-bs-target='#modal-edituserbl'><i class='fa-solid fa-user-pen'></i></button><button class='btn btn-addcuenta' type='button' data-id='".$value['ubl_id']."' data-Tipo='0' data-bs-toggle='modal' data-bs-target='#modal-addcuentabl'><i class='fa-solid fa-file-invoice-dollar'></i></button><button aria-label='".$value["ubl_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn btn-microtexto microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-verreporte' type='button' data-id='".$value['ubl_id']."'><i class='fa-solid fa-book'></i></button><button class='btn btn-eliminar' type='button' data-id='".$value['ubl_id']."'><i class='fa-solid fa-user-slash'></i></button></div>";
        }else {
          $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['ubl_id']."' data-bs-toggle='modal' data-bs-target='#modal-edituserbl'><i class='fa-solid fa-user-pen'></i></button><button class='btn btn-addcuenta' type='button' data-id='".$value['ubl_id']."' data-tipo='1' data-bs-toggle='modal' data-bs-target='#modal-addcuentabl'><i class='fa-solid fa-file-invoice-dollar'></i></button><button aria-label='".$value["ubl_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn btn-microtexto microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-verreportespe' type='button' data-id='".$value['ubl_id']."'><i class='fa-solid fa-book'></i></button><button class='btn btn-eliminar' type='button' data-id='".$value['ubl_id']."'><i class='fa-solid fa-user-slash'></i></button></div>";
        }


        $row = array(
            'id' => ($key+1),
            // 'tipo'=> $tipo,
            'nombre' => $nombre,
            // 'email' => $email,
            'ncuenta' => "-.-",
            'acciones' => $acciones,
            'namec' => "Activa"
          );
        $return_json[] = $row;

      }else{
        $cuentas = "<div class='btn-group'>
                    <button type='button' class='btn btn-vercuentas dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                      Ver cuentas
                    </button>
                    <ul class='dropdown-menu'>";
        foreach ($cuentaslista as $llave => $valor) {
          //vemos de que tipo es la cuenta para agregar la vista correcta

          if ($valor['cbl_numero'] == null || $valor['cbl_numero'] == "") {
            $numcuenta = "000000";
            $nomcuenta = "<a class='link-mes' href='".$ruta."/wp-admin/admin.php?page=crc_admin_vercuentablspe&id=".$valor['cbl_id']."'>000000 - ".$valor['cbl_nombre']."</a>";
            $namec = "Activa";
          }else{
            $numcuenta = $valor['cbl_numero'];
            $nomcuenta = "<a class='link-mes' href='".$ruta."/wp-admin/admin.php?page=crc_admin_vercuentablspe&id=".$valor['cbl_id']."'>".$valor['cbl_numero']." - ".$valor['cbl_nombre']."</a>";
            $namec = "Activa";
          }

          // if ($valor['cbl_tipo'] == 0) {
          //   $cuentas .=  "<li><a class='dropdown-item' href='".$ruta."/wp-admin/admin.php?page=crc_admin_vercuentabl&id=".$valor['cbl_id']."'>".$numcuenta." - ".$valor['cbl_nombre']."</a></li>";
          // }else {
          //   $cuentas .=  "<li><a class='dropdown-item' href='".$ruta."/wp-admin/admin.php?page=crc_admin_vercuentablspe&id=".$valor['cbl_id']."'>".$numcuenta." - ".$valor['cbl_nombre']."</a></li>";
          // }
          //
          // $cuentas .=  "</ul>
          //           </div>";

          if ($value["ubl_tipo"] == 0) {
            $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['ubl_id']."' data-bs-toggle='modal' data-bs-target='#modal-edituserbl'><i class='fa-solid fa-user-pen'></i></button><button class='btn btn-addcuenta' type='button' data-id='".$value['ubl_id']."' data-Tipo='0' data-bs-toggle='modal' data-bs-target='#modal-addcuentabl'><i class='fa-solid fa-file-invoice-dollar'></i></button><button aria-label='".$value["ubl_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn btn-microtexto microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-verreporte' type='button' data-id='".$value['ubl_id']."'><i class='fa-solid fa-book'></i></button><button class='btn btn-eliminar-gris' type='button' data-id='".$value['ubl_id']."'><i class='fa-solid fa-user-slash'></i></button></div>";
          }else {
            $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['ubl_id']."' data-bs-toggle='modal' data-bs-target='#modal-edituserbl'><i class='fa-solid fa-user-pen'></i></button><button class='btn btn-addcuenta' type='button' data-id='".$value['ubl_id']."' data-Tipo='1' data-bs-toggle='modal' data-bs-target='#modal-addcuentabl'><i class='fa-solid fa-file-invoice-dollar'></i></button><button aria-label='".$value["ubl_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn btn-microtexto microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-verreportespe' type='button' data-id='".$value['ubl_id']."'><i class='fa-solid fa-book'></i></button><button class='btn btn-eliminar-gris' type='button' data-id='".$value['ubl_id']."'><i class='fa-solid fa-user-slash'></i></button></div>";
          }

          $row = array(
              'id' => ($key+1),
              // 'tipo'=> $tipo,
              'nombre' => $nombre,
              // 'email' => $email,
              // 'cuentas' => $numcuenta,
              'ncuenta' => $nomcuenta,
              'acciones' => $acciones,
              'namec' => $namec
            );
          $return_json[] = $row;

        }


      }

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }

  public function mostrarTablaReferralCuentaN(){
    if(isset($_POST['cid'])){
      global $wpdb;
      $usersbl = $wpdb->prefix . 'usuarios_bl';
      $cuentasbl = $wpdb->prefix . 'cuentas_bl';
      $registrosbl = $wpdb->prefix . 'registros_bl';
      $projectsbl = $wpdb->prefix . 'projects_bl';
      $cid = (int) $_POST['cid'];
      // $cid = 1;
      $ruta = get_site_url();
      $registros = $wpdb->get_results(" SELECT * FROM $registrosbl INNER JOIN $cuentasbl ON $registrosbl.rbl_cuenta = $cuentasbl.cbl_id INNER JOIN $usersbl ON $cuentasbl.cbl_usuario = $usersbl.ubl_id INNER JOIN $projectsbl ON $usersbl.ubl_project = $projectsbl.pbl_id WHERE rbl_cuenta = $cid AND rbl_status = 1 ORDER BY rbl_year, rbl_mes ", ARRAY_A);
      $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
      '8' => 'Agosto',
      '9' => 'Septiembre',
      '10' => 'Octubre',
      '11' => 'Noviembre',
      '12' => 'Diciembre' );

      $return_json = array();

      if(count($registros) == 0){

        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
        return;
      }

      foreach ($registros as $key => $value) {

        $utilmes  = (float) $value["rbl_utilmes"];
        $combro  = (float) $value["rbl_combro"];
        $utilreal = $utilmes - $combro;
        if ($value["pbl_comision"]) {
          $comision = (float) $value["pbl_comision"];
        }else{
          $comision = 0;
        }
        $comfinal = ($utilreal*$comision)/100;
        $tutilreal = "$".number_format($utilreal, 2);
        $tcomfinal = "$".number_format($comfinal, 2);
        $tutilmes  = "$".number_format($value["rbl_utilmes"], 2);
        $tcombro  = "$".number_format($value["rbl_combro"], 2);
        $mes = (int)$value['rbl_mes'];
        $tmes = $mesesNombre[$mes];
        $agno = (int)$value['rbl_year'];

        $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['rbl_id']."' data-bs-toggle='modal' data-bs-target='#modal-editregnbl'><i class='fa-solid fa-pen'></i></button><button aria-label='".$value["rbl_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-eliminar btn-deleteregnbl' type='button' data-id='".$value['rbl_id']."'><i class='fa-solid fa-x'></i></button></div>";

        // $ublid = $value['ubl_id'];

        $row = array(
            'id' => ($key+1),
            'agno'=> $agno,
            'mes' => $tmes,
            'utilmes' => $tutilmes,
            'combro' => $tcombro,
            'utilreal' => $tutilreal,
            'comfinal' => $tcomfinal,
            'acciones' => $acciones
          );
        $return_json[] = $row;

      }
      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
    }

  }

  public function mostrarTablaReferralCuentaS(){
    if(isset($_POST['cid'])){
      global $wpdb;
      $usersbl = $wpdb->prefix . 'usuarios_bl';
      $cuentasbl = $wpdb->prefix . 'cuentas_bl';
      $registrosbl = $wpdb->prefix . 'registros_bl';
      $projectsbl = $wpdb->prefix . 'projects_bl';
      $cid = (int) $_POST['cid'];
      // $cid = 1;
      $ruta = get_site_url();
      $registros = $wpdb->get_results(" SELECT * FROM $registrosbl INNER JOIN $cuentasbl ON $registrosbl.rbl_cuenta = $cuentasbl.cbl_id INNER JOIN $usersbl ON $cuentasbl.cbl_usuario = $usersbl.ubl_id INNER JOIN $projectsbl ON $usersbl.ubl_project = $projectsbl.pbl_id WHERE rbl_cuenta = $cid AND rbl_status = 1 ORDER BY rbl_year, rbl_mes ", ARRAY_A);
      $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
      '8' => 'Agosto',
      '9' => 'Septiembre',
      '10' => 'Octubre',
      '11' => 'Noviembre',
      '12' => 'Diciembre' );

      $return_json = array();

      if(count($registros) == 0){

        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
        return;
      }

      foreach ($registros as $key => $value) {

        $utilmes  = (float) $value["rbl_utilmes"];
        $comtra  = (float) $value["rbl_comtra"];
        $combro  = (float) $value["rbl_combro"];
        $salini  = (float) $value["rbl_salini"];
        $utilmesfinal = $utilmes - $combro;
        $comtrapor = round(($comtra*100)/$utilmesfinal,2);

        $comandresent = (float) $value["pbl_comandres"];
        $comtigerent = (float) $value["pbl_comtiger"];
        $comfinpor = $comtrapor+$comandresent+$comtigerent;
        $utilafterpor = 100-$comfinpor;
        $utilafter = round($utilmesfinal*($utilafterpor/100),2);
        // if($salini == 0){
        //   $utilrealfinpor = 100;
        // }else{
        //   $utilrealfinpor = round(($utilafter*100)/$salini,2);
        // }
        $utilrealfinpor = round(($utilafter*100)/$salini,2);
        $comandres = round(($utilmesfinal*($comandresent/100)),2);
        $comtiger = round(($utilmesfinal*($comtigerent/100)),2);
        // $utilreal = $utilmes - $combro;
        // $comfinal = ($utilreal*2.5)/100;
        // $tutilreal = "$".number_format($utilreal, 2);
        // $tcomfinal = "$".number_format($comfinal, 2);
        $tutilmes  = "$".number_format($value["rbl_utilmes"], 2);
        $tcomtra  = "$".number_format($value["rbl_comtra"], 2);
        $tcombro  = "$".number_format($value["rbl_combro"], 2);
        $tutilafter = "$".number_format($utilafter, 2);
        $tsaldini  = "$".number_format($value["rbl_salini"], 2);
        $tcomandres = "$".number_format($comandres, 2);
        $tcomtiger = "$".number_format($comtiger, 2);
        $mes = (int)$value['rbl_mes'];
        $tmes = $mesesNombre[$mes];
        $agno = (int)$value['rbl_year'];

        $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['rbl_id']."' data-bs-toggle='modal' data-bs-target='#modal-editregnbl'><i class='fa-solid fa-pen'></i></button><button aria-label='".$value["rbl_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-eliminar btn-deleteregnbl' type='button' data-id='".$value['rbl_id']."'><i class='fa-solid fa-x'></i></button></div>";

        // $ublid = $value['ubl_id'];

        $row = array(
            'id' => ($key+1),
            'agno'=> $agno,
            'mes' => $tmes,
            'utilmes' => $tutilmes,
            'combro' => $tcombro,
            'comtra' => $tcomtra,
            'pcomtra' => $comtrapor."%",
            'utilafter' => $tutilafter,
            'salini' => $tsaldini,
            'putilrealfin' => $utilrealfinpor."%",
            'comtiger' => $tcomtiger,
            'comandres' => $tcomandres,
            'acciones' => $acciones
          );
        $return_json[] = $row;

      }
      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
    }

  }

  public function mostrarTablaReferralProyectoNFT(){
    if(isset($_POST['nid'])){
      global $wpdb;
      // $usersbl = $wpdb->prefix . 'usuarios_bl';
      $proyectosnft = $wpdb->prefix . 'projects_nft';
      $registrosnft = $wpdb->prefix . 'registros_nft';
      $retirosnft = $wpdb->prefix . 'retiros_nft';
      $nid = (int) $_POST['nid'];
      // $cid = 1;
      $ruta = get_site_url();
      $registros = $wpdb->get_results(" SELECT * FROM $registrosnft INNER JOIN $proyectosnft ON $registrosnft.rnft_proyecto = $proyectosnft.nft_id  WHERE rnft_proyecto = $nid ORDER BY rnft_year, rnft_mes, rnft_semana, rnft_fecha ", ARRAY_A);
      $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
      '8' => 'Agosto',
      '9' => 'Septiembre',
      '10' => 'Octubre',
      '11' => 'Noviembre',
      '12' => 'Diciembre' );

      $return_json = array();

      if(count($registros) == 0){

        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
        return;
      }

      $totalacu = 0;
      $teamacu = 0;
      $personalacu = 0;
      $totalante = 0;
      $teamante = 0;
      $personalante = 0;
      $buclenum = 0;


      foreach ($registros as $key => $value) {
        // Si es el primer registro

        $total  = (float) $value["rnft_total"];
        $team  = (float) $value["rnft_team"];
        $personal  = round($total-$team,4);

        $totaldif = $total - $totalante ;
        $teamdif = $team - $teamante ;
        $personaldif = $personal - $personalante ;

        $ttotal   = number_format($value["rnft_total"], 4)."/".number_format($totaldif, 4);
        $tteam  = number_format($value["rnft_team"], 4)."/".number_format($teamdif, 4);
        // $tpersonal  = number_format($personal, 4)."/".number_format($personalacu, 4);
        $tpersonal  = number_format($personal, 4)."/".number_format($personaldif, 4);

        if($value["rnft_retiro"]){
          $totalacu = 0;
          $teamacu = 0;
          $personalacu = 0;
          $totalante = 0;
          $teamante = 0;
          $personalante = 0;
        }else {
            $totalacu = $total;
            $teamacu = $team;
            $personalacu = $personal;
            $totalante = $total;
            $teamante = $team;
            $personalante = $personal;
          }


        $mes = (int)$value['rnft_mes'];
        $tmes = $mesesNombre[$mes];
        $agno = (int)$value['rnft_year'];

        if ($value['nft_tipo']==1) {

          switch ($value['rnft_semana']) {
            case 1:
              $semana = "Semana 1";
              break;
            case 2:
              $semana = "Semana 2";
              break;
            case 3:
              $semana = "Semana 3";
              break;
            case 4:
              $semana = "Semana 4";
              break;
            case 5:
              $semana = "Semana 5";
              break;
            default:
              $semana = "Mensual";
              break;
          }
          $periodo = $tmes." - ".$semana;
        }else{
          $periodo = $tmes." - Mensual";
        }

        if($value["rnft_retiro"]){
          $rtid = (int)$value["rnft_retiro"];
          $retiros = $wpdb->get_results(" SELECT * FROM $retirosnft WHERE rtnft_id = $rtid ORDER BY rtnft_id DESC LIMIT 1", ARRAY_A);
          $retiro = $retiros[0]['rtnft_fecha_retiro'];
        }else{
          $retiro = "-.-";
        }

        $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['rnft_id']."' data-bs-toggle='modal' data-bs-target='#modal-editregnft'><i class='fa-solid fa-pen'></i></button><button aria-label='".$value["rnft_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-eliminar btn-deleteregnft' type='button' data-id='".$value['rnft_id']."'><i class='fa-solid fa-x'></i></button></div>";

        // $ublid = $value['ubl_id'];

        $row = array(
            'id' => ($key+1),
            'agno'=> $agno,
            'periodo' => $periodo,
            'total' => $ttotal,
            'team' => $tteam,
            'personal' => $tpersonal,
            'acciones' => $acciones,
            'status' => $retiro
          );
        $return_json[] = $row;

      }
      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
    }

  }

  public function mostrarTablaReferralProyectoNFTM(){
    // Este es la tabla por mes
    if(isset($_POST['nid'])){
      global $wpdb;
      // $usersbl = $wpdb->prefix . 'usuarios_bl';
      $proyectosnft = $wpdb->prefix . 'projects_nft';
      $registrosnft = $wpdb->prefix . 'registros_nft';
      $nid = (int) $_POST['nid'];
      // $cid = 1;
      $ruta = get_site_url();
      $registros = $wpdb->get_results(" SELECT rnft_mes, rnft_year, rnft_proyecto, ROUND(SUM(rnft_total), 4) AS rnft_cantidadtotal, ROUND(SUM(rnft_team), 4) AS rnft_cantidadteam, COUNT(rnft_year AND rnft_mes ) AS rnft_registros FROM $registrosnft INNER JOIN $proyectosnft ON $registrosnft.rnft_proyecto = $proyectosnft.nft_id  WHERE rnft_proyecto = $nid GROUP BY rnft_year, rnft_mes ORDER BY rnft_year, rnft_mes, rnft_semana, rnft_fecha ", ARRAY_A);
      $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
      '8' => 'Agosto',
      '9' => 'Septiembre',
      '10' => 'Octubre',
      '11' => 'Noviembre',
      '12' => 'Diciembre' );

      $return_json = array();

      if(count($registros) == 0){

        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
        return;
      }

      // $regi = count($registros);
      $totalacu = 0;
      $teamacu = 0;
      $personalacu = 0;
      $personalante = 0;

      foreach ($registros as $key => $value) {

        // Si es el primer registro
        if($key == 0){

          $total  = (float) $value["rnft_cantidadtotal"];
          $team  = (float) $value["rnft_cantidadteam"];
          $personal  = round($total-$team,4);

          $totalacu = $total;
          $teamacu = $team;
          $personalacu = $personal;

          $personaldif = $personal - $personalante ;

          // $ttotal   = number_format($value["rnft_total"], 4);
          // $tteam  = number_format($value["rnft_team"], 4);
          $ttotal   = number_format($value["rnft_cantidadtotal"], 4);
          $tteam  = number_format($value["rnft_cantidadteam"], 4);
          // $tpersonal  = number_format($personal, 4)."/".number_format($personalacu, 4);
          $tpersonal  = number_format($personal, 4)."/".number_format($personaldif, 4);
          // $tpersonal  = number_format($personal, 4)."/".number_format($personaldif, 4);

          $personalante = $personal;
        }else{

          $total  = (float) $value["rnft_cantidadtotal"];
          $team  = (float) $value["rnft_cantidadteam"];
          $personal  = round($total-$team,4);

          $totalacu = $totalacu + $total;
          $teamacu = $teamacu + $team;
          $personalacu = $personalacu + $personal;

          $personaldif = $personal - $personalante ;

          // $ttotal   = number_format($value["rnft_total"], 4);
          // $tteam  = number_format($value["rnft_team"], 4);
          $ttotal   = number_format($value["rnft_cantidadtotal"], 4);
          $tteam  = number_format($value["rnft_cantidadteam"], 4);
          // $tpersonal  = number_format($personal, 4)."/".number_format($personalacu, 4);
          $tpersonal  = number_format($personal, 4)."/".number_format($personaldif, 4);
          // $tpersonal  = number_format($personal, 4)."/".number_format($personaldif, 4);

          $personalante = $personal;
          // Si en algun momento ya hay un registro cerrado anterior, la diferencia vuelve a 0
          // if($value["rnft_status"] == 1){
          //   $personalante = $personal;
          // }else{
          //   $personalante = 0;
          // }

        }

        $tcuenta = "<a class='link-mes' href='".$ruta."/wp-admin/admin.php?page=crc_admin_verdetallemesnft&p=".$value["rnft_proyecto"]."&m=".$value["rnft_mes"]."&y=".$value["rnft_year"]."'>".$ttotal."</a>";

        $mes = (int)$value['rnft_mes'];
        $tmes = $mesesNombre[$mes];
        $agno = (int)$value['rnft_year'];
        $regi = (int)$value['rnft_registros'];

        $periodo = $tmes." - Mensual";

        // $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['rnft_id']."' data-bs-toggle='modal' data-bs-target='#modal-editregnft'><i class='fa-solid fa-pen'></i></button><button aria-label='".$value["rnft_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-eliminar btn-deleteregnft' type='button' data-id='".$value['rnft_id']."'><i class='fa-solid fa-x'></i></button></div>";

        // $ublid = $value['ubl_id'];

        $row = array(
            'id' => ($key+1),
            'agno'=> $agno,
            'periodo' => $periodo,
            'total' => $tcuenta,
            'team' => $tteam,
            'personal' => $tpersonal,
            'registros' => $regi
          );
        $return_json[] = $row;

      }
      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
    }

  }

  public function mostrarTablaReferralProyectoNFTMes(){
    // Este es el semanal por mes
    if(isset($_POST['nid'])){
      global $wpdb;
      // $usersbl = $wpdb->prefix . 'usuarios_bl';
      $proyectosnft = $wpdb->prefix . 'projects_nft';
      $registrosnft = $wpdb->prefix . 'registros_nft';
      $nid = (int) $_POST['nid'];
      // $cid = 1;
      $ruta = get_site_url();
      $registrossem = $wpdb->get_results("SELECT * FROM $registrosnft INNER JOIN $proyectosnft ON $registrosnft.rnft_proyecto = $proyectosnft.nft_id  WHERE rnft_proyecto = $nid ORDER BY rnft_year, rnft_mes, rnft_semana, rnft_fecha", ARRAY_A);
      $registros = $wpdb->get_results(" SELECT rnft_year, rnft_mes, ROUND(SUM(rnft_total), 4) AS totalmes, ROUND(SUM(rnft_team), 4) AS totalteam, COUNT(*) AS totalsemanas FROM $registrosnft INNER JOIN $proyectosnft ON $registrosnft.rnft_proyecto = $proyectosnft.nft_id  WHERE rnft_proyecto = $nid GROUP BY rnft_year, rnft_mes ORDER BY rnft_year, rnft_mes ", ARRAY_A);
      // $registros = $wpdb->get_results(" SELECT * FROM $registrosnft INNER JOIN $proyectosnft ON $registrosnft.rnft_proyecto = $proyectosnft.nft_id  WHERE rnft_proyecto = $nid ORDER BY rnft_year, rnft_mes, rnft_semana ", ARRAY_A);
      $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
      '8' => 'Agosto',
      '9' => 'Septiembre',
      '10' => 'Octubre',
      '11' => 'Noviembre',
      '12' => 'Diciembre' );

      $return_json = array();

      if(count($registros) == 0){

        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
        return;
      }

      $totalante = 0;
      $teamante = 0;
      $personalante = 0;

      $mtotalante = 0;
      $mteamante = 0;
      $mpersonalante = 0;

      foreach ($registros as $key => $value) {

        $numsemanas = $value["totalsemanas"];

        $mes = (int)$value['rnft_mes'];
        $tmes = $mesesNombre[$mes];
        $agno = (int)$value['rnft_year'];


        $mtotal = 0;
        $mteam = 0;
        $mpersonal = 0;


        // Ahora vamos por semana
        foreach ($registrossem as $llave => $valor) {

          if ($valor["rnft_year"] == $agno && $valor["rnft_mes"] == $mes)  {

            $total  = (float) $valor["rnft_total"];
            $team  = (float) $valor["rnft_team"];
            $personal  = round($total-$team,4);

            $totaldif = $total - $totalante ;
            $teamdif = $team - $teamante ;
            $personaldif = $personal - $personalante ;

            // $ttotali   = number_format($valor["rnft_total"], 4)."/".number_format($totaldif, 4);
            // $tteami  = number_format($valor["rnft_team"], 4)."/".number_format($teamdif, 4);
            // $tpersonal  = number_format($personal, 4)."/".number_format($personalacu, 4);
            // $tpersonali  = number_format($personal, 4)."/".number_format($personaldif, 4);

            // Vamos acumulando los totales diferencias
            $mtotal = $mtotal + $totaldif;
            $mteam = $mteam + $teamdif;
            $mpersonal = $mpersonal + $personaldif;

            $mtotaldif = $mtotal - $mtotalante ;
            $mteamdif = $mteam - $mteamante ;
            $mpersonaldif = $mpersonal - $mpersonalante ;

            if($valor["rnft_retiro"]){
              // $totalacu = 0;
              // $teamacu = 0;
              // $personalacu = 0;
              $totalante = 0;
              $teamante = 0;
              $personalante = 0;
            }else {
                // $totalacu = $total;
                // $teamacu = $team;
                // $personalacu = $personal;
                $totalante = $total;
                $teamante = $team;
                $personalante = $personal;
              }



          }
        }

        $mtotalante = $mtotal;
        $mteamante = $mteam;
        $mpersonalante = $mpersonal;

        $ttotal   = number_format($mtotal, 4)."/".number_format($mtotaldif,4);
        $tteam  = number_format($mteam, 4)."/".number_format($mteamdif,4);
        // $tpersonal  = number_format($personal, 4)."/".number_format($personalacu, 4);
        $tpersonal  = number_format($mpersonal, 4)."/".number_format($mpersonaldif,4);

        // $total  = (float) $value["totalmes"];
        // $team  = (float) $value["totalteam"];
        // $personal  = round($total-$team,4);
        //
        // $ttotal   = number_format($value["totalmes"], 4);
        // $tteam  = number_format($value["totalteam"], 4);
        // $tpersonal  = number_format($personal, 4);


        // if ($value['nft_tipo']==1) {
        //
        //   switch ($value['rnft_semana']) {
        //     case 1:
        //       $semana = "Semana 1";
        //       break;
        //     case 2:
        //       $semana = "Semana 2";
        //       break;
        //     case 3:
        //       $semana = "Semana 3";
        //       break;
        //     case 4:
        //       $semana = "Semana 4";
        //       break;
        //     case 5:
        //       $semana = "Semana 5";
        //       break;
        //     default:
        //       $semana = "Mensual";
        //       break;
        //   }
        //   $periodo = $tmes." - ".$semana;
        // }else{
        //   $periodo = $tmes." - Mensual";
        // }

        // if($value['rnft_status']==1){
        //   $status = "Abierto";
        // }else{
        //   $status = "Cerrado";
        // }

        // $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['rnft_id']."' data-bs-toggle='modal' data-bs-target='#modal-editregnft'><i class='fa-solid fa-pen'></i></button><button aria-label='".$value["rnft_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-eliminar btn-deleteregnft' type='button' data-id='".$value['rnft_id']."'><i class='fa-solid fa-x'></i></button></div>";

        // $ublid = $value['ubl_id'];

        $row = array(
            'id' => ($key+1),
            'agno'=> $agno,
            'mes' => $tmes,
            'total' => $ttotal,
            'team' => $tteam,
            'personal' => $tpersonal,
            'numsemanas' => $numsemanas
          );
        $return_json[] = $row;

      }
      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
    }

  }

  public function mostrarTablaReferralRetirosNFT(){
    if(isset($_POST['nid'])){
      global $wpdb;
      // $usersbl = $wpdb->prefix . 'usuarios_bl';
      $proyectosnft = $wpdb->prefix . 'projects_nft';
      $registrosnft = $wpdb->prefix . 'retiros_nft';
      $nid = (int) $_POST['nid'];
      // $cid = 1;
      $ruta = get_site_url();
      $registros = $wpdb->get_results(" SELECT * FROM $registrosnft INNER JOIN $proyectosnft ON $registrosnft.rtnft_proyecto = $proyectosnft.nft_id  WHERE rtnft_proyecto = $nid ORDER BY rtnft_fecha ", ARRAY_A);
      $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
      '8' => 'Agosto',
      '9' => 'Septiembre',
      '10' => 'Octubre',
      '11' => 'Noviembre',
      '12' => 'Diciembre' );

      $return_json = array();

      if(count($registros) == 0){

        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
        return;
      }

      foreach ($registros as $key => $value) {

        $total  = (float) $value["rtnft_cantidad"];
        $valusd  = (float) $value["rtnft_usdactual"];
        $ttotal   = number_format($total, 4);

        $totalval = $total * $valusd;
        $tvalusd = number_format($valusd,2);
        $ttotalval = number_format($totalval,2);

        $fecha = explode(" ", $value["rtnft_fecha"]);
        $tfecha = $value["rtnft_fecha_retiro"];

        $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['rtnft_id']."' data-bs-toggle='modal' data-bs-target='#modal-editretnft'><i class='fa-solid fa-pen'></i></button><button aria-label='".$value["rtnft_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-eliminar btn-deleteretnft' type='button' data-id='".$value['rtnft_id']."'><i class='fa-solid fa-x'></i></button></div>";

        // $ublid = $value['ubl_id'];

        $row = array(
            'id' => ($key+1),
            'cantidad'=> $ttotal,
            'valusd'=> "$".$tvalusd,
            'totalval' => "$".$ttotalval,
            'fecha' => $tfecha,
            'acciones' => $acciones
          );
        $return_json[] = $row;

      }
      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
    }

  }

  public function mostrarTablaReferralTotalCuentasN(){
    if(isset($_POST['uid'])){
      global $wpdb;
      $usuariosbl = $wpdb->prefix . 'usuarios_bl';
      $cuentasbl = $wpdb->prefix . 'cuentas_bl';
      $registrosbl = $wpdb->prefix . 'registros_bl';
      $projectsbl = $wpdb->prefix . 'projects_bl';
      $uid = (int) $_POST['uid'];
      // $cid = 1;
      $ruta = get_site_url();
      // $registros = $wpdb->get_results(" SELECT * FROM $registrosbl WHERE rbl_cuenta = $cid AND rbl_status = 1 ORDER BY rbl_year, rbl_mes", ARRAY_A);
      $registros = $wpdb->get_results(" SELECT ubl_id, pbl_comision, rbl_mes, rbl_year, ROUND(SUM(rbl_utilmes), 2) AS rbl_utilmes_total, ROUND(SUM(rbl_combro), 2) AS rbl_combro_total FROM $registrosbl INNER JOIN $cuentasbl ON $registrosbl.rbl_cuenta = $cuentasbl.cbl_id INNER JOIN $usuariosbl ON $cuentasbl.cbl_usuario = $usuariosbl.ubl_id INNER JOIN $projectsbl ON $usuariosbl.ubl_project = $projectsbl.pbl_id WHERE ubl_id = $uid GROUP BY rbl_mes, rbl_year ORDER BY rbl_year, rbl_mes", ARRAY_A);

      $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
      '8' => 'Agosto',
      '9' => 'Septiembre',
      '10' => 'Octubre',
      '11' => 'Noviembre',
      '12' => 'Diciembre' );

      $return_json = array();

      if(count($registros) == 0){

        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
        return;
      }

      foreach ($registros as $key => $value) {

        $utilmes  = (float) $value["rbl_utilmes_total"];
        $combro  = (float) $value["rbl_combro_total"];
        $utilreal = $utilmes - $combro;
        if ($value["pbl_comision"]) {
          $comision = (float) $value["pbl_comision"];
        }else{
          $comision = 0;
        }
        $comfinal = ($utilreal*$comision)/100;
        $tutilreal = "$".number_format($utilreal, 2);
        $tcomfinal = "$".number_format($comfinal, 2);
        $tutilmes  = "$".number_format($value["rbl_utilmes_total"], 2);
        $tcombro  = "$".number_format($value["rbl_combro_total"], 2);
        $mes = (int)$value['rbl_mes'];
        $tmes = $mesesNombre[$mes];
        $agno = (int)$value['rbl_year'];
        $linkmes = "<a class='link-mes' href='".$ruta."/wp-admin/admin.php?page=crc_admin_verdetallemesbl&id=".$uid."&y=".$value["rbl_year"]."&m=".$value["rbl_mes"]."'>".$agno." - ".$tmes."</a>";


        // $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['rbl_id']."' data-bs-toggle='modal' data-bs-target='#modal-editregnbl'><i class='fa-solid fa-pen'></i></button><button aria-label='".$value["rbl_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-eliminar btn-deleteregnbl' type='button' data-id='".$value['rbl_id']."'><i class='fa-solid fa-x'></i></button></div>";

        // $ublid = $value['ubl_id'];

        $row = array(
            'id' => ($key+1),
            'mes' => $linkmes,
            'utilmes' => $tutilmes,
            'combro' => $tcombro,
            'utilreal' => $tutilreal,
            'comfinal' => $tcomfinal
          );
        $return_json[] = $row;

      }
      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
    }

  }

  public function mostrarTablaReferralTotalCuentasS(){
    if(isset($_POST['uid'])){
      global $wpdb;
      $usuariosbl = $wpdb->prefix . 'usuarios_bl';
      $cuentasbl = $wpdb->prefix . 'cuentas_bl';
      $registrosbl = $wpdb->prefix . 'registros_bl';
      $projectsbl = $wpdb->prefix . 'projects_bl';
      $uid = (int) $_POST['uid'];
      // $cid = 1;
      $ruta = get_site_url();
      // $registros = $wpdb->get_results(" SELECT * FROM $registrosbl WHERE rbl_cuenta = $cid AND rbl_status = 1 ORDER BY rbl_year, rbl_mes", ARRAY_A);
      $registros = $wpdb->get_results(" SELECT pbl_comandres, pbl_comtiger, ubl_id, rbl_mes, rbl_year, ROUND(SUM(rbl_utilmes), 2) AS rbl_utilmes_total, ROUND(SUM(rbl_combro), 2) AS rbl_combro_total, ROUND(SUM(rbl_comtra), 2) AS rbl_comtra_total, ROUND(SUM(rbl_salini), 2) AS rbl_salini_total FROM $registrosbl INNER JOIN $cuentasbl ON $registrosbl.rbl_cuenta = $cuentasbl.cbl_id INNER JOIN $usuariosbl ON $cuentasbl.cbl_usuario = $usuariosbl.ubl_id INNER JOIN $projectsbl ON $usuariosbl.ubl_project = $projectsbl.pbl_id WHERE ubl_id = $uid GROUP BY rbl_mes, rbl_year ORDER BY rbl_year, rbl_mes", ARRAY_A);

      $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
      '8' => 'Agosto',
      '9' => 'Septiembre',
      '10' => 'Octubre',
      '11' => 'Noviembre',
      '12' => 'Diciembre' );

      $return_json = array();

      if(count($registros) == 0){

        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
        return;
      }

      foreach ($registros as $key => $value) {

        $utilmes  = (float) $value["rbl_utilmes_total"];
        $comtra  = (float) $value["rbl_comtra_total"];
        $combro  = (float) $value["rbl_combro_total"];
        $salini  = (float) $value["rbl_salini_total"];
        $utilmesfinal = $utilmes - $combro;
        $comtrapor = round(($comtra*100)/$utilmesfinal,2);

        $comandresent = (float) $value["pbl_comandres"];
        $comtigerent = (float) $value["pbl_comtiger"];
        $comfinpor = $comtrapor+$comandresent+$comtigerent;
        $utilafterpor = 100-$comfinpor;
        $utilafter = round($utilmesfinal*($utilafterpor/100),2);
        // if($salini == 0){
        //   $utilrealfinpor = 100;
        // }else{
        //   $utilrealfinpor = round(($utilafter*100)/$salini,2);
        // }
        $utilrealfinpor = round(($utilafter*100)/$salini,2);
        $comandres = round(($utilmesfinal*($comandresent/100)),2);
        $comtiger = round(($utilmesfinal*($comtigerent/100)),2);
        // $utilreal = $utilmes - $combro;
        // $comfinal = ($utilreal*2.5)/100;
        // $tutilreal = "$".number_format($utilreal, 2);
        // $tcomfinal = "$".number_format($comfinal, 2);
        $tutilmes  = "$".number_format($value["rbl_utilmes_total"], 2);
        $tcomtra  = "$".number_format($value["rbl_comtra_total"], 2);
        $tcombro  = "$".number_format($value["rbl_combro_total"], 2);
        $tutilafter = "$".number_format($utilafter, 2);
        $tsaldini  = "$".number_format($value["rbl_salini_total"], 2);
        $tcomandres = "$".number_format($comandres, 2);
        $tcomtiger = "$".number_format($comtiger, 2);
        $mes = (int)$value['rbl_mes'];
        $tmes = $mesesNombre[$mes];
        $agno = (int)$value['rbl_year'];
        $linkmes = "<a class='link-mes' href='".$ruta."/wp-admin/admin.php?page=crc_admin_verdetallemesspebl&id=".$uid."&y=".$value["rbl_year"]."&m=".$value["rbl_mes"]."'>".$agno." - ".$tmes."</a>";

        // $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['rbl_id']."' data-bs-toggle='modal' data-bs-target='#modal-editregnbl'><i class='fa-solid fa-pen'></i></button><button aria-label='".$value["rbl_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-eliminar btn-deleteregnbl' type='button' data-id='".$value['rbl_id']."'><i class='fa-solid fa-x'></i></button></div>";

        // $ublid = $value['ubl_id'];

        $row = array(
            'id' => ($key+1),
            'mes' => $linkmes,
            'utilmes' => $tutilmes,
            'combro' => $tcombro,
            'comtra' => $tcomtra,
            'pcomtra' => $comtrapor."%",
            'utilafter' => $tutilafter,
            'salini' => $tsaldini,
            'putilrealfin' => $utilrealfinpor."%",
            'comtiger' => $tcomtiger,
            'comandres' => $tcomandres
          );
        $return_json[] = $row;

      }
      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
    }

  }

  public function mostrarTablaReferralDetalleMesN(){
    if(isset($_POST['uid'])){
      global $wpdb;
      $usuariosbl = $wpdb->prefix . 'usuarios_bl';
      $cuentasbl = $wpdb->prefix . 'cuentas_bl';
      $registrosbl = $wpdb->prefix . 'registros_bl';
      $projectsbl = $wpdb->prefix . 'projects_bl';
      $uid = (int) $_POST['uid'];
      $rbl_year = (int) $_POST['year'];
      $rbl_mes = (int) $_POST['mes'];
      // $cid = 1;
      $ruta = get_site_url();
      // $registros = $wpdb->get_results(" SELECT * FROM $registrosbl WHERE rbl_cuenta = $cid AND rbl_status = 1 ORDER BY rbl_year, rbl_mes", ARRAY_A);
      $registros = $wpdb->get_results(" SELECT * FROM $registrosbl INNER JOIN $cuentasbl ON $registrosbl.rbl_cuenta = $cuentasbl.cbl_id INNER JOIN $usuariosbl ON $cuentasbl.cbl_usuario = $usuariosbl.ubl_id INNER JOIN $projectsbl ON $usuariosbl.ubl_project = $projectsbl.pbl_id WHERE ubl_id = $uid AND rbl_year = $rbl_year AND rbl_mes = $rbl_mes ORDER BY rbl_id", ARRAY_A);

      $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
      '8' => 'Agosto',
      '9' => 'Septiembre',
      '10' => 'Octubre',
      '11' => 'Noviembre',
      '12' => 'Diciembre' );

      $return_json = array();

      if(count($registros) == 0){

        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
        return;
      }

      foreach ($registros as $key => $value) {

        $utilmes  = (float) $value["rbl_utilmes"];
        $combro  = (float) $value["rbl_combro"];
        $utilreal = $utilmes - $combro;
        if ($value["pbl_comision"]) {
          $comision = (float) $value["pbl_comision"];
        }else{
          $comision = 0;
        }
        $comfinal = ($utilreal*$comision)/100;
        $tutilreal = "$".number_format($utilreal, 2);
        $tcomfinal = "$".number_format($comfinal, 2);
        $tutilmes  = "$".number_format($value["rbl_utilmes"], 2);
        $tcombro  = "$".number_format($value["rbl_combro"], 2);
        $mes = (int)$value['rbl_mes'];
        $tmes = $mesesNombre[$mes];
        $agno = (int)$value['rbl_year'];

        if ($value["cbl_numero"] == null || $value["cbl_numero"] == "") {
          $tcuenta = "<a class='link-mes' href='".$ruta."/wp-admin/admin.php?page=crc_admin_vercuentabl&id=".$value["cbl_id"]."'>000000</a>";
        }else{
          $tcuenta = "<a class='link-mes' href='".$ruta."/wp-admin/admin.php?page=crc_admin_vercuentabl&id=".$value["cbl_id"]."'>".$value["cbl_numero"]."</a>";
        }

        // $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['rbl_id']."' data-bs-toggle='modal' data-bs-target='#modal-editregnbl'><i class='fa-solid fa-pen'></i></button><button aria-label='".$value["rbl_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-eliminar btn-deleteregnbl' type='button' data-id='".$value['rbl_id']."'><i class='fa-solid fa-x'></i></button></div>";

        // $ublid = $value['ubl_id'];

        $row = array(
            'id' => ($key+1),
            'cuenta'=> $tcuenta,
            'comfinal' => $tcomfinal,
            'utilmes' => $tutilmes,
            'combro' => $tcombro,
            'utilreal' => $tutilreal
          );
        $return_json[] = $row;

      }
      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
    }
  }

  public function mostrarTablaReferralDetalleMesS(){
    if(isset($_POST['uid'])){
      global $wpdb;
      $usuariosbl = $wpdb->prefix . 'usuarios_bl';
      $cuentasbl = $wpdb->prefix . 'cuentas_bl';
      $registrosbl = $wpdb->prefix . 'registros_bl';
      $projectsbl = $wpdb->prefix . 'projects_bl';
      $uid = (int) $_POST['uid'];
      $rbl_year = (int) $_POST['year'];
      $rbl_mes = (int) $_POST['mes'];
      // $cid = 1;
      $ruta = get_site_url();
      // $registros = $wpdb->get_results(" SELECT * FROM $registrosbl WHERE rbl_cuenta = $cid AND rbl_status = 1 ORDER BY rbl_year, rbl_mes", ARRAY_A);
      $registros = $wpdb->get_results(" SELECT * FROM $registrosbl INNER JOIN $cuentasbl ON $registrosbl.rbl_cuenta = $cuentasbl.cbl_id INNER JOIN $usuariosbl ON $cuentasbl.cbl_usuario = $usuariosbl.ubl_id INNER JOIN $projectsbl ON $usuariosbl.ubl_project = $projectsbl.pbl_id WHERE ubl_id = $uid AND rbl_year = $rbl_year AND rbl_mes = $rbl_mes ORDER BY rbl_id", ARRAY_A);

      $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
      '8' => 'Agosto',
      '9' => 'Septiembre',
      '10' => 'Octubre',
      '11' => 'Noviembre',
      '12' => 'Diciembre' );

      $return_json = array();

      if(count($registros) == 0){

        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
        return;
      }

      foreach ($registros as $key => $value) {

        $utilmes  = (float) $value["rbl_utilmes"];
        $comtra  = (float) $value["rbl_comtra"];
        $combro  = (float) $value["rbl_combro"];
        $salini  = (float) $value["rbl_salini"];
        $utilmesfinal = $utilmes - $combro;
        $comtrapor = round(($comtra*100)/$utilmesfinal,2);

        $comandresent = (float) $value["pbl_comandres"];
        $comtigerent = (float) $value["pbl_comtiger"];
        $comfinpor = $comtrapor+$comandresent+$comtigerent;
        $utilafterpor = 100-$comfinpor;
        $utilafter = round($utilmesfinal*($utilafterpor/100),2);
        // if($salini == 0){
        //   $utilrealfinpor = 100;
        // }else{
        //   $utilrealfinpor = round(($utilafter*100)/$salini,2);
        // }
        $utilrealfinpor = round(($utilafter*100)/$salini,2);
        $comandres = round(($utilmesfinal*($comandresent/100)),2);
        $comtiger = round(($utilmesfinal*($comtigerent/100)),2);
        // $utilreal = $utilmes - $combro;
        // $comfinal = ($utilreal*2.5)/100;
        // $tutilreal = "$".number_format($utilreal, 2);
        // $tcomfinal = "$".number_format($comfinal, 2);
        $tutilmes  = "$".number_format($value["rbl_utilmes"], 2);
        $tcomtra  = "$".number_format($value["rbl_comtra"], 2);
        $tcombro  = "$".number_format($value["rbl_combro"], 2);
        $tutilafter = "$".number_format($utilafter, 2);
        $tsaldini  = "$".number_format($value["rbl_salini"], 2);
        $tcomandres = "$".number_format($comandres, 2);
        $tcomtiger = "$".number_format($comtiger, 2);
        $mes = (int)$value['rbl_mes'];
        $tmes = $mesesNombre[$mes];
        $agno = (int)$value['rbl_year'];

        if ($value["cbl_numero"] == null || $value["cbl_numero"] == "") {
          $tcuenta = "<a class='link-mes' href='".$ruta."/wp-admin/admin.php?page=crc_admin_vercuentablspe&id=".$value["cbl_id"]."'>000000</a>";
        }else{
          $tcuenta = "<a class='link-mes' href='".$ruta."/wp-admin/admin.php?page=crc_admin_vercuentablspe&id=".$value["cbl_id"]."'>".$value["cbl_numero"]."</a>";
        }

        // $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['rbl_id']."' data-bs-toggle='modal' data-bs-target='#modal-editregnbl'><i class='fa-solid fa-pen'></i></button><button aria-label='".$value["rbl_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-eliminar btn-deleteregnbl' type='button' data-id='".$value['rbl_id']."'><i class='fa-solid fa-x'></i></button></div>";

        // $ublid = $value['ubl_id'];

        $row = array(
            'id' => ($key+1),
            'cuenta'=> $tcuenta,
            'comandres' => $tcomandres,
            'utilmes' => $tutilmes,
            'combro' => $tcombro,
            'comtra' => $tcomtra,
            'pcomtra' => $comtrapor."%",
            'utilafter' => $tutilafter,
            'salini' => $tsaldini,
            'putilrealfin' => $utilrealfinpor."%",
            'comtiger' => $tcomtiger
          );
        $return_json[] = $row;

      }
      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
    }
  }

  public function mostrarTablaReferralDetalleMesVar(){
    if(isset($_POST['year'])){
      global $wpdb;
      $registrosvar = $wpdb->prefix . 'registros_var';
      $uid = (int) $_POST['uid'];
      $rvar_year = (int) $_POST['year'];
      $rvar_mes = (int) $_POST['mes'];
      // $cid = 1;
      $ruta = get_site_url();
      // $registros = $wpdb->get_results(" SELECT * FROM $registrosbl WHERE rbl_cuenta = $cid AND rbl_status = 1 ORDER BY rbl_year, rbl_mes", ARRAY_A);
      $registros = $wpdb->get_results(" SELECT * FROM $registrosvar  WHERE rvar_year = $rvar_year AND rvar_mes = $rvar_mes ORDER BY rvar_id", ARRAY_A);

      $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
      '8' => 'Agosto',
      '9' => 'Septiembre',
      '10' => 'Octubre',
      '11' => 'Noviembre',
      '12' => 'Diciembre' );

      $return_json = array();

      if(count($registros) == 0){

        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
        return;
      }

      foreach ($registros as $key => $value) {

        $utilmes  = (float) $value["rvar_cantidad"];
        $tutilmes  = "$".number_format($value["rvar_cantidad"], 2);
        $mes = (int)$value['rvar_mes'];
        $tmes = $mesesNombre[$mes];
        $agno = (int)$value['rvar_year'];

        $fechares = substr($value['rvar_fecha'], 0, 10);

        $texto = $value['rvar_titulo'];
        $texlon = strlen($texto);

        if ($texlon > 25 ) {
          $texto = substr($texlon, 0, 25) . "...";
        }

        // if ($value["cbl_numero"] == null || $value["cbl_numero"] == "") {
        //   $tcuenta = "<a class='link-mes' href='".$ruta."/wp-admin/admin.php?page=crc_admin_vercuentablspe&id=".$value["cbl_id"]."'>000000</a>";
        // }else{
        //   $tcuenta = "<a class='link-mes' href='".$ruta."/wp-admin/admin.php?page=crc_admin_vercuentablspe&id=".$value["cbl_id"]."'>".$value["cbl_numero"]."</a>";
        // }

        $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['rvar_id']."' data-bs-toggle='modal' data-bs-target='#modal-editregvar'><i class='fa-solid fa-pen'></i></button><button aria-label='".$value["rvar_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-eliminar btn-deleteregvar' type='button' data-id='".$value['rvar_id']."'><i class='fa-solid fa-x'></i></button></div>";

        // $ublid = $value['ubl_id'];

        $row = array(
            'id' => ($key+1),
            'cantidad' => $tutilmes,
            'concepto' => $texto,
            'registro' => $fechares,
            'acciones' => $acciones,
          );
        $return_json[] = $row;

      }
      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
    }
  }

  public function mostrarTablaReferralDetalleMesNFT(){
    if(isset($_POST['year'])){
      global $wpdb;
      $registrosnft = $wpdb->prefix . 'registros_nft';
      $pid = (int) $_POST['proyecto'];
      $rnft_year = (int) $_POST['year'];
      $rnft_mes = (int) $_POST['mes'];
      // $cid = 1;
      $ruta = get_site_url();
      // $registros = $wpdb->get_results(" SELECT * FROM $registrosbl WHERE rbl_cuenta = $cid AND rbl_status = 1 ORDER BY rbl_year, rbl_mes", ARRAY_A);
      $registros = $wpdb->get_results(" SELECT * FROM $registrosnft WHERE rnft_proyecto = $pid AND rnft_year = $rnft_year AND rnft_mes = $rnft_mes ORDER BY rnft_fecha ", ARRAY_A);

      $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
      '8' => 'Agosto',
      '9' => 'Septiembre',
      '10' => 'Octubre',
      '11' => 'Noviembre',
      '12' => 'Diciembre' );

      $return_json = array();

      if(count($registros) == 0){

        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();
        return;
      }

      $totalacu = 0;
      $teamacu = 0;
      $personalacu = 0;
      $personalante = 0;
      $statusanterior = 0;

      foreach ($registros as $key => $value) {
        // Si es el primer registro
        if($key == 0){
          $total  = (float) $value["rnft_total"];
          $team  = (float) $value["rnft_team"];
          $personal  = round($total-$team,4);

          $totalacu = $total;
          $teamacu = $team;
          $personalacu = $personal;

          $personaldif = $personal - $personalante ;

          $ttotal   = number_format($value["rnft_total"], 4);
          $tteam  = number_format($value["rnft_team"], 4);
          // $tpersonal  = number_format($personal, 4)."/".number_format($personalacu, 4);
          $tpersonal  = number_format($personal, 4)."/".number_format($personaldif, 4);

          $personalante = $personal;
          $statusanterior = (int)$value["rnft_status"];
        }else{

          $total  = (float) $value["rnft_total"];
          $team  = (float) $value["rnft_team"];
          $personal  = round($total-$team,4);

          $totalacu = $totalacu + $total;
          $teamacu = $teamacu + $team;
          $personalacu = $personalacu + $personal;

          // Si en algun momento ya hay un registro cerrado anterior, la diferencia vuelve a 0
          if($value["rnft_status"] == $statusanterior){
            $personaldif = $personal - $personalante ;
          }else {
            $personaldif = $personal;
          }

          $ttotal   = number_format($value["rnft_total"], 4);
          $tteam  = number_format($value["rnft_team"], 4);
          // $tpersonal  = number_format($personal, 4)."/".number_format($personalacu, 4);
          $tpersonal  = number_format($personal, 4)."/".number_format($personaldif, 4);

          // Si en algun momento ya hay un registro cerrado anterior, la diferencia vuelve a 0
          // if($value["rnft_status"] == 1){
          //   $personalante = $personal;
          // }else{
          //   $personalante = 0;
          // }

          $personalante = $personal;

          $statusanterior = (int)$value["rnft_status"];

        }

        // $numsemanas = $value["totalsemanas"];

        // $mes = (int)$value['rnft_mes'];
        // $tmes = $mesesNombre[$mes];
        // $agno = (int)$value['rnft_year'];

        // if ($value['nft_tipo']==1) {
        //
        //   switch ($value['rnft_semana']) {
        //     case 1:
        //       $semana = "Semana 1";
        //       break;
        //     case 2:
        //       $semana = "Semana 2";
        //       break;
        //     case 3:
        //       $semana = "Semana 3";
        //       break;
        //     case 4:
        //       $semana = "Semana 4";
        //       break;
        //     case 5:
        //       $semana = "Semana 5";
        //       break;
        //     default:
        //       $semana = "Mensual";
        //       break;
        //   }
        //   $periodo = $tmes." - ".$semana;
        // }else{
        //   $periodo = $tmes." - Mensual";
        // }

        if($value['rnft_status']==1){
          $status = "Abierto";
        }else{
          $status = "Cerrado";
        }

        $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-editar' type='button' data-id='".$value['rnft_id']."' data-bs-toggle='modal' data-bs-target='#modal-editregnft'><i class='fa-solid fa-pen'></i></button><button aria-label='".$value["rnft_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='btn microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button><button class='btn btn-eliminar btn-deleteregnft' type='button' data-id='".$value['rnft_id']."'><i class='fa-solid fa-x'></i></button></div>";

        // $ublid = $value['ubl_id'];

        $row = array(
            'id' => ($key+1),
            'total' => $ttotal,
            'team' => $tteam,
            'personal' => $tpersonal,
            'acciones' => $acciones,
            'status' => $status
          );
        $return_json[] = $row;

      }
      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
    }
  }

  public function mostrarTablaReferralVarMes(){
    global $wpdb;
    $registrosvar = $wpdb->prefix . 'registros_var';
    // $cid = 1;
    $ruta = get_site_url();
    // $registros = $wpdb->get_results(" SELECT * FROM $registrosbl WHERE rbl_cuenta = $cid AND rbl_status = 1 ORDER BY rbl_year, rbl_mes", ARRAY_A);
    $registros = $wpdb->get_results(" SELECT rvar_mes, rvar_year, ROUND(SUM(rvar_cantidad), 2) AS rvar_cantidadtotal FROM $registrosvar GROUP BY rvar_year, rvar_mes ORDER BY rvar_year, rvar_mes DESC", ARRAY_A);

    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );

    $return_json = array();

    if(count($registros) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

    foreach ($registros as $key => $value) {

      $utilmes  = (float) $value["rvar_cantidadtotal"];
      $tutilmes  = "$".number_format($value["rvar_cantidadtotal"], 2);
      $mes = (int)$value['rvar_mes'];
      $tmes = $mesesNombre[$mes];
      $agno = (int)$value['rvar_year'];

      $tcuenta = "<a class='link-mes' href='".$ruta."/wp-admin/admin.php?page=crc_admin_verdetallemesvar&m=".$mes."&y=".$agno."'>".$tutilmes."</a>";

      $acciones = "<div class='btn-group btn-grupo'><button class='btn btn-addregistrovar' type='button' data-mes='".$value['rvar_mes']."' data-year='".$value['rvar_year']."' data-bs-toggle='modal' data-bs-target='#modal-addregvar'><i class='fa-solid fa-sack-dollar'></i></button></div>";

      // $ublid = $value['ubl_id'];

      $row = array(
          'id' => ($key+1),
          'agno'=> $agno,
          'mes' => $tmes,
          'total' => $tcuenta,
          'acciones' => $acciones
        );
      $return_json[] = $row;

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();

  }

  public function referral_registraruser(){
    if(isset($_POST['nombre'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'usuarios_bl';

      date_default_timezone_set("America/Mexico_City");

      $nombre = $_POST['nombre'];
      $apellidos = $_POST['apellidos'];
      $email = $_POST['email'];
      $tipo = (int)$_POST['tipo'];
      $project = (int)$_POST['project'];
      // $fecha_retiro = date("Y-m-d");

      $datos = array(
          'ubl_nombre'=>$nombre,
          'ubl_apellidos'=>$apellidos,
          'ubl_correo'=>$email,
          'ubl_status'=>1,
          'ubl_tipo'=>$tipo,
          'ubl_notas'=>sanitize_text_field($_POST['notas']),
          'ubl_project'=>$project
          );

      $formato = array(
      '%s',
      '%s',
      '%s',
      '%d',
      '%d',
      '%s',
      '%d'
      );

      $resultado =  $wpdb->insert($tabla, $datos, $formato);

      if($resultado==1){

        $respuesta = array(
          'respuesta'=>1
        );
      }else{
        $respuesta=array(
          'respuesta'=>'error'
        );
      }

    }
    die(json_encode($respuesta));
  }

  public function referral_editaruser(){
    if(isset($_POST['id'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'usuarios_bl';

      date_default_timezone_set("America/Mexico_City");

      $id = (int)$_POST['id'];
      $nombre = $_POST['nombre'];
      $apellidos = $_POST['apellidos'];
      $email = $_POST['email'];
      // $tipo = (int)$_POST['tipo'];

      $datos = array(
          'ubl_nombre'=>$nombre,
          'ubl_apellidos'=>$apellidos,
          'ubl_correo'=>$email,
          'ubl_status'=>1,
          'ubl_notas'=>sanitize_text_field($_POST['notas'])
          );

      $formato = array(
      '%s',
      '%s',
      '%s',
      '%d',
      '%s'
      );

      $donde = [
        'ubl_id' => $id
      ];

      $donde_formato = [
        '%d'
      ];

      $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

        if($actualizar !== false){
          if( $actualizar != 0){

            $respuesta = array(
              'respuesta'=>1
            );
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }
        }else{
          $respuesta=array(
            'respuesta'=>'error'
          );
        }
    }

    die(json_encode($respuesta));
  }

  public function referral_agregarcuenta(){
    if(isset($_POST['nombre'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'cuentas_bl';

      date_default_timezone_set("America/Tijuana");

      $uid = $_POST['uid'];
      $nombre = $_POST['nombre'];
      $numero = $_POST['numero'];
      $tipo = (int)$_POST['tipo'];
      // $fecha_retiro = date("Y-m-d");

      $datos = array(
          'cbl_nombre'=>$nombre,
          'cbl_usuario'=>$uid,
          'cbl_status'=>1,
          'cbl_tipo'=>$tipo,
          'cbl_notas'=>sanitize_text_field($_POST['notas']),
          'cbl_numero'=>$numero,
          );

      $formato = array(
      '%s',
      '%d',
      '%d',
      '%d',
      '%s',
      '%s'
      );

      $resultado =  $wpdb->insert($tabla, $datos, $formato);

      if($resultado==1){

        $respuesta = array(
          'respuesta'=>1
        );
      }else{
        $respuesta=array(
          'respuesta'=>'error'
        );
      }

    }
    die(json_encode($respuesta));
  }

  public function referral_agregarnftproject(){
    if(isset($_POST['nombre'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'projects_nft';

      date_default_timezone_set("America/Tijuana");

      // $nid = $_POST['nid'];
      $nombre = $_POST['nombre'];
      $numero = $_POST['numero'];
      $tipo = (int)$_POST['tipo'];
      $color = (int)$_POST['color'];
      // $fecha_retiro = date("Y-m-d");

      $datos = array(
          'nft_nombre'=>$nombre,
          'nft_status'=>1,
          'nft_tipo'=>$tipo,
          'nft_notas'=>sanitize_text_field($_POST['notas']),
          'nft_numero'=>"000000",
          'nft_color'=>$color,
          'nft_imagen'=>""
          );

      $formato = array(
      '%s',
      '%d',
      '%d',
      '%s',
      '%s',
      '%d',
      '%s'
      );

      $resultado =  $wpdb->insert($tabla, $datos, $formato);

      if($resultado==1){

        $respuesta = array(
          'respuesta'=>1
        );
      }else{
        $respuesta=array(
          'respuesta'=>'error'
        );
      }

    }
    die(json_encode($respuesta));
  }

  public function referral_agregarblproject(){
    if(isset($_POST['nombre'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'projects_bl';

      date_default_timezone_set("America/Tijuana");

      // $nid = $_POST['nid'];
      $nombre = $_POST['nombre'];
      $comision = $_POST['comision'];
      $comandres = $_POST['comandres'];
      $comtiger = $_POST['comtiger'];
      $tipo = (int)$_POST['tipo'];
      $color = (int)$_POST['color'];
      // $fecha_retiro = date("Y-m-d");

      $datos = array(
          'pbl_nombre'=>$nombre,
          'pbl_status'=>1,
          'pbl_tipo'=>$tipo,
          'pbl_notas'=>sanitize_text_field($_POST['notas']),
          'pbl_comision'=>$comision,
          'pbl_comtiger'=>$comtiger,
          'pbl_comandres'=>$comandres,
          'pbl_color'=>$color
          );

      $formato = array(
      '%s',
      '%d',
      '%d',
      '%s',
      '%f',
      '%f',
      '%f',
      '%d'
      );

      $resultado =  $wpdb->insert($tabla, $datos, $formato);

      if($resultado==1){

        $respuesta = array(
          'respuesta'=>1
        );
      }else{
        $respuesta=array(
          'respuesta'=>'error'
        );
      }

    }
    die(json_encode($respuesta));
  }

  public function referral_editarcuenta(){
    if(isset($_POST['cid'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'cuentas_bl';

      date_default_timezone_set("America/Tijuana");

      $id = (int)$_POST['cid'];
      $nombre = $_POST['nombre'];
      $numero = $_POST['numero'];
      $notas = $_POST['notas'];


      $datos = array(
          'cbl_nombre'=>$nombre,
          'cbl_notas'=>sanitize_text_field($_POST['notas']),
          'cbl_numero'=>$numero,
          );

      $formato = array(
      '%s',
      '%s',
      '%s'
      );

      $donde = [
        'cbl_id' => $id
      ];

      $donde_formato = [
        '%d'
      ];

      $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

        if($actualizar !== false){
          if( $actualizar != 0){

            $respuesta = array(
              'respuesta'=>1
            );
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }
        }else{
          $respuesta=array(
            'respuesta'=>'error'
          );
        }
    }

    die(json_encode($respuesta));
  }

  public function referral_editarproyectonft(){
    if(isset($_POST['nid'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'projects_nft';

      date_default_timezone_set("America/Tijuana");

      $id = (int)$_POST['nid'];
      $nombre = $_POST['nombre'];
      $color = (int)$_POST['color'];
      $notas = $_POST['notas'];

      $registros1 = $wpdb->get_results(" SELECT * FROM $tabla WHERE nft_id = $id ", ARRAY_A);

      $fotourl = $registros1[0]["nft_imagen"];

      if ($_FILES['imagen']['name'] != '' && !empty($_FILES['imagen'])) { // Valida que no venga vacia la imagen...
        // Haz más validaciones lado servidor que sea el formato que vas a aceptar como en el javascript JPG o el que tu desees...
          if ($_FILES['imagen']['type'] == 'image/jpeg' ) {
            // Valida los datos des inputs
            // Tú SQL lo mandas aquí de preferencia con PDO
            // Luego de que alamacenes los datos mandas la imagen al directorio
            $numero_aleatorio = rand(100,999);
            $nombre_img = 'avatarnft_' .$numero_aleatorio . ".jpeg";
            // las carpetas deben estar creadas si no es asi te dara un error, si no estan creadas indaga sobre como crear directorios en php.
            // ruta final de la imagen. Esta deberias de guardarla en un campo de tu BD para poderla mostrar en algun otro lugar
            $destino = ABSPATH . "/wp-content/uploads/".$nombre_img;

            move_uploaded_file($_FILES['imagen']['tmp_name'], $destino);
            $imagen = $nombre_img;

          } else if ($_FILES['imagen']['type'] == 'image/png') {
            // Valida los datos des inputs
            // Tú SQL lo mandas aquí de preferencia con PDO
            // Luego de que alamacenes los datos mandas la imagen al directorio
            $numero_aleatorio = rand(100,999);
            $nombre_img = 'avatarnft_' .$numero_aleatorio. ".png";
            // las carpetas deben estar creadas si no es asi te dara un error, si no estan creadas indaga sobre como crear directorios en php.
            // ruta final de la imagen. Esta deberias de guardarla en un campo de tu BD para poderla mostrar en algun otro lugar
            $destino = ABSPATH . "/wp-content/uploads/".$nombre_img;
            move_uploaded_file($_FILES['imagen']['tmp_name'], $destino);
            $imagen = $nombre_img;

          }else if ($_FILES['imagen']['type'] == 'image/jpg') {
            // Valida los datos des inputs
            // Tú SQL lo mandas aquí de preferencia con PDO
            // Luego de que alamacenes los datos mandas la imagen al directorio
            $numero_aleatorio = rand(100,999);
            $nombre_img = 'avatarnft_' .$numero_aleatorio. ".jpg";
            // las carpetas deben estar creadas si no es asi te dara un error, si no estan creadas indaga sobre como crear directorios en php.
            // ruta final de la imagen. Esta deberias de guardarla en un campo de tu BD para poderla mostrar en algun otro lugar
            $destino = ABSPATH . "/wp-content/uploads/".$nombre_img;
            move_uploaded_file($_FILES['imagen']['tmp_name'], $destino);
            $imagen = $nombre_img;

          }else {
            $imagen = "a";
          }

      }else{
        $imagen = "s";
      }

      // Move the file
      // if (move_uploaded_file($_FILES["file"]["tmp_name"], ABSPATH . "/wp-content/avatar-" .$id)) {
      // 	echo ABSPATH ."/wp-content/avatar-".$id;
      // }

      $datos = array(
          'nft_nombre'=>$nombre,
          'nft_notas'=>sanitize_text_field($_POST['notas']),
          'nft_color'=>$color,
          'nft_imagen'=>$imagen
          );

      $formato = array(
      '%s',
      '%s',
      '%d',
      '%s'
      );

      $donde = [
        'nft_id' => $id
      ];

      $donde_formato = [
        '%d'
      ];

      $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

        if($actualizar !== false){
          if( $actualizar != 0){

            $respuesta = array(
              'respuesta'=>1
            );
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }
        }else{
          $respuesta=array(
            'respuesta'=>'error'
          );
        }
    }

    die(json_encode($respuesta));
  }

  public function referral_editarproyectobl(){
    if(isset($_POST['pid'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'projects_bl';

      date_default_timezone_set("America/Tijuana");

      $id = (int)$_POST['pid'];
      $nombre = $_POST['nombre'];
      $comision = (float)$_POST['comision'];
      $comandres = (float)$_POST['comandres'];
      $comtiger = (float)$_POST['comtiger'];
      $color = (int)$_POST['color'];
      $notas = $_POST['notas'];


      $datos = array(
          'pbl_nombre'=>$nombre,
          'pbl_comision'=>$comision,
          'pbl_comandres'=>$comandres,
          'pbl_comtiger'=>$comtiger,
          'pbl_notas'=>sanitize_text_field($_POST['notas']),
          'pbl_color'=>$color
          // 'nft_numero'=>$numero,
          );

      $formato = array(
      '%s',
      '%f',
      '%f',
      '%f',
      '%s',
      '%d'
      );

      $donde = [
        'pbl_id' => $id
      ];

      $donde_formato = [
        '%d'
      ];

      $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

        if($actualizar !== false){
          if( $actualizar != 0){

            $respuesta = array(
              'respuesta'=>1
            );
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }
        }else{
          $respuesta=array(
            'respuesta'=>'error'
          );
        }
    }

    die(json_encode($respuesta));
  }

  public function referral_agregarregistro(){
    if(isset($_POST['cid'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'registros_bl';

      date_default_timezone_set("America/Tijuana");

      $cid = $_POST['cid'];
      $mes = (int)$_POST['mes'];
      $year = (int)$_POST['year'];
      $utilmes = (float)$_POST['utilmes'];
      $combro = (float)$_POST['combro'];
      // $fecha_retiro = date("Y-m-d");

      $datos = array(
          'rbl_cuenta'=>$cid,
          'rbl_mes'=>$mes,
          'rbl_year'=>$year,
          'rbl_utilmes'=>$utilmes,
          'rbl_combro'=>$combro,
          'rbl_comtra'=>0,
          'rbl_salini'=>0,
          'rbl_status'=>1,
          'rbl_notas'=>sanitize_text_field($_POST['notas'])
          );

      $formato = array(
      '%d',
      '%d',
      '%d',
      '%f',
      '%f',
      '%f',
      '%f',
      '%d',
      '%s'
      );

      $resultado =  $wpdb->insert($tabla, $datos, $formato);

      if($resultado==1){

        $respuesta = array(
          'respuesta'=>1
        );
      }else{
        $respuesta=array(
          'respuesta'=>'error'
        );
      }

    }
    die(json_encode($respuesta));
  }

  public function referral_agregarregistrospe(){
    if(isset($_POST['cid'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'registros_bl';

      date_default_timezone_set("America/Tijuana");

      $cid = $_POST['cid'];
      $mes = (int)$_POST['mes'];
      $year = (int)$_POST['year'];
      $utilmes = (float)$_POST['utilmes'];
      $combro = (float)$_POST['combro'];
      $comtra = (float)$_POST['comtra'];
      $salini = (float)$_POST['salini'];
      // $fecha_retiro = date("Y-m-d");

      $datos = array(
          'rbl_cuenta'=>$cid,
          'rbl_mes'=>$mes,
          'rbl_year'=>$year,
          'rbl_utilmes'=>$utilmes,
          'rbl_combro'=>$combro,
          'rbl_comtra'=>$comtra,
          'rbl_salini'=>$salini,
          'rbl_status'=>1,
          'rbl_notas'=>sanitize_text_field($_POST['notas'])
          );

      $formato = array(
      '%d',
      '%d',
      '%d',
      '%f',
      '%f',
      '%f',
      '%f',
      '%d',
      '%s'
      );

      $resultado =  $wpdb->insert($tabla, $datos, $formato);

      if($resultado==1){

        $respuesta = array(
          'respuesta'=>1
        );
      }else{
        $respuesta=array(
          'respuesta'=>'error'
        );
      }

    }
    die(json_encode($respuesta));
  }

  public function referral_agregarregistronft(){
    if(isset($_POST['nid'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'registros_nft';

      date_default_timezone_set("America/Tijuana");

      $nid = $_POST['nid'];
      $mes = (int)$_POST['mes'];
      $year = (int)$_POST['year'];
      $semana = (int)$_POST['semana'];
      $total = (float)$_POST['total'];
      $team = (float)$_POST['team'];
      // $fecha_retiro = date("Y-m-d");

      $datos = array(
          'rnft_proyecto'=>$nid,
          'rnft_mes'=>$mes,
          'rnft_year'=>$year,
          'rnft_semana'=>$semana,
          'rnft_total'=>$total,
          'rnft_team'=>$team,
          'rnft_status'=>1,
          'rnft_notas'=>sanitize_text_field($_POST['notas'])
          );

      $formato = array(
      '%d',
      '%d',
      '%d',
      '%d',
      '%f',
      '%f',
      '%d',
      '%s'
      );

      $resultado =  $wpdb->insert($tabla, $datos, $formato);

      if($resultado==1){

        $respuesta = array(
          'respuesta'=>1
        );
      }else{
        $respuesta=array(
          'respuesta'=>'error'
        );
      }

    }
    die(json_encode($respuesta));
  }

  public function referral_agregarregistrovar(){
    global $wpdb;
    $tabla = $wpdb->prefix.'registros_var';

    date_default_timezone_set("America/Tijuana");

    $mes = (int)$_POST['mes'];
    $year = (int)$_POST['year'];
    $cantidad = (float)$_POST['cantidad'];
    $nombre = $_POST['titulo'];
    // $fecha_retiro = date("Y-m-d");

    $datos = array(
        'rvar_mes'=>$mes,
        'rvar_year'=>$year,
        'rvar_titulo'=>$nombre,
        'rvar_cantidad'=>$cantidad,
        'rvar_status'=>1,
        'rvar_notas'=>sanitize_text_field($_POST['notas'])
        );

    $formato = array(
    '%d',
    '%d',
    '%s',
    '%f',
    '%d',
    '%s'
    );

    $resultado =  $wpdb->insert($tabla, $datos, $formato);

    if($resultado==1){

      $respuesta = array(
        'respuesta'=>1
      );
    }else{
      $respuesta=array(
        'respuesta'=>'error'
      );
    }

    die(json_encode($respuesta));
  }

  public function referral_agregarretironft(){
    if(isset($_POST['nid'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'retiros_nft';
      $tabla1 = $wpdb->prefix.'registros_nft';

      date_default_timezone_set("America/Tijuana");

      $nid = $_POST['nid'];
      $total = (float)$_POST['total'];
      $usdval = (float)$_POST['usdval'];
      $ultreg = (int)$_POST['cierre'];
      $fecha_retiro = $_POST['fecharetiro'];
      // $fecha_retiro = date("Y-m-d");

      $datos = array(
          'rtnft_cantidad'=>$total,
          'rtnft_status'=>1,
          'rtnft_notas'=>sanitize_text_field($_POST['notas']),
          'rtnft_proyecto'=>$nid,
          'rtnft_usdactual'=>$usdval,
          'rtnft_fecha_retiro'=>$fecha_retiro
          );

      $formato = array(
      '%f',
      '%d',
      '%s',
      '%d',
      '%f',
      '%s'
      );

      $resultado =  $wpdb->insert($tabla, $datos, $formato);

      if($resultado==1){

        $retiro = $wpdb->insert_id;

        $datos1 = array(
          'rnft_status'=>0
          );

        $formato1 = array(
          '%d'
          );

        $donde1 = [
          'rnft_proyecto' => $nid,
          'rnft_status'=>1
        ];

        $donde_formato1 = [
          '%d',
          '%d'
        ];


        $datos2 = array(
          'rnft_retiro'=>$retiro
          );

        $formato2 = array(
          '%d'
          );

        $donde2 = [
          'rnft_id' => $ultreg
        ];

        $donde_formato2 = [
          '%d'
        ];

        $actualizar = $wpdb->update($tabla1, $datos1, $donde1, $formato1, $donde_formato1);
        $actualizar2 = $wpdb->update($tabla1, $datos2, $donde2, $formato2, $donde_formato2);

          if($actualizar !== false){
            if( $actualizar != 0){
              $respuesta = array(
                'respuesta'=>1
              );
            }else{
              $respuesta=array(
                'respuesta'=>$actualizar
              );
            }
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }

      }else{
        $respuesta=array(
          'respuesta'=>'error'
        );
      }

    }
    die(json_encode($respuesta));
  }

  public function referral_editarregistro(){
    if(isset($_POST['rid'])){


      if ($_POST['tipo'] == 1) {
        global $wpdb;
        $tabla = $wpdb->prefix.'registros_bl';

        date_default_timezone_set("America/Tijuana");

        $id = (int)$_POST['rid'];
        $mes = (int)$_POST['mes'];
        $year = (int)$_POST['year'];
        $utilmes = (float)$_POST['utilmes'];
        $comtra = (float)$_POST['comtra'];
        $combro = (float)$_POST['combro'];
        $salini = (float)$_POST['salini'];

        $datos = array(
          'rbl_mes'=>$mes,
          'rbl_year'=>$year,
          'rbl_utilmes'=>$utilmes,
          'rbl_combro'=>$combro,
          'rbl_comtra'=>$comtra,
          'rbl_salini'=>$salini,
          'rbl_notas'=>sanitize_text_field($_POST['notas'])
            );

        $formato = array(
        '%d',
        '%d',
        '%f',
        '%f',
        '%f',
        '%f',
        '%s'
        );

        $donde = [
          'rbl_id' => $id
        ];

        $donde_formato = [
          '%d'
        ];

        $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

          if($actualizar !== false){
            if( $actualizar != 0){

              $respuesta = array(
                'respuesta'=>1
              );
            }else{
              $respuesta=array(
                'respuesta'=>$actualizar
              );
            }
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }
      }else {
        global $wpdb;
        $tabla = $wpdb->prefix.'registros_bl';

        date_default_timezone_set("America/Tijuana");

        $id = (int)$_POST['rid'];
        $mes = (int)$_POST['mes'];
        $year = (int)$_POST['year'];
        $utilmes = (float)$_POST['utilmes'];
        $combro = (float)$_POST['combro'];

        $datos = array(
          'rbl_mes'=>$mes,
          'rbl_year'=>$year,
          'rbl_utilmes'=>$utilmes,
          'rbl_combro'=>$combro,
          'rbl_notas'=>sanitize_text_field($_POST['notas'])
            );

        $formato = array(
        '%d',
        '%d',
        '%f',
        '%f',
        '%s'
        );

        $donde = [
          'rbl_id' => $id
        ];

        $donde_formato = [
          '%d'
        ];

        $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

          if($actualizar !== false){
            if( $actualizar != 0){

              $respuesta = array(
                'respuesta'=>1
              );
            }else{
              $respuesta=array(
                'respuesta'=>'error'
              );
            }
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }
      }


    }

    die(json_encode($respuesta));
  }

  public function referral_editarregistronft(){
    if(isset($_POST['rid'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'registros_nft';

      date_default_timezone_set("America/Tijuana");

      $id = (int)$_POST['rid'];
      $mes = (int)$_POST['mes'];
      $year = (int)$_POST['year'];
      $semana = (int)$_POST['semana'];
      $total = (float)$_POST['total'];
      $team = (float)$_POST['team'];
      $status = (int)$_POST['status'];

      $datos = array(
        'rnft_mes'=>$mes,
        'rnft_year'=>$year,
        'rnft_semana'=>$semana,
        'rnft_total'=>$total,
        'rnft_team'=>$team,
        'rnft_status'=>$status,
        'rnft_notas'=>sanitize_text_field($_POST['notas'])
        );

      $formato = array(
        '%d',
        '%d',
        '%d',
        '%f',
        '%f',
        '%d',
        '%s'
        );

      $donde = [
        'rnft_id' => $id
      ];

      $donde_formato = [
        '%d'
      ];

      $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

        if($actualizar !== false){
          if( $actualizar != 0){
            $respuesta = array(
              'respuesta'=>1
            );
          }else{
            $respuesta=array(
              'respuesta'=>$actualizar
            );
          }
        }else{
          $respuesta=array(
            'respuesta'=>'error'
          );
        }

        die(json_encode($respuesta));
    }
  }

  public function referral_editarretironft(){
    if(isset($_POST['rid'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'retiros_nft';

      date_default_timezone_set("America/Tijuana");

      $id = (int)$_POST['rid'];
      $total = (float)$_POST['total'];
      $usdval = (float)$_POST['usdval'];
      $fecha_retiro = $_POST['fecharetiro'];

      // $fecha_retiro = date("Y-m-d");

      $datos = array(
          'rtnft_cantidad'=>$total,
          'rtnft_notas'=>sanitize_text_field($_POST['notas']),
          'rtnft_usdactual'=>$usdval,
          'rtnft_fecha_retiro'=>$fecha_retiro,
          );

      $formato = array(
      '%f',
      '%s',
      '%f',
      '%s'
      );

      $donde = [
        'rtnft_id' => $id
      ];

      $donde_formato = [
        '%d'
      ];

      $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

        if($actualizar !== false){
          if( $actualizar != 0){

            $respuesta = array(
              'respuesta'=>1
            );
          }else{
            $respuesta=array(
              'respuesta'=>'error'
            );
          }
        }else{
          $respuesta=array(
            'respuesta'=>'error'
          );
        }
    }

    die(json_encode($respuesta));
  }

  public function referral_editarregistrovar(){
    if(isset($_POST['rid'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'registros_var';

      date_default_timezone_set("America/Tijuana");

      $id = (int)$_POST['rid'];
      $mes = (int)$_POST['mes'];
      $year = (int)$_POST['year'];
      $titulo = $_POST['titulo'];
      $notas = $_POST['notas'];
      $total = (float)$_POST['total'];

      $datos = array(
        'rvar_mes'=>$mes,
        'rvar_year'=>$year,
        'rvar_titulo'=>$titulo,
        'rvar_cantidad'=>$total,
        'rvar_notas'=>sanitize_text_field($_POST['notas'])
        );

      $formato = array(
        '%d',
        '%d',
        '%s',
        '%f',
        '%s'
        );

      $donde = [
        'rvar_id' => $id
      ];

      $donde_formato = [
        '%d'
      ];

      $actualizar = $wpdb->update($tabla, $datos, $donde, $formato, $donde_formato);

        if($actualizar !== false){
          if( $actualizar != 0){
            $respuesta = array(
              'respuesta'=>1
            );
          }else{
            $respuesta=array(
              'respuesta'=>$actualizar
            );
          }
        }else{
          $respuesta=array(
            'respuesta'=>'error'
          );
        }

        die(json_encode($respuesta));
    }
  }

  public function referral_borrarregistro(){
    if(isset($_POST['rid'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'registros_bl';

      date_default_timezone_set("America/Tijuana");

      $id = (int)$_POST['rid'];
      $resultado = $wpdb->delete($tabla, array('rbl_id'=>$id), array('%d'));

        if($resultado==1){
          $respuesta = array(
            'respuesta'=>1,
            'id'=>$id_codigo
          );
        }else{
          $respuesta=array(
            'respuesta'=>'error'
          );
        }
    }

    die(json_encode($respuesta));
  }

  public function referral_borrarregistronft(){
    if(isset($_POST['nid'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'registros_nft';

      date_default_timezone_set("America/Tijuana");

      $id = (int)$_POST['nid'];
      $resultado = $wpdb->delete($tabla, array('rnft_id'=>$id), array('%d'));

        if($resultado==1){
          $respuesta = array(
            'respuesta'=>1,
            'id'=>$id_codigo
          );
        }else{
          $respuesta=array(
            'respuesta'=>'error'
          );
        }
    }

    die(json_encode($respuesta));
  }

  public function referral_borrarregistrovar(){
    if(isset($_POST['rid'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'registros_var';

      date_default_timezone_set("America/Tijuana");

      $id = (int)$_POST['rid'];
      $resultado = $wpdb->delete($tabla, array('rvar_id'=>$id), array('%d'));

        if($resultado==1){
          $respuesta = array(
            'respuesta'=>1,
            'id'=>$id_codigo
          );
        }else{
          $respuesta=array(
            'respuesta'=>'error'
          );
        }
    }

    die(json_encode($respuesta));
  }

  public function referral_borrarretironft(){
    if(isset($_POST['nid'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'retiros_nft';

      date_default_timezone_set("America/Tijuana");

      $id = (int)$_POST['nid'];
      $resultado = $wpdb->delete($tabla, array('rtnft_id'=>$id), array('%d'));

        if($resultado==1){
          $respuesta = array(
            'respuesta'=>1,
            'id'=>$id_codigo
          );
        }else{
          $respuesta=array(
            'respuesta'=>'error'
          );
        }
    }

    die(json_encode($respuesta));
  }

  public function referral_borrarcuenta(){
    if(isset($_POST['cid'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'cuentas_bl';

      date_default_timezone_set("America/Tijuana");

      $id = (int)$_POST['cid'];
      $resultado = $wpdb->delete($tabla, array('cbl_id'=>$id), array('%d'));

        if($resultado==1){
          $respuesta = array(
            'respuesta'=>1,
            'id'=>$id_codigo
          );
        }else{
          $respuesta=array(
            'respuesta'=>'error'
          );
        }
    }

    die(json_encode($respuesta));
  }

  public function referral_borrarnftproject(){
    if(isset($_POST['nid'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'projects_nft';

      date_default_timezone_set("America/Tijuana");

      $id = (int)$_POST['nid'];
      $resultado = $wpdb->delete($tabla, array('nft_id'=>$id), array('%d'));

        if($resultado==1){
          $respuesta = array(
            'respuesta'=>1,
            'id'=>$id_codigo
          );
        }else{
          $respuesta=array(
            'respuesta'=>'error'
          );
        }
    }

    die(json_encode($respuesta));
  }

  public function referral_borrarblproject(){
    if(isset($_POST['pid'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'projects_bl';

      $id = (int)$_POST['pid'];
      $resultado = $wpdb->delete($tabla, array('pbl_id'=>$id), array('%d'));

        if($resultado==1){
          $respuesta = array(
            'respuesta'=>1,
            'id'=>$id_codigo
          );
        }else{
          $respuesta=array(
            'respuesta'=>'error'
          );
        }
    }

    die(json_encode($respuesta));
  }

  public function referral_borrarusuariobl(){
    if(isset($_POST['uid'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'usuarios_bl';

      date_default_timezone_set("America/Tijuana");

      $id = (int)$_POST['uid'];
      $resultado = $wpdb->delete($tabla, array('ubl_id'=>$id), array('%d'));

        if($resultado==1){
          $respuesta = array(
            'respuesta'=>1,
            'id'=>$id_codigo
          );
        }else{
          $respuesta=array(
            'respuesta'=>'error'
          );
        }
    }

    die(json_encode($respuesta));
  }

  public function generar_codigo(){
    $codigo = '';
    $pattern = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $max = strlen($pattern)-1;
    for($i=0;$i < 6;$i++) $codigo .= $pattern[mt_rand(0,$max)];
    return $codigo;
  }
}
