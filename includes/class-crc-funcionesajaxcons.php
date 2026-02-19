<?php

class CRC_FuncionesajaxCons{

  public function solicitar_condeposito(){
    if(isset($_POST['user'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'depositos_con';

      date_default_timezone_set("America/Tijuana");

      $cantidad = (float)$_POST['cantidad'];
      $usuario = (int)$_POST['user'];

      $fecha_deposito = date("Y-m-d");
      $codigo = $this->generar_codigo();


      $datos = array(
          'dcon_cantidad'=>$cantidad,
          'dcon_usuario'=>$usuario,
          'dcon_fecha_deposito'=>$fecha_deposito,
          'dcon_codigo'=>$codigo,
          'dcon_status'=>0
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
        $urlcompleta = $url . '/?code_submitted='.$codigo.'&tipo=condep';
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

  public function solicitar_conretiro(){
    if(isset($_POST['user'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'retiros_con';

      date_default_timezone_set("America/Tijuana");

      $cantidad = (float)$_POST['cantidad'];
      $usuario = (int)$_POST['user'];

      $fecha_retiro = date("Y-m-d");
      $codigo = $this->generar_codigo();

      $datos = array(
          'rcon_cantidad'=>$cantidad,
          'rcon_usuario'=>$usuario,
          'rcon_fecha_retiro'=>$fecha_retiro,
          'rcon_codigo'=>$codigo,
          'rcon_status'=>0
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
        $urlcompleta = $url . '/?code_submitted='.$codigo.'&tipo=conret';
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

  public function traer_datos_cons(){
    if(isset($_POST['id'])){
      global $wpdb;

      date_default_timezone_set("America/Tijuana");

      $id = $_POST['id'];
      $tipo = $_POST['tipo'];

      if ($tipo == 'deposito') {
        $tabla = $wpdb->prefix.'depositos_con';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE dcon_id = $id ORDER BY dcon_id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){

          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {

          $cantidad = $value['dcon_cantidad'];
          $cantidadreal = $value['dcon_cantidad_real'];
          $idmovind = $value['dcon_idmov_ind'];
          $idmovgral = $value['dcon_idmov_gral'];
          $fecha = $value['dcon_fecha_deposito'];
          $fechafin = $value['dcon_fecha_termino'];
          $notas = $value['dcon_notas'];

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
        $tabla = $wpdb->prefix.'retiros_con';

        $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE rcon_id = $id ORDER BY rcon_id DESC", ARRAY_A);

        $return_json = array();

        if(count($registros) == 0){

          //return the result to the ajax request and die
          echo json_encode(array('data' => $return_json));
          wp_die();
          return;
        }

        foreach ($registros as $key => $value) {

          $cantidad = $value['rcon_cantidad'];
          $cantidadreal = $value['rcon_cantidad_real'];
          $idmovind = $value['rcon_idmov_ind'];
          $fecha = $value['rcon_fecha_retiro'];
          $fechafin = $value['rcon_fecha_termino'];
          $notas = $value['rcon_notas'];

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

  public function operacion_finalizar_cons(){
    if(isset($_POST['id'])){
      global $wpdb;

      date_default_timezone_set("America/Tijuana");

      $id = $_POST['id'];
      $tipo = $_POST['tipo'];

      if ($tipo == 'deposito') {
        $tabla = $wpdb->prefix.'depositos_con';

        //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));

        $fechafin = $_POST['fechafin'];
        $fechasol = $_POST['fechasol'];
        $cantidadfin = (float)$_POST['cantidadfin'];

        $datos = [
        'dcon_status'=> 2,
        'dcon_idmov_ind'=> $_POST['idmovind'],
        'dcon_idmov_gral'=> $_POST['idmovgral'],
        'dcon_cantidad_real'=> $cantidadfin,
        'dcon_fecha_deposito' => $fechasol,
        'dcon_fecha_termino' => $fechafin,
        'dcon_notas' => sanitize_text_field($_POST['notas'])
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
          'dcon_id' => $id
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
        $tabla = $wpdb->prefix.'retiros_con';

        //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));

        $fechafin = $_POST['fechafin'];
        $fechasol = $_POST['fechasol'];
        $cantidadfin = (float)$_POST['cantidadfin'];

        $datos = [
        'rcon_status'=> 2,
        'rcon_idmov_ind'=> $_POST['idmovind'],
        'rcon_idmov_gral'=> '',
        'rcon_cantidad_real'=> $cantidadfin,
        'rcon_fecha_retiro' => $fechasol,
        'rcon_fecha_termino' => $fechafin,
        'rcon_notas' => sanitize_text_field($_POST['notas'])
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
          'rcon_id' => $id
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

  public function operacion_editar_cons(){
    if(isset($_POST['id'])){
      global $wpdb;

      date_default_timezone_set("America/Tijuana");

      $id = $_POST['id'];
      $tipo = $_POST['tipo'];

      if ($tipo == 'deposito') {
        $tabla = $wpdb->prefix.'depositos_con';

        //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));

        $fechafin = $_POST['fechafin'];
        $fechasol = $_POST['fechasol'];
        $cantidadfin = (float)$_POST['cantidadfin'];

        $datos = [
        'dcon_status'=> 2,
        'dcon_idmov_ind'=> $_POST['idmovind'],
        'dcon_idmov_gral'=> $_POST['idmovgral'],
        'dcon_cantidad_real'=> $cantidadfin,
        'dcon_fecha_deposito' => $fechasol,
        'dcon_fecha_termino' => $fechafin,
        'dcon_notas' => sanitize_text_field($_POST['notas'])
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
          'dcon_id' => $id
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
        $tabla = $wpdb->prefix.'retiros_con';

        //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));
        $fechafin = $_POST['fechafin'];
        $fechasol = $_POST['fechasol'];
        $cantidadfin = (float)$_POST['cantidadfin'];

        $datos = [
        'rcon_status'=> 2,
        'rcon_idmov_ind'=> $_POST['idmovind'],
        'rcon_cantidad_real'=> $cantidadfin,
        'rcon_fecha_retiro' => $fechasol,
        'rcon_fecha_termino' => $fechafin,
        'rcon_notas' => sanitize_text_field($_POST['notas'])
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
          'rcon_id' => $id
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

  public function operacion_cancelar_cons(){
    if(isset($_POST['id'])){
      global $wpdb;

      date_default_timezone_set("America/Tijuana");

      $id = $_POST['id'];
      $tipo = $_POST['tipo'];

      if ($tipo == 'deposito') {
        $tabla = $wpdb->prefix.'depositos_con';

        //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));
        $fechafin = $_POST['fechafin'];
        $fechasol = $_POST['fechasol'];

        $datos = [
        'dcon_status'=> 3,
        'dcon_fecha_deposito' => $fechasol,
        'dcon_fecha_termino' => $fechafin,
        'dcon_notas' => sanitize_text_field($_POST['notas'])
        ];

        $formato = [
          '%d',
          '%s',
          '%s',
          '%s'
        ];

        $donde = [
          'dcon_id' => $id
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
        $tabla = $wpdb->prefix.'retiros_con';

        //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ));
        $fechafin = $_POST['fechafin'];
        $fechasol = $_POST['fechasol'];

        $datos = [
        'rcon_status'=> 3,
        'rcon_fecha_retiro' => $fechasol,
        'rcon_fecha_termino' => $fechafin,
        'rcon_notas' => sanitize_text_field($_POST['notas'])
        ];

        $formato = [
          '%d',
          '%s',
          '%s',
          '%s'
        ];

        $donde = [
          'rcon_id' => $id
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

  public function crear_opemaster_cons(){
    if(isset($_POST['tipo'])){
      global $wpdb;
      $tipo = $_POST['tipo'];

      if($tipo == 'deposito'){
        $tabla = $wpdb->prefix.'depositos_con';

        $idmov_ind = $_POST['idmov_ind'];
        $idmov_gral = $_POST['idmov_gral'];
        $cantidad = (float)$_POST['cantidad'];
        $cantidad_real = (float)$_POST['cantidad_real'];
        $fecha_deposito = $_POST['fecha_deposito'];
        $fecha_findeposito = $_POST['fecha_findeposito'];
        $notas = sanitize_textarea_field($_POST['notas']);
        if ($solicitante == 0) {
          $status = 2;
        }else {
          $status = 1;
        }
        $usuario = (int)$_POST['usuario'];
        $codigo = $this->generar_codigo();

        $datos = array(
            'dcon_cantidad'=>$cantidad,
            'dcon_cantidad_real'=>$cantidad_real,
            'dcon_usuario'=>$usuario,
            'dcon_fecha_deposito'=>$fecha_deposito,
            'dcon_fecha_termino'=>$fecha_findeposito,
            'dcon_codigo'=>$codigo,
            'dcon_status'=>$status,
            'dcon_idmov_ind'=> $idmov_ind,
            'dcon_idmov_gral'=> $idmov_gral,
            'dcon_notas'=> $notas,
            );

        $formato = array(
          '%f',
          '%f',
          '%d',
          '%s',
          '%s',
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
        $tabla = $wpdb->prefix.'retiros_con';

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
        $usuario = (int)$_POST['usuario'];
        $codigo = $this->generar_codigo();

        $datos = array(
              'rcon_cantidad'=>$cantidad,
              'rcon_cantidad_real'=> $cantidad_real,
              'rcon_usuario'=>$usuario,
              'rcon_fecha_retiro'=>$fecha_retiro,
              'rcon_fecha_termino' => $fecha_finretiro,
              'rcon_codigo'=>$codigo,
              'rcon_status'=>$status,
              'rcon_idmov_ind'=> $idmov_ind,
              'rcon_idmov_gral'=> '',
              'rcon_notas' => $notas
            );

        $formato = array(
        '%f',
        '%f',
        '%d',
        '%s',
        '%s',
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
      }
    }
    die(json_encode($respuesta));
  }

  public function mostrarTablaConHistoDep(){
    if(isset($_POST['id'])){
      global $wpdb;
      // $user = wp_get_current_user();
      $userid = (int)$_POST['id'];
      $depositos = $wpdb->prefix . 'depositos_con';
      $ruta = get_site_url();
      $registros = $wpdb->get_results(" SELECT month(dcon_fecha_deposito) AS mes, year(dcon_fecha_deposito) AS agno, dcon_cantidad, dcon_cantidad_real, dcon_status, dcon_fecha_deposito FROM $depositos WHERE dcon_usuario = $userid ", ARRAY_A);
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

        if ($value["dcon_status"] == 0 || $value["dcon_status"] == 1 ) {
          $fechaSeparada = explode("-", $value["dcon_fecha_deposito"]);
          $fechadep = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

          $tcant = number_format($value["dcon_cantidad"], 2, '.', ',');
          $accio = "<span class='accio accio-gray'><i class='fa-solid fa-hourglass'></i> Pendiente</span>";
        }else if($value["dcon_status"] == 2 ) {
          $fechaSeparada = explode("-", $value["dcon_fecha_deposito"]);
          $fechadep = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

          $tcant = number_format($value["dcon_cantidad_real"], 2, '.', ',');
          $accio = "<span class='accio accio-green'><i class='fa-solid fa-circle-check'></i> Autorizado</span>";
        } else{
          $fechaSeparada = explode("-", $value["dcon_fecha_deposito"]);
          $fechadep = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

          $tcant = number_format($value["dcon_cantidad"], 2, '.', ',');
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
  }

  public function mostrarTablaConHistoRet(){
    if(isset($_POST['id'])){
      global $wpdb;
      // $user = wp_get_current_user();
      $userid = (int)$_POST['id'];
      $retiros = $wpdb->prefix . 'retiros_con';
      $ruta = get_site_url();
      $registros = $wpdb->get_results(" SELECT month(rcon_fecha_retiro) AS mes, year(rcon_fecha_retiro) AS agno, rcon_cantidad, rcon_cantidad_real, rcon_status, rcon_fecha_retiro FROM $retiros WHERE rcon_usuario = $userid ", ARRAY_A);
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

        if ($value["rcon_status"] == 0 || $value["rcon_status"] == 1 ) {
          $fechaSeparada = explode("-", $value["rcon_fecha_retiro"]);
          $fecharet = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

          $tcant = number_format($value["rcon_cantidad"], 2, '.', ',');
          $accio = "<span class='accio accio-gray'><i class='fa-solid fa-hourglass'></i> Pendiente</span>";
        }else if($value["rcon_status"] == 2 ) {
          $fechaSeparada = explode("-", $value["rcon_fecha_retiro"]);
          $fecharet = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

          $tcant = number_format($value["rcon_cantidad_real"], 2, '.', ',');
          $accio = "<span class='accio accio-green'><i class='fa-solid fa-circle-check'></i> Autorizado</span>";
        } else{
          $fechaSeparada = explode("-", $value["rcon_fecha_retiro"]);
          $fecharet = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);

          $tcant = number_format($value["rcon_cantidad"], 2, '.', ',');
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
  }

  public function mostrarConTablaHistoDepFull(){
    global $wpdb;
    $user = wp_get_current_user();
    $userid = $user->ID;
    $depositos = $wpdb->prefix . 'depositos_con';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $depositos WHERE dcon_usuario = $userid ORDER BY dcon_id DESC", ARRAY_A);

    $return_json = array();

    if(count($registros) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

    foreach ($registros as $key => $value) {

      $status = $value["dcon_status"];
      if ($status == 0) {
        $statusc = "<span class='accio accio-gray'><i class='fa-solid fa-hourglass'></i> Pendiente</span>";
      }else if($status == 1){
        $statusc = "<span class='accio accio-gray'><i class='fa-solid fa-hourglass'></i> Pendiente</span>";
      }else if($status == 3){
        $statusc = "<span class='accio accio-red'><i class='fa-solid fa-ban'></i> Cancelado</span>";
      }else{
        $statusc = "<span class='accio accio-green'><i class='fa-solid fa-circle-check'></i> Autorizado</span>";
      }

      if(!$value["dcon_idmov_ind"]){
        $idmov_ind = "";
      }else{
        $idmov_ind = $value["dcon_idmov_ind"];
      }

      if(!$value["dcon_idmov_gral"]){
        $idmov_gral = "";
      }else{
        $idmov_gral = $value["dcon_idmov_gral"];
      }

      if(!$value["dcon_fecha_termino"]){
        $fechafin = "";
      }else{
        $fechafin = $value["dcon_fecha_termino"];
      }

      if(!$value["dcon_cantidad_real"]){
        $cantidadreal = "";
      }else{
        $cantidadreal = "$".number_format($value["dcon_cantidad_real"], 2);
      }

      $cantidad  = "$".number_format($value["dcon_cantidad"], 2);

      $fecha = substr($value["dcon_fecha_deposito"], 0, 10);

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

  public function mostrarConTablaHistoRetFull(){
    global $wpdb;
    $user = wp_get_current_user();
    $userid = $user->ID;
    $retiros = $wpdb->prefix . 'retiros_con';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $retiros WHERE rcon_usuario = $userid ORDER BY rcon_id DESC", ARRAY_A);

    $return_json = array();

    if(count($registros) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

    foreach ($registros as $key => $value) {

      $status = $value["rcon_status"];
      if ($status == 0) {
        $statusc = "<span class='accio accio-gray'><i class='fa-solid fa-hourglass'></i> Pendiente</span>";
        $acciones = "<button class='btn-finalizar-inac' type='button' name='button'>Finalizar</button>";
      }else if($status == 1){
        $statusc = "<span class='accio accio-gray'><i class='fa-solid fa-hourglass'></i> Pendiente</span>";
        $acciones = "<input alt='#TB_inline?width=400&inlineId=modal-finret' title='Finalizar un retiro' data-retiro='".$value['rcon_id']."' class='thickbox button button-primary button-large btn-finalizar' type='button' value='Finalizar' />";
      }else if($status == 3){
        $statusc = "<span class='accio accio-red'><i class='fa-solid fa-ban'></i> Cancelado</span>";
        $acciones = "";
      }else{
        $statusc = "<span class='accio accio-green'><i class='fa-solid fa-circle-check'></i> Autorizado</span>";
        $acciones = "<button class='btn-finalizar-inac' type='button' name='button'>Finalizar</button>";
      }

      if(!$value["rcon_idmov_ind"]){
        $idmov_ind = "";
      }else{
        $idmov_ind = $value["rcon_idmov_ind"];
      }

      if(!$value["rcon_idmov_gral"]){
        $idmov_gral = "";
      }else{
        $idmov_gral = $value["rcon_idmov_gral"];
      }

      if(!$value["rcon_fecha_termino"]){
        $fechafin = "";
      }else{
        $fechafin = $value["rcon_fecha_termino"];
      }

      if(!$value["rcon_cantidad_real"]){
        $cantidadreal = "";
      }else{
        $cantidadreal = "$".number_format($value["rcon_cantidad_real"], 2);
      }

      $cantidad  = "$".number_format($value["rcon_cantidad"], 2);

      $fecha = substr($value["rcon_fecha_retiro"], 0, 10);

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

  public function mostrarConTablaAdminDep(){
    global $wpdb;
    $depositos = $wpdb->prefix . 'depositos_con';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $depositos ORDER BY dcon_id DESC", ARRAY_A);

    $return_json = array();

    if(count($registros) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

    foreach ($registros as $key => $value) {

      $user = get_userdata( absint( $value["dcon_usuario"] ) );
      $wallet = get_user_meta( $user->ID, 'wallet', true);
      $walletcode = get_user_meta( $user->ID, 'walletcode', true);
      $email = $user->user_email;
      $pais = get_user_meta( $user->ID, 'pais', true);

      if ($user) {
        $nombre = $user->first_name . ' ' .$user->last_name ;
      }else{
        $nombre = 'Usuario no encontrado';
      }

      $status = $value["dcon_status"];
      if ($status == 0) {
        $statusc = "Generada";
        $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-confindep' data-deposito='".$value['dcon_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-concancdep' data-deposito='".$value['dcon_id']."'>Cancelar</button>";
      }else if($status == 1){
        $statusc = "Confirmada";
        $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-confindep' data-deposito='".$value['dcon_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-concancdep' data-deposito='".$value['dcon_id']."'>Cancelar</button>";
      }else if($status == 3){
        $statusc = "Cancelada";
        $acciones = "<button class='btn-finalizar-inac'>Editar</button>";
      }else{
        $statusc = "Autorizada";
        $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-coneditdep' data-deposito='".$value['dcon_id']."'>Editar</button>";
      }

      if(!$value["dcon_idmov_ind"]){
        $idmov_ind = "";
      }else{
        $idmov_ind = $value["dcon_idmov_ind"];
      }

      if(!$value["dcon_idmov_gral"]){
        $idmov_gral = "";
      }else{
        $idmov_gral = $value["dcon_idmov_gral"];
      }

      if(!$value["dcon_fecha_termino"]){
        $fechafin = "";
      }else{
        $fechafin = $value["dcon_fecha_termino"];
      }

      if($value["dcon_cantidad_real"] == 0){
        $cantidadreal = "";
      }else{
        $cantidadreal = "$".number_format($value["dcon_cantidad_real"], 2);
      }

      $fecha = substr($value["dcon_fecha_deposito"], 0, 10);

      $cantidad  = "$".number_format($value["dcon_cantidad"], 2);
      // $interes = $value["interes"]."%";

      $notas = "<button aria-label='".$value["dcon_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";

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

  public function mostrarConTablaAdminRet(){
    global $wpdb;
    $retiros = $wpdb->prefix . 'retiros_con';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $retiros ORDER BY rcon_id DESC", ARRAY_A);

    $return_json = array();

    if(count($registros) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

    foreach ($registros as $key => $value) {

      $user = get_userdata( absint( $value["rcon_usuario"] ) );
      $wallet = get_user_meta( $user->ID, 'wallet', true);
      $walletcode = get_user_meta( $user->ID, 'walletcode', true);
      $email = $user->user_email;
      $pais = get_user_meta( $user->ID, 'pais', true);

      if ($user) {
        $nombre = $user->first_name . ' ' .$user->last_name ;
      }else{
        $nombre = 'Usuario no encontrado';
      }

      $cantidad  = "$".number_format($value["rcon_cantidad"], 2);
      $cantidadi  = number_format($value["rcon_cantidad"], 2);

      if(!$value["rcon_cantidad_real"]){
        $cantidadreal = "";
        $cantidadfini = "0.00";
      }else{
        $cantidadreal = "$".number_format($value["rcon_cantidad_real"], 2);
        $cantidadfini = $value["rcon_cantidad_real"];
      }

      if(!$value["rcon_notas"]){
        $notas = "";
      }else{
        $notas = $value["rcon_notas"];
      }

      $status = $value["rcon_status"];
      if ($status == 0) {
        $statusc = "Generada";
        $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-confinret' data-retiro='".$value['rcon_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-concancret' data-retiro='".$value['rcon_id']."'>Cancelar</button>";
      }else if($status == 1){
        $statusc = "Confirmada";
        $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-confinret' data-retiro='".$value['rcon_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-concancret' data-retiro='".$value['rcon_id']."'>Cancelar</button>";
      }else if($status == 3){
        $statusc = "Cancelada";
        $acciones = "<button class='btn-finalizar-inac'>Editar</button>";
      }else{
        $statusc = "Autorizada";
        $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-coneditret' data-retiro='".$value['rcon_id']."'>Editar</button>";
      }

      if(!$value["rcon_idmov_ind"]){
        $idmov_ind = "";
      }else{
        $idmov_ind = $value["rcon_idmov_ind"];
      }

      if(!$value["rcon_idmov_gral"]){
        $idmov_gral = "";
      }else{
        $idmov_gral = $value["rcon_idmov_gral"];
      }

      if(!$value["rcon_fecha_termino"]){
        $fechafin = "";
      }else{
        $fechafin = $value["rcon_fecha_termino"];
      }


      $notas = "<button aria-label='".$value["rcon_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";


      $fecha = substr($value["rcon_fecha_retiro"], 0, 10);

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

  // public function mostrarTablaAgrHistoDepMas(){
  //   global $wpdb;
  //   $user = wp_get_current_user();
  //   $userid = $user->ID;
  //   $depositos = $wpdb->prefix . 'depositos_master_agr';
  //   $ruta = get_site_url();
  //   $registros = $wpdb->get_results(" SELECT month(dmagr_fecha_deposito) AS mes, year(dmagr_fecha_deposito) AS agno, dmagr_cantidad, dmagr_cantidad_real, dmagr_status, dmagr_fecha_deposito FROM $depositos WHERE dmagr_usuario = $userid ", ARRAY_A);
  //   $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
  //   '8' => 'Agosto',
  //   '9' => 'Septiembre',
  //   '10' => 'Octubre',
  //   '11' => 'Noviembre',
  //   '12' => 'Diciembre' );
  //
  //   $return_json = array();
  //
  //   if(count($registros) == 0){
  //
  //     //return the result to the ajax request and die
  //     echo json_encode(array('data' => $return_json));
  //     wp_die();
  //     return;
  //   }
  //
  //   foreach ($registros as $key => $value) {
  //
  //
  //
  //     $mes = $value["mes"];
  //     $tmes = $mesesNombre[$mes];
  //     $agno = (int)$value['agno'];
  //
  //     if ($value["dmagr_status"] == 0 || $value["dmagr_status"] == 1 ) {
  //       $fechaSeparada = explode("-", $value["dmagr_fecha_deposito"]);
  //       $fechadep = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);
  //
  //       $tcant = number_format($value["dmagr_cantidad"], 2, '.', ',');
  //       $accio = "<span class='accio accio-gray'><i class='fa-solid fa-hourglass'></i> Pendiente</span>";
  //     }else if($value["dmagr_status"] == 2 ) {
  //       $fechaSeparada = explode("-", $value["dmagr_fecha_deposito"]);
  //       $fechadep = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);
  //
  //       $tcant = number_format($value["dmagr_cantidad_real"], 2, '.', ',');
  //       $accio = "<span class='accio accio-green'><i class='fa-solid fa-circle-check'></i> Autorizado</span>";
  //     } else{
  //       $fechaSeparada = explode("-", $value["dmagr_fecha_deposito"]);
  //       $fechadep = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);
  //
  //       $tcant = number_format($value["dmagr_cantidad"], 2, '.', ',');
  //       $accio = "<span class='accio accio-red'><i class='fa-solid fa-ban'></i> Cancelado</span>";
  //     }
  //
  //
  //     $row = array(
  //         'mes' => $fechadep,
  //         'cantidad'=> "$".$tcant,
  //         'status'=> $accio
  //       );
  //     $return_json[] = $row;
  //
  //   }
  //   //return the result to the ajax request and die
  //   echo json_encode(array('data' => $return_json));
  //   wp_die();
  // }
  //
  // public function mostrarTablaAgrHistoRetMas(){
  //   global $wpdb;
  //   $user = wp_get_current_user();
  //   $userid = $user->ID;
  //   $retiros = $wpdb->prefix . 'retiros_master_agr';
  //   $ruta = get_site_url();
  //   $registros = $wpdb->get_results(" SELECT month(rmagr_fecha_retiro) AS mes, year(rmagr_fecha_retiro) AS agno, rmagr_cantidad, rmagr_cantidad_real, rmagr_status, rmagr_fecha_retiro FROM $retiros WHERE rmagr_usuario = $userid ", ARRAY_A);
  //   $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
  //   '8' => 'Agosto',
  //   '9' => 'Septiembre',
  //   '10' => 'Octubre',
  //   '11' => 'Noviembre',
  //   '12' => 'Diciembre' );
  //
  //   $return_json = array();
  //
  //   if(count($registros) == 0){
  //
  //     //return the result to the ajax request and die
  //     echo json_encode(array('data' => $return_json));
  //     wp_die();
  //     return;
  //   }
  //
  //   foreach ($registros as $key => $value) {
  //
  //     $mes = $value["mes"];
  //     $tmes = $mesesNombre[$mes];
  //     $agno = (int)$value['agno'];
  //
  //     if ($value["rmagr_status"] == 0 || $value["rmagr_status"] == 1 ) {
  //       $fechaSeparada = explode("-", $value["rmagr_fecha_retiro"]);
  //       $fecharet = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);
  //
  //       $tcant = number_format($value["rmagr_cantidad"], 2, '.', ',');
  //       $accio = "<span class='accio accio-gray'><i class='fa-solid fa-hourglass'></i> Pendiente</span>";
  //     }else if($value["rmagr_status"] == 2 ) {
  //       $fechaSeparada = explode("-", $value["rmagr_fecha_retiro"]);
  //       $fecharet = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);
  //
  //       $tcant = number_format($value["rmagr_cantidad_real"], 2, '.', ',');
  //       $accio = "<span class='accio accio-green'><i class='fa-solid fa-circle-check'></i> Autorizado</span>";
  //     } else{
  //       $fechaSeparada = explode("-", $value["rmagr_fecha_retiro"]);
  //       $fecharet = date($fechaSeparada[2]."-".$fechaSeparada[1]."-".$fechaSeparada[0]);
  //
  //       $tcant = number_format($value["ragr_cantidad"], 2, '.', ',');
  //       $accio = "<span class='accio accio-red'><i class='fa-solid fa-ban'></i> Cancelado</span>";
  //     }
  //
  //
  //     $row = array(
  //         'mes' => $fecharet,
  //         'cantidad'=> "$".$tcant,
  //         'status'=> $accio
  //       );
  //     $return_json[] = $row;
  //
  //   }
  //   //return the result to the ajax request and die
  //   echo json_encode(array('data' => $return_json));
  //   wp_die();
  // }
  //
  // public function mostrarAgrTablaDepMas(){
  //   global $wpdb;
  //   $useract = wp_get_current_user();
  //   $userid = $useract->ID;
  //   $subadministrador = false;
  //
  //   if ( isset( $useract->roles ) && is_array( $useract->roles ) ) {
  //       if ( in_array( 'subadministrador', $useract->roles ) ) {
  //         $subadministrador = true;
  //     }
  //   }
  //
  //   $depositos = $wpdb->prefix . 'depositos_master_agr';
  //   $ruta = get_site_url();
  //   $registros = $wpdb->get_results(" SELECT * FROM $depositos ORDER BY dmagr_id DESC", ARRAY_A);
  //
  //   $return_json = array();
  //
  //   if(count($registros) == 0){
  //
  //     //return the result to the ajax request and die
  //     echo json_encode(array('data' => $return_json));
  //     wp_die();
  //     return;
  //   }
  //
  //   foreach ($registros as $key => $value) {
  //
  //     $user = get_userdata( absint( $value["dmagr_usuario"] ) );
  //     // $wallet = get_user_meta( $user->ID, 'wallet', true);
  //     // $walletcode = get_user_meta( $user->ID, 'walletcode', true);
  //     $email = $user->user_email;
  //     // $pais = get_user_meta( $user->ID, 'pais', true);
  //
  //     if ($user) {
  //       $nombre = $user->first_name . ' ' .$user->last_name ;
  //     }else{
  //       $nombre = 'Usuario no encontrado';
  //     }
  //
  //     $status = $value["dmagr_status"];
  //     if ($status == 0) {
  //       $statusc = "Generada";
  //       if ($subadministrador) {
  //         $acciones = "";
  //       }else{
  //         $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindepmas' data-deposito='".$value['dmagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdepmas' data-deposito='".$value['dmagr_id']."'>Cancelar</button>";
  //       }
  //     }else if($status == 1){
  //       $statusc = "Confirmada";
  //       if ($subadministrador) {
  //         $acciones = "";
  //       }else{
  //         $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindepmas' data-deposito='".$value['dmagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdepmas' data-deposito='".$value['dmagr_id']."'>Cancelar</button>";
  //       }
  //     }else if($status == 3){
  //       $statusc = "Cancelada";
  //       if ($subadministrador) {
  //         $acciones = "";
  //       }else{
  //         $acciones = "<button class='btn-finalizar-inac'>Editar</button>";
  //       }
  //     }else{
  //       $statusc = "Autorizada";
  //       if ($subadministrador) {
  //         $acciones = "";
  //       }else{
  //         $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agreditdepmas' data-deposito='".$value['dmagr_id']."'>Editar</button>";
  //       }
  //     }
  //
  //     if(!$value["dmagr_idmov_ind"]){
  //       $idmov_ind = "";
  //     }else{
  //       $idmov_ind = $value["dmagr_idmov_ind"];
  //     }
  //
  //     if(!$value["dmagr_idmov_gral"]){
  //       $idmov_gral = "";
  //     }else{
  //       $idmov_gral = $value["dmagr_idmov_gral"];
  //     }
  //
  //     if(!$value["dmagr_fecha_termino"] || $value["dmagr_fecha_termino"] == "0000-00-00"){
  //       $fechafin = "";
  //     }else{
  //       $fechafin = $value["dmagr_fecha_termino"];
  //     }
  //
  //     if($value["dmagr_cantidad_real"] == 0){
  //       $cantidadreal = "";
  //     }else{
  //       $cantidadreal = "$".number_format($value["dmagr_cantidad_real"], 2);
  //     }
  //
  //     $fecha = substr($value["dmagr_fecha_deposito"], 0, 10);
  //
  //     $cantidad  = "$".number_format($value["dmagr_cantidad"], 2);
  //     // $interes = $value["interes"]."%";
  //
  //     $notas = "<button aria-label='".$value["dmagr_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";
  //
  //     $row = array(
  //         'id' => ($key+1),
  //         'nombre' => $nombre,
  //         'cantidad' => $cantidad,
  //         'cantidadfin' => $cantidadreal,
  //         'notas' => $notas,
  //         'status' => $statusc,
  //         'idmov_ind' => $idmov_ind,
  //         'idmov_gral' => $idmov_gral,
  //         'fecha' => $fecha,
  //         'fechafin' => $fechafin,
  //         'acciones' => $acciones
  //       );
  //     $return_json[] = $row;
  //
  //   }
  //   //return the result to the ajax request and die
  //   echo json_encode(array('data' => $return_json));
  //   wp_die();
  // }
  //
  // public function mostrarAgrTablaRetMas(){
  //   global $wpdb;
  //   $useract = wp_get_current_user();
  //   $userid = $useract->ID;
  //   $subadministrador = false;
  //
  //   if ( isset( $useract->roles ) && is_array( $useract->roles ) ) {
  //       if ( in_array( 'subadministrador', $useract->roles ) ) {
  //         $subadministrador = true;
  //     }
  //   }
  //
  //   $retiros = $wpdb->prefix . 'retiros_master_agr';
  //   $ruta = get_site_url();
  //   $registros = $wpdb->get_results(" SELECT * FROM $retiros ORDER BY rmagr_id DESC", ARRAY_A);
  //
  //   $return_json = array();
  //
  //   if(count($registros) == 0){
  //
  //     //return the result to the ajax request and die
  //     echo json_encode(array('data' => $return_json));
  //     wp_die();
  //     return;
  //   }
  //
  //   foreach ($registros as $key => $value) {
  //
  //     $user = get_userdata( absint( $value["rmagr_usuario"] ) );
  //     // $wallet = get_user_meta( $user->ID, 'wallet', true);
  //     // $walletcode = get_user_meta( $user->ID, 'walletcode', true);
  //     $email = $user->user_email;
  //     // $pais = get_user_meta( $user->ID, 'pais', true);
  //
  //     if ($user) {
  //       $nombre = $user->first_name . ' ' .$user->last_name ;
  //     }else{
  //       $nombre = 'Usuario no encontrado';
  //     }
  //
  //     $cantidad  = "$".number_format($value["rmagr_cantidad"], 2);
  //     $cantidadi  = number_format($value["rmagr_cantidad"], 2);
  //
  //     if(!$value["rmagr_cantidad_real"]){
  //       $cantidadreal = "";
  //       $cantidadfini = "0.00";
  //     }else{
  //       $cantidadreal = "$".number_format($value["rmagr_cantidad_real"], 2);
  //       $cantidadfini = $value["rmagr_cantidad_real"];
  //     }
  //
  //     if(!$value["rmagr_notas"]){
  //       $notas = "";
  //     }else{
  //       $notas = $value["rmagr_notas"];
  //     }
  //
  //     $status = $value["rmagr_status"];
  //     if ($status == 0) {
  //       $statusc = "Generada";
  //       if ($subadministrador) {
  //         $acciones = "";
  //       }else{
  //         $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfinretmas' data-retiro='".$value['rmagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancretmas' data-retiro='".$value['rmagr_id']."'>Cancelar</button>";
  //       }
  //     }else if($status == 1){
  //       $statusc = "Confirmada";
  //       if ($subadministrador) {
  //         $acciones = "";
  //       }else{
  //         $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfinretmas' data-retiro='".$value['rmagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancretmas' data-retiro='".$value['rmagr_id']."'>Cancelar</button>";
  //       }
  //     }else if($status == 3){
  //       $statusc = "Cancelada";
  //       if ($subadministrador) {
  //         $acciones = "";
  //       }else{
  //         $acciones = "<button class='btn-finalizar-inac'>Editar</button>";
  //       }
  //     }else{
  //       $statusc = "Autorizada";
  //       if ($subadministrador) {
  //         $acciones = "";
  //       }else{
  //         $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agreditretmas' data-retiro='".$value['rmagr_id']."'>Editar</button>";
  //       }
  //     }
  //
  //     if(!$value["rmagr_idmov_ind"]){
  //       $idmov_ind = "";
  //     }else{
  //       $idmov_ind = $value["rmagr_idmov_ind"];
  //     }
  //
  //     if(!$value["rmagr_idmov_gral"]){
  //       $idmov_gral = "";
  //     }else{
  //       $idmov_gral = $value["rmagr_idmov_gral"];
  //     }
  //
  //     if(!$value["rmagr_fecha_termino"] || $value["rmagr_fecha_termino"] == "0000-00-00"){
  //       $fechafin = "";
  //     }else{
  //       $fechafin = $value["rmagr_fecha_termino"];
  //     }
  //
  //
  //     $notas = "<button aria-label='".$value["rmagr_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";
  //
  //
  //     $fecha = substr($value["rmagr_fecha_retiro"], 0, 10);
  //
  //     $row = array(
  //         'id' => ($key+1),
  //         'nombre' => $nombre,
  //         'cantidad' => $cantidad,
  //         'cantidadfin' => $cantidadreal,
  //         'notas' => $notas,
  //         'status' => $statusc,
  //         'idmov_ind' => $idmov_ind,
  //         'fecha' => $fecha,
  //         'fechafin' => $fechafin,
  //         'acciones' => $acciones
  //       );
  //     $return_json[] = $row;
  //
  //   }
  //   //return the result to the ajax request and die
  //   echo json_encode(array('data' => $return_json));
  //   wp_die();
  // }
  //
  // public function mostrarTablaAdmAgrConMaster(){
  //   global $wpdb;
  //   // $useract = wp_get_current_user();
  //   // $userid = $useract->ID;
  //   // $subadministrador = false;
  //
  //   // if ( isset( $useract->roles ) && is_array( $useract->roles ) ) {
  //   //     if ( in_array( 'subadministrador', $useract->roles ) ) {
  //   //       $subadministrador = true;
  //   //   }
  //   // }
  //
  //   $tabla = $wpdb->prefix . 'registros_agr';
  //   $ruta = get_site_url();
  //   $registros = $wpdb->get_results(" SELECT * FROM $tabla ORDER BY reagr_id", ARRAY_A);
  //   $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
  //   '8' => 'Agosto',
  //   '9' => 'Septiembre',
  //   '10' => 'Octubre',
  //   '11' => 'Noviembre',
  //   '12' => 'Diciembre' );
  //
  //   $return_json = array();
  //
  //   if(count($registros) == 0){
  //
  //     //return the result to the ajax request and die
  //     echo json_encode(array('data' => $return_json));
  //     wp_die();
  //     return;
  //   }
  //
  //   $calculos = new CRC_AgreCalculo();
  //   // $detallemesuser = $calculos->crc_datosproyeccion_agreinvestor(48);
  //   $detalleregistros = $calculos->crc_datosproyeccion_agreregistros();
  //   $reversed = array_reverse($detalleregistros);
  //
  //
  //   foreach ($reversed as $key => $value) {
  //
  //     $mes = $value['mes'];
  //     $agno = $value['year'];
  //     $ttotaldep = "$".number_format($value['depmes'],2);
  //     $ttdp = '<span class="verde btn-ver-depagr" data-mes='.$mes.' data-agno='.$agno.'>+ '.$ttotaldep.'</span>';
  //     $tcapini = "$".number_format($value['capini'],2);
  //     $ttotalret = "$".number_format($value['retmes'],2);
  //     $ttrt = '<span class="rojo btn-ver-retagr" data-mes='.$mes.' data-agno='.$agno.'>- '.$ttotalret.'</span>';
  //     $tutilinvestors = number_format($value['investors'],2);
  //     $ttutilinvestors = '<span class="blanco btn-ver-invagr" data-mes='.$mes.' data-agno='.$agno.'>$'.$tutilinvestors.'</span>';
  //     $notas = $value['notas'];
  //     $fechareg = $value['fecharegistro'];
  //     $utilini = "$".number_format($value['utilmes'],2);
  //     $combroker = "$".number_format($value['combroker'],2);
  //     $utilreal = "$".number_format($value['utilreal'],2);
  //     $theinc = "$".number_format($value['theinc'],2);
  //     $gopro = "$".number_format($value['gopro'],2);
  //     $promgananciasxuser = "%".number_format($value['utilinvpor'],2);
  //     $utilrealpor = "%".number_format($value['utilrealpor'],2);
  //     $totalcierremes = "$".number_format($value['totalcierremes'],2);
  //
  //     $row = array(
  //         'id' => ($key+1),
  //         'periodo' => $value['tmes']." - ".$value['year'],
  //         'depositos' => $ttdp,
  //         'capinicial' => $tcapini,
  //         'utilinicial' => $utilini,
  //         'combroker' => $combroker,
  //         'utilreal' => $utilreal,
  //         'investors' => $ttutilinvestors,
  //         'theinc' => $theinc,
  //         'gopro' => $gopro,
  //         'utilrealpor' => $utilrealpor,
  //         'utilinvpor' => $promgananciasxuser,
  //         'retiros' => $ttrt,
  //         'totalcierremes' => $totalcierremes,
  //         'notas' => $notas,
  //         'fecharegistro' => $fechareg
  //       );
  //     $return_json[] = $row;
  //
  //   }
  //   //return the result to the ajax request and die
  //   echo json_encode(array('data' => $return_json));
  //   wp_die();
  // }
  //
  public function mostrarTablaConUserDepMes(){
    if(isset($_POST['year'])){
      global $wpdb;

      $agno = (int) $_POST['year'];
      $mesint = (int) $_POST['mes'];
      $user = (int) $_POST['id'];

      $depositos = $wpdb->prefix . 'depositos_con';
      $ruta = get_site_url();
      $registros = $wpdb->get_results(" SELECT * , month(dcon_fecha_termino) AS mes, year(dcon_fecha_termino) AS agno FROM $depositos WHERE month(dcon_fecha_termino) = $mesint AND year(dcon_fecha_termino) = $agno AND dcon_status = 2 AND dcon_usuario = $user ", ARRAY_A);

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

        $status = $value["dcon_status"];
        if ($status == 0) {
          $statusc = "<span class='accio accio-gray'><i class='fa-solid fa-hourglass'></i> Pendiente</span>";
          // $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindep' data-deposito='".$value['dagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdep' data-deposito='".$value['dagr_id']."'>Cancelar</button>";
        }else if($status == 1){
          $statusc = "<span class='accio accio-gray'><i class='fa-solid fa-hourglass'></i> Pendiente</span>";
          // $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindep' data-deposito='".$value['dagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdep' data-deposito='".$value['dagr_id']."'>Cancelar</button>";
        }else if($status == 2){
          $statusc = "<span class='accio accio-green'><i class='fa-solid fa-circle-check'></i> Autorizado</span>";
          // $acciones = "<button class='btn-finalizar-inac'>Editar</button>";
        }else{
          $statusc = "<span class='accio accio-red'><i class='fa-solid fa-ban'></i> Cancelado</span>";
          // $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agreditdep' data-deposito='".$value['dagr_id']."'>Editar</button>";
        }

        if(!$value["dcon_idmov_ind"]){
          $idmov_ind = "";
        }else{
          $idmov_ind = $value["dcon_idmov_ind"];
        }

        if(!$value["dcon_idmov_gral"]){
          $idmov_gral = "";
        }else{
          $idmov_gral = $value["dcon_idmov_gral"];
        }

        if(!$value["dcon_fecha_termino"]){
          $fechafin = "";
        }else{
          $fechafin = $value["dcon_fecha_termino"];
        }

        if($value["dcon_cantidad_real"] == 0){
          $cantidadreal = "";
        }else{
          $cantidadreal = "$".number_format($value["dcon_cantidad_real"], 2);
        }

        $fecha = substr($value["dcon_fecha_deposito"], 0, 10);

        $cantidad  = "$".number_format($value["dcon_cantidad"], 2);
        // $interes = $value["interes"]."%";

        $notas = "<button aria-label='".$value["dcon_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";

        $row = array(
            'id' => ($key+1),
            'cantidad' => $cantidad,
            'cantidad_final' => $cantidadreal,
            'notas' => $notas,
            'status' => $statusc,
            'id_deposito_ind' => $idmov_ind,
            'id_deposito_gral' => $idmov_gral,
            'fecha_solicitud' => $fecha,
            'fecha_fin' => $fechafin
          );
        $return_json[] = $row;

      }
      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
    }
  }
  //
  // public function mostrarTablaAgrDetalleDepMasMes(){
  //   if(isset($_POST['year'])){
  //     global $wpdb;
  //
  //     $agno = (int) $_POST['year'];
  //     $mesint = (int) $_POST['mes'];
  //
  //     $depositos = $wpdb->prefix . 'depositos_master_agr';
  //     $ruta = get_site_url();
  //     $registros = $wpdb->get_results(" SELECT * , month(dmagr_fecha_termino) AS mes, year(dmagr_fecha_termino) AS agno FROM $depositos WHERE month(dmagr_fecha_termino) = $mesint AND year(dmagr_fecha_termino) = $agno AND dmagr_status = 2", ARRAY_A);
  //
  //     $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
  //     '8' => 'Agosto',
  //     '9' => 'Septiembre',
  //     '10' => 'Octubre',
  //     '11' => 'Noviembre',
  //     '12' => 'Diciembre' );
  //
  //     $return_json = array();
  //
  //     if(count($registros) == 0){
  //
  //       //return the result to the ajax request and die
  //       echo json_encode(array('data' => $return_json));
  //       wp_die();
  //       return;
  //     }
  //
  //     foreach ($registros as $key => $value) {
  //
  //       $user = get_userdata( absint( $value["dmagr_usuario"] ) );
  //       $email = $user->user_email;
  //
  //       if ($user) {
  //         $nombre = $user->first_name . ' ' .$user->last_name ;
  //       }else{
  //         $nombre = 'Usuario no encontrado';
  //       }
  //
  //       $status = $value["dmagr_status"];
  //       if ($status == 0) {
  //         $statusc = "Generada";
  //         // $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindep' data-deposito='".$value['dagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdep' data-deposito='".$value['dagr_id']."'>Cancelar</button>";
  //       }else if($status == 1){
  //         $statusc = "Confirmada";
  //         // $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindep' data-deposito='".$value['dagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdep' data-deposito='".$value['dagr_id']."'>Cancelar</button>";
  //       }else if($status == 3){
  //         $statusc = "Cancelada";
  //         // $acciones = "<button class='btn-finalizar-inac'>Editar</button>";
  //       }else{
  //         $statusc = "Autorizada";
  //         // $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agreditdep' data-deposito='".$value['dagr_id']."'>Editar</button>";
  //       }
  //
  //       if(!$value["dmagr_idmov_ind"]){
  //         $idmov_ind = "";
  //       }else{
  //         $idmov_ind = $value["dmagr_idmov_ind"];
  //       }
  //
  //       if(!$value["dmagr_idmov_gral"]){
  //         $idmov_gral = "";
  //       }else{
  //         $idmov_gral = $value["dmagr_idmov_gral"];
  //       }
  //
  //       if(!$value["dmagr_fecha_termino"]){
  //         $fechafin = "";
  //       }else{
  //         $fechafin = $value["dmagr_fecha_termino"];
  //       }
  //
  //       if($value["dmagr_cantidad_real"] == 0){
  //         $cantidadreal = "";
  //       }else{
  //         $cantidadreal = "$".number_format($value["dmagr_cantidad_real"], 2);
  //       }
  //
  //       $fecha = substr($value["dmagr_fecha_deposito"], 0, 10);
  //
  //       $cantidad  = "$".number_format($value["dmagr_cantidad"], 2);
  //       // $interes = $value["interes"]."%";
  //
  //       $notas = "<button aria-label='".$value["dmagr_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";
  //
  //       $row = array(
  //           'id' => ($key+1),
  //           'nombre' => $nombre,
  //           'cantidad' => $cantidad,
  //           'cantidadfin' => $cantidadreal,
  //           'notas' => $notas,
  //           'status' => $statusc,
  //           'idmov_ind' => $idmov_ind,
  //           'idmov_gral' => $idmov_gral,
  //           'fecha' => $fecha,
  //           'fechafin' => $fechafin
  //         );
  //       $return_json[] = $row;
  //
  //     }
  //     //return the result to the ajax request and die
  //     echo json_encode(array('data' => $return_json));
  //     wp_die();
  //   }
  // }
  //
  public function mostrarTablaConUserRetMes(){
    if(isset($_POST['year'])){
      global $wpdb;

      $agno = (int) $_POST['year'];
      $mesint = (int) $_POST['mes'];
      $user = (int) $_POST['id'];

      $retiros = $wpdb->prefix . 'retiros_con';
      $ruta = get_site_url();
      $registros = $wpdb->get_results(" SELECT * , month(rcon_fecha_termino) AS mes, year(rcon_fecha_termino) AS agno FROM $retiros WHERE month(rcon_fecha_termino) = $mesint AND year(rcon_fecha_termino) = $agno AND rcon_status = 2 AND rcon_usuario = $user ", ARRAY_A);

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

        $status = $value["rcon_status"];
        if ($status == 0) {
          $statusc = "<span class='accio accio-gray'><i class='fa-solid fa-hourglass'></i> Pendiente</span>";
          // $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindep' data-deposito='".$value['dagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdep' data-deposito='".$value['dagr_id']."'>Cancelar</button>";
        }else if($status == 1){
          $statusc = "<span class='accio accio-gray'><i class='fa-solid fa-hourglass'></i> Pendiente</span>";
          // $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindep' data-deposito='".$value['dagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdep' data-deposito='".$value['dagr_id']."'>Cancelar</button>";
        }else if($status == 2){
          $statusc = "<span class='accio accio-green'><i class='fa-solid fa-circle-check'></i> Autorizado</span>";
          // $acciones = "<button class='btn-finalizar-inac'>Editar</button>";
        }else{
          $statusc = "<span class='accio accio-red'><i class='fa-solid fa-ban'></i> Cancelado</span>";
          // $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agreditdep' data-deposito='".$value['dagr_id']."'>Editar</button>";
        }

        if(!$value["rcon_idmov_ind"]){
          $idmov_ind = "";
        }else{
          $idmov_ind = $value["rcon_idmov_ind"];
        }

        if(!$value["rcon_idmov_gral"]){
          $idmov_gral = "";
        }else{
          $idmov_gral = $value["rcon_idmov_gral"];
        }

        if(!$value["rcon_fecha_termino"]){
          $fechafin = "";
        }else{
          $fechafin = $value["rcon_fecha_termino"];
        }

        if($value["rcon_cantidad_real"] == 0){
          $cantidadreal = "";
        }else{
          $cantidadreal = "$".number_format($value["rcon_cantidad_real"], 2);
        }

        $fecha = substr($value["rcon_fecha_retiro"], 0, 10);

        $cantidad  = "$".number_format($value["rcon_cantidad"], 2);
        // $interes = $value["interes"]."%";

        $notas = "<button aria-label='".$value["rcon_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";

        $row = array(
            'id' => ($key+1),
            'nombre' => $nombre,
            'cantidad' => $cantidad,
            'cantidad_final' => $cantidadreal,
            'notas' => $notas,
            'status' => $statusc,
            'id_retiro_ind' => $idmov_ind,
            'fecha_solicitud' => $fecha,
            'fecha_fin' => $fechafin
          );
        $return_json[] = $row;

      }
      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
    }
  }
  //
  // public function mostrarTablaAgrDetalleRetMasMes(){
  //   if(isset($_POST['year'])){
  //     global $wpdb;
  //
  //     $agno = (int) $_POST['year'];
  //     $mesint = (int) $_POST['mes'];
  //
  //     $retiros = $wpdb->prefix . 'retiros_master_agr';
  //     $ruta = get_site_url();
  //     $registros = $wpdb->get_results(" SELECT * , month(rmagr_fecha_termino) AS mes, year(rmagr_fecha_termino) AS agno FROM $retiros WHERE month(rmagr_fecha_termino) = $mesint AND year(rmagr_fecha_termino) = $agno AND rmagr_status = 2", ARRAY_A);
  //
  //     $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
  //     '8' => 'Agosto',
  //     '9' => 'Septiembre',
  //     '10' => 'Octubre',
  //     '11' => 'Noviembre',
  //     '12' => 'Diciembre' );
  //
  //     $return_json = array();
  //
  //     if(count($registros) == 0){
  //
  //       //return the result to the ajax request and die
  //       echo json_encode(array('data' => $return_json));
  //       wp_die();
  //       return;
  //     }
  //
  //     foreach ($registros as $key => $value) {
  //
  //       $user = get_userdata( absint( $value["rmagr_usuario"] ) );
  //       $email = $user->user_email;
  //
  //       if ($user) {
  //         $nombre = $user->first_name . ' ' .$user->last_name ;
  //       }else{
  //         $nombre = 'Usuario no encontrado';
  //       }
  //
  //       $status = $value["rmagr_status"];
  //       if ($status == 0) {
  //         $statusc = "Generada";
  //         // $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindep' data-deposito='".$value['dagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdep' data-deposito='".$value['dagr_id']."'>Cancelar</button>";
  //       }else if($status == 1){
  //         $statusc = "Confirmada";
  //         // $acciones = "<button class='button button-primary button-large btn-finalizar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrfindep' data-deposito='".$value['dagr_id']."'>Autorizar</button><button class='button button-primary button-large btn-cancelar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agrcancdep' data-deposito='".$value['dagr_id']."'>Cancelar</button>";
  //       }else if($status == 3){
  //         $statusc = "Cancelada";
  //         // $acciones = "<button class='btn-finalizar-inac'>Editar</button>";
  //       }else{
  //         $statusc = "Autorizada";
  //         // $acciones = "<button class='button button-primary button-large btn-editar' type='button' data-bs-toggle='modal' data-bs-target='#modal-agreditdep' data-deposito='".$value['dagr_id']."'>Editar</button>";
  //       }
  //
  //       if(!$value["rmagr_idmov_ind"]){
  //         $idmov_ind = "";
  //       }else{
  //         $idmov_ind = $value["rmagr_idmov_ind"];
  //       }
  //
  //       // if(!$value["rmagr_idmov_gral"]){
  //       //   $idmov_gral = "";
  //       // }else{
  //       //   $idmov_gral = $value["rmagr_idmov_gral"];
  //       // }
  //
  //       if(!$value["rmagr_fecha_termino"]){
  //         $fechafin = "";
  //       }else{
  //         $fechafin = $value["rmagr_fecha_termino"];
  //       }
  //
  //       if($value["rmagr_cantidad_real"] == 0){
  //         $cantidadreal = "";
  //       }else{
  //         $cantidadreal = "$".number_format($value["rmagr_cantidad_real"], 2);
  //       }
  //
  //       $fecha = substr($value["rmagr_fecha_retiro"], 0, 10);
  //
  //       $cantidad  = "$".number_format($value["rmagr_cantidad"], 2);
  //       // $interes = $value["interes"]."%";
  //
  //       $notas = "<button aria-label='".$value["rmagr_notas"]."' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button>";
  //
  //       $row = array(
  //           'id' => ($key+1),
  //           'nombre' => $nombre,
  //           'cantidad' => $cantidad,
  //           'cantidadfin' => $cantidadreal,
  //           'notas' => $notas,
  //           'status' => $statusc,
  //           'idmov_ind' => $idmov_ind,
  //           'fecha' => $fecha,
  //           'fechafin' => $fechafin
  //         );
  //       $return_json[] = $row;
  //
  //     }
  //     //return the result to the ajax request and die
  //     echo json_encode(array('data' => $return_json));
  //     wp_die();
  //   }
  // }
  //
  // public function mostrarTablaAdmAgrInvMes(){
  //   global $wpdb;
  //   // $useract = wp_get_current_user();
  //   // $userid = $useract->ID;
  //   // $subadministrador = false;
  //
  //   $agno = (int) $_POST['year'];
  //   $mesint = (int) $_POST['mes'];
  //
  //   // if ( isset( $useract->roles ) && is_array( $useract->roles ) ) {
  //   //     if ( in_array( 'subadministrador', $useract->roles ) ) {
  //   //       $subadministrador = true;
  //   //   }
  //   // }
  //
  //   $tabla = $wpdb->prefix . 'registros_agr';
  //   $ruta = get_site_url();
  //   $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE reagr_mes = $mesint AND reagr_year = $agno ORDER BY reagr_id LIMIT 1", ARRAY_A);
  //   $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
  //   '8' => 'Agosto',
  //   '9' => 'Septiembre',
  //   '10' => 'Octubre',
  //   '11' => 'Noviembre',
  //   '12' => 'Diciembre' );
  //
  //   $return_json = array();
  //
  //   if(count($registros) == 0){
  //
  //     //return the result to the ajax request and die
  //     echo json_encode(array('data' => $return_json));
  //     wp_die();
  //     return;
  //   }
  //
  //   foreach ($registros as $key => $value) {
  //
  //     $mes = (int)$value['reagr_mes'];
  //     $tmes = $mesesNombre[$mes];
  //     $agno = (int)$value['reagr_year'];
  //
  //     $listausers = json_decode($value['reagr_usuarios'],true);
  //
  //     // Checamos cuantos users hay
  //     if (count($listausers) == 0) {
  //       $porinver = 0;
  //       $poradmins = 100 ;
  //     }else{
  //       $porinver = (float)$value['reagr_por_inver'];
  //       $poradmins = 100 - $porinver ;
  //     }
  //
  //
  //     // Rellenamos el array de la tabla
  //
  //     foreach ($listausers as $llave => $valor) {
  //
  //       $user = get_userdata( absint( $valor ) );
  //       $wallet = get_user_meta( $user->ID, 'wallet', true);
  //       $walletcode = get_user_meta( $user->ID, 'walletcode', true);
  //       $email = $user->user_email;
  //       $pais = get_user_meta( $user->ID, 'pais', true);
  //
  //       if ($user) {
  //         $nombre = $user->first_name . ' ' .$user->last_name ;
  //       }else{
  //         $nombre = 'Usuario no encontrado';
  //       }
  //
  //       $calculos = new CRC_AgreCalculo();
  //       $detalleregistros = $calculos->crc_datosmes_agreinvestor($user->ID, $mesint, $agno);
  //
  //       if(empty($detalleregistros)){
  //         // $ttotaldep = "$".number_format($detalleregistros[0]['depmes'],2);
  //         $ttdp = '<span class="verde " data-mes='.$mes.' data-agno='.$agno.'>+ $0</span>';
  //
  //         // $ttotalret = "$".number_format($detalleregistros[0]['retmes'],2);
  //         $ttrt = '<span class="rojo " data-mes='.$mes.' data-agno='.$agno.'>- $0</span>';
  //
  //         $tcapini = "$0";
  //         $porparticipuser = "0";
  //         $utilidaduser = "$0";
  //         $utilidadacum = "$0";
  //         $total = "$0";
  //         $rendimientomes = "%0";
  //
  //       }else{
  //         $ttotaldep = "$".number_format($detalleregistros[0]['depmes'],2);
  //         $ttdp = '<span class="verde " data-mes='.$mes.' data-agno='.$agno.'>+ '.$ttotaldep.'</span>';
  //
  //         $ttotalret = "$".number_format($detalleregistros[0]['retmes'],2);
  //         $ttrt = '<span class="rojo " data-mes='.$mes.' data-agno='.$agno.'>- '.$ttotalret.'</span>';
  //
  //         $tcapini = "$".number_format($detalleregistros[0]['capini'],2);
  //         $porparticipuser = $detalleregistros[0]['porparticipuser'];
  //         $utilidaduser = "$".number_format($detalleregistros[0]['utilidad'],2);
  //         $utilidadacum = "$".number_format($detalleregistros[0]['utilacumulada'],2);
  //         $total = "$".number_format($detalleregistros[0]['total'],2);
  //         $rendimientomes = "%".number_format($detalleregistros[0]['rendimientomes'],2);
  //
  //       }
  //
  //
  //       $row = array(
  //           'id' => ($key+1),
  //           'nombre' => $nombre,
  //           'depositos' => $ttdp,
  //           'capinicial' => $tcapini,
  //           'porparticip' => "%".$porparticipuser,
  //           'utilmes' => $utilidaduser,
  //           'utilacum' => $utilidadacum,
  //           'total' =>  $total,
  //           'porrendimiento' => $rendimientomes,
  //           'retiros' => $ttrt
  //         );
  //       $return_json[] = $row;
  //
  //     }
  //
  //   }
  //   //return the result to the ajax request and die
  //   echo json_encode(array('data' => $return_json));
  //   wp_die();
  // }
  //
  public function mostrarTablaConListaUsuarios(){
    global $wpdb;
    $userconservador = get_users(array(
    'meta_key' => 'modconservador',
    'meta_value' => 1
    ));

    $listausers = array();

    if (count($userconservador) ==  0) {

    }else {
      foreach ($userconservador as $key => $value) {
        $valid = $value->ID;
        $listausers[] = $value->ID;

      }
    }

    $return_json = array();

    $depositos = $wpdb->prefix . 'depositos_con';
    $registros = $wpdb->get_results(" SELECT DISTINCT(dcon_usuario) FROM $depositos WHERE dcon_status = 2 ", ARRAY_A);

    $listausers2 = array();

    if(count($registros) == 0){
    }else {
      foreach ($registros as $key => $value) {
        $listausers2[] = (int)$value["dcon_usuario"];
      }
    }

    $listausers3 = array_merge($listausers, $listausers2);
    $listausers4 = array_unique($listausers3);

    if(count($listausers4) == 0){

      //return the result to the ajax request and die
      echo json_encode(array('data' => $return_json));
      wp_die();
      return;
    }

    foreach ($listausers4 as $key => $value) {
      $userid = absint( $value );
      $user = get_userdata( absint( $value ) );
      if ( isset( $user->roles ) && is_array( $user->roles ) ) {

            $wallet = get_user_meta( $user->ID, 'wallet', true);
            $walletcode = get_user_meta( $user->ID, 'walletcode', true);
            $email = $user->user_email;
            $pais = get_user_meta( $user->ID, 'pais', true);
            $activo = get_user_meta( $user->ID, 'modconservador', true);

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
              if ( in_array( 'administrator', $user->roles ) || in_array( 'subadministrador', $user->roles ) ) {
                $acceso = "Sí";
              }else {
                $acceso = "No";
              }
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
    //return the result to the ajax request and die
    echo json_encode(array('data' => $return_json));
    wp_die();
  }

  public function operacion_agregarconstatus(){
    if(isset($_POST['id'])){
      global $wpdb;

      $id = (int) $_POST['id'];

      $tabla = $wpdb->prefix.'nuevosstatus_con';

      //$rows = $wpdb->query( $wpdb->prepare("SELECT * FROM $tabla WHERE codigo = %s ", $codigo ))
      $mes = (int)$_POST['mes'];
      $year = (int)$_POST['year'];
      $porcentaje = (float)$_POST['porcentaje'];
      $tipo = (int)$_POST['tipo'];
      $notas = $_POST['notas'];

      $datos = array(
          'nscon_mes'=>$mes,
          'nscon_year'=>$year,
          'nscon_usuario'=>$id,
          'nscon_porcentaje'=>$porcentaje,
          'nscon_notas'=>$notas,
          'nscon_tipo'=>$tipo
          );

      $formato = array(
      '%d',
      '%d',
      '%d',
      '%f',
      '%s',
      '%d'
      );

      $resultado =  $wpdb->insert($tabla, $datos, $formato);

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

  public function operacion_editstatus(){
    if(isset($_POST['id'])){
      global $wpdb;

      date_default_timezone_set("America/Mexico_City");

      $id = (int) $_POST['id'];
      $mes = (int)$_POST['mes'];
      $year = (int)$_POST['year'];
      $porcentaje = (float)$_POST['porcentaje'];
      $tipo = (int)$_POST['tipo'];
      $notas = $_POST['notas'];

      $tabla = $wpdb->prefix.'nuevosstatus_con';

      $datos = [
        'nscon_mes'=>$mes,
        'nscon_year'=>$year,
        'nscon_porcentaje'=>$porcentaje,
        'nscon_notas'=>$notas,
        'nscon_tipo'=>$tipo
      ];

      $formato = [
        '%d',
        '%d',
        '%f',
        '%s',
        '%d'
      ];

      $donde = [
        'nscon_id' => $id
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

  public function operacion_elimstatus(){
    if(isset($_POST['id'])){
      global $wpdb;
      $tabla = $wpdb->prefix.'nuevosstatus_con';

      date_default_timezone_set("America/Mexico_City");

      $id = (int)$_POST['id'];
      $resultado = $wpdb->delete($tabla, array('nscon_id'=>$id), array('%d'));

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


  // public function traer_datos_registro_agr(){
  //   if(isset($_POST['tipo'])){
  //     global $wpdb;
  //
  //     date_default_timezone_set("America/Tijuana");
  //
  //     $tipo = $_POST['tipo'];
  //
  //     if ($tipo == "editRegistro") {
  //       $tabla = $wpdb->prefix.'registros_agr';
  //
  //       $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE reagr_id = $id ORDER BY reagr_id DESC", ARRAY_A);
  //
  //       $return_json = array();
  //
  //       if(count($registros) == 0){
  //
  //         //return the result to the ajax request and die
  //         echo json_encode(array('data' => $return_json));
  //         wp_die();
  //         return;
  //       }
  //
  //       foreach ($registros as $key => $value) {
  //
  //         $utilmes = $value['reagr_util_mes'];
  //         $combro = $value['reagr_com_bro'];
  //         $por_inver= $value['reagr_por_inver'];
  //         $por_refer = $value['reagr_por_refer'];
  //         $mes = $value['reagr_mes'];
  //         $year = $value['reagr_year'];
  //         $notas = $value['reagr_notas'];
  //
  //         $row = array(
  //             'mes' => $mes,
  //             'year' => $year,
  //             'utilmes' => $utilmes,
  //             'combro' => $combro,
  //             'por_inver'=> $por_inver,
  //             'por_refer'=>$por_refer,
  //             'notas' => $notas
  //           );
  //         $return_json[] = $row;
  //
  //       }
  //       //return the result to the ajax request and die
  //       echo json_encode(array('data' => $return_json));
  //       wp_die();
  //
  //     }else{
  //
  //       $mes = (int)$_POST['mes'];
  //       $agno = (int)$_POST['year'];
  //
  //       $tabla = $wpdb->prefix.'registros_agr';
  //
  //       $registros = $wpdb->get_results(" SELECT * FROM $tabla WHERE reagr_mes = $mes AND reagr_year = $agno ORDER BY reagr_id DESC", ARRAY_A);
  //
  //       $return_json = array();
  //
  //       if(count($registros) == 0){
  //         //return the result to the ajax request and die
  //         echo json_encode(array('data' => $return_json));
  //         wp_die();
  //         return;
  //       }
  //
  //       foreach ($registros as $key => $value) {
  //
  //         $id = $value['reagr_id'];
  //         $utilmes = $value['reagr_util_mes'];
  //         $combro = $value['reagr_com_bro'];
  //         $por_inver= $value['reagr_por_inver'];
  //         $por_refer = $value['reagr_por_refer'];
  //         $mes = $value['reagr_mes'];
  //         $year = $value['reagr_year'];
  //         $notas = $value['reagr_notas'];
  //
  //         $row = array(
  //             'id' => $id,
  //             'mes' => $mes,
  //             'year' => $year,
  //             'utilmes' => $utilmes,
  //             'combro' => $combro,
  //             'por_inver'=> $por_inver,
  //             'por_refer'=>$por_refer,
  //             'notas' => $notas
  //           );
  //         $return_json[] = $row;
  //
  //       }
  //       //return the result to the ajax request and die
  //       echo json_encode(array('data' => $return_json));
  //       wp_die();
  //     }
  //
  //     die(json_encode($respuesta));
  //
  //   }
  //
  // }
  //
  // public function referral_agregarregistro_agr(){
  //   if(isset($_POST['mes'])){
  //     global $wpdb;
  //     $tabla = $wpdb->prefix.'registros_agr';
  //     $tabladep = $wpdb->prefix.'depositos_agr';
  //
  //     date_default_timezone_set("America/Tijuana");
  //
  //     // $cid = $_POST['cid'];
  //     $mes = (int)$_POST['mes'];
  //     $year = (int)$_POST['year'];
  //     $utilmes = (float)$_POST['utilmes'];
  //     $combro = (float)$_POST['combro'];
  //     $por_inver = (float)$_POST['por_inver'];
  //     $por_refer = (float)$_POST['por_refer'];
  //
  //     // BUSCAMOS A TODOS LOS USUARIOS QUE PARTICIPAN EN AGRESIVO:
  //
  //     $useragresivo = get_users(array(
  //     'meta_key' => 'modagresivo',
  //     'meta_value' => 1
  //     ));
  //
  //     $listausers = array();
  //
  //     if (count($useragresivo) ==  0) {
  //
  //     }else {
  //       foreach ($useragresivo as $key => $value) {
  //         $valid = $value->ID;
  //         $registros = $wpdb->get_results(" SELECT * FROM $tabladep WHERE dagr_usuario = $valid AND dagr_status = 2 ", ARRAY_A);
  //
  //         if (count($registros) != 0) {
  //           $listausers[] = $value->ID;
  //         }
  //
  //       }
  //     }
  //
  //     $listausers_json = json_encode($listausers);
  //
  //     // $fecha_retiro = date("Y-m-d");
  //
  //     $datos = array(
  //         'reagr_mes'=>$mes,
  //         'reagr_year'=>$year,
  //         'reagr_util_mes'=>$utilmes,
  //         'reagr_com_bro'=>$combro,
  //         'reagr_por_inver'=>$por_inver,
  //         'reagr_por_refer'=>$por_refer,
  //         'reagr_status'=>1,
  //         'reagr_usuarios'=>$listausers_json,
  //         'reagr_fecha_control'=>$_POST['fecha'],
  //         'reagr_notas'=>sanitize_text_field($_POST['notas'])
  //         );
  //
  //     $formato = array(
  //     '%d',
  //     '%d',
  //     '%f',
  //     '%f',
  //     '%f',
  //     '%f',
  //     '%d',
  //     '%s',
  //     '%s',
  //     '%s'
  //     );
  //
  //     $resultado =  $wpdb->insert($tabla, $datos, $formato);
  //
  //     if($resultado==1){
  //
  //       $respuesta = array(
  //         'respuesta'=>1
  //       );
  //     }else{
  //       $respuesta=array(
  //         'respuesta'=>'error'
  //       );
  //     }
  //
  //   }
  //   die(json_encode($respuesta));
  // }

  public function generar_codigo(){
    $codigo = '';
    $pattern = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $max = strlen($pattern)-1;
    for($i=0;$i < 6;$i++) $codigo .= $pattern[mt_rand(0,$max)];
    return $codigo;
  }

}

 ?>
