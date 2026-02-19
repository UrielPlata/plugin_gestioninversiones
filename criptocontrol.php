<?php
/*
Plugin Name: Cripto Control
Plugin URI: www.abundisservices.com
Description: Plugin para gestionar y administrar toda la informacion relativa a las inversiones de los usuarios.
Version: 2.8.9
Author: Abundiss Services
License: GPL2
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: criptocontrol
Domain Path:
*/

if ( ! defined( 'ABSPATH' ) ) exit;

//Revisa que exista la pagina 404 para mascotas
function crc_registropage_revisar(){
  $landing = get_page_by_path( 'registro' , OBJECT );

  if ( isset($landing) ) {

  }else{
    $new_post = array(
    'post_title' => 'Registro de Usuarios',
    'post_content' => '',
    'post_status' => 'publish',
    'post_type' => 'page',
    'post_name' => 'registro'
    );
    $crearland = wp_insert_post($new_post);
  }
}
add_action('admin_init', 'crc_registropage_revisar');

//Revisa que exista la pagina landing confirmación
function crc_confirmpage_revisar(){
  $landing = get_page_by_path( 'confirmacion' , OBJECT );

  if ( isset($landing) ) {

  }else{
    $new_post = array(
    'post_title' => 'Confirmación de solicitudes',
    'post_content' => '',
    'post_status' => 'publish',
    'post_type' => 'page',
    'post_name' => 'confirmacion'
    );
    $crearland = wp_insert_post($new_post);
  }
}
add_action('admin_init', 'crc_confirmpage_revisar');

define('CRC_DIR_PATH',plugin_dir_path( __FILE__ ) );

require_once CRC_DIR_PATH . 'includes/class-crc-calculos.php';
require_once CRC_DIR_PATH . 'includes/class-crc-agrecalculos.php';
require_once CRC_DIR_PATH . 'includes/class-crc-conscalculos.php';
require_once CRC_DIR_PATH . 'includes/class-crc-master.php';


if(file_exists(dirname(__FILE__).'/inc/CMB2old/init.php')){
  require_once dirname(__FILE__).'/inc/CMB2old/init.php';
}

function run_mid_master(){
  $mid_master = new CRC_Master();
  $mid_master->run();
}

run_mid_master();
