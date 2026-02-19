<?php

class CRC_Redirect{


  public function crc_redirect_home(){
    if ( is_home() ) {
      //echo 'Esta es la home';
      // then redirect to yourdomain.com/jobs/
      wp_redirect( "wp-login.php");
      exit();
    }else if ($GLOBALS['pagenow'] === 'wp-login.php'){

    }else{

    }
  }

  public function add_link_register(){

    $registro = get_permalink(get_page_by_path( 'registro' ));

    echo '<a class="notuser" href="'.$registro.'">¿No tienes una cuenta aun? <br><span>Reg&iacute;strate.<span></a>';

  }

  public function add_inactive_userbox(){
    echo '<div class="login_error"><strong>Error</strong>: Tu usuario se encuentra inactivo. Espera hasta que el administrador te conceda acceso. <br></div>';
  }

  public function my_login_redirect( $redirect_to, $request, $user ) {
    //is there a user to check?
    global $user;
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {

        if ( in_array( 'inversionista', $user->roles ) ) {
            // redirect them to the default place
            return home_url('/wp-admin/admin.php?page=crc_inversiones');
        } else if( in_array( 'administrator', $user->roles ) ){
            return home_url('/wp-admin/admin.php?page=crc_admin_inversiones');
        }else{
          return home_url('/wp-admin');
        }
    } else  {
        return $redirect_to;
    }
  }

  // add a parent item to the WordPress admin toolbar

  function add_link_to_admin_bar($admin_bar) {
      $nameblog = get_bloginfo( 'name' );
      $dashboard = home_url('/wp-admin/admin.php?page=crc_inversiones');
      $dashboardadmin = home_url('/wp-admin/admin.php?page=crc_admin_inversiones');

      $args = array(
          'id' => 'my-dashboard',
          'title' => $nameblog,
          'href' => $dashboard
      );
      $args2 = array(
        'parent' => 'my-dashboard',
	      'id' => 'visitar-dashboard',
        'title' => __('Dashboard Principal','criptocontrol'),
        'href' => $dashboard
      );
      $args3 = array(
        'parent' => 'my-dashboard',
	      'id' => 'visitar-dashboard',
        'title' => __('Dashboard Principal','criptocontrol'),
        'href' => $dashboardadmin
      );

      $user = wp_get_current_user();
      if (current_user_can('inversionista')) {
        $admin_bar->add_node($args);
        $admin_bar->add_node($args2);
        $admin_bar->remove_node('site-name');
      }

      if($user->ID == 16){
        $admin_bar->add_node($args);
        $admin_bar->add_node($args3);
        $admin_bar->remove_node('site-name');
        $admin_bar->remove_node('comments');
        $admin_bar->remove_node('new-content');
      }

  }

  public function isUserActivated($user){
    $userStatus = get_user_meta($user->ID, 'activo', true);

    // for testing $userStatus = 1;
    $login_page  = home_url('/wp-login.php');
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        if ( in_array( 'inversionista', $user->roles ) ) {
          if($userStatus == '0'){
              wp_redirect($login_page . "?login=failed");
              exit;
          }
        }
    }


    return $user;
  }

  public function custom_wp_mail_from( $original_email_address ) {
      //Make sure the email is from the same domain
      //as your website to avoid being marked as spam.
      return 'admin@theincproject.com';
  }

  public function custom_wp_mail_from_name( $original_email_from ) {
    return 'The Inc Project';
  }

  public function crc_set_content_type(){
    return "text/html";
  }

  public function my_admin_title($admin_title, $title)
  {
          return get_bloginfo('name').' &bull; '.$title;
  }

}
