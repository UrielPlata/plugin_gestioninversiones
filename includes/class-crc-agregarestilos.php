<?php

class CRC_Agregarestilos{

  public function agregarscripts() {

    wp_enqueue_script('jquery');

    if(is_page('registro')){

      wp_enqueue_script( 'select2', plugin_dir_url( __DIR__ ) . 'assets/js/select2.full.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script('registro_js', plugin_dir_url( __DIR__ ) . 'assets/js/registro.js', array('jquery'), '1.0', true );

      wp_enqueue_style('Open Sans', 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet',array());
      wp_enqueue_style( 'select2', plugin_dir_url( __DIR__ ) . 'assets/css/select2.min.css');
      wp_enqueue_style('registro_css', plugin_dir_url( __DIR__ ) . 'assets/css/registro-redesign.css',array('Open Sans'));

      // Define the URL
       $url_em = plugin_dir_url( __DIR__ ) . 'assets/js/estados.json';


       // Make the request
       $request1 = wp_remote_get( $url_em );


       // If the remote request fails, wp_remote_get() will return a WP_Error, so let’s check if the $request variable is an error:
       if( is_wp_error( $request1 ) ) {
           return false; // Bail early
       }


       // Retrieve the data
       $body1 = wp_remote_retrieve_body( $request1 );
       $data1 = json_decode( $body1 );

      // Localize script exposing $data contents
       wp_localize_script( 'registro_js', 'municipiosJSON', array(
               'municipios_url' => admin_url( 'admin-ajax.php' ),
               'full_data' => $data1
           )
       );

       wp_localize_script( 'registro_js', 'admin_url', array(
          'ajax_url' => admin_url( 'admin-ajax.php')
        ) );

    }else if (is_page('confirmacion')) {
      wp_enqueue_script('confirmacion_js', plugin_dir_url( __DIR__ ) . 'assets/js/confirmacion.js', array('jquery'), '1.0', true );

      wp_enqueue_style('Roboto', 'https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;700&display=swap" rel="stylesheet',array());
      wp_enqueue_style('confirmacion_css', plugin_dir_url( __DIR__ ) . 'assets/css/confirmacion.css',array('Roboto'));

      wp_localize_script( 'confirmacion_js', 'confirm_url', array(
         'ajax_url' => admin_url( 'admin-ajax.php')
       ) );
    }else{

    }

  }

  public function agregarscripts_admin() {

    global $pagenow;
    $user = wp_get_current_user();

    wp_enqueue_script('jquery');

    wp_enqueue_style( 'flaticon', 'https://fonts.googleapis.com/icon?family=Material+Icons');
    wp_enqueue_style( 'darktooltip',  plugin_dir_url( __DIR__ ) . 'assets/css/microtip.min.css');
    wp_enqueue_style( 'datatable_css', '//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' );
    wp_enqueue_style( 'datatable_responsive_css', 'https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.css' );
    wp_enqueue_style( 'datatable_responsive_css', 'https://cdn.datatables.net/rowgroup/1.2.0/css/rowGroup.dataTables.min.css' );
    wp_enqueue_style( 'admin-crc',  plugin_dir_url( __DIR__ ) . 'assets/css/admin-crc.css', array('flaticon') );
    wp_enqueue_style( 'sweetalert2_css', plugin_dir_url( __DIR__ ) . 'assets/css/sweetalert2.min.css');
    wp_enqueue_script( 'datatable_js', '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'datatable_responsive_js', 'https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'datatable_rowgroup_js', 'https://cdn.datatables.net/rowgroup/1.2.0/js/dataTables.rowGroup.min.js', array('jquery'), '1.0', true );

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_retiros' ){
      wp_enqueue_script( 'tabla-histret', plugin_dir_url( __DIR__ ) . 'assets/js/tab-historetiros.js', array('jquery'), '1.0', true );

      wp_localize_script(
        'tabla-histret',
        'tabla_histret_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_depositos' ){
      wp_enqueue_script( 'tabla-histdep', plugin_dir_url( __DIR__ ) . 'assets/js/tab-histodepositos.js', array('jquery'), '1.0', true );

      wp_localize_script(
        'tabla-histdep',
        'tabla_histdep_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_inversiones' ){
      wp_enqueue_script( 'tabla-adminuser', plugin_dir_url( __DIR__ ) . 'assets/js/tab-adminusuarios.js', array('jquery'), '1.0', true );

      wp_localize_script(
        'tabla-adminuser',
        'tabla_adminuser_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_retiros' ){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'tabla-adminret', plugin_dir_url( __DIR__ ) . 'assets/js/tab-adminretiros.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16  ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'tabla-adminret',
        'tabla_adminret_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_depositos' ){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'tabla-admindep', plugin_dir_url( __DIR__ ) . 'assets/js/tab-admindepositos.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'tabla-admindep',
        'tabla_admindep_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_inversiones' ){
      wp_enqueue_script( 'tabla-proyec', plugin_dir_url( __DIR__ ) . 'assets/js/tab-proyecciones.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-admininvrefmes', plugin_dir_url( __DIR__ ) . 'assets/js/tab-admininvrefmes.js', array('jquery'), '1.0', true );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_userdashboard'){
      wp_enqueue_style( 'perfil-admin-crc',  plugin_dir_url( __DIR__ ) . 'assets/css/perfil.css' );
      wp_enqueue_script( 'tabla-proyec', plugin_dir_url( __DIR__ ) . 'assets/js/tab-proyecciones.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-admininvrefmes', plugin_dir_url( __DIR__ ) . 'assets/js/tab-admininvrefmes.js', array('jquery'), '1.0', true );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_verdepmes'){
      wp_enqueue_style( 'perfil-admin-crc',  plugin_dir_url( __DIR__ ) . 'assets/css/perfil.css' );
      wp_enqueue_script( 'tabla-proyec', plugin_dir_url( __DIR__ ) . 'assets/js/tab-listamesdep.js', array('jquery'), '1.0', true );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_verretmes'){
      wp_enqueue_style( 'perfil-admin-crc',  plugin_dir_url( __DIR__ ) . 'assets/css/perfil.css' );
      wp_enqueue_script( 'tabla-proyec', plugin_dir_url( __DIR__ ) . 'assets/js/tab-listamesret.js', array('jquery'), '1.0', true );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_master'){
      wp_enqueue_style( 'perfil-admin-crc',  plugin_dir_url( __DIR__ ) . 'assets/css/master-admin.css' );
      wp_enqueue_script( 'tabla-admconmast', plugin_dir_url( __DIR__ ) . 'assets/js/tab-admconmaster.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-rephistgral', plugin_dir_url( __DIR__ ) . 'assets/js/tab-rephistgral.js', array('tabla-admconmast'), '1.0', true );

      wp_localize_script(
        'tabla-admconmast',
        'tabla_admindep_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );

      wp_localize_script(
        'tabla-rephistgral',
        'tabla_admindep_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_depmas'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'tabla-admdepmast', plugin_dir_url( __DIR__ ) . 'assets/js/tab-admindepmas.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16  ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'tabla-admdepmast',
        'tabla_admindep_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_retmas'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'tabla-admretmast', plugin_dir_url( __DIR__ ) . 'assets/js/tab-adminretmas.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'tabla-admretmast',
        'tabla_adminret_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_verdepmasmes'){
      wp_enqueue_style( 'perfil-admin-crc',  plugin_dir_url( __DIR__ ) . 'assets/css/perfil.css' );
      wp_enqueue_script( 'tabla-depmas', plugin_dir_url( __DIR__ ) . 'assets/js/tab-listadepmas.js', array('jquery'), '1.0', true );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_verretmasmes'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'perfil-admin-crc',  plugin_dir_url( __DIR__ ) . 'assets/css/perfil.css' );
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-retmas', plugin_dir_url( __DIR__ ) . 'assets/js/tab-listaretmas.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_repmensual'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'reporte_css',  plugin_dir_url( __DIR__ ) . 'assets/css/reporte.css' );
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'reporte_js', plugin_dir_url( __DIR__ ) . 'assets/js/reporte.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'reporte_js',
        'url_operep',
        array('ajax_operep_url'=>admin_url( 'admin-ajax.php'))
      );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_agrlistausuarios'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'agrlistusers_js', plugin_dir_url( __DIR__ ) . 'assets/js/agrlistusers.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-agrlistusers', plugin_dir_url( __DIR__ ) . 'assets/js/tab-agrlistusers.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'tabla-agrlistusers',
        'url_solicitar_agresivo', array(
        'ajaxurl' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_verutilrefmes'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'reporte_css',  plugin_dir_url( __DIR__ ) . 'assets/css/reporte.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-admininvrefmes', plugin_dir_url( __DIR__ ) . 'assets/js/tab-admininvrefmes.js', array('jquery'), '1.0', true );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_verdepmes'){
      wp_enqueue_style( 'perfil-admin-crc',  plugin_dir_url( __DIR__ ) . 'assets/css/perfil.css' );
      wp_enqueue_script( 'tabla-proyec', plugin_dir_url( __DIR__ ) . 'assets/js/tab-listamesdep.js', array('jquery'), '1.0', true );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_verretmes'){
      wp_enqueue_style( 'perfil-admin-crc',  plugin_dir_url( __DIR__ ) . 'assets/css/perfil.css' );
      wp_enqueue_script( 'tabla-proyec', plugin_dir_url( __DIR__ ) . 'assets/js/tab-listamesret.js', array('jquery'), '1.0', true );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_referral_principal'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );

      wp_enqueue_script( 'referral_blprojects_js', plugin_dir_url( __DIR__ ) . 'assets/js/referral-blprojects.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'referral_blprojects_js',
        'url_referral',
        array('ajax_referral_url'=>admin_url( 'admin-ajax.php'))
      );

    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_referral_dashboard'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-referralusers', plugin_dir_url( __DIR__ ) . 'assets/js/tab-referralusers.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'referral_dashboard_js', plugin_dir_url( __DIR__ ) . 'assets/js/referral-dashboard.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'referral_dashboard_js',
        'url_referral',
        array('ajax_referral_url'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'tabla-referralusers',
        'tabla_referral_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_referral_dashboardspe'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-referralusersspe', plugin_dir_url( __DIR__ ) . 'assets/js/tab-referralusersspe.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'referral_dashboardspe_js', plugin_dir_url( __DIR__ ) . 'assets/js/referral-dashboardspe.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'referral_dashboardspe_js',
        'url_referral',
        array('ajax_referral_url'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'tabla-referralusersspe',
        'tabla_referral_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_referral_nftprojects'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );

      wp_enqueue_script( 'referral_nftprojects_js', plugin_dir_url( __DIR__ ) . 'assets/js/referral-nftprojects.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'referral_nftprojects_js',
        'url_referral',
        array('ajax_referral_url'=>admin_url( 'admin-ajax.php'))
      );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_vernftproject'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-referralproyectonft', plugin_dir_url( __DIR__ ) . 'assets/js/tab-referralcuentanft.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-referralproyectonftm', plugin_dir_url( __DIR__ ) . 'assets/js/tab-referralcuentanftm.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-referralproyectonftmes', plugin_dir_url( __DIR__ ) . 'assets/js/tab-referralcuentanftmes.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'referral_vernftproject_js', plugin_dir_url( __DIR__ ) . 'assets/js/referral-vernftproject.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-referralretirosnft', plugin_dir_url( __DIR__ ) . 'assets/js/tab-referralretirosnft.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16  ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'referral_vernftproject_js',
        'url_referral',
        array('ajax_referral_url'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'tabla-referralproyectonft',
        'tabla_referral_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );

      wp_localize_script(
        'tabla-referralproyectonftm',
        'tabla_referral_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );

      wp_localize_script(
        'tabla-referralproyectonftmes',
        'tabla_referral_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );

      wp_localize_script(
        'tabla-referralretirosnft',
        'tabla_referral_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_referral_ingresosvar'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'referral_ingresosvar_js', plugin_dir_url( __DIR__ ) . 'assets/js/referral-ingresosvar.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-referralvarmes', plugin_dir_url( __DIR__ ) . 'assets/js/tab-referralvarmes.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'referral_ingresosvar_js',
        'url_referral',
        array('ajax_referral_url'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'tabla-referralvarmes',
        'tabla_referral_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_verdetallemesvar'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'referral_verdetallemesvar_js', plugin_dir_url( __DIR__ ) . 'assets/js/referral-verdetallemesvar.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-referraldetallevarmes', plugin_dir_url( __DIR__ ) . 'assets/js/tab-referraldetallevarmes.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'referral_verdetallemesvar_js',
        'url_referral',
        array('ajax_referral_url'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'tabla-referraldetallevarmes',
        'tabla_referral_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_verdetallemesnft'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'referral_verdetallemesnft_js', plugin_dir_url( __DIR__ ) . 'assets/js/referral-verdetallemesnft.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-referraldetallenftmes', plugin_dir_url( __DIR__ ) . 'assets/js/tab-referraldetallenftmes.js', array('jquery'), '1.0', true );

      wp_localize_script(
        'referral_verdetallemesnft_js',
        'url_referral',
        array('ajax_referral_url'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'tabla-referraldetallenftmes',
        'tabla_referral_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_vercuentabl'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-referralcuentan', plugin_dir_url( __DIR__ ) . 'assets/js/tab-referralcuentan.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'referral_vercuenta_js', plugin_dir_url( __DIR__ ) . 'assets/js/referral-vercuenta.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'referral_vercuenta_js',
        'url_referral',
        array('ajax_referral_url'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'tabla-referralcuentan',
        'tabla_referral_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_vercuentablspe'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-referralcuentas', plugin_dir_url( __DIR__ ) . 'assets/js/tab-referralcuentas.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'referral_vercuentaspe_js', plugin_dir_url( __DIR__ ) . 'assets/js/referral-vercuentaspe.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'referral_vercuentaspe_js',
        'url_referral',
        array('ajax_referral_url'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'tabla-referralcuentas',
        'tabla_referral_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_vertotalcuentasbl'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-referraltotalcuentasn', plugin_dir_url( __DIR__ ) . 'assets/js/tab-referraltotalcuentasn.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'referral_totalcuentasn_js', plugin_dir_url( __DIR__ ) . 'assets/js/referral-totalcuentasn.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'referral_totalcuentasn_js',
        'url_referral',
        array('ajax_referral_url'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'tabla-referraltotalcuentasn',
        'tabla_referral_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_vertotalcuentasspebl'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-referraltotalcuentass', plugin_dir_url( __DIR__ ) . 'assets/js/tab-referraltotalcuentass.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'referral_totalcuentass_js', plugin_dir_url( __DIR__ ) . 'assets/js/referral-totalcuentass.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'referral_totalcuentass_js',
        'url_referral',
        array('ajax_referral_url'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'tabla-referraltotalcuentass',
        'tabla_referral_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_verdetallemesbl'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-referraldetallemes', plugin_dir_url( __DIR__ ) . 'assets/js/tab-referraldetallemes.js', array('jquery'), '1.0', true );
      // wp_enqueue_script( 'referral_totalcuentass_js', plugin_dir_url( __DIR__ ) . 'assets/js/referral-totalcuentass.js', array('jquery'), '1.0', true );
      //
      // wp_localize_script(
      //   'referral_totalcuentass_js',
      //   'url_referral',
      //   array('ajax_referral_url'=>admin_url( 'admin-ajax.php'))
      // );
      //

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'tabla-referraldetallemes',
        'tabla_referral_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_verdetallemesspebl'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-referraldetallemesspe', plugin_dir_url( __DIR__ ) . 'assets/js/tab-referraldetallemesspe.js', array('jquery'), '1.0', true );
      // wp_enqueue_script( 'referral_totalcuentass_js', plugin_dir_url( __DIR__ ) . 'assets/js/referral-totalcuentass.js', array('jquery'), '1.0', true );
      //
      // wp_localize_script(
      //   'referral_totalcuentass_js',
      //   'url_referral',
      //   array('ajax_referral_url'=>admin_url( 'admin-ajax.php'))
      // );
      //

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'tabla-referraldetallemesspe',
        'tabla_referral_url', array(
        'tabla_ajax' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_verreportemesbl'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'referral_reportemes_js', plugin_dir_url( __DIR__ ) . 'assets/js/refreportemes.js', array('jquery'), '1.0', true );
      // wp_enqueue_script( 'referral_totalcuentass_js', plugin_dir_url( __DIR__ ) . 'assets/js/referral-totalcuentass.js', array('jquery'), '1.0', true );
      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'referral_reportemes_js',
        'url_operep',
        array('ajax_operep_url'=>admin_url( 'admin-ajax.php'))
      );

    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_verreportemesspebl'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'referral_reportemesspe_js', plugin_dir_url( __DIR__ ) . 'assets/js/refreportemesspe.js', array('jquery'), '1.0', true );
      // wp_enqueue_script( 'referral_totalcuentass_js', plugin_dir_url( __DIR__ ) . 'assets/js/referral-totalcuentass.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'referral_reportemesspe_js',
        'url_operep',
        array('ajax_operep_url'=>admin_url( 'admin-ajax.php'))
      );
      //
      // wp_localize_script(
      //   'tabla-referralreportemesspe',
      //   'tabla_referral_url', array(
      //   'tabla_ajax' => admin_url( 'admin-ajax.php')
      // ) );
    }

    wp_enqueue_script( 'inputmask_js', plugin_dir_url(__DIR__) . 'assets/js/jquery.inputmask.js', array('jquery'), '1.0', true );
    wp_enqueue_script( 'sweetalert2_js', plugin_dir_url(__DIR__) . 'assets/js/sweetalert2.min.js', array('jquery'), '1.0', true );
    wp_enqueue_script( 'admin_js', plugin_dir_url( __DIR__ ) . 'assets/js/admin-crc.js', array('jquery'), '1.0', true );
    wp_enqueue_script( 'tabla-mesesinv', plugin_dir_url( __DIR__ ) . 'assets/js/tab-mesesinv.js', array('jquery'), '1.0', true );
    wp_enqueue_script( 'tabla-constatus', plugin_dir_url( __DIR__ ) . 'assets/js/tab-constatus.js', array('jquery'), '1.0', true );


    if($pagenow == 'profile.php'){
      if ( isset( $user->roles ) && is_array( $user->roles ) ) {
          if ( in_array( 'inversionista', $user->roles ) ) {
            wp_enqueue_style( 'perfil-crc',  plugin_dir_url( __DIR__ ) . 'assets/css/perfil.css' );
          }
        }
    }

    if($pagenow == 'user-edit.php'){
      wp_enqueue_style( 'perfil-admin-crc',  plugin_dir_url( __DIR__ ) . 'assets/css/perfil-admin.css' );
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'simpleswitch_css',  plugin_dir_url( __DIR__ ) . 'assets/css/simple-switch.min.css' );
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'simpleswitchJS', plugin_dir_url( __DIR__ ) . 'assets/js/jquery.simpleswitch.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'perfil-admin_js', plugin_dir_url( __DIR__ ) . 'assets/js/perfil-admin.js', array('jquery'), '1.0', true );




      // Define the URL
       $url_em = plugin_dir_url( __DIR__ ) . 'assets/js/estados.json';


       // Make the request
       $request1 = wp_remote_get( $url_em );


       // If the remote request fails, wp_remote_get() will return a WP_Error, so let’s check if the $request variable is an error:
       if( is_wp_error( $request1 ) ) {
           return false; // Bail early
       }


       // Retrieve the data
       $body1 = wp_remote_retrieve_body( $request1 );
       $data1 = json_decode( $body1 );

      // Localize script exposing $data contents
       wp_localize_script( 'perfil-admin_js', 'municipiosadminJSON', array(
               'municipios_admin_url' => admin_url( 'admin-ajax.php' ),
               'full_data' => $data1
           )
       );
    }

    // ESTILOS Y SCRIPTS PARA MODULO AGRESIVO

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_agresivo'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );

      wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
      wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'agresivo_js', plugin_dir_url( __DIR__ ) . 'assets/js/agredashboard.js', array('jquery'), '1.0', true );

      wp_localize_script(
        'agresivo_js',
        'url_solicitar_agresivo',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_agre_depositos'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );

      wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
      wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tab-agrehistodepositos_js', plugin_dir_url( __DIR__ ) . 'assets/js/tab-agrehistodepositos.js', array('jquery'), '1.0', true );

      wp_localize_script(
        'tab-agrehistodepositos_js',
        'url_solicitar_agresivo',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_agre_retiros'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );

      wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
      wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tab-agrehistoretiros_js', plugin_dir_url( __DIR__ ) . 'assets/js/tab-agrehistoretiros.js', array('jquery'), '1.0', true );

      wp_localize_script(
        'tab-agrehistoretiros_js',
        'url_solicitar_agresivo',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

    }

    // ESTILOS Y SCRIPTS PARA MODULO AGRESIVO ADMIN

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_agresivo_control'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'agresivocontrol_js', plugin_dir_url( __DIR__ ) . 'assets/js/agreadmindashboard.js', array('jquery'), '1.0', true );
        wp_enqueue_script( 'tab-admagrconmaster_js', plugin_dir_url( __DIR__ ) . 'assets/js/tab-admagrconmaster.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'agresivocontrol_js',
        'url_solicitar_agresivo',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'tab-admagrconmaster_js',
        'url_solicitar_agresivo',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_agrdepositos'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-agradmindep', plugin_dir_url( __DIR__ ) . 'assets/js/tab-agreadmindepositos.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'agreadmindepositos-js', plugin_dir_url( __DIR__ ) . 'assets/js/agreadmindepositos.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'tabla-agradmindep',
        'url_solicitar_agresivo',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'agreadmindepositos-js',
        'url_operacion_agresivo',
        array('ajaxopeurl' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_agrretiros'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-agradminret', plugin_dir_url( __DIR__ ) . 'assets/js/tab-agreadminretiros.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'agreadminretiros-js', plugin_dir_url( __DIR__ ) . 'assets/js/agreadminretiros.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'tabla-agradminret',
        'url_solicitar_agresivo',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'agreadminretiros-js',
        'url_operacion_agresivo',
        array('ajaxopeurl' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_agrdepmaster'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-agrdepmas', plugin_dir_url( __DIR__ ) . 'assets/js/tab-agredepmas.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'agredepmaster-js', plugin_dir_url( __DIR__ ) . 'assets/js/agredepmas.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'tabla-agrdepmas',
        'url_solicitar_agresivo',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'agredepmaster-js',
        'url_operacion_agresivo',
        array('ajaxopeurl' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_agrretmaster'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-agrretmas', plugin_dir_url( __DIR__ ) . 'assets/js/tab-agreretmas.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'agreretmaster-js', plugin_dir_url( __DIR__ ) . 'assets/js/agreretmas.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'tabla-agrretmas',
        'url_solicitar_agresivo',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'agreretmaster-js',
        'url_operacion_agresivo',
        array('ajaxopeurl' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_verdepagrmes'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-verdepagrmes', plugin_dir_url( __DIR__ ) . 'assets/js/tab-agreverdepmes.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-verdepagrmasmes', plugin_dir_url( __DIR__ ) . 'assets/js/tab-agreverdepmasmes.js', array('jquery'), '1.0', true );
      // wp_enqueue_script( 'agreretmaster-js', plugin_dir_url( __DIR__ ) . 'assets/js/agreretmas.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'tabla-verdepagrmes',
        'url_solicitar_agresivo',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'tabla-verdepagrmasmes',
        'url_solicitar_agresivo',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

      // wp_localize_script(
      //   'agreretmaster-js',
      //   'url_operacion_agresivo',
      //   array('ajaxopeurl' => admin_url( 'admin-ajax.php')
      // ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_verretagrmes'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-verretagrmes', plugin_dir_url( __DIR__ ) . 'assets/js/tab-agreverretmes.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-verretagrmasmes', plugin_dir_url( __DIR__ ) . 'assets/js/tab-agreverretmasmes.js', array('jquery'), '1.0', true );
      // wp_enqueue_script( 'agreretmaster-js', plugin_dir_url( __DIR__ ) . 'assets/js/agreretmas.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'tabla-verretagrmes',
        'url_solicitar_agresivo',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'tabla-verretagrmasmes',
        'url_solicitar_agresivo',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

      // wp_localize_script(
      //   'agreretmaster-js',
      //   'url_operacion_agresivo',
      //   array('ajaxopeurl' => admin_url( 'admin-ajax.php')
      // ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_verinvagrmes'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-verinvagrmes', plugin_dir_url( __DIR__ ) . 'assets/js/tab-agreverinvmes.js', array('jquery'), '1.0', true );
      // wp_enqueue_script( 'tabla-verretagrmasmes', plugin_dir_url( __DIR__ ) . 'assets/js/tab-agreverretmasmes.js', array('jquery'), '1.0', true );
      // wp_enqueue_script( 'agreretmaster-js', plugin_dir_url( __DIR__ ) . 'assets/js/agreretmas.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'tabla-verinvagrmes',
        'url_solicitar_agresivo',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

      // wp_localize_script(
      //   'tabla-verretagrmasmes',
      //   'url_solicitar_agresivo',
      //   array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      // );

      // wp_localize_script(
      //   'agreretmaster-js',
      //   'url_operacion_agresivo',
      //   array('ajaxopeurl' => admin_url( 'admin-ajax.php')
      // ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_agruserdashboard'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );

      wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
      wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'agresivo_js', plugin_dir_url( __DIR__ ) . 'assets/js/agreadminuserdashboard.js', array('jquery'), '1.0', true );


      wp_localize_script(
        'agresivo_js',
        'url_solicitar_agresivo',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

    }

    // ESTILOS Y SCRIPTS PARA MODULO CONSERVADOR

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_conservador'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );

      wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
      wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'conservador_js', plugin_dir_url( __DIR__ ) . 'assets/js/consdashboard.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tab-consutiluser_js', plugin_dir_url( __DIR__ ) . 'assets/js/tab-consutiluser.js', array('jquery'), '1.0', true );

      wp_localize_script(
        'conservador_js',
        'url_solicitar_conservador',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'tab-consutiluser_js',
        'url_solicitar_conservador',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_cons_depositos'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );

      wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
      wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tab-conshistodepositos_js', plugin_dir_url( __DIR__ ) . 'assets/js/tab-conshistodepositos.js', array('jquery'), '1.0', true );

      wp_localize_script(
        'tab-conshistodepositos_js',
        'url_solicitar_conservador',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_cons_retiros'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );

      wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
      wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tab-conshistoretiros_js', plugin_dir_url( __DIR__ ) . 'assets/js/tab-conshistoretiros.js', array('jquery'), '1.0', true );

      wp_localize_script(
        'tab-conshistoretiros_js',
        'url_solicitar_conservador',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_consverdepmes'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );

      wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
      wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tab-consuserdepmes_js', plugin_dir_url( __DIR__ ) . 'assets/js/tab-consuserdepmes.js', array('jquery'), '1.0', true );

      wp_localize_script(
        'tab-consuserdepmes_js',
        'url_solicitar_conservador',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_consverretmes'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );

      wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
      wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tab-consuserretmes_js', plugin_dir_url( __DIR__ ) . 'assets/js/tab-consuserretmes.js', array('jquery'), '1.0', true );

      wp_localize_script(
        'tab-consuserretmes_js',
        'url_solicitar_conservador',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

    }

    // ESTILOS Y SCRIPTS PARA MODULO CONSERVADOR ADMIN

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_conservador_control'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'conservadorcontrol_js', plugin_dir_url( __DIR__ ) . 'assets/js/consadmindashboard.js', array('jquery'), '1.0', true );
        wp_enqueue_script( 'tab-admconconmaster_js', plugin_dir_url( __DIR__ ) . 'assets/js/tab-admconconmaster.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'conservadorcontrol_js',
        'url_solicitar_conservador',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'tab-admconconmaster_js',
        'url_solicitar_conservador',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_condepositos'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-conadmindep', plugin_dir_url( __DIR__ ) . 'assets/js/tab-consadmindepositos.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'consadmindepositos-js', plugin_dir_url( __DIR__ ) . 'assets/js/consadmindepositos.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'tabla-conadmindep',
        'url_solicitar_conservador',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'consadmindepositos-js',
        'url_operacion_conservador',
        array('ajaxopeurl' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_conretiros'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-conadminret', plugin_dir_url( __DIR__ ) . 'assets/js/tab-consadminretiros.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'consadminretiros-js', plugin_dir_url( __DIR__ ) . 'assets/js/consadminretiros.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'tabla-conadminret',
        'url_solicitar_conservador',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'consadminretiros-js',
        'url_operacion_conservador',
        array('ajaxopeurl' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_condepmaster'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-condepmas', plugin_dir_url( __DIR__ ) . 'assets/js/tab-conshistodepositos.js', array('jquery'), '1.0', true );
      // wp_enqueue_script( 'consdepmaster-js', plugin_dir_url( __DIR__ ) . 'assets/js/consdepmas.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'tabla-condepmas',
        'url_solicitar_conservador',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'consdepmaster-js',
        'url_operacion_conservador',
        array('ajaxopeurl' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_conretmaster'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-conretmas', plugin_dir_url( __DIR__ ) . 'assets/js/tab-conshistoretiros.js', array('jquery'), '1.0', true );
      // wp_enqueue_script( 'consretmaster-js', plugin_dir_url( __DIR__ ) . 'assets/js/consretmas.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'tabla-conretmas',
        'url_solicitar_conservador',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

      wp_localize_script(
        'consretmaster-js',
        'url_operacion_conservador',
        array('ajaxopeurl' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_conlistausuarios'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'conlistusers_js', plugin_dir_url( __DIR__ ) . 'assets/js/conlistusers.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tabla-conlistusers', plugin_dir_url( __DIR__ ) . 'assets/js/tab-conlistusers.js', array('jquery'), '1.0', true );

      // Debe de ser el usuario 16 en produccion
      if ( $user->ID == 1 || $user->ID == 16 ) {

        wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
        wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

      }else if ( isset( $user->roles ) && is_array( $user->roles ) ) {

          if ( in_array( 'inversionista', $user->roles ) ) {

            wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
            wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );

          }else{
            wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );
          }

      }else{

        wp_enqueue_style( 'antimenu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/antimenu.css' );

      }

      wp_localize_script(
        'tabla-conlistusers',
        'url_solicitar_conservador', array(
        'ajaxurl' => admin_url( 'admin-ajax.php')
      ) );
    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_admin_conuserdashboard'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );

      wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
      wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'conservador_js', plugin_dir_url( __DIR__ ) . 'assets/js/consadminuserdashboard.js', array('jquery'), '1.0', true );


      wp_localize_script(
        'conservador_js',
        'url_solicitar_conservador',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_consadminverdepmes'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );

      wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
      wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tab-consuserdepmes_js', plugin_dir_url( __DIR__ ) . 'assets/js/tab-consuserdepmes.js', array('jquery'), '1.0', true );

      wp_localize_script(
        'tab-consuserdepmes_js',
        'url_solicitar_conservador',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

    }

    if(in_array( $pagenow, array('admin.php') ) &&  $_GET['page'] == 'crc_consadminverretmes'){
      wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
      wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style( 'redesign_css',  plugin_dir_url( __DIR__ ) . 'assets/css/redesign.css' );
      wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', true );

      wp_enqueue_style( 'menu_css',  plugin_dir_url( __DIR__ ) . 'assets/css/menu.css' );
      wp_enqueue_script( 'menu_js', plugin_dir_url( __DIR__ ) . 'assets/js/menu.js', array('jquery'), '1.0', true );
      wp_enqueue_script( 'tab-consuserretmes_js', plugin_dir_url( __DIR__ ) . 'assets/js/tab-consuserretmes.js', array('jquery'), '1.0', true );

      wp_localize_script(
        'tab-consuserretmes_js',
        'url_solicitar_conservador',
        array('ajaxurl'=>admin_url( 'admin-ajax.php'))
      );

    }


    wp_localize_script(
      'admin_js',
      'url_solicitar_retiro',
      array('ajaxurl'=>admin_url( 'admin-ajax.php'))
    );

    wp_localize_script(
      'admin_js',
      'url_opefin',
      array('ajax_opefin_url'=>admin_url( 'admin-ajax.php'))
    );

  }

  public function agregarscripts_login() {

    wp_enqueue_script('jquery');
    wp_enqueue_style('Open Sans', 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet',array());
    wp_enqueue_style( 'login-crc',  plugin_dir_url( __DIR__ ) . 'assets/css/login-redesign.css');
    wp_enqueue_script( 'login_js', plugin_dir_url( __DIR__ ) . 'assets/js/login.js', array('jquery'), '1.0', true );

  }

} ?>
