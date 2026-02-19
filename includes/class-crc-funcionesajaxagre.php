<?php

class CRC_FuncionesajaxAgre{

  public function solicitar_agrdeposito(){
    if(isset($_POST['user'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'depositos_agr';

      date_default_timezone_set("America/Tijuana");

      $cantidad = (float)$_POST['cantidad'];
      $usuario = (int)$_POST['user'];

      $fecha_deposito = date("Y-m-d");
      $codigo = $this->generar_codigo();


      $datos = array(
          'dagr_cantidad'=>$cantidad,
          'dagr_usuario'=>$usuario,
          'dagr_fecha_deposito'=>$fecha_deposito,
          'dagr_codigo'=>$codigo,
          'dagr_status'=>0
          );

      $formato = array(
      '%f',
      '%d',
      '%s',
      '%s',
      '%d'
      );

      $resultado =  $wpdb->insert($tabla, $datos, $formato);

      if($resultado==1){

        //correo de generacion de solicitud de deposito
        $url = home_url('/confirmacion');
        $urlcompleta = $url . '/?code_submitted='.$codigo.'&tipo=agrdep';
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

  public function solicitar_agrretiro(){
    if(isset($_POST['user'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'retiros_agr';

      date_default_timezone_set("America/Tijuana");

      $cantidad = (float)$_POST['cantidad'];
      $usuario = (int)$_POST['user'];

      $fecha_retiro = date("Y-m-d");
      $codigo = $this->generar_codigo();

      $datos = array(
          'ragr_cantidad'=>$cantidad,
          'ragr_usuario'=>$usuario,
          'ragr_fecha_retiro'=>$fecha_retiro,
          'ragr_codigo'=>$codigo,
          'ragr_status'=>0
          );

      $formato = array(
      '%f',
      '%d',
      '%s',
      '%s',
      '%d'
      );

      $resultado =  $wpdb->insert($tabla, $datos, $formato);

      if($resultado==1){

        //correo de generacion de solicitud de retiro
        $url = home_url('/confirmacion');
        $urlcompleta = $url . '/?code_submitted='.$codigo.'&tipo=agrret';
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

  public function traer_datos_agre(){
    if(isset($_POST['id'])){
      global $wpdb;

      date_default_timezone_set("America/Tijuana");

      $id = $_POST['id'];
      $tipo = $_POST['tipo'];

      if ($tipo == 'deposito') {
        $tabla = $wpdb->prefix.'depositos_agr';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE dagr_id = $id ORDER BY dagr_id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){

          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {

          $cantidad = $value['dagr_cantidad'];
          $cantidadreal = $value['dagr_antidad_real'];
          $idmovind = $value['dagr_idmov_ind'];
          $idmovgral = $value['dagr_idmov_gral'];
          $fecha = $value['dagr_fecha_deposito'];
          $fechafin = $value['dagr_fecha_termino'];
          $notas = $value['dagr_notas'];

          $row = array(
              'idmovind' => $idmovind,
              'idmovgral' => $idmovgral,
              'cantidad' => $cantidad,
              'cantidadfin' => $cantidadreal,
              'fecha' => $fecha,
              'fechafin' => $fechafin,
              'notas' => $notas
            );
          $return_json[] = $row;

        }
        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();

      }else {
        $tabla = $wpdb->prefix.'retiros_agr';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE ragr_id = $id ORDER BY ragr_id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){

          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {

          $cantidad = $value['ragr_cantidad'];
          $cantidadreal = $value['ragr_cantidad_real'];
          $idmovind = $value['ragr_idmov_ind'];
          $fecha = $value['ragr_fecha_retiro'];
          $fechafin = $value['ragr_fecha_termino'];
          $notas = $value['ragr_notas'];

          $row = array(
              'idmovind' => $idmovind,
              'cantidad' => $cantidad,
              'cantidadfin' => $cantidadreal,
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

  public function operacion_finalizar_agre(){
    if(isset($_POST['id'])){
      global $wpdb;

      date_default_timezone_set("America/Tijuana");

      $id = $_POST['id'];
      $tipo = $_POST['tipo'];

      if ($tipo == 'deposito') {
        $tabla = $wpdb->prefix.'depositos_agr';

        //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));

        $fechafin = $_POST['fechafin'];
        $fechasol = $_POST['fechasol'];
        $cantidadfin = (float)$_POST['cantidadfin'];

        $datos = [
        'dagr_status'=> 2,
        'dagr_idmov_ind'=> $_POST['idmovind'],
        'dagr_idmov_gral'=> $_POST['idmovgral'],
        'dagr_cantidad_real'=> $cantidadfin,
        'dagr_fecha_deposito' => $fechasol,
        'dagr_fecha_termino' => $fechafin,
        'dagr_notas' => sanitize_text_field($_POST['notas'])
        ];

        $formato = [
          '%d',
          '%s',
          '%s',
          '%f',
          '%s',
          '%s',
          '%s'
        ];

        $donde = [
          'dagr_id' => $id
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
        $tabla = $wpdb->prefix.'retiros_agr';

        //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));

        $fechafin = $_POST['fechafin'];
        $fechasol = $_POST['fechasol'];
        $cantidadfin = (float)$_POST['cantidadfin'];

        $datos = [
        'ragr_status'=> 2,
        'ragr_idmov_ind'=> $_POST['idmovind'],
        'ragr_idmov_gral'=> '',
        'ragr_cantidad_real'=> $cantidadfin,
        'ragr_fecha_retiro' => $fechasol,
        'ragr_fecha_termino' => $fechafin,
        'ragr_notas' => sanitize_text_field($_POST['notas'])
        ];

        $formato = [
          '%d',
          '%s',
          '%s',
          '%f',
          '%s',
          '%s',
          '%s'
        ];

        $donde = [
          'ragr_id' => $id
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

  public function operacion_editar_agre(){
    if(isset($_POST['id'])){
      global $wpdb;

      date_default_timezone_set("America/Tijuana");

      $id = $_POST['id'];
      $tipo = $_POST['tipo'];

      if ($tipo == 'deposito') {
        $tabla = $wpdb->prefix.'depositos_agr';

        //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));

        $fechafin = $_POST['fechafin'];
        $fechasol = $_POST['fechasol'];
        $cantidadfin = (float)$_POST['cantidadfin'];

        $datos = [
        'dagr_status'=> 2,
        'dagr_idmov_ind'=> $_POST['idmovind'],
        'dagr_idmov_gral'=> $_POST['idmovgral'],
        'dagr_cantidad_real'=> $cantidadfin,
        'dagr_fecha_deposito' => $fechasol,
        'dagr_fecha_termino' => $fechafin,
        'dagr_notas' => sanitize_text_field($_POST['notas'])
        ];

        $formato = [
          '%d',
          '%s',
          '%s',
          '%f',
          '%s',
          '%s',
          '%s'
        ];

        $donde = [
          'dagr_id' => $id
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
        $tabla = $wpdb->prefix.'retiros_agr';

        //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));
        $fechafin = $_POST['fechafin'];
        $fechasol = $_POST['fechasol'];
        $cantidadfin = (float)$_POST['cantidadfin'];

        $datos = [
        'ragr_status'=> 2,
        'ragr_idmov_ind'=> $_POST['idmovind'],
        'ragr_cantidad_real'=> $cantidadfin,
        'ragr_fecha_retiro' => $fechasol,
        'ragr_fecha_termino' => $fechafin,
        'ragr_notas' => sanitize_text_field($_POST['notas'])
        ];

        $formato = [
          '%d',
          '%s',
          '%f',
          '%s',
          '%s',
          '%s'
        ];

        $donde = [
          'ragr_id' => $id
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

  public function operacion_cancelar_agre(){
    if(isset($_POST['id'])){
      global $wpdb;

      date_default_timezone_set("America/Tijuana");

      $id = $_POST['id'];
      $tipo = $_POST['tipo'];

      if ($tipo == 'deposito') {
        $tabla = $wpdb->prefix.'depositos_agr';

        //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));
        $fechafin = $_POST['fechafin'];
        $fechasol = $_POST['fechasol'];

        $datos = [
        'dagr_status'=> 3,
        'dagr_fecha_deposito' => $fechasol,
        'dagr_fecha_termino' => $fechafin,
        'dagr_notas' => sanitize_text_field($_POST['notas'])
        ];

        $formato = [
          '%d',
          '%s',
          '%s',
          '%s'
        ];

        $donde = [
          'dagr_id' => $id
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
        $tabla = $wpdb->prefix.'retiros_agr';

        //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));
        $fechafin = $_POST['fechafin'];
        $fechasol = $_POST['fechasol'];

        $datos = [
        'ragr_status'=> 3,
        'ragr_fecha_retiro' => $fechasol,
        'ragr_fecha_termino' => $fechafin,
        'ragr_notas' => sanitize_text_field($_POST['notas'])
        ];

        $formato = [
          '%d',
          '%s',
          '%s',
          '%s'
        ];

        $donde = [
          'ragr_id' => $id
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

  public function crear_opemaster_agre(){
    if(isset($_POST['tipo'])){
      global $wpdb;
      $tipo = $_POST['tipo'];

      if($tipo == 'deposito'){
        $tabla = $wpdb->prefix.'depositos_master_agr';

        $idmov_ind = $_POST['idmov_ind'];
        $idmov_gral = $_POST['idmov_gral'];
        $cantidad = (float)$_POST['cantidad'];
        $cantidad_real = (float)$_POST['cantidad_real'];
        $fecha_deposito = $_POST['fecha_deposito'];
        $fecha_findeposito = $_POST['fecha_findeposito'];
        $notas = sanitize_textarea_field($_POST['notas']);
        $solicitante = (int)$_POST['solicitante'];
        if ($solicitante == 0) {
          $status = 2;
        }else {
          $status = 1;
        }
        $user = (int)$_POST['usuario'];

        $datos = array(
            'dmagr_cantidad'=> $cantidad,
            'dmagr_cantidad_real'=> $cantidad_real,
            'dmagr_fecha_deposito'=> $fecha_deposito,
            'dmagr_status'=> $status,
            'dmagr_idmov_ind'=> $idmov_ind,
            'dmagr_notas'=> $notas,
            'dmagr_idmov_gral'=> $idmov_gral,
            'dmagr_usuario'=>$user,
            'dmagr_fecha_termino'=> $fecha_findeposito,
            );

        $formato = array(
        '%f',
        '%f',
        '%s',
        '%d',
        '%s',
        '%s',
        '%s',
        '%d',
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
        $tabla = $wpdb->prefix.'retiros_master_agr';

        $idmov_ind = $_POST['idmov_ind'];
        $cantidad = (float)$_POST['cantidad_real'];
        $cantidad_real = (float)$_POST['cantidad_real'];
        $fecha_retiro = $_POST['fecha_retiro'];
        $fecha_finretiro = $_POST['fecha_finretiro'];
        $notas = sanitize_textarea_field($_POST['notas']);
        $solicitante = (int)$_POST['solicitante'];
        if ($solicitante == 0) {
          $status = 2;
        }else {
          $status = 1;
        }
        $user = (int)$_POST['usuario'];

        $datos = array(
            'rmagr_cantidad'=> $cantidad,
            'rmagr_cantidad_real'=> $cantidad_real,
            'rmagr_fecha_retiro'=> $fecha_retiro,
            'rmagr_status'=> $status,
            'rmagr_idmov_ind'=> $idmov_ind,
            'rmagr_notas'=> $notas,
            'rmagr_usuario'=>$user,
            'rmagr_fecha_termino'=> $fecha_finretiro,
            );

        $formato = array(
        '%f',
        '%f',
        '%s',
        '%d',
        '%s',
        '%s',
        '%d',
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

  public function traer_datos_agre_mas(){
    if(isset($_POST['id'])){
      global $wpdb;

      date_default_timezone_set("America/Tijuana");

      $id = $_POST['id'];
      $tipo = $_POST['tipo'];

      if ($tipo == 'deposito') {
        $tabla = $wpdb->prefix.'depositos_master_agr';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE dmagr_id = $id ORDER BY dmagr_id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){

          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {

          $cantidad = $value['dmagr_cantidad'];
          $cantidadreal = $value['dmagr_antidad_real'];
          $idmovind = $value['dmagr_idmov_ind'];
          $idmovgral = $value['dmagr_idmov_gral'];
          $fecha = $value['dmagr_fecha_deposito'];
          $fechafin = $value['dmagr_fecha_termino'];
          $notas = $value['dmagr_notas'];

          $row = array(
              'idmovind' => $idmovind,
              'idmovgral' => $idmovgral,
              'cantidad' => $cantidad,
              'cantidadfin' => $cantidadreal,
              'fecha' => $fecha,
              'fechafin' => $fechafin,
              'notas' => $notas
            );
          $return_json[] = $row;

        }
        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();

      }else {
        $tabla = $wpdb->prefix.'retiros_master_agr';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE rmagr_id = $id ORDER BY rmagr_id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){

          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {

          $cantidad = $value['rmagr_cantidad'];
          $cantidadreal = $value['rmagr_cantidad_real'];
          $idmovind = $value['rmagr_idmov_ind'];
          $fecha = $value['rmagr_fecha_retiro'];
          $fechafin = $value['rmagr_fecha_termino'];
          $notas = $value['rmagr_notas'];

          $row = array(
              'idmovind' => $idmovind,
              'cantidad' => $cantidad,
              'cantidadfin' => $cantidadreal,
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

  public function operacion_finalizar_agre_mas(){
    if(isset($_POST['id'])){
      global $wpdb;

      date_default_timezone_set("America/Tijuana");

      $id = $_POST['id'];
      $tipo = $_POST['tipo'];

      if ($tipo == 'deposito') {
        $tabla = $wpdb->prefix.'depositos_master_agr';

        //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));

        $fechafin = $_POST['fechafin'];
        $fechasol = $_POST['fechasol'];
        $cantidadfin = (float)$_POST['cantidadfin'];

        $datos = [
        'dmagr_status'=> 2,
        'dmagr_idmov_ind'=> $_POST['idmovind'],
        'dmagr_idmov_gral'=> $_POST['idmovgral'],
        'dmagr_cantidad_real'=> $cantidadfin,
        'dmagr_fecha_deposito' => $fechasol,
        'dmagr_fecha_termino' => $fechafin,
        'dmagr_notas' => sanitize_text_field($_POST['notas'])
        ];

        $formato = [
          '%d',
          '%s',
          '%s',
          '%f',
          '%s',
          '%s',
          '%s'
        ];

        $donde = [
          'dmagr_id' => $id
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
        $tabla = $wpdb->prefix.'retiros_master_agr';

        //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));

        $fechafin = $_POST['fechafin'];
        $fechasol = $_POST['fechasol'];
        $cantidadfin = (float)$_POST['cantidadfin'];

        $datos = [
        'rmagr_status'=> 2,
        'rmagr_idmov_ind'=> $_POST['idmovind'],
        'rmagr_cantidad_real'=> $cantidadfin,
        'rmagr_fecha_retiro' => $fechasol,
        'rmagr_fecha_termino' => $fechafin,
        'rmagr_notas' => sanitize_text_field($_POST['notas'])
        ];

        $formato = [
          '%d',
          '%s',
          '%f',
          '%s',
          '%s',
          '%s'
        ];

        $donde = [
          'rmagr_id' => $id
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

  public function mostrarTablaAgrHistoDep(){
    global $wpdb;
    $user = wp_get_current_user();
    $userid = $user->ID;
    $depositos = $wpdb->prefix . 'depositos_agr';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT month(dagr_fecha_deposito) AS mes, year(dagr_fecha_deposito) AS agno, dagr_cantidad, dagr_cantidad_real, dagr_status, dagr_fecha_deposito FROM $depositos WHERE dagr_usuario = $userid ", ARRAY_A);
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



      $mes = $value["mes"];
      $tmes = $mesesNombre[$mes];
      $agno = (int)$value['agno'];

      if ($value["dagr_status"] == 0 || $value["dagr_status"] == 1 ) {
        $fechaSeparada = explode("-", $value["dagr_fecha_deposito"]);
        $fechadep = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

        $tcant = number_format($value["dagr_cantidad"], 2, '.', ',');
        $accio = "<span class='accio accio-gray'><i class='fa-solid fa-hourglass'></i> Pendiente</span>";
      }else if($value["dagr_status"] == 2 ) {
        $fechaSeparada = explode("-", $value["dagr_fecha_deposito"]);
        $fechadep = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

        $tcant = number_format($value["dagr_cantidad_real"], 2, '.', ',');
        $accio = "<span class='accio accio-green'><i class='fa-solid fa-circle-check'></i> Autorizado</span>";
      } else{
        $fechaSeparada = explode("-", $value["dagr_fecha_deposito"]);
        $fechadep = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

        $tcant = number_format($value["dagr_cantidad"], 2, '.', ',');
        $accio = "<span class='accio accio-red'><i class='fa-solid fa-ban'></i> Cancelado</span>";
      }


      $row = array(
          'mes' => $fechadep,
          'cantidad'=> "$".$tcant,
          'status'=> $accio
        );
      $return_json[] = $row;

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }

  public function mostrarTablaAgrHistoRet(){
    global $wpdb;
    $user = wp_get_current_user();
    $userid = $user->ID;
    $retiros = $wpdb->prefix . 'retiros_agr';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT month(ragr_fecha_retiro) AS mes, year(ragr_fecha_retiro) AS agno, ragr_cantidad, ragr_cantidad_real, ragr_status, ragr_fecha_retiro FROM $retiros WHERE ragr_usuario = $userid ", ARRAY_A);
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

      $mes = $value["mes"];
      $tmes = $mesesNombre[$mes];
      $agno = (int)$value['agno'];

      if ($value["ragr_status"] == 0 || $value["ragr_status"] == 1 ) {
        $fechaSeparada = explode("-", $value["ragr_fecha_retiro"]);
        $fecharet = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

        $tcant = number_format($value["ragr_cantidad"], 2, '.', ',');
        $accio = "<span class='accio accio-gray'><i class='fa-solid fa-hourglass'></i> Pendiente</span>";
      }else if($value["ragr_status"] == 2 ) {
        $fechaSeparada = explode("-", $value["ragr_fecha_retiro"]);
        $fecharet = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

        $tcant = number_format($value["ragr_cantidad_real"], 2, '.', ',');
        $accio = "<span class='accio accio-green'><i class='fa-solid fa-circle-check'></i> Autorizado</span>";
      } else{
        $fechaSeparada = explode("-", $value["ragr_fecha_retiro"]);
        $fecharet = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

        $tcant = number_format($value["ragr_cantidad"], 2, '.', ',');
        $accio = "<span class='accio accio-red'><i class='fa-solid fa-ban'></i> Cancelado</span>";
      }


      $row = array(
          'mes' => $fecharet,
          'cantidad'=> "$".$tcant,
          'status'=> $accio
        );
      $return_json[] = $row;

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }

  public function mostrarTablaAgrUserHistoDep(){
    global $wpdb;
    $userid = (int) $_POST['user'];
    $depositos = $wpdb->prefix . 'depositos_agr';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT month(dagr_fecha_deposito) AS mes, year(dagr_fecha_deposito) AS agno, dagr_fecha_deposito, dagr_cantidad, dagr_cantidad_real, dagr_status, dagr_fecha_deposito FROM $depositos WHERE dagr_usuario = $userid ORDER BY dagr_fecha_deposito DESC ", ARRAY_A);
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

      $mes = $value["mes"];
      $tmes = $mesesNombre[$mes];
      $agno = (int)$value['agno'];

      if ($value["dagr_status"] == 0 || $value["dagr_status"] == 1 ) {
        $fechaSeparada = explode("-", $value["dagr_fecha_deposito"]);
        $fechadep = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

        $tcant = number_format($value["dagr_cantidad"], 2, '.', ',');
        $accio = "<span class='accio accio-gray'><i class='fa-solid fa-hourglass'></i> Pendiente</span>";
      }else if($value["dagr_status"] == 2 ) {
        $fechaSeparada = explode("-", $value["dagr_fecha_deposito"]);
        $fechadep = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

        $tcant = number_format($value["dagr_cantidad_real"], 2, '.', ',');
        $accio = "<span class='accio accio-green'><i class='fa-solid fa-circle-check'></i> Autorizado</span>";
      } else{
        $fechaSeparada = explode("-", $value["dagr_fecha_deposito"]);
        $fechadep = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

        $tcant = number_format($value["dagr_cantidad"], 2, '.', ',');
        $accio = "<span class='accio accio-red'><i class='fa-solid fa-ban'></i> Cancelado</span>";
      }


      $row = array(
          'mes' => $fechadep,
          'cantidad'=> "$".$tcant,
          'status'=> $accio
        );
      $return_json[] = $row;

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }

  public function mostrarTablaAgrUserHistoRet(){
    global $wpdb;
    $userid = (int) $_POST['user'];
    $retiros = $wpdb->prefix . 'retiros_agr';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT month(ragr_fecha_retiro) AS mes, year(ragr_fecha_retiro) AS agno, ragr_fecha_retiro, ragr_cantidad, ragr_cantidad_real, ragr_status, ragr_fecha_retiro FROM $retiros WHERE ragr_usuario = $userid ORDER BY ragr_fecha_retiro DESC", ARRAY_A);
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

      $mes = $value["mes"];
      $tmes = $mesesNombre[$mes];
      $agno = (int)$value['agno'];

      if ($value["ragr_status"] == 0 || $value["ragr_status"] == 1 ) {
        $fechaSeparada = explode("-", $value["ragr_fecha_retiro"]);
        $fecharet = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

        $tcant = number_format($value["ragr_cantidad"], 2, '.', ',');
        $accio = "<span class='accio accio-gray'><i class='fa-solid fa-hourglass'></i> Pendiente</span>";
      }else if($value["ragr_status"] == 2 ) {
        $fechaSeparada = explode("-", $value["ragr_fecha_retiro"]);
        $fecharet = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

        $tcant = number_format($value["ragr_cantidad_real"], 2, '.', ',');
        $accio = "<span class='accio accio-green'><i class='fa-solid fa-circle-check'></i> Autorizado</span>";
      } else{
        $fechaSeparada = explode("-", $value["ragr_fecha_retiro"]);
        $fecharet = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

        $tcant = number_format($value["ragr_cantidad"], 2, '.', ',');
        $accio = "<span class='accio accio-red'><i class='fa-solid fa-ban'></i> Cancelado</span>";
      }


      $row = array(
          'mes' => $fecharet,
          'cantidad'=> "$".$tcant,
          'status'=> $accio
        );
      $return_json[] = $row;

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }

  public function mostrarAgrTablaHistoDepFull(){
    global $wpdb;
    $user = wp_get_current_user();
    $userid = $user->ID;
    $depositos = $wpdb->prefix . 'depositos_agr';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $depositos WHERE dagr_usuario = $userid ORDER BY dagr_id DESC", ARRAY_A);

    $return_json = array();

    if(count($registros) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

    foreach ($registros as $key => $value) {

      $status = $value["dagr_status"];
      if ($status == 0) {
        $statusc = "Generada";
      }else if($status == 1){
        $statusc = "Confirmada";
      }else if($status == 3){
        $statusc = "Cancelada";
      }else{
        $statusc = "Autorizada";
      }

      if(!$value["dagr_idmov_ind"]){
        $idmov_ind = "";
      }else{
        $idmov_ind = $value["dagr_idmov_ind"];
      }

      if(!$value["dagr_idmov_gral"]){
        $idmov_gral = "";
      }else{
        $idmov_gral = $value["dagr_idmov_gral"];
      }

      if(!$value["dagr_fecha_termino"]){
        $fechafin = "";
      }else{
        $fechafin = $value["dagr_fecha_termino"];
      }

      if(!$value["dagr_cantidad_real"]){
        $cantidadreal = "";
      }else{
        $cantidadreal = "$".number_format($value["dagr_cantidad_real"], 2);
      }

      $cantidad  = "$".number_format($value["dagr_cantidad"], 2);

      $fecha = substr($value["dagr_fecha_deposito"], 0, 10);

      $row = array(
          'id' => ($key+1),
          'cantidad' => $cantidad,
          'cantidadfin' => $cantidadreal,
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

  public function mostrarAgrTablaHistoRetFull(){
    global $wpdb;
    $user = wp_get_current_user();
    $userid = $user->ID;
    $retiros = $wpdb->prefix . 'retiros_agr';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $retiros WHERE ragr_usuario = $userid ORDER BY ragr_id DESC", ARRAY_A);

    $return_json = array();

    if(count($registros) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

    foreach ($registros as $key => $value) {

      $status = $value["ragr_status"];
      if ($status == 0) {
        $statusc = "Generada";
        $acciones = "<button class='btn-finalizar-inac' type='button' name='button'>Finalizar</button>";
      }else if($status == 1){
        $statusc = "Confirmada";
        $acciones = "<input alt='#TB_inline?width=400&inlineId=modal-finret' title='Finalizar un retiro' data-retiro='".$value['ragr_id']."' class='thickbox button button-primary button-large btn-finalizar' type='button' value='Finalizar' />";
      }else if($status == 3){
        $statusc = "Cancelada";
        $acciones = "";
      }else{
        $statusc = "Autorizada";
        $acciones = "<button class='btn-finalizar-inac' type='button' name='button'>Finalizar</button>";
      }

      if(!$value["ragr_idmov_ind"]){
        $idmov_ind = "";
      }else{
        $idmov_ind = $value["ragr_idmov_ind"];
      }

      if(!$value["ragr_idmov_gral"]){
        $idmov_gral = "";
      }else{
        $idmov_gral = $value["ragr_idmov_gral"];
      }

      if(!$value["ragr_fecha_termino"]){
        $fechafin = "";
      }else{
        $fechafin = $value["ragr_fecha_termino"];
      }

      if(!$value["ragr_cantidad_real"]){
        $cantidadreal = "";
      }else{
        $cantidadreal = "$".number_format($value["ragr_cantidad_real"], 2);
      }

      $cantidad  = "$".number_format($value["ragr_cantidad"], 2);

      $fecha = substr($value["ragr_fecha_retiro"], 0, 10);

      $row = array(
          'id' => ($key+1),
          'cantidad' => $cantidad,
          'cantidadfin' => $cantidadreal,
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

  public function mostrarAgrTablaAdminDep(){
    global $wpdb;
    $depositos = $wpdb->prefix . 'depositos_agr';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $depositos ORDER BY dagr_id DESC", ARRAY_A);

    $return_json = array();

    if(count($registros) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

    foreach ($registros as $key => $value) {

      $user = get_userdata( absint( $value["dagr_usuario"] ) );
      $wallet = get_user_meta( $user->ID, 'wallet', true);
      $walletcode = get_user_meta( $user->ID, 'walletcode', true);
      $email = $user->user_email;
      $pais = get_user_meta( $user->ID, 'pais', true);

      if ($user) {
        $nombre = $user->first_name . ' ' .$user->last_name ;
      }else{
        $nombre = 'Usuario no encontrado';
      }

      $status = $value["dagr_status"];
      if ($status == 0) {
        $statusc = "Generada";
        $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindep' data-deposito='".$value['dagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdep' data-deposito='".$value['dagr_id']."'>Cancelar</button>";
      }else if($status == 1){
        $statusc = "Confirmada";
        $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindep' data-deposito='".$value['dagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdep' data-deposito='".$value['dagr_id']."'>Cancelar</button>";
      }else if($status == 3){
        $statusc = "Cancelada";
        $acciones = "<button class='btn-finalizar-inac'>Editar</button>";
      }else{
        $statusc = "Autorizada";
        $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agreditdep' data-deposito='".$value['dagr_id']."'>Editar</button>";
      }

      if(!$value["dagr_idmov_ind"]){
        $idmov_ind = "";
      }else{
        $idmov_ind = $value["dagr_idmov_ind"];
      }

      if(!$value["dagr_idmov_gral"]){
        $idmov_gral = "";
      }else{
        $idmov_gral = $value["dagr_idmov_gral"];
      }

      if(!$value["dagr_fecha_termino"]){
        $fechafin = "";
      }else{
        $fechafin = $value["dagr_fecha_termino"];
      }

      if($value["dagr_cantidad_real"] == 0){
        $cantidadreal = "";
      }else{
        $cantidadreal = "$".number_format($value["dagr_cantidad_real"], 2);
      }

      $fecha = substr($value["dagr_fecha_deposito"], 0, 10);

      $cantidad  = "$".number_format($value["dagr_cantidad"], 2);
      // $interes = $value["interes"]."%";

      $notas = "<button aria-label='".$value["dagr_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";

      $row = array(
          'id' => ($key+1),
          'nombre' => $nombre,
          'cantidad' => $cantidad,
          'cantidadfin' => $cantidadreal,
          'notas' => $notas,
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

  public function mostrarAgrTablaAdminRet(){
    global $wpdb;
    $retiros = $wpdb->prefix . 'retiros_agr';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $retiros ORDER BY ragr_id DESC", ARRAY_A);

    $return_json = array();

    if(count($registros) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

    foreach ($registros as $key => $value) {

      $user = get_userdata( absint( $value["ragr_usuario"] ) );
      $wallet = get_user_meta( $user->ID, 'wallet', true);
      $walletcode = get_user_meta( $user->ID, 'walletcode', true);
      $email = $user->user_email;
      $pais = get_user_meta( $user->ID, 'pais', true);

      if ($user) {
        $nombre = $user->first_name . ' ' .$user->last_name ;
      }else{
        $nombre = 'Usuario no encontrado';
      }

      $cantidad  = "$".number_format($value["ragr_cantidad"], 2);
      $cantidadi  = number_format($value["ragr_cantidad"], 2);

      if(!$value["ragr_cantidad_real"]){
        $cantidadreal = "";
        $cantidadfini = "0.00";
      }else{
        $cantidadreal = "$".number_format($value["ragr_cantidad_real"], 2);
        $cantidadfini = $value["ragr_cantidad_real"];
      }

      if(!$value["ragr_notas"]){
        $notas = "";
      }else{
        $notas = $value["ragr_notas"];
      }

      $status = $value["ragr_status"];
      if ($status == 0) {
        $statusc = "Generada";
        $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfinret' data-retiro='".$value['ragr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancret' data-retiro='".$value['ragr_id']."'>Cancelar</button>";
      }else if($status == 1){
        $statusc = "Confirmada";
        $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfinret' data-retiro='".$value['ragr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancret' data-retiro='".$value['ragr_id']."'>Cancelar</button>";
      }else if($status == 3){
        $statusc = "Cancelada";
        $acciones = "<button class='btn-finalizar-inac'>Editar</button>";
      }else{
        $statusc = "Autorizada";
        $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agreditret' data-retiro='".$value['ragr_id']."'>Editar</button>";
      }

      if(!$value["ragr_idmov_ind"]){
        $idmov_ind = "";
      }else{
        $idmov_ind = $value["ragr_idmov_ind"];
      }

      if(!$value["ragr_idmov_gral"]){
        $idmov_gral = "";
      }else{
        $idmov_gral = $value["ragr_idmov_gral"];
      }

      if(!$value["ragr_fecha_termino"]){
        $fechafin = "";
      }else{
        $fechafin = $value["ragr_fecha_termino"];
      }


      $notas = "<button aria-label='".$value["ragr_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";


      $fecha = substr($value["ragr_fecha_retiro"], 0, 10);

      $row = array(
          'id' => ($key+1),
          'nombre' => $nombre,
          'cantidad' => $cantidad,
          'cantidadfin' => $cantidadreal,
          'notas' => $notas,
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

  public function mostrarTablaAgrHistoDepMas(){
    global $wpdb;
    $user = wp_get_current_user();
    $userid = $user->ID;
    $depositos = $wpdb->prefix . 'depositos_master_agr';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT month(dmagr_fecha_deposito) AS mes, year(dmagr_fecha_deposito) AS agno, dmagr_cantidad, dmagr_cantidad_real, dmagr_status, dmagr_fecha_deposito FROM $depositos WHERE dmagr_usuario = $userid ", ARRAY_A);
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



      $mes = $value["mes"];
      $tmes = $mesesNombre[$mes];
      $agno = (int)$value['agno'];

      if ($value["dmagr_status"] == 0 || $value["dmagr_status"] == 1 ) {
        $fechaSeparada = explode("-", $value["dmagr_fecha_deposito"]);
        $fechadep = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

        $tcant = number_format($value["dmagr_cantidad"], 2, '.', ',');
        $accio = "<span class='accio accio-gray'><i class='fa-solid fa-hourglass'></i> Pendiente</span>";
      }else if($value["dmagr_status"] == 2 ) {
        $fechaSeparada = explode("-", $value["dmagr_fecha_deposito"]);
        $fechadep = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

        $tcant = number_format($value["dmagr_cantidad_real"], 2, '.', ',');
        $accio = "<span class='accio accio-green'><i class='fa-solid fa-circle-check'></i> Autorizado</span>";
      } else{
        $fechaSeparada = explode("-", $value["dmagr_fecha_deposito"]);
        $fechadep = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

        $tcant = number_format($value["dmagr_cantidad"], 2, '.', ',');
        $accio = "<span class='accio accio-red'><i class='fa-solid fa-ban'></i> Cancelado</span>";
      }


      $row = array(
          'mes' => $fechadep,
          'cantidad'=> "$".$tcant,
          'status'=> $accio
        );
      $return_json[] = $row;

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }

  public function mostrarTablaAgrHistoRetMas(){
    global $wpdb;
    $user = wp_get_current_user();
    $userid = $user->ID;
    $retiros = $wpdb->prefix . 'retiros_master_agr';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT month(rmagr_fecha_retiro) AS mes, year(rmagr_fecha_retiro) AS agno, rmagr_cantidad, rmagr_cantidad_real, rmagr_status, rmagr_fecha_retiro FROM $retiros WHERE rmagr_usuario = $userid ", ARRAY_A);
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

      $mes = $value["mes"];
      $tmes = $mesesNombre[$mes];
      $agno = (int)$value['agno'];

      if ($value["rmagr_status"] == 0 || $value["rmagr_status"] == 1 ) {
        $fechaSeparada = explode("-", $value["rmagr_fecha_retiro"]);
        $fecharet = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

        $tcant = number_format($value["rmagr_cantidad"], 2, '.', ',');
        $accio = "<span class='accio accio-gray'><i class='fa-solid fa-hourglass'></i> Pendiente</span>";
      }else if($value["rmagr_status"] == 2 ) {
        $fechaSeparada = explode("-", $value["rmagr_fecha_retiro"]);
        $fecharet = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

        $tcant = number_format($value["rmagr_cantidad_real"], 2, '.', ',');
        $accio = "<span class='accio accio-green'><i class='fa-solid fa-circle-check'></i> Autorizado</span>";
      } else{
        $fechaSeparada = explode("-", $value["rmagr_fecha_retiro"]);
        $fecharet = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

        $tcant = number_format($value["ragr_cantidad"], 2, '.', ',');
        $accio = "<span class='accio accio-red'><i class='fa-solid fa-ban'></i> Cancelado</span>";
      }


      $row = array(
          'mes' => $fecharet,
          'cantidad'=> "$".$tcant,
          'status'=> $accio
        );
      $return_json[] = $row;

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }

  public function mostrarAgrTablaDepMas(){
    global $wpdb;
    $useract = wp_get_current_user();
    $userid = $useract->ID;
    $subadministrador = false;

    if ( isset( $useract->roles ) && is_array( $useract->roles ) ) {
        if ( in_array( 'subadministrador', $useract->roles ) ) {
          $subadministrador = true;
      }
    }

    $depositos = $wpdb->prefix . 'depositos_master_agr';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $depositos ORDER BY dmagr_id DESC", ARRAY_A);

    $return_json = array();

    if(count($registros) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

    foreach ($registros as $key => $value) {

      $user = get_userdata( absint( $value["dmagr_usuario"] ) );
      // $wallet = get_user_meta( $user->ID, 'wallet', true);
      // $walletcode = get_user_meta( $user->ID, 'walletcode', true);
      $email = $user->user_email;
      // $pais = get_user_meta( $user->ID, 'pais', true);

      if ($user) {
        $nombre = $user->first_name . ' ' .$user->last_name ;
      }else{
        $nombre = 'Usuario no encontrado';
      }

      $status = $value["dmagr_status"];
      if ($status == 0) {
        $statusc = "Generada";
        if ($subadministrador) {
          $acciones = "";
        }else{
          $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindepmas' data-deposito='".$value['dmagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdepmas' data-deposito='".$value['dmagr_id']."'>Cancelar</button>";
        }
      }else if($status == 1){
        $statusc = "Confirmada";
        if ($subadministrador) {
          $acciones = "";
        }else{
          $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindepmas' data-deposito='".$value['dmagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdepmas' data-deposito='".$value['dmagr_id']."'>Cancelar</button>";
        }
      }else if($status == 3){
        $statusc = "Cancelada";
        if ($subadministrador) {
          $acciones = "";
        }else{
          $acciones = "<button class='btn-finalizar-inac'>Editar</button>";
        }
      }else{
        $statusc = "Autorizada";
        if ($subadministrador) {
          $acciones = "";
        }else{
          $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agreditdepmas' data-deposito='".$value['dmagr_id']."'>Editar</button>";
        }
      }

      if(!$value["dmagr_idmov_ind"]){
        $idmov_ind = "";
      }else{
        $idmov_ind = $value["dmagr_idmov_ind"];
      }

      if(!$value["dmagr_idmov_gral"]){
        $idmov_gral = "";
      }else{
        $idmov_gral = $value["dmagr_idmov_gral"];
      }

      if(!$value["dmagr_fecha_termino"] || $value["dmagr_fecha_termino"] == "0000-00-00"){
        $fechafin = "";
      }else{
        $fechafin = $value["dmagr_fecha_termino"];
      }

      if($value["dmagr_cantidad_real"] == 0){
        $cantidadreal = "";
      }else{
        $cantidadreal = "$".number_format($value["dmagr_cantidad_real"], 2);
      }

      $fecha = substr($value["dmagr_fecha_deposito"], 0, 10);

      $cantidad  = "$".number_format($value["dmagr_cantidad"], 2);
      // $interes = $value["interes"]."%";

      $notas = "<button aria-label='".$value["dmagr_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";

      $row = array(
          'id' => ($key+1),
          'nombre' => $nombre,
          'cantidad' => $cantidad,
          'cantidadfin' => $cantidadreal,
          'notas' => $notas,
          'status' => $statusc,
          'idmov_ind' => $idmov_ind,
          'idmov_gral' => $idmov_gral,
          'fecha' => $fecha,
          'fechafin' => $fechafin,
          'acciones' => $acciones
        );
      $return_json[] = $row;

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }

  public function mostrarAgrTablaRetMas(){
    global $wpdb;
    $useract = wp_get_current_user();
    $userid = $useract->ID;
    $subadministrador = false;

    if ( isset( $useract->roles ) && is_array( $useract->roles ) ) {
        if ( in_array( 'subadministrador', $useract->roles ) ) {
          $subadministrador = true;
      }
    }

    $retiros = $wpdb->prefix . 'retiros_master_agr';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $retiros ORDER BY rmagr_id DESC", ARRAY_A);

    $return_json = array();

    if(count($registros) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

    foreach ($registros as $key => $value) {

      $user = get_userdata( absint( $value["rmagr_usuario"] ) );
      // $wallet = get_user_meta( $user->ID, 'wallet', true);
      // $walletcode = get_user_meta( $user->ID, 'walletcode', true);
      $email = $user->user_email;
      // $pais = get_user_meta( $user->ID, 'pais', true);

      if ($user) {
        $nombre = $user->first_name . ' ' .$user->last_name ;
      }else{
        $nombre = 'Usuario no encontrado';
      }

      $cantidad  = "$".number_format($value["rmagr_cantidad"], 2);
      $cantidadi  = number_format($value["rmagr_cantidad"], 2);

      if(!$value["rmagr_cantidad_real"]){
        $cantidadreal = "";
        $cantidadfini = "0.00";
      }else{
        $cantidadreal = "$".number_format($value["rmagr_cantidad_real"], 2);
        $cantidadfini = $value["rmagr_cantidad_real"];
      }

      if(!$value["rmagr_notas"]){
        $notas = "";
      }else{
        $notas = $value["rmagr_notas"];
      }

      $status = $value["rmagr_status"];
      if ($status == 0) {
        $statusc = "Generada";
        if ($subadministrador) {
          $acciones = "";
        }else{
          $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfinretmas' data-retiro='".$value['rmagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancretmas' data-retiro='".$value['rmagr_id']."'>Cancelar</button>";
        }
      }else if($status == 1){
        $statusc = "Confirmada";
        if ($subadministrador) {
          $acciones = "";
        }else{
          $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfinretmas' data-retiro='".$value['rmagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancretmas' data-retiro='".$value['rmagr_id']."'>Cancelar</button>";
        }
      }else if($status == 3){
        $statusc = "Cancelada";
        if ($subadministrador) {
          $acciones = "";
        }else{
          $acciones = "<button class='btn-finalizar-inac'>Editar</button>";
        }
      }else{
        $statusc = "Autorizada";
        if ($subadministrador) {
          $acciones = "";
        }else{
          $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agreditretmas' data-retiro='".$value['rmagr_id']."'>Editar</button>";
        }
      }

      if(!$value["rmagr_idmov_ind"]){
        $idmov_ind = "";
      }else{
        $idmov_ind = $value["rmagr_idmov_ind"];
      }

      if(!$value["rmagr_idmov_gral"]){
        $idmov_gral = "";
      }else{
        $idmov_gral = $value["rmagr_idmov_gral"];
      }

      if(!$value["rmagr_fecha_termino"] || $value["rmagr_fecha_termino"] == "0000-00-00"){
        $fechafin = "";
      }else{
        $fechafin = $value["rmagr_fecha_termino"];
      }


      $notas = "<button aria-label='".$value["rmagr_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";


      $fecha = substr($value["rmagr_fecha_retiro"], 0, 10);

      $row = array(
          'id' => ($key+1),
          'nombre' => $nombre,
          'cantidad' => $cantidad,
          'cantidadfin' => $cantidadreal,
          'notas' => $notas,
          'status' => $statusc,
          'idmov_ind' => $idmov_ind,
          'fecha' => $fecha,
          'fechafin' => $fechafin,
          'acciones' => $acciones
        );
      $return_json[] = $row;

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }

  public function mostrarTablaAdmAgrConMaster(){
    global $wpdb;
    // $useract = wp_get_current_user();
    // $userid = $useract->ID;
    // $subadministrador = false;

    // if ( isset( $useract->roles ) && is_array( $useract->roles ) ) {
    //     if ( in_array( 'subadministrador', $useract->roles ) ) {
    //       $subadministrador = true;
    //   }
    // }

    $tabla = $wpdb->prefix . 'registros_agr';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $tabla ORDER BY reagr_id", ARRAY_A);
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

    $calculos = new CRC_AgreCalculo();
    // $detallemesuser = $calculos->crc_datosproyeccion_agreinvestor(48);
    $detalleregistros = $calculos->crc_datosproyeccion_agreregistros();
    $reversed = array_reverse($detalleregistros);


    foreach ($reversed as $key => $value) {

      $mes = $value['mes'];
      $agno = $value['year'];
      $ttotaldep = "$".number_format($value['depmes'],2);
      $ttdp = '<span class="verde btn-ver-depagr" data-mes='.$mes.' data-agno='.$agno.'>+ '.$ttotaldep.'</span>';
      $tcapini = "$".number_format($value['capini'],2);
      $ttotalret = "$".number_format($value['retmes'],2);
      $ttrt = '<span class="rojo btn-ver-retagr" data-mes='.$mes.' data-agno='.$agno.'>- '.$ttotalret.'</span>';
      $tutilinvestors = number_format($value['investors'],2);
      $ttutilinvestors = '<span class="blanco btn-ver-invagr" data-mes='.$mes.' data-agno='.$agno.'>$'.$tutilinvestors.'</span>';
      $notas = $value['notas'];
      $fechareg = $value['fecharegistro'];
      $utilini = "$".number_format($value['utilmes'],2);
      $combroker = "$".number_format($value['combroker'],2);
      $utilreal = "$".number_format($value['utilreal'],2);
      $theinc = "$".number_format($value['theinc'],2);
      $gopro = "$".number_format($value['gopro'],2);
      $promgananciasxuser = "%".number_format($value['utilinvpor'],2);
      $utilrealpor = "%".number_format($value['utilrealpor'],2);
      $totalcierremes = "$".number_format($value['totalcierremes'],2);

      $row = array(
          'id' => ($key+1),
          'periodo' => $value['tmes']." - ".$value['year'],
          'depositos' => $ttdp,
          'capinicial' => $tcapini,
          'utilinicial' => $utilini,
          'combroker' => $combroker,
          'utilreal' => $utilreal,
          'investors' => $ttutilinvestors,
          'theinc' => $theinc,
          'gopro' => $gopro,
          'utilrealpor' => $utilrealpor,
          'utilinvpor' => $promgananciasxuser,
          'retiros' => $ttrt,
          'totalcierremes' => $totalcierremes,
          'notas' => $notas,
          'fecharegistro' => $fechareg
        );
      $return_json[] = $row;

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }

  public function mostrarTablaAgrDetalleDepMes(){
    if(isset($_POST['year'])){
      global $wpdb;

      $agno = (int) $_POST['year'];
      $mesint = (int) $_POST['mes'];

      $depositos = $wpdb->prefix . 'depositos_agr';
      $ruta = get_site_url();
      $registros = $wpdb->get_results(" SELECT * , month(dagr_fecha_termino) AS mes, year(dagr_fecha_termino) AS agno FROM $depositos WHERE month(dagr_fecha_termino) = $mesint AND year(dagr_fecha_termino) = $agno AND dagr_status = 2", ARRAY_A);

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

        $user = get_userdata( absint( $value["dagr_usuario"] ) );
        $wallet = get_user_meta( $user->ID, 'wallet', true);
        $walletcode = get_user_meta( $user->ID, 'walletcode', true);
        $email = $user->user_email;
        $pais = get_user_meta( $user->ID, 'pais', true);

        if ($user) {
          $nombre = $user->first_name . ' ' .$user->last_name ;
        }else{
          $nombre = 'Usuario no encontrado';
        }

        $status = $value["dagr_status"];
        if ($status == 0) {
          $statusc = "Generada";
          // $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindep' data-deposito='".$value['dagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdep' data-deposito='".$value['dagr_id']."'>Cancelar</button>";
        }else if($status == 1){
          $statusc = "Confirmada";
          // $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindep' data-deposito='".$value['dagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdep' data-deposito='".$value['dagr_id']."'>Cancelar</button>";
        }else if($status == 3){
          $statusc = "Cancelada";
          // $acciones = "<button class='btn-finalizar-inac'>Editar</button>";
        }else{
          $statusc = "Autorizada";
          // $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agreditdep' data-deposito='".$value['dagr_id']."'>Editar</button>";
        }

        if(!$value["dagr_idmov_ind"]){
          $idmov_ind = "";
        }else{
          $idmov_ind = $value["dagr_idmov_ind"];
        }

        if(!$value["dagr_idmov_gral"]){
          $idmov_gral = "";
        }else{
          $idmov_gral = $value["dagr_idmov_gral"];
        }

        if(!$value["dagr_fecha_termino"]){
          $fechafin = "";
        }else{
          $fechafin = $value["dagr_fecha_termino"];
        }

        if($value["dagr_cantidad_real"] == 0){
          $cantidadreal = "";
        }else{
          $cantidadreal = "$".number_format($value["dagr_cantidad_real"], 2);
        }

        $fecha = substr($value["dagr_fecha_deposito"], 0, 10);

        $cantidad  = "$".number_format($value["dagr_cantidad"], 2);
        // $interes = $value["interes"]."%";

        $notas = "<button aria-label='".$value["dagr_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";

        $row = array(
            'id' => ($key+1),
            'nombre' => $nombre,
            'cantidad' => $cantidad,
            'cantidadfin' => $cantidadreal,
            'notas' => $notas,
            'status' => $statusc,
            'idmov_ind' => $idmov_ind,
            'idmov_gral' => $idmov_gral,
            'fecha' => $fecha,
            'fechafin' => $fechafin,
            'wallet' => $wallet,
            'walletcode' => $walletcode
          );
        $return_json[] = $row;

      }
      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
    }
  }

  public function mostrarTablaAgrDetalleDepMasMes(){
    if(isset($_POST['year'])){
      global $wpdb;

      $agno = (int) $_POST['year'];
      $mesint = (int) $_POST['mes'];

      $depositos = $wpdb->prefix . 'depositos_master_agr';
      $ruta = get_site_url();
      $registros = $wpdb->get_results(" SELECT * , month(dmagr_fecha_termino) AS mes, year(dmagr_fecha_termino) AS agno FROM $depositos WHERE month(dmagr_fecha_termino) = $mesint AND year(dmagr_fecha_termino) = $agno AND dmagr_status = 2", ARRAY_A);

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

        $user = get_userdata( absint( $value["dmagr_usuario"] ) );
        $email = $user->user_email;

        if ($user) {
          $nombre = $user->first_name . ' ' .$user->last_name ;
        }else{
          $nombre = 'Usuario no encontrado';
        }

        $status = $value["dmagr_status"];
        if ($status == 0) {
          $statusc = "Generada";
          // $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindep' data-deposito='".$value['dagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdep' data-deposito='".$value['dagr_id']."'>Cancelar</button>";
        }else if($status == 1){
          $statusc = "Confirmada";
          // $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindep' data-deposito='".$value['dagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdep' data-deposito='".$value['dagr_id']."'>Cancelar</button>";
        }else if($status == 3){
          $statusc = "Cancelada";
          // $acciones = "<button class='btn-finalizar-inac'>Editar</button>";
        }else{
          $statusc = "Autorizada";
          // $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agreditdep' data-deposito='".$value['dagr_id']."'>Editar</button>";
        }

        if(!$value["dmagr_idmov_ind"]){
          $idmov_ind = "";
        }else{
          $idmov_ind = $value["dmagr_idmov_ind"];
        }

        if(!$value["dmagr_idmov_gral"]){
          $idmov_gral = "";
        }else{
          $idmov_gral = $value["dmagr_idmov_gral"];
        }

        if(!$value["dmagr_fecha_termino"]){
          $fechafin = "";
        }else{
          $fechafin = $value["dmagr_fecha_termino"];
        }

        if($value["dmagr_cantidad_real"] == 0){
          $cantidadreal = "";
        }else{
          $cantidadreal = "$".number_format($value["dmagr_cantidad_real"], 2);
        }

        $fecha = substr($value["dmagr_fecha_deposito"], 0, 10);

        $cantidad  = "$".number_format($value["dmagr_cantidad"], 2);
        // $interes = $value["interes"]."%";

        $notas = "<button aria-label='".$value["dmagr_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";

        $row = array(
            'id' => ($key+1),
            'nombre' => $nombre,
            'cantidad' => $cantidad,
            'cantidadfin' => $cantidadreal,
            'notas' => $notas,
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
  }

  public function mostrarTablaAgrDetalleRetMes(){
    if(isset($_POST['year'])){
      global $wpdb;

      $agno = (int) $_POST['year'];
      $mesint = (int) $_POST['mes'];

      $retiros = $wpdb->prefix . 'retiros_agr';
      $ruta = get_site_url();
      $registros = $wpdb->get_results(" SELECT * , month(ragr_fecha_termino) AS mes, year(ragr_fecha_termino) AS agno FROM $retiros WHERE month(ragr_fecha_termino) = $mesint AND year(ragr_fecha_termino) = $agno AND ragr_status = 2", ARRAY_A);

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

        $user = get_userdata( absint( $value["ragr_usuario"] ) );
        $wallet = get_user_meta( $user->ID, 'wallet', true);
        $walletcode = get_user_meta( $user->ID, 'walletcode', true);
        $email = $user->user_email;
        $pais = get_user_meta( $user->ID, 'pais', true);

        if ($user) {
          $nombre = $user->first_name . ' ' .$user->last_name ;
        }else{
          $nombre = 'Usuario no encontrado';
        }

        $status = $value["ragr_status"];
        if ($status == 0) {
          $statusc = "Generada";
          // $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindep' data-deposito='".$value['dagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdep' data-deposito='".$value['dagr_id']."'>Cancelar</button>";
        }else if($status == 1){
          $statusc = "Confirmada";
          // $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindep' data-deposito='".$value['dagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdep' data-deposito='".$value['dagr_id']."'>Cancelar</button>";
        }else if($status == 3){
          $statusc = "Cancelada";
          // $acciones = "<button class='btn-finalizar-inac'>Editar</button>";
        }else{
          $statusc = "Autorizada";
          // $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agreditdep' data-deposito='".$value['dagr_id']."'>Editar</button>";
        }

        if(!$value["ragr_idmov_ind"]){
          $idmov_ind = "";
        }else{
          $idmov_ind = $value["ragr_idmov_ind"];
        }

        if(!$value["ragr_idmov_gral"]){
          $idmov_gral = "";
        }else{
          $idmov_gral = $value["ragr_idmov_gral"];
        }

        if(!$value["ragr_fecha_termino"]){
          $fechafin = "";
        }else{
          $fechafin = $value["ragr_fecha_termino"];
        }

        if($value["ragr_cantidad_real"] == 0){
          $cantidadreal = "";
        }else{
          $cantidadreal = "$".number_format($value["ragr_cantidad_real"], 2);
        }

        $fecha = substr($value["ragr_fecha_retiro"], 0, 10);

        $cantidad  = "$".number_format($value["ragr_cantidad"], 2);
        // $interes = $value["interes"]."%";

        $notas = "<button aria-label='".$value["ragr_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";

        $row = array(
            'id' => ($key+1),
            'nombre' => $nombre,
            'cantidad' => $cantidad,
            'cantidadfin' => $cantidadreal,
            'notas' => $notas,
            'status' => $statusc,
            'idmov_ind' => $idmov_ind,
            'fecha' => $fecha,
            'fechafin' => $fechafin,
            'wallet' => $wallet,
            'walletcode' => $walletcode
          );
        $return_json[] = $row;

      }
      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
    }
  }

  public function mostrarTablaAgrDetalleRetMasMes(){
    if(isset($_POST['year'])){
      global $wpdb;

      $agno = (int) $_POST['year'];
      $mesint = (int) $_POST['mes'];

      $retiros = $wpdb->prefix . 'retiros_master_agr';
      $ruta = get_site_url();
      $registros = $wpdb->get_results(" SELECT * , month(rmagr_fecha_termino) AS mes, year(rmagr_fecha_termino) AS agno FROM $retiros WHERE month(rmagr_fecha_termino) = $mesint AND year(rmagr_fecha_termino) = $agno AND rmagr_status = 2", ARRAY_A);

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

        $user = get_userdata( absint( $value["rmagr_usuario"] ) );
        $email = $user->user_email;

        if ($user) {
          $nombre = $user->first_name . ' ' .$user->last_name ;
        }else{
          $nombre = 'Usuario no encontrado';
        }

        $status = $value["rmagr_status"];
        if ($status == 0) {
          $statusc = "Generada";
          // $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindep' data-deposito='".$value['dagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdep' data-deposito='".$value['dagr_id']."'>Cancelar</button>";
        }else if($status == 1){
          $statusc = "Confirmada";
          // $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindep' data-deposito='".$value['dagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdep' data-deposito='".$value['dagr_id']."'>Cancelar</button>";
        }else if($status == 3){
          $statusc = "Cancelada";
          // $acciones = "<button class='btn-finalizar-inac'>Editar</button>";
        }else{
          $statusc = "Autorizada";
          // $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agreditdep' data-deposito='".$value['dagr_id']."'>Editar</button>";
        }

        if(!$value["rmagr_idmov_ind"]){
          $idmov_ind = "";
        }else{
          $idmov_ind = $value["rmagr_idmov_ind"];
        }

        // if(!$value["rmagr_idmov_gral"]){
        //   $idmov_gral = "";
        // }else{
        //   $idmov_gral = $value["rmagr_idmov_gral"];
        // }

        if(!$value["rmagr_fecha_termino"]){
          $fechafin = "";
        }else{
          $fechafin = $value["rmagr_fecha_termino"];
        }

        if($value["rmagr_cantidad_real"] == 0){
          $cantidadreal = "";
        }else{
          $cantidadreal = "$".number_format($value["rmagr_cantidad_real"], 2);
        }

        $fecha = substr($value["rmagr_fecha_retiro"], 0, 10);

        $cantidad  = "$".number_format($value["rmagr_cantidad"], 2);
        // $interes = $value["interes"]."%";

        $notas = "<button aria-label='".$value["rmagr_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";

        $row = array(
            'id' => ($key+1),
            'nombre' => $nombre,
            'cantidad' => $cantidad,
            'cantidadfin' => $cantidadreal,
            'notas' => $notas,
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
  }

  public function mostrarTablaAdmAgrInvMes(){
    global $wpdb;
    // $useract = wp_get_current_user();
    // $userid = $useract->ID;
    // $subadministrador = false;

    $agno = (int) $_POST['year'];
    $mesint = (int) $_POST['mes'];

    // if ( isset( $useract->roles ) && is_array( $useract->roles ) ) {
    //     if ( in_array( 'subadministrador', $useract->roles ) ) {
    //       $subadministrador = true;
    //   }
    // }

    $tabla = $wpdb->prefix . 'registros_agr';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE reagr_mes = $mesint AND reagr_year = $agno ORDER BY reagr_id LIMIT 1", ARRAY_A);
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

      $mes = (int)$value['reagr_mes'];
      $tmes = $mesesNombre[$mes];
      $agno = (int)$value['reagr_year'];

      $listausers = json_decode($value['reagr_usuarios'],true);

      // Checamos cuantos users hay
      if (count($listausers) == 0) {
        $porinver = 0;
        $poradmins = 100 ;
      }else{
        $porinver = (float)$value['reagr_por_inver'];
        $poradmins = 100 - $porinver ;
      }


      // Rellenamos el array de la tabla

      foreach ($listausers as $llave => $valor) {

        $user = get_userdata( absint( $valor ) );
        $wallet = get_user_meta( $user->ID, 'wallet', true);
        $walletcode = get_user_meta( $user->ID, 'walletcode', true);
        $email = $user->user_email;
        $pais = get_user_meta( $user->ID, 'pais', true);

        if ($user) {
          $nombre = $user->first_name . ' ' .$user->last_name ;
        }else{
          $nombre = 'Usuario no encontrado';
        }

        $calculos = new CRC_AgreCalculo();
        $detalleregistros = $calculos->crc_datosmes_agreinvestor($user->ID, $mesint, $agno);

        if(empty($detalleregistros)){
          // $ttotaldep = "$".number_format($detalleregistros[0]['depmes'],2);
          $ttdp = '<span class="verde " data-mes='.$mes.' data-agno='.$agno.'>+ $0</span>';

          // $ttotalret = "$".number_format($detalleregistros[0]['retmes'],2);
          $ttrt = '<span class="rojo " data-mes='.$mes.' data-agno='.$agno.'>- $0</span>';

          $tcapini = "$0";
          $porparticipuser = "0";
          $utilidaduser = "$0";
          $utilidadacum = "$0";
          $total = "$0";
          $rendimientomes = "%0";

        }else{
          $ttotaldep = "$".number_format($detalleregistros[0]['depmes'],2);
          $ttdp = '<span class="verde " data-mes='.$mes.' data-agno='.$agno.'>+ '.$ttotaldep.'</span>';

          $ttotalret = "$".number_format($detalleregistros[0]['retmes'],2);
          $ttrt = '<span class="rojo " data-mes='.$mes.' data-agno='.$agno.'>- '.$ttotalret.'</span>';

          $tcapini = "$".number_format($detalleregistros[0]['capini'],2);
          $porparticipuser = $detalleregistros[0]['porparticipuser'];
          $utilidaduser = "$".number_format($detalleregistros[0]['utilidad'],2);
          $utilidadacum = "$".number_format($detalleregistros[0]['utilacumulada'],2);
          $total = "$".number_format($detalleregistros[0]['total'],2);
          $rendimientomes = "%".number_format($detalleregistros[0]['rendimientomes'],2);

        }


        $row = array(
            'id' => ($key+1),
            'nombre' => $nombre,
            'depositos' => $ttdp,
            'capinicial' => $tcapini,
            'porparticip' => "%".$porparticipuser,
            'utilmes' => $utilidaduser,
            'utilacum' => $utilidadacum,
            'total' =>  $total,
            'porrendimiento' => $rendimientomes,
            'retiros' => $ttrt
          );
        $return_json[] = $row;

      }

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }

  public function mostrarTablaAgrListaUsuarios(){

    $useragresivo = get_users(array(
    'meta_key' => 'modagresivo',
    'meta_value' => 1
    ));

    $listausers = array();

    if (count($useragresivo) ==  0) {

    }else {
      foreach ($useragresivo as $key => $value) {
        $valid = $value->ID;
        $listausers[] = $value->ID;

      }
    }

    $return_json = array();

    if(count($listausers) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

    foreach ($listausers as $key => $value) {
      $userid = absint( $value );
      $user = get_userdata( absint( $value ) );
      if ( isset( $user->roles ) && is_array( $user->roles ) ) {
          if ( in_array( 'inversionista', $user->roles ) ) {

            $wallet = get_user_meta( $user->ID, 'wallet', true);
            $walletcode = get_user_meta( $user->ID, 'walletcode', true);
            $email = $user->user_email;
            $pais = get_user_meta( $user->ID, 'pais', true);
            $activo = get_user_meta( $user->ID, 'activo', true);

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
            $perfil = "<button class='button button-primary btn-perfil' data-usuario='".$userid."'>Ver</button>";

            $row = array(
                'id' => ($key+1),
                'nombre' => $nombre,
                'email' => $email,
                'acceso' => $acceso,
                'proyeccion' => $acciones,
                'perfil' => $perfil,
                'capprincipal' => "",
                'tipowallet' => $walletc,
                'wallet' => $walletcodec
              );

            $return_json[] = $row;

        }

      }

    }
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }


  public function traer_datos_registro_agr(){
    if(isset($_POST['tipo'])){
      global $wpdb;

      date_default_timezone_set("America/Tijuana");

      $tipo = $_POST['tipo'];

      if ($tipo == "editRegistro") {
        $tabla = $wpdb->prefix.'registros_agr';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE reagr_id = $id ORDER BY reagr_id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){

          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {

          $utilmes = $value['reagr_util_mes'];
          $combro = $value['reagr_com_bro'];
          $por_inver= $value['reagr_por_inver'];
          $por_refer = $value['reagr_por_refer'];
          $mes = $value['reagr_mes'];
          $year = $value['reagr_year'];
          $notas = $value['reagr_notas'];

          $row = array(
              'mes' => $mes,
              'year' => $year,
              'utilmes' => $utilmes,
              'combro' => $combro,
              'por_inver'=> $por_inver,
              'por_refer'=>$por_refer,
              'notas' => $notas
            );
          $return_json[] = $row;

        }
        //return the result to the ajax request and die
        echo json_encode(array('data' => $return_json));
        wp_die();

      }else{

        $mes = (int)$_POST['mes'];
        $agno = (int)$_POST['year'];

        $tabla = $wpdb->prefix.'registros_agr';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE reagr_mes = $mes AND reagr_year = $agno ORDER BY reagr_id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){
          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {

          $id = $value['reagr_id'];
          $utilmes = $value['reagr_util_mes'];
          $combro = $value['reagr_com_bro'];
          $por_inver= $value['reagr_por_inver'];
          $por_refer = $value['reagr_por_refer'];
          $mes = $value['reagr_mes'];
          $year = $value['reagr_year'];
          $notas = $value['reagr_notas'];

          $row = array(
              'id' => $id,
              'mes' => $mes,
              'year' => $year,
              'utilmes' => $utilmes,
              'combro' => $combro,
              'por_inver'=> $por_inver,
              'por_refer'=>$por_refer,
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

  public function referral_agregarregistro_agr(){
    if(isset($_POST['mes'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'registros_agr';
      $tabladep = $wpdb->prefix.'depositos_agr';

      date_default_timezone_set("America/Tijuana");

      // $cid = $_POST['cid'];
      $mes = (int)$_POST['mes'];
      $year = (int)$_POST['year'];
      $utilmes = (float)$_POST['utilmes'];
      $combro = (float)$_POST['combro'];
      $por_inver = (float)$_POST['por_inver'];
      $por_refer = (float)$_POST['por_refer'];

      // BUSCAMOS A TODOS LOS USUARIOS QUE PARTICIPAN EN AGRESIVO:

      $useragresivo = get_users(array(
      'meta_key' => 'modagresivo',
      'meta_value' => 1
      ));

      $listausers = array();

      if (count($useragresivo) ==  0) {

      }else {
        foreach ($useragresivo as $key => $value) {
          $valid = $value->ID;
          $registros = $wpdb->get_results(" SELECT * FROM $tabladep WHERE dagr_usuario = $valid AND dagr_status = 2 ", ARRAY_A);

          if (count($registros) != 0) {
            $listausers[] = $value->ID;
          }

        }
      }

      $listausers_json = json_encode($listausers);

      // $fecha_retiro = date("Y-m-d");

      $datos = array(
          'reagr_mes'=>$mes,
          'reagr_year'=>$year,
          'reagr_util_mes'=>$utilmes,
          'reagr_com_bro'=>$combro,
          'reagr_por_inver'=>$por_inver,
          'reagr_por_refer'=>$por_refer,
          'reagr_status'=>1,
          'reagr_usuarios'=>$listausers_json,
          'reagr_fecha_control'=>$_POST['fecha'],
          'reagr_notas'=>sanitize_text_field($_POST['notas'])
          );

      $formato = array(
      '%d',
      '%d',
      '%f',
      '%f',
      '%f',
      '%f',
      '%d',
      '%s',
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

  public function generar_codigo(){
    $codigo = '';
    $pattern = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $max = strlen($pattern)-1;
    for($i=0;$i < 6;$i++) $codigo .= $pattern[mt_rand(0,$max)];
    return $codigo;
  }

}

 ?>
