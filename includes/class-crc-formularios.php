<?php

class CRC_Formularios{

  /** Crear shortcode, utiliza [mid_registrar_mascota_shortcode] */
  public function campos_formulario(){

    $prefix = 'crc_usuario_';

    $cmb = new_cmb2_box(array(
      'id'  => $prefix . 'registrar',
      'object_types'  => array('page'),
      'hookup' => false,
      'save_fields' => false,
    ));
    $cmb->add_field(array(
      'name' => esc_html__( 'Registro de usuario','registrousuario' ),
      'type' => 'title',
      'id' => $prefix . 'titulo_registro_usuario'
    ));
    $cmb->add_field(array(
      'name' => esc_html__( 'Nombre:','registrousuario' ),
      'id' => $prefix . 'nombre',
      'type' => 'text',
      'attributes' => array(
        'data-validation' => 'required',
      )
    ));

    $cmb->add_field(array(
      'name' => esc_html__( 'Apellidos:','registrousuario' ),
      'id' => $prefix . 'apellidos',
      'type' => 'text',
      'attributes' => array(
        'data-validation' => 'required',
      )
    ));

    $cmb->add_field(array(
      'name' => esc_html__( 'Nombre de usuario:','registrousuario' ),
      'id' => $prefix . 'nickname',
      'type' => 'text',
      'attributes' => array(
        'data-validation' => 'required',
      ),
      'after_field' => '<span class="hay-user">'.__( 'Ya hay un usuario registrado con el nombre de usuario.', 'registrousuario' ).'</span><img src="'.plugin_dir_url( __DIR__ ).'assets/img/ajax-loading-gif.gif'.'" alt="" id="mascotas-load" class="mascotas-load">'
    ));

    $cmb->add_field(array(
      'name' => esc_html__( 'Password:','registrousuario' ),
      'id' => $prefix . 'password',
      'type' => 'text',
      'attributes' => array(
        'type' => 'password',
        'data-validation' => 'required',
      )
    ));

    $cmb->add_field( array(
		'name' => esc_html__( 'Email:', 'registrousuario' ),
		'id'   => $prefix . 'email',
		'type' => 'text_email',
    'attributes' => array(
      'data-validation' => 'required',
    ),
    'after_field' => '<span class="hay-user">'.__( 'Ya hay un usuario registrado con el email.', 'registrousuario' ).'</span><img src="'.plugin_dir_url( __DIR__ ).'assets/img/ajax-loading-gif.gif'.'" alt="" id="mascotas-load" class="mascotas-load">'
		// 'repeatable' => true,
	) );

    /*$cmb->add_field( array(
      'name'             => esc_html__( 'Status:','registrousuario' ),
      'id'               => $prefix . 'status',
      'type'             => 'select',
      'options'          => array(
        ''     => __( 'Seleccione un porcentaje', 'cmb2' ),
        '5'  =>  __('5 %', 'cmb2' ),
        '10'  =>  __('10 %', 'cmb2' ),
        '12'   =>  __('12 %', 'cmb2' ),
        '15'  =>  __('15 %', 'cmb2' )
      ),
      'attributes' => array(
        'data-validation' => 'required',
      )
    ) );*/

    $cmb->add_field( array(
      'name'             => esc_html__( 'Wallet:','registrousuario' ),
      'id'               => $prefix . 'wallet',
      'type'             => 'select',
      'options'          => array(
        ''     => __( 'Seleccione un tipo', 'registrousuario' ),
        'ethereum'  =>  __('Ethereum', 'registrousuario' ),
        'bitcoin'  =>  __('Bitcoin', 'registrousuario' ),
        'usdt'  =>  __('USDT (Tether)', 'registrousuario' ),
      ),
      'attributes' => array(
        'data-validation' => 'required',
      )
    ) );

    $cmb->add_field( array(
      'name'             => esc_html__( 'Número de wallet:','registrousuario' ),
      'id'               => $prefix . 'walletcode',
      'type' => 'text',
      'attributes' => array(
        'data-validation' => 'required',
      )
    ) );

    $cmb->add_field( array(
      'name'             => esc_html__( 'Pais:','registrousuario' ),
      'id'               => $prefix . 'pais',
      'type' => 'select',
      'options'          => array(
        ''     => __( 'Seleccione un pais', 'registrousuario' ),
        'USA'  =>  __('Estados Unidos', 'registrousuario' ),
        'MEX'  =>  __('México', 'registrousuario' ),
      ),
      'attributes' => array(
        'data-validation' => 'required',
      )
    ) );

    $cmb->add_field( array(
      'name'             => esc_html__( 'Estado:','registrousuario' ),
      'id'               => $prefix . 'estado',
      'type' => 'select',
      'options'          => array(
        ''     => __( 'Seleccione una opción', 'registrousuario' )
      ),
      'attributes' => array(
        'data-validation' => 'required',
      )
    ) );

    $cmb->add_field( array(
      'name'             => esc_html__( 'Municipio:','registrousuario' ),
      'id'               => $prefix . 'municipio',
      'type' => 'text',
      'attributes' => array(
        'data-validation' => 'required',
      )
    ) );

    $cmb->add_field(array(
      'name' => esc_html__( 'Calle:','registrousuario' ),
      'id' => $prefix . 'calle',
      'type' => 'text',
      'attributes' => array(
      )
    ));

    $cmb->add_field( array(
      'name'       => esc_html__( 'Código postal:', 'registrousuario' ),
      'id'         => $prefix . 'zipcode',
      'type'       => 'text',
      'attributes' => array(
    		'type' => 'number',
    		'pattern' => '\d*',
        'min' => '0',
		    'max' => '99999',
        'data-validation' => 'required',
    	),
      // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
      ) );

      $cmb->add_field(array(
        'name' => esc_html__( 'Código de invitación:','registrousuario' ),
        'id' => $prefix . 'referido',
        'type' => 'text',
        'attributes' => array(
        )
      ));


}


// Obtiene la instancia del formulario
public function formulario_instancia(){
  //id del metabox
  $metabox_id = 'crc_usuario_registrar';
  //No aplica el object id ya qu se va generar automaticamente al crearlo
  $object_id = 'fake-object-id';

  return cmb2_get_metabox($metabox_id, $object_id);
}


public function formulario_registrar_usuario_shortcode($atts){

  // the default $atts values
  $defaults = array( 'exito' => 'false');

  // use array merge to override values in $defaults with those in $atts
  $atts = shortcode_atts( $defaults, $atts );

  $exito = $atts['exito'];

  //obtener el ID del formulario para imprimir el formulario en HTML
  $cmb = $this->formulario_instancia();

  $output = '';

  if($exito == 'true' ){
    // Obtener algún error
      if ( ( $error = $cmb->prop( 'submission_error' ) ) && is_wp_error( $error ) ) {
      // If there was an error with the submission, add it to our ouput.
      $output .= '<h3 class="fracaso-registro">' . sprintf( __( 'Hubo un error: %s', 'registrousuario' ), '<strong>'. $error->get_error_message() .'</strong>' ) . '</h3>';
    }

      // si la receta se envia correctamente, notificar al usuario
      if ( isset( $_GET['user_submitted'] ) && ( $user = get_userdata( absint( $_GET['user_submitted'] ) ) ) ) {

      // Get submitter's name
      $nombre = $user->first_name;
      $nick = $user->user_login;
      $nick = $nick ? ' '. $nick : '';
      $mail = $user->user_email;
      $pass = $user->user_pass;

      // Imprimir un aviso.
      $output .= '<h2 class="gracias">¡GRACIAS!</h2><h3 class="exito-registro">' . sprintf( __( '%s has sido registrado/a en nuestra base de datos de usuarios con éxito.', 'registrousuario' ), esc_html( $nombre ) ) . '</h3><p>' . sprintf( 'Tus datos de registro son los siguientes:', 'registrousuario'  ) . '<ul><li><strong>Nickname:</strong> ' . $nick . '</li><li><strong>Password:</strong> <l>Tu contrase&ntilde;a ingresada</l></li><li><strong>Email:</strong> ' . $mail . '</li></ul></p>';
    }
    return $output;
  }else{

    //imprimir el formulario
    $output .= cmb2_get_metabox_form($cmb, 'fake-object-id', array('save_button' => 'Registrar usuario'));

    return $output;

  }
}

public function insertar_usuario(){

    // En caso de que no se envie un formulario, no ejecutar nada
    if(empty($_POST) || !isset( $_POST['submit-cmb'], $_POST['object_id']) ) {
        return false;
    }

    // Obtener una instancia del formulario
    $cmb = $this->formulario_instancia();

    $post_data = array();

    // Revisar nonce de seguridad
    if( !isset($_POST[ $cmb->nonce()] ) || !wp_verify_nonce($_POST[ $cmb->nonce()], $cmb->nonce() ) ) {
        return $cmb->prop('submission_error', new WP_Error('security_fail', 'Fallo en la seguridad.') );
    }

    // Revisar que haya un titulo de receta

    if(empty($_POST['crc_usuario_nombre'])) {
        return $cmb->prop('submission_error', new WP_Error('post_data_missing', 'Se requiere un nombre para el usuario'));
    }

    $valores_sanitizados= $cmb->get_sanitized_values($_POST);

    $user_login = $valores_sanitizados['crc_usuario_nickname'];
    $user_pass = $valores_sanitizados['crc_usuario_password'];
    $user_email = $valores_sanitizados['crc_usuario_email'];
    $user_nombre = $valores_sanitizados['crc_usuario_nombre'];
    $user_lastname = $valores_sanitizados['crc_usuario_apellidos'];
    //$user_status = $valores_sanitizados['crc_usuario_status'];
    $user_wallet = $valores_sanitizados['crc_usuario_wallet'];
    $user_numwallet = $valores_sanitizados['crc_usuario_walletcode'];
    $user_pais = $valores_sanitizados['crc_usuario_pais'];
    $user_estado = $valores_sanitizados['crc_usuario_estado'];
    $user_ciudad = $valores_sanitizados['crc_usuario_municipio'];
    $user_calle = $valores_sanitizados['crc_usuario_calle'];
    $user_zipcode = $valores_sanitizados['crc_usuario_zipcode'];

    if(isset($valores_sanitizados['crc_usuario_referido']) && $valores_sanitizados['crc_usuario_referido'] !== ''){
      $user_referido = $valores_sanitizados['crc_usuario_referido'];
    }else{
      $user_referido = 'AB1EF1';
    }

    $user_id = username_exists( $user_login );

    //Generamos un codigo de 6 digitos
    $identificador = $this->generar_codigo();
    //Revisamos que no se haya asignado el codigo referido del administrador
    if($identificador == 'AB1EF1' ){
      $identificador = $this->generar_codigo();
    }

    // Vamos a darle los accesos a los modulos. Todos al principio empiezan con 0 = sin acceso
    $mod_agresivo = 0;
    $mod_agrepart = 0;
    $mod_conservador = 0;
    $mod_interes = 0;

    /*$registro = [
      'status' => $user_status,
      'wallet' => $user_wallet
    ];*/

    $meta = [
      'wallet' => $user_wallet,
      'walletcode' => $user_numwallet,
      'pais' => $user_pais,
      'estado' => $user_estado,
      'municipio' => $user_ciudad,
      'calle' => $user_calle,
      'zipcode' => $user_zipcode,
      'referido' => $user_referido,
      'invitecode' => $identificador,
      'modagresivo' => $mod_agresivo,
      'modagresivopart' => $mod_agrepart,
      'modconservador' => $mod_conservador,
      'modinteres' => $mod_interes,
      'activo' => '0'
    ];

    $userdata = [
      'user_login' => $user_login,
      'user_pass' => $user_pass,
      'user_email' => $user_email,
      'first_name' => $user_nombre,
      'last_name' => $user_lastname,
      'role' => 'inversionista',
      'meta_input' => $meta
    ];

    if( ! $user_id && email_exists( $user_email ) === false ) {

      $user_id = wp_insert_user($userdata);

      if( !is_wp_error( $user_id ) ){
         wp_mail( $userdata['user_email'], 'Bienvenido!', 'Su contraseña es: '.$userdata['user_pass'].' , su nickname es: '.$user_login.' y su código para invitar usuarios es: '.$identificador);
         $adminemail = "admin@theincproject.com";
         wp_mail( $adminemail, 'Nuevo Usuario Registrado', 'Se ha registrado un nuevo usuario con email: '.$userdata['user_email'].' su nombre completo es: '.$userdata['first_name'].' '.$userdata['last_name'].' , y su nombre de usuario es: '.$user_login.' . El usuario se encuentra actualmente como inactivo, hasta su aprobación.');
      }

    }

    // Redireccionamos para prevenir que no haya duplicados
    $url_base = remove_query_arg("user_updated");
    $url = add_query_arg( "user_submitted", $user_id, $url_base );
    wp_redirect(esc_url_raw($url));
    exit;
    // $post_data['post_title'] = $valores_sanitizados['mid_mascota_nombre'];
    // unset($valores_sanitizados['mid_mascota_nombre']);
    //
    // $post_data['post_content'] = '';
    // $post_data['post_name'] = $valores_sanitizados['codigo_id'];
    // $post_data['post_status'] = 'publish';
    // $post_data['post_author'] = $user_actual;



  }

  public function generar_codigo(){
    $codigo = '';
    $pattern = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $max = strlen($pattern)-1;
    for($i=0;$i < 6;$i++) $codigo .= $pattern[mt_rand(0,$max)];
    return $codigo;
  }

  function cmb2_after_form_do_js_validation( $post_id, $cmb ) {
  static $added = false;

  // Only add this to the page once (not for every metabox)
  if ( $added ) {
    return;
  }

  $added = true;
  ?>
  <script type="text/javascript">
  jQuery(document).ready(function($) {

    $form1 = $( document.getElementById( 'crc_usuario_registrar' ) );
    //$form2 = $( document.getElementById( 'mid_mascota_actualizar_mascota' ) );
    $htmlbody = $( 'html, body' );
    $toValidate1 = $( '#crc_usuario_registrar [data-validation]' );
    //$toValidate2 = $( '#mid_mascota_actualizar_mascota [data-validation]' );

    if ( ! $toValidate1.length ) {
      return;
    }

    function checkValidation( evt ) {
      let objetivo = $(evt.target).attr("id");

      var $toValidate = $toValidate1;
      var labels = [];
      var $first_error_row = null;
      var $row = null;

      function add_required( $row ) {

        $row.children('.cmb-td').children(':first').css({
          'box-shadow': '0 0 5px #d45252',
          'border-color': '#b03535'});

        $row.children('.cmb-td').find('.select2-container .select2-selection').css({
          'box-shadow': '0 0 5px #d45252',
          'border-color': '#b03535'});

        let etiqueta = '<span class="aviso-falta-cmb"><?php _e( 'Este campo no puede ir vacío.', 'mascotaidperfil' ); ?></span>';
        $row.children('.cmb-td').children(':first').after(etiqueta);
        $first_error_row = $first_error_row ? $first_error_row : $row;
        labels.push( $row.find( '.cmb-th label' ).text() );
      }

      function remove_required( $row ) {
        $row.children('.cmb-td').children(':first').css({ 'box-shadow': 'none',
        'border-color': 'rgba(129, 129, 129, .20)' });
        $row.children('.cmb-td').find('.select2-container .select2-selection').css({'box-shadow': 'none',
        'border-color': 'rgba(129, 129, 129, .20)' });
        $row.children('.cmb-td').children('.aviso-falta-cmb').remove();
      }

      $toValidate.each( function() {
        var $this = $(this);
        var val = $this.val();
        $row = $this.parents( '.cmb-row' );

        if ( $this.is( '[type="button"]' ) || $this.is( '.cmb2-upload-file-id' ) ) {
          return true;
        }

        if ( 'required' === $this.data( 'validation' ) ) {
          if ( $row.is( '.cmb-type-file-list' ) ) {

            var has_LIs = $row.find( 'ul.cmb-attach-list li' ).length > 0;

            if ( ! has_LIs ) {
              add_required( $row );
            } else {
              remove_required( $row );
            }

          } else {
            if ( ! val ) {
              add_required( $row );
            } else {
              remove_required( $row );
            }
          }
        }

      });

      if ( $first_error_row ) {
        evt.preventDefault();

        $htmlbody.animate({
          scrollTop: ( $first_error_row.offset().top - 200 )
        }, 1000);
      } else {
        // Feel free to comment this out or remove
        // alert( 'submission is good!' );
      }

    }

    $form1.on( 'submit', checkValidation );
  });
  </script>
  <?php
}

}

?>
