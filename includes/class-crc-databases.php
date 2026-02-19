<?php

class CRC_Nuevatabla{
//inicializa la creacion de las tablas nuevas
 public function crc_database_tabretiros() {
    //$wpbd nos da los metodos para trabajar con las tablas
    global $wpdb;
    //agregamos una version
    global $retirosCRC_dbversion;
    $retirosCRC_dbversion = '1.5';//indica en que version de la base de datos estamos
    //obtenemos el prefijo
    $tabla = $wpdb->prefix . 'retiros';
    //obtenemos el collation de la instalacion
    $charset_collate = $wpdb->get_charset_collate();
    //agregamos la estructura de la base de datos
    $sql = "CREATE TABLE $tabla (
        id int(11) NOT NULL AUTO_INCREMENT,
        cantidad float(10,2) NOT NULL,
        usuario bigint(20) NOT NULL,
        urgente int(1) NOT NULL,
        fecha_cuando int(1) NOT NULL,
        fecha_retiro date NOT NULL,
        fecha_termino date NULL,
        codigo varchar(6) NOT NULL,
        status int(1) NOT NULL,
        idmov_ind varchar(50) NULL,
        idmov_gral varchar(50) NULL,
        cantidadfin float(10,2) NULL,
        notas text NULL,
        fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (id)
    ) $charset_collate; ";//para que todo eso lo agregue al final
    //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    //db_delta examina las estructuras de la tabla conforme se necesrio edita
    dbDelta($sql);
    //agrgamos la version de la base de datos para compararla con futuras actualizaciones
    add_option('retirosCRC_dbversion', $retirosCRC_dbversion);

  }

  public function crc_database_tabdepositos() {
     //$wpbd nos da los metodos para trabajar con las tablas
     global $wpdb;
     //agregamos una version
     global $depositosCRC_dbversion;
     $depositosCRC_dbversion = '1.4';//indica en que version de la base de datos estamos
     //obtenemos el prefijo
     $tabla = $wpdb->prefix . 'depositos';
     //obtenemos el collation de la instalacion
     $charset_collate = $wpdb->get_charset_collate();
     //agregamos la estructura de la base de datos
     $sql = "CREATE TABLE $tabla (
         id int(11) NOT NULL AUTO_INCREMENT,
         cantidad float(10,2) NOT NULL,
         cantidad_real float(10,2) NOT NULL,
         usuario bigint(20) NOT NULL,
         fecha_cuando int(1) NOT NULL,
         fecha_deposito date NOT NULL,
         fecha_termino date NULL,
         codigo varchar(6) NOT NULL,
         status int(1) NOT NULL,
         idmov_ind varchar(50) NULL,
         idmov_gral varchar(50) NULL,
         interes int(3) NOT NULL DEFAULT 0,
         notas text NULL,
         fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
       PRIMARY KEY (id)
     ) $charset_collate; ";//para que todo eso lo agregue al final
     //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
     require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
     //db_delta examina las estructuras de la tabla conforme se necesrio edita
     dbDelta($sql);
     //agrgamos la version de la base de datos para compararla con futuras actualizaciones
     add_option('depositosCRC_dbversion', $depositosCRC_dbversion);

   }

   public function crc_database_tabmesesinv() {
      //$wpbd nos da los metodos para trabajar con las tablas
      global $wpdb;
      //agregamos una version
      global $mesesinvCRC_dbversion;
      $mesesinvCRC_dbversion = '1.1';//indica en que version de la base de datos estamos
      //obtenemos el prefijo
      $tabla = $wpdb->prefix . 'mesesinv';
      //obtenemos el collation de la instalacion
      $charset_collate = $wpdb->get_charset_collate();
      //agregamos la estructura de la base de datos
      $sql = "CREATE TABLE $tabla (
          id int(11) NOT NULL AUTO_INCREMENT,
          mes int(2) NOT NULL,
          interes int(3) NOT NULL DEFAULT 0,
          usuario bigint(20) NOT NULL,
          status int(1) NOT NULL,
          notas text NULL,
          fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
      ) $charset_collate; ";//para que todo eso lo agregue al final
      //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      //db_delta examina las estructuras de la tabla conforme se necesrio edita
      dbDelta($sql);
      //agrgamos la version de la base de datos para compararla con futuras actualizaciones
      add_option('mesesinvCRC_dbversion', $mesesinvCRC_dbversion);

    }

    public function crc_database_tabcontrolmaster() {
       //$wpbd nos da los metodos para trabajar con las tablas
       global $wpdb;
       //agregamos una version
       global $controlmasterCRC_dbversion;
       $controlmasterCRC_dbversion = '1.3';//indica en que version de la base de datos estamos
       //obtenemos el prefijo
       $tabla = $wpdb->prefix . 'controlmaster';
       //obtenemos el collation de la instalacion
       $charset_collate = $wpdb->get_charset_collate();
       //agregamos la estructura de la base de datos
       $sql = "CREATE TABLE $tabla (
           id int(11) NOT NULL AUTO_INCREMENT,
           mes int(2) NOT NULL,
           agno int(4) NOT NULL,
           start_balance decimal(10,2) NOT NULL,
           balance_bef_com decimal(10,2) NOT NULL,
           com_broker decimal(10,2) NOT NULL,
           com_trader decimal(10,2) NOT NULL,
           balance_final decimal(10,2) NOT NULL,
           total_cuentas decimal(10,2) NOT NULL,
           notas text NULL,
           status int(1) NOT NULL DEFAULT 1,
           fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
         PRIMARY KEY (id)
       ) $charset_collate; ";//para que todo eso lo agregue al final
       //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
       require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
       //db_delta examina las estructuras de la tabla conforme se necesrio edita
       dbDelta($sql);
       //agrgamos la version de la base de datos para compararla con futuras actualizaciones
       add_option('controlmasterCRC_dbversion', $controlmasterCRC_dbversion);

    }

    public function crc_database_tabdepmaster() {
       //$wpbd nos da los metodos para trabajar con las tablas
       global $wpdb;
       //agregamos una version
       global $depmasterCRC_dbversion;
       $depmasterCRC_dbversion = '1.3';//indica en que version de la base de datos estamos
       //obtenemos el prefijo
       $tabla = $wpdb->prefix . 'depositos_master';
       //obtenemos el collation de la instalacion
       $charset_collate = $wpdb->get_charset_collate();
       //agregamos la estructura de la base de datos
       $sql = "CREATE TABLE $tabla (
           id int(11) NOT NULL AUTO_INCREMENT,
           cantidad float(10,2) NOT NULL,
           cantidad_real float(10,2) NOT NULL,
           fecha_deposito date NOT NULL,
           status int(1) NOT NULL,
           idmov_ind varchar(50) NULL,
           idmov_gral varchar(50) NULL,
           notas text NULL,
           fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
         PRIMARY KEY (id)
       ) $charset_collate; ";//para que todo eso lo agregue al final
       //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
       require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
       //db_delta examina las estructuras de la tabla conforme se necesrio edita
       dbDelta($sql);
       //agrgamos la version de la base de datos para compararla con futuras actualizaciones
       add_option('depmasterCRC_dbversion', $depmasterCRC_dbversion);
     }

     public function crc_database_tabretmaster() {
        //$wpbd nos da los metodos para trabajar con las tablas
        global $wpdb;
        //agregamos una version
        global $retmasterCRC_dbversion;
        $retmasterCRC_dbversion = '1.2';//indica en que version de la base de datos estamos
        //obtenemos el prefijo
        $tabla = $wpdb->prefix . 'retiros_master';
        //obtenemos el collation de la instalacion
        $charset_collate = $wpdb->get_charset_collate();
        //agregamos la estructura de la base de datos
        $sql = "CREATE TABLE $tabla (
            id int(11) NOT NULL AUTO_INCREMENT,
            cantidad float(10,2) NOT NULL,
            cantidad_real float(10,2) NOT NULL,
            fecha_retiro date NOT NULL,
            status int(1) NOT NULL,
            idmov_ind varchar(50) NULL,
            notas text NULL,
            fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (id)
        ) $charset_collate; ";//para que todo eso lo agregue al final
        //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //db_delta examina las estructuras de la tabla conforme se necesrio edita
        dbDelta($sql);
        //agrgamos la version de la base de datos para compararla con futuras actualizaciones
        add_option('retmasterCRC_dbversion', $retmasterCRC_dbversion);
      }

      public function crc_database_tabreghistorico() {
         //$wpbd nos da los metodos para trabajar con las tablas
         global $wpdb;
         //agregamos una version
         global $reghistoricoCRC_dbversion;
         $reghistoricoCRC_dbversion = '1.0';//indica en que version de la base de datos estamos
         //obtenemos el prefijo
         $tabla = $wpdb->prefix . 'registro_historico';
         //obtenemos el collation de la instalacion
         $charset_collate = $wpdb->get_charset_collate();
         //agregamos la estructura de la base de datos
         $sql = "CREATE TABLE $tabla (
             id int(11) NOT NULL AUTO_INCREMENT,
             year int NOT NULL,
             mes int(2) NOT NULL,
             external float(10,2) NOT NULL,
             notas text NULL,
             fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
           PRIMARY KEY (id)
         ) $charset_collate; ";//para que todo eso lo agregue al final
         //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
         require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
         //db_delta examina las estructuras de la tabla conforme se necesrio edita
         dbDelta($sql);
         //agrgamos la version de la base de datos para compararla con futuras actualizaciones
         add_option('reghistoricoCRC_dbversion', $reghistoricoCRC_dbversion);
       }

       public function crc_database_tabusuariosbl() {
          //$wpbd nos da los metodos para trabajar con las tablas
          global $wpdb;
          //agregamos una version
          global $usuariosblCRC_dbversion;
          $usuariosblCRC_dbversion = '1.1';//indica en que version de la base de datos estamos
          //obtenemos el prefijo
          $tabla = $wpdb->prefix . 'usuarios_bl';
          //obtenemos el collation de la instalacion
          $charset_collate = $wpdb->get_charset_collate();
          //agregamos la estructura de la base de datos
          $sql = "CREATE TABLE $tabla (
              ubl_id int(11) NOT NULL AUTO_INCREMENT,
              ubl_nombre varchar(50) NOT NULL,
              ubl_apellidos varchar(50) NOT NULL,
              ubl_correo varchar(50) NOT NULL,
              ubl_status int(1) NOT NULL,
              ubl_tipo int(1) NOT NULL,
              ubl_notas text NULL,
              ubl_fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              ubl_project int(11) NOT NULL
            PRIMARY KEY (ubl_id)
          ) $charset_collate; ";//para que todo eso lo agregue al final
          //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
          require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
          //db_delta examina las estructuras de la tabla conforme se necesrio edita
          dbDelta($sql);
          //agrgamos la version de la base de datos para compararla con futuras actualizaciones
          add_option('usuariosblCRC_dbversion', $usuariosblCRC_dbversion);
        }

        public function crc_database_tabprojectsbl() {
           //$wpbd nos da los metodos para trabajar con las tablas
           global $wpdb;
           //agregamos una version
           global $projectsblCRC_dbversion;
           $projectsblCRC_dbversion = '1.3';//indica en que version de la base de datos estamos
           //obtenemos el prefijo
           $tabla = $wpdb->prefix . 'projects_bl';
           //obtenemos el collation de la instalacion
           $charset_collate = $wpdb->get_charset_collate();
           //agregamos la estructura de la base de datos
           $sql = "CREATE TABLE $tabla (
             pbl_id int(11) NOT NULL AUTO_INCREMENT,
             pbl_nombre varchar(50) NOT NULL,
             pbl_status int(1) NOT NULL,
             pbl_tipo int(1) NOT NULL,
             pbl_notas text NULL,
             pbl_fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
             pbl_comision float(10,2) NOT NULL,
             pbl_comandres float(10,2) NOT NULL,
             pbl_comtiger float(10,2) NOT NULL,
             pbl_color int(1) NOT NULL
             PRIMARY KEY (pbl_id)
           ) $charset_collate; ";//para que todo eso lo agregue al final
           //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
           require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
           //db_delta examina las estructuras de la tabla conforme se necesrio edita
           dbDelta($sql);
           //agrgamos la version de la base de datos para compararla con futuras actualizaciones
           add_option('projectsblCRC_dbversion', $projectsblCRC_dbversion);
         }

       public function crc_database_tabcuentasbl() {
          //$wpbd nos da los metodos para trabajar con las tablas
          global $wpdb;
          //agregamos una version
          global $cuentasblCRC_dbversion;
          $cuentasblCRC_dbversion = '1.1';//indica en que version de la base de datos estamos
          //obtenemos el prefijo
          $tabla = $wpdb->prefix . 'cuentas_bl';
          //obtenemos el collation de la instalacion
          $charset_collate = $wpdb->get_charset_collate();
          //agregamos la estructura de la base de datos
          $sql = "CREATE TABLE $tabla (
              cbl_id int(11) NOT NULL AUTO_INCREMENT,
              cbl_nombre varchar(50) NOT NULL,
              cbl_usuario int(11) NOT NULL,
              cbl_status int(1) NOT NULL,
              cbl_tipo int(1) NOT NULL,
              cbl_notas text NULL,
              cbl_numero varchar(50) NULL,
              cbl_fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (cbl_id)
          ) $charset_collate; ";//para que todo eso lo agregue al final
          //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
          require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
          //db_delta examina las estructuras de la tabla conforme se necesrio edita
          dbDelta($sql);
          //agrgamos la version de la base de datos para compararla con futuras actualizaciones
          add_option('cuentasblCRC_dbversion', $cuentasblCRC_dbversion);
      }

      public function crc_database_tabregistrosbl() {
         //$wpbd nos da los metodos para trabajar con las tablas
         global $wpdb;
         //agregamos una version
         global $registrosblCRC_dbversion;
         $registrosblCRC_dbversion = '1.1';//indica en que version de la base de datos estamos
         //obtenemos el prefijo
         $tabla = $wpdb->prefix . 'registros_bl';
         //obtenemos el collation de la instalacion
         $charset_collate = $wpdb->get_charset_collate();
         //agregamos la estructura de la base de datos
         $sql = "CREATE TABLE $tabla (
             rbl_id int(11) NOT NULL AUTO_INCREMENT,
             rbl_cuenta int(11) NOT NULL,
             rbl_mes int(2) NOT NULL,
             rbl_year int(4) NOT NULL,
             rbl_utilmes decimal(10,2) NOT NULL,
             rbl_combro decimal(10,2) NOT NULL,
             rbl_comtra decimal(10,2) NOT NULL,
             rbl_salini decimal(10,2) NOT NULL,
             rbl_status int(1) NOT NULL,
             rbl_notas text NULL,
             rbl_fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
           PRIMARY KEY (rbl_id)
         ) $charset_collate; ";//para que todo eso lo agregue al final
         //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
         require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
         //db_delta examina las estructuras de la tabla conforme se necesrio edita
         dbDelta($sql);
         //agrgamos la version de la base de datos para compararla con futuras actualizaciones
         add_option('registrosblCRC_dbversion', $registrosblCRC_dbversion);
      }

      public function crc_database_nftprojects() {
         //$wpbd nos da los metodos para trabajar con las tablas
         global $wpdb;
         //agregamos una version
         global $nftprojectsCRC_dbversion;
         $nftprojectsCRC_dbversion = '1.1';//indica en que version de la base de datos estamos
         //obtenemos el prefijo
         $tabla = $wpdb->prefix . 'projects_nft';
         //obtenemos el collation de la instalacion
         $charset_collate = $wpdb->get_charset_collate();
         //agregamos la estructura de la base de datos
         $sql = "CREATE TABLE $tabla (
             nft_id int(11) NOT NULL AUTO_INCREMENT,
             nft_nombre varchar(50) NOT NULL,
             nft_status int(1) NOT NULL,
             nft_tipo int(1) NOT NULL,
             nft_notas text NULL,
             nft_numero varchar(50) NULL,
             nft_fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
             nft_color int(1) NOT NULL,
             nft_imagen varchar(100) NULL
           PRIMARY KEY (nft_id)
         ) $charset_collate; ";//para que todo eso lo agregue al final
         //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
         require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
         //db_delta examina las estructuras de la tabla conforme se necesrio edita
         dbDelta($sql);
         //agrgamos la version de la base de datos para compararla con futuras actualizaciones
         add_option('nftprojectsCRC_dbversion', $nftprojectsCRC_dbversion);
     }

     public function crc_database_tabregistrosnft() {
        //$wpbd nos da los metodos para trabajar con las tablas
        global $wpdb;
        //agregamos una version
        global $registrosnftCRC_dbversion;
        $registrosnftCRC_dbversion = '1.2';//indica en que version de la base de datos estamos
        //obtenemos el prefijo
        $tabla = $wpdb->prefix . 'registros_nft';
        //obtenemos el collation de la instalacion
        $charset_collate = $wpdb->get_charset_collate();
        //agregamos la estructura de la base de datos
        $sql = "CREATE TABLE $tabla (
            rnft_id int(11) NOT NULL AUTO_INCREMENT,
            rnft_proyecto int(11) NOT NULL,
            rnft_mes int(2) NOT NULL,
            rnft_year int(4) NOT NULL,
            rnft_semana int(2) NOT NULL,
            rnft_total decimal(10,4) NOT NULL,
            rnft_team decimal(10,4) NOT NULL,
            rnft_status int(1) NOT NULL,
            rnft_notas text NULL,
            rnft_fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            rnft_retiro int(11) NULL,
            rnft_fecha_retiro date NULL,
          PRIMARY KEY (rnft_id)
        ) $charset_collate; ";//para que todo eso lo agregue al final
        //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //db_delta examina las estructuras de la tabla conforme se necesrio edita
        dbDelta($sql);
        //agrgamos la version de la base de datos para compararla con futuras actualizaciones
        add_option('registrosnft_dbversion', $registrosnftCRC_dbversion);
     }

     public function crc_database_tabretirosnft() {
        //$wpbd nos da los metodos para trabajar con las tablas
        global $wpdb;
        //agregamos una version
        global $retirosnftCRC_dbversion;
        $retirosnftCRC_dbversion = '1.4';//indica en que version de la base de datos estamos
        //obtenemos el prefijo
        $tabla = $wpdb->prefix . 'retiros_nft';
        //obtenemos el collation de la instalacion
        $charset_collate = $wpdb->get_charset_collate();
        //agregamos la estructura de la base de datos
        $sql = "CREATE TABLE $tabla (
            rtnft_id int(11) NOT NULL AUTO_INCREMENT,
            rtnft_proyecto int(11) NOT NULL,
            rtnft_cantidad decimal(10,4) NOT NULL,
            rtnft_usdactual decimal(10,2) NOT NULL,
            rtnft_status int(1) NOT NULL,
            rtnft_notas text NULL,
            rtnft_fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            rtnft_fecha_retiro date NOT NULL
          PRIMARY KEY (rtnft_id)
        ) $charset_collate; ";//para que todo eso lo agregue al final
        //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //db_delta examina las estructuras de la tabla conforme se necesrio edita
        dbDelta($sql);
        //agrgamos la version de la base de datos para compararla con futuras actualizaciones
        add_option('retirosnft_dbversion', $retirosnftCRC_dbversion);
     }

     public function crc_database_tabregistrosvar() {
        //$wpbd nos da los metodos para trabajar con las tablas
        global $wpdb;
        //agregamos una version
        global $registrosvarCRC_dbversion;
        $registrosvarCRC_dbversion = '1.1';//indica en que version de la base de datos estamos
        //obtenemos el prefijo
        $tabla = $wpdb->prefix . 'registros_var';
        //obtenemos el collation de la instalacion
        $charset_collate = $wpdb->get_charset_collate();
        //agregamos la estructura de la base de datos
        $sql = "CREATE TABLE $tabla (
            rvar_id int(11) NOT NULL AUTO_INCREMENT,
            rvar_mes int(2) NOT NULL,
            rvar_year int(4) NOT NULL,
            rvar_titulo varchar(50) NOT NULL,
            rvar_cantidad decimal(10,2) NOT NULL,
            rvar_status int(1) NOT NULL,
            rvar_notas text NULL,
            rvar_fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (rvar_id)
        ) $charset_collate; ";//para que todo eso lo agregue al final
        //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //db_delta examina las estructuras de la tabla conforme se necesrio edita
        dbDelta($sql);
        //agrgamos la version de la base de datos para compararla con futuras actualizaciones
        add_option('registrosvar_dbversion', $registrosvarCRC_dbversion);
     }

     public function crc_database_tabagredepositos() {
        //$wpbd nos da los metodos para trabajar con las tablas
        global $wpdb;
        //agregamos una version
        global $agredepositosCRC_dbversion;
        $agredepositosCRC_dbversion = '1.0';//indica en que version de la base de datos estamos
        //obtenemos el prefijo
        $tabla = $wpdb->prefix . 'depositos_agr';
        //obtenemos el collation de la instalacion
        $charset_collate = $wpdb->get_charset_collate();
        //agregamos la estructura de la base de datos
        $sql = "CREATE TABLE $tabla (
            dagr_id int(11) NOT NULL AUTO_INCREMENT,
            dagr_cantidad float(10,2) NOT NULL,
            dagr_cantidad_real float(10,2) NOT NULL,
            dagr_usuario bigint(20) NOT NULL,
            dagr_fecha_deposito date NOT NULL,
            dagr_fecha_termino date NULL,
            dagr_codigo varchar(6) NOT NULL,
            dagr_status int(1) NOT NULL,
            dagr_idmov_ind varchar(50) NULL,
            dagr_idmov_gral varchar(50) NULL,
            dagr_notas text NULL,
            dagr_fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (dagr_id)
        ) $charset_collate; ";//para que todo eso lo agregue al final
        //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //db_delta examina las estructuras de la tabla conforme se necesrio edita
        dbDelta($sql);
        //agrgamos la version de la base de datos para compararla con futuras actualizaciones
        add_option('agredepositosCRC_dbversion', $agredepositosCRC_dbversion);

      }

      public function crc_database_tabagreretiros() {
         //$wpbd nos da los metodos para trabajar con las tablas
         global $wpdb;
         //agregamos una version
         global $agreretirosCRC_dbversion;
         $agreretirosCRC_dbversion = '1.1';//indica en que version de la base de datos estamos
         //obtenemos el prefijo
         $tabla = $wpdb->prefix . 'retiros_agr';
         //obtenemos el collation de la instalacion
         $charset_collate = $wpdb->get_charset_collate();
         //agregamos la estructura de la base de datos
         $sql = "CREATE TABLE $tabla (
             ragr_id int(11) NOT NULL AUTO_INCREMENT,
             ragr_cantidad float(10,2) NOT NULL,
             ragr_cantidad_real float(10,2) NOT NULL,
             ragr_usuario bigint(20) NOT NULL,
             ragr_fecha_retiro date NOT NULL,
             ragr_fecha_termino date NULL,
             ragr_codigo varchar(6) NOT NULL,
             ragr_status int(1) NOT NULL,
             ragr_idmov_ind varchar(50) NULL,
             ragr_idmov_gral varchar(50) NULL,
             ragr_notas text NULL,
             ragr_fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
           PRIMARY KEY (ragr_id)
         ) $charset_collate; ";//para que todo eso lo agregue al final
         //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
         require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
         //db_delta examina las estructuras de la tabla conforme se necesrio edita
         dbDelta($sql);
         //agrgamos la version de la base de datos para compararla con futuras actualizaciones
         add_option('agreretirosCRC_dbversion', $agreretirosCRC_dbversion);

       }

       public function crc_database_tabagredepmaster() {
          //$wpbd nos da los metodos para trabajar con las tablas
          global $wpdb;
          //agregamos una version
          global $agredepmasterCRC_dbversion;
          $agredepmasterCRC_dbversion = '1.2';//indica en que version de la base de datos estamos
          //obtenemos el prefijo
          $tabla = $wpdb->prefix . 'depositos_master_agr';
          //obtenemos el collation de la instalacion
          $charset_collate = $wpdb->get_charset_collate();
          //agregamos la estructura de la base de datos
          $sql = "CREATE TABLE $tabla (
              dmagr_id int(11) NOT NULL AUTO_INCREMENT,
              dmagr_cantidad float(10,2) NOT NULL,
              dmagr_cantidad_real float(10,2) NOT NULL,
              dmagr_fecha_deposito date NOT NULL,
              dmagr_status int(1) NOT NULL,
              dmagr_idmov_ind varchar(50) NULL,
              dmagr_idmov_gral varchar(50) NULL,
              dmagr_notas text NULL,
              dmagr_fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              dmagr_usuario bigint(20) NOT NULL,
              dmagr_fecha_termino date NULL,
            PRIMARY KEY (dmagr_id)
          ) $charset_collate; ";//para que todo eso lo agregue al final
          //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
          require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
          //db_delta examina las estructuras de la tabla conforme se necesrio edita
          dbDelta($sql);
          //agrgamos la version de la base de datos para compararla con futuras actualizaciones
          add_option('agredepmasterCRC_dbversion', $agredepmasterCRC_dbversion);
        }

      public function crc_database_tabagreretmaster() {
         //$wpbd nos da los metodos para trabajar con las tablas
         global $wpdb;
         //agregamos una version
         global $agreretmasterCRC_dbversion;
         $agreretmasterCRC_dbversion = '1.1';//indica en que version de la base de datos estamos
         //obtenemos el prefijo
         $tabla = $wpdb->prefix . 'retiros_master_agr';
         //obtenemos el collation de la instalacion
         $charset_collate = $wpdb->get_charset_collate();
         //agregamos la estructura de la base de datos
         $sql = "CREATE TABLE $tabla (
             rmagr_id int(11) NOT NULL AUTO_INCREMENT,
             rmagr_cantidad float(10,2) NOT NULL,
             rmagr_cantidad_real float(10,2) NOT NULL,
             rmagr_fecha_retiro date NOT NULL,
             rmagr_status int(1) NOT NULL,
             rmagr_idmov_ind varchar(50) NULL,
             rmagr_notas text NULL,
             rmagr_fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
             rmagr_usuario bigint(20) NOT NULL,
             rmagr_fecha_termino date NULL,
           PRIMARY KEY (rmagr_id)
         ) $charset_collate; ";//para que todo eso lo agregue al final
         //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
         require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
         //db_delta examina las estructuras de la tabla conforme se necesrio edita
         dbDelta($sql);
         //agrgamos la version de la base de datos para compararla con futuras actualizaciones
         add_option('agreretmasterCRC_dbversion', $agreretmasterCRC_dbversion);
       }

       public function crc_database_tabagreregistros() {
          //$wpbd nos da los metodos para trabajar con las tablas
          global $wpdb;
          //agregamos una version
          global $agreregistrosCRC_dbversion;
          $agreregistrosCRC_dbversion = '1.0';//indica en que version de la base de datos estamos
          //obtenemos el prefijo
          $tabla = $wpdb->prefix . 'registros_agr';
          //obtenemos el collation de la instalacion
          $charset_collate = $wpdb->get_charset_collate();
          //agregamos la estructura de la base de datos
          $sql = "CREATE TABLE $tabla (
              reagr_id int(11) NOT NULL AUTO_INCREMENT,
              reagr_mes int(2) NOT NULL,
              reagr_year int(4) NOT NULL,
              reagr_util_mes decimal(10,2) NOT NULL,
              reagr_com_bro decimal(10,2) NOT NULL,
              reagr_por_inver decimal(10,2) NOT NULL,
              reagr_por_refer decimal(10,2) NOT NULL,
              reagr_status int(1) NOT NULL,
              reagr_usuarios text NULL,
              reagr_notas text NULL,
              reagr_fecha_control date NULL,
              reagr_fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (reagr_id)
          ) $charset_collate; ";//para que todo eso lo agregue al final
          //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
          require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
          //db_delta examina las estructuras de la tabla conforme se necesrio edita
          dbDelta($sql);
          //agrgamos la version de la base de datos para compararla con futuras actualizaciones
          add_option('agreregistrosCRC_dbversion', $agreregistrosCRC_dbversion);
       }

       public function crc_database_tabconsdepositos() {
          //$wpbd nos da los metodos para trabajar con las tablas
          global $wpdb;
          //agregamos una version
          global $consdepositosCRC_dbversion;
          $consdepositosCRC_dbversion = '1.0';//indica en que version de la base de datos estamos
          //obtenemos el prefijo
          $tabla = $wpdb->prefix . 'depositos_con';
          //obtenemos el collation de la instalacion
          $charset_collate = $wpdb->get_charset_collate();
          //agregamos la estructura de la base de datos
          $sql = "CREATE TABLE $tabla (
              dcon_id int(11) NOT NULL AUTO_INCREMENT,
              dcon_cantidad float(10,2) NOT NULL,
              dcon_cantidad_real float(10,2) NOT NULL,
              dcon_usuario bigint(20) NOT NULL,
              dcon_fecha_deposito date NOT NULL,
              dcon_fecha_termino date NULL,
              dcon_codigo varchar(6) NOT NULL,
              dcon_status int(1) NOT NULL,
              dcon_idmov_ind varchar(50) NULL,
              dcon_idmov_gral varchar(50) NULL,
              dcon_notas text NULL,
              dcon_fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (dcon_id)
          ) $charset_collate; ";//para que todo eso lo agregue al final
          //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
          require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
          //db_delta examina las estructuras de la tabla conforme se necesrio edita
          dbDelta($sql);
          //agrgamos la version de la base de datos para compararla con futuras actualizaciones
          add_option('consdepositosCRC_dbversion', $consdepositosCRC_dbversion);

        }

        public function crc_database_tabconsretiros() {
           //$wpbd nos da los metodos para trabajar con las tablas
           global $wpdb;
           //agregamos una version
           global $consretirosCRC_dbversion;
           $consretirosCRC_dbversion = '1.0';//indica en que version de la base de datos estamos
           //obtenemos el prefijo
           $tabla = $wpdb->prefix . 'retiros_con';
           //obtenemos el collation de la instalacion
           $charset_collate = $wpdb->get_charset_collate();
           //agregamos la estructura de la base de datos
           $sql = "CREATE TABLE $tabla (
               rcon_id int(11) NOT NULL AUTO_INCREMENT,
               rcon_cantidad float(10,2) NOT NULL,
               rcon_cantidad_real float(10,2) NOT NULL,
               rcon_usuario bigint(20) NOT NULL,
               rcon_fecha_retiro date NOT NULL,
               rcon_fecha_termino date NULL,
               rcon_codigo varchar(6) NOT NULL,
               rcon_status int(1) NOT NULL,
               rcon_idmov_ind varchar(50) NULL,
               rcon_idmov_gral varchar(50) NULL,
               rcon_notas text NULL,
               rcon_fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
             PRIMARY KEY (rcon_id)
           ) $charset_collate; ";//para que todo eso lo agregue al final
           //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
           require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
           //db_delta examina las estructuras de la tabla conforme se necesrio edita
           dbDelta($sql);
           //agrgamos la version de la base de datos para compararla con futuras actualizaciones
           add_option('consretirosCRC_dbversion', $consretirosCRC_dbversion);

         }

       public function crc_database_tabconsnuevosstatus() {
          //$wpbd nos da los metodos para trabajar con las tablas
          global $wpdb;
          //agregamos una version
          global $consnuevosstatusCRC_dbversion;
          $consnuevosstatusCRC_dbversion = '1.1';//indica en que version de la base de datos estamos
          //obtenemos el prefijo
          $tabla = $wpdb->prefix . 'nuevosstatus_con';
          //obtenemos el collation de la instalacion
          $charset_collate = $wpdb->get_charset_collate();
          //agregamos la estructura de la base de datos
          $sql = "CREATE TABLE $tabla (
              nscon_id int(11) NOT NULL AUTO_INCREMENT,
              nscon_mes int(2) NOT NULL,
              nscon_year int(4) NOT NULL,
              nscon_usuario bigint(20) NOT NULL,
              nscon_porcentaje decimal(10,2) NOT NULL,
              nscon_tipo int(1) NOT NULL,
              nscon_notas text NULL,
              nscon_fecha timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (nscon_id)
          ) $charset_collate; ";//para que todo eso lo agregue al final
          //llamamos este archivo que imprime la ruta absoluta, porque aqui viene dbdelta
          require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
          //db_delta examina las estructuras de la tabla conforme se necesrio edita
          dbDelta($sql);
          //agrgamos la version de la base de datos para compararla con futuras actualizaciones
          add_option('consnuevosstatusCRC_dbversion', $consnuevosstatusCRC_dbversion);

        }
}
