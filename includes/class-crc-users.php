<?php

class CRC_Users{


  public function crc_rol_inversor(){

    $wp_roles = new WP_Roles;

    $capacidades = [
      'read' => true,
      'upload_file' => true,
      'inversionista' => true,
      'edit_user' => true,
    ];

    $wp_roles->add_role(
      'inversionista',
      'Inversionista',
      $capacidades
    );

  }

  public function crc_rol_biglevel(){

    $wp_roles = new WP_Roles;

    $capacidades = [
      'read' => true,
      'upload_file' => true,
      'upload_files' => true,
      'big_level' => true,
      'edit_user' => true,
    ];

    $wp_roles->add_role(
      'big-level',
      'Big Level',
      $capacidades
    );

  }


}
