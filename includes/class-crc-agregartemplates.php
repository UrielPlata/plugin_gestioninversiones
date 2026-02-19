<?php

class CRC_Agregartemplates{

  public function agregartemplates($template) {

    if ( is_page('registro') && file_exists( plugin_dir_path(__DIR__) . 'template-parts/registro-usuario.php' ) ){
        $template = plugin_dir_path(__DIR__) . 'template-parts/registro-usuario.php';
    }

    if ( is_page('confirmacion') && file_exists( plugin_dir_path(__DIR__) . 'template-parts/confirmar-solicitud.php' ) ){
        $template = plugin_dir_path(__DIR__) . 'template-parts/confirmar-solicitud.php';
    }

    return $template;
  }

}
