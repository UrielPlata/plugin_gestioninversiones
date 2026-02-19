<?php

class CRC_AdminDashboard{



  public function interfaz_admindashboard(){
    $user_actual = get_current_user_id();
    $noncedep = wp_create_nonce( 'mi_nonce_dep' );
    $nonceret = wp_create_nonce( 'mi_nonce_ret' );
    ?>
    <div class="wrap">
      <div class="col-titulo">
        <h1>Control de Inversiones</h1>
        <img class="logo-theinc" src="" alt="logo_theinc">
      </div>
      <hr>
      <div class="cuerpo-dashboard">
        <br>
        <h3>Lista de inversionistas</h3>
        <br>
        <br>
        <table class="wp-list-table widefat tab-dark striped tab-usersadm">
          <thead>
            <tr>
              <th class="manage_column" >#</th>
              <th class="manage_column" >ID</th>
              <th class="manage_column" >Nombre</th>
              <th class="manage_column" >Correo</th>
              <th class="manage_column" >Acceso</th>
              <th class="manage_column" >Status</th>
              <th class="manage_column" >Proyección</th>
              <th class="manage_column" >Tipo Wallet</th>
              <th class="manage_column" >Wallet Address</th>
              <th class="manage_column" >Pais</th>
              <th class="manage_column" >Capital principal</th>
              <th class="manage_column" >Utilidad acumulada</th>
            </tr>
          </thead>

          <tbody>

          </tbody>
        </table>

      </div>


    </div>


    <?php
  }

  public function interfaz_adminverdepmes(){
    global $wpdb;
    $user_actual = (int) $_GET['id'];
    $mesint = (int) $_GET['m'];
    //$mes = printf('%02d', $mesint);
    $agno = $_GET['p'];
    $user_data = get_userdata( absint( $user_actual ) );
    $unombre = $user_data->first_name ." ". $user_data->last_name;
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );
    $tmes = $mesesNombre[$mesint];
    $depositos = $wpdb->prefix . 'depositos';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $depositos WHERE usuario = $user_actual AND status = 2 AND MONTH(fecha_termino) = $mesint AND YEAR(fecha_termino) = $agno ORDER BY id ASC", ARRAY_A);
    /*echo "<pre>";
    var_dump($mes);
    var_dump($agno);
    var_dump($registros);
    echo "</pre>";*/
    ?>

    <div class="wrap">
      <div class="col-titulo">
        <h1>Depósitos <?php echo $tmes ." ". $agno ?> - <?php echo $unombre ?></h1>
        <img class="logo-theinc" src="" alt="logo_theinc">
      </div>
      <hr>
      <div class="cuerpo-dashboard">
        <br>
        <h2>Lista de dep&oacute;sitos válidos:</h2>
        <table class="wp-list-table widefat tab-dark dt-responsive striped tab-invmesdepositos">
          <thead>
            <tr>
              <th class="manage_column" >#</th>
              <th class="manage_column" >Cantidad</th>
              <th class="manage_column" >Cantidad final</th>
              <th class="manage_column" >Notas</th>
              <th class="manage_column" >Fecha deposito</th>
              <th class="manage_column" >Status</th>
              <th class="manage_column" >Id Depósito a TD</th>
              <th class="manage_column" >Id Depósito a Master</th>
              <th class="manage_column" >Fecha solicitud</th>
              <th class="manage_column" >Fecha autorización</th>
            </tr>
          </thead>

          <tbody>
            <?php
              if(count($registros) != 0){
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

                  if($value["cantidad_real"] == 0){
                    $cantidadreal = "";
                  }else{
                    $cantidadreal = "$".number_format($value["cantidad_real"], 2);
                  }

                  $fecha = substr($value["fecha_deposito"], 0, 10);

                  $cantidad  = "$".number_format($value["cantidad"], 2);
                  ?>
                  <tr>
                    <td><?php echo $key+1 ?></td>
                    <td><?php echo $cantidad; ?></td>
                    <td><?php echo $cantidadreal; ?></td>
                    <td><button aria-label='<?php echo $value["notas"]; ?>' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button></td>
                    <td><?php echo $fechadep; ?></td>
                    <td><?php echo $statusc; ?></td>
                    <td><?php echo $idmov_ind; ?></td>
                    <td><?php echo $idmov_gral; ?></td>
                    <td><?php echo $fecha; ?></td>
                    <td><?php echo $fechafin; ?></td>
                  </tr>
                  <?php
                }
              }
             ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php
  }

  public function interfaz_adminverretmes(){
    global $wpdb;
    $user_actual = (int) $_GET['id'];
    $mesint = (int) $_GET['m'];
    //$mes = printf('%02d', $mesint);
    $agno = $_GET['p'];
    $user_data = get_userdata( absint( $user_actual ) );
    $unombre = $user_data->first_name ." ". $user_data->last_name;
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );
    $tmes = $mesesNombre[$mesint];
    $retiros = $wpdb->prefix . 'retiros';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $retiros WHERE usuario = $user_actual AND status = 2 AND MONTH(fecha_termino) = $mesint AND YEAR(fecha_termino) = $agno ORDER BY id ASC", ARRAY_A);
    /*echo "<pre>";
    var_dump($mes);
    var_dump($agno);
    var_dump($registros);
    echo "</pre>";*/
    ?>

    <div class="wrap">
      <div class="col-titulo">
        <h1>Retiros <?php echo $tmes ." ". $agno ?> - <?php echo $unombre ?></h1>
        <img class="logo-theinc" src="" alt="logo_theinc">
      </div>
      <hr>
      <div class="cuerpo-dashboard">
        <br>
        <h2>Lista de retiros válidos:</h2>
        <table class="wp-list-table widefat tab-dark dt-responsive striped tab-invmesretiros">
          <thead>
            <tr>
              <th class="manage_column" >#</th>
              <th class="manage_column" >Cantidad</th>
              <th class="manage_column" >Cantidad final</th>
              <th class="manage_column" >Notas</th>
              <th class="manage_column" >Urgente</th>
              <th class="manage_column" >Fecha retiro</th>
              <th class="manage_column" >Status</th>
              <th class="manage_column" >Id Retiro a TD</th>
              <th class="manage_column" >Fecha solicitud</th>
              <th class="manage_column" >Fecha autorización</th>
            </tr>
          </thead>

          <tbody>
            <?php
              if(count($registros) != 0){
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

                  $fecha = substr($value["fecha_retiro"], 0, 10);

                  ?>
                  <tr>
                    <td><?php echo $key+1 ?></td>
                    <td><?php echo $cantidad; ?></td>
                    <td><?php echo $cantidadreal; ?></td>
                    <td><button aria-label='<?php echo $value["notas"]; ?>' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button></td>
                    <td><?php echo $esurgente; ?></td>
                    <td><?php echo $fecharet; ?></td>
                    <td><?php echo $statusc; ?></td>
                    <td><?php echo $idmov_ind; ?></td>
                    <td><?php echo $fecha; ?></td>
                    <td><?php echo $fechafin; ?></td>
                  </tr>
                  <?php
                }
              }
             ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php
  }

  public function interfaz_adminverdepmasmes(){
    $url = get_site_url();
    global $wpdb;
    $mesint = (int) $_GET['m'];
    //$mes = printf('%02d', $mesint);
    $agno = (int)$_GET['p'];
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );
    $tmes = $mesesNombre[$mesint];
    $depositos = $wpdb->prefix . 'depositos_master';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * , month(fecha_deposito) AS mes, year(fecha_deposito) AS agno FROM $depositos WHERE month(fecha_deposito) = $mesint AND year(fecha_deposito) = $agno AND status = 1", ARRAY_A);
    // echo "<pre>";
    // var_dump($mes);
    // var_dump($agno);
    // var_dump($registros);
    // echo "</pre>";
    ?>

    <div class="wrap">
      <div class="col-titulo">
        <h1>Depósitos <?php echo $tmes ." ". $agno ?> - Cuenta Maestra</h1>
        <img class="logo-theinc" src="" alt="logo_theinc">
      </div>
      <hr>
      <div class="cuerpo-dashboard">
        <br>
        <a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master" ?>" class="volver" ><span class="material-icons">keyboard_backspace</span>Volver a Control Maestro</a>
        <br>
        <h2>Lista de dep&oacute;sitos válidos:</h2>
        <table class="wp-list-table widefat tab-dark dt-responsive striped tab-invdepmasmes">
          <thead>
            <tr>
              <th class="manage_column" >#</th>
              <th class="manage_column" >Cantidad</th>
              <th class="manage_column" >Cantidad final</th>
              <th class="manage_column" >Notas</th>
              <th class="manage_column" >Fecha deposito</th>
              <th class="manage_column" >Status</th>
              <th class="manage_column" >Id Depósito a TD</th>
              <th class="manage_column" >Id Depósito a Master</th>
            </tr>
          </thead>

          <tbody>
            <?php
              if(count($registros) != 0){
                foreach ($registros as $key => $value) {

                  $status = $value["status"];
                  if ($status == 0) {
                    $statusc = "Cancelado";
                  }else {
                    $statusc = "Registrado";
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
                  ?>
                  <tr>
                    <td><?php echo $key+1 ?></td>
                    <td><?php echo $cantidad; ?></td>
                    <td><?php echo $cantidadreal; ?></td>
                    <td><button aria-label='<?php echo $value["notas"]; ?>' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button></td>
                    <td><?php echo $fecha; ?></td>
                    <td><?php echo $statusc; ?></td>
                    <td><?php echo $idmov_ind; ?></td>
                    <td><?php echo $idmov_gral; ?></td>
                  </tr>
                  <?php
                }
              }
             ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php
  }

  public function interfaz_adminverretmasmes(){
    $url = get_site_url();
    global $wpdb;
    $mesint = (int) $_GET['m'];
    //$mes = printf('%02d', $mesint);
    $agno = $_GET['p'];
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );
    $tmes = $mesesNombre[$mesint];
    $retiros = $wpdb->prefix . 'retiros_master';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * , month(fecha_retiro) AS mes, year(fecha_retiro) AS agno FROM $retiros WHERE month(fecha_retiro) = $mesint AND year(fecha_retiro) = $agno AND status = 1", ARRAY_A);
    $user = wp_get_current_user();
    $usernombre = $user->user_firstname;
    $usermail = $user->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // echo "<pre>";
    // var_dump($mes);
    // var_dump($agno);
    // var_dump($registros);
    // echo "</pre>";
    ?>

    <div class="wrap">
      <div class="container-fluid">
        <div class="row">
          <!-- MENU RESPONSIVE -->
          <nav class="navbar navbar-dark menu-responsive">
            <div class="container-fluid">
              <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#hamburguer-principal"
                aria-controls="hamburguer-principal" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
              </button>
              <img class="img-fluid logo-menu" src="<?php echo plugin_dir_url( __DIR__ )."assets/img/icon-inproyect.png" ?>" alt="">
            </div>
          </nav>
          <div class="collapse" id="hamburguer-principal">
            <div class="shadow-3 d-flex flex-column shadow-3 ">
              <button class="btn btn-link ham-primario btn-block border-bottom m-0 collapsed" data-bs-toggle="collapse" data-bs-target="#hamburguer1" aria-expanded="false">Control de Inversiones</button>
              <div class="collapse" id="hamburguer1" style="">
                <ul class="btn-toggle-nav list-unstyled hamburguer-sublista fw-normal pb-1 small">
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  menu-activo">Control de Depósitos</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                </ul>
              </div>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Investment Referral Com</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">NFT Projects</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Ingresos varios</button></a>
              <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Usuarios</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Wordfence Bloqueos</button></a>
              <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Salir</button></a>
            </div>
          </div>

          <div class="col-0 col-md-3 d-none d-md-block mt-3 menu-escritorio">

            <!-- MENU DESKTOP -->
            <div class="menu-redesign">
              <img src="<?php echo plugin_dir_url( __DIR__ )."assets/img/logo_theinc.png" ?>" class="menu-logo img-fluid" alt="">
              <div class="menu-info">
                <img src="<?php echo $fotourl ?>" class="menu-foto img-fluid" alt="">
                <h3 class="menu-nombre"><?php echo $usernombre; ?></h3>
                <h3 class="menu-email"><?php echo $usermail; ?></h3>
              </div>
              <p class="menu-titulo">MENU</p>
              <ul class="list-unstyled menu-lista ps-0">
                <li class="">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 boton-activo" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    Control de Inversiones
                  </button>
                  <div class="collapse show" id="home-collapse" style="">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  ">Control de Depósitos</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                    </ul>
                  </div>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Investment Referral Com
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    NFT Projects
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Ingresos Varios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Usuarios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Wordfence Bloqueos
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Salir
                  </button>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-9 antimenu-dashboard" >

            <div class="col-titulo">
              <h1>Retiros <?php echo $tmes ." ". $agno ?> - Cuenta Maestra</h1>
              <img class="logo-theinc" src="" alt="logo_theinc">
            </div>
            <hr>
            <div class="cuerpo-dashboard">
              <br>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master" ?>" class="volver" ><span class="material-icons">keyboard_backspace</span>Volver a Control Maestro</a>
              <br>
              <h2>Lista de retiros válidos:</h2>
              <table class="wp-list-table widefat tab-dark dt-responsive striped tab-invretmasmes">
                <thead>
                  <tr>
                    <th class="manage_column" >#</th>
                    <th class="manage_column" >Cantidad</th>
                    <th class="manage_column" >Notas</th>
                    <th class="manage_column" >Fecha retiro</th>
                    <th class="manage_column" >Status</th>
                    <th class="manage_column" >Id Retiro a TD</th>
                  </tr>
                </thead>

                <tbody>
                  <?php
                    if(count($registros) != 0){
                      foreach ($registros as $key => $value) {

                        $status = $value["status"];
                        if ($status == 0) {
                          $statusc = "Cancelado";
                        }else {
                          $statusc = "Registrado";
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
                        ?>
                        <tr>
                          <td><?php echo $key+1 ?></td>
                          <td><?php echo $cantidadreal; ?></td>
                          <td><button aria-label='<?php echo $value["notas"]; ?>' data-microtip-position='top' data-microtip-size='medium' class='microtexto' role='tooltip'><span class='material-icons'>speaker_notes</span></button></td>
                          <td><?php echo $fecha; ?></td>
                          <td><?php echo $statusc; ?></td>
                          <td><?php echo $idmov_ind; ?></td>
                        </tr>
                        <?php
                      }
                    }
                   ?>
                </tbody>
              </table>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

  <?php
  }

  public function interfaz_adminverutilrefmes(){
    $url = get_site_url();
    global $wpdb;
    $mesint = (int) $_GET['m'];
    //$mes = printf('%02d', $mesint);
    $agno = (int) $_GET['p'];
    $iduser = (int) $_GET['id'];
    $userdata = get_userdata( $iduser );
    $nombreuser = $userdata->first_name . ' ' .$userdata->last_name ;
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );
    $tmes = $mesesNombre[$mesint];
    $calculos = new CRC_Calculo();
    $utilidadref = $calculos->totalcuentas_utilref_individual_detalle($iduser);

    // $retiros = $wpdb->prefix . 'retiros_master';
    // $ruta = get_site_url();
    // $registros = $wpdb->get_results(" SELECT * , month(fecha_retiro) AS mes, year(fecha_retiro) AS agno FROM $retiros WHERE month(fecha_retiro) = $mesint AND year(fecha_retiro) = $agno AND status = 1", ARRAY_A);
    // echo "<pre>";
    // var_dump($utilidadref);
    // var_dump($agno);
    // var_dump($registros);
    // echo "</pre>";
    ?>

    <div class="wrap">
      <div class="col-titulo">
        <h1>Utilidad Acumulada de Referidos <?php echo $tmes ." ". $agno; ?> - <?php echo $nombreuser; ?></h1>
        <img class="logo-theinc" src="" alt="logo_theinc">
      </div>
      <hr>
      <div class="cuerpo-dashboard">
        <br>
        <a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual&m=".$mesint."&p=".$agno ?>" class="volver" ><span class="material-icons">keyboard_backspace</span>Volver a Reporte Mensual</a>
        <br>
        <div class="caja-morada mt-0">
          <h4 class="mt-3 mb-4">Lista de Usuarios Referidos activos al cierre del mes:</h4>
          <table class="wp-list-table widefat tab-dark striped tab-admininvrefmes">
            <thead>
              <tr>
                <th class="manage_column" >#</th>
                <th class="manage_column" >Usuario Referido</th>
                <th class="manage_column" >Utilidad Acum Generada</th>
              </tr>
            </thead>

            <tbody>
              <?php
              if ( count($utilidadref[0]["totalxuserrefxmes"]) == 0) {              // code...
              } else {
                $numref = 1;
                //Si tiene invitados entonces recorremos el array de invitados
                foreach ($utilidadref[0]["totalxuserrefxmes"] as $code => $invitado) {

                  // Revisamos si los invitados tienen alguna generacion de utilidades para el user
                  if (count($invitado["utilidadxmes"]) == 0) {

                  }else {
                    // Si la tienen recorremos meses y checamos que sea del mismo mes para sumarla
                    foreach ($invitado["utilidadxmes"] as $chiave => $mesdelref) {

                      if ($mesdelref["mes"] == $mesint && $mesdelref["year"] == $agno) {
                        $totalref = number_format($mesdelref["total"], 2, '.', ',');
                        ?>
                        <tr>
                          <td><?php echo $numref ; ?></td>
                          <td><?php echo $invitado["nombre"]; ?></td>
                          <td><?php echo "$".$totalref; ?></td>
                        </tr>
                        <?php
                        $numref++;
                      }

                    }
                  }

                }
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php
  }

  public function interfaz_referral_principal(){
    $url = get_site_url();
    global $wpdb;
    $tabprojectsbl = $wpdb->prefix . 'projects_bl';
    $proyectosbl = $wpdb->get_results(" SELECT * FROM $tabprojectsbl WHERE pbl_status = 1 ORDER BY pbl_id", ARRAY_A);
    $user = wp_get_current_user();
    $usernombre = $user->user_firstname;
    $usermail = $user->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">
          <!-- MENU RESPONSIVE -->
          <nav class="navbar navbar-dark menu-responsive">
            <div class="container-fluid">
              <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#hamburguer-principal"
                aria-controls="hamburguer-principal" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
              </button>
              <img class="img-fluid logo-menu" src="<?php echo plugin_dir_url( __DIR__ )."assets/img/icon-inproyect.png" ?>" alt="">
            </div>
          </nav>
          <div class="collapse" id="hamburguer-principal">
            <div class="shadow-3 d-flex flex-column shadow-3 ">
              <button class="btn btn-link ham-primario btn-block border-bottom m-0 collapsed" data-bs-toggle="collapse" data-bs-target="#hamburguer1" aria-expanded="false">Control de Inversiones</button>
              <div class="collapse" id="hamburguer1" style="">
                <ul class="btn-toggle-nav list-unstyled hamburguer-sublista fw-normal pb-1 small">
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  menu-activo">Control de Depósitos</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                </ul>
              </div>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Investment Referral Com</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">NFT Projects</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Ingresos varios</button></a>
              <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Usuarios</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Wordfence Bloqueos</button></a>
              <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Salir</button></a>
            </div>
          </div>

          <div class="col-0 col-md-3 d-none d-md-block mt-3 menu-escritorio">

            <!-- MENU DESKTOP -->
            <div class="menu-redesign">
              <img src="<?php echo plugin_dir_url( __DIR__ )."assets/img/logo_theinc.png" ?>" class="menu-logo img-fluid" alt="">
              <div class="menu-info">
                <img src="<?php echo $fotourl ?>" class="menu-foto img-fluid" alt="">
                <h3 class="menu-nombre"><?php echo $usernombre; ?></h3>
                <h3 class="menu-email"><?php echo $usermail; ?></h3>
              </div>
              <p class="menu-titulo">MENU</p>
              <ul class="list-unstyled menu-lista ps-0">
                <li class="">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    Control de Inversiones
                  </button>
                  <div class="collapse" id="home-collapse" style="">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  ">Control de Depósitos</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                    </ul>
                  </div>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed boton-activo" >
                    Investment Referral Com
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    NFT Projects
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    Ingresos Varios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Usuarios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Wordfence Bloqueos
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Salir
                  </button>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Investment Referral Commissions</h1>
              </div>

              <div class="ui-dashboard">

                <div class="d-grid gap-2 d-md-flex justify-content-center justify-content-md-between py-4">
                  <div class="">

                  </div>
                  <div class="">
                    <button class="btn-adduserbl" type="button" data-bs-toggle="modal" data-bs-target="#modal-addblproject"><i class="fa-solid fa-users"></i>Nuevo Referral Project</button>
                  </div>
                </div>

                <div class="box-transparent mb-3">

                  <div class="row referral-boxes">

                    <?php foreach ($proyectosbl as $key => $value):
                      switch ($value["pbl_color"]) {
                        case '1':
                          $colorurl = "caja-verde";
                          break;
                        case '2':
                          $colorurl = "caja-gris";
                          break;
                        case '3':
                          $colorurl = "caja-naranja";
                          break;
                        case '4':
                          $colorurl = "caja-morado";
                          break;
                        case '5':
                          $colorurl = "caja-rojo";
                          break;
                        default:
                          $colorurl = "caja-verde";
                          break;
                      }
                      if ($value["pbl_tipo"] == 0) { ?>
                        <a class="col-12 col-md-4 px-md-3 mb-3 mb-md-5" href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_dashboard&p=".$value["pbl_id"] ?>">
                          <div class="caja-referral referral-normal <?php echo $colorurl; ?>">
                            <i class="fa-solid fa-users"></i>
                            <h3><?php echo $value["pbl_nombre"]; ?></h3>
                          </div>
                        </a>
                    <?php }else { ?>
                      <a class="col-12 col-md-4 px-md-3 mb-3 mb-md-5" href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_dashboardspe&p=".$value["pbl_id"] ?>">
                        <div class="caja-referral referral-especial <?php echo $colorurl; ?>" >
                          <i class="fa-solid fa-star"></i>
                          <h3><?php echo $value["pbl_nombre"]; ?></h3>
                        </div>
                      </a>
                    <?php  } ?>
                    <?php endforeach; ?>

                  </div>

                  <div class="modal fade modal-ui" id="modal-addblproject" tabindex="-1" aria-labelledby="modal-addblproject" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title px-4" id="modal-addblproject">Agregar Nuevo Referral Project</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-4" >
                          <form id="form-addblproject" class="form-addblproject" action="#" method="post">
                            <div class="campo">
                              <label for="pbl_nombre">*Nombre(s): </label>
                              <div class="input-group mb-3">
                                <!-- <span class="input-group-text">$</span> -->
                                <input id="pbl_nombre" class="form-control" type="text" name="pbl_nombre" required >
                              </div>
                            </div>
                            <div class="campo">
                              <label for="pbl_comision">*Porcentaje de comisión: </label>
                              <div class="input-group mb-3">
                                <!-- <span class="input-group-text">$</span> -->
                                <input id="pbl_comision" class="form-control" type="text" name="pbl_comision" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                              </div>
                            </div>
                            <div class="campo">
                              <label for="pbl_comandres">*Comisión Propia: </label>
                              <div class="input-group mb-3">
                                <!-- <span class="input-group-text">$</span> -->
                                <input id="pbl_comandres" class="form-control" type="text" name="pbl_comandres" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required  disabled>
                              </div>
                            </div>
                            <div class="campo">
                              <label for="pbl_comtiger">*Comisión Tiger: </label>
                              <div class="input-group mb-3">
                                <!-- <span class="input-group-text">$</span> -->
                                <input id="pbl_comtiger" class="form-control" type="text" name="pbl_comtiger" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required  disabled>
                              </div>
                            </div>
                            <!-- <input type="hidden" name="ubl_tipo" id="ubl_tipo" value="1"> -->
                            <div class="campo">
                              <label for="pbl_tipo">*Tipo de proyecto: </label>
                              <div class="input-group mb-3">
                                <div class="form-check">
                                  <input class="form-check-input cambio-cuenta" type="radio" value="normal" name="pbl_tipo" id="pbl_tipo_normal" checked required>
                                  <label class="form-check-label" for="pbl_tipo_normal">
                                    Normal
                                  </label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input cambio-cuenta" type="radio" value="vip" name="pbl_tipo" id="pbl_tipo_vip">
                                  <label class="form-check-label" for="pbl_tipo_vip">
                                    Especial
                                  </label>
                                </div>

                              </div>
                            </div>
                            <div class="campo">
                              <label for="pbl_color">*Color de la tarjeta: </label>
                              <select class="input-group mb-3" name="pbl_color" id="pbl_color" required>
                                <option value="1" selected>Verde</option>
                                <option value="2">Gris</option>
                                <option value="3">Naranja</option>
                                <option value="4">Morado</option>
                                <option value="5">Rojo</option>
                              </select>
                            </div>
                            <div class="campo campo-notas">
                              <label for="pbl_notas">Notas: </label>
                              <textarea name="pbl_notas" id="pbl_notas" rows="5" style="resize: none;" ></textarea>
                            </div>
                            <div class="campo-especial">
                              <input id="registrarpblproject" type="submit" name="registrar" class="button button-primary" value="Registrar">
                              <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                            </div>
                          </form>

                        </div>

                      </div>
                    </div>
                  </div>

                </div>
              </div>
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_referral_nftprojects(){
    $url = get_site_url();
    global $wpdb;
    // $nft_id = (int) $_GET['id'];
    $nft_projects = $wpdb->prefix . 'projects_nft';
    // $usuarios = $wpdb->prefix . 'usuarios_bl';
    // $registrosbl = $wpdb->prefix . 'registros_bl';
    $registros = $wpdb->get_results(" SELECT * FROM $nft_projects  WHERE nft_status = 1 ", ARRAY_A);
    $cuenta = 1;
    $user = wp_get_current_user();
    $usernombre = $user->user_firstname;
    $usermail = $user->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">
          <!-- MENU RESPONSIVE -->
          <nav class="navbar navbar-dark menu-responsive">
            <div class="container-fluid">
              <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#hamburguer-principal"
                aria-controls="hamburguer-principal" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
              </button>
              <img class="img-fluid logo-menu" src="<?php echo plugin_dir_url( __DIR__ )."assets/img/icon-inproyect.png" ?>" alt="">
            </div>
          </nav>
          <div class="collapse" id="hamburguer-principal">
            <div class="shadow-3 d-flex flex-column shadow-3 ">
              <button class="btn btn-link ham-primario btn-block border-bottom m-0 collapsed" data-bs-toggle="collapse" data-bs-target="#hamburguer1" aria-expanded="false">Control de Inversiones</button>
              <div class="collapse" id="hamburguer1" style="">
                <ul class="btn-toggle-nav list-unstyled hamburguer-sublista fw-normal pb-1 small">
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  menu-activo">Control de Depósitos</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                </ul>
              </div>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Investment Referral Com</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">NFT Projects</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Ingresos varios</button></a>
              <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Usuarios</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Wordfence Bloqueos</button></a>
              <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Salir</button></a>
            </div>
          </div>

          <div class="col-0 col-md-3 d-none d-md-block mt-3 menu-escritorio">

            <!-- MENU DESKTOP -->
            <div class="menu-redesign">
              <img src="<?php echo plugin_dir_url( __DIR__ )."assets/img/logo_theinc.png" ?>" class="menu-logo img-fluid" alt="">
              <div class="menu-info">
                <img src="<?php echo $fotourl ?>" class="menu-foto img-fluid" alt="">
                <h3 class="menu-nombre"><?php echo $usernombre; ?></h3>
                <h3 class="menu-email"><?php echo $usermail; ?></h3>
              </div>
              <p class="menu-titulo">MENU</p>
              <ul class="list-unstyled menu-lista ps-0">
                <li class="">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    Control de Inversiones
                  </button>
                  <div class="collapse" id="home-collapse" style="">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  ">Control de Depósitos</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                    </ul>
                  </div>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Investment Referral Com
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed boton-activo" >
                    NFT Projects
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    Ingresos Varios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Usuarios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Wordfence Bloqueos
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Salir
                  </button>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-9 antimenu-dashboard" >

            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>NFT Projects</h1>
              </div>
              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-center justify-content-md-between py-4">
                  <div class="">

                  </div>
                  <div class="">
                    <button class="btn-adduserbl" type="button" data-bs-toggle="modal" data-bs-target="#modal-addnftproject"><i class="fa-solid fa-people-group"></i>Nuevo NFT Project</button>
                  </div>
                </div>
                <div class="box-transparent mb-3">

                  <div class="row">

                    <?php foreach ($registros as $key => $value):
                      switch ($value["nft_color"]) {
                        case '1':
                          $colorurl = "caja-verde";
                          break;
                        case '2':
                          $colorurl = "caja-gris";
                          break;
                        case '3':
                          $colorurl = "caja-naranja";
                          break;
                        case '4':
                          $colorurl = "caja-morado";
                          break;
                        case '5':
                          $colorurl = "caja-rojo";
                          break;
                        default:
                          $colorurl = "caja-verde";
                          break;
                      }
                      if ($value["nft_imagen"]) {
                        $urlimagen = get_site_url()."/wp-content/uploads/".$value["nft_imagen"] ;
                      }else{
                        $urlimagen = plugin_dir_url( __DIR__ )."assets/img/image-empty.png";
                      }
                      ?>
                      <div class="col-12 col-md-6 px-md-3">
                        <a class="link-nft " href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_vernftproject&id=".$value["nft_id"]; ?>">
                          <div class="caja-nft d-flex flex-row <?php echo $colorurl; ?>">
                            <div class="caja-img-nft">
                              <img src="<?php echo $urlimagen; ?>" class="img-fluid img-nft-avatar" alt="">
                            </div>
                            <div class="caja-desc-nft">
                              <!-- <i class="fa-solid fa-people-group"></i> -->
                              <div class="d-flex flex-column caja-tipo-nft">
                                Tipo: <br><span class="tipo-nft"><?php echo ($value["nft_tipo"] == 1) ? "Semanal" : "Mensual" ;  ?></span>
                              </div>
                              <span class="nombre-nft"><?php echo $value["nft_nombre"]; ?></span>
                            </div>

                          </div>
                        </a>
                      </div>
                    <?php

                  endforeach; ?>

                  </div>

                  <div class="modal fade modal-ui" id="modal-addnftproject" tabindex="-1" aria-labelledby="modal-addnftproject" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title px-4" id="modal-addnftproject">Agregar Nuevo Projecto NFT</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-4" >
                          <form id="form-addnftproject" class="form-addnftproject" action="#" method="post">
                            <div class="campo">
                              <label for="nft_nombre">*Nombre(s): </label>
                              <div class="input-group mb-3">
                                <!-- <span class="input-group-text">$</span> -->
                                <input id="nft_nombre" class="form-control" type="text" name="nft_nombre" required >
                              </div>
                            </div>
                            <!-- <input type="hidden" name="ubl_tipo" id="ubl_tipo" value="1"> -->
                            <div class="campo">
                              <label for="ubl_tipo">*Tipo de proyecto: </label>
                              <div class="input-group mb-3">
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="nft_tipo" id="nft_tipo_mensual" checked required>
                                  <label class="form-check-label" for="nft_tipo_mensual">
                                    Mensual
                                  </label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="nft_tipo" id="nft_tipo_semanal">
                                  <label class="form-check-label" for="nft_tipo_semanal">
                                    Semanal
                                  </label>
                                </div>

                              </div>
                            </div>
                            <div class="campo campo-notas">
                              <label for="nft_notas">Notas: </label>
                              <textarea name="nft_notas" id="nft_notas" rows="5" style="resize: none;" ></textarea>
                            </div>
                            <div class="campo-especial">
                              <input id="registrarnftproject" type="submit" name="registrar" class="button button-primary" value="Registrar">
                              <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                            </div>
                          </form>

                        </div>

                      </div>
                    </div>
                  </div>

                </div>
              </div>
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_referral_dashboard(){
    $url = get_site_url();
    global $wpdb;
    $pbl_id = (int) $_GET['p'];
    $bl_projects = $wpdb->prefix . 'projects_bl';
    $bl_usuarios = $wpdb->prefix . 'usuarios_bl';
    // $registrosbl = $wpdb->prefix . 'registros_bl';
    $registros = $wpdb->get_results(" SELECT * FROM $bl_projects  WHERE pbl_id = $pbl_id AND pbl_status = 1 LIMIT 1", ARRAY_A);
    // Saber si hay registros en el proyecto
    $registros1 = $wpdb->get_results(" SELECT * FROM $bl_usuarios WHERE ubl_project = $pbl_id  ", ARRAY_A);
    $user = wp_get_current_user();
    $usernombre = $user->user_firstname;
    $usermail = $user->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">
          <!-- MENU RESPONSIVE -->
          <nav class="navbar navbar-dark menu-responsive">
            <div class="container-fluid">
              <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#hamburguer-principal"
                aria-controls="hamburguer-principal" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
              </button>
              <img class="img-fluid logo-menu" src="<?php echo plugin_dir_url( __DIR__ )."assets/img/icon-inproyect.png" ?>" alt="">
            </div>
          </nav>
          <div class="collapse" id="hamburguer-principal">
            <div class="shadow-3 d-flex flex-column shadow-3 ">
              <button class="btn btn-link ham-primario btn-block border-bottom m-0 collapsed" data-bs-toggle="collapse" data-bs-target="#hamburguer1" aria-expanded="false">Control de Inversiones</button>
              <div class="collapse" id="hamburguer1" style="">
                <ul class="btn-toggle-nav list-unstyled hamburguer-sublista fw-normal pb-1 small">
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  menu-activo">Control de Depósitos</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                </ul>
              </div>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Investment Referral Com</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">NFT Projects</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Ingresos varios</button></a>
              <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Usuarios</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Wordfence Bloqueos</button></a>
              <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Salir</button></a>
            </div>
          </div>

          <div class="col-0 col-md-3 d-none d-md-block mt-3 menu-escritorio">

            <!-- MENU DESKTOP -->
            <div class="menu-redesign">
              <img src="<?php echo plugin_dir_url( __DIR__ )."assets/img/logo_theinc.png" ?>" class="menu-logo img-fluid" alt="">
              <div class="menu-info">
                <img src="<?php echo $fotourl ?>" class="menu-foto img-fluid" alt="">
                <h3 class="menu-nombre"><?php echo $usernombre; ?></h3>
                <h3 class="menu-email"><?php echo $usermail; ?></h3>
              </div>
              <p class="menu-titulo">MENU</p>
              <ul class="list-unstyled menu-lista ps-0">
                <li class="">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    Control de Inversiones
                  </button>
                  <div class="collapse" id="home-collapse" style="">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  ">Control de Depósitos</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                    </ul>
                  </div>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed boton-activo" >
                    Investment Referral Com
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    NFT Projects
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    Ingresos Varios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Usuarios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Wordfence Bloqueos
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Salir
                  </button>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-9 antimenu-dashboard" >

            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1><?php echo $registros[0]["pbl_nombre"]; ?></h1>
              </div>
              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-4">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal" ?>" class="volverref" ><span class="material-icons">keyboard_backspace</span>Volver al Dashboard Principal</a>
                  </div>
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_verreportemesbl&p=".$pbl_id ?>"><button class="btn-verrepmesbl" type="button"><i class="fa-solid fa-calendar-days"></i>Reporte Mensual Global</button></a>
                    <button class="btn-editnftprojectbl" type="button" data-bs-toggle="modal" data-bs-target="#modal-editnftprojectbl"><i class="fa-solid fa-pencil"></i>Editar Project</button>
                    <?php if(count($registros1) == 0){ ?>
                      <button class="btn-deleteproyectobl" data-id="<?php echo $pbl_id; ?>" type="button" ><i class="fa-solid fa-x" ></i>Eliminar</button>
                    <?php } ?>
                    <button class="btn-adduserbl" type="button" data-bs-toggle="modal" data-bs-target="#modal-adduserbl"><i class="fa-solid fa-user-plus" ></i>Nuevo Inversor</button>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-editnftprojectbl" tabindex="-1" aria-labelledby="modal-editnftprojectblLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-4" id="modal-editblprojectLabel">Editar proyecto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-editblproject" class="form-editblproject" action="#" method="post">
                          <input type="hidden" id="pbl_eid" name="pbl_eid" value="<?php echo $pbl_id ?>">
                          <div class="campo">
                            <label for="pbl_enombre">*Nombre(s): </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="pbl_enombre" class="form-control" type="text" name="pbl_enombre" value="<?php echo $registros[0]['pbl_nombre'] ?>" required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="pbl_ecomision">*Porcentaje de comisión: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="pbl_ecomision" class="form-control" type="text" name="pbl_ecomision" value="<?php echo (float)$registros[0]['pbl_comision'] ?>" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                              <input id="pbl_ecomandres" class="form-control" type="text" name="pbl_ecomandres" value="<?php echo (float)$registros[0]['pbl_comandres'] ?>" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required hidden >
                              <input id="pbl_ecomtiger" class="form-control" type="text" name="pbl_ecomtiger" value="<?php echo (float)$registros[0]['pbl_comtiger'] ?>" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required hidden >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="pbl_ecolor">*Color de la tarjeta: </label>
                            <select class="input-group mb-3" name="pbl_ecolor" id="pbl_ecolor" required>
                              <option value="1" <?php  echo ($registros[0]['pbl_color'] == 1) ? "selected"  :  "" ; ?> >Verde</option>
                              <option value="2" <?php  echo ($registros[0]['pbl_color'] == 2) ? "selected"  :  "" ; ?> >Gris</option>
                              <option value="3" <?php  echo ($registros[0]['pbl_color'] == 3) ? "selected"  :  "" ; ?> >Naranja</option>
                              <option value="4" <?php  echo ($registros[0]['pbl_color'] == 4) ? "selected"  :  "" ; ?> >Morado</option>
                              <option value="5" <?php  echo ($registros[0]['pbl_color'] == 5) ? "selected"  :  "" ; ?> >Rojo</option>
                            </select>
                          </div>
                          <div class="campo campo-notas">
                            <label for="pbl_enotas">Notas: </label>
                            <textarea name="pbl_enotas" id="pbl_enotas" rows="5" style="resize: none;" ><?php echo $registros[0]['pbl_notas'] ?></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="editarproyectobl" type="submit" name="editarproyecto" class="button button-primary" value="Editar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-adduserbl" tabindex="-1" aria-labelledby="modal-adduserblLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-4" id="modal-adduserblLabel">Agregar Inversor Normal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-adduserbl" class="form-adduserbl" action="#" method="post">
                          <input type="text" name="ubl_project" id="ubl_project" value="<?php echo $pbl_id; ?>" hidden>
                          <div class="campo">
                            <label for="ubl_nombre">*Nombre(s): </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="ubl_nombre" class="form-control" type="text" name="ubl_nombre" required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="ubl_apellidos">*Apellido(s): </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="ubl_apellidos" class="form-control" type="text" name="ubl_apellidos" required >
                            </div>
                          </div>
                          <input type="hidden" name="ubl_tipo" id="ubl_tipo" value="0">
                          <!-- <div class="campo">
                            <label for="ubl_tipo">*Tipo de inversor: </label>
                            <div class="input-group mb-3">
                              -->
                              <!-- <div class="form-check">
                                <input class="form-check-input" type="radio" name="ubl_tipo" id="ubl_tipo_normal" checked required>
                                <label class="form-check-label" for="ubl_tipo_normal">
                                  Normal
                                </label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="radio" name="ubl_tipo" id="ubl_tipo_especial">
                                <label class="form-check-label" for="ubl_tipo_especial">
                                  Especial
                                </label>
                              </div>

                            </div>
                          </div>  -->
                          <div class="campo">
                            <label for="ubl_email">Email: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="ubl_email" class="form-control" type="email" name="ubl_email" >
                            </div>
                          </div>
                          <div class="campo campo-notas">
                            <label for="ubl_notas">Notas: </label>
                            <textarea name="ubl_notas" id="ubl_notas" rows="5" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="registraruserbl" type="submit" name="registrar" class="button button-primary" value="Agregar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="box-transparent mb-3">
                  <h4 class="mb-5">Lista de Inversores Big Level:</h4>
                  <table class="wp-list-table widefat tab-ui striped tab-referralusers dt-responsive" >
                    <thead>
                      <tr>
                        <th class="manage_column" style="witdh:100px;!important">#</th>
                        <th class="manage_column" style="max-witdh:100px;">Nombre</th>
                        <th class="manage_column" style="max-witdh:100px;">Cuentas</th>
                        <th class="manage_column" >Acciones</th>
                        <th class="manage_column" >Status</th>
                      </tr>
                    </thead>

                    <tbody>

                    </tbody>
                  </table>

                  <div class="modal fade modal-ui" id="modal-edituserbl" tabindex="-1" aria-labelledby="modal-edituserblLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title px-4" id="modal-edituserblLabel">Editar Inversor Big Level</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-4" >
                          <form id="form-edituserbl" class="form-edituserbl" action="#" method="post">
                            <input type="hidden" id="ubl_eid" name="ubl_eid" value="">
                            <div class="campo">
                              <label for="ubl_enombre">*Nombre(s): </label>
                              <div class="input-group mb-3">
                                <!-- <span class="input-group-text">$</span> -->
                                <input id="ubl_enombre" class="form-control" type="text" name="ubl_enombre" required >
                              </div>
                            </div>
                            <div class="campo">
                              <label for="ubl_eapellidos">*Apellido(s): </label>
                              <div class="input-group mb-3">
                                <!-- <span class="input-group-text">$</span> -->
                                <input id="ubl_eapellidos" class="form-control" type="text" name="ubl_eapellidos" required >
                              </div>
                            </div>
                            <!-- <div class="campo">
                              <label for="ubl_etipo">*Tipo de inversor: </label>
                              <div class="input-group mb-3">

                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="ubl_etipo" id="ubl_etipo_normal" checked required>
                                  <label class="form-check-label" for="ubl_etipo_normal">
                                    Normal
                                  </label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="ubl_etipo" id="ubl_etipo_especial">
                                  <label class="form-check-label" for="ubl_etipo_especial">
                                    Especial
                                  </label>
                                </div>

                              </div>
                            </div> -->
                            <div class="campo">
                              <label for="ubl_eemail">Email: </label>
                              <div class="input-group mb-3">
                                <!-- <span class="input-group-text">$</span> -->
                                <input id="ubl_eemail" class="form-control" type="email" name="ubl_eemail" >
                              </div>
                            </div>
                            <div class="campo campo-notas">
                              <label for="ubl_enotas">Notas: </label>
                              <textarea name="ubl_enotas" id="ubl_enotas" rows="5" style="resize: none;" ></textarea>
                            </div>
                            <div class="campo-especial">
                              <input id="editaruserbl" type="submit" name="editar" class="button button-primary" value="Editar">
                              <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                            </div>
                          </form>

                        </div>

                      </div>
                    </div>
                  </div>

                  <!-- Modal agregar cuenta -->

                  <div class="modal fade modal-ui" id="modal-addcuentabl" tabindex="-1" aria-labelledby="modal-addcuentablLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title px-4" id="modal-addcuentablLabel">Agregar cuenta</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-4" >
                          <form id="form-addcuentabl" class="form-addcuentabl" action="#" method="post">
                            <input type="hidden" id="cbl_uid" name="cbl_uid" value="">
                            <div class="campo">
                              <label for="cbl_nombre">*Nombre de la cuenta: </label>
                              <div class="input-group mb-3">
                                <!-- <span class="input-group-text">$</span> -->
                                <input id="cbl_nombre" class="form-control" type="text" name="cbl_nombre" required >
                              </div>
                            </div>
                            <div class="campo">
                              <label for="cbl_numero">*Número de cuenta: </label>
                              <div class="input-group mb-3">
                                <!-- <span class="input-group-text">$</span> -->
                                <input id="cbl_numero" class="form-control" type="text" name="cbl_numero" value="" required >
                              </div>
                            </div>
                            <div class="campo campo-notas">
                              <label for="cbl_notas">Notas: </label>
                              <textarea name="cbl_notas" id="cbl_notas" rows="5" style="resize: none;" ></textarea>
                            </div>
                            <div class="campo-especial">
                              <input id="agregarcuentabl" type="submit" name="agregarcuenta" class="button button-primary" value="Agregar">
                              <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                            </div>
                          </form>

                        </div>

                      </div>
                    </div>
                  </div>

                </div>
              </div>
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_referral_dashboardspe(){
    $url = get_site_url();
    global $wpdb;
    $pbl_id = (int) $_GET['p'];
    $bl_projects = $wpdb->prefix . 'projects_bl';
    $bl_usuarios = $wpdb->prefix . 'usuarios_bl';
    // $registrosbl = $wpdb->prefix . 'registros_bl';
    $registros = $wpdb->get_results(" SELECT * FROM $bl_projects  WHERE pbl_id = $pbl_id AND pbl_status = 1 LIMIT 1", ARRAY_A);
    // Saber si hay registros en el proyecto
    $registros1 = $wpdb->get_results(" SELECT * FROM $bl_usuarios WHERE ubl_project = $pbl_id AND ubl_status = 1 ", ARRAY_A);
    $user = wp_get_current_user();
    $usernombre = $user->user_firstname;
    $usermail = $user->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">
          <!-- MENU RESPONSIVE -->
          <nav class="navbar navbar-dark menu-responsive">
            <div class="container-fluid">
              <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#hamburguer-principal"
                aria-controls="hamburguer-principal" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
              </button>
              <img class="img-fluid logo-menu" src="<?php echo plugin_dir_url( __DIR__ )."assets/img/icon-inproyect.png" ?>" alt="">
            </div>
          </nav>
          <div class="collapse" id="hamburguer-principal">
            <div class="shadow-3 d-flex flex-column shadow-3 ">
              <button class="btn btn-link ham-primario btn-block border-bottom m-0 collapsed" data-bs-toggle="collapse" data-bs-target="#hamburguer1" aria-expanded="false">Control de Inversiones</button>
              <div class="collapse" id="hamburguer1" style="">
                <ul class="btn-toggle-nav list-unstyled hamburguer-sublista fw-normal pb-1 small">
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  menu-activo">Control de Depósitos</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                </ul>
              </div>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Investment Referral Com</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">NFT Projects</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Ingresos varios</button></a>
              <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Usuarios</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Wordfence Bloqueos</button></a>
              <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Salir</button></a>
            </div>
          </div>

          <div class="col-0 col-md-3 d-none d-md-block mt-3 menu-escritorio">

            <!-- MENU DESKTOP -->
            <div class="menu-redesign">
              <img src="<?php echo plugin_dir_url( __DIR__ )."assets/img/logo_theinc.png" ?>" class="menu-logo img-fluid" alt="">
              <div class="menu-info">
                <img src="<?php echo $fotourl ?>" class="menu-foto img-fluid" alt="">
                <h3 class="menu-nombre"><?php echo $usernombre; ?></h3>
                <h3 class="menu-email"><?php echo $usermail; ?></h3>
              </div>
              <p class="menu-titulo">MENU</p>
              <ul class="list-unstyled menu-lista ps-0">
                <li class="">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    Control de Inversiones
                  </button>
                  <div class="collapse" id="home-collapse" style="">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  ">Control de Depósitos</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                    </ul>
                  </div>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed boton-activo" >
                    Investment Referral Com
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    NFT Projects
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    Ingresos Varios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Usuarios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Wordfence Bloqueos
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Salir
                  </button>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-9 antimenu-dashboard" >

            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1><i class="fa-solid fa-star"></i><?php echo $registros[0]["pbl_nombre"]; ?></h1>
              </div>
              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-4">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal" ?>" class="volverref" ><span class="material-icons">keyboard_backspace</span>Volver al Dashboard Principal</a>
                  </div>
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_verreportemesspebl&p=".$pbl_id ?>"><button class="btn-verrepmesbl" type="button"><i class="fa-solid fa-calendar-days"></i>Reporte Mensual Global</button></a>
                    <button class="btn-editnftprojectbl" type="button" data-bs-toggle="modal" data-bs-target="#modal-editnftprojectbl"><i class="fa-solid fa-pencil"></i>Editar Project</button>
                    <?php if(count($registros1) == 0){ ?>
                      <button class="btn-deleteproyectobl" data-id="<?php echo $pbl_id; ?>" type="button" ><i class="fa-solid fa-x" ></i>Eliminar</button>
                    <?php } ?>
                    <button class="btn-adduserbl" type="button" data-bs-toggle="modal" data-bs-target="#modal-adduserbl"><i class="fa-solid fa-user-plus" ></i>Nuevo Inversor</button>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-adduserbl" tabindex="-1" aria-labelledby="modal-adduserblLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-4" id="modal-adduserblLabel">Agregar Inversor VIP</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-adduserbl" class="form-adduserbl" action="#" method="post">
                          <input type="text" name="ubl_project" id="ubl_project" value="<?php echo $pbl_id; ?>" hidden>
                          <div class="campo">
                            <label for="ubl_nombre">*Nombre(s): </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="ubl_nombre" class="form-control" type="text" name="ubl_nombre" required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="ubl_apellidos">*Apellido(s): </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="ubl_apellidos" class="form-control" type="text" name="ubl_apellidos" required >
                            </div>
                          </div>
                          <input type="hidden" name="ubl_tipo" id="ubl_tipo" value="1">
                          <!-- <div class="campo">
                            <label for="ubl_tipo">*Tipo de inversor: </label>
                            <div class="input-group mb-3">
                              -->
                              <!-- <div class="form-check">
                                <input class="form-check-input" type="radio" name="ubl_tipo" id="ubl_tipo_normal" checked required>
                                <label class="form-check-label" for="ubl_tipo_normal">
                                  Normal
                                </label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="radio" name="ubl_tipo" id="ubl_tipo_especial">
                                <label class="form-check-label" for="ubl_tipo_especial">
                                  Especial
                                </label>
                              </div>

                            </div>
                          </div>  -->
                          <div class="campo">
                            <label for="ubl_email">Email: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="ubl_email" class="form-control" type="email" name="ubl_email" >
                            </div>
                          </div>
                          <div class="campo campo-notas">
                            <label for="ubl_notas">Notas: </label>
                            <textarea name="ubl_notas" id="ubl_notas" rows="5" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="registraruserbl" type="submit" name="registrar" class="button button-primary" value="Agregar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="box-transparent mb-3">
                  <h4 class="mb-5">Lista de Inversores:</h4>
                  <table class="wp-list-table widefat tab-ui striped tab-referralusersspe dt-responsive" >
                    <thead>
                      <tr>
                        <th class="manage_column" style="witdh:100px;!important">#</th>
                        <th class="manage_column" style="max-witdh:100px;">Nombre</th>
                        <th class="manage_column" style="max-witdh:100px;">Cuenta</th>
                        <th class="manage_column" >Acciones</th>
                        <th class="manage_column" >Status</th>
                      </tr>
                    </thead>

                    <tbody>

                    </tbody>
                  </table>

                  <div class="modal fade modal-ui" id="modal-editnftprojectbl" tabindex="-1" aria-labelledby="modal-editnftprojectblLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title px-4" id="modal-editblprojectLabel">Editar proyecto</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-4" >
                          <form id="form-editblproject" class="form-editblproject" action="#" method="post">
                            <input type="hidden" id="pbl_eid" name="pbl_eid" value="<?php echo $pbl_id ?>">
                            <div class="campo">
                              <label for="pbl_enombre">*Nombre(s): </label>
                              <div class="input-group mb-3">
                                <!-- <span class="input-group-text">$</span> -->
                                <input id="pbl_enombre" class="form-control" type="text" name="pbl_enombre" value="<?php echo $registros[0]['pbl_nombre'] ?>" required >
                              </div>
                            </div>
                            <div class="campo">
                              <label for="pbl_ecomandres">*Comisión Propia: </label>
                              <div class="input-group mb-3">
                                <!-- <span class="input-group-text">$</span> -->
                                <input id="pbl_ecomandres" class="form-control" type="text" name="pbl_ecomandres" value="<?php echo (float)$registros[0]['pbl_comandres'] ?>" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                              </div>
                            </div>
                            <div class="campo">
                              <label for="pbl_ecomtiger">*Comisión Tiger: </label>
                              <div class="input-group mb-3">
                                <!-- <span class="input-group-text">$</span> -->
                                <input id="pbl_ecomision" class="form-control" type="text" name="pbl_ecomision" value="<?php echo (float)$registros[0]['pbl_comision'] ?>" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required hidden>
                                <input id="pbl_ecomtiger" class="form-control" type="text" name="pbl_ecomtiger" value="<?php echo (float)$registros[0]['pbl_comtiger'] ?>" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                              </div>
                            </div>
                            <div class="campo">
                              <label for="pbl_ecolor">*Color de la tarjeta: </label>
                              <select class="input-group mb-3" name="pbl_ecolor" id="pbl_ecolor" required>
                                <option value="1" <?php  echo ($registros[0]['pbl_color'] == 1) ? "selected"  :  "" ; ?> >Verde</option>
                                <option value="2" <?php  echo ($registros[0]['pbl_color'] == 2) ? "selected"  :  "" ; ?> >Gris</option>
                                <option value="3" <?php  echo ($registros[0]['pbl_color'] == 3) ? "selected"  :  "" ; ?> >Naranja</option>
                                <option value="4" <?php  echo ($registros[0]['pbl_color'] == 4) ? "selected"  :  "" ; ?> >Morado</option>
                                <option value="5" <?php  echo ($registros[0]['pbl_color'] == 5) ? "selected"  :  "" ; ?> >Rojo</option>
                              </select>
                            </div>
                            <div class="campo campo-notas">
                              <label for="pbl_enotas">Notas: </label>
                              <textarea name="pbl_enotas" id="pbl_enotas" rows="5" style="resize: none;" ><?php echo $registros[0]['pbl_notas'] ?></textarea>
                            </div>
                            <div class="campo-especial">
                              <input id="editarproyectobl" type="submit" name="editarproyecto" class="button button-primary" value="Editar">
                              <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                            </div>
                          </form>

                        </div>

                      </div>
                    </div>
                  </div>

                  <div class="modal fade modal-ui" id="modal-edituserbl" tabindex="-1" aria-labelledby="modal-edituserblLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title px-4" id="modal-edituserblLabel">Editar Inversor Big Level</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-4" >
                          <form id="form-edituserbl" class="form-edituserbl" action="#" method="post">
                            <input type="hidden" id="ubl_eid" name="ubl_eid" value="">
                            <div class="campo">
                              <label for="ubl_enombre">*Nombre(s): </label>
                              <div class="input-group mb-3">
                                <!-- <span class="input-group-text">$</span> -->
                                <input id="ubl_enombre" class="form-control" type="text" name="ubl_enombre" required >
                              </div>
                            </div>
                            <div class="campo">
                              <label for="ubl_eapellidos">*Apellido(s): </label>
                              <div class="input-group mb-3">
                                <!-- <span class="input-group-text">$</span> -->
                                <input id="ubl_eapellidos" class="form-control" type="text" name="ubl_eapellidos" required >
                              </div>
                            </div>
                            <!-- <div class="campo">
                              <label for="ubl_etipo">*Tipo de inversor: </label>
                              <div class="input-group mb-3">

                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="ubl_etipo" id="ubl_etipo_normal" checked required>
                                  <label class="form-check-label" for="ubl_etipo_normal">
                                    Normal
                                  </label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="ubl_etipo" id="ubl_etipo_especial">
                                  <label class="form-check-label" for="ubl_etipo_especial">
                                    Especial
                                  </label>
                                </div>

                              </div>
                            </div> -->
                            <div class="campo">
                              <label for="ubl_eemail">Email: </label>
                              <div class="input-group mb-3">
                                <!-- <span class="input-group-text">$</span> -->
                                <input id="ubl_eemail" class="form-control" type="email" name="ubl_eemail" >
                              </div>
                            </div>
                            <div class="campo campo-notas">
                              <label for="ubl_enotas">Notas: </label>
                              <textarea name="ubl_enotas" id="ubl_enotas" rows="5" style="resize: none;" ></textarea>
                            </div>
                            <div class="campo-especial">
                              <input id="editaruserbl" type="submit" name="editar" class="button button-primary" value="Editar">
                              <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                            </div>
                          </form>

                        </div>

                      </div>
                    </div>
                  </div>

                  <!-- Modal agregar cuenta -->

                  <div class="modal fade modal-ui" id="modal-addcuentabl" tabindex="-1" aria-labelledby="modal-addcuentablLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title px-4" id="modal-addcuentablLabel">Agregar cuenta</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-4" >
                          <form id="form-addcuentabl" class="form-addcuentabl" action="#" method="post">
                            <input type="hidden" id="cbl_uid" name="cbl_uid" value="">
                            <div class="campo">
                              <label for="cbl_nombre">*Nombre de la cuenta: </label>
                              <div class="input-group mb-3">
                                <!-- <span class="input-group-text">$</span> -->
                                <input id="cbl_nombre" class="form-control" type="text" name="cbl_nombre" required >
                              </div>
                            </div>
                            <div class="campo">
                              <label for="cbl_numero">*Número de cuenta: </label>
                              <div class="input-group mb-3">
                                <!-- <span class="input-group-text">$</span> -->
                                <input id="cbl_numero" class="form-control" type="text" name="cbl_numero" value="" required >
                              </div>
                            </div>
                            <div class="campo campo-notas">
                              <label for="cbl_notas">Notas: </label>
                              <textarea name="cbl_notas" id="cbl_notas" rows="5" style="resize: none;" ></textarea>
                            </div>
                            <div class="campo-especial">
                              <input id="agregarcuentabl" type="submit" name="agregarcuenta" class="button button-primary" value="Agregar">
                              <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                            </div>
                          </form>

                        </div>

                      </div>
                    </div>
                  </div>

                </div>
              </div>
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_referral_ingresosvar(){
    $url = get_site_url();
    global $wpdb;
    $year = date("Y");
    $month = date("m");
    // $nft_id = (int) $_GET['id'];
    $nft_projects = $wpdb->prefix . 'projects_nft';
    // $usuarios = $wpdb->prefix . 'usuarios_bl';
    // $registrosbl = $wpdb->prefix . 'registros_bl';
    // $registros = $wpdb->get_results(" SELECT * FROM $nft_projects  WHERE nft_status = 1 ", ARRAY_A);
    $cuenta = 1;
    $user = wp_get_current_user();
    $usernombre = $user->user_firstname;
    $usermail = $user->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">
          <!-- MENU RESPONSIVE -->
          <nav class="navbar navbar-dark menu-responsive">
            <div class="container-fluid">
              <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#hamburguer-principal"
                aria-controls="hamburguer-principal" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
              </button>
              <img class="img-fluid logo-menu" src="<?php echo plugin_dir_url( __DIR__ )."assets/img/icon-inproyect.png" ?>" alt="">
            </div>
          </nav>
          <div class="collapse" id="hamburguer-principal">
            <div class="shadow-3 d-flex flex-column shadow-3 ">
              <button class="btn btn-link ham-primario btn-block border-bottom m-0 collapsed" data-bs-toggle="collapse" data-bs-target="#hamburguer1" aria-expanded="false">Control de Inversiones</button>
              <div class="collapse" id="hamburguer1" style="">
                <ul class="btn-toggle-nav list-unstyled hamburguer-sublista fw-normal pb-1 small">
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  menu-activo">Control de Depósitos</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                </ul>
              </div>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Investment Referral Com</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">NFT Projects</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Ingresos varios</button></a>
              <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Usuarios</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Wordfence Bloqueos</button></a>
              <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Salir</button></a>
            </div>
          </div>

          <div class="col-0 col-md-3 d-none d-md-block mt-3 menu-escritorio">

            <!-- MENU DESKTOP -->
            <div class="menu-redesign">
              <img src="<?php echo plugin_dir_url( __DIR__ )."assets/img/logo_theinc.png" ?>" class="menu-logo img-fluid" alt="">
              <div class="menu-info">
                <img src="<?php echo $fotourl ?>" class="menu-foto img-fluid" alt="">
                <h3 class="menu-nombre"><?php echo $usernombre; ?></h3>
                <h3 class="menu-email"><?php echo $usermail; ?></h3>
              </div>
              <p class="menu-titulo">MENU</p>
              <ul class="list-unstyled menu-lista ps-0">
                <li class="">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    Control de Inversiones
                  </button>
                  <div class="collapse" id="home-collapse" style="">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  ">Control de Depósitos</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                    </ul>
                  </div>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Investment Referral Com
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    NFT Projects
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed boton-activo" >
                    Ingresos Varios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Usuarios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Wordfence Bloqueos
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Salir
                  </button>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-9 antimenu-dashboard" >

            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Ingresos varios</h1>
              </div>
              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-center justify-content-md-between py-4">
                  <div class="">

                  </div>
                  <div class="">
                    <button class="btn-addregvar" id="btn-addregvar" type="button" data-bs-toggle="modal" data-bs-target="#modal-addregvar"><i class="fa-solid fa-sack-dollar"></i>Agregar ingreso</button>
                  </div>
                </div>
                <div class="box-transparent mb-3">

                  <h4 class="mb-5">Ingresos mensuales:</h4>
                  <table class="wp-list-table widefat tab-ui striped tab-referralvarmes dt-responsive" >
                    <thead>
                      <tr>
                        <th class="manage_column" >#</th>
                        <th class="manage_column" >Año</th>
                        <th class="manage_column" >Mes</th>
                        <th class="manage_column" >Total</th>
                        <th class="manage_column" >Acciones</th>
                      </tr>
                    </thead>

                    <tbody>

                    </tbody>
                  </table>

                  <div class="modal fade modal-ui" id="modal-addregvar" tabindex="-1" aria-labelledby="modal-addregvarLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title px-2" id="modal-addregvarLabel">Agregar ingreso</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-4" >
                          <form id="form-addregvar" class="form-addregvarl" action="#" method="post">
                            <div class="campo">
                              <label for="rvar_mes">*Mes: </label>
                              <input id="rvar_mes" type="number" name="rvar_mes" value="<?php echo $month; ?>" min="1" max="12"  required>
                            </div>
                            <div class="campo">
                              <label for="rvar_agno">*Año: </label>
                              <input id="rvar_agno" type="text" name="rvar_agno" value="<?php echo $year; ?>" data-inputmask="'mask':'9{4}'" data-mask required>
                            </div>
                            <div class="campo">
                              <label for="rvar_nombre">*Concepto: </label>
                              <div class="input-group mb-3">
                                <!-- <span class="input-group-text">$</span> -->
                                <input id="rvar_nombre" class="form-control" type="text" name="rvar_nombre" required >
                              </div>
                            </div>
                            <div class="campo">
                              <label for="rvar_cantidad">*Cantidad: </label>
                              <div class="input-group mb-3">
                                <!-- <span class="input-group-text">$</span> -->
                                <input id="rvar_cantidad" class="form-control" type="text" name="rvar_cantidad" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                              </div>
                            </div>
                            <div class="campo campo-notas">
                              <label for="rvar_notas">Notas: </label>
                              <textarea name="rvar_notas" id="rvar_notas" rows="5" style="resize: none;" ></textarea>
                            </div>
                            <div class="campo-especial">
                              <input id="agregarregvar" type="submit" name="agregarregvar" class="button button-primary" value="Agregar">
                              <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                            </div>
                          </form>

                        </div>

                      </div>
                    </div>
                  </div>

                </div>
              </div>
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->


    <?php
  }

  public function interfaz_agreadmindashboard(){
    $year = (int) date("Y");
    $month = (int) date("m");
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    // $modagresivo = get_user_meta( $user, 'modagresivo', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "agresivo";
    $submenu = "agreadmincontrol";
    $subadministrador = false;

    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        if ( in_array( 'subadministrador', $user->roles ) ) {
          $subadministrador = true;
      }
    }

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }

    // BUSCAMOS A TODOS LOS USUARIOS QUE PARTICIPAN EN AGRESIVO:

    global $wpdb;
    $tabladep = $wpdb->prefix.'depositos_agr';

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

    if (count($listausers) == 0 ) {
      $hayagreusers = false ;
    }else {
      $hayagreusers = true ;
    }
    // $listausers_json = json_encode($listausers);

    // echo "<pre>";
    // var_dump($hayagreusers);
    // echo "</pre>";

    $calculos = new CRC_AgreCalculo();
    $detallecajasadmin = $calculos->crc_datoscajasuperiores_admin();

    // echo "<pre>";
    // var_dump($detallecajasadmin);
    // echo "</pre>";

    $totalcuenta = $detallecajasadmin[0]["totalcuenta"];
    $ttotalcuenta = number_format($totalcuenta,2);
    $totalinvestors = $detallecajasadmin[0]["totalinvestors"];
    $ttotalinvestors = number_format($totalinvestors ,2);
    $totaltheinc = $detallecajasadmin[0]["totaltheinc"];
    $ttotaltheinc = number_format($totaltheinc ,2);
    $totalgopro = $detallecajasadmin[0]["totalgopro"];
    $ttotalgopro = number_format($totalgopro  ,2);

    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-admin.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Control Maestro</h1>
              </div>

              <div class="ui-dashboard">
                <!-- cajas superiores -->
                <div class="container-fluid px-0 my-4 pt-5 pb-4">
                  <div class="row">
                    <div class="col-12 col-md-3 ">
                      <div class="rd-cajasup rd-cajasup-azul">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-flag"></i></span>
                        <h4>Total cuenta</h4>
                        <span class="rd-cajasup-cifra">$<?php echo $ttotalcuenta ?></span>
                      </div>
                    </div>
                    <div class="col-12 col-md-3 ">
                      <div class="rd-cajasup rd-cajasup-verde">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-user-group"></i></span>
                        <h4>Capital Inversionistas</h4>
                        <span class="rd-cajasup-cifra">$<?php echo $ttotalinvestors ?></span>
                      </div>
                    </div>
                    <div class="col-12 col-md-3 ">
                      <div class="rd-cajasup rd-cajasup-gris">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-file-circle-check"></i></span>
                        <h4>Total The Inc</h4>
                        <span class="rd-cajasup-cifra">$<?php echo $ttotaltheinc ?></span>
                      </div>
                    </div>
                    <div class="col-12 col-md-3 ">
                      <div class="rd-cajasup rd-cajasup-naranja">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-chart-line"></i></span>
                        <h4>Total GoPro</h4>
                        <span class="rd-cajasup-cifra">$<?php echo $ttotalgopro ?></span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Botones de deposito y retiro -->
                <div class="oper-master row mt-4 pt-3">
                  <div class="oper-mes oper-mes1 col-12 col-md-6 d-flex flex-column ">
                    <div class="caja-agredep d-flex ">
                      <button class='btn-addagredep d-flex' id='btn-addagredepmas' type='button' data-bs-toggle='modal' data-bs-target='#modal-addagredepmas' >
                        <span class="oper-agredep"><i class="fa-solid fa-arrow-down"></i></span>
                        <div class="d-flex flex-column">
                          <span class="oper-titulo">Depósito Master</span>
                        </div>
                      </button>
                    </div>
                    <table class="wp-list-table widefat tab-ui striped tab-agredepmas">
                      <thead>
                        <tr>
                          <th class="manage_column" >Fecha</th>
                          <th class="manage_column" >Cantidad</th>
                          <th class="manage_column" >Status</th>
                        </tr>
                      </thead>

                      <tbody>

                      </tbody>
                    </table>
                  </div>
                  <div class="oper-mes oper-mes2 col-12 col-md-6 d-flex flex-column mt-4 mt-md-0">
                    <div class="caja-agreret d-flex">
                      <button class='btn-addagreret d-flex' id='btn-addagreretmas' type='button' data-bs-toggle='modal' data-bs-target='#modal-addagreretmas' >
                        <span class="oper-agreret"><i class="fa-solid fa-arrow-up"></i></span>
                        <div class="d-flex flex-column">
                          <span class="oper-titulo">Retiro Master</span>
                        </div>
                      </button>
                    </div>
                    <table class="wp-list-table widefat tab-ui striped tab-agreretmas">
                      <thead>
                        <tr>
                          <th class="manage_column" >Fecha</th>
                          <th class="manage_column" >Cantidad</th>
                          <th class="manage_column" >Status</th>
                        </tr>
                      </thead>

                      <tbody>

                      </tbody>
                    </table>
                  </div>
                </div>

                <!-- Boton para crear registros -->
                <div class="d-grid gap-2 d-md-flex justify-content-center justify-content-md-between pt-5 pb-3 pb-md-1">
                  <div class="">

                  </div>
                  <div class="">
                    <?php if ($hayagreusers) { ?>
                      <button class="btn-addregagr" id="btn-addregagr" type="button" data-bs-toggle="modal" data-bs-target="#modal-addregagr"><i class="fa-solid fa-file"></i>Nuevo registro</button>
                    <?php } else { ?>
                      <button class="btn-addregagr" id="btn-noaddregagr" type="button" ><i class="fa-solid fa-file"></i>Nuevo registro</button>
                    <?php } ?>
                  </div>
                </div>

                <div class="box-transparent">
                  <h3>Historial de registros:</h3>
                  <br>
                  <table class="wp-list-table widefat tab-ui dt-responsive striped tab-admagrconmaster">
                    <thead>
                      <tr>
                        <th class="manage_column" >#</th>
                        <th class="manage_column" >Año - Mes</th>
                        <th class="manage_column" >Depósitos</th>
                        <th class="manage_column" >Cap Inicial</th>
                        <th class="manage_column" >Util Inicial</th>
                        <th class="manage_column" >Com Broker</th>
                        <th class="manage_column" >Util Real</th>
                        <th class="manage_column" >Investors</th>
                        <th class="manage_column" >The Inc</th>
                        <th class="manage_column" >GoPro</th>
                        <th class="manage_column" >% Util Real Master</th>
                        <th class="manage_column" >% Util Inverstors</th>
                        <th class="manage_column" >Retiros</th>
                        <th class="manage_column" >Total Cierre Mes</th>
                        <th class="manage_column" >Notas</th>
                        <th class="manage_column" >Fecha del registro</th>
                      </tr>
                    </thead>

                    <tbody>

                    </tbody>
                  </table>
                  <br>
                  <hr>
                  <p>Notas: <br>
                    <ol>
                      <li>Los datos de las cajas superiores hacen referencia a los datos almacenados en el ultimo mes capturado.</li>
                    </ol>
                  </p>
                </div>

                <div class="modal fade modal-ui" id="modal-addagredepmas" tabindex="-1" aria-labelledby="modal-addagredepmasLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-addagredepmasLabel">Solicitar depósito master</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-addagredepmas" class="form-addagredepmas" action="#" method="post">
                          <input type="hidden" id="userdep" name="userdep" value="<?php echo $user ?>">
                          <input type="hidden" id="dmagr_solicitante" name="dmagr_solicitante" value="<?php echo ( $subadministrador == true ? '1' : '0'); ?>">
                          <div class="campo">
                            <label for="dmagr_idinddepmas">Id Depósito a TD: </label>
                            <input id="dmagr_idinddepmas" type="text" name="dmagr_idinddepmas" value="<?php echo ( $subadministrador == true ? 'xxxxxx' : ''); ?>" required <?php echo ( $subadministrador == true ? 'disabled' : ''); ?>>
                          </div>
                          <div class="campo">
                            <label for="dmagr_idgraldepmas">Id Depósito a Master: </label>
                            <input id="dmagr_idgraldepmas" type="text" name="dmagr_idgraldepmas" value="<?php echo ( $subadministrador == true ? 'xxxxxx' : ''); ?>" required <?php echo ( $subadministrador == true ? 'disabled' : ''); ?>>
                          </div>
                          <div class="campo">
                            <label for="dmagr_fechadepmas">Fecha: </label>
                            <input type="date" name="dmagr_fechadepmas" id="dmagr_fechadepmas" value="<?php echo date('Y-m-d'); ?>" required>
                          </div>
                          <div class="campo">
                            <label for="dmagr_fechafindepmas">Fecha autorización: </label>
                            <input type="date" name="dmagr_fechafindepmas" id="dmagr_fechafindepmas" value="<?php echo ( $subadministrador == true ? '' : date('Y-m-d') ); ?>" required <?php echo ( $subadministrador == true ? 'disabled' : ''); ?> >
                          </div>
                          <div class="campo">
                            <label for="dmagr_cantidaddepmas">Cantidad a depositar: </label>
                            <input id="dmagr_cantidaddepmas" type="text" name="dmagr_cantidaddepmas" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="dmagr_cantidadfindepmas">Cantidad final: </label>
                            <input id="dmagr_cantidadfindepmas" type="text" name="dmagr_cantidadfindepmas" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required <?php echo ( $subadministrador == true ? 'disabled' : ''); ?>>
                          </div>
                          <div class="campo">
                            <label for="dmagr_notasdepmas">Notas: </label>
                            <textarea name="dmagr_notasdepmas" id="dmagr_notasdepmas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="agregaragredepmas" type="submit" name="agregaragredepmas" class="button button-primary" value="Solicitar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-addagreretmas" tabindex="-1" aria-labelledby="modal-addagreretmasLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-addagreretmasLabel">Solicitar retiro master</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-addagreretmas" class="form-addagreretmas" action="#" method="post">
                          <input type="hidden" id="userret" name="userret" value="<?php echo $user ?>">
                          <input type="hidden" id="rmagr_solicitante" name="rmagr_solicitante" value="<?php echo ( $subadministrador == true ? '1' : '0'); ?>">
                          <input type="hidden" id="totaldisp" name="totaldisp" value="100000">
                          <div class="campo">
                            <label for="rmagr_idindretmas">Id Retiro a TD: </label>
                            <input id="rmagr_idindretmas" type="text" name="rmagr_idindretmas" value="<?php echo ( $subadministrador == true ? 'xxxxxx' : ''); ?>" required <?php echo ( $subadministrador == true ? 'disabled' : ''); ?>>
                          </div>
                          <div class="campo">
                            <label for="rmagr_fecharetmas">Fecha: </label>
                            <input type="date" name="ragr_fecharetmas" id="rmagr_fecharetmas" value="<?php echo date('Y-m-d'); ?>" required>
                          </div>
                          <div class="campo">
                            <label for="rmagr_fechafinretmas">Fecha autorización: </label>
                            <input type="date" name="rmagr_fechafinretmas" id="rmagr_fechafinretmas" value="<?php echo ( $subadministrador == true ? '' : date('Y-m-d') ); ?>" required <?php echo ( $subadministrador == true ? 'disabled' : ''); ?> >
                          </div>
                          <div class="campo">
                            <label for="rmagr_cantidadfinretmas">Cantidad final: </label>
                            <input id="rmagr_cantidadfinretmas" type="text" name="rmagr_cantidadfinretmas" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="rmagr_notasretmas">Notas: </label>
                            <textarea name="rmagr_notasretmas" id="rmagr_notasretmas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="agregaragreretmas" type="submit" name="agregaragreretmas" class="button button-primary" value="Solicitar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-addregagr" tabindex="-1" aria-labelledby="modal-addregagrLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-addregagrLabel">Agregar registro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-addregagr" class="form-addregagr" action="#" method="post">
                          <div class="campo">
                            <label for="reagr_mes">Mes: </label>
                            <input id="reagr_mes" type="number" name="rbl_mes" value="<?php echo $month; ?>" min="1" max="12"  required>
                          </div>
                          <div class="campo">
                            <label for="reagr_year">Año: </label>
                            <input id="reagr_year" type="text" name="rbl_year" value="<?php echo $year; ?>" data-inputmask="'mask':'9{4}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="reagr_util_mes">*Utilidad mensual: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="reagr_util_mes" class="form-control" type="text" name="reagr_util_mes" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="reagr_com_bro">*Commision Broker: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="reagr_com_bro" class="form-control" type="text" name="reagr_com_bro" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="reagr_por_inver">* % Util Inversionistas: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="reagr_por_inver" class="form-control" type="text" name="reagr_por_inver" value="0.00" data-inputmask="'mask':'9{1,2}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="reagr_por_refer">* % Util Referidos: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="reagr_por_refer" class="form-control" type="text" name="reagr_por_refer" value="0.00" data-inputmask="'mask':'9{0,1}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="reagr_fecha_control">*Fecha referencia: </label>
                            <input type="date" name="reagr_fecha_control" id="reagr_fecha_control" value="<?php echo date('Y-m-d'); ?>" required>
                          </div>
                          <div class="campo campo-notas">
                            <label for="reagr_notas">Notas: </label>
                            <textarea name="reagr_notas" id="reagr_notas" rows="5" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="agregarregagr" type="submit" name="agregarregagr" class="button button-primary" value="Agregar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>


              </div><!-- ui-dashboard -->
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_consadmindashboard(){
    $year = (int) date("Y");
    $month = (int) date("m");
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    // $modagresivo = get_user_meta( $user, 'modagresivo', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "conservador";
    $submenu = "consadmincontrol";
    $subadministrador = false;

    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        if ( in_array( 'subadministrador', $user->roles ) ) {
          $subadministrador = true;
      }
    }

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }

    // BUSCAMOS A TODOS LOS USUARIOS QUE PARTICIPAN EN AGRESIVO:

    global $wpdb;
    $tabladep = $wpdb->prefix.'depositos_con';

    // Saber si hay usuarios conservador
    $userconservador = get_users(array(
    'meta_key' => 'modconservador',
    'meta_value' => 1
    ));

    $listausers = array();

    if (count($userconservador) ==  0) {

    }else {
      foreach ($userconservador as $key => $value) {
        $valid = $value->ID;
        $registros = $wpdb->get_results(" SELECT * FROM $tabladep WHERE dcon_usuario = $valid AND dcon_status = 2 ", ARRAY_A);

        if (count($registros) != 0) {
          $listausers[] = $value->ID;
        }

      }
    }

    if (count($listausers) == 0 ) {
      $hayconsusers = false ;
    }else {
      $hayconsusers = true ;
    }
    // $listausers_json = json_encode($listausers);

    // echo "<pre>";
    // var_dump($hayagreusers);
    // echo "</pre>";

    // $calculos = new CRC_AgreCalculo();
    // $detallecajasadmin = $calculos->crc_datoscajasuperiores_admin();

    // echo "<pre>";
    // var_dump($detallecajasadmin);
    // echo "</pre>";
    //
    // $totalcuenta = $detallecajasadmin[0]["totalcuenta"];
    // $ttotalcuenta = number_format($totalcuenta,2);
    // $totalinvestors = $detallecajasadmin[0]["totalinvestors"];
    // $ttotalinvestors = number_format($totalinvestors ,2);
    // $totaltheinc = $detallecajasadmin[0]["totaltheinc"];
    // $ttotaltheinc = number_format($totaltheinc ,2);
    // $totalgopro = $detallecajasadmin[0]["totalgopro"];
    // $ttotalgopro = number_format($totalgopro  ,2);

    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-admin.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Control Maestro</h1>
              </div>

              <div class="ui-dashboard">
                <!-- cajas superiores -->
                <div class="container-fluid px-0 my-4 pt-5 pb-4">
                  <div class="row">
                    <div class="col-12 col-md-3 ">
                      <div class="rd-cajasup rd-cajasup-azul">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-flag"></i></span>
                        <h4>Total cuenta</h4>
                        <span class="rd-cajasup-cifra">$</span>
                      </div>
                    </div>
                    <div class="col-12 col-md-3 ">
                      <div class="rd-cajasup rd-cajasup-verde">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-user-group"></i></span>
                        <h4>Capital Inversionistas</h4>
                        <span class="rd-cajasup-cifra">$</span>
                      </div>
                    </div>
                    <div class="col-12 col-md-3 ">
                      <div class="rd-cajasup rd-cajasup-gris">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-file-circle-check"></i></span>
                        <h4>Total The Inc</h4>
                        <span class="rd-cajasup-cifra">$</span>
                      </div>
                    </div>
                    <div class="col-12 col-md-3 ">
                      <div class="rd-cajasup rd-cajasup-naranja">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-chart-line"></i></span>
                        <h4>Total GoPro</h4>
                        <span class="rd-cajasup-cifra">$</span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Botones de deposito y retiro -->
                <div class="oper-master row mt-4 pt-3">
                  <div class="oper-mes oper-mes1 col-12 col-md-6 d-flex flex-column ">
                    <div class="caja-consdep d-flex ">
                      <button class='btn-addconsdep d-flex' id='btn-addconsdepmas' type='button' data-bs-toggle='modal' data-bs-target='#modal-addconsdepmas' >
                        <span class="oper-consdep"><i class="fa-solid fa-arrow-down"></i></span>
                        <div class="d-flex flex-column">
                          <span class="oper-titulo">Depósito</span>
                        </div>
                      </button>
                    </div>
                    <table class="wp-list-table widefat tab-ui striped tab-consdep" data-user="<?php echo $user; ?>">
                      <thead>
                        <tr>
                          <th class="manage_column" >Fecha</th>
                          <th class="manage_column" >Cantidad</th>
                          <th class="manage_column" >Status</th>
                        </tr>
                      </thead>

                      <tbody>

                      </tbody>
                    </table>
                  </div>
                  <div class="oper-mes oper-mes2 col-12 col-md-6 d-flex flex-column mt-4 mt-md-0">
                    <div class="caja-consret d-flex">
                      <button class='btn-addconsret d-flex' id='btn-addconsretmas' type='button' data-bs-toggle='modal' data-bs-target='#modal-addconsretmas' >
                        <span class="oper-consret"><i class="fa-solid fa-arrow-up"></i></span>
                        <div class="d-flex flex-column">
                          <span class="oper-titulo">Retiro</span>
                        </div>
                      </button>
                    </div>
                    <table class="wp-list-table widefat tab-ui striped tab-consret" data-user="<?php echo $user; ?>">
                      <thead>
                        <tr>
                          <th class="manage_column" >Fecha</th>
                          <th class="manage_column" >Cantidad</th>
                          <th class="manage_column" >Status</th>
                        </tr>
                      </thead>

                      <tbody>

                      </tbody>
                    </table>
                  </div>
                </div>

                <div class="box-transparent">
                  <!-- <h3>Historial de registros:</h3>
                  <br>
                  <table class="wp-list-table widefat tab-ui dt-responsive striped tab-admconconmaster">
                    <thead>
                      <tr>
                        <th class="manage_column" >#</th>
                        <th class="manage_column" >Año - Mes</th>
                        <th class="manage_column" >Depósitos</th>
                        <th class="manage_column" >Cap Inicial</th>
                        <th class="manage_column" >Util Inicial</th>
                        <th class="manage_column" >Com Broker</th>
                        <th class="manage_column" >Util Real</th>
                        <th class="manage_column" >Investors</th>
                        <th class="manage_column" >The Inc</th>
                        <th class="manage_column" >GoPro</th>
                        <th class="manage_column" >% Util Real Master</th>
                        <th class="manage_column" >% Util Inverstors</th>
                        <th class="manage_column" >Retiros</th>
                        <th class="manage_column" >Total Cierre Mes</th>
                        <th class="manage_column" >Notas</th>
                        <th class="manage_column" >Fecha del registro</th>
                      </tr>
                    </thead>

                    <tbody>

                    </tbody>
                  </table>
                  <br>
                  <hr> -->
                  <p>Notas: <br>
                    <ol>
                      <li>Los datos de las cajas superiores hacen referencia a los datos almacenados en el ultimo mes capturado.</li>
                    </ol>
                  </p>
                </div>

                <div class="modal fade modal-ui" id="modal-addconsdepmas" tabindex="-1" aria-labelledby="modal-addconsdepmasLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-addconsdepmasLabel">Solicitar depósito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-addconsdepmas" class="form-addconsdepmas" action="#" method="post">
                          <input type="hidden" id="userdep" name="userdep" value="<?php echo $user ?>">
                          <input type="hidden" id="dcon_solicitante" name="dmcon_solicitante" value="<?php echo ( $subadministrador == true ? '1' : '0'); ?>">
                          <div class="campo">
                            <label for="dmcon_idinddepmas">Id Depósito a TD: </label>
                            <input id="dmcon_idinddepmas" type="text" name="dmcon_idinddepmas" value="<?php echo ( $subadministrador == true ? 'xxxxxx' : ''); ?>" required <?php echo ( $subadministrador == true ? 'disabled' : ''); ?>>
                          </div>
                          <div class="campo">
                            <label for="dmcon_idgraldepmas">Id Depósito a Master: </label>
                            <input id="dmcon_idgraldepmas" type="text" name="dmcon_idgraldepmas" value="<?php echo ( $subadministrador == true ? 'xxxxxx' : ''); ?>" required <?php echo ( $subadministrador == true ? 'disabled' : ''); ?>>
                          </div>
                          <div class="campo">
                            <label for="dmcon_fechadepmas">Fecha: </label>
                            <input type="date" name="dmcon_fechadepmas" id="dmcon_fechadepmas" value="<?php echo date('Y-m-d'); ?>" required>
                          </div>
                          <div class="campo">
                            <label for="dmcon_fechafindepmas">Fecha autorización: </label>
                            <input type="date" name="dmcon_fechafindepmas" id="dmcon_fechafindepmas" value="<?php echo ( $subadministrador == true ? '' : date('Y-m-d') ); ?>" required <?php echo ( $subadministrador == true ? 'disabled' : ''); ?> >
                          </div>
                          <div class="campo">
                            <label for="dmcon_cantidaddepmas">Cantidad a depositar: </label>
                            <input id="dmcon_cantidaddepmas" type="text" name="dmcon_cantidaddepmas" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="dmcon_cantidadfindepmas">Cantidad final: </label>
                            <input id="dmcon_cantidadfindepmas" type="text" name="dmcon_cantidadfindepmas" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required <?php echo ( $subadministrador == true ? 'disabled' : ''); ?>>
                          </div>
                          <div class="campo">
                            <label for="dmcon_notasdepmas">Notas: </label>
                            <textarea name="dmcon_notasdepmas" id="dmcon_notasdepmas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="agregarconsdepmas" type="submit" name="agregarconsdepmas" class="button button-primary" value="Solicitar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-addconsretmas" tabindex="-1" aria-labelledby="modal-addconsretmasLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-addconsretmasLabel">Solicitar retiro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-addconsretmas" class="form-addconsretmas" action="#" method="post">
                          <input type="hidden" id="userret" name="userret" value="<?php echo $user ?>">
                          <input type="hidden" id="rmcon_solicitante" name="rmcon_solicitante" value="<?php echo ( $subadministrador == true ? '1' : '0'); ?>">
                          <input type="hidden" id="totaldisp" name="totaldisp" value="100000">
                          <div class="campo">
                            <label for="rmcon_idindretmas">Id Retiro a TD: </label>
                            <input id="rmcon_idindretmas" type="text" name="rmcon_idindretmas" value="<?php echo ( $subadministrador == true ? 'xxxxxx' : ''); ?>" required <?php echo ( $subadministrador == true ? 'disabled' : ''); ?>>
                          </div>
                          <div class="campo">
                            <label for="rmcon_fecharetmas">Fecha: </label>
                            <input type="date" name="rmcon_fecharetmas" id="rmcon_fecharetmas" value="<?php echo date('Y-m-d'); ?>" required>
                          </div>
                          <div class="campo">
                            <label for="rmcon_fechafinretmas">Fecha autorización: </label>
                            <input type="date" name="rmcon_fechafinretmas" id="rmcon_fechafinretmas" value="<?php echo ( $subadministrador == true ? '' : date('Y-m-d') ); ?>" required <?php echo ( $subadministrador == true ? 'disabled' : ''); ?> >
                          </div>
                          <div class="campo">
                            <label for="rmcon_cantidadfinretmas">Cantidad final: </label>
                            <input id="rmcon_cantidadfinretmas" type="text" name="rmcon_cantidadfinretmas" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="rmcon_notasretmas">Notas: </label>
                            <textarea name="rmcon_notasretmas" id="rmcon_notasretmas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="agregarconsretmas" type="submit" name="consgaragreretmas" class="button button-primary" value="Solicitar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>


              </div><!-- ui-dashboard -->
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function agregar_menuadmininversiones(){
    add_menu_page(
      "Control de Inversiones",
      "Control de Inversiones",
      "administrator",
      "crc_admin_inversiones",
      [ $this, "interfaz_admindashboard" ],
      "dashicons-chart-area",
      15
    );

    add_menu_page(
      "Dashboard Principal",
      "TD Referral Commissions",
      "administrator",
      "crc_referral_principal",
      [ $this, "interfaz_referral_principal" ],
      "dashicons-chart-area",
      16
    );

    add_menu_page(
      "Dashboard Principal",
      "NFT Projects",
      "administrator",
      "crc_referral_nftprojects",
      [ $this, "interfaz_referral_nftprojects" ],
      "dashicons-chart-area",
      17
    );

    add_menu_page(
      "Dashboard Principal",
      "Ingresos varios",
      "administrator",
      "crc_referral_ingresosvar",
      [ $this, "interfaz_referral_ingresosvar" ],
      "dashicons-chart-area",
      18
    );

    add_menu_page(
      "Dashboard Principal",
      "Agresivo",
      "administrator",
      "crc_agresivo_control",
      [ $this, "interfaz_agreadmindashboard" ],
      "dashicons-chart-area",
      19
    );

    add_menu_page(
      "Dashboard Principal",
      "Conservador",
      "administrator",
      "crc_conservador_control",
      [ $this, "interfaz_consadmindashboard" ],
      "dashicons-chart-area",
      19
    );

    $user = wp_get_current_user();
    if ( $user->ID == 16 ) {
      remove_menu_page('edit.php');
      remove_menu_page('upload.php');
      remove_menu_page('edit.php?post_type=page');
      remove_menu_page('edit-comments.php');
      remove_menu_page('themes.php');
      remove_menu_page('plugins.php');
      remove_menu_page('index.php');
      remove_menu_page('options-general.php');
      remove_menu_page('tools.php');
      remove_menu_page('ai1wm_export');
    }

  }

  public function interfaz_adminuserdashboard(){
    $user_actual = (int) $_GET['id'];
    $user_data = get_userdata( absint( $user_actual ) );
    $unombre = $user_data->first_name ." ". $user_data->last_name;
    $invitecode = get_user_meta($user_actual,'invitecode',true);
    $status = get_user_meta($user_actual,'status',true);
    $interes = ((int) $status / 100);
    $noncedep = wp_create_nonce( 'mi_nonce_dep' );
    $nonceret = wp_create_nonce( 'mi_nonce_ret' );

    global $wpdb;
    $tabla = $wpdb->prefix . 'depositos';
    $tabla2 = $wpdb->prefix . 'retiros';
    $tabla3 = $wpdb->prefix . 'mesesinv';
    $depositos = $wpdb->get_results("SELECT month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidad_real, fecha_cuando, fecha_termino FROM $tabla WHERE usuario = $user_actual AND status = 2 ORDER BY fecha_termino", ARRAY_A);
    $totaldep = $wpdb->get_results("SELECT ROUND(SUM(cantidad_real), 2) AS totaldep FROM $tabla WHERE usuario = $user_actual AND status = 2 ", ARRAY_A);
    $retiros = $wpdb->get_results("SELECT day(fecha_termino) AS dia, month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidadfin, urgente, fecha_cuando FROM $tabla2 WHERE usuario = $user_actual AND status = 2 ORDER BY fecha_termino", ARRAY_A);
    $mesesinv = $wpdb->get_results("SELECT mes, interes FROM $tabla3 WHERE usuario = $user_actual AND status = 1 ORDER BY id", ARRAY_A);
    $totalmeses = count($mesesinv);
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
      $mesesquedan = $totalmeses - $totalMonths;

      for ($i = 0 ; $i < $totalMonths; $i++) {
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
                  if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
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
          $i = $totalMonths;
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

    // echo $meshoy;
    // echo "<br>";
    // echo $i;
    // echo "<br>";
    ?>
    <div class="wrap">
      <div class="col-titulo">
        <h1>Dashboard Principal - <?php echo $unombre ?></h1>
        <img class="logo-theinc" src="" alt="logo_theinc">
      </div>
      <hr>
      <div class="cuerpo-dashboard">
        <div class="col-dashboard col-status">
          <div class="caja-invitecode">
            Tu código de Invitación: <span><?php echo $invitecode; ?></span>
          </div>
          <div class="caja-interes">
            Inter&eacute;s: <span><?php echo (($status != '') ?  $status : "0" ) ?></span>%
          </div>
        </div>
        <?php add_thickbox(); ?>
        <?php if($status != ''){
          $calculos = new CRC_Calculo();
          $utilidadref = $calculos->crc_utilidad_referidos($user_actual);
          $tutilidadref = number_format($utilidadref, 2, '.', ',');

          // echo "<pre>";
          // echo var_dump($mesesquedan);
          // echo "</pre>";
          // $utilidadref = 0;
          // $tutilidadref = number_format($utilidadref, 2, '.', ',');
          // echo "<pre>";
          // echo var_dump($utilidadref);
          // echo "</pre>";
          ?>
        <div class="col-dashboard col-cajitas">
          <div class="caja caja-inicial">
            <span class='material-icons inicial'>flag</span>
            <div class="info-box-content">
              <span class="info-box-text">Capital principal</span>
              <span class="info-box-number">$<?php echo $ttotaldep ?></span>
            </div>
          </div>
          <div class="caja caja-utilidad">
            <span class='material-icons utilidad'>insights</span>
            <div class="info-box-content">
              <span class="info-box-text ">Utilidad generada</span>
              <span class="info-box-number">$<?php echo $tintacu ?></span>
            </div>
          </div>
          <div class="caja caja-referido">
            <span class='material-icons referidos'>people</span>
            <div class="info-box-content">
              <span class="info-box-text">Utilidad referidos</span>
              <span class="info-box-number">$<?php echo $tutilidadref ?></span>
            </div>
          </div>
        </div>
        <div class="col-dashboard col-botones">
          <input alt="#TB_inline?width=400&inlineId=modal-deposito" title="Solicitar un dep&oacute;sito" class="thickbox button button-primary button-large button-depositar" type="button" value="Depósito" />

          <div id="modal-deposito" style="display:none;" >
            <form id="form-solicitadep" class="" action="" method="post">
              <input type="hidden" id="userdep" name="userdep" value="<?php echo $user_actual ?>">
              <input type="hidden" id="userint" name="userint" value="<?php echo $status ?>">
              <input type="hidden" name="nonce" value="<?php echo $noncedep ?>">
              <div class="campo">
                <label for="fechadep">Fecha del depósito: </label>
                <select id="fechadep" name="fechadep">
                  <option value="" selected>Seleccione una fecha</option>
                  <option value="1">Día 1 del mes</option>
                  <option value="15">Día 15 del mes</option>
                </select>
              </div>
              <div class="campo">
                <label for="cantidaddep"><i class="fa fa-user"></i>Cantidad a depositar: </label>
                <input id="cantidaddep" type="text" name="cantidad" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
              </div>
              <div class="campo-especial">
                <input id="registrardep" type="submit" name="depositar" class="button button-primary" value="Depositar">
                <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
              </div>
            </form>
          </div>

          <input alt="#TB_inline?width=400&inlineId=modal-retiro" title="Solicitar un retiro" class="thickbox button button-primary button-large button-retirar" type="button" value="Retiro" />

          <div id="modal-retiro" style="display:none;" >
            <form id="form-solicitaret" class="" action="" method="post">
              <input type="hidden" id="userret" name="userret" value="<?php echo $user_actual ?>">
              <input type="hidden" name="nonce" value="<?php echo $nonceret ?>">
              <input type="hidden" id="totaldisp" name="totaldisp" value="<?php echo $totaldisp ?>">
              <div class="campo">
                <label for="urgenteret">¿Es un retiro urgente?</label>
                <input type="checkbox" id="urgenteret" name="urgenteret" value="urgente">
              </div>
              <div class="campo">
                <label for="fecharet">Fecha del retiro: </label>
                <select id="fecharet" name="fecharet">
                  <option value="" selected>Seleccione una fecha</option>
                  <option value="15">Día 15 del mes</option>
                  <option value="30">Día último del mes</option>
                </select>
              </div>
              <div class="campo">
                <label for="cantidadret"><i class="fa fa-user"></i>Cantidad a retirar: </label>
                <input id="cantidadret" type="text" name="cantidad" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
              </div>
              <div class="campo-especial">
                <input id="registrarret" type="submit" name="registrar" class="button button-primary" value="Solicitar">
                <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
              </div>
            </form>
          </div>
        </div>
      <?php } ?>

      <h3>Proyeccion del inversionista</h3>
      <br>
      <br>
      <?php  if($status != ''){  ?>
      <?php
      /*echo "<pre>";
      echo var_dump($depositos);
      echo "</pre>";*/
      ?>
      <table class="wp-list-table widefat tab-dark striped tab-proyecinv tab-adminproyecinv">
        <thead>
          <tr>
            <th class="manage_column" >O</th>
            <th class="manage_column" >#</th>
            <th class="manage_column" >Año - Mes</th>
            <th class="manage_column" >Depósitos</th>
            <th class="manage_column" >Cap Inicial Mes</th>
            <th class="manage_column" >Utilidad</th>
            <th class="manage_column" >Utilidad acumulada</th>
            <th class="manage_column" >Retiros</th>
            <th class="manage_column" >Total</th>
            <th class="manage_column" >Status</th>

          </tr>
        </thead>

        <tbody>
          <?php
          if (!$nohay) {

            $mesuno = $depositos[0]["mes"];
            $agnouno = (int) $depositos[0]["agno"];
            $agno = $agnouno;
            $fechaini = date($depositos[0]["agno"]."-".$depositos[0]["mes"]."-01");
            $inicio = 0;
            $intacu = 0;
            $totalacu = 0;
            $mes = (int) $mesuno;
            $contorden = 13;
            if ($totalMonths < 1 ) {
              $mesesprint = 12;
            }else{
              if($mesesquedan < 12){
                $mesesprint = $totalMonths + $mesesquedan;
              }else {
                $mesesprint = $totalMonths + 11;
              }

            }


            for ($i = 0 ; $i < $mesesprint; $i++) {
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
                        if ( $v['mes'] == $strmes &&  $v['agno'] == $agno) {
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

              if (($i+1) <= $totalMonths ) { ?>
              <tr class="mes-pasado">
              <?php }else { ?>
              <tr >
              <?php  }
              ?>
                <td><?php echo $contorden ?></td>
                <td><?php echo $i+1 ?></td>
                <td><?php echo $agno." - ".$tmes ?></td>
                <td><span class="verde btn-ver-dep" data-userid="<?php echo $user_actual ?>" data-mes="<?php echo $mes ?>" data-agno="<?php echo $agno ?>">+ $<?php echo number_format($depmes, 2, '.', ','); ?></span></td>
                <td>$<?php echo number_format($capprin, 2, '.', ','); ?></td>
                <td>$<?php echo number_format($totalintmes, 2, '.', ',');?></td>
                <td>$<?php echo number_format($intacu, 2, '.', ','); ?></td>
                <td><span class="rojo btn-ver-ret" data-userid="<?php echo $user_actual ?>" data-mes="<?php echo $mes ?>" data-agno="<?php echo $agno ?>">- $<?php echo number_format($retmes, 2, '.', ','); ?></span></td>
                <td><span class="final">$<?php echo number_format($totalacu, 2, '.', ','); ?></span></td>
                <td><?php echo $statusmes ?>%</td>
              </tr>
              <?php
              if ($mes == 12) {
                $mes = 1;
                $agno++;
              }else{
                $mes++;
              }

              if($mesesquedan < 11){
                // checamos diferencia para saber con cuanto armo 12 mesesin
                $difmeses = 12 - $mesesquedan;

                if (($i+1) == $totalMonths-$difmeses ) {
                  $contorden = 1;
                }else {
                  $contorden++;
                }
              }else{
                if (($i+1) == $totalMonths-1 ) {
                  $contorden = 1;
                }else {
                  $contorden++;
                }
              }



            }
          }


           ?>
        </tbody>
      </table>
      <?php } ?>
      <br>
      <hr>
      <p>Notas: <br>
        <ol>
          <li>La tabla muestra la proyección de los meses cerrados al día de hoy más 12 meses en el futuro. En caso de tener autorizados por el administrador menos de 12 meses a futuro, entonces solo se mostrarán los meses a futuro restantes.</li>
          <li>Los depósitos del día 15 de mes generarán unicamente el 5% de interés durante el mes en que se autorizó el depósito.</li>
          <li>Los retiros del día 15 de mes generarán unicamente el 5% de interés durante el mes en que se utorizó  el retiro.</li>
        </ol>
      </p>
      </div>
      <br>
      <div class="caja-morada mt-0">
        <?php
        $calculos = new CRC_Calculo();
        $utilidadref = $calculos->totalcuentas_utilref_individual_detalle($user_actual);

        //calculamos la diferencia de meses
        $fechahoy = date("Y-m-d");
        $fechaSeparada = explode("-", $fechahoy);
        $meshoy = (int) $fechaSeparada[1];
        $agnohoy = (int) $fechaSeparada[0];

        if ($meshoy == 1) {
          $mesint = 12;
          $agno = $agnohoy-1;
        }else{
          $mesint = $meshoy-1;
          $agno = $agnohoy;
        }

         ?>
        <h3 class="mt-3 mb-4">Lista de Usuarios Referidos activos:</h3>
        <br>
        <table class="wp-list-table widefat tab-dark striped tab-admininvrefmes">
          <thead>
            <tr>
              <th class="manage_column" >#</th>
              <th class="manage_column" >Usuario Referido</th>
              <th class="manage_column" >Utilidad Generada</th>
            </tr>
          </thead>

          <tbody>
            <?php
            if ( count($utilidadref[0]["totalxuserrefxmes"]) == 0) {              // code...
            } else {
              $numref = 1;
              //Si tiene invitados entonces recorremos el array de invitados
              foreach ($utilidadref[0]["totalxuserrefxmes"] as $code => $invitado) {

                // Revisamos si los invitados tienen alguna generacion de utilidades para el user
                if (count($invitado["utilidadxmes"]) == 0) {

                }else {
                  // Si la tienen recorremos meses y checamos que sea del mismo mes para sumarla
                  foreach ($invitado["utilidadxmes"] as $chiave => $mesdelref) {

                    if ($mesdelref["mes"] == $mesint && $mesdelref["year"] == $agno) {
                      $totalref = number_format($mesdelref["total"], 2, '.', ',');
                      ?>
                      <tr>
                        <td><?php echo $numref ; ?></td>
                        <td><?php echo $invitado["nombre"]; ?></td>
                        <td><?php echo "$".$totalref; ?></td>
                      </tr>
                      <?php
                      $numref++;
                    }

                  }
                }

              }
            } ?>
          </tbody>
        </table>
        <br>
        <hr>
        <p>Notas: <br>
          <ol>
            <li>La utilidad de referido corresponde a la utilidad acumulada que el usuario le ha generado como referido hasta el cierre del ultimo mes finalizado anterior.</li>
            <li>Solo se muestran los usuarios referidos que esten activos y tengan algun depósito validado, es decir que ya hayan empezado a generar utilidades. </li>
          </ol>
        </p>
      </div>

    </div>


    <?php
  }

  public function interfaz_admincontrolmaster(){
    date_default_timezone_set('America/Tijuana');
    $noncecmast = wp_create_nonce( 'mi_nonce_contmaster' );
    $year = date("Y");
    $month = date("m");
    global $wpdb;
    $usuarios = $wpdb->prefix . 'users';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $usuarios ORDER BY id DESC", ARRAY_A);
    $balances = $wpdb->prefix . 'controlmaster';
    $ruta = get_site_url();
    $balregistros = $wpdb->get_results("SELECT * FROM $balances WHERE status = 1 ORDER BY agno, mes ", ARRAY_A);

    $totinvlm = 0;
    $totinvhoy = 0;
    $totutilref = 0;

    $utilidadporuser = array();
    $aportacionporuser = array();

    $totalinvestors = 0;
    $totalprofit = 0;
    $futurdepos = 0;

    $return_json = array();

    if(count($balregistros) == 0){
      $ultbalfinal = 0;
    }else{
      $ultimobal = end($balregistros);
      $ultbalfinal = (float)$ultimobal['balance_final'];
      $totalinvestors = (float)$ultimobal['total_cuentas'];
      $totalprofit = $ultbalfinal - $totalinvestors;
    }

    if(count($registros) == 0){

    } else{
      $calculos = new CRC_Calculo();
      $utilidadinv = $calculos->totalcuentas_mensual_hoy();
      $utilidadref = $calculos->totalcuentas_utilref_cierre();
      // Saber en que año y mes estamos
      $year = (int) date("Y");
      $month = (int) date("m");

      if ($utilidadinv == NULL) {
        $utilidadinv = array();
      }

      if(count($utilidadinv) == 0){
      } else{
        foreach ($utilidadinv as $llave => $valor) {
          if($valor['mes'] == $month && $valor['year'] == $year){
            $totinvhoy = (float) $valor['total'];
          }
        }

        if(count($utilidadref) == 0){
        } else{
          foreach ($utilidadref as $llave => $valor) {
            if($valor['mes'] == $month && $valor['year'] == $year){
              $totutilref = (float) $valor['total'];
            }
          }
        }

        global $wpdb;
        $tabdep = $wpdb->prefix . 'depositos';

        // $month = 9;

        if($month == 12){
          $nmonth = 1;
          $nyear = $year+1;
        }else{
          $nmonth = $month+1;
          $nyear = $year;
        }

        $fechanextm = $nyear.'-'.$nmonth.'-01';
        // $ruta = get_site_url();
        $proxdepos = $wpdb->get_results(" SELECT ROUND(SUM(cantidad_real), 2) AS futurdepos FROM $tabdep WHERE fecha_termino >= '".$fechanextm."'AND status = 2 ORDER BY id ASC", ARRAY_A);

        $futurdepos = (float) $proxdepos[0]['futurdepos'];
        $totinvhoy = $totinvhoy + $futurdepos;
      }
      // $utilidadref = $calculos->totalcuentas_utilref_cierre();

      // global $wpdb;
      // $reghistorico = $wpdb->prefix . 'registro_historico';
      // $ruta = get_site_url();
      // $registros = $wpdb->get_results(" SELECT * FROM $reghistorico ORDER BY id ASC", ARRAY_A);

      // echo "<pre>";
      // var_dump($totinvhoy);
      // var_dump($fechanextm);
      // var_dump($proxdepos);
      // echo "</pre>";
    }



    // $ttotinvlm = number_format($totinvlm, 2, '.', ',');
    $ttotinvhoy = number_format($totinvhoy, 2, '.', ',');
    $tultbalfinal = number_format($ultbalfinal, 2, '.', ',');//ultimo balance final
    $ttotalinvestors = number_format($totalinvestors, 2, '.', ',');//total cuentas del ultimo balance
    $ttotalprofit = number_format($totalprofit, 2, '.', ',');//total profit
    $tutilidadref = number_format($totutilref, 2, '.', ',');//total de utilidad referidos


    // echo "<pre>";
    // // echo var_dump($utilidadporuser);
    // echo var_dump($aportacionporuser);
    // echo var_dump($totinvhoy);
    // echo var_dump($totutilref);
    // echo var_dump((float)number_format($totinvhoy + $totutilref, 2, '.', ''));
    // echo "</pre>";
    /*
    echo $ttotinvlm;
    echo "<br>";
    echo $ttotinvhoy;
    echo "<br>";
    */
    ?>
    <div class="wrap">
      <div class="col-titulo">
        <h1>Control Maestro</h1>
        <img class="logo-theinc" src="" alt="logo_theinc">
      </div>
      <hr>
      <?php add_thickbox();

      $tabla = $wpdb->prefix.'depositos_master';
      $tabla1 = $wpdb->prefix.'retiros_master';
      // $imes = (int)$mes;
      // $iagno = (int)$agno;
      $totaldep = $wpdb->get_results("SELECT month(fecha_deposito) AS mes, year(fecha_deposito) AS agno, ROUND(SUM(cantidad_real), 2) AS totaldep FROM $tabla WHERE month(fecha_deposito) = $month AND year(fecha_deposito) = $year AND status = 1 ", ARRAY_A);
      $ttotaldep = number_format($totaldep[0]['totaldep'], 2, '.', ',');
      if(!$totaldep[0]['totaldep']){
        $itotaldep = 0.0;
      }else {
        $itotaldep = (float)$totaldep[0]['totaldep'];
      }
      // $ttdp = '<span class="verde btn-ver-depmas" data-mes='.$mes.' data-agno='.$agno.'>+ $'.$ttotaldep.'</span>';

      $totalret = $wpdb->get_results("SELECT month(fecha_retiro) AS mes, year(fecha_retiro) AS agno, ROUND(SUM(cantidad_real), 2) AS totalret FROM $tabla1 WHERE month(fecha_retiro) = $month AND year(fecha_retiro) = $year AND status = 1 ", ARRAY_A);
      $ttotalret = number_format($totalret[0]['totalret'], 2, '.', ',');
      if(!$totalret[0]['totalret']){
        $itotalret = 0.0;
      }else {
        $itotalret = (float)$totalret[0]['totalret'];
      }
      // $ttrt = '<span class="rojo btn-ver-retmas" data-mes='.$mes.' data-agno='.$agno.'>- $'.$ttotalret.'</span>';
      // $calculos = new CRC_Calculo();
      // $utilidadref = $calculos->totalcuentas_mensual_hoy_detalle();
      // echo "<pre>";
      // echo var_dump($utilidadref);
      // echo "</pre>";

       ?>
      <div class="cuerpo-dashboard">
        <div class="col-totales">
          <div class="caja-totalhoy" style="display:none;" id="totinvhoy" data-totinvhoy="<?php echo $totinvhoy + $totutilref ?>" data-ultbalfinal="<?php echo $ultbalfinal ?>" data-dephoy="<?php echo $itotaldep ?>" data-rethoy="<?php echo $itotalret ?>" >
            <span class="material-icons">show_chart</span> Total real inversionistas este mes: <span>$<?php echo $ttotinvhoy ?></span>
          </div>
          <br>
          <div class="caja-totallm" style="display:none;" id="totinvlm" data-totinvlm="<?php echo $totinvlm ?>">
            <span class="material-icons">show_chart</span> Total real inversionistas mes anterior: <span>$<?php echo $ttotinvlm ?></span>
          </div>
          <div class="col-dashboard col-cajitas">
            <div class="caja caja-inicial">
              <span class='material-icons inicial'>account_balance</span>
              <div class="info-box-content">
                <span class="info-box-text">Total cuenta maestra</span>
                <span class="info-box-number">$<?php echo $tultbalfinal ?></span>
              </div>
            </div>
            <div class="caja caja-utilidad">
              <span class='material-icons utilidad'>person</span>
              <div class="info-box-content">
                <span class="info-box-text ">Total inversionistas</span>
                <span class="info-box-number">$<?php echo $ttotalinvestors ?></span>
              </div>
            </div>
            <div class="caja caja-referido">
              <span class='material-icons referidos'>show_chart</span>
              <div class="info-box-content">
                <span class="info-box-text">Proffit Account</span>
                <span class="info-box-number">$<?php echo $ttotalprofit ?></span>
              </div>
            </div>
          </div>
          <div class="col-dashboard col-botones">
            <input alt="#TB_inline?width=400&inlineId=modal-depMaster" title="Dep&oacute;sito cuenta maestra" class="thickbox button button-primary button-large button-depositar" type="button" value="Depósito" />

            <div id="modal-depMaster" style="display:none;" >
              <form id="form-depmaster" class="" action="" method="post">
                <input type="hidden" name="nonce" value="<?php echo $noncecmast ?>">
                <div class="campo">
                  <label for="idinddepmas">Id Depósito a TD: </label>
                  <input id="idinddepmas" type="text" name="idinddepmas" required>
                </div>
                <div class="campo">
                  <label for="idgraldepmas">Id Depósito a Master: </label>
                  <input id="idgraldepmas" type="text" name="idgraldepmas" required>
                </div>
                <div class="campo">
                  <label for="fechadepmas"><i class="fa fa-user"></i>Fecha: </label>
                  <input type="date" name="fechadepmas" id="fechadepmas" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="campo">
                  <label for="cantidaddepmas"><i class="fa fa-user"></i>Cantidad a depositar: </label>
                  <input id="cantidaddepmas" type="text" name="cantidaddepmas" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                </div>
                <div class="campo">
                  <label for="cantidadfindepmas"><i class="fa fa-user"></i>Cantidad final: </label>
                  <input id="cantidadfindepmas" type="text" name="cantidadfindepmas" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                </div>
                <div class="campo">
                  <label for="notasdepmas"><i class="fa fa-user"></i>Notas: </label>
                  <textarea name="notasdepmas" id="notasdepmas" rows="5" cols="18" style="resize: none;" ></textarea>
                </div>
                <div class="campo-especial">
                  <input id="registrardepmas" type="submit" name="depositar" class="button button-primary" value="Depositar">
                  <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                </div>
              </form>
            </div>

            <input alt="#TB_inline?width=400&inlineId=modal-retMaster" title="Retiro cuenta maestra" class="thickbox button button-primary button-large button-retirar" type="button" value="Retiro" />

            <div id="modal-retMaster" style="display:none;" >
              <form id="form-retmaster" class="" action="" method="post">
                <input type="hidden" name="nonce" value="<?php echo $noncecmast ?>">
                <div class="campo">
                  <label for="idindretmas">Id Retiro a TD: </label>
                  <input id="idindretmas" type="text" name="idindretmas" required>
                </div>
                <div class="campo">
                  <label for="fecharetmas"><i class="fa fa-user"></i>Fecha: </label>
                  <input type="date" name="fecharetmas" id="fecharetmas" value="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <div class="campo">
                  <label for="cantidadfinretmas"><i class="fa fa-user"></i>Cantidad final: </label>
                  <input id="cantidadfinretmas" type="text" name="cantidadfinretmas" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                </div>
                <div class="campo">
                  <label for="notasretmas"><i class="fa fa-user"></i>Notas: </label>
                  <textarea name="notasretmas" id="notasretmas" rows="5" cols="18" style="resize: none;" ></textarea>
                </div>
                <div class="campo-especial">
                  <input id="registrarretmas" type="submit" name="registrar" class="button button-primary" value="Retirar">
                  <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-dashboard col-botones-master">

          <input alt="#TB_inline?width=400&inlineId=modal-balance" title="Nuevo Balance Mensual" class="thickbox button button-primary button-large button-newbalance" type="button" value="Nuevo Balance" />
          <div id="modal-balance" style="display:none;" >
            <form id="form-newbalance" class="" action="" method="post">
              <input type="hidden" name="nonce" value="<?php echo $noncecmast ?>">
              <div class="campo">
                <label for="mes"><i class="fa fa-user"></i>Mes: </label>
                <input id="mes" type="number" name="mes" value="<?php echo $month; ?>" min="1" max="12"  required>
              </div>
              <div class="campo">
                <label for="agno"><i class="fa fa-user"></i>Año: </label>
                <input id="agno" type="text" name="agno" value="<?php echo $year; ?>" data-inputmask="'mask':'9{4}'" data-mask required>
              </div>
              <div class="campo">
                <label for="startbal"><i class="fa fa-user"></i>Starting Balance: </label>
                <input id="startbal" type="text" name="startbal" value="0.00" data-inputmask="'mask':'9{1,20}.{0,1}9{0,2}'" data-mask required>
              </div>
              <div class="campo">
                <label for="depmes"><i class="fa fa-user"></i>Total of Deposits Current Month: </label>
                <input id="depmes" type="text" name="depmes" value="<?php echo $itotaldep; ?>" data-inputmask="'mask':'9{1,20}.{0,1}9{0,2}'" data-mask disabled readonly >
              </div>
              <div class="campo">
                <label for="retmes"><i class="fa fa-user"></i>Total of Withdraws Current Month: </label>
                <input id="retmes" type="text" name="retmes" value="<?php echo $itotalret; ?>" data-inputmask="'mask':'9{1,20}.{0,1}9{0,2}'" data-mask disabled readonly >
              </div>
              <div class="campo">
                <label for="balbefcom"><i class="fa fa-user"></i>Balance Bef. Commision: </label>
                <input id="balbefcom" type="text" name="balbefcom" value="0.00" data-inputmask="'mask':'9{1,20}.{0,1}9{0,2}'" data-mask required>
              </div>
              <div class="campo">
                <label for="comtrader"><i class="fa fa-user"></i>Commission Trader: </label>
                <input id="comtrader" type="text" name="comtrader" value="0.00" data-inputmask="'mask':'9{1,20}.{0,1}9{0,2}'" data-mask required>
              </div>
              <div class="campo">
                <label for="combroker"><i class="fa fa-user"></i>Commission Broker: </label>
                <input id="combroker" type="text" name="combroker" value="0.00" data-inputmask="'mask':'9{1,20}.{0,1}9{0,2}'" data-mask required>
              </div>
              <div class="campo">
                <label for="balfinal"><i class="fa fa-user"></i>Balance final: </label>
                <input id="balfinal" type="text" name="balfinal" value="0.00" data-inputmask="'mask':'9{1,20}.{0,1}9{0,2}'" data-mask disabled required>
              </div>
              <div class="campo">
                <label for="totalcuentas"><i class="fa fa-user"></i>Total of Investors current month (includes commisions by refers and authorized next month's deposits): </label>
                <input id="totalcuentas" type="text" name="totalcuentas" value="0.00" data-inputmask="'mask':'9{1,20}.{0,1}9{0,2}'" data-mask required>
              </div>
              <div class="campo">
                <label for="notas"><i class="fa fa-user"></i>Notas: </label>
                <textarea name="notas" id="notas" rows="5" cols="18" style="resize: none;" ></textarea>
              </div>
              <div class="campo-especial">
                <input id="registrarbal" type="submit" name="registrarbal" class="button button-primary" value="Crear">
                <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
              </div>
            </form>
          </div>

          <div id="modal-editreg" style="display:none;" >
            <form id="form-editreg" class="" action="" method="post">
              <input type="hidden" name="nonce" value="<?php echo $noncecmast ?>">
              <div class="campo">
                <label for="eryear"><i class="fa fa-user"></i>Año: </label>
                <input id="eryear" type="text" name="eryear" value="eryear" data-inputmask="'mask':'9{4}'" data-mask disabled required>
              </div>
              <div class="campo">
                <label for="ermes"><i class="fa fa-user"></i>Mes: </label>
                <input id="ermes" type="number" name="ermes" value="" min="1" max="12" disabled required>
              </div>
              <div class="campo">
                <label for="erext"><i class="fa fa-user"></i>Utilidad Externa: </label>
                <input id="erext" type="text" name="erext" value="0.00" data-inputmask="'mask':'9{1,20}.{0,1}9{0,2}'" data-mask required>
              </div>
              <div class="campo">
                <label for="ernotas"><i class="fa fa-user"></i>Notas: </label>
                <textarea name="ernotas" id="ernotas" rows="5" cols="18" style="resize: none;" ></textarea>
              </div>
              <div class="campo-especial">
                <input id="editarreg" type="submit" name="editarreg" class="button button-primary" value="Editar">
                <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
              </div>
            </form>
          </div>
          <div id="modal-editbal" style="display:none;" >
            <form id="form-editbalance" class="" action="" method="post">
              <input type="hidden" id="idebal" name="idebal" value="">
              <input type="hidden" name="nonce" value="<?php echo $noncecmast ?>">
              <div class="campo">
                <label for="emes"><i class="fa fa-user"></i>Mes: </label>
                <input id="emes" type="number" name="emes" value="<?php echo $month; ?>" min="1" max="12"  required>
              </div>
              <div class="campo">
                <label for="eagno"><i class="fa fa-user"></i>Año: </label>
                <input id="eagno" type="text" name="eagno" value="<?php echo $year; ?>" data-inputmask="'mask':'9{4}'" data-mask required>
              </div>
              <div class="campo">
                <label for="estartbal"><i class="fa fa-user"></i>Starting Balance: </label>
                <input id="estartbal" type="text" name="estartbal" value="0.00" data-inputmask="'mask':'9{1,20}.{0,1}9{0,2}'" data-mask required>
              </div>
              <div class="campo">
                <label for="edepmes"><i class="fa fa-user"></i>Total of Deposits Current Month: </label>
                <input id="edepmes" type="text" name="edepmes" value="0.00" data-inputmask="'mask':'9{1,20}.{0,1}9{0,2}'" data-mask disabled readonly >
              </div>
              <div class="campo">
                <label for="eretmes"><i class="fa fa-user"></i>Total of Withdraws Current Month: </label>
                <input id="eretmes" type="text" name="eretmes" value="0.00" data-inputmask="'mask':'9{1,20}.{0,1}9{0,2}'" data-mask disabled readonly >
              </div>
              <div class="campo">
                <label for="ebalbefcom"><i class="fa fa-user"></i>Balance Bef. Commision: </label>
                <input id="ebalbefcom" type="text" name="ebalbefcom" value="0.00" data-inputmask="'mask':'9{1,20}.{0,1}9{0,2}'" data-mask required>
              </div>
              <div class="campo">
                <label for="ecomtrader"><i class="fa fa-user"></i>Commission Trader: </label>
                <input id="ecomtrader" type="text" name="ecomtrader" value="0.00" data-inputmask="'mask':'9{1,20}.{0,1}9{0,2}'" data-mask required>
              </div>
              <div class="campo">
                <label for="ecombroker"><i class="fa fa-user"></i>Commission Broker: </label>
                <input id="ecombroker" type="text" name="ecombroker" value="0.00" data-inputmask="'mask':'9{1,20}.{0,1}9{0,2}'" data-mask required>
              </div>
              <div class="campo">
                <label for="ebalfinal"><i class="fa fa-user"></i>Balance final: </label>
                <input id="ebalfinal" type="text" name="ebalfinal" value="0.00" data-inputmask="'mask':'9{1,20}.{0,1}9{0,2}'" data-mask disabled required>
              </div>
              <div class="campo">
                <label for="etotalcuentas"><i class="fa fa-user"></i>Total of Investors this month: </label>
                <input id="etotalcuentas" type="text" name="etotalcuentas" value="0.00" data-inputmask="'mask':'9{1,20}.{0,1}9{0,2}'" data-mask required>
              </div>
              <div class="campo">
                <label for="enotas"><i class="fa fa-user"></i>Notas: </label>
                <textarea name="enotas" id="enotas" rows="5" cols="18" style="resize: none;" ></textarea>
              </div>
              <div class="campo-especial">
                <input id="editarbal" type="submit" name="editarbal" class="button button-primary" value="Editar">
                <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
              </div>
            </form>
          </div>
        </div>
        <h3>Lista de balances:</h3>
        <br>
        <table class="wp-list-table widefat tab-dark striped tab-admconmaster">
          <thead>
            <tr>
              <th class="manage_column" >#</th>
              <th class="manage_column" >Año</th>
              <th class="manage_column" >Mes</th>
              <th class="manage_column" >Start Balance</th>
              <th class="manage_column" >Depósitos</th>
              <th class="manage_column" >Retiros</th>
              <th class="manage_column" >Balance Bef Com</th>
              <th class="manage_column" >Com Broker</th>
              <th class="manage_column" >Com Trader</th>
              <th class="manage_column" >Balance Final</th>
              <th class="manage_column" >Total Inv</th>
              <th class="manage_column" >Profit Acum</th>
              <th class="manage_column" >Profit Mes</th>
              <th class="manage_column" >Acciones</th>
            </tr>
          </thead>

          <tbody>

          </tbody>
        </table>
        <br>
        <hr>
        <p>Notas: <br>
          <ol>
            <li>Los datos de las cajas superiores hacen referencia a los datos almacenados en el ultimo  balance creado.</li>
            <li>El balance final representa el resultado de la operacion: Balance Bef. Commission - Commission Trader </li>
          </ol>
        </p>
        <!-- Reporte histórico general dt-responsive-->
        <br>
        <h3>Reporte histórico general de utilidades:</h3>
        <br>
        <table class="wp-list-table widefat  tab-dark striped tab-rephistgral">
          <thead>
            <tr>
              <th class="manage_column" >#</th>
              <th class="manage_column" >Año</th>
              <th class="manage_column" >Mes</th>
              <th class="manage_column" >Subtotal Inv</th>
              <th class="manage_column" >Util Acum</th>
              <th class="manage_column" >Util Ref</th>
              <th class="manage_column" >Util Tot</th>
              <th class="manage_column" >Util Ext</th>
              <th class="manage_column" >Util Final</th>
              <th class="manage_column" >Total Inv</th>
              <th class="manage_column" >Acciones</th>
            </tr>
          </thead>

          <tbody>

          </tbody>
        </table>
        <br>
        <hr>
        <p>Notas: <br>
          <ol>
            <li>La Utilidad Final es el resultado de la diferencia de Utilidad Externa - Utilidad Total .</li>
            <li>El Subtotal Inv hace referencia al total de una cuenta de usuario inversionista hasta el cierre de cada mes, incluye ya la utilidad de ese mes generada (en caso de haber), pero no la utilidad de sus referidos. </li>
          </ol>
        </p>
        <?php
        // $calculos = new CRC_Calculo();
        // $utilidadref = $calculos->totalcuentas_mensual_hoy();
        // $utilidadref = $calculos->totalcuentas_utilref_cierre();

        // global $wpdb;
        // $reghistorico = $wpdb->prefix . 'registro_historico';
        // $ruta = get_site_url();
        // $registros = $wpdb->get_results(" SELECT * FROM $reghistorico ORDER BY id ASC", ARRAY_A);

        // echo "<pre>";
        // var_dump($utilidadref);
        // echo "</pre>";
         ?>
      </div><!-- fin del body wp -->


    </div>


    <?php
  }

  public function interfaz_admindepmaster(){
    $url = get_site_url();
    $urlc = home_url('/confirmacion');
    // $urlcompleta = $urlc . '/?code_submitted='.$codigo.'&tipo=dep';
    $user = wp_get_current_user();
    $usernombre = $user->user_firstname;
    $usermail = $user->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">
          <!-- MENU RESPONSIVE -->
          <nav class="navbar navbar-dark menu-responsive">
            <div class="container-fluid">
              <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#hamburguer-principal"
                aria-controls="hamburguer-principal" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
              </button>
              <img class="img-fluid logo-menu" src="<?php echo plugin_dir_url( __DIR__ )."assets/img/icon-inproyect.png" ?>" alt="">
            </div>
          </nav>
          <div class="collapse" id="hamburguer-principal">
            <div class="shadow-3 d-flex flex-column shadow-3 ">
              <button class="btn btn-link ham-primario btn-block border-bottom m-0 collapsed" data-bs-toggle="collapse" data-bs-target="#hamburguer1" aria-expanded="false">Control de Inversiones</button>
              <div class="collapse" id="hamburguer1" style="">
                <ul class="btn-toggle-nav list-unstyled hamburguer-sublista fw-normal pb-1 small">
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  menu-activo">Control de Depósitos</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                </ul>
              </div>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Investment Referral Com</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">NFT Projects</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Ingresos varios</button></a>
              <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Usuarios</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Wordfence Bloqueos</button></a>
              <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Salir</button></a>
            </div>
          </div>

          <div class="col-0 col-md-3 d-none d-md-block mt-3 menu-escritorio">

            <!-- MENU DESKTOP -->
            <div class="menu-redesign">
              <img src="<?php echo plugin_dir_url( __DIR__ )."assets/img/logo_theinc.png" ?>" class="menu-logo img-fluid" alt="">
              <div class="menu-info">
                <img src="<?php echo $fotourl ?>" class="menu-foto img-fluid" alt="">
                <h3 class="menu-nombre"><?php echo $usernombre; ?></h3>
                <h3 class="menu-email"><?php echo $usermail; ?></h3>
              </div>
              <p class="menu-titulo">MENU</p>
              <ul class="list-unstyled menu-lista ps-0">
                <li class="">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 boton-activo" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    Control de Inversiones
                  </button>
                  <div class="collapse show" id="home-collapse" style="">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  ">Control de Depósitos</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none menu-activo">Depósitos Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                    </ul>
                  </div>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Investment Referral Com
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    NFT Projects
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Ingresos Varios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Usuarios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Wordfence Bloqueos
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Salir
                  </button>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-9 antimenu-dashboard" >

            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Depósitos Cuenta Maestra</h1>
              </div>
              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-4">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver a Control Maestro</a>
                  </div>
                  <div class="">
                  </div>
                </div>

              </div>

              <div class="box-transparent mb-3">
                <h3 class="titulo3-redesign">Historial de depósitos</h3>
                <br>
                <br>
                <table class="wp-list-table widefat dt-responsive tab-ui striped tab-admindepmas">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Cantidad final</th>
                      <th class="manage_column" >Notas</th>
                      <th class="manage_column" >Fecha deposito</th>
                      <th class="manage_column" >Status</th>
                      <th class="manage_column" >Id Depósito a TD</th>
                      <th class="manage_column" >Id Depósito a Master</th>
                      <th class="manage_column" >Acciones</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>

                <div class="modal fade modal-ui" id="modal-edepmaster" tabindex="-1" aria-labelledby="modal-edepmasterLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-edepmasterLabel">Editar un depósito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-edepmaster" class="form-edepmaster" action="#" method="post">
                          <input type="hidden" id="idedep" name="idedep" value="">
                          <div class="campo">
                            <label for="eidinddepmas">Id Depósito a TD: </label>
                            <input id="eidinddepmas" type="text" name="eidinddepmas" required>
                          </div>
                          <div class="campo">
                            <label for="eidgraldepmas">Id Depósito a Master: </label>
                            <input id="eidgraldepmas" type="text" name="eidgraldepmas" required>
                          </div>
                          <div class="campo">
                            <label for="efechadepmas">Fecha: </label>
                            <input type="date" name="efechadepmas" id="efechadepmas" value="" required>
                          </div>
                          <div class="campo">
                            <label for="ecantidaddepmas">Cantidad a depositar: </label>
                            <input id="ecantidaddepmas" type="text" name="ecantidaddepmas" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="ecantidadfindepmas">Cantidad final: </label>
                            <input id="ecantidadfindepmas" type="text" name="ecantidadfindepmas" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="enotasdepmas">Notas: </label>
                            <textarea name="enotasdepmas" id="enotasdepmas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="editardepmas" type="submit" name="editardepmas" class="button button-primary" value="Editar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

              </div>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_adminretmaster(){
    $url = get_site_url();
    $user = wp_get_current_user();
    $usernombre = $user->user_firstname;
    $usermail = $user->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">
          <!-- MENU RESPONSIVE -->
          <nav class="navbar navbar-dark menu-responsive">
            <div class="container-fluid">
              <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#hamburguer-principal"
                aria-controls="hamburguer-principal" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
              </button>
              <img class="img-fluid logo-menu" src="<?php echo plugin_dir_url( __DIR__ )."assets/img/icon-inproyect.png" ?>" alt="">
            </div>
          </nav>
          <div class="collapse" id="hamburguer-principal">
            <div class="shadow-3 d-flex flex-column shadow-3 ">
              <button class="btn btn-link ham-primario btn-block border-bottom m-0 collapsed" data-bs-toggle="collapse" data-bs-target="#hamburguer1" aria-expanded="false">Control de Inversiones</button>
              <div class="collapse" id="hamburguer1" style="">
                <ul class="btn-toggle-nav list-unstyled hamburguer-sublista fw-normal pb-1 small">
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  menu-activo">Control de Depósitos</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                </ul>
              </div>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Investment Referral Com</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">NFT Projects</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Ingresos varios</button></a>
              <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Usuarios</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Wordfence Bloqueos</button></a>
              <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Salir</button></a>
            </div>
          </div>

          <div class="col-0 col-md-3 d-none d-md-block mt-3 menu-escritorio">

            <!-- MENU DESKTOP -->
            <div class="menu-redesign">
              <img src="<?php echo plugin_dir_url( __DIR__ )."assets/img/logo_theinc.png" ?>" class="menu-logo img-fluid" alt="">
              <div class="menu-info">
                <img src="<?php echo $fotourl ?>" class="menu-foto img-fluid" alt="">
                <h3 class="menu-nombre"><?php echo $usernombre; ?></h3>
                <h3 class="menu-email"><?php echo $usermail; ?></h3>
              </div>
              <p class="menu-titulo">MENU</p>
              <ul class="list-unstyled menu-lista ps-0">
                <li class="">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 boton-activo" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    Control de Inversiones
                  </button>
                  <div class="collapse show" id="home-collapse" style="">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  ">Control de Depósitos</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none menu-activo">Retiros Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                    </ul>
                  </div>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Investment Referral Com
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    NFT Projects
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Ingresos Varios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Usuarios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Wordfence Bloqueos
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Salir
                  </button>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Retiros Cuenta Maestra</h1>
              </div>
              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-4">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver a Control Maestro</a>
                  </div>
                  <div class="">
                  </div>
                </div>

              </div>

              <div class="box-transparent mb-3">
                <h3 class="titulo3-redesign">Historial de retiros</h3>
                <br>
                <br>
                <table class="wp-list-table widefat dt-responsive tab-ui striped tab-adminretmas">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Notas</th>
                      <th class="manage_column" >Fecha retiro</th>
                      <th class="manage_column" >Status</th>
                      <th class="manage_column" >Id Retiro a TD</th>
                      <th class="manage_column" >Acciones</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>

                  <div class="modal fade modal-ui" id="modal-eretmaster" tabindex="-1" aria-labelledby="modal-eretmasterLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title px-2" id="modal-eretmasterLabel">Editar un retiro</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-4" >
                          <form id="form-eretmaster" class="form-eretmaster" action="#" method="post">
                            <input type="hidden" id="ideret" name="ideret" value="">
                            <div class="campo">
                              <label for="eidindretmas">Id Retiro a TD: </label>
                              <input id="eidindretmas" type="text" name="eidindretmas" required>
                            </div>
                            <div class="campo">
                              <label for="efecharetmas">Fecha: </label>
                              <input type="date" name="efecharetmas" id="efecharetmas" value="" required>
                            </div>

                            <div class="campo">
                              <label for="ecantidadfinretmas">Cantidad final: </label>
                              <input id="ecantidadfinretmas" type="text" name="ecantidadfinretmas" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                            </div>
                            <div class="campo">
                              <label for="enotasretmas">Notas: </label>
                              <textarea name="enotasretmas" id="enotasretmas" rows="5" cols="18" style="resize: none;" ></textarea>
                            </div>
                            <div class="campo-especial">
                              <input id="editarretmas" type="submit" name="editarretmas" class="button button-primary" value="Editar">
                              <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                            </div>
                          </form>

                        </div>

                      </div>
                    </div>
                  </div>

              </div>

            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_adminretiros(){
    $codigo = 'A3CAX1';
    //$filar = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $tabla WHERE codigo = %s", $codigo) );
    $url = get_site_url();
    $urlc = home_url('/confirmacion');
    $urlcompleta = $urlc . '/?code_submitted='.$codigo.'&tipo=dep';
    $user = wp_get_current_user();
    $usernombre = $user->user_firstname;
    $usermail = $user->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }

    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">
          <!-- MENU RESPONSIVE -->
          <nav class="navbar navbar-dark menu-responsive">
            <div class="container-fluid">
              <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#hamburguer-principal"
                aria-controls="hamburguer-principal" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
              </button>
              <img class="img-fluid logo-menu" src="<?php echo plugin_dir_url( __DIR__ )."assets/img/icon-inproyect.png" ?>" alt="">
            </div>
          </nav>
          <div class="collapse" id="hamburguer-principal">
            <div class="shadow-3 d-flex flex-column shadow-3 ">
              <button class="btn btn-link ham-primario btn-block border-bottom m-0 collapsed" data-bs-toggle="collapse" data-bs-target="#hamburguer1" aria-expanded="false">Control de Inversiones</button>
              <div class="collapse" id="hamburguer1" style="">
                <ul class="btn-toggle-nav list-unstyled hamburguer-sublista fw-normal pb-1 small">
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  menu-activo">Control de Depósitos</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                </ul>
              </div>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Investment Referral Com</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">NFT Projects</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Ingresos varios</button></a>
              <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Usuarios</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Wordfence Bloqueos</button></a>
              <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Salir</button></a>
            </div>
          </div>

          <div class="col-0 col-md-3 d-none d-md-block mt-3 menu-escritorio">

            <!-- MENU DESKTOP -->
            <div class="menu-redesign">
              <img src="<?php echo plugin_dir_url( __DIR__ )."assets/img/logo_theinc.png" ?>" class="menu-logo img-fluid" alt="">
              <div class="menu-info">
                <img src="<?php echo $fotourl ?>" class="menu-foto img-fluid" alt="">
                <h3 class="menu-nombre"><?php echo $usernombre; ?></h3>
                <h3 class="menu-email"><?php echo $usermail; ?></h3>
              </div>
              <p class="menu-titulo">MENU</p>
              <ul class="list-unstyled menu-lista ps-0">
                <li class="">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 boton-activo" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    Control de Inversiones
                  </button>
                  <div class="collapse show" id="home-collapse" style="">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none menu-activo">Control de Retiros</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  ">Control de Depósitos</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                    </ul>
                  </div>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Investment Referral Com
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    NFT Projects
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Ingresos Varios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Usuarios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Wordfence Bloqueos
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Salir
                  </button>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-9 antimenu-dashboard" >

            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Control de Retiros</h1>
              </div>


              <div class="box-transparent mb-3">
                <br>
                <h3 class="titulo3-redesign">Historial de retiros</h3>
                <br>
                <br>
                <table class="wp-list-table widefat dt-responsive tab-ui striped tab-adminretiros">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Usuario</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Cantidad final</th>
                      <th class="manage_column" >Notas</th>
                      <th class="manage_column" >Urgente</th>
                      <th class="manage_column" >Fecha retiro</th>
                      <th class="manage_column" >Status</th>
                      <th class="manage_column" >Id Retiro a TD</th>
                      <th class="manage_column" >Fecha solicitud</th>
                      <th class="manage_column" >Fecha autorización</th>
                      <th class="manage_column" >Tipo Wallet</th>
                      <th class="manage_column" >Wallet Address</th>
                      <th class="manage_column" >Acciones</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>

                <div class="modal fade modal-ui" id="modal-finret" tabindex="-1" aria-labelledby="modal-finretLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-finretLabel">Autorizar retiro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-finret" class="form-finret" action="#" method="post">
                          <input type="hidden" id="idret" name="idret" value="">
                          <div class="campo">
                            <label for="idmovind">Id Retiro a TD: </label>
                            <input id="idmovind" type="text" name="idmovind" required>
                          </div>
                          <div class="campo">
                            <label for="cantidadini">Cantidad solicitada: </label>
                            <input id="cantidadini" type="text" name="cantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="cantidadfin">Cantidad final: </label>
                            <input id="cantidadfin" type="text" name="cantidadfin" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="furgenteret">¿Es un retiro urgente?</label>
                            <input type="checkbox" id="furgenteret" name="furgenteret" value="urgente">
                          </div>
                          <div class="campo">
                            <label for="fecharet">Fecha retiro: </label>
                            <select id="fecharet" name="fecharet" required>
                              <option value="0">Seleccione una fecha</option>
                              <option value="1">Día 15 del mes</option>
                              <option value="2">Día último del mes</option>
                            </select>
                          </div>
                          <div class="campo">
                            <label for="fechasol">Fecha solicitud: </label>
                            <input type="date" name="fechasol" id="fechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="fechafin">Fecha autorización: </label>
                            <input type="date" name="fechafin" id="fechafin" value="" required>
                          </div>
                          <div class="campo">
                            <label for="notas">Notas: </label>
                            <textarea name="notas" id="notas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="finalizarret" type="submit" name="finalizarret" class="button button-primary" value="Autorizar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-editret" tabindex="-1" aria-labelledby="modal-editretLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-editretLabel">Editar retiro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-editret" class="form-editret" action="#" method="post">
                          <input type="hidden" id="ideret" name="ideret" value="">
                          <div class="campo">
                            <label for="eidmovind">Id Retiro a TD: </label>
                            <input id="eidmovind" type="text" name="eidmovind" required>
                          </div>
                          <div class="campo">
                            <label for="ecantidadini">Cantidad solicitada: </label>
                            <input id="ecantidadini" type="text" name="ecantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="ecantidadfin">Cantidad final: </label>
                            <input id="ecantidadfin" type="text" name="ecantidadfin" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="eurgenteret">¿Es un retiro urgente?</label>
                            <input type="checkbox" id="eurgenteret" name="eurgenteret" value="urgente">
                          </div>
                          <div class="campo">
                            <label for="efecharet">Fecha retiro: </label>
                            <select id="efecharet" name="efecharet" required>
                              <option value="0">Seleccione una fecha</option>
                              <option value="1">Día 15 del mes</option>
                              <option value="2">Día último del mes</option>
                            </select>
                          </div>
                          <div class="campo">
                            <label for="efechasol">Fecha solicitud: </label>
                            <input type="date" name="efechasol" id="efechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="efechafin">Fecha autorización: </label>
                            <input type="date" name="efechafin" id="efechafin" value="">
                          </div>
                          <div class="campo">
                            <label for="enotas">Notas: </label>
                            <textarea name="enotas" id="enotas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="editarret" type="submit" name="editarret" class="button button-primary" value="Editar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-cancret" tabindex="-1" aria-labelledby="modal-cancretLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-cancretLabel">Cancelar retiro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-cancret" class="form-cancret" action="#" method="post">
                          <input type="hidden" id="idcret" name="idcret" value="">
                          <div class="campo">
                            <label for="ccantidadini">Cantidad solicitada: </label>
                            <input id="ccantidadini" type="text" name="ccantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="cfechasol">Fecha solicitud: </label>
                            <input type="date" name="cfechasol" id="cfechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="cfechafin">Fecha cancelación: </label>
                            <input type="date" name="cfechafin" id="cfechafin" value="">
                          </div>
                          <div class="campo">
                            <label for="cnotas">Notas: </label>
                            <textarea name="cnotas" id="cnotas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="cancelarret" type="submit" name="cancelarret" class="button button-primary" value="Cancelar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                </div>
              </div>
          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_admindepositos(){
    $codigo = 'A3CAX1';
    //$filar = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $tabla WHERE codigo = %s", $codigo) );
    $url = get_site_url();
    $urlc = home_url('/confirmacion');
    $urlcompleta = $urlc . '/?code_submitted='.$codigo.'&tipo=dep';
    $user = wp_get_current_user();
    $usernombre = $user->user_firstname;
    $usermail = $user->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }

    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">
          <!-- MENU RESPONSIVE -->
          <nav class="navbar navbar-dark menu-responsive">
            <div class="container-fluid">
              <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#hamburguer-principal"
                aria-controls="hamburguer-principal" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
              </button>
              <img class="img-fluid logo-menu" src="<?php echo plugin_dir_url( __DIR__ )."assets/img/icon-inproyect.png" ?>" alt="">
            </div>
          </nav>
          <div class="collapse" id="hamburguer-principal">
            <div class="shadow-3 d-flex flex-column shadow-3 ">
              <button class="btn btn-link ham-primario btn-block border-bottom m-0 collapsed" data-bs-toggle="collapse" data-bs-target="#hamburguer1" aria-expanded="false">Control de Inversiones</button>
              <div class="collapse" id="hamburguer1" style="">
                <ul class="btn-toggle-nav list-unstyled hamburguer-sublista fw-normal pb-1 small">
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  menu-activo">Control de Depósitos</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                </ul>
              </div>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Investment Referral Com</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">NFT Projects</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Ingresos varios</button></a>
              <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Usuarios</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Wordfence Bloqueos</button></a>
              <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Salir</button></a>
            </div>
          </div>

          <div class="col-0 col-md-3 d-none d-md-block mt-3 menu-escritorio">

            <!-- MENU DESKTOP -->
            <div class="menu-redesign">
              <img src="<?php echo plugin_dir_url( __DIR__ )."assets/img/logo_theinc.png" ?>" class="menu-logo img-fluid" alt="">
              <div class="menu-info">
                <img src="<?php echo $fotourl ?>" class="menu-foto img-fluid" alt="">
                <h3 class="menu-nombre"><?php echo $usernombre; ?></h3>
                <h3 class="menu-email"><?php echo $usermail; ?></h3>
              </div>
              <p class="menu-titulo">MENU</p>
              <ul class="list-unstyled menu-lista ps-0">
                <li class="">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 boton-activo" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    Control de Inversiones
                  </button>
                  <div class="collapse show" id="home-collapse" style="">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  menu-activo">Control de Depósitos</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                    </ul>
                  </div>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Investment Referral Com
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    NFT Projects
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Ingresos Varios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Usuarios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Wordfence Bloqueos
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Salir
                  </button>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-9 antimenu-dashboard" >

            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Control de Depósitos</h1>
              </div>


              <div class="box-transparent mb-3">
                <br>
                <h3 class="titulo3-redesign">Historial de depósitos</h3>
                <br>
                <br>
                <table class="wp-list-table widefat dt-responsive tab-ui striped tab-admindepositos">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Usuario</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Cantidad final</th>
                      <th class="manage_column" >Notas</th>
                      <th class="manage_column" >Fecha deposito</th>
                      <th class="manage_column" >Status</th>
                      <th class="manage_column" >Id Depósito a TD</th>
                      <th class="manage_column" >Id Depósito a Master</th>
                      <th class="manage_column" >Fecha solicitud</th>
                      <th class="manage_column" >Fecha autorización</th>
                      <th class="manage_column" >Tipo Wallet</th>
                      <th class="manage_column" >Wallet Address</th>
                      <th class="manage_column" >Acciones</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
                <!-- <div id="def" class="box" data-tooltip="Hello world">default</div> -->

                <div class="modal fade modal-ui" id="modal-findep" tabindex="-1" aria-labelledby="modal-findepLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-findepLabel">Autorizar depósito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-findep" class="form-findep" action="#" method="post">
                          <input type="hidden" id="iddep" name="iddep" value="">
                          <div class="campo">
                            <label for="idmovind">Id Depósito a TD: </label>
                            <input id="idmovind" type="text" name="idmovind" required>
                          </div>
                          <div class="campo">
                            <label for="idmovgral">Id Depósito a Master: </label>
                            <input id="idmovgral" type="text" name="idmovgral" required>
                          </div>
                          <div class="campo">
                            <label for="cantidadini">Cantidad solicitada: </label>
                            <input id="cantidadini" type="text" name="cantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="cantidadfin">Cantidad final: </label>
                            <input id="cantidadfin" type="text" name="cantidadfin" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="fechadep">Fecha depósito: </label>
                            <select id="fechadep" name="fechadep" required>
                              <option value="1">Día 1 del mes</option>
                              <option value="2">Día 15 del mes</option>
                            </select>
                          </div>
                          <div class="campo">
                            <label for="fechasol">Fecha solicitud: </label>
                            <input type="date" name="fechasol" id="fechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="fechafin">Fecha autorización: </label>
                            <input type="date" name="fechafin" id="fechafin" value="" required>
                          </div>
                          <div class="campo">
                            <label for="notas">Notas: </label>
                            <textarea name="notas" id="notas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="finalizardep" type="submit" name="finalizardep" class="button button-primary" value="Autorizar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-editdep" tabindex="-1" aria-labelledby="modal-editdepLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-editdepLabel">Editar depósito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-editdep" class="form-editdep" action="#" method="post">
                          <input type="hidden" id="idedep" name="idedep" value="">
                          <div class="campo">
                            <label for="eidmovind">Id Depósito a TD: </label>
                            <input id="eidmovind" type="text" name="eidmovind" required>
                          </div>
                          <div class="campo">
                            <label for="eidmovgral">Id Depósito a Master: </label>
                            <input id="eidmovgral" type="text" name="eidmovgral" required>
                          </div>
                          <div class="campo">
                            <label for="ecantidadini">Cantidad solicitada: </label>
                            <input id="ecantidadini" type="text" name="ecantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="ecantidadfin">Cantidad final: </label>
                            <input id="ecantidadfin" type="text" name="ecantidadfin" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="efechadep">Fecha depósito: </label>
                            <select id="efechadep" name="efechadep" required>
                              <option value="1">Día 1 del mes</option>
                              <option value="2">Día 15 del mes</option>
                            </select>
                          </div>
                          <div class="campo">
                            <label for="efechasol">Fecha solicitud: </label>
                            <input type="date" name="efechasol" id="efechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="efechafin">Fecha autorización: </label>
                            <input type="date" name="efechafin" id="efechafin" value="" required>
                          </div>
                          <div class="campo">
                            <label for="enotas">Notas: </label>
                            <textarea name="enotas" id="enotas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="editarret" type="submit" name="editarret" class="button button-primary" value="Editar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-cancdep" tabindex="-1" aria-labelledby="modal-cancdepLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-cancdepLabel">Cancelar depósito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-cancdep" class="form-cancdep" action="#" method="post">
                          <input type="hidden" id="idcdep" name="idcdep" value="">
                          <div class="campo">
                            <label for="ccantidadini">Cantidad solicitada: </label>
                            <input id="ccantidadini" type="text" name="ccantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="cfechasol">Fecha solicitud: </label>
                            <input type="date" name="cfechasol" id="cfechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="cfechafin">Fecha cancelación: </label>
                            <input type="date" name="cfechafin" id="cfechafin" value="">
                          </div>
                          <div class="campo">
                            <label for="cnotas">Notas: </label>
                            <textarea name="cnotas" id="cnotas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="cancelardep" type="submit" name="cancelardep" class="button button-primary" value="Cancelar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                </div>

            </div>

          </div>
        </div>
      </div>


    </div>
    <?php
  }

  public function interfaz_reporteadormensual(){
    // $url = home_url('/confirmacion');
    // $urlcompleta = $url . '/?code_submitted='.$codigo.'&tipo=dep';
    // $year = 2023;
    // $month = 2;
    $year = (int) date("Y");
    $month = (int) date("m");
    global $wpdb;
    //Vemos cuando fue el primer deposito
    $tabdepositos = $wpdb->prefix . 'depositos';
    $primerdepogral = $wpdb->get_results("SELECT month(fecha_termino) AS mes, year(fecha_termino) AS agno, cantidad_real, fecha_cuando, fecha_termino FROM $tabdepositos WHERE status = 2 ORDER BY fecha_termino LIMIT 1 ", ARRAY_A);
    if(count($primerdepogral) == 0){
      $primerdeposito = false;
    }else{
      $pdmesuno = $primerdepogral[0]["mes"];
      $pdmes = (int) $pdmesuno;
      $pdagnouno = (int) $primerdepogral[0]["agno"];
      $pdagno = $pdagnouno;
    }
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );

    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    // $modagresivo = get_user_meta( $user, 'modagresivo', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "interes";
    $submenu = "";

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }

    // echo "<pre>";
    // var_dump($row);
    // echo "</pre>";
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-admin.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Retiros Master</h1>
              </div>

              <div class="ui-dashboard">

                <div class="caja-morada">
                  <h3>Selección de fecha: </h3>
                  <?php if(count($primerdepogral) == 0){ ?>
                  <?php } else { ?>
                    <form class="rep-fecha my-3 d-flex gap-3" id="rep-fecha" action="" method="post">
                      <input type="hidden" id="pd-mes" name="pdmes" value="<?php echo $pdmes; ?>">
                      <input type="hidden" id="pd-agno" name="pdagno" value="<?php echo $pdagno; ?>">
                      <div class="d-flex gap-3 align-items-center">
                        <label for="rep-agno" class="form-label">Año</label>
                        <select class="form-control" id="rep-agno" name="rmagno">
                          <option value="">Seleccione el año</option>
                          <?php for ($i = $pdagno; $i <= $year ; $i++) {
                            if ( $month == 1) {
                              echo ($i == ($year-1)) ? '<option value="'.$i.'" selected="selected">‌'.$i.'</option>' : '<option value="'.$i.'" >‌'.$i.'</option>' ;
                            } else { ?>
                            <?php echo ($i == $year) ? '<option value="'.$i.'" selected="selected">‌'.$i.'</option>' : '<option value="'.$i.'" >‌'.$i.'</option>' ; ?>
                          <?php }
                          } ?>
                        </select>
                      </div>
                      <div class="d-flex gap-3 align-items-center">
                        <label for="rep-mes" class="form-label">Mes</label>
                        <select class="form-control" id="rep-mes" name="rmmes">
                          <option value="">Seleccione el mes</option>
                          <?php for ($i = 1; $i < 13 ; $i++) {
                              if( $month == 1 ){
                              echo '<option value="'.$i.'" selected="selected">‌'.$mesesNombre[$i].'</option>' ;
                            } else {
                              echo ($i == ($month-1 )) ? '<option value="'.$i.'" selected="selected">‌'.$mesesNombre[$i].'</option>' : '<option value="'.$i.'" >‌'.$mesesNombre[$i].'</option>' ;
                            }
                          }?>
                        </select>
                      </div>
                      <button type="submit" id="btn-solrepor" class="btn btn-primary">Consultar</button>
                    </form>
                  <?php } ?>
                </div>

                <div class="box-transparent ">
                  <?php
                    // $calculos = new CRC_Calculo();
                    // $utilidadref = $calculos->totalcuentas_mensual_hoy_detalle();
                  // $utilidadxinvit = $calculos->totalcuentas_utilref_cierre_detalle();
                  // global $wpdb;
                  // $reghistorico = $wpdb->prefix . 'registro_historico';
                  // $depositos = $wpdb->prefix . 'depositos';
                  // $month = 8;
                  // $year = 2022;
                  // $ruta = get_site_url();
                  // // $registros = $wpdb->get_results(" SELECT * FROM $reghistorico ORDER BY id ASC", ARRAY_A);
                  //
                  // $return_json = array();
                  //
                  // if (!$utilidadref) {
                  //   echo json_encode(array('data' => $return_json));
                  //   wp_die();
                  //   return;
                  // }else{
                  //   if(count($utilidadref) == 0 ){
                  //     //return the result to the ajax request and die
                  //     echo json_encode(array('data' => $return_json));
                  //     wp_die();
                  //     return;
                  //   }else{
                  //     // Recorremos el array de los inverisonistas y vemos si tiene utilidades por mes
                  //     foreach ($utilidadref as $key => $inversor) {
                  //       if (count($inversor["totalxuserxmes"]) == 0) {
                  //
                  //       }else{
                  //         // Si tiene utilidades por mes recorremos los meses y comprobamos que sea igual en el que estoy checando
                  //         foreach ($inversor["totalxuserxmes"] as $llave => $mesesdelinv) {
                  //           if ($mesesdelinv["mes"] == $month && $mesesdelinv["year"] == $year) {
                  //
                  //             $totalref = 0;
                  //             // Ahora vamos a revisar el array de usuarios invitados por inversor si no hay nadie pues utilref sera 0, si no buscamos coincidencias de id
                  //             if(!$utilidadxinvit){
                  //
                  //             }else{
                  //               foreach ($utilidadxinvit as $clave => $investor) {
                  //                 // Vemos si el mismo user y revisamos que tenga invitados
                  //                 if ($investor["id"] == $inversor["id"]) {
                  //                   if (count($investor["totalxuserrefxmes"]) == 0) {
                  //
                  //                   }else{
                  //                     //Si tiene invitados entonces recorremos el array de invitados
                  //                     foreach ($investor["totalxuserrefxmes"] as $code => $invitado) {
                  //
                  //                       // Revisamos si los invitados tienen alguna generacion de utilidades para el user
                  //                       if (count($invitado["utilidadxmes"]) == 0) {
                  //
                  //                       }else {
                  //                         // Si la tienen recorremos meses y checamos que sea del mismo mes para sumarla
                  //                         foreach ($invitado["utilidadxmes"] as $chiave => $mesdelref) {
                  //
                  //                           if ($mesdelref["mes"] == $month && $mesdelref["year"] == $year) {
                  //                             $totalref = $totalref+$mesdelref["total"];
                  //                           }
                  //
                  //                         }
                  //                       }
                  //
                  //                     }
                  //                   }
                  //                 }
                  //               }
                  //             }
                  //
                  //             $tutilacum = number_format($mesesdelinv["utilacumulada"], 2, '.', ',');
                  //             $tsubtotalinv = number_format($mesesdelinv["total"], 2, '.', ',');
                  //             $ttotalref = number_format($totalref, 2, '.', ',');
                  //             $ttotal = number_format(($mesesdelinv["total"]+$totalref), 2, '.', ',');
                  //
                  //             $row = array(
                  //                 'nombre' => $inversor["nombre"],
                  //                 'util'=>'-.-',
                  //                 'utilacum'=> $tutilacum,
                  //                 'subtotalinv'=> $tsubtotalinv,
                  //                 'utilref'=> $ttotalref,
                  //                 'total'=> $ttotal,
                  //                 'acciones'=>'-.-'
                  //               );
                  //             $return_json[] = $row;
                  //
                  //           }
                  //         }
                  //       }
                  //     }
                  //   }
                  // }
                  // echo "<pre>";
                  // var_dump($utilidadref);
                  // var_dump($utilidadxinvit);
                  // echo "</pre>";
                  ?>
                  <div class="oper-master row">
                    <div class="oper-mes oper-mes1 col-12 col-md-6 d-flex flex-column ">
                      <div class="caja-depmas d-flex">
                        <span class="oper-depmas"><i class="fa-solid fa-arrow-down"></i></span>
                        <div class="d-flex flex-column">
                          <span class="oper-titulo">Depósitos Master</span>
                          <span class="oper-cant cant-depmas">$0.00</span>
                        </div>
                      </div>
                      <table class="wp-list-table widefat tab-ui striped tab-repmesdep">
                        <thead>
                          <tr>
                            <th class="manage_column" >Mes</th>
                            <th class="manage_column" >Cantidad</th>
                            <th class="manage_column" >Status</th>
                          </tr>
                        </thead>

                        <tbody>

                        </tbody>
                      </table>
                    </div>
                    <div class="oper-mes oper-mes2 col-12 col-md-6 d-flex flex-column mt-4 mt-md-0">
                      <div class="caja-retmas d-flex">
                        <span class="oper-retmas"><i class="fa-solid fa-arrow-up"></i></span>
                        <div class="d-flex flex-column">
                          <span class="oper-titulo">Retiros Master</span>
                          <span class="oper-cant cant-retmas">$0.00</span>
                        </div>
                      </div>
                      <table class="wp-list-table widefat tab-ui striped tab-repmesret">
                        <thead>
                          <tr>
                            <th class="manage_column" >Mes</th>
                            <th class="manage_column" >Cantidad</th>
                            <th class="manage_column" >Status</th>
                          </tr>
                        </thead>

                        <tbody>

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="box-transparent mb-3">
                  <div class="caja-spin">
                    <div class="sk-chase">
                      <div class="sk-chase-dot"></div>
                      <div class="sk-chase-dot"></div>
                      <div class="sk-chase-dot"></div>
                      <div class="sk-chase-dot"></div>
                      <div class="sk-chase-dot"></div>
                      <div class="sk-chase-dot"></div>
                    </div>
                  </div>
                  <div class="caja-tabla mt-5">
                    <table class="wp-list-table widefat tab-ui striped dt-responsive tab-repmensual">
                      <thead>
                        <tr>
                          <th class="manage_column" >Nombre</th>
                          <th class="manage_column" >Depósitos</th>
                          <th class="manage_column" >Cap Inicial Mes</th>
                          <th class="manage_column" >Util</th>
                          <th class="manage_column" >Util Acum</th>
                          <th class="manage_column" >Retiros</th>
                          <th class="manage_column" >Subtotal</th>
                          <th class="manage_column" >Util Ref</th>
                          <th class="manage_column" >Util Tot</th>
                          <th class="manage_column" >Total</th>
                          <th class="manage_column" >Acciones</th>
                        </tr>
                      </thead>

                      <tbody>

                      </tbody>
                    </table>
                  </div>
                  <div class="oper-master my-4">
                    <div class="oper-mes d-flex justify-content-start justify-content-md-end mb-4">
                      <span class="oper-totalinv"><i class="fa-solid fa-user"></i></span>
                      <span class="oper-titulo">Total Inv</span>
                      <span class="oper-cant cant-totalinv">$0.00</span>
                    </div>
                  </div>
                </div>

              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <?php
  }

  public function interfaz_reporteadormensualrefcue(){
    // $url = home_url('/confirmacion');
    // $urlcompleta = $url . '/?code_submitted='.$codigo.'&tipo=dep';
    // $year = 2023;
    // $month = 2;
    global $wpbd;
    $url = get_site_url();
    $year = (int) date("Y");
    $month = (int) date("m");
    global $wpdb;
    //Vemos cuando fue el primer deposito
    $pbl_id = (int) $_GET['p'];
    $bl_projects = $wpdb->prefix . 'projects_bl';
    $bl_usuarios = $wpdb->prefix . 'usuarios_bl';
    // $registrosbl = $wpdb->prefix . 'registros_bl';
    $registros = $wpdb->get_results(" SELECT * FROM $bl_projects  WHERE pbl_id = $pbl_id AND pbl_status = 1 LIMIT 1", ARRAY_A);
    $comision = (float)$registros[0]['pbl_comision'];
    // Saber si hay registros en el proyecto
    $registros1 = $wpdb->get_results(" SELECT * FROM $bl_usuarios WHERE ubl_project = $pbl_id AND cbl_status = 1 ", ARRAY_A);

    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );

    $ruta = get_site_url();

    $registros1 = $wpdb->get_results(" SELECT * FROM $bl_usuarios WHERE ubl_project = $pbl_id AND cbl_status = 1 ", ARRAY_A);

    $user = wp_get_current_user();
    $usernombre = $user->user_firstname;
    $usermail = $user->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // echo "<pre>";
    // var_dump($row);
    // echo "</pre>";
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">
          <!-- MENU RESPONSIVE -->
          <nav class="navbar navbar-dark menu-responsive">
            <div class="container-fluid">
              <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#hamburguer-principal"
                aria-controls="hamburguer-principal" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
              </button>
              <img class="img-fluid logo-menu" src="<?php echo plugin_dir_url( __DIR__ )."assets/img/icon-inproyect.png" ?>" alt="">
            </div>
          </nav>
          <div class="collapse" id="hamburguer-principal">
            <div class="shadow-3 d-flex flex-column shadow-3 ">
              <button class="btn btn-link ham-primario btn-block border-bottom m-0 collapsed" data-bs-toggle="collapse" data-bs-target="#hamburguer1" aria-expanded="false">Control de Inversiones</button>
              <div class="collapse" id="hamburguer1" style="">
                <ul class="btn-toggle-nav list-unstyled hamburguer-sublista fw-normal pb-1 small">
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  menu-activo">Control de Depósitos</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                </ul>
              </div>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Investment Referral Com</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">NFT Projects</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Ingresos varios</button></a>
              <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Usuarios</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Wordfence Bloqueos</button></a>
              <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Salir</button></a>
            </div>
          </div>

          <div class="col-0 col-md-3 d-none d-md-block mt-3 menu-escritorio">

            <!-- MENU DESKTOP -->
            <div class="menu-redesign">
              <img src="<?php echo plugin_dir_url( __DIR__ )."assets/img/logo_theinc.png" ?>" class="menu-logo img-fluid" alt="">
              <div class="menu-info">
                <img src="<?php echo $fotourl ?>" class="menu-foto img-fluid" alt="">
                <h3 class="menu-nombre"><?php echo $usernombre; ?></h3>
                <h3 class="menu-email"><?php echo $usermail; ?></h3>
              </div>
              <p class="menu-titulo">MENU</p>
              <ul class="list-unstyled menu-lista ps-0">
                <li class="">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    Control de Inversiones
                  </button>
                  <div class="collapse" id="home-collapse" style="">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  ">Control de Depósitos</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                    </ul>
                  </div>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed boton-activo" >
                    Investment Referral Com
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    NFT Projects
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    Ingresos Varios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Usuarios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Wordfence Bloqueos
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Salir
                  </button>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-9 antimenu-dashboard" >

            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Reporte mensual global - <?php echo $registros[0]["pbl_nombre"]; ?></h1>
              </div>
              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between pt-4 pb-2">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_dashboard&p=".$pbl_id ?>" class="volverref" ><span class="material-icons">keyboard_backspace</span>Volver al Dashboard</a>
                  </div>
                  <div class="">

                  </div>
                </div>

              </div>

              <div class="box-transparent mb-3">
                <h3>Selección de fecha: </h3>
                <form class="refrep-fecha my-3 d-flex gap-3" id="refrep-fecha" action="" method="post">
                  <input type="hidden" id="pd-mes" name="pdmes" value="<?php echo $month; ?>">
                  <input type="hidden" id="pd-agno" name="pdagno" value="<?php echo $year; ?>">
                  <div class="d-flex gap-3 align-items-center">
                    <label for="rep-agno" class="form-label">Año</label>
                    <select class="form-control" id="rep-agno" name="rmagno">
                      <option value="2022" <?php echo ($year == 2022) ? 'selected="selected"' : '' ; ?> >2022</option>
                      <option value="2023" <?php echo ($year == 2023) ? 'selected="selected"' : '' ; ?> >2023</option>
                      <option value="2024" <?php echo ($year == 2024) ? 'selected="selected"' : '' ; ?> >2024</option>
                      <option value="2025" <?php echo ($year == 2025) ? 'selected="selected"' : '' ; ?> >2025</option>
                      <option value="2026" <?php echo ($year == 2026) ? 'selected="selected"' : '' ; ?> >2026</option>
                      <option value="2027" <?php echo ($year == 2027) ? 'selected="selected"' : '' ; ?> >2027</option>
                      <option value="2028" <?php echo ($year == 2028) ? 'selected="selected"' : '' ; ?> >2028</option>
                      <option value="2029" <?php echo ($year == 2029) ? 'selected="selected"' : '' ; ?> >2029</option>
                      <option value="2030" <?php echo ($year == 2030) ? 'selected="selected"' : '' ; ?> >2030</option>
                      <option value="2031" <?php echo ($year == 2031) ? 'selected="selected"' : '' ; ?> >2031</option>
                    </select>
                  </div>
                  <div class="d-flex gap-3 align-items-center">
                    <label for="rep-mes" class="form-label">Mes</label>
                    <select class="form-control" id="rep-mes" name="rmmes">
                      <?php for ($i = 1; $i < 13 ; $i++) {
                          if( $month == 1 ){
                          echo '<option value="'.$i.'" selected="selected">‌'.$mesesNombre[$i].'</option>' ;
                        } else {
                          echo ($i == ($month-1 )) ? '<option value="'.$i.'" selected="selected">‌'.$mesesNombre[$i].'</option>' : '<option value="'.$i.'" >‌'.$mesesNombre[$i].'</option>' ;
                        }
                      }?>
                    </select>
                  </div>
                  <button type="submit" id="btn-solrepor" class="btn btn-primary">Consultar</button>
                </form>
                <h4 class="mt-5 mb-4">Lista de cuentas :</h4>
                <div class="caja-spin">
                  <div class="sk-chase">
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                  </div>
                </div>
                <table class="caja-tabla wp-list-table widefat tab-ui dt-responsive striped tab-referralreportemes">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Cuenta</th>
                      <th class="manage_column" >Comisión <?php echo $comision; ?>%</th>
                      <th class="manage_column" >Utilidad Mes</th>
                      <th class="manage_column" >Com Broker</th>
                      <th class="manage_column" >Utilidad Real</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
                <br>
                <p class="total-utlreal"><i class="fa-solid fa-coins"></i> Total Util Real :<span class="totalutlreal"></span></p>
                <br>
                <p class="total-detalle"><i class="fa-solid fa-coins"></i> Total Comm <?php echo $comision; ?>:<span class="totalcomm"></span></p>
                <br>
                <br>
                <p>Notas: <br>
                  <ol>
                    <li>La Comisión equivale al <?php echo $comision; ?>% del valor de la Utilidad Real.</li>
                  </ol>
                </p>

              </div>
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

  <?php
  }

  public function interfaz_reporteadormensualrefcuespe(){
    // $url = home_url('/confirmacion');
    // $urlcompleta = $url . '/?code_submitted='.$codigo.'&tipo=dep';
    // $year = 2023;
    // $month = 2;
    $url = get_site_url();
    $year = (int) date("Y");
    $month = (int) date("m");
    global $wpdb;
    //Vemos cuando fue el primer deposito
    $pbl_id = (int) $_GET['p'];
    $bl_projects = $wpdb->prefix . 'projects_bl';
    $bl_usuarios = $wpdb->prefix . 'usuarios_bl';

    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );

    $ruta = get_site_url();

    $registros = $wpdb->get_results(" SELECT * FROM $bl_projects  WHERE pbl_id = $pbl_id AND pbl_status = 1 LIMIT 1", ARRAY_A);
    $comandres = (float)$registros[0]['pbl_comandres'];
    $comtiger = (float)$registros[0]['pbl_comtiger'];
    // Saber si hay registros en el proyecto
    $registros1 = $wpdb->get_results(" SELECT * FROM $bl_usuarios WHERE ubl_project = $pbl_id AND cbl_status = 1 ", ARRAY_A);

    $user = wp_get_current_user();
    $usernombre = $user->user_firstname;
    $usermail = $user->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // echo "<pre>";
    // var_dump($row);
    // echo "</pre>";
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">
          <!-- MENU RESPONSIVE -->
          <nav class="navbar navbar-dark menu-responsive">
            <div class="container-fluid">
              <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#hamburguer-principal"
                aria-controls="hamburguer-principal" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
              </button>
              <img class="img-fluid logo-menu" src="<?php echo plugin_dir_url( __DIR__ )."assets/img/icon-inproyect.png" ?>" alt="">
            </div>
          </nav>
          <div class="collapse" id="hamburguer-principal">
            <div class="shadow-3 d-flex flex-column shadow-3 ">
              <button class="btn btn-link ham-primario btn-block border-bottom m-0 collapsed" data-bs-toggle="collapse" data-bs-target="#hamburguer1" aria-expanded="false">Control de Inversiones</button>
              <div class="collapse" id="hamburguer1" style="">
                <ul class="btn-toggle-nav list-unstyled hamburguer-sublista fw-normal pb-1 small">
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  menu-activo">Control de Depósitos</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                </ul>
              </div>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Investment Referral Com</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">NFT Projects</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Ingresos varios</button></a>
              <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Usuarios</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Wordfence Bloqueos</button></a>
              <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Salir</button></a>
            </div>
          </div>

          <div class="col-0 col-md-3 d-none d-md-block mt-3 menu-escritorio">

            <!-- MENU DESKTOP -->
            <div class="menu-redesign">
              <img src="<?php echo plugin_dir_url( __DIR__ )."assets/img/logo_theinc.png" ?>" class="menu-logo img-fluid" alt="">
              <div class="menu-info">
                <img src="<?php echo $fotourl ?>" class="menu-foto img-fluid" alt="">
                <h3 class="menu-nombre"><?php echo $usernombre; ?></h3>
                <h3 class="menu-email"><?php echo $usermail; ?></h3>
              </div>
              <p class="menu-titulo">MENU</p>
              <ul class="list-unstyled menu-lista ps-0">
                <li class="">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    Control de Inversiones
                  </button>
                  <div class="collapse" id="home-collapse" style="">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  ">Control de Depósitos</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                    </ul>
                  </div>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed boton-activo" >
                    Investment Referral Com
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    NFT Projects
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    Ingresos Varios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Usuarios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Wordfence Bloqueos
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Salir
                  </button>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-9 antimenu-dashboard" >

            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1><i class="fa-solid fa-star star-amarilla"></i>Reporte mensual global - <?php echo $registros[0]["pbl_nombre"]; ?></h1>
              </div>
              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between pt-4 pb-2">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_dashboardspe&p=".$pbl_id ?>" class="volverref" ><span class="material-icons">keyboard_backspace</span>Volver al Dashboard</a>
                  </div>
                  <div class="">

                  </div>
                </div>

              </div>

              <div class="box-transparent mb-3">
                <h3>Selección de fecha: </h3>
                <form class="refrepspe-fecha my-3 d-flex gap-3" id="refrepspe-fecha" action="" method="post">
                  <input type="hidden" id="pd-mes" name="pdmes" value="<?php echo $month; ?>">
                  <input type="hidden" id="pd-agno" name="pdagno" value="<?php echo $year; ?>">
                  <div class="d-flex gap-3 align-items-center">
                    <label for="rep-agno" class="form-label">Año</label>
                    <select class="form-control" id="rep-agno" name="rmagno">
                      <option value="2022" <?php echo ($year == 2022) ? 'selected="selected"' : '' ; ?> >2022</option>
                      <option value="2023" <?php echo ($year == 2023) ? 'selected="selected"' : '' ; ?> >2023</option>
                      <option value="2024" <?php echo ($year == 2024) ? 'selected="selected"' : '' ; ?> >2024</option>
                      <option value="2025" <?php echo ($year == 2025) ? 'selected="selected"' : '' ; ?> >2025</option>
                      <option value="2026" <?php echo ($year == 2026) ? 'selected="selected"' : '' ; ?> >2026</option>
                      <option value="2027" <?php echo ($year == 2027) ? 'selected="selected"' : '' ; ?> >2027</option>
                      <option value="2028" <?php echo ($year == 2028) ? 'selected="selected"' : '' ; ?> >2028</option>
                      <option value="2029" <?php echo ($year == 2029) ? 'selected="selected"' : '' ; ?> >2029</option>
                      <option value="2030" <?php echo ($year == 2030) ? 'selected="selected"' : '' ; ?> >2030</option>
                      <option value="2031" <?php echo ($year == 2031) ? 'selected="selected"' : '' ; ?> >2031</option>
                    </select>
                  </div>
                  <div class="d-flex gap-3 align-items-center">
                    <label for="rep-mes" class="form-label">Mes</label>
                    <select class="form-control" id="rep-mes" name="rmmes">
                      <?php for ($i = 1; $i < 13 ; $i++) {
                          if( $month == 1 ){
                          echo '<option value="'.$i.'" selected="selected">‌'.$mesesNombre[$i].'</option>' ;
                        } else {
                          echo ($i == ($month-1 )) ? '<option value="'.$i.'" selected="selected">‌'.$mesesNombre[$i].'</option>' : '<option value="'.$i.'" >‌'.$mesesNombre[$i].'</option>' ;
                        }
                      }?>
                    </select>
                  </div>
                  <button type="submit" id="btn-solrepor" class="btn btn-primary">Consultar</button>
                </form>
                <h4 class="mt-5 mb-4">Lista de cuentas :</h4>
                <div class="caja-spin">
                  <div class="sk-chase">
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                  </div>
                </div>
                <table class="caja-tabla wp-list-table widefat tab-ui dt-responsive striped tab-referralreportemesspe">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Cuenta</th>
                      <th class="manage_column" >Com Andrés</th>
                      <th class="manage_column" >Utilidad</th>
                      <th class="manage_column" >Com Broker</th>
                      <th class="manage_column" >Com Trader</th>
                      <th class="manage_column" >% Com Trader</th>
                      <th class="manage_column" >Util After</th>
                      <th class="manage_column" >Saldo Inicial</th>
                      <th class="manage_column" >% Util Real Final</th>
                      <th class="manage_column" >Com Tiger</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
                <br>
                <p class="total-utlaft"><i class="fa-solid fa-coins"></i> Total Util After :<span class="totalutlaft"></span></p>
                <br>
                <p class="total-detalle"><i class="fa-solid fa-coins"></i> Total Comm :<span class="totalcomm"></span></p>
                <br>
                <br>
                <p>Notas: <br>
                  <ol>
                    <li> Com Andrés equivale a un <?php echo $comandres; ?>% de la utilidad mensual.</li>
                    <li> Com Tiger equivale a un <?php echo $comtiger; ?>% de la utilidad mensual.</li>
                  </ol>
                </p>

              </div>
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

  <?php
  }

  public function interfaz_adminrefvercuenta(){
    $url = get_site_url();
    $year = date("Y");
    $month = date("m");
    global $wpdb;
    $cbl_id = (int) $_GET['id'];
    $cuentas = $wpdb->prefix . 'cuentas_bl';
    $usuarios = $wpdb->prefix . 'usuarios_bl';
    $registrosbl = $wpdb->prefix . 'registros_bl';
    $proyectosbl = $wpdb->prefix . 'projects_bl';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $cuentas INNER JOIN $usuarios ON $cuentas.cbl_usuario = $usuarios.ubl_id WHERE cbl_id = $cbl_id AND cbl_status = 1 LIMIT 1", ARRAY_A);
    $cbl_nombre = $registros[0]['cbl_nombre'];
    $ubl_nombre = $registros[0]['ubl_nombre']." ".$registros[0]['ubl_apellidos'];
    $cbl_numero = $registros[0]['cbl_numero'];
    $pbl_id = $registros[0]['ubl_project'];
    if ($cbl_numero == null) {
      $cbl_numero = "0000";
    }
    $ubl_id = $registros[0]['ubl_id'];
    $proyecto = $wpdb->get_results(" SELECT * FROM $proyectosbl WHERE pbl_id = $pbl_id AND pbl_status = 1 ", ARRAY_A);
    $registros1 = $wpdb->get_results(" SELECT * FROM $registrosbl WHERE rbl_cuenta = $cbl_id AND rbl_status = 1 ORDER BY rbl_year, rbl_mes", ARRAY_A);
    if (count($proyecto) != 0) {
      $comision = (float)$proyecto[0]['pbl_comision'];
    }else{
      $comision = 0;
    }

    $user = wp_get_current_user();
    $usernombre = $user->user_firstname;
    $usermail = $user->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }

    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">
          <!-- MENU RESPONSIVE -->
          <nav class="navbar navbar-dark menu-responsive">
            <div class="container-fluid">
              <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#hamburguer-principal"
                aria-controls="hamburguer-principal" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
              </button>
              <img class="img-fluid logo-menu" src="<?php echo plugin_dir_url( __DIR__ )."assets/img/icon-inproyect.png" ?>" alt="">
            </div>
          </nav>
          <div class="collapse" id="hamburguer-principal">
            <div class="shadow-3 d-flex flex-column shadow-3 ">
              <button class="btn btn-link ham-primario btn-block border-bottom m-0 collapsed" data-bs-toggle="collapse" data-bs-target="#hamburguer1" aria-expanded="false">Control de Inversiones</button>
              <div class="collapse" id="hamburguer1" style="">
                <ul class="btn-toggle-nav list-unstyled hamburguer-sublista fw-normal pb-1 small">
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  menu-activo">Control de Depósitos</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                </ul>
              </div>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Investment Referral Com</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">NFT Projects</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Ingresos varios</button></a>
              <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Usuarios</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Wordfence Bloqueos</button></a>
              <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Salir</button></a>
            </div>
          </div>

          <div class="col-0 col-md-3 d-none d-md-block mt-3 menu-escritorio">

            <!-- MENU DESKTOP -->
            <div class="menu-redesign">
              <img src="<?php echo plugin_dir_url( __DIR__ )."assets/img/logo_theinc.png" ?>" class="menu-logo img-fluid" alt="">
              <div class="menu-info">
                <img src="<?php echo $fotourl ?>" class="menu-foto img-fluid" alt="">
                <h3 class="menu-nombre"><?php echo $usernombre; ?></h3>
                <h3 class="menu-email"><?php echo $usermail; ?></h3>
              </div>
              <p class="menu-titulo">MENU</p>
              <ul class="list-unstyled menu-lista ps-0">
                <li class="">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    Control de Inversiones
                  </button>
                  <div class="collapse" id="home-collapse" style="">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  ">Control de Depósitos</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                    </ul>
                  </div>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed boton-activo" >
                    Investment Referral Com
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    NFT Projects
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    Ingresos Varios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Usuarios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Wordfence Bloqueos
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Salir
                  </button>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-9 antimenu-dashboard" >

            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1><?php echo $cbl_nombre." - ".$cbl_numero." - ".$ubl_nombre; ?></h1>
              </div>
              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-4">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_dashboard&p=".$pbl_id ?>" class="volverref" ><span class="material-icons">keyboard_backspace</span>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                    <button class="btn-editcuentabl" type="button" data-bs-toggle="modal" data-bs-target="#modal-editcuentabl"><i class="fa-solid fa-pencil"></i>Editar cuenta</button>
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_vertotalcuentasbl&id=".$ubl_id ?>"><button class="btn-vertodascuentas" ><i class="fa-solid fa-file-invoice-dollar"></i>Reporte Total de Cuentas</button></a>
                    <?php
                    if(count($registros1) == 0){ ?>
                    <button class="btn-deletecuentabl" data-id="<?php echo $cbl_id; ?>" type="button" ><i class="fa-solid fa-x" ></i>Eliminar</button>
                    <?php } ?>
                    <button class="btn-addregnbl" type="button" data-bs-toggle="modal" data-bs-target="#modal-addregnbl"><i class="fa-solid fa-file-circle-plus"></i>Agregar registro</button>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-editcuentabl" tabindex="-1" aria-labelledby="modal-editcuentablLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-4" id="modal-editcuentablLabel">Editar cuenta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-editcuentabl" class="form-editcuentabl" action="#" method="post">
                          <input type="hidden" id="cbl_eid" name="cbl_eid" value="<?php echo $registros[0]['cbl_id'] ?>">
                          <div class="campo">
                            <label for="cbl_enombre">*Nombre de la cuenta: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="cbl_enombre" class="form-control" type="text" name="cbl_enombre" value="<?php echo $registros[0]['cbl_nombre'] ?>" required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="cbl_enumero">*Número de cuenta: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="cbl_enumero" class="form-control" type="text" name="cbl_enumero" value="<?php echo $registros[0]['cbl_numero'] ?>" required >
                            </div>
                          </div>
                          <div class="campo campo-notas">
                            <label for="cbl_enotas">Notas: </label>
                            <textarea name="cbl_enotas" id="cbl_enotas" rows="5" style="resize: none;" ><?php echo $registros[0]['cbl_notas'] ?></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="editarcuentabl" type="submit" name="editarcuenta" class="button button-primary" value="Editar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-addregnbl" tabindex="-1" aria-labelledby="modal-addregnblLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-addregnblLabel">Agregar registro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-addregnbl" class="form-addregnbl" action="#" method="post">
                          <input type="hidden" id="rbl_cid" name="rbl_cid" value="<?php echo $registros[0]['cbl_id'] ?>">
                          <div class="campo">
                            <label for="rbl_mes">Mes: </label>
                            <input id="rbl_mes" type="number" name="rbl_mes" value="<?php echo $month; ?>" min="1" max="12"  required>
                          </div>
                          <div class="campo">
                            <label for="rbl_agno">Año: </label>
                            <input id="rbl_agno" type="text" name="rbl_agno" value="<?php echo $year; ?>" data-inputmask="'mask':'9{4}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="rbl_utilidad">*Utilidad mensual: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rbl_utilidad" class="form-control" type="text" name="rbl_utilidad" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="rbl_combro">*Commision Broker: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rbl_combro" class="form-control" type="text" name="rbl_combro" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo campo-notas">
                            <label for="rbl_notas">Notas: </label>
                            <textarea name="rbl_notas" id="rbl_notas" rows="5" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="agregarregnbl" type="submit" name="agregarregn" class="button button-primary" value="Agregar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

              </div>

              <div class="box-transparent mb-3">
                <h4 class="mb-5">Registros mensuales de la cuenta:</h4>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-referralcuentan">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Año</th>
                      <th class="manage_column" >Mes</th>
                      <th class="manage_column" >Utilidad Mes</th>
                      <th class="manage_column" >Com Broker</th>
                      <th class="manage_column" >Utilidad Real</th>
                      <th class="manage_column" >Comisión <?php echo $comision; ?>%</th>
                      <th class="manage_column" >Acciones</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
                <br>
                <br>
                <p>Notas: <br>
                  <ol>
                    <li>El Profit equivale al <?php echo $comision; ?>% del valor de la Utilidad Real.</li>
                  </ol>
                </p>

                <div class="modal fade modal-ui" id="modal-editregnbl" tabindex="-1" aria-labelledby="modal-editregnblLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-editregnblLabel">Editar registro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-editregnbl" class="form-editregnbl" action="#" method="post">
                          <input type="hidden" id="rbl_eid" name="rbl_eid" value="">
                          <div class="campo">
                            <label for="rbl_emes">Mes: </label>
                            <input id="rbl_emes" type="number" name="rbl_emes" value="<?php echo $month; ?>" min="1" max="12"  required>
                          </div>
                          <div class="campo">
                            <label for="rbl_eagno">Año: </label>
                            <input id="rbl_eagno" type="text" name="rbl_eagno" value="<?php echo $year; ?>" data-inputmask="'mask':'9{4}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="rbl_eutilidad">*Utilidad mensual: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rbl_eutilidad" class="form-control" type="text" name="rbl_eutilidad" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="rbl_ecombro">*Commision Broker: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rbl_ecombro" class="form-control" type="text" name="rbl_ecombro" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo campo-notas">
                            <label for="rbl_enotas">Notas: </label>
                            <textarea name="rbl_enotas" id="rbl_enotas" rows="5" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="editarregnbl" type="submit" name="editarregn" class="button button-primary" value="Editar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>
              </div>
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_adminrefvernftproject(){
    $url = get_site_url();
    $year = date("Y");
    $month = date("m");
    global $wpdb;
    $nft_id = (int) $_GET['id'];
    $nft_projects = $wpdb->prefix . 'projects_nft';
    // $usuarios = $wpdb->prefix . 'usuarios_bl';
    $registrosnft = $wpdb->prefix . 'registros_nft';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $nft_projects  WHERE nft_id = $nft_id AND nft_status = 1 LIMIT 1", ARRAY_A);
    $totalret = $wpdb->get_results(" SELECT ROUND(SUM(rnft_total), 4) AS totalret FROM $registrosnft  WHERE rnft_proyecto = $nft_id AND rnft_status = 1 LIMIT 1", ARRAY_A);
    $nft_nombre = $registros[0]['nft_nombre'];
    if ($registros[0]["nft_imagen"]) {
      $urlimagen = get_site_url()."/wp-content/uploads/".$registros[0]["nft_imagen"] ;
    }else{
      $urlimagen = plugin_dir_url( __DIR__ )."assets/img/image-empty.png";
    }
    $tipo = $registros[0]['nft_tipo'];
    if ($tipo == 0) {
      $ttipo = "Mensual";
    }else{
      $ttipo = "Semanal";
    }

    // $ubl_nombre = $registros[0]['ubl_nombre']." ".$registros[0]['ubl_apellidos'];
    // $nft_numero = $registros[0]['nft_numero'];
    // if ($cbl_numero == null) {
    //   $cbl_numero = "0000";
    // }
    // $ubl_id = $registros[0]['ubl_id'];

    // Saber si hay registros en el proyecto
    $registros1 = $wpdb->get_results(" SELECT * FROM $registrosnft WHERE rnft_proyecto = $nft_id ", ARRAY_A);
    $registros2 = $wpdb->get_results(" SELECT * FROM $registrosnft WHERE rnft_proyecto = $nft_id AND rnft_status = 1 ORDER BY rnft_year, rnft_mes, rnft_semana, rnft_fecha ", ARRAY_A);
    if(count($registros2) == 0){
      $totact = 0;
      $totret = number_format($totact, 4);
      $ultregid = 0;
    }else{
      $lkey2 = sizeof($registros2) -1 ;
      $totact = (float)$registros2[$lkey2]['rnft_total'];
      $totret = number_format($totact, 4);
      $ultregid = (int)$registros2[$lkey2]['rnft_id'];
    }



    // echo "<pre>";
    // var_dump($totalret);
    // echo "</pre>";
    $user = wp_get_current_user();
    $usernombre = $user->user_firstname;
    $usermail = $user->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }

    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">
          <!-- MENU RESPONSIVE -->
          <nav class="navbar navbar-dark menu-responsive">
            <div class="container-fluid">
              <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#hamburguer-principal"
                aria-controls="hamburguer-principal" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
              </button>
              <img class="img-fluid logo-menu" src="<?php echo plugin_dir_url( __DIR__ )."assets/img/icon-inproyect.png" ?>" alt="">
            </div>
          </nav>
          <div class="collapse" id="hamburguer-principal">
            <div class="shadow-3 d-flex flex-column shadow-3 ">
              <button class="btn btn-link ham-primario btn-block border-bottom m-0 collapsed" data-bs-toggle="collapse" data-bs-target="#hamburguer1" aria-expanded="false">Control de Inversiones</button>
              <div class="collapse" id="hamburguer1" style="">
                <ul class="btn-toggle-nav list-unstyled hamburguer-sublista fw-normal pb-1 small">
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  menu-activo">Control de Depósitos</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                </ul>
              </div>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Investment Referral Com</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">NFT Projects</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Ingresos varios</button></a>
              <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Usuarios</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Wordfence Bloqueos</button></a>
              <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Salir</button></a>
            </div>
          </div>

          <div class="col-0 col-md-3 d-none d-md-block mt-3 menu-escritorio">

            <!-- MENU DESKTOP -->
            <div class="menu-redesign">
              <img src="<?php echo plugin_dir_url( __DIR__ )."assets/img/logo_theinc.png" ?>" class="menu-logo img-fluid" alt="">
              <div class="menu-info">
                <img src="<?php echo $fotourl ?>" class="menu-foto img-fluid" alt="">
                <h3 class="menu-nombre"><?php echo $usernombre; ?></h3>
                <h3 class="menu-email"><?php echo $usermail; ?></h3>
              </div>
              <p class="menu-titulo">MENU</p>
              <ul class="list-unstyled menu-lista ps-0">
                <li class="">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    Control de Inversiones
                  </button>
                  <div class="collapse" id="home-collapse" style="">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  ">Control de Depósitos</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                    </ul>
                  </div>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Investment Referral Com
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed boton-activo" >
                    NFT Projects
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    Ingresos Varios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Usuarios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Wordfence Bloqueos
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Salir
                  </button>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-9 antimenu-dashboard" >

            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1><?php echo $nft_nombre." - ".$ttipo; ?></h1>
              </div>
              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-4">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects" ?>" class="volverref" ><span class="material-icons">keyboard_backspace</span>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                    <button class="btn-editnftproject" type="button" data-bs-toggle="modal" data-bs-target="#modal-editnftproject"><i class="fa-solid fa-pencil"></i>Editar NFT Project</button>
                    <?php if(count($registros1) == 0){ ?>
                    <button class="btn-deleteproyectonft" data-id="<?php echo $nft_id; ?>" type="button" ><i class="fa-solid fa-x" ></i>Eliminar</button>
                    <?php } ?>
                    <button class="btn-addregnft" id="btn-addregnft" data-tipo="<?php echo $tipo; ?>" type="button" data-bs-toggle="modal" data-bs-target="#modal-addregnft"><i class="fa-solid fa-file-circle-plus"></i>Agregar registro</button>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-editnftproject" tabindex="-1" aria-labelledby="modal-editnftprojectLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-4" id="modal-editnftprojectLabel">Editar cuenta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-editnftproject" class="form-editnftproject" action="#" method="post">
                          <input type="hidden" id="nft_eid" name="nft_eid" value="<?php echo $registros[0]['nft_id'] ?>">
                          <div class="campo">
                            <label for="nft_enombre">*Nombre(s): </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="nft_enombre" class="form-control" type="text" name="nft_enombre" value="<?php echo $registros[0]['nft_nombre'] ?>" required >
                            </div>
                          </div>
                          <!-- <input type="hidden" name="ubl_tipo" id="ubl_tipo" value="1"> -->
                          <!-- <div class="campo">
                            <label for="nft_etipo">*Tipo de proyecto: </label>
                            <div class="input-group mb-3">
                              <div class="form-check">
                                <input class="form-check-input" type="radio" name="nft_etipo" id="nft_etipo_mensual" checked required>
                                <label class="form-check-label" for="nft_etipo_mensual">
                                  Mensual
                                </label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="radio" name="nft_etipo" id="nft_etipo_semanal">
                                <label class="form-check-label" for="nft_etipo_semanal">
                                  Semanal
                                </label>
                              </div>

                            </div>
                          </div> -->
                          <div class="campo">
                            <label for="nft_ecolor">*Color de la tarjeta: </label>
                            <select class="input-group mb-3" name="nft_ecolor" id="nft_ecolor" required>
                              <option value="1" <?php  echo ($registros[0]['nft_color'] == 1) ? "selected"  :  "" ; ?> >Verde</option>
                              <option value="2" <?php  echo ($registros[0]['nft_color'] == 2) ? "selected"  :  "" ; ?> >Gris</option>
                              <option value="3" <?php  echo ($registros[0]['nft_color'] == 3) ? "selected"  :  "" ; ?> >Naranja</option>
                              <option value="4" <?php  echo ($registros[0]['nft_color'] == 4) ? "selected"  :  "" ; ?> >Morado</option>
                              <option value="5" <?php  echo ($registros[0]['nft_color'] == 5) ? "selected"  :  "" ; ?> >Rojo</option>
                            </select>
                          </div>
                          <div class="campo campo-img">
                            <label for="nft_eimagen">*Imagen de la tarjeta: </label>
                            <div class="campo-imagen">
                              <img src="<?php echo $urlimagen; ?>" class="imagen-tarjeta" alt="">
                              <div class="help-block">
                                <span class="help-block-archivos">La imagen debe estar en formato JPG o PNG.</span>
                                <span class="help-block-peso">La imagen no debe pesar más de 5MB","Atención al subir la foto.</span>
                              </div>
                              <input type="file" id="nft_eimagen" name="nft_eimagen" value="">
                            </div>
                          </div>
                          <div class="campo campo-notas">
                            <label for="nft_enotas">Notas: </label>
                            <textarea name="nft_enotas" id="nft_enotas" rows="5" style="resize: none;" ><?php echo $registros[0]['nft_notas'] ?></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="editarproyectonft" type="submit" name="editarproyecto" class="button button-primary" value="Editar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-addregnft" tabindex="-1" aria-labelledby="modal-addregnftlLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-addregnftLabel">Agregar registro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-addregnft" class="form-addregnft" action="#" method="post">
                          <input type="hidden" id="rnft_nid" name="rnft_nid" value="<?php echo $registros[0]['nft_id'] ?>">
                          <div class="campo">
                            <label for="rnft_agno">*Año: </label>
                            <input id="rnft_agno" type="text" name="rnft_agno" value="<?php echo $year; ?>" data-inputmask="'mask':'9{4}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="rnft_mes">*Mes: </label>
                            <input id="rnft_mes" type="number" name="rnft_mes" value="<?php echo $month; ?>" min="1" max="12"  required>
                          </div>
                          <?php if ($registros[0]['nft_tipo'] == 0){ ?>
                            <div class="campo">
                              <label for="rnft_semana">*Semana: </label>
                              <select id="rnft_semana" class="rnft_semana" name="rnft_semana" disabled required>
                                <option value="0" selected>Mensual</option>
                                <option value="1">Semana 1</option>
                                <option value="2">Semana 2</option>
                                <option value="3">Semana 3</option>
                                <option value="4">Semana 4</option>
                                <option value="5">Semana 5</option>
                              </select>
                            </div>
                          <?php }else {?>
                            <div class="campo">
                              <label for="rnft_semana">*Semana: </label>
                              <select id="rnft_semana" class="rnft_semana" name="rnft_semana" required>
                                <option value="1">Semana 1</option>
                                <option value="2">Semana 2</option>
                                <option value="3">Semana 3</option>
                                <option value="4">Semana 4</option>
                                <option value="5">Semana 5</option>
                              </select>
                            </div>
                          <?php } ?>
                          <div class="campo">
                            <label for="rnft_total">*Total: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rnft_total" class="form-control" type="text" name="rnft_total" value="0.00" data-inputmask="'mask':'9{1,20}.{0,1}9{0,4}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="rnft_team">*Team: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rnft_team" class="form-control" type="text" name="rnft_team" value="0.00" data-inputmask="'mask':'9{1,20}.{0,1}9{0,4}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo campo-notas">
                            <label for="rnft_notas">Notas: </label>
                            <textarea name="rnft_notas" id="rnft_notas" rows="5" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="agregarregnft" type="submit" name="agregarregnft" class="button button-primary" value="Agregar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

              </div>

              <div class="box-transparent mb-3">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between pb-4">
                  <div class="">
                    <p class="total-totalreg"><i class="fa-solid fa-coins"></i> Total Actual: <span class="totalreg"><?php echo " ".$totret; ?></span></p>
                  </div>
                  <div class="">
                    <?php if(count($registros1) != 0){ ?>
                    <button class="btn-addretnft" id="btn-addretnft" data-tipo="<?php echo $tipo; ?>" type="button" data-bs-toggle="modal" data-bs-target="#modal-addretnft"><i class="fa-solid fa-money-bill-transfer"></i>Registrar retiro</button>
                    <?php } ?>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-addretnft" tabindex="-1" aria-labelledby="modal-addretnftlLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-addretnftLabel">Agregar retiro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-addretnft" class="form-addretnft" action="#" method="post">
                          <input type="hidden" id="rtnft_nid" name="rtnft_nid" value="<?php echo $registros[0]['nft_id'] ?>">
                          <input type="hidden" id="rtnft_ultreg" name="rtnft_ultreg" value="<?php echo $ultregid ?>">
                          <div class="campo">
                            <label for="rtnft_total">*Total Actual: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rtnft_total" class="form-control" type="text" name="rtnft_total" value="<?php echo $totact; ?>" data-inputmask="'mask':'9{1,20}.{0,1}9{0,4}'" data-mask required disabled >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="rtnft_usdactual">*Valor USD: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rtnft_usdactual" class="form-control" type="text" name="rtnft_usdactual" value="" data-inputmask="'mask':'9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="rtnft_fecha_retiro">*Fecha Retiro: </label>
                            <input type="date" name="rtnft_fecha_retiro" id="rtnft_fecha_retiro" value="<?php echo date('Y-m-d'); ?>" required>
                          </div>
                          <div class="campo campo-notas">
                            <label for="rtnft_notas">Notas: </label>
                            <textarea name="rtnft_notas" id="rtnft_notas" rows="5" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="agregarretnft" type="submit" name="agregarretnft" class="button button-primary" value="Aceptar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-editretnft" tabindex="-1" aria-labelledby="modal-editretnftlLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-editretnftLabel">Editar retiro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-editretnft" class="form-editretnft" action="#" method="post">
                          <input type="hidden" id="rtnft_eid" name="rtnft_eid" value="">
                          <div class="campo">
                            <label for="rtnft_etotal">*Total Actual: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rtnft_etotal" class="form-control" type="text" name="rtnft_etotal" value="" data-inputmask="'mask':'9{1,20}.{0,1}9{0,4}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="rtnft_eusdactual">*Valor USD: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rtnft_eusdactual" class="form-control" type="text" name="rtnft_eusdactual" value="" data-inputmask="'mask':'9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="rtnft_efecha_retiro">*Fecha Retiro: </label>
                            <input type="date" name="rtnft_efecha_retiro" id="rtnft_efecha_retiro" value="" required>
                          </div>
                          <div class="campo campo-notas">
                            <label for="rtnft_enotas">Notas: </label>
                            <textarea name="rtnft_enotas" id="rtnft_enotas" rows="5" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="editarretnft" type="submit" name="editarretnft" class="button button-primary" value="Editar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>


                <?php if ($tipo == 1) { ?>
                  <h4 class="mb-5">Ganancias por mes:</h4>
                  <table class="wp-list-table widefat tab-ui dt-responsive striped tab-referralregistrosnftmes">
                    <thead>
                      <tr>
                        <th class="manage_column" >#</th>
                        <th class="manage_column" >Año</th>
                        <th class="manage_column" >Mes</th>
                        <th class="manage_column" >Total</th>
                        <th class="manage_column" >Team</th>
                        <th class="manage_column" >Personal</th>
                        <th class="manage_column" >Núm Semanas</th>
                      </tr>
                    </thead>

                    <tbody>

                    </tbody>
                  </table>
                  <br>
                  <p>Notas: <br>
                    <ol>
                      <li>La columna Personal muestra la diferencia entre Total y Personal / Y diferencia (Personal mes actual - Personal mes inmediato anterior).</li>
                      <li>Esta tabla historica mensual en la columna personal no tiene en cuenta los registros cerrados o abiertos para la diferencia (Personal mes actual - Personal mes inmediato anterior) .</li>
                    </ol>
                  </p>
                  <br>
                  <br>
                  <h4 class="mb-5">Registros semanales:</h4>
                  <table class="wp-list-table widefat tab-ui dt-responsive striped tab-referralregistrosnft">
                    <thead>
                      <tr>
                        <th class="manage_column" >#</th>
                        <th class="manage_column" >Año</th>
                        <th class="manage_column" >Periodo</th>
                        <th class="manage_column" >Total</th>
                        <th class="manage_column" >Team</th>
                        <th class="manage_column" >Personal</th>
                        <th class="manage_column" >Acciones</th>
                        <th class="manage_column" >Retiro</th>
                      </tr>
                    </thead>

                    <tbody>

                    </tbody>
                  </table>
                  <br>
                  <p>Notas: <br>
                    <ol>
                      <li>La columna Personal muestra la diferencia entre Total y Personal / Y diferencia (Personal registro actual - Personal registro inmediato anterior).</li>
                      <li>Si se efectua un retiro, los registros se cierran y el nuevo registro capturado mostrará su diferencia igual que el personal de dicha semana para las tres columnas, ya que la cuenta vuelve a empezar de 0.</li>
                    </ol>
                  </p>

                <?php }else{ ?>
                  <h4 class="mb-5">Registros mensuales:</h4>
                  <table class="wp-list-table widefat tab-ui dt-responsive striped tab-referralregistrosnft">
                    <thead>
                      <tr>
                        <th class="manage_column" >#</th>
                        <th class="manage_column" >Año</th>
                        <th class="manage_column" >Periodo</th>
                        <th class="manage_column" >Total</th>
                        <th class="manage_column" >Team</th>
                        <th class="manage_column" >Personal</th>
                        <th class="manage_column" >Acciones</th>
                        <th class="manage_column" >Retiro</th>
                      </tr>
                    </thead>

                    <tbody>

                    </tbody>
                  </table>
                  <br>
                  <p>Notas: <br>
                    <ol>
                      <li>La columna Personal muestra la diferencia entre Total y Personal / Y diferencia (Personal registro actual - Personal registro mensual inmediato anterior).</li>
                      <li>Si se efectua un retiro, los registros se cierran y el nuevo registro capturado mostrará su diferencia igual que el de dicho mes para las tres columnas, ya que la cuenta vuelve a empezar de 0.</li>
                    </ol>
                  </p>
                  <!-- <h4 class="mb-5">Registros:</h4>
                  <table class="wp-list-table widefat tab-ui dt-responsive striped tab-referralregistrosnftm">
                    <thead>
                      <tr>
                        <th class="manage_column" >#</th>
                        <th class="manage_column" >Año</th>
                        <th class="manage_column" >Periodo</th>
                        <th class="manage_column" >Total</th>
                        <th class="manage_column" >Team</th>
                        <th class="manage_column" >Personal</th>
                        <th class="manage_column" >Registros</th>
                      </tr>
                    </thead>

                    <tbody>

                    </tbody>
                  </table>
                  <br>
                  <p>Notas: <br>
                    <ol>
                      <li>La columna Personal muestra la diferencia entre Total y Personal.</li>
                        <li>Esta tabla historica mensual en la columna personal no tiene en cuenta los registros cerrados o abiertos para la diferencia (Personal mes actual - Personal mes inmediato anterior) .</li>
                    </ol>
                  </p> -->
                  <br>
                  <br>
                <?php

                } ?>
                <br>
                <br>
                <h4 class="mb-5">Historial  de retiros:</h4>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-referralretirosnft">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >USD Val</th>
                      <th class="manage_column" >Total USD</th>
                      <th class="manage_column" >Fecha</th>
                      <th class="manage_column" >Acciones</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
                <br>

                <div class="modal fade modal-ui" id="modal-editregnft" tabindex="-1" aria-labelledby="modal-editregnft" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-editregnftLabel">Editar registro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-editregnft" class="form-editregnft" action="#" method="post">
                          <input type="hidden" id="rnft_eid" name="rnft_eid" value="">
                          <div class="campo">
                            <label for="rnft_eagno">*Año: </label>
                            <input id="rnft_eagno" type="text" name="rnft_eagno" value="" data-inputmask="'mask':'9{4}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="rnft_emes">*Mes: </label>
                            <input id="rnft_emes" type="number" name="rnft_emes" value="" min="1" max="12"  required>
                          </div>
                          <?php if ($registros[0]['nft_tipo'] == 0){ ?>
                            <div class="campo">
                              <label for="rnft_esemana">*Semana: </label>
                              <select id="rnft_esemana" class="rnft_esemana" name="rnft_esemana" disabled required>
                                <option value="0" selected>Mensual</option>
                                <option value="1">Semana 1</option>
                                <option value="2">Semana 2</option>
                                <option value="3">Semana 3</option>
                                <option value="4">Semana 4</option>
                                <option value="5">Semana 5</option>
                              </select>
                            </div>
                          <?php }else {?>
                            <div class="campo">
                              <label for="rnft_esemana">*Semana: </label>
                              <select id="rnft_esemana" class="rnft_esemana" name="rnft_esemana" required>
                                <option value="1">Semana 1</option>
                                <option value="2">Semana 2</option>
                                <option value="3">Semana 3</option>
                                <option value="4">Semana 4</option>
                                <option value="5">Semana 5</option>
                              </select>
                            </div>
                          <?php } ?>
                          <div class="campo">
                            <label for="rnft_etotal">*Total: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rnft_etotal" class="form-control" type="text" name="rnft_etotal" value="" data-inputmask="'mask':'9{1,20}.{0,1}9{0,4}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="rnft_eteam">*Team: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rnft_eteam" class="form-control" type="text" name="rnft_eteam" value="" data-inputmask="'mask':'9{1,20}.{0,1}9{0,4}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo campo-nft-status">
                            <label for="rnft_estatus">*Status: </label>
                            <div class="input-group mb-3">
                              <div class="form-check">
                                <input class="form-check-input" type="radio" name="rnft_estatus" id="rnft_status_abie">
                                <label class="form-check-label" for="rnft_status_abie">
                                  Abierto
                                </label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="radio" name="rnft_estatus" id="rnft_status_cerr">
                                <label class="form-check-label" for="rnft_status_cerr">
                                  Cerrado
                                </label>
                              </div>

                            </div>
                          </div>
                          <div class="campo campo-notas">
                            <label for="rnft_enotas">Notas: </label>
                            <textarea name="rnft_enotas" id="rnft_enotas" rows="5" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="editarregnft" type="submit" name="editarregnft" class="button button-primary" value="Editar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>
              </div>
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_adminrefvercuentaspe(){
    $url = get_site_url();
    $year = date("Y");
    $month = date("m");
    global $wpdb;
    $cbl_id = (int) $_GET['id'];
    $cuentas = $wpdb->prefix . 'cuentas_bl';
    $usuarios = $wpdb->prefix . 'usuarios_bl';
    $registrosbl = $wpdb->prefix . 'registros_bl';
    $proyectosbl = $wpdb->prefix . 'projects_bl';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $cuentas INNER JOIN $usuarios ON $cuentas.cbl_usuario = $usuarios.ubl_id WHERE cbl_id = $cbl_id AND cbl_status = 1 LIMIT 1", ARRAY_A);
    $cbl_nombre = $registros[0]['cbl_nombre'];
    $ubl_nombre = $registros[0]['ubl_nombre']." ".$registros[0]['ubl_apellidos'];
    $cbl_numero = $registros[0]['cbl_numero'];
    $pbl_id = $registros[0]['ubl_project'];
    if ($cbl_numero == null) {
      $cbl_numero = "0000";
    }
    $ubl_id = $registros[0]['ubl_id'];
    $proyecto = $wpdb->get_results(" SELECT * FROM $proyectosbl WHERE pbl_id = $pbl_id AND pbl_status = 1 ", ARRAY_A);
    $registros1 = $wpdb->get_results(" SELECT * FROM $registrosbl WHERE rbl_cuenta = $cbl_id AND rbl_status = 1 ORDER BY rbl_year, rbl_mes", ARRAY_A);
    if (count($proyecto) != 0) {
      $comandres = (float)$proyecto[0]['pbl_comandres'];
      $comtiger = (float)$proyecto[0]['pbl_comtiger'];
    }else{
      $comandres = 0;
      $comtiger = 0;
    }

    $user = wp_get_current_user();
    $usernombre = $user->user_firstname;
    $usermail = $user->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }

    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">
          <!-- MENU RESPONSIVE -->
          <nav class="navbar navbar-dark menu-responsive">
            <div class="container-fluid">
              <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#hamburguer-principal"
                aria-controls="hamburguer-principal" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
              </button>
              <img class="img-fluid logo-menu" src="<?php echo plugin_dir_url( __DIR__ )."assets/img/icon-inproyect.png" ?>" alt="">
            </div>
          </nav>
          <div class="collapse" id="hamburguer-principal">
            <div class="shadow-3 d-flex flex-column shadow-3 ">
              <button class="btn btn-link ham-primario btn-block border-bottom m-0 collapsed" data-bs-toggle="collapse" data-bs-target="#hamburguer1" aria-expanded="false">Control de Inversiones</button>
              <div class="collapse" id="hamburguer1" style="">
                <ul class="btn-toggle-nav list-unstyled hamburguer-sublista fw-normal pb-1 small">
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  menu-activo">Control de Depósitos</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                </ul>
              </div>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Investment Referral Com</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">NFT Projects</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Ingresos varios</button></a>
              <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Usuarios</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Wordfence Bloqueos</button></a>
              <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Salir</button></a>
            </div>
          </div>

          <div class="col-0 col-md-3 d-none d-md-block mt-3 menu-escritorio">

            <!-- MENU DESKTOP -->
            <div class="menu-redesign">
              <img src="<?php echo plugin_dir_url( __DIR__ )."assets/img/logo_theinc.png" ?>" class="menu-logo img-fluid" alt="">
              <div class="menu-info">
                <img src="<?php echo $fotourl ?>" class="menu-foto img-fluid" alt="">
                <h3 class="menu-nombre"><?php echo $usernombre; ?></h3>
                <h3 class="menu-email"><?php echo $usermail; ?></h3>
              </div>
              <p class="menu-titulo">MENU</p>
              <ul class="list-unstyled menu-lista ps-0">
                <li class="">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    Control de Inversiones
                  </button>
                  <div class="collapse" id="home-collapse" style="">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  ">Control de Depósitos</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                    </ul>
                  </div>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed boton-activo" >
                    Investment Referral Com
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    NFT Projects
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    Ingresos Varios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Usuarios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Wordfence Bloqueos
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Salir
                  </button>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-9 antimenu-dashboard" >

            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1><i class="fa-solid fa-star star-amarilla"></i><?php echo $cbl_nombre." - ".$cbl_numero." - ".$ubl_nombre; ?></h1>
              </div>
              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-4">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_dashboardspe&p=".$pbl_id ?>" class="volverref" ><span class="material-icons">keyboard_backspace</span>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                    <button class="btn-editcuentabl" type="button" data-bs-toggle="modal" data-bs-target="#modal-editcuentabl"><i class="fa-solid fa-pencil"></i>Editar cuenta</button>
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_vertotalcuentasspebl&id=".$ubl_id ?>"><button class="btn-vertodascuentas" ><i class="fa-solid fa-file-invoice-dollar"></i>Reporte Total de Cuentas</button></a>
                    <?php
                    if(count($registros1) == 0){ ?>
                    <button class="btn-deletecuentabl" data-id="<?php echo $cbl_id; ?>" type="button" ><i class="fa-solid fa-x" ></i>Eliminar</button>
                    <?php } ?>
                    <button class="btn-addregnbl" type="button" data-bs-toggle="modal" data-bs-target="#modal-addregnbl"><i class="fa-solid fa-file-circle-plus"></i>Agregar registro</button>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-editcuentabl" tabindex="-1" aria-labelledby="modal-editcuentablLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-4" id="modal-editcuentablLabel">Editar cuenta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-editcuentabl" class="form-editcuentabl" action="#" method="post">
                          <input type="hidden" id="cbl_eid" name="cbl_eid" value="<?php echo $registros[0]['cbl_id'] ?>">
                          <div class="campo">
                            <label for="cbl_enombre">*Nombre de la cuenta: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="cbl_enombre" class="form-control" type="text" name="cbl_enombre" value="<?php echo $registros[0]['cbl_nombre'] ?>" required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="cbl_enumero">*Número de cuenta: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="cbl_enumero" class="form-control" type="text" name="cbl_enumero" value="<?php echo $registros[0]['cbl_numero'] ?>" required >
                            </div>
                          </div>
                          <div class="campo campo-notas">
                            <label for="cbl_enotas">Notas: </label>
                            <textarea name="cbl_enotas" id="cbl_enotas" rows="5" style="resize: none;" ><?php echo $registros[0]['cbl_notas'] ?></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="editarcuentabl" type="submit" name="editarcuenta" class="button button-primary" value="Editar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-addregnbl" tabindex="-1" aria-labelledby="modal-addregnblLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-addregnblLabel">Agregar registro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-addregnsbl" class="form-addregnsbl" action="#" method="post">
                          <input type="hidden" id="rbl_cid" name="rbl_cid" value="<?php echo $registros[0]['cbl_id'] ?>">
                          <div class="campo">
                            <label for="rbl_mes">Mes: </label>
                            <input id="rbl_mes" type="number" name="rbl_mes" value="<?php echo $month; ?>" min="1" max="12"  required>
                          </div>
                          <div class="campo">
                            <label for="rbl_agno">Año: </label>
                            <input id="rbl_agno" type="text" name="rbl_agno" value="<?php echo $year; ?>" data-inputmask="'mask':'9{4}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="rbl_utilidad">*Utilidad mensual: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rbl_utilidad" class="form-control" type="text" name="rbl_utilidad" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="rbl_combro">*Commision Broker: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rbl_combro" class="form-control" type="text" name="rbl_combro" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="rbl_comtra">*Commision Trader: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rbl_comtra" class="form-control" type="text" name="rbl_comtra" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="rbl_salini">*Saldo inicial: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rbl_salini" class="form-control" type="text" name="rbl_salini" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo campo-notas">
                            <label for="rbl_notas">Notas: </label>
                            <textarea name="rbl_notas" id="rbl_notas" rows="5" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="agregarregnbl" type="submit" name="agregarregn" class="button button-primary" value="Agregar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

              </div>

              <div class="box-transparent mb-3">
                <h4 class="mb-5">Registros mensuales de la cuenta:</h4>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-referralcuentas">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Año</th>
                      <th class="manage_column" >Mes</th>
                      <th class="manage_column" >Utilidad</th>
                      <th class="manage_column" >Com Broker</th>
                      <th class="manage_column" >Com Trader</th>
                      <th class="manage_column" >% Com Trader</th>
                      <th class="manage_column" >Util After</th>
                      <th class="manage_column" >Saldo Inicial</th>
                      <th class="manage_column" >% Util Real Final</th>
                      <th class="manage_column" >Com Tiger</th>
                      <th class="manage_column" >Com Andrés</th>
                      <th class="manage_column" >Acciones</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
                <br>
                <br>
                <p>Notas: <br>
                  <ol>
                    <li> Com Andrés equivale a un <?php echo $comandres; ?>% de la utilidad mensual.</li>
                    <li> Com Tiger equivale a un <?php echo $comtiger; ?>% de la utilidad mensual.</li>
                  </ol>
                </p>

                <div class="modal fade modal-ui" id="modal-editregnbl" tabindex="-1" aria-labelledby="modal-editregnblLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-editregnblLabel">Editar registro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-editregnsbl" class="form-editregnsbl" action="#" method="post">
                          <input type="hidden" id="rbl_eid" name="rbl_eid" value="">
                          <div class="campo">
                            <label for="rbl_emes">Mes: </label>
                            <input id="rbl_emes" type="number" name="rbl_emes" value="<?php echo $month; ?>" min="1" max="12"  required>
                          </div>
                          <div class="campo">
                            <label for="rbl_eagno">Año: </label>
                            <input id="rbl_eagno" type="text" name="rbl_eagno" value="<?php echo $year; ?>" data-inputmask="'mask':'9{4}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="rbl_eutilidad">*Utilidad mensual: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rbl_eutilidad" class="form-control" type="text" name="rbl_eutilidad" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="rbl_ecomtra">*Commision Broker: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rbl_ecombro" class="form-control" type="text" name="rbl_ecombro" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="rbl_ecomtra">*Commision Trader: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rbl_ecomtra" class="form-control" type="text" name="rbl_ecomtra" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo">
                            <label for="rbl_esalini">*Saldo inicial: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rbl_esalini" class="form-control" type="text" name="rbl_esalini" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo campo-notas">
                            <label for="rbl_enotas">Notas: </label>
                            <textarea name="rbl_enotas" id="rbl_enotas" rows="5" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="editarregnbl" type="submit" name="editarregn" class="button button-primary" value="Editar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>
              </div>
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_adminreftotalcuentas(){
    $url = get_site_url();
    $year = date("Y");
    $month = date("m");
    global $wpdb;
    $ubl_id = (int) $_GET['id'];
    $cuentas = $wpdb->prefix . 'cuentas_bl';
    $usuarios = $wpdb->prefix . 'usuarios_bl';
    $registrosbl = $wpdb->prefix . 'registros_bl';
    $proyectosbl = $wpdb->prefix . 'projects_bl';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $usuarios WHERE ubl_id = $ubl_id ", ARRAY_A);
    // $cbl_nombre = $registros[0]['cbl_nombre'];
    $ubl_nombre = $registros[0]['ubl_nombre']." ".$registros[0]['ubl_apellidos'];
    $pbl_id = $registros[0]['ubl_project'];
    $proyecto = $wpdb->get_results(" SELECT * FROM $proyectosbl WHERE pbl_id = $pbl_id AND pbl_status = 1 ", ARRAY_A);
    if (count($proyecto) != 0) {
      $comision = (float)$proyecto[0]['pbl_comision'];
    }else{
      $comision = 0;
    }
    $user = wp_get_current_user();
    $usernombre = $user->user_firstname;
    $usermail = $user->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // $registros2 = $wpdb->get_results(" SELECT ubl_id, rbl_mes, rbl_year, ROUND(SUM(rbl_utilmes), 2) AS rbl_utilmes_total, ROUND(SUM(rbl_combro), 2) AS rbl_combro_total FROM $registrosbl INNER JOIN $cuentas ON $registrosbl.rbl_cuenta = $cuentas.cbl_id INNER JOIN $usuarios ON $cuentas.cbl_usuario = $usuarios.ubl_id WHERE ubl_id = 2 GROUP BY rbl_mes, rbl_year ORDER BY rbl_year, rbl_mes", ARRAY_A);
    // echo "<pre>";
    // var_dump($registros2);
    // echo "</pre>";
    // $registros1 = $wpdb->get_results(" SELECT * FROM $registrosbl WHERE rbl_cuenta = $cbl_id AND rbl_status = 1 ORDER BY rbl_year, rbl_mes", ARRAY_A);
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">
          <!-- MENU RESPONSIVE -->
          <nav class="navbar navbar-dark menu-responsive">
            <div class="container-fluid">
              <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#hamburguer-principal"
                aria-controls="hamburguer-principal" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
              </button>
              <img class="img-fluid logo-menu" src="<?php echo plugin_dir_url( __DIR__ )."assets/img/icon-inproyect.png" ?>" alt="">
            </div>
          </nav>
          <div class="collapse" id="hamburguer-principal">
            <div class="shadow-3 d-flex flex-column shadow-3 ">
              <button class="btn btn-link ham-primario btn-block border-bottom m-0 collapsed" data-bs-toggle="collapse" data-bs-target="#hamburguer1" aria-expanded="false">Control de Inversiones</button>
              <div class="collapse" id="hamburguer1" style="">
                <ul class="btn-toggle-nav list-unstyled hamburguer-sublista fw-normal pb-1 small">
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  menu-activo">Control de Depósitos</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                </ul>
              </div>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Investment Referral Com</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">NFT Projects</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Ingresos varios</button></a>
              <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Usuarios</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Wordfence Bloqueos</button></a>
              <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Salir</button></a>
            </div>
          </div>

          <div class="col-0 col-md-3 d-none d-md-block mt-3 menu-escritorio">

            <!-- MENU DESKTOP -->
            <div class="menu-redesign">
              <img src="<?php echo plugin_dir_url( __DIR__ )."assets/img/logo_theinc.png" ?>" class="menu-logo img-fluid" alt="">
              <div class="menu-info">
                <img src="<?php echo $fotourl ?>" class="menu-foto img-fluid" alt="">
                <h3 class="menu-nombre"><?php echo $usernombre; ?></h3>
                <h3 class="menu-email"><?php echo $usermail; ?></h3>
              </div>
              <p class="menu-titulo">MENU</p>
              <ul class="list-unstyled menu-lista ps-0">
                <li class="">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    Control de Inversiones
                  </button>
                  <div class="collapse" id="home-collapse" style="">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  ">Control de Depósitos</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                    </ul>
                  </div>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed boton-activo" >
                    Investment Referral Com
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    NFT Projects
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    Ingresos Varios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Usuarios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Wordfence Bloqueos
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Salir
                  </button>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-9 antimenu-dashboard" >

            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Reporte Total de Cuentas - <?php echo $ubl_nombre; ?></h1>
              </div>
              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-4">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_dashboard&p=".$pbl_id ?>" class="volverref" ><span class="material-icons">keyboard_backspace</span>Volver al Dashboard</a>
                  </div>
                  <div class="">

                  </div>
                </div>

              </div>

              <div class="box-transparent mb-3">
                <h4 class="mb-5">Registros mensuales totales del usuario:</h4>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-referraltotalcuentasn">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Periodo</th>
                      <th class="manage_column" >Utilidad Mes</th>
                      <th class="manage_column" >Com Broker</th>
                      <th class="manage_column" >Utilidad Real</th>
                      <th class="manage_column" >Comisión <?php echo $comision; ?>%</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
                <br>
                <br>
                <p>Notas: <br>
                  <ol>
                    <li>El Profit equivale al <?php echo $comision; ?>% del valor de la Utilidad Real.</li>
                    <li>Las cantidades representadas en cada columna equivalen al total de los registros de todas las cuentas del usuario por mes.</li>
                  </ol>
                </p>

              </div>
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_adminreftotalcuentasspe(){
    $url = get_site_url();
    $year = date("Y");
    $month = date("m");
    global $wpdb;
    $ubl_id = (int) $_GET['id'];
    $cuentas = $wpdb->prefix . 'cuentas_bl';
    $usuarios = $wpdb->prefix . 'usuarios_bl';
    $registrosbl = $wpdb->prefix . 'registros_bl';
    $proyectosbl = $wpdb->prefix . 'projects_bl';
    $ruta = get_site_url();
    $registros = $wpdb->get_results(" SELECT * FROM $usuarios WHERE ubl_id = $ubl_id ", ARRAY_A);
    // $cbl_nombre = $registros[0]['cbl_nombre'];
    $ubl_nombre = $registros[0]['ubl_nombre']." ".$registros[0]['ubl_apellidos'];
    $pbl_id = $registros[0]['ubl_project'];
    $proyecto = $wpdb->get_results(" SELECT * FROM $proyectosbl WHERE pbl_id = $pbl_id AND pbl_status = 1 ", ARRAY_A);
    if (count($proyecto) != 0) {
      $comandres = (float)$proyecto[0]['pbl_comandres'];
      $comtiger = (float)$proyecto[0]['pbl_comtiger'];
    }else{
      $comandres = 0;
      $comtiger = 0;
    }

    $user = wp_get_current_user();
    $usernombre = $user->user_firstname;
    $usermail = $user->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // $registros2 = $wpdb->get_results(" SELECT ubl_id, rbl_mes, rbl_year, ROUND(SUM(rbl_utilmes), 2) AS rbl_utilmes_total, ROUND(SUM(rbl_combro), 2) AS rbl_combro_total FROM $registrosbl INNER JOIN $cuentas ON $registrosbl.rbl_cuenta = $cuentas.cbl_id INNER JOIN $usuarios ON $cuentas.cbl_usuario = $usuarios.ubl_id WHERE ubl_id = 2 GROUP BY rbl_mes, rbl_year ORDER BY rbl_year, rbl_mes", ARRAY_A);
    // echo "<pre>";
    // var_dump($registros2);
    // echo "</pre>";
    // $registros1 = $wpdb->get_results(" SELECT * FROM $registrosbl WHERE rbl_cuenta = $cbl_id AND rbl_status = 1 ORDER BY rbl_year, rbl_mes", ARRAY_A);
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">
          <!-- MENU RESPONSIVE -->
          <nav class="navbar navbar-dark menu-responsive">
            <div class="container-fluid">
              <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#hamburguer-principal"
                aria-controls="hamburguer-principal" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
              </button>
              <img class="img-fluid logo-menu" src="<?php echo plugin_dir_url( __DIR__ )."assets/img/icon-inproyect.png" ?>" alt="">
            </div>
          </nav>
          <div class="collapse" id="hamburguer-principal">
            <div class="shadow-3 d-flex flex-column shadow-3 ">
              <button class="btn btn-link ham-primario btn-block border-bottom m-0 collapsed" data-bs-toggle="collapse" data-bs-target="#hamburguer1" aria-expanded="false">Control de Inversiones</button>
              <div class="collapse" id="hamburguer1" style="">
                <ul class="btn-toggle-nav list-unstyled hamburguer-sublista fw-normal pb-1 small">
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  menu-activo">Control de Depósitos</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                </ul>
              </div>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Investment Referral Com</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">NFT Projects</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Ingresos varios</button></a>
              <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Usuarios</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Wordfence Bloqueos</button></a>
              <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Salir</button></a>
            </div>
          </div>

          <div class="col-0 col-md-3 d-none d-md-block mt-3 menu-escritorio">

            <!-- MENU DESKTOP -->
            <div class="menu-redesign">
              <img src="<?php echo plugin_dir_url( __DIR__ )."assets/img/logo_theinc.png" ?>" class="menu-logo img-fluid" alt="">
              <div class="menu-info">
                <img src="<?php echo $fotourl ?>" class="menu-foto img-fluid" alt="">
                <h3 class="menu-nombre"><?php echo $usernombre; ?></h3>
                <h3 class="menu-email"><?php echo $usermail; ?></h3>
              </div>
              <p class="menu-titulo">MENU</p>
              <ul class="list-unstyled menu-lista ps-0">
                <li class="">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    Control de Inversiones
                  </button>
                  <div class="collapse" id="home-collapse" style="">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  ">Control de Depósitos</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                    </ul>
                  </div>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed boton-activo" >
                    Investment Referral Com
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    NFT Projects
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    Ingresos Varios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Usuarios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Wordfence Bloqueos
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Salir
                  </button>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-9 antimenu-dashboard" >

            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1><i class="fa-solid fa-star star-amarilla"></i>Reporte Total de Cuentas - <?php echo $ubl_nombre; ?></h1>
              </div>
              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-4">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_dashboardspe&p=".$pbl_id ?>" class="volverref" ><span class="material-icons">keyboard_backspace</span>Volver al Dashboard</a>
                  </div>
                  <div class="">

                  </div>
                </div>

              </div>

              <div class="box-transparent mb-3">
                <h4 class="mb-5">Registros mensuales totales del usuario:</h4>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-referraltotalcuentass">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Periodo</th>
                      <th class="manage_column" >Utilidad</th>
                      <th class="manage_column" >Com Broker</th>
                      <th class="manage_column" >Com Trader</th>
                      <th class="manage_column" >% Com Trader</th>
                      <th class="manage_column" >Util After</th>
                      <th class="manage_column" >Saldo Inicial</th>
                      <th class="manage_column" >% Util Real Final</th>
                      <th class="manage_column" >Com Tiger</th>
                      <th class="manage_column" >Com Andrés</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
                <br>
                <br>
                <p>Notas: <br>
                  <ol>
                    <li>Com Andres equivale a un <?php echo $comandres ?>% de la utilidad mensual.</li>
                    <li>Com Tiger equivale a un <?php echo $comtiger ?>% de la utilidad mensual.</li>
                  </ol>
                </p>
              </div>
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_adminrefdetallemes(){
    $url = get_site_url();
    // $year = date("Y");
    // $month = date("m");
    global $wpdb;
    $ubl_id = (int) $_GET['id'];
    $rbl_year = (int) $_GET['y'];
    $rbl_mes = (int) $_GET['m'];
    $cuentas = $wpdb->prefix . 'cuentas_bl';
    $usuarios = $wpdb->prefix . 'usuarios_bl';
    $registrosbl = $wpdb->prefix . 'registros_bl';
    $projectsbl = $wpdb->prefix . 'projects_bl';
    $ruta = get_site_url();
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );
    $tmes = $mesesNombre[$rbl_mes];
    $registros = $wpdb->get_results(" SELECT * FROM $registrosbl INNER JOIN $cuentas ON $registrosbl.rbl_cuenta = $cuentas.cbl_id INNER JOIN $usuarios ON $cuentas.cbl_usuario = $usuarios.ubl_id INNER JOIN $projectsbl ON $usuarios.ubl_project = $projectsbl.pbl_id WHERE ubl_id = $ubl_id AND rbl_year = $rbl_year AND rbl_mes = $rbl_mes ORDER BY rbl_id ", ARRAY_A);
    // $cbl_nombre = $registros[0]['cbl_nombre'];
    $ubl_nombre = $registros[0]['ubl_nombre']." ".$registros[0]['ubl_apellidos'];

    $comtotal = 0;
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
      $comtotal = $comtotal+$comfinal;
    }
    $tcomtotal  = "$".number_format($comtotal, 2);

    $user = wp_get_current_user();
    $usernombre = $user->user_firstname;
    $usermail = $user->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // $registros2 = $wpdb->get_results(" SELECT ubl_id, rbl_mes, rbl_year, ROUND(SUM(rbl_utilmes), 2) AS rbl_utilmes_total, ROUND(SUM(rbl_combro), 2) AS rbl_combro_total FROM $registrosbl INNER JOIN $cuentas ON $registrosbl.rbl_cuenta = $cuentas.cbl_id INNER JOIN $usuarios ON $cuentas.cbl_usuario = $usuarios.ubl_id WHERE ubl_id = 2 GROUP BY rbl_mes, rbl_year ORDER BY rbl_year, rbl_mes", ARRAY_A);
    // echo "<pre>";
    // var_dump($registros);
    // echo "</pre>";
    // $registros1 = $wpdb->get_results(" SELECT * FROM $registrosbl WHERE rbl_cuenta = $cbl_id AND rbl_status = 1 ORDER BY rbl_year, rbl_mes", ARRAY_A);
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">
          <!-- MENU RESPONSIVE -->
          <nav class="navbar navbar-dark menu-responsive">
            <div class="container-fluid">
              <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#hamburguer-principal"
                aria-controls="hamburguer-principal" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
              </button>
              <img class="img-fluid logo-menu" src="<?php echo plugin_dir_url( __DIR__ )."assets/img/icon-inproyect.png" ?>" alt="">
            </div>
          </nav>
          <div class="collapse" id="hamburguer-principal">
            <div class="shadow-3 d-flex flex-column shadow-3 ">
              <button class="btn btn-link ham-primario btn-block border-bottom m-0 collapsed" data-bs-toggle="collapse" data-bs-target="#hamburguer1" aria-expanded="false">Control de Inversiones</button>
              <div class="collapse" id="hamburguer1" style="">
                <ul class="btn-toggle-nav list-unstyled hamburguer-sublista fw-normal pb-1 small">
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  menu-activo">Control de Depósitos</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                </ul>
              </div>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Investment Referral Com</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">NFT Projects</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Ingresos varios</button></a>
              <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Usuarios</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Wordfence Bloqueos</button></a>
              <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Salir</button></a>
            </div>
          </div>

          <div class="col-0 col-md-3 d-none d-md-block mt-3 menu-escritorio">

            <!-- MENU DESKTOP -->
            <div class="menu-redesign">
              <img src="<?php echo plugin_dir_url( __DIR__ )."assets/img/logo_theinc.png" ?>" class="menu-logo img-fluid" alt="">
              <div class="menu-info">
                <img src="<?php echo $fotourl ?>" class="menu-foto img-fluid" alt="">
                <h3 class="menu-nombre"><?php echo $usernombre; ?></h3>
                <h3 class="menu-email"><?php echo $usermail; ?></h3>
              </div>
              <p class="menu-titulo">MENU</p>
              <ul class="list-unstyled menu-lista ps-0">
                <li class="">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    Control de Inversiones
                  </button>
                  <div class="collapse" id="home-collapse" style="">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  ">Control de Depósitos</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                    </ul>
                  </div>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed boton-activo" >
                    Investment Referral Com
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    NFT Projects
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    Ingresos Varios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Usuarios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Wordfence Bloqueos
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Salir
                  </button>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-9 antimenu-dashboard" >

            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Detalle <?php echo $tmes." ".$rbl_year ?> - <?php echo $ubl_nombre; ?></h1>
              </div>
              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-4">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_vertotalcuentasbl&id=".$ubl_id ?>" class="volverref" ><span class="material-icons">keyboard_backspace</span>Volver al Reporte Total de Cuentas</a>
                  </div>
                  <div class="">

                  </div>
                </div>

              </div>

              <div class="box-transparent mb-3">
                <h4 class="mb-5">Detalle de cuentas <?php echo $tmes." ".$rbl_year ?> :</h4>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-referraldetallemes">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Cuenta</th>
                      <th class="manage_column" >Comisión <?php echo $comision ?>%</th>
                      <th class="manage_column" >Utilidad Mes</th>
                      <th class="manage_column" >Com Broker</th>
                      <th class="manage_column" >Utilidad Real</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
                <br>
                <p class="total-detalle"><i class="fa-solid fa-coins"></i> Total Comm :<span><?php echo $tcomtotal; ?></span></p>
                <br>
                <br>
                <p>Notas: <br>
                  <ol>
                    <li>La Comisión equivale al <?php echo $comision ?>% del valor de la Utilidad Real.</li>
                    <li>Las cantidades representadas en cada columna equivalen al total de los registros de todas las cuentas del usuario por mes.</li>
                  </ol>
                </p>

              </div>
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_adminrefdetallemesspe(){
    $url = get_site_url();
    // $year = date("Y");
    // $month = date("m");
    global $wpdb;
    $ubl_id = (int) $_GET['id'];
    $rbl_year = (int) $_GET['y'];
    $rbl_mes = (int) $_GET['m'];
    $cuentas = $wpdb->prefix . 'cuentas_bl';
    $usuarios = $wpdb->prefix . 'usuarios_bl';
    $registrosbl = $wpdb->prefix . 'registros_bl';
    $projectsbl = $wpdb->prefix . 'projects_bl';
    $ruta = get_site_url();
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );
    $tmes = $mesesNombre[$rbl_mes];
    $registros = $wpdb->get_results(" SELECT * FROM $registrosbl INNER JOIN $cuentas ON $registrosbl.rbl_cuenta = $cuentas.cbl_id INNER JOIN $usuarios ON $cuentas.cbl_usuario = $usuarios.ubl_id INNER JOIN $projectsbl ON $usuarios.ubl_project = $projectsbl.pbl_id WHERE ubl_id = $ubl_id AND rbl_year = $rbl_year AND rbl_mes = $rbl_mes ORDER BY rbl_id ", ARRAY_A);
    // $cbl_nombre = $registros[0]['cbl_nombre'];
    $ubl_nombre = $registros[0]['ubl_nombre']." ".$registros[0]['ubl_apellidos'];
    $pbl_id = $registros[0]['ubl_project'];
    $proyecto = $wpdb->get_results(" SELECT * FROM $projectsbl WHERE pbl_id = $pbl_id AND pbl_status = 1 ", ARRAY_A);
    if (count($proyecto) != 0) {
      $comandres1 = (float)$proyecto[0]['pbl_comandres'];
      $comtiger1 = (float)$proyecto[0]['pbl_comtiger'];
    }else{
      $comandres1 = 0;
      $comtiger1 = 0;
    }
    $comtotal = 0;
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

      $utilrealfinpor = round(($utilafter*100)/$salini,2);
      $comandres = round(($utilmesfinal*($comandresent/100)),2);
      $comtiger = round(($utilmesfinal*($comtigerent/100)),2);

      $comtotal = $comtotal+$comandres;
    }
    $tcomtotal  = "$".number_format($comtotal, 2);

    $user = wp_get_current_user();
    $usernombre = $user->user_firstname;
    $usermail = $user->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // $registros2 = $wpdb->get_results(" SELECT ubl_id, rbl_mes, rbl_year, ROUND(SUM(rbl_utilmes), 2) AS rbl_utilmes_total, ROUND(SUM(rbl_combro), 2) AS rbl_combro_total FROM $registrosbl INNER JOIN $cuentas ON $registrosbl.rbl_cuenta = $cuentas.cbl_id INNER JOIN $usuarios ON $cuentas.cbl_usuario = $usuarios.ubl_id WHERE ubl_id = 2 GROUP BY rbl_mes, rbl_year ORDER BY rbl_year, rbl_mes", ARRAY_A);
    // echo "<pre>";
    // var_dump($registros);
    // echo "</pre>";
    // $registros1 = $wpdb->get_results(" SELECT * FROM $registrosbl WHERE rbl_cuenta = $cbl_id AND rbl_status = 1 ORDER BY rbl_year, rbl_mes", ARRAY_A);
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">
          <!-- MENU RESPONSIVE -->
          <nav class="navbar navbar-dark menu-responsive">
            <div class="container-fluid">
              <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#hamburguer-principal"
                aria-controls="hamburguer-principal" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
              </button>
              <img class="img-fluid logo-menu" src="<?php echo plugin_dir_url( __DIR__ )."assets/img/icon-inproyect.png" ?>" alt="">
            </div>
          </nav>
          <div class="collapse" id="hamburguer-principal">
            <div class="shadow-3 d-flex flex-column shadow-3 ">
              <button class="btn btn-link ham-primario btn-block border-bottom m-0 collapsed" data-bs-toggle="collapse" data-bs-target="#hamburguer1" aria-expanded="false">Control de Inversiones</button>
              <div class="collapse" id="hamburguer1" style="">
                <ul class="btn-toggle-nav list-unstyled hamburguer-sublista fw-normal pb-1 small">
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  menu-activo">Control de Depósitos</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                </ul>
              </div>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Investment Referral Com</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">NFT Projects</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Ingresos varios</button></a>
              <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Usuarios</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Wordfence Bloqueos</button></a>
              <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Salir</button></a>
            </div>
          </div>

          <div class="col-0 col-md-3 d-none d-md-block mt-3 menu-escritorio">

            <!-- MENU DESKTOP -->
            <div class="menu-redesign">
              <img src="<?php echo plugin_dir_url( __DIR__ )."assets/img/logo_theinc.png" ?>" class="menu-logo img-fluid" alt="">
              <div class="menu-info">
                <img src="<?php echo $fotourl ?>" class="menu-foto img-fluid" alt="">
                <h3 class="menu-nombre"><?php echo $usernombre; ?></h3>
                <h3 class="menu-email"><?php echo $usermail; ?></h3>
              </div>
              <p class="menu-titulo">MENU</p>
              <ul class="list-unstyled menu-lista ps-0">
                <li class="">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    Control de Inversiones
                  </button>
                  <div class="collapse" id="home-collapse" style="">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  ">Control de Depósitos</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                    </ul>
                  </div>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed boton-activo" >
                    Investment Referral Com
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    NFT Projects
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
                    Ingresos Varios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Usuarios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Wordfence Bloqueos
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Salir
                  </button>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-9 antimenu-dashboard" >

            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1><i class="fa-solid fa-star star-amarilla"></i>Detalle <?php echo $tmes." ".$rbl_year ?> - <?php echo $ubl_nombre; ?></h1>
              </div>
              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-4">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_vertotalcuentasspebl&id=".$ubl_id ?>" class="volverref" ><span class="material-icons">keyboard_backspace</span>Volver al Reporte Total de Cuentas</a>
                  </div>
                  <div class="">

                  </div>
                </div>

              </div>

              <div class="box-transparent mb-3">
                <h4 class="mb-5">Detalle de cuentas <?php echo $tmes." ".$rbl_year ?> :</h4>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-referraldetallemesspe">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Cuenta</th>
                      <th class="manage_column" >Com Andrés</th>
                      <th class="manage_column" >Utilidad</th>
                      <th class="manage_column" >Com Broker</th>
                      <th class="manage_column" >Com Trader</th>
                      <th class="manage_column" >% Com Trader</th>
                      <th class="manage_column" >Util After</th>
                      <th class="manage_column" >Saldo Inicial</th>
                      <th class="manage_column" >% Util Real Final</th>
                      <th class="manage_column" >Com Tiger</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
                <br>
                <p class="total-detalle"><i class="fa-solid fa-coins"></i> Total Comm :<span><?php echo $tcomtotal; ?></span></p>
                <br>
                <br>
                <p>Notas: <br>
                  <ol>
                    <li>Com Andres equivale a un <?php echo $comandres1 ?>% de la utilidad mensual.</li>
                    <li>Com Tiger equivale a un <?php echo $comtiger1 ?>% de la utilidad mensual.</li>
                  </ol>
                </p>

              </div>
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_adminrefingresosvarmes(){
    $url = get_site_url();
    // $year = date("Y");
    // $month = date("m");
    global $wpdb;
    $rvar_year = (int) $_GET['y'];
    $rvar_mes = (int) $_GET['m'];
    $registrosvar = $wpdb->prefix . 'registros_var';
    $ruta = get_site_url();
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );
    $tmes = $mesesNombre[$rvar_mes];

    $registros = $wpdb->get_results("SELECT rvar_mes, rvar_year, ROUND(SUM(rvar_cantidad), 2) AS rvar_cantidadtotal FROM $registrosvar WHERE rvar_year = $rvar_year AND rvar_mes = $rvar_mes GROUP BY rvar_year, rvar_mes ORDER BY rvar_year, rvar_mes ", ARRAY_A);
    // $cbl_nombre = $registros[0]['cbl_nombre'];

    $total  = "$".number_format($registros[0]["rvar_cantidadtotal"], 2);
    // $registros2 = $wpdb->get_results(" SELECT ubl_id, rbl_mes, rbl_year, ROUND(SUM(rbl_utilmes), 2) AS rbl_utilmes_total, ROUND(SUM(rbl_combro), 2) AS rbl_combro_total FROM $registrosbl INNER JOIN $cuentas ON $registrosbl.rbl_cuenta = $cuentas.cbl_id INNER JOIN $usuarios ON $cuentas.cbl_usuario = $usuarios.ubl_id WHERE ubl_id = 2 GROUP BY rbl_mes, rbl_year ORDER BY rbl_year, rbl_mes", ARRAY_A);
    // echo "<pre>";
    // var_dump($registros);
    // echo "</pre>";
    // $registros1 = $wpdb->get_results(" SELECT * FROM $registrosbl WHERE rbl_cuenta = $cbl_id AND rbl_status = 1 ORDER BY rbl_year, rbl_mes", ARRAY_A);
    $user = wp_get_current_user();
    $usernombre = $user->user_firstname;
    $usermail = $user->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">
          <!-- MENU RESPONSIVE -->
          <nav class="navbar navbar-dark menu-responsive">
            <div class="container-fluid">
              <button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#hamburguer-principal"
                aria-controls="hamburguer-principal" aria-expanded="false"
                aria-label="Toggle navigation">
                <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
              </button>
              <img class="img-fluid logo-menu" src="<?php echo plugin_dir_url( __DIR__ )."assets/img/icon-inproyect.png" ?>" alt="">
            </div>
          </nav>
          <div class="collapse" id="hamburguer-principal">
            <div class="shadow-3 d-flex flex-column shadow-3 ">
              <button class="btn btn-link ham-primario btn-block border-bottom m-0 collapsed" data-bs-toggle="collapse" data-bs-target="#hamburguer1" aria-expanded="false">Control de Inversiones</button>
              <div class="collapse" id="hamburguer1" style="">
                <ul class="btn-toggle-nav list-unstyled hamburguer-sublista fw-normal pb-1 small">
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  menu-activo">Control de Depósitos</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                  <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                </ul>
              </div>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Investment Referral Com</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">NFT Projects</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Ingresos varios</button></a>
              <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Usuarios</button></a>
              <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Wordfence Bloqueos</button></a>
              <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Salir</button></a>
            </div>
          </div>

          <div class="col-0 col-md-3 d-none d-md-block mt-3 menu-escritorio">

            <!-- MENU DESKTOP -->
            <div class="menu-redesign">
              <img src="<?php echo plugin_dir_url( __DIR__ )."assets/img/logo_theinc.png" ?>" class="menu-logo img-fluid" alt="">
              <div class="menu-info">
                <img src="<?php echo $fotourl ?>" class="menu-foto img-fluid" alt="">
                <h3 class="menu-nombre"><?php echo $usernombre; ?></h3>
                <h3 class="menu-email"><?php echo $usermail; ?></h3>
              </div>
              <p class="menu-titulo">MENU</p>
              <ul class="list-unstyled menu-lista ps-0">
                <li class="">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    Control de Inversiones
                  </button>
                  <div class="collapse" id="home-collapse" style="">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Lista de inversionistas</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control de Retiros</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  ">Control de Depósitos</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_master"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Control Maestro</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_depmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Depósitos Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_retmas"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Retiros Master</a></li>
                      <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_repmensual"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Reporte Mensual</a></li>
                    </ul>
                  </div>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_principal"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Investment Referral Com
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_nftprojects"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    NFT Projects
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed boton-activo" >
                    Ingresos Varios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/users.php"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
                    Usuarios
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo $url."/wp-admin/admin.php?page=WordfenceWAF#top#blocking"; ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Wordfence Bloqueos
                  </button>
                  </a>
                </li>
                <li class="">
                  <a href="<?php echo wp_logout_url(); ?>" class="collapsed text-decoration-none">
                  <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed">
                    Salir
                  </button>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-12 col-md-9 antimenu-dashboard" >

            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Ingresos <?php echo $tmes." ".$rvar_year; ?></h1>
              </div>
              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-4">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_referral_ingresosvar" ?>" class="volverref" ><span class="material-icons">keyboard_backspace</span>Volver al Dashboard</a>
                  </div>
                  <div class="">
                    <button class="btn-addregvar" id="btn-addregvar" type="button" data-bs-toggle="modal" data-bs-target="#modal-addregvar"><i class="fa-solid fa-sack-dollar"></i>Agregar ingreso</button>
                  </div>
                </div>

              </div>

              <div class="modal fade modal-ui" id="modal-addregvar" tabindex="-1" aria-labelledby="modal-addregvarLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title px-2" id="modal-addregvarLabel">Agregar ingreso</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4" >
                      <form id="form-addregvar" class="form-addregvarl" action="#" method="post">
                        <div class="campo">
                          <label for="rvar_mes">*Mes: </label>
                          <input id="rvar_mes" type="number" name="rvar_mes" value="<?php echo $rvar_mes; ?>" min="1" max="12"  required>
                        </div>
                        <div class="campo">
                          <label for="rvar_agno">*Año: </label>
                          <input id="rvar_agno" type="text" name="rvar_agno" value="<?php echo $rvar_year; ?>" data-inputmask="'mask':'9{4}'" data-mask required>
                        </div>
                        <div class="campo">
                          <label for="rvar_nombre">*Concepto: </label>
                          <div class="input-group mb-3">
                            <!-- <span class="input-group-text">$</span> -->
                            <input id="rvar_nombre" class="form-control" type="text" name="rvar_nombre" required >
                          </div>
                        </div>
                        <div class="campo">
                          <label for="rvar_cantidad">*Cantidad: </label>
                          <div class="input-group mb-3">
                            <!-- <span class="input-group-text">$</span> -->
                            <input id="rvar_cantidad" class="form-control" type="text" name="rvar_cantidad" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                          </div>
                        </div>
                        <div class="campo campo-notas">
                          <label for="rvar_notas">Notas: </label>
                          <textarea name="rvar_notas" id="rvar_notas" rows="5" style="resize: none;" ></textarea>
                        </div>
                        <div class="campo-especial">
                          <input id="agregarregvar" type="submit" name="agregarregvar" class="button button-primary" value="Agregar">
                          <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                        </div>
                      </form>

                    </div>

                  </div>
                </div>
              </div>

              <div class="modal fade modal-ui" id="modal-editregvar" tabindex="-1" aria-labelledby="modal-editregvarLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title px-2" id="modal-editregvarLabel">Editar ingreso</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4" >
                      <form id="form-editregvar" class="form-editregvarl" action="#" method="post">
                        <input type="hidden" id="rvar_eid" name="rvar_eid" value="">
                        <div class="campo">
                          <label for="rvar_emes">*Mes: </label>
                          <input id="rvar_emes" type="number" name="rvar_emes" value="" min="1" max="12"  required>
                        </div>
                        <div class="campo">
                          <label for="rvar_eagno">*Año: </label>
                          <input id="rvar_eagno" type="text" name="rvar_eagno" value="" data-inputmask="'mask':'9{4}'" data-mask required>
                        </div>
                        <div class="campo">
                          <label for="rvar_enombre">*Concepto: </label>
                          <div class="input-group mb-3">
                            <!-- <span class="input-group-text">$</span> -->
                            <input id="rvar_enombre" class="form-control" type="text" name="rvar_enombre" required >
                          </div>
                        </div>
                        <div class="campo">
                          <label for="rvar_ecantidad">*Cantidad: </label>
                          <div class="input-group mb-3">
                            <!-- <span class="input-group-text">$</span> -->
                            <input id="rvar_ecantidad" class="form-control" type="text" name="rvar_ecantidad" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                          </div>
                        </div>
                        <div class="campo campo-notas">
                          <label for="rvar_enotas">Notas: </label>
                          <textarea name="rvar_enotas" id="rvar_enotas" rows="5" style="resize: none;" ></textarea>
                        </div>
                        <div class="campo-especial">
                          <input id="editarregvar" type="submit" name="editarregvar" class="button button-primary" value="Editar">
                          <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                        </div>
                      </form>

                    </div>

                  </div>
                </div>
              </div>

              <div class="box-transparent mb-3">
                <h4 class="mb-5">Lista de ingresos <?php echo $tmes." ".$rvar_year ?> :</h4>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-referraldetallemesvar">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Concepto</th>
                      <th class="manage_column" >Registro</th>
                      <th class="manage_column" >Acciones</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
                <br>
                <p class="total-detalle"><i class="fa-solid fa-coins"></i> Total:<span><?php echo $total; ?></span></p>
                <br>
                <br>
                <p>Notas: <br>

                </p>

              </div>
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_adminrefregistrosnftmes(){
    $url = get_site_url();
    $year = date("Y");
    $month = date("m");
    global $wpdb;
    $rnft_year = (int) $_GET['y'];
    $rnft_mes = (int) $_GET['m'];
    $rnft_proyecto = (int) $_GET['p'];
    $registrosnft = $wpdb->prefix . 'registros_nft';
    $projectsnft = $wpdb->prefix . 'projects_nft';
    $ruta = get_site_url();
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );
    $tmes = $mesesNombre[$rnft_mes];

    $registros = $wpdb->get_results("SELECT * FROM $registrosnft INNER JOIN $projectsnft ON $registrosnft.rnft_proyecto = $projectsnft.nft_id  WHERE rnft_year = $rnft_year AND rnft_mes = $rnft_mes AND rnft_proyecto = $rnft_proyecto ORDER BY rnft_id ", ARRAY_A);
    // $cbl_nombre = $registros[0]['cbl_nombre'];
    // $registros2 = $wpdb->get_results(" SELECT * FROM $registrosnft WHERE rnft_proyecto = $nft_id AND rnft_status = 1 ORDER BY rnft_year, rnft_mes, rnft_semana, rnft_fecha ", ARRAY_A);
    // if(count($registros2) == 0){
    //
    //   $totact = 0;
    //   $totret = number_format($totact, 4);
    // }else{
    //   $lkey2 = sizeof($registros2) -1 ;
    //   $totact = (float)$registros2[$lkey2]['rnft_total'];
    //   $totret = number_format($totact, 4);
    // }
    $lkey2 = sizeof($registros) -1 ;
    $totact = (float)$registros[$lkey2]['rnft_total'];
    $totret = number_format($totact, 4);

    $nft_nombre = $registros[0]['nft_nombre'];

    $tipo = $registros[0]['nft_tipo'];

    $total = 0;
    foreach ($registros as $key => $value) {
      $total += $value["rnft_total"];
    }

    $ttotal  = number_format($total, 4);
    // $registros2 = $wpdb->get_results(" SELECT ubl_id, rbl_mes, rbl_year, ROUND(SUM(rbl_utilmes), 2) AS rbl_utilmes_total, ROUND(SUM(rbl_combro), 2) AS rbl_combro_total FROM $registrosbl INNER JOIN $cuentas ON $registrosbl.rbl_cuenta = $cuentas.cbl_id INNER JOIN $usuarios ON $cuentas.cbl_usuario = $usuarios.ubl_id WHERE ubl_id = 2 GROUP BY rbl_mes, rbl_year ORDER BY rbl_year, rbl_mes", ARRAY_A);
    // echo "<pre>";
    // var_dump($registros);
    // echo "</pre>";
    // $registros1 = $wpdb->get_results(" SELECT * FROM $registrosbl WHERE rbl_cuenta = $cbl_id AND rbl_status = 1 ORDER BY rbl_year, rbl_mes", ARRAY_A);
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="ui-titulo my-3">
          <h1> <?php echo $nft_nombre; ?> - <?php echo $tmes." ".$rnft_year; ?></h1>
        </div>
        <div class="ui-dashboard">
          <div class="d-grid gap-2 d-md-flex justify-content-md-between py-4">
            <div class="">
              <a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_vernftproject&id=".$rnft_proyecto ?>" class="volverref" ><span class="material-icons">keyboard_backspace</span>Volver al Proyecto NFT</a>
            </div>
            <div class="">
              <button class="btn-addregnft" id="btn-addregnft" data-tipo="<?php echo $tipo; ?>" type="button" data-bs-toggle="modal" data-bs-target="#modal-addregnft"><i class="fa-solid fa-file-circle-plus"></i>Agregar registro</button>
            </div>
          </div>

        </div>


        <div class="box-transparent mb-3">
          <h4 class="mb-5">Lista de registros <?php echo $tmes." ".$rnft_year ?> :</h4>
          <table class="wp-list-table widefat tab-ui dt-responsive striped tab-referraldetallemesnft">
            <thead>
              <tr>
                <th class="manage_column" >#</th>
                <th class="manage_column" >Total</th>
                <th class="manage_column" >Team</th>
                <th class="manage_column" >Personal</th>
                <th class="manage_column" >Acciones</th>
                <th class="manage_column" >Status</th>
              </tr>
            </thead>

            <tbody>

            </tbody>
          </table>
          <br>
          <p class="total-detalle"><i class="fa-solid fa-coins"></i> Total:<span><?php echo $totret; ?></span></p>
          <br>
          <br>
          <p>Notas: <br>

          </p>

          <div class="modal fade modal-ui" id="modal-addregnft" tabindex="-1" aria-labelledby="modal-addregnftlLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title px-2" id="modal-addregnftLabel">Agregar registro</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4" >
                  <form id="form-addregnft" class="form-addregnft" action="#" method="post">
                    <input type="hidden" id="rnft_nid" name="rnft_nid" value="<?php echo $rnft_proyecto ?>">
                    <div class="campo">
                      <label for="rnft_agno">*Año: </label>
                      <input id="rnft_agno" type="text" name="rnft_agno" value="<?php echo $year; ?>" data-inputmask="'mask':'9{4}'" data-mask required>
                    </div>
                    <div class="campo">
                      <label for="rnft_mes">*Mes: </label>
                      <input id="rnft_mes" type="number" name="rnft_mes" value="<?php echo $month; ?>" min="1" max="12"  required>
                    </div>
                    <?php if ($registros[0]['nft_tipo'] == 0){ ?>
                      <div class="campo">
                        <label for="rnft_semana">*Semana: </label>
                        <select id="rnft_semana" class="rnft_semana" name="rnft_semana" disabled required>
                          <option value="0" selected>Mensual</option>
                          <option value="1">Semana 1</option>
                          <option value="2">Semana 2</option>
                          <option value="3">Semana 3</option>
                          <option value="4">Semana 4</option>
                          <option value="5">Semana 5</option>
                        </select>
                      </div>
                    <?php }else {?>
                      <div class="campo">
                        <label for="rnft_semana">*Semana: </label>
                        <select id="rnft_semana" class="rnft_semana" name="rnft_semana" required>
                          <option value="1">Semana 1</option>
                          <option value="2">Semana 2</option>
                          <option value="3">Semana 3</option>
                          <option value="4">Semana 4</option>
                          <option value="5">Semana 5</option>
                        </select>
                      </div>
                    <?php } ?>
                    <div class="campo">
                      <label for="rnft_total">*Total: </label>
                      <div class="input-group mb-3">
                        <!-- <span class="input-group-text">$</span> -->
                        <input id="rnft_total" class="form-control" type="text" name="rnft_total" value="0.00" data-inputmask="'mask':'9{1,20}.{0,1}9{0,4}'" data-mask required >
                      </div>
                    </div>
                    <div class="campo">
                      <label for="rnft_team">*Team: </label>
                      <div class="input-group mb-3">
                        <!-- <span class="input-group-text">$</span> -->
                        <input id="rnft_team" class="form-control" type="text" name="rnft_team" value="0.00" data-inputmask="'mask':'9{1,20}.{0,1}9{0,4}'" data-mask required >
                      </div>
                    </div>
                    <div class="campo campo-notas">
                      <label for="rnft_notas">Notas: </label>
                      <textarea name="rnft_notas" id="rnft_notas" rows="5" style="resize: none;" ></textarea>
                    </div>
                    <div class="campo-especial">
                      <input id="agregarregnft" type="submit" name="agregarregnft" class="button button-primary" value="Agregar">
                      <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                    </div>
                  </form>

                </div>

              </div>
            </div>
          </div>

          <div class="modal fade modal-ui" id="modal-editregnft" tabindex="-1" aria-labelledby="modal-editregnft" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title px-2" id="modal-editregnftLabel">Editar registro</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4" >
                  <form id="form-editregnft" class="form-editregnft" action="#" method="post">
                    <input type="hidden" id="rnft_eid" name="rnft_eid" value="">
                    <div class="campo">
                      <label for="rnft_eagno">*Año: </label>
                      <input id="rnft_eagno" type="text" name="rnft_eagno" value="" data-inputmask="'mask':'9{4}'" data-mask required>
                    </div>
                    <div class="campo">
                      <label for="rnft_emes">*Mes: </label>
                      <input id="rnft_emes" type="number" name="rnft_emes" value="" min="1" max="12"  required>
                    </div>
                    <?php if ($registros[0]['nft_tipo'] == 0){ ?>
                      <div class="campo">
                        <label for="rnft_esemana">*Semana: </label>
                        <select id="rnft_esemana" class="rnft_esemana" name="rnft_esemana" disabled required>
                          <option value="0" selected>Mensual</option>
                          <option value="1">Semana 1</option>
                          <option value="2">Semana 2</option>
                          <option value="3">Semana 3</option>
                          <option value="4">Semana 4</option>
                          <option value="5">Semana 5</option>
                        </select>
                      </div>
                    <?php }else {?>
                      <div class="campo">
                        <label for="rnft_esemana">*Semana: </label>
                        <select id="rnft_esemana" class="rnft_esemana" name="rnft_esemana" required>
                          <option value="1">Semana 1</option>
                          <option value="2">Semana 2</option>
                          <option value="3">Semana 3</option>
                          <option value="4">Semana 4</option>
                          <option value="5">Semana 5</option>
                        </select>
                      </div>
                    <?php } ?>
                    <div class="campo">
                      <label for="rnft_etotal">*Total: </label>
                      <div class="input-group mb-3">
                        <!-- <span class="input-group-text">$</span> -->
                        <input id="rnft_etotal" class="form-control" type="text" name="rnft_etotal" value="" data-inputmask="'mask':'9{1,20}.{0,1}9{0,4}'" data-mask required >
                      </div>
                    </div>
                    <div class="campo">
                      <label for="rnft_eteam">*Team: </label>
                      <div class="input-group mb-3">
                        <!-- <span class="input-group-text">$</span> -->
                        <input id="rnft_eteam" class="form-control" type="text" name="rnft_eteam" value="" data-inputmask="'mask':'9{1,20}.{0,1}9{0,4}'" data-mask required >
                      </div>
                    </div>
                    <div class="campo">
                      <label for="rnft_estatus">*Status: </label>
                      <div class="input-group mb-3">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="rnft_estatus" id="rnft_status_abie">
                          <label class="form-check-label" for="rnft_status_abie">
                            Abierto
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="rnft_estatus" id="rnft_status_cerr">
                          <label class="form-check-label" for="rnft_status_cerr">
                            Cerrado
                          </label>
                        </div>

                      </div>
                    </div>
                    <div class="campo campo-notas">
                      <label for="rnft_enotas">Notas: </label>
                      <textarea name="rnft_enotas" id="rnft_enotas" rows="5" style="resize: none;" ></textarea>
                    </div>
                    <div class="campo-especial">
                      <input id="editarregnft" type="submit" name="editarregnft" class="button button-primary" value="Editar">
                      <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                    </div>
                  </form>

                </div>

              </div>
            </div>
          </div>

        </div>
        <hr>
      </div>
    </div>
    <?php
  }


  public function interfaz_agreadmindepositos(){
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    // $modagresivo = get_user_meta( $user, 'modagresivo', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "agresivo";
    $submenu = "agreadmindepositos";

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // echo "<pre>";
    // var_dump($modagresivo);
    // var_dump($modagrepart);
    // echo "</pre>";
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-admin.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Historial de dep&oacute;sitos</h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_agresivo_control" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>
              </div><!-- ui-dashboard -->

              <div class="box-transparent mb-3">
                <br>
                <h3 class="titulo3-redesign">Control de depósitos</h3>
                <br>
                <br>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-agreadmindepositos">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Usuario</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Cantidad final</th>
                      <th class="manage_column" >Notas</th>
                      <th class="manage_column" >Status</th>
                      <th class="manage_column" >Id Depósito a TD</th>
                      <th class="manage_column" >Id Depósito a Master</th>
                      <th class="manage_column" >Fecha solicitud</th>
                      <th class="manage_column" >Fecha autorización</th>
                      <th class="manage_column" >Tipo Wallet</th>
                      <th class="manage_column" >Wallet Address</th>
                      <th class="manage_column" >Acciones</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>

                <div class="modal fade modal-ui" id="modal-agrfindep" tabindex="-1" aria-labelledby="modal-agrfindepLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-agrfindepLabel">Autorizar depósito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-agrfindep" class="form-agrfindep" action="#" method="post">
                          <input type="hidden" id="iddep" name="iddep" value="">
                          <div class="campo">
                            <label for="idmovind">Id Depósito a TD: </label>
                            <input id="idmovind" type="text" name="idmovind" required>
                          </div>
                          <div class="campo">
                            <label for="idmovgral">Id Depósito a Master: </label>
                            <input id="idmovgral" type="text" name="idmovgral" required>
                          </div>
                          <div class="campo">
                            <label for="cantidadini">Cantidad solicitada: </label>
                            <input id="cantidadini" type="text" name="cantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="cantidadfin">Cantidad final: </label>
                            <input id="cantidadfin" type="text" name="cantidadfin" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="fechasol">Fecha solicitud: </label>
                            <input type="date" name="fechasol" id="fechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="fechafin">Fecha autorización: </label>
                            <input type="date" name="fechafin" id="fechafin" value="" required>
                          </div>
                          <div class="campo">
                            <label for="notas">Notas: </label>
                            <textarea name="notas" id="notas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="agrefinalizardep" type="submit" name="agrefinalizardep" class="button button-primary" value="Autorizar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-agreditdep" tabindex="-1" aria-labelledby="modal-agreditdepLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-agreditdepLabel">Editar depósito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-agreditdep" class="form-agreditdep" action="#" method="post">
                          <input type="hidden" id="idedep" name="idedep" value="">
                          <div class="campo">
                            <label for="eidmovind">Id Depósito a TD: </label>
                            <input id="eidmovind" type="text" name="eidmovind" required>
                          </div>
                          <div class="campo">
                            <label for="eidmovgral">Id Depósito a Master: </label>
                            <input id="eidmovgral" type="text" name="eidmovgral" required>
                          </div>
                          <div class="campo">
                            <label for="ecantidadini">Cantidad solicitada: </label>
                            <input id="ecantidadini" type="text" name="ecantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="ecantidadfin">Cantidad final: </label>
                            <input id="ecantidadfin" type="text" name="ecantidadfin" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="efechasol">Fecha solicitud: </label>
                            <input type="date" name="efechasol" id="efechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="efechafin">Fecha autorización: </label>
                            <input type="date" name="efechafin" id="efechafin" value="" required>
                          </div>
                          <div class="campo">
                            <label for="enotas">Notas: </label>
                            <textarea name="enotas" id="enotas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="agreditarret" type="submit" name="agreditarret" class="button button-primary" value="Editar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-agrcancdep" tabindex="-1" aria-labelledby="modal-agrcancdepLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-agrcancdepLabel">Cancelar depósito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-agrcancdep" class="form-agrcancdep" action="#" method="post">
                          <input type="hidden" id="idcdep" name="idcdep" value="">
                          <div class="campo">
                            <label for="ccantidadini">Cantidad solicitada: </label>
                            <input id="ccantidadini" type="text" name="ccantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="cfechasol">Fecha solicitud: </label>
                            <input type="date" name="cfechasol" id="cfechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="cfechafin">Fecha cancelación: </label>
                            <input type="date" name="cfechafin" id="cfechafin" value="">
                          </div>
                          <div class="campo">
                            <label for="cnotas">Notas: </label>
                            <textarea name="cnotas" id="cnotas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="agrcancelardep" type="submit" name="agrcancelardep" class="button button-primary" value="Cancelar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

              </div>

            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_agreadminretiros(){
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    // $modagresivo = get_user_meta( $user, 'modagresivo', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "agresivo";
    $submenu = "agreadminretiros";

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // echo "<pre>";
    // var_dump($modagresivo);
    // var_dump($modagrepart);
    // echo "</pre>";
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-admin.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Historial de retiros</h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_agresivo_control" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>
              </div><!-- ui-dashboard -->

              <div class="box-transparent mb-3">
                <br>
                <h3 class="titulo3-redesign">Control de retiros</h3>
                <br>
                <br>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-agreadminretiros">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Usuario</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Cantidad final</th>
                      <th class="manage_column" >Notas</th>
                      <th class="manage_column" >Status</th>
                      <th class="manage_column" >Id Retiro a TD</th>
                      <th class="manage_column" >Fecha solicitud</th>
                      <th class="manage_column" >Fecha autorización</th>
                      <th class="manage_column" >Tipo Wallet</th>
                      <th class="manage_column" >Wallet Address</th>
                      <th class="manage_column" >Acciones</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>

                <div class="modal fade modal-ui" id="modal-agrfinret" tabindex="-1" aria-labelledby="modal-agrfinretLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-agrfinretLabel">Autorizar retiro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-agrfinret" class="form-agrfinret" action="#" method="post">
                          <input type="hidden" id="idret" name="idret" value="">
                          <div class="campo">
                            <label for="idmovind">Id Retiro a TD: </label>
                            <input id="idmovind" type="text" name="idmovind" required>
                          </div>
                          <div class="campo">
                            <label for="cantidadini">Cantidad solicitada: </label>
                            <input id="cantidadini" type="text" name="cantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="cantidadfin">Cantidad final: </label>
                            <input id="cantidadfin" type="text" name="cantidadfin" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="fechasol">Fecha solicitud: </label>
                            <input type="date" name="fechasol" id="fechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="fechafin">Fecha autorización: </label>
                            <input type="date" name="fechafin" id="fechafin" value="" required>
                          </div>
                          <div class="campo">
                            <label for="notas">Notas: </label>
                            <textarea name="notas" id="notas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="agrefinalizarret" type="submit" name="agrefinalizarret" class="button button-primary" value="Autorizar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-agreditret" tabindex="-1" aria-labelledby="modal-agreditretLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-agreditretLabel">Editar retiro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-agreditret" class="form-agreditret" action="#" method="post">
                          <input type="hidden" id="ideret" name="ideret" value="">
                          <div class="campo">
                            <label for="eidmovind">Id Retiro a TD: </label>
                            <input id="eidmovind" type="text" name="eidmovind" required>
                          </div>
                          <div class="campo">
                            <label for="ecantidadini">Cantidad solicitada: </label>
                            <input id="ecantidadini" type="text" name="ecantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="ecantidadfin">Cantidad final: </label>
                            <input id="ecantidadfin" type="text" name="ecantidadfin" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="efechasol">Fecha solicitud: </label>
                            <input type="date" name="efechasol" id="efechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="efechafin">Fecha autorización: </label>
                            <input type="date" name="efechafin" id="efechafin" value="">
                          </div>
                          <div class="campo">
                            <label for="enotas">Notas: </label>
                            <textarea name="enotas" id="enotas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="agreditarret" type="submit" name="agreditarret" class="button button-primary" value="Editar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-agrcancret" tabindex="-1" aria-labelledby="modal-agrcancretLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-agrcancretLabel">Cancelar retiro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-agrcancret" class="form-agrcancret" action="#" method="post">
                          <input type="hidden" id="idcret" name="idcret" value="">
                          <div class="campo">
                            <label for="ccantidadini">Cantidad solicitada: </label>
                            <input id="ccantidadini" type="text" name="ccantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="cfechasol">Fecha solicitud: </label>
                            <input type="date" name="cfechasol" id="cfechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="cfechafin">Fecha cancelación: </label>
                            <input type="date" name="cfechafin" id="cfechafin" value="">
                          </div>
                          <div class="campo">
                            <label for="cnotas">Notas: </label>
                            <textarea name="cnotas" id="cnotas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="agrcancelarret" type="submit" name="agrcancelarret" class="button button-primary" value="Cancelar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

              </div>

            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_agredepmaster(){
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    // $modagresivo = get_user_meta( $user, 'modagresivo', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "agresivo";
    $submenu = "agredepositosmaster";

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // echo "<pre>";
    // var_dump($modagresivo);
    // var_dump($modagrepart);
    // echo "</pre>";
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-admin.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Dep&oacute;sitos Master</h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_agresivo_control" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>
              </div><!-- ui-dashboard -->

              <div class="box-transparent mb-3">
                <br>
                <h3 class="titulo3-redesign">Control de depósitos master</h3>
                <br>
                <br>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-agredepmaster">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Usuario</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Cantidad final</th>
                      <th class="manage_column" >Notas</th>
                      <th class="manage_column" >Status</th>
                      <th class="manage_column" >Id Depósito a TD</th>
                      <th class="manage_column" >Id Depósito a Master</th>
                      <th class="manage_column" >Fecha solicitud</th>
                      <th class="manage_column" >Fecha autorización</th>
                      <th class="manage_column" >Acciones</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>

                <div class="modal fade modal-ui" id="modal-agrfindepmas" tabindex="-1" aria-labelledby="modal-agrfindepmasLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-agrfindepmasLabel">Autorizar depósito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-agrfindepmas" class="form-agrfindepmas" action="#" method="post">
                          <input type="hidden" id="iddep" name="iddep" value="">
                          <div class="campo">
                            <label for="idmovind">Id Depósito a TD: </label>
                            <input id="idmovind" type="text" name="idmovind" required>
                          </div>
                          <div class="campo">
                            <label for="idmovgral">Id Depósito a Master: </label>
                            <input id="idmovgral" type="text" name="idmovgral" required>
                          </div>
                          <div class="campo">
                            <label for="cantidadini">Cantidad solicitada: </label>
                            <input id="cantidadini" type="text" name="cantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="cantidadfin">Cantidad final: </label>
                            <input id="cantidadfin" type="text" name="cantidadfin" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="fechasol">Fecha solicitud: </label>
                            <input type="date" name="fechasol" id="fechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="fechafin">Fecha autorización: </label>
                            <input type="date" name="fechafin" id="fechafin" value="" required>
                          </div>
                          <div class="campo">
                            <label for="notas">Notas: </label>
                            <textarea name="notas" id="notas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="agrefinalizardepmas" type="submit" name="agrefinalizardepmas" class="button button-primary" value="Autorizar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-agreditdepmas" tabindex="-1" aria-labelledby="modal-agreditdepmasLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-agreditdepmasLabel">Editar depósito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-agreditdepmas" class="form-agreditdepmas" action="#" method="post">
                          <input type="hidden" id="idedep" name="idedep" value="">
                          <div class="campo">
                            <label for="eidmovind">Id Depósito a TD: </label>
                            <input id="eidmovind" type="text" name="eidmovind" required>
                          </div>
                          <div class="campo">
                            <label for="eidmovgral">Id Depósito a Master: </label>
                            <input id="eidmovgral" type="text" name="eidmovgral" required>
                          </div>
                          <div class="campo">
                            <label for="ecantidadini">Cantidad solicitada: </label>
                            <input id="ecantidadini" type="text" name="ecantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="ecantidadfin">Cantidad final: </label>
                            <input id="ecantidadfin" type="text" name="ecantidadfin" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="efechasol">Fecha solicitud: </label>
                            <input type="date" name="efechasol" id="efechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="efechafin">Fecha autorización: </label>
                            <input type="date" name="efechafin" id="efechafin" value="" required>
                          </div>
                          <div class="campo">
                            <label for="enotas">Notas: </label>
                            <textarea name="enotas" id="enotas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="agreditarretmas" type="submit" name="agreditarretmas" class="button button-primary" value="Editar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-agrcancdep" tabindex="-1" aria-labelledby="modal-agrcancdepLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-agrcancdepLabel">Cancelar depósito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-agrcancdep" class="form-agrcancdep" action="#" method="post">
                          <input type="hidden" id="idcdep" name="idcdep" value="">
                          <div class="campo">
                            <label for="ccantidadini">Cantidad solicitada: </label>
                            <input id="ccantidadini" type="text" name="ccantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="cfechasol">Fecha solicitud: </label>
                            <input type="date" name="cfechasol" id="cfechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="cfechafin">Fecha cancelación: </label>
                            <input type="date" name="cfechafin" id="cfechafin" value="">
                          </div>
                          <div class="campo">
                            <label for="cnotas">Notas: </label>
                            <textarea name="cnotas" id="cnotas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="agrcancelardep" type="submit" name="agrcancelardep" class="button button-primary" value="Cancelar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

              </div>

            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_agreretmaster(){
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    // $modagresivo = get_user_meta( $user, 'modagresivo', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "agresivo";
    $submenu = "agrearetirosmaster";

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // echo "<pre>";
    // var_dump($modagresivo);
    // var_dump($modagrepart);
    // echo "</pre>";
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-admin.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Retiros Master</h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_agresivo_control" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>
              </div><!-- ui-dashboard -->

              <div class="box-transparent mb-3">
                <br>
                <h3 class="titulo3-redesign">Control de retiros master</h3>
                <br>
                <br>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-agreretmaster">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Usuario</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Cantidad final</th>
                      <th class="manage_column" >Notas</th>
                      <th class="manage_column" >Status</th>
                      <th class="manage_column" >Id Retiro a TD</th>
                      <th class="manage_column" >Fecha solicitud</th>
                      <th class="manage_column" >Fecha autorización</th>
                      <th class="manage_column" >Acciones</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>

                <div class="modal fade modal-ui" id="modal-agrfinretmas" tabindex="-1" aria-labelledby="modal-agrfinretmasLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-agrfinretmasLabel">Autorizar retiro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-agrfinretmas" class="form-agrfinretmas" action="#" method="post">
                          <input type="hidden" id="idret" name="idret" value="">
                          <div class="campo">
                            <label for="idmovind">Id Retiro a TD: </label>
                            <input id="idmovind" type="text" name="idmovind" required>
                          </div>
                          <div class="campo">
                            <label for="cantidadini">Cantidad solicitada: </label>
                            <input id="cantidadini" type="text" name="cantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="cantidadfin">Cantidad final: </label>
                            <input id="cantidadfin" type="text" name="cantidadfin" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="fechasol">Fecha solicitud: </label>
                            <input type="date" name="fechasol" id="fechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="fechafin">Fecha autorización: </label>
                            <input type="date" name="fechafin" id="fechafin" value="" required>
                          </div>
                          <div class="campo">
                            <label for="notas">Notas: </label>
                            <textarea name="notas" id="notas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="agrefinalizarretmas" type="submit" name="agrefinalizarretmas" class="button button-primary" value="Autorizar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-agreditretmas" tabindex="-1" aria-labelledby="modal-agreditretLmasabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-agreditretmasLabel">Editar retiro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-agreditretmas" class="form-agreditretmas" action="#" method="post">
                          <input type="hidden" id="ideret" name="ideret" value="">
                          <div class="campo">
                            <label for="eidmovind">Id Retiro a TD: </label>
                            <input id="eidmovind" type="text" name="eidmovind" required>
                          </div>
                          <div class="campo">
                            <label for="ecantidadini">Cantidad solicitada: </label>
                            <input id="ecantidadini" type="text" name="ecantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="ecantidadfin">Cantidad final: </label>
                            <input id="ecantidadfin" type="text" name="ecantidadfin" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="efechasol">Fecha solicitud: </label>
                            <input type="date" name="efechasol" id="efechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="efechafin">Fecha autorización: </label>
                            <input type="date" name="efechafin" id="efechafin" value="">
                          </div>
                          <div class="campo">
                            <label for="enotas">Notas: </label>
                            <textarea name="enotas" id="enotas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="agreditarret" type="submit" name="agreditarret" class="button button-primary" value="Editar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-agrcancret" tabindex="-1" aria-labelledby="modal-agrcancretLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-agrcancretLabel">Cancelar retiro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-agrcancret" class="form-agrcancret" action="#" method="post">
                          <input type="hidden" id="idcret" name="idcret" value="">
                          <div class="campo">
                            <label for="ccantidadini">Cantidad solicitada: </label>
                            <input id="ccantidadini" type="text" name="ccantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="cfechasol">Fecha solicitud: </label>
                            <input type="date" name="cfechasol" id="cfechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="cfechafin">Fecha cancelación: </label>
                            <input type="date" name="cfechafin" id="cfechafin" value="">
                          </div>
                          <div class="campo">
                            <label for="cnotas">Notas: </label>
                            <textarea name="cnotas" id="cnotas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="agrcancelarret" type="submit" name="agrcancelarret" class="button button-primary" value="Cancelar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

              </div>

            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_adminverdepagrmes(){
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );

    global $wpdb;
    $mesint = (int) $_GET['m'];
    //$mes = printf('%02d', $mesint);
    $agno = (int)$_GET['y'];
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );
    $tmes = $mesesNombre[$mesint];

    // $modagresivo = get_user_meta( $user, 'modagresivo', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "agresivo";
    $submenu = "";

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // echo "<pre>";
    // var_dump($modagresivo);
    // var_dump($modagrepart);
    // echo "</pre>";

    $depositos = $wpdb->prefix . 'depositos_agr';
    $registros = $wpdb->get_results(" SELECT ROUND(SUM(dagr_cantidad_real), 2) AS totaldepmes FROM $depositos WHERE month(dagr_fecha_termino) = $mesint AND year(dagr_fecha_termino) = $agno AND dagr_status = 2", ARRAY_A);
    $totaldepmes = (float) $registros[0]["totaldepmes"];
    $ttotaldepmes = number_format($totaldepmes, 2);

    $depositosmas = $wpdb->prefix . 'depositos_master_agr';
    $registrosmas = $wpdb->get_results(" SELECT ROUND(SUM(dmagr_cantidad_real), 2) AS totaldepmes FROM $depositosmas WHERE month(dmagr_fecha_termino) = $mesint AND year(dmagr_fecha_termino) = $agno AND dmagr_status = 2", ARRAY_A);
    $totaldepmasmes = (float) $registrosmas[0]["totaldepmes"];
    $ttotaldepmasmes = number_format($totaldepmasmes, 2);

    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-admin.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Depósitos <?php echo $tmes ." ". $agno ?></h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_agresivo_control" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>
              </div><!-- ui-dashboard -->

              <div class="box-transparent mb-3">
                <br>
                <h3 class="titulo3-redesign">Lista de depósitos válidos de inversionistas:</h3>
                <br>
                <br>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-agrdepmes">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Usuario</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Cantidad final</th>
                      <th class="manage_column" >Notas</th>
                      <th class="manage_column" >Status</th>
                      <th class="manage_column" >Id Depósito a TD</th>
                      <th class="manage_column" >Id Depósito a Master</th>
                      <th class="manage_column" >Fecha solicitud</th>
                      <th class="manage_column" >Fecha autorización</th>
                      <th class="manage_column" >Tipo Wallet</th>
                      <th class="manage_column" >Wallet Address</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
                <br>
                <p class="total-detalle"><i class="fa-solid fa-coins"></i> Total Depositos Inv: <span class="totaldepmes">$<?php echo $ttotaldepmes ; ?></span></p>
                <br>
                <br>
                <h3 class="titulo3-redesign">Lista de depósitos válidos de administradores:</h3>
                <br>
                <br>
                <table class="wp-list-table widefat tab-ui tab-admin dt-responsive striped tab-agrdepmasmes">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Usuario</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Cantidad final</th>
                      <th class="manage_column" >Notas</th>
                      <th class="manage_column" >Status</th>
                      <th class="manage_column" >Id Depósito a TD</th>
                      <th class="manage_column" >Id Depósito a Master</th>
                      <th class="manage_column" >Fecha solicitud</th>
                      <th class="manage_column" >Fecha autorización</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
                <br>
                <p class="total-detallemas"><i class="fa-solid fa-coins"></i> Total Depositos Admin: <span class="totaldepmasmes">$<?php echo $ttotaldepmasmes ; ?></span></p>
                <br>

              </div>

            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_adminverretagrmes(){
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );

    global $wpdb;
    $mesint = (int) $_GET['m'];
    //$mes = printf('%02d', $mesint);
    $agno = (int)$_GET['y'];
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );
    $tmes = $mesesNombre[$mesint];

    // $modagresivo = get_user_meta( $user, 'modagresivo', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "agresivo";
    $submenu = "";

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // echo "<pre>";
    // var_dump($modagresivo);
    // var_dump($modagrepart);
    // echo "</pre>";

    $retiros = $wpdb->prefix . 'retiros_agr';
    $registros = $wpdb->get_results(" SELECT ROUND(SUM(ragr_cantidad_real), 2) AS totalretmes FROM $retiros WHERE month(ragr_fecha_termino) = $mesint AND year(ragr_fecha_termino) = $agno AND ragr_status = 2", ARRAY_A);
    $totalretmes = (float) $registros[0]["totalretmes"];
    $ttotalretmes = number_format($totalretmes, 2);

    $retirosmas = $wpdb->prefix . 'retiros_master_agr';
    $registrosmas = $wpdb->get_results(" SELECT ROUND(SUM(rmagr_cantidad_real), 2) AS totalretmes FROM $retirosmas WHERE month(rmagr_fecha_termino) = $mesint AND year(rmagr_fecha_termino) = $agno AND rmagr_status = 2", ARRAY_A);
    $totalretmasmes = (float) $registrosmas[0]["totalretmes"];
    $ttotalretmasmes = number_format($totalretmasmes, 2);

    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-admin.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Retiros <?php echo $tmes ." ". $agno ?></h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_agresivo_control" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>
              </div><!-- ui-dashboard -->

              <div class="box-transparent mb-3">
                <br>
                <h3 class="titulo3-redesign">Lista de retiros válidos de inversionistas:</h3>
                <br>
                <br>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-agrretmes">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Usuario</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Cantidad final</th>
                      <th class="manage_column" >Notas</th>
                      <th class="manage_column" >Status</th>
                      <th class="manage_column" >Id Retiro a TD</th>
                      <th class="manage_column" >Fecha solicitud</th>
                      <th class="manage_column" >Fecha autorización</th>
                      <th class="manage_column" >Tipo Wallet</th>
                      <th class="manage_column" >Wallet Address</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
                <br>
                <p class="total-detalle"><i class="fa-solid fa-coins"></i> Total Retiros Inv: <span class="totalretmes">$<?php echo $ttotalretmes ; ?></span></p>
                <br>
                <br>
                <h3 class="titulo3-redesign">Lista de retiros válidos de administradores:</h3>
                <br>
                <br>
                <table class="wp-list-table widefat tab-ui tab-admin dt-responsive striped tab-agrretmasmes">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Usuario</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Cantidad final</th>
                      <th class="manage_column" >Notas</th>
                      <th class="manage_column" >Status</th>
                      <th class="manage_column" >Id Retiro a TD</th>
                      <th class="manage_column" >Fecha solicitud</th>
                      <th class="manage_column" >Fecha autorización</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
                <br>
                <p class="total-detallemas"><i class="fa-solid fa-coins"></i> Total Retiros Admin: <span class="totalretmasmes">$<?php echo $ttotalretmasmes ; ?></span></p>
                <br>

              </div>

            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_adminagrinvmes(){
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );

    global $wpdb;
    $mesint = (int) $_GET['m'];
    //$mes = printf('%02d', $mesint);
    $agno = (int)$_GET['y'];
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );
    $tmes = $mesesNombre[$mesint];

    // $modagresivo = get_user_meta( $user, 'modagresivo', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "agresivo";
    $submenu = "";

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // echo "<pre>";
    // var_dump($modagresivo);
    // var_dump($modagrepart);
    // echo "</pre>";

    // $retiros = $wpdb->prefix . 'retiros_agr';
    // $registros = $wpdb->get_results(" SELECT ROUND(SUM(ragr_cantidad_real), 2) AS totalretmes FROM $retiros WHERE month(ragr_fecha_termino) = $mesint AND year(ragr_fecha_termino) = $agno AND ragr_status = 2", ARRAY_A);
    // $totalretmes = (float) $registros[0]["totalretmes"];
    // $ttotalretmes = number_format($totalretmes, 2);

    // $fechasfuturas = "2023-4-01";
    // $tabladep = $wpdb->prefix . 'depositos_agr';
    // $totaldep = $wpdb->get_results("SELECT ROUND(SUM(dagr_cantidad_real), 2) AS totaldep FROM $tabladep WHERE dagr_status = 2 AND dagr_fecha_termino >= '".$fechasfuturas."'" , ARRAY_A);
    // //
    // $retirosmas = $wpdb->prefix . 'retiros_master_agr';
    // $registrosmas = $wpdb->get_results(" SELECT ROUND(SUM(rmagr_cantidad_real), 2) AS totalretmes FROM $retirosmas WHERE month(rmagr_fecha_termino) = $mesint AND year(rmagr_fecha_termino) = $agno AND rmagr_status = 2", ARRAY_A);
    // $totalretmasmes = (float) $registrosmas[0]["totalretmes"];
    // $ttotalretmasmes = number_format($totalretmasmes, 2);

    $calculos = new CRC_AgreCalculo();
    // $detallecajasadmin = $calculos->crc_datoscajasuperiores_admin();
    // $detallemesuser = $calculos->crc_datosmes_agreinvestor(48,1,2023);
    // $detallemesuser = $calculos->crc_datosfull_agreinvestor(48);
    // $detalleregistros = $calculos->crc_datosproyeccion_agreregistros();

    // echo "<pre>";
    // var_dump($detallecajasadmin);
    // var_dump($detalleregistros);
    // echo "</pre>";
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-admin.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Detalle inversionistas <?php echo $tmes ." ". $agno ?></h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_agresivo_control" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>
              </div><!-- ui-dashboard -->

              <div class="box-transparent mb-3">
                <br>
                <h3 class="titulo3-redesign">Reparto entre inversionistas:</h3>
                <br>
                <br>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-agrinvmes">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Nombre</th>
                      <th class="manage_column" >Depósitos</th>
                      <th class="manage_column" >Cap principal</th>
                      <th class="manage_column" >% de participación</th>
                      <th class="manage_column" >Utilidad mes</th>
                      <th class="manage_column" >Utilidad acumulada</th>
                      <th class="manage_column" >Total</th>
                      <th class="manage_column" >% Rendimiento</th>
                      <th class="manage_column" >Retiros</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
                <br>
                <p class="total-detalle"><i class="fa-solid fa-coins"></i> Total Cap Inicial Mes: <span class="totalretmes">$0.00</span></p>
                <br>
                <p class="total-detalle"><i class="fa-solid fa-coins"></i> Total Utilidad Mes: <span class="totalretmes">$0.00</span></p>
                <!-- <br>
                <h3 class="titulo3-redesign">Lista de retiros válidos de administradores:</h3>
                <br>
                <br>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-agrretmasmes">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Usuario</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Cantidad final</th>
                      <th class="manage_column" >Notas</th>
                      <th class="manage_column" >Status</th>
                      <th class="manage_column" >Id Retiro a TD</th>
                      <th class="manage_column" >Fecha solicitud</th>
                      <th class="manage_column" >Fecha autorización</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
                <br>
                <p class="total-detallemas"><i class="fa-solid fa-coins"></i> Total Depositos Inv: <span class="totalretmasmes">$0.00</span></p>
                <br> -->

              </div>

            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_agrlistausuarios(){
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );

    global $wpdb;
    // $mesint = (int) $_GET['m'];
    //$mes = printf('%02d', $mesint);
    // $agno = (int)$_GET['y'];
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );
    // $tmes = $mesesNombre[$mesint];

    // $modagresivo = get_user_meta( $user, 'modagresivo', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "agresivo";
    $submenu = "listagresiva";

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }

    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-admin.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Lista de usuarios Agresivo</h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_agresivo_control" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>
              </div><!-- ui-dashboard -->

              <div class="box-transparent mb-3">
                <br>
                <h3 class="titulo3-redesign">Usuarios activos en el modulo:</h3>
                <br>
                <br>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-agrlistusers">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Nombre</th>
                      <th class="manage_column" >Email</th>
                      <th class="manage_column" >Acceso</th>
                      <th class="manage_column" >Dashboard</th>
                      <th class="manage_column" >Perfil</th>
                      <th class="manage_column" >Cap principal</th>
                      <th class="manage_column" >Tipo Wallet</th>
                      <th class="manage_column" >Wallet Address</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
                <br>
                <hr>
                <p>Notas: <br>
                  <ol>
                    <li>La lista muestra a todos los usuarios activos actualmente que se encuentren participando en el módulo agresivo.</li>
                  </ol>
                </p>

              </div>

            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_agruserdashboard(){
    if ( ! defined( 'ABSPATH' ) ) exit;

    $url = get_site_url();
    $user = (int) $_GET['id'];
    $useradmin = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    $useradmin_data = get_userdata( absint( $useradmin ) );
    $modagresivo = get_user_meta( $user, 'modagresivo', true);
    $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "agresivo";
    $submenu = "";

    $usernombre = $useradmin_data->user_firstname;
    $usermail = $useradmin_data->user_email;

    $userfirstname = $user_data->user_firstname;
    $userlastname = $user_data->user_lastname;
    $userfullname = $userfirstname." ".$userlastname;

    $foto = get_user_meta( $useradmin, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // echo "<pre>";
    // var_dump($modagresivo);
    // var_dump($modagrepart);
    // echo "</pre>";
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-admin.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Dashboard Agresivo - <?php echo $userfullname ?></h1>
              </div>

              <div class="ui-dashboard">
                <!-- cajas superiores -->
                <div class="container-fluid px-0 my-4 pt-5 pb-4">
                  <div class="row">
                    <div class="col-12 col-md-3 ">
                      <div class="rd-cajasup rd-cajasup-azul">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-flag"></i></span>
                        <h4>Capital Principal</h4>
                        <span class="rd-cajasup-cifra">$</span>
                      </div>
                    </div>
                    <div class="col-12 col-md-3 ">
                      <div class="rd-cajasup rd-cajasup-verde">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-chart-line"></i></span>
                        <h4>Utilidad generada</h4>
                        <span class="rd-cajasup-cifra">$</span>
                      </div>
                    </div>
                    <div class="col-12 col-md-3 ">
                      <div class="rd-cajasup rd-cajasup-gris">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-user-group"></i></span>
                        <h4>Utilidad referidos</h4>
                        <span class="rd-cajasup-cifra">$</span>
                      </div>
                    </div>
                    <div class="col-12 col-md-3 ">
                      <div class="rd-cajasup rd-cajasup-naranja">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-file-circle-check"></i></span>
                        <h4>Total</h4>
                        <span class="rd-cajasup-cifra">$</span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Botones de deposito y retiro -->
                <div class="oper-master row mt-4 pt-3">
                  <div class="oper-mes oper-mes1 col-12 col-md-6 d-flex flex-column ">
                    <div class="caja-agredep d-flex ">
                      <button class='btn-addagredep d-flex' id='btn-addagredep' type='button' data-bs-toggle='modal' data-bs-target='#modal-addagredep' >
                        <span class="oper-agredep"><i class="fa-solid fa-arrow-down"></i></span>
                        <div class="d-flex flex-column">
                          <span class="oper-titulo">Depósito</span>
                        </div>
                      </button>
                    </div>
                    <table class="wp-list-table widefat tab-ui striped tab-agreuserdep">
                      <thead>
                        <tr>
                          <th class="manage_column" >Fecha</th>
                          <th class="manage_column" >Cantidad</th>
                          <th class="manage_column" >Status</th>
                        </tr>
                      </thead>

                      <tbody>

                      </tbody>
                    </table>
                  </div>
                  <div class="oper-mes oper-mes2 col-12 col-md-6 d-flex flex-column mt-4 mt-md-0">
                    <div class="caja-agreret d-flex">
                      <button class='btn-addagreret d-flex' id='btn-addagreret' type='button' data-bs-toggle='modal' data-bs-target='#modal-addagreret' >
                        <span class="oper-agreret"><i class="fa-solid fa-arrow-up"></i></span>
                        <div class="d-flex flex-column">
                          <span class="oper-titulo">Retiro</span>
                        </div>
                      </button>
                    </div>
                    <table class="wp-list-table widefat tab-ui striped tab-agreuserret">
                      <thead>
                        <tr>
                          <th class="manage_column" >Fecha</th>
                          <th class="manage_column" >Cantidad</th>
                          <th class="manage_column" >Status</th>
                        </tr>
                      </thead>

                      <tbody>

                      </tbody>
                    </table>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-addagredep" tabindex="-1" aria-labelledby="modal-addagredepLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-addagredepLabel">Solicitar depósito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-addagredep" class="form-addagredep" action="#" method="post">
                          <input type="hidden" id="userdep" name="userdep" value="<?php echo $user ?>">
                          <div class="campo">
                            <label for="dagr_cantidad">*Cantidad: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="dagr_cantidad" class="form-control" type="text" name="dagr_cantidad" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo-especial">
                            <input id="agregaragredep" type="submit" name="agregaragredep" class="button button-primary" value="Solicitar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-addagreret" tabindex="-1" aria-labelledby="modal-addagreretLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-addagreretLabel">Solicitar retiro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-addagreret" class="form-addagreret" action="#" method="post">
                          <input type="hidden" id="userret" name="userret" value="<?php echo $user ?>">
                          <input type="hidden" id="totaldisp" name="totaldisp" value="10000">
                          <div class="campo">
                            <label for="ragr_cantidad">*Cantidad: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="ragr_cantidad" class="form-control" type="text" name="ragr_cantidad" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo-especial">
                            <input id="agregaragreret" type="submit" name="agregaragreret" class="button button-primary" value="Solicitar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

              </div><!-- ui-dashboard -->
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_consadmindepositos(){
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    // $modagresivo = get_user_meta( $user, 'modagresivo', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "conservador";
    $submenu = "consadmindepositos";

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // echo "<pre>";
    // var_dump($modagresivo);
    // var_dump($modagrepart);
    // echo "</pre>";
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-admin.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Historial de dep&oacute;sitos</h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_conservador_control" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>
              </div><!-- ui-dashboard -->

              <div class="box-transparent mb-3">
                <br>
                <h3 class="titulo3-redesign">Control de depósitos</h3>
                <br>
                <br>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-consadmindepositos">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Usuario</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Cantidad final</th>
                      <th class="manage_column" >Notas</th>
                      <th class="manage_column" >Status</th>
                      <th class="manage_column" >Id Depósito a TD</th>
                      <th class="manage_column" >Id Depósito a Master</th>
                      <th class="manage_column" >Fecha solicitud</th>
                      <th class="manage_column" >Fecha autorización</th>
                      <th class="manage_column" >Tipo Wallet</th>
                      <th class="manage_column" >Wallet Address</th>
                      <th class="manage_column" >Acciones</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>

                <div class="modal fade modal-ui" id="modal-confindep" tabindex="-1" aria-labelledby="modal-confindepLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-confindepLabel">Autorizar depósito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-confindep" class="form-confindep" action="#" method="post">
                          <input type="hidden" id="iddep" name="iddep" value="">
                          <div class="campo">
                            <label for="idmovind">Id Depósito a TD: </label>
                            <input id="idmovind" type="text" name="idmovind" required>
                          </div>
                          <div class="campo">
                            <label for="idmovgral">Id Depósito a Master: </label>
                            <input id="idmovgral" type="text" name="idmovgral" required>
                          </div>
                          <div class="campo">
                            <label for="cantidadini">Cantidad solicitada: </label>
                            <input id="cantidadini" type="text" name="cantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="cantidadfin">Cantidad final: </label>
                            <input id="cantidadfin" type="text" name="cantidadfin" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="fechasol">Fecha solicitud: </label>
                            <input type="date" name="fechasol" id="fechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="fechafin">Fecha autorización: </label>
                            <input type="date" name="fechafin" id="fechafin" value="" required>
                          </div>
                          <div class="campo">
                            <label for="notas">Notas: </label>
                            <textarea name="notas" id="notas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="consfinalizardep" type="submit" name="consfinalizardep" class="button button-primary" value="Autorizar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-coneditdep" tabindex="-1" aria-labelledby="modal-coneditdepLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-coneditdepLabel">Editar depósito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-coneditdep" class="form-coneditdep" action="#" method="post">
                          <input type="hidden" id="idedep" name="idedep" value="">
                          <div class="campo">
                            <label for="eidmovind">Id Depósito a TD: </label>
                            <input id="eidmovind" type="text" name="eidmovind" required>
                          </div>
                          <div class="campo">
                            <label for="eidmovgral">Id Depósito a Master: </label>
                            <input id="eidmovgral" type="text" name="eidmovgral" required>
                          </div>
                          <div class="campo">
                            <label for="ecantidadini">Cantidad solicitada: </label>
                            <input id="ecantidadini" type="text" name="ecantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="ecantidadfin">Cantidad final: </label>
                            <input id="ecantidadfin" type="text" name="ecantidadfin" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="efechasol">Fecha solicitud: </label>
                            <input type="date" name="efechasol" id="efechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="efechafin">Fecha autorización: </label>
                            <input type="date" name="efechafin" id="efechafin" value="" required>
                          </div>
                          <div class="campo">
                            <label for="enotas">Notas: </label>
                            <textarea name="enotas" id="enotas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="coneditarret" type="submit" name="coneditarret" class="button button-primary" value="Editar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-concancdep" tabindex="-1" aria-labelledby="modal-concancdepLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-concancdepLabel">Cancelar depósito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-concancdep" class="form-concancdep" action="#" method="post">
                          <input type="hidden" id="idcdep" name="idcdep" value="">
                          <div class="campo">
                            <label for="ccantidadini">Cantidad solicitada: </label>
                            <input id="ccantidadini" type="text" name="ccantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="cfechasol">Fecha solicitud: </label>
                            <input type="date" name="cfechasol" id="cfechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="cfechafin">Fecha cancelación: </label>
                            <input type="date" name="cfechafin" id="cfechafin" value="">
                          </div>
                          <div class="campo">
                            <label for="cnotas">Notas: </label>
                            <textarea name="cnotas" id="cnotas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="concancelardep" type="submit" name="concancelardep" class="button button-primary" value="Cancelar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

              </div>

            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_consadminretiros(){
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    // $modagresivo = get_user_meta( $user, 'modagresivo', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "conservador";
    $submenu = "consadminretiros";

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // echo "<pre>";
    // var_dump($modagresivo);
    // var_dump($modagrepart);
    // echo "</pre>";
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-admin.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Historial de retiros</h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_agresivo_control" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>
              </div><!-- ui-dashboard -->

              <div class="box-transparent mb-3">
                <br>
                <h3 class="titulo3-redesign">Control de retiros</h3>
                <br>
                <br>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-consadminretiros">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Usuario</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Cantidad final</th>
                      <th class="manage_column" >Notas</th>
                      <th class="manage_column" >Status</th>
                      <th class="manage_column" >Id Retiro a TD</th>
                      <th class="manage_column" >Fecha solicitud</th>
                      <th class="manage_column" >Fecha autorización</th>
                      <th class="manage_column" >Tipo Wallet</th>
                      <th class="manage_column" >Wallet Address</th>
                      <th class="manage_column" >Acciones</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>

                <div class="modal fade modal-ui" id="modal-confinret" tabindex="-1" aria-labelledby="modal-confinretLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-confinretLabel">Autorizar retiro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-confinret" class="form-confinret" action="#" method="post">
                          <input type="hidden" id="idret" name="idret" value="">
                          <div class="campo">
                            <label for="idmovind">Id Retiro a TD: </label>
                            <input id="idmovind" type="text" name="idmovind" required>
                          </div>
                          <div class="campo">
                            <label for="cantidadini">Cantidad solicitada: </label>
                            <input id="cantidadini" type="text" name="cantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="cantidadfin">Cantidad final: </label>
                            <input id="cantidadfin" type="text" name="cantidadfin" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="fechasol">Fecha solicitud: </label>
                            <input type="date" name="fechasol" id="fechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="fechafin">Fecha autorización: </label>
                            <input type="date" name="fechafin" id="fechafin" value="" required>
                          </div>
                          <div class="campo">
                            <label for="notas">Notas: </label>
                            <textarea name="notas" id="notas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="consfinalizarret" type="submit" name="consfinalizarret" class="button button-primary" value="Autorizar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-coneditret" tabindex="-1" aria-labelledby="modal-coneditretLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-coneditretLabel">Editar retiro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-coneditret" class="form-coneditret" action="#" method="post">
                          <input type="hidden" id="ideret" name="ideret" value="">
                          <div class="campo">
                            <label for="eidmovind">Id Retiro a TD: </label>
                            <input id="eidmovind" type="text" name="eidmovind" required>
                          </div>
                          <div class="campo">
                            <label for="ecantidadini">Cantidad solicitada: </label>
                            <input id="ecantidadini" type="text" name="ecantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="ecantidadfin">Cantidad final: </label>
                            <input id="ecantidadfin" type="text" name="ecantidadfin" value="0.00" data-inputmask="'mask':'9{1,7}.{0,1}9{0,2}'" data-mask required>
                          </div>
                          <div class="campo">
                            <label for="efechasol">Fecha solicitud: </label>
                            <input type="date" name="efechasol" id="efechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="efechafin">Fecha autorización: </label>
                            <input type="date" name="efechafin" id="efechafin" value="">
                          </div>
                          <div class="campo">
                            <label for="enotas">Notas: </label>
                            <textarea name="enotas" id="enotas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="coneditarret" type="submit" name="coneditarret" class="button button-primary" value="Editar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-concancret" tabindex="-1" aria-labelledby="modal-concancretLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-concancretLabel">Cancelar retiro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-concancret" class="form-concancret" action="#" method="post">
                          <input type="hidden" id="idcret" name="idcret" value="">
                          <div class="campo">
                            <label for="ccantidadini">Cantidad solicitada: </label>
                            <input id="ccantidadini" type="text" name="ccantidadini" readonly disabled>
                          </div>
                          <div class="campo">
                            <label for="cfechasol">Fecha solicitud: </label>
                            <input type="date" name="cfechasol" id="cfechasol" value="" required>
                          </div>
                          <div class="campo">
                            <label for="cfechafin">Fecha cancelación: </label>
                            <input type="date" name="cfechafin" id="cfechafin" value="">
                          </div>
                          <div class="campo">
                            <label for="cnotas">Notas: </label>
                            <textarea name="cnotas" id="cnotas" rows="5" cols="18" style="resize: none;" ></textarea>
                          </div>
                          <div class="campo-especial">
                            <input id="concancelarret" type="submit" name="concancelarret" class="button button-primary" value="Cancelar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

              </div>

            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_consdepmaster(){
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    // $modconservador = get_user_meta( $user, 'modconservador', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "conservador";
    $submenu = "conshistodepositos";


    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // echo "<pre>";
    // var_dump($modagresivo);
    // var_dump($modagrepart);
    // echo "</pre>";
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-admin.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Historial de dep&oacute;sitos</h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_conservador_control" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>
              </div><!-- ui-dashboard -->

              <div class="box-transparent mb-3">
                <h4 class="mb-5">Historial de depósitos</h4>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-conshistodepositos">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Cantidad final</th>
                      <th class="manage_column" >Status</th>
                      <th class="manage_column" >Id Depósito a TD</th>
                      <th class="manage_column" >Id Depósito a Master</th>
                      <th class="manage_column" >Fecha solicitud</th>
                      <th class="manage_column" >Fecha autorización</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
              </div>

            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php

  }

  public function interfaz_consretmaster(){
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    // $modconservador = get_user_meta( $user, 'modconservador', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "conservador";
    $submenu = "conshistoretiros";


    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }
    // echo "<pre>";
    // var_dump($modagresivo);
    // var_dump($modagrepart);
    // echo "</pre>";
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-admin.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Historial de retiros</h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_conservador_control" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>
              </div><!-- ui-dashboard -->

              <div class="box-transparent mb-3">
                <h4 class="mb-5">Historial de retiros</h4>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-conshistoretiros">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Cantidad</th>
                      <th class="manage_column" >Cantidad final</th>
                      <th class="manage_column" >Status</th>
                      <th class="manage_column" >Id Retiro a TD</th>
                      <th class="manage_column" >Fecha solicitud</th>
                      <th class="manage_column" >Fecha autorización</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
              </div>

            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php

  }

  public function interfaz_conslistausuarios(){
    if ( ! defined( 'ABSPATH' ) ) exit;
    $url = get_site_url();
    $user = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );

    global $wpdb;
    // $mesint = (int) $_GET['m'];
    //$mes = printf('%02d', $mesint);
    // $agno = (int)$_GET['y'];
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );
    // $tmes = $mesesNombre[$mesint];

    // $modagresivo = get_user_meta( $user, 'modagresivo', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "conservador";
    $submenu = "listconsvedor";

    $usernombre = $user_data->user_firstname;
    $usermail = $user_data->user_email;

    $foto = get_user_meta( $user, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }

    // $depositos = $wpdb->prefix . 'depositos_con';
    // $registros = $wpdb->get_results(" SELECT DISTINCT(dcon_usuario) FROM $depositos WHERE dcon_status = 2 ", ARRAY_A);
    //
    // $listausers2 = array();
    //
    // if(count($registros) == 0){
    // }else {
    //   foreach ($registros as $key => $value) {
    //     $listausers2[] = (int)$value["dcon_usuario"];
    //   }
    // }
    //
    // $userconservador = get_users(array(
    // 'meta_key' => 'modconservador',
    // 'meta_value' => 1
    // ));
    //
    // $listausers = array();
    //
    // if (count($userconservador) ==  0) {
    //
    // }else {
    //   foreach ($userconservador as $key => $value) {
    //     $valid = $value->ID;
    //     $listausers[] = $value->ID;
    //
    //   }
    // }
    //
    // $listausers3 = array_merge($listausers, $listausers2);
    // $listausers4 = array_unique($listausers3);


    // echo "<pre>";
    // // var_dump($registros);
    // var_dump($listausers);
    // echo "con depositos";
    // var_dump($listausers2);
    // var_dump($listausers4);
    // echo "</pre>";

    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-admin.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Lista de usuarios Conservador</h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_conservador_control" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>
              </div><!-- ui-dashboard -->

              <div class="box-transparent mb-3">
                <br>
                <h3 class="titulo3-redesign">Usuarios activos en el modulo:</h3>
                <br>
                <br>
                <table class="wp-list-table widefat tab-ui dt-responsive striped tab-conlistusers">
                  <thead>
                    <tr>
                      <th class="manage_column" >#</th>
                      <th class="manage_column" >Nombre</th>
                      <th class="manage_column" >Email</th>
                      <th class="manage_column" >Acceso</th>
                      <th class="manage_column" >Dashboard</th>
                      <th class="manage_column" >Perfil</th>
                      <th class="manage_column" >Cap principal</th>
                      <th class="manage_column" >Tipo Wallet</th>
                      <th class="manage_column" >Wallet Address</th>
                    </tr>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
                <br>
                <hr>
                <p>Notas: <br>
                  <ol>
                    <li>La lista muestra a todos los usuarios participantes y/o activos en el módulo conservador.</li>
                  </ol>
                </p>

              </div>

            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_conuserdashboard(){
    if ( ! defined( 'ABSPATH' ) ) exit;

    $url = get_site_url();
    $user = (int) $_GET['id'];
    $useradmin = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    $useradmin_data = get_userdata( absint( $useradmin ) );
    $modagresivo = get_user_meta( $user, 'modconservador', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "conservador";
    $submenu = "";

    $usernombre = $useradmin_data->user_firstname;
    $usermail = $useradmin_data->user_email;

    $userfirstname = $user_data->user_firstname;
    $userlastname = $user_data->user_lastname;
    $userfullname = $userfirstname." ".$userlastname;

    $foto = get_user_meta( $useradmin, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }

    global  $wpdb;
    $tabladep = $wpdb->prefix . 'depositos_con';
    $tablaret = $wpdb->prefix . 'retiros_con';
    // $usw = 54;
    $calculos = new CRC_ConsCalculo();
    $detalleregistros = $calculos->crc_datosfull_consinvestor($user);

    if(count($detalleregistros) == 1){

      $ultmescerrado = $detalleregistros[count($detalleregistros)-1];
      $ultmesc = (int)$ultmescerrado["mes"];
      $ultyearc = (int)$ultmescerrado["year"];

      if ($ultmesc == 12) {
        $mesfut = 1;
        $yearfut = $ultyearc+1;
      }else {
        $mesfut = $ultmesc+1;
        $yearfut = $ultyearc;
      }

      $fechafut = $yearfut."-".$mesfut."-01";

      // Obtener depositos futuros
      $totalfuturdep = $wpdb->get_results("SELECT ROUND(SUM(dcon_cantidad_real), 2) AS totalfuturdep FROM $tabladep WHERE dcon_usuario = $user AND dcon_status = 2 AND dcon_fecha_termino >= '".$fechafut."'" , ARRAY_A);
      $ttotalfuturdep = (float) $totalfuturdep[0]["totalfuturdep"];

      // Obtener retiros futuros
      $totalfuturret = $wpdb->get_results("SELECT ROUND(SUM(rcon_cantidad_real), 2) AS totalfuturret FROM $tablaret WHERE rcon_usuario = $user AND rcon_status = 2 AND rcon_fecha_termino >= '".$fechafut."'" , ARRAY_A);
      $ttotalfuturret = (float) $totalfuturret[0]["totalfuturret"];

      $capini = $ultmescerrado["capini"];
      $tcapini = number_format($capini+$ttotalfuturdep,2);
      $utilacum = 0.00;
      $tutilacum = number_format($utilacum,2);
      $totalcierremes = $capini ;
      $ttotalcierremes = number_format($totalcierremes+$ttotalfuturdep-$ttotalfuturret,2);

    }else if (count($detalleregistros) >= 1) {

      $ultmescerrado = $detalleregistros[count($detalleregistros)-2];
      $ultmesc = (int)$ultmescerrado["mes"];
      $ultyearc = (int)$ultmescerrado["year"];

      if ($ultmesc == 12) {
        $mesfut = 1;
        $yearfut = $ultyearc+1;
      }else {
        $mesfut = $ultmesc+1;
        $yearfut = $ultyearc;
      }

      $fechafut = $yearfut."-".$mesfut."-01";

      // Obtener depositos futuros
      $totalfuturdep = $wpdb->get_results("SELECT ROUND(SUM(dcon_cantidad_real), 2) AS totalfuturdep FROM $tabladep WHERE dcon_usuario = $user AND dcon_status = 2 AND dcon_fecha_termino >= '".$fechafut."'" , ARRAY_A);
      $ttotalfuturdep = (float) $totalfuturdep[0]["totalfuturdep"];

      // Obtener retiros futuros
      $totalfuturret = $wpdb->get_results("SELECT ROUND(SUM(rcon_cantidad_real), 2) AS totalfuturret FROM $tablaret WHERE rcon_usuario = $user AND rcon_status = 2 AND rcon_fecha_termino >= '".$fechafut."'" , ARRAY_A);
      $ttotalfuturret = (float) $totalfuturret[0]["totalfuturret"];

      $capini = $ultmescerrado["capini"];
      $tcapini = number_format($capini+$ttotalfuturdep,2);
      $utilacum = $ultmescerrado["utilacumulada"];
      $tutilacum = number_format($utilacum,2);
      $totalcierremes = $ultmescerrado["totalcierremes"];
      $ttotalcierremes = number_format($totalcierremes+$ttotalfuturdep-$ttotalfuturret,2);

    }else{
      $capini = 0.00;
      $tcapini = number_format($capini,2);
      $utilacum = 0.00;
      $tutilacum = number_format($utilacum,2);
      $totalcierremes = 0.00;
      $ttotalcierremes = number_format($totalcierremes,2);
    }

    // echo "<pre>";
    // var_dump($totalfuturdep);
    // var_dump($detalleregistros);
    // echo "</pre>";
    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-admin.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Dashboard Conservador - <?php echo $userfullname ?></h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_conlistausuarios" ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver a la Lista de Usuarios</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>
                <!-- cajas superiores -->
                <div class="container-fluid px-0 my-4 pt-5 pb-4">
                  <div class="row">
                    <div class="col-12 col-md-4 ">
                      <div class="rd-cajasup rd-cajasup-azul">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-flag"></i></span>
                        <h4>Capital Principal</h4>
                        <span class="rd-cajasup-cifra">$<?php echo $tcapini ; ?></span>
                      </div>
                    </div>
                    <div class="col-12 col-md-4 ">
                      <div class="rd-cajasup rd-cajasup-verde">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-chart-line"></i></span>
                        <h4>Utilidad acumulada</h4>
                        <span class="rd-cajasup-cifra">$<?php echo $tutilacum ; ?></span>
                      </div>
                    </div>
                    <div class="col-12 col-md-4 ">
                      <div class="rd-cajasup rd-cajasup-naranja">
                        <span class="rd-cajasup-icon"><i class="fa-solid fa-file-circle-check"></i></span>
                        <h4>Total</h4>
                        <span class="rd-cajasup-cifra">$<?php echo $ttotalcierremes ; ?></span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Botones de deposito y retiro -->
                <div class="oper-master row mt-4 pt-3">
                  <div class="oper-mes oper-mes1 col-12 col-md-6 d-flex flex-column ">
                    <div class="caja-consdep d-flex ">
                      <button class='btn-addconsdep d-flex' id='btn-addconsdep' type='button' data-bs-toggle='modal' data-bs-target='#modal-addconsdep' >
                        <span class="oper-consdep"><i class="fa-solid fa-arrow-down"></i></span>
                        <div class="d-flex flex-column">
                          <span class="oper-titulo">Depósito</span>
                        </div>
                      </button>
                    </div>
                    <table class="wp-list-table widefat tab-ui striped tab-consdep" data-user="<?php echo $user; ?>">
                      <thead>
                        <tr>
                          <th class="manage_column" >Fecha</th>
                          <th class="manage_column" >Cantidad</th>
                          <th class="manage_column" >Status</th>
                        </tr>
                      </thead>

                      <tbody>

                      </tbody>
                    </table>
                  </div>
                  <div class="oper-mes oper-mes2 col-12 col-md-6 d-flex flex-column mt-4 mt-md-0">
                    <div class="caja-consret d-flex">
                      <button class='btn-addconsret d-flex' id='btn-addconsret' type='button' data-bs-toggle='modal' data-bs-target='#modal-addconsret' >
                        <span class="oper-consret"><i class="fa-solid fa-arrow-up"></i></span>
                        <div class="d-flex flex-column">
                          <span class="oper-titulo">Retiro</span>
                        </div>
                      </button>
                    </div>
                    <table class="wp-list-table widefat tab-ui striped tab-consret" data-user="<?php echo $user; ?>">
                      <thead>
                        <tr>
                          <th class="manage_column" >Fecha</th>
                          <th class="manage_column" >Cantidad</th>
                          <th class="manage_column" >Status</th>
                        </tr>
                      </thead>

                      <tbody>

                      </tbody>
                    </table>
                  </div>
                </div>

                <br>
                <br>

                <div class="box-transparent">
                  <h3>Registro de utilidades:</h3>
                  <br>
                  <table class="wp-list-table widefat tab-ui dt-responsive striped tab-consutiluser">
                    <thead>
                      <tr>
                        <th class="manage_column" >#</th>
                        <th class="manage_column" >Año - Mes</th>
                        <th class="manage_column" >Depósitos</th>
                        <th class="manage_column" >Cap Principal</th>
                        <th class="manage_column" >Util</th>
                        <th class="manage_column" >Util Acum</th>
                        <th class="manage_column" >Retiros</th>
                        <th class="manage_column" >Total</th>
                        <th class="manage_column" >Status</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php
                      if (count($detalleregistros) != 0) {
                        foreach ($detalleregistros as $key => $value) { ?>
                          <tr>
                          <td><?php echo $key+1; ?></td>
                          <td><?php echo $value["year"]." - ".$value["tmes"]; ?></td>
                          <td><span class="verde btn-ver-dep" data-userid="<?php echo $user ?>" data-mes="<?php echo $value["mes"] ?>" data-agno="<?php echo $value["year"] ?>">+ $<?php echo number_format($value["depmes"], 2, '.', ','); ?></span></td>
                          <td>$<?php echo number_format($value["capini"], 2, '.', ','); ?></td>
                          <td>$<?php echo number_format($value["utilmes"], 2, '.', ','); ?></td>
                          <td>$<?php echo number_format($value["utilacumulada"], 2, '.', ','); ?></td>
                          <td><span class="rojo btn-ver-ret" data-userid="<?php echo $user ?>" data-mes="<?php echo $value["mes"] ?>" data-agno="<?php echo $value["year"]?>">- $<?php echo number_format($value["retmes"], 2, '.', ','); ?></span></td>
                          <td>$<?php echo number_format($value["totalcierremes"], 2, '.', ','); ?></td>
                          <td>% <?php echo $value["statusmes"]; ?></td>
                          </tr>
                        <?php }
                      } ?>
                    </tbody>
                  </table>
                  <br>
                  <hr>
                  <p>Notas: <br>
                    <ol>
                      <li>Los datos de las cajas superiores hacen referencia a los datos almacenados en el ultimo mes finalizado inmediato anterior.</li>
                      <li>La caja superior de "Capital Principal", representa el capital principal en el ultimo mes finalizado inmediato anterior más depositos autorizados futuros .</li>
                      <li>La caja superior de "Total", representa el total al cierre del ultimo mes finalizado inmediato anterior más depositos autorizados futuros menos retiros autorizados futuros.</li>
                      <li>Los datos de la columna "% Status", representan  el porcentaje de utilidad para dicho mes. Tome en cuenta que dicho status puede ser modificado por TheIncProject bajo criterio reservado.</li>
                    </ol>
                  </p>
                </div>


                <div class="modal fade modal-ui" id="modal-addconsdep" tabindex="-1" aria-labelledby="modal-addconsdepLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-addconsdepLabel">Solicitar depósito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-addconsdep" class="form-addconsdep" action="#" method="post">
                          <input type="hidden" id="userdep" name="userdep" value="<?php echo $user ?>">
                          <div class="campo">
                            <label for="dcon_cantidad">*Cantidad: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="dcon_cantidad" class="form-control" type="text" name="dcon_cantidad" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo-especial">
                            <input id="agregarconsdep" type="submit" name="agregarconsdep" class="button button-primary" value="Solicitar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade modal-ui" id="modal-addconsret" tabindex="-1" aria-labelledby="modal-addconsretLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title px-2" id="modal-addconsretLabel">Solicitar retiro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4" >
                        <form id="form-addconsret" class="form-addconsret" action="#" method="post">
                          <input type="hidden" id="userret" name="userret" value="<?php echo $user ?>">
                          <input type="hidden" id="totaldisp" name="totaldisp" value="10000">
                          <div class="campo">
                            <label for="rcon_cantidad">*Cantidad: </label>
                            <div class="input-group mb-3">
                              <!-- <span class="input-group-text">$</span> -->
                              <input id="rcon_cantidad" class="form-control" type="text" name="rcon_cantidad" value="0.00" data-inputmask="'mask':'-{0,1}9{1,20}.{0,1}9{0,2}'" data-mask required >
                            </div>
                          </div>
                          <div class="campo-especial">
                            <input id="agregarconsret" type="submit" name="agregarconsret" class="button button-primary" value="Solicitar">
                            <input type="hidden" name="oculto" value="1"><!-- con esto nos aseguramos de que cargue toda la pagina y el formulario no sea llenado por bots-->
                          </div>
                        </form>

                      </div>

                    </div>
                  </div>
                </div>

              </div><!-- ui-dashboard -->
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_adminconsdepositosmes(){
    if ( ! defined( 'ABSPATH' ) ) exit;

    $url = get_site_url();
    $user = (int) $_GET['id'];
    $useradmin = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    $useradmin_data = get_userdata( absint( $useradmin ) );
    $modagresivo = get_user_meta( $user, 'modconservador', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "conservador";
    $submenu = "";

    $usernombre = $useradmin_data->user_firstname;
    $usermail = $useradmin_data->user_email;

    $userfirstname = $user_data->user_firstname;
    $userlastname = $user_data->user_lastname;
    $userfullname = $userfirstname." ".$userlastname;

    $foto = get_user_meta( $useradmin, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }

    $mesint = (int) $_GET['m'];
    //$mes = printf('%02d', $mesint);
    $agno = $_GET['p'];
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );
    $tmes = $mesesNombre[$mesint];

    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-admin.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Depósitos - <?php echo $agno." ".$tmes; ?> - <?php echo $userfullname ?></h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_conuserdashboard&id=".$user ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>

                <div class="box-transparent">
                  <br>
                  <h4 class="mb-5">Lista de dep&oacute;sitos válidos:</h4>
                  <table class="wp-list-table widefat tab-ui dt-responsive striped tab-consuserdepmes" data-user="<?php echo $user; ?>">
                    <thead>
                      <tr>
                        <th class="manage_column" >#</th>
                        <th class="manage_column" >Cantidad</th>
                        <th class="manage_column" >Cantidad final</th>
                        <th class="manage_column" >Notas</th>
                        <th class="manage_column" >Status Dep</th>
                        <th class="manage_column" >Id Depósito a TD</th>
                        <th class="manage_column" >Id Depósito a Master</th>
                        <th class="manage_column" >Fecha solicitud</th>
                        <th class="manage_column" >Fecha autorización</th>
                      </tr>
                    </thead>

                    <tbody>
                    </tbody>
                  </table>

                </div>
              </div><!-- ui-dashboard -->
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function interfaz_adminconsretirosmes(){
    if ( ! defined( 'ABSPATH' ) ) exit;

    $url = get_site_url();
    $user = (int) $_GET['id'];
    $useradmin = get_current_user_id();
    $user_data = get_userdata( absint( $user ) );
    $useradmin_data = get_userdata( absint( $useradmin ) );
    $modagresivo = get_user_meta( $user, 'modconservador', true);
    // $modagrepart = get_user_meta( $user, 'modagresivopart', true);

    $menu = "conservador";
    $submenu = "";

    $usernombre = $useradmin_data->user_firstname;
    $usermail = $useradmin_data->user_email;

    $userfirstname = $user_data->user_firstname;
    $userlastname = $user_data->user_lastname;
    $userfullname = $userfirstname." ".$userlastname;

    $foto = get_user_meta( $useradmin, 'fotografia', true );

    if ($foto) {
      $fotourl = $foto['url'];
    }else{
      $fotourl = plugin_dir_url( __DIR__ ) . 'assets/img/user-foto.png';
    }

    $mesint = (int) $_GET['m'];
    //$mes = printf('%02d', $mesint);
    $agno = $_GET['p'];
    $mesesNombre = array('0' => 'Ninguno' , '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre' );
    $tmes = $mesesNombre[$mesint];

    ?>
    <div class="wrap">
      <div class="container-fluid">
        <div class="row">

          <?php require CRC_DIR_PATH . 'template-parts/menu-admin.php'; ?>

          <div class="col-12 col-md-9 antimenu-dashboard" >
            <div class="container-fluid">
              <div class="ui-titulo my-3">
                <h1>Retiros - <?php echo $agno." ".$tmes; ?> - <?php echo $userfullname ?> </h1>
              </div>

              <div class="ui-dashboard">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between py-0">
                  <div class="">
                    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_conuserdashboard&id=".$user ?>" class="volverref" ><i class="fa-solid fa-caret-left"></i>Volver al Dashboard</a>
                  </div>
                  <div class="lista-botones">
                  </div>
                </div>

                <div class="box-transparent">
                  <br>
                  <h4 class="mb-5">Lista de retiros válidos:</h4>
                  <table class="wp-list-table widefat tab-ui dt-responsive striped tab-consuserretmes" data-user="<?php echo $user; ?>">
                    <thead>
                      <tr>
                        <th class="manage_column" >#</th>
                        <th class="manage_column" >Cantidad</th>
                        <th class="manage_column" >Cantidad final</th>
                        <th class="manage_column" >Notas</th>
                        <th class="manage_column" >Status Ret</th>
                        <th class="manage_column" >Id Retiro a TD</th>
                        <th class="manage_column" >Fecha solicitud</th>
                        <th class="manage_column" >Fecha autorización</th>
                      </tr>
                    </thead>

                    <tbody>
                    </tbody>
                  </table>

                </div>
              </div><!-- ui-dashboard -->
              <hr>
            </div>

          </div><!-- antimenu-dashboard -->
        </div>
      </div>
    </div><!-- wrap -->

    <?php
  }

  public function agregar_menuadminretiros(){
    add_submenu_page( 'crc_admin_inversiones',
    'Control de Retiros',
    'Control de Retiros',
    'administrator',
    'crc_admin_retiros',
    [ $this, 'interfaz_adminretiros' ] );

  }

  public function agregar_menuadmindepositos(){
    add_submenu_page( 'crc_admin_inversiones',
    'Control de Depósitos',
    'Control de Depósitos',
    'administrator',
    'crc_admin_depositos',
    [ $this, 'interfaz_admindepositos' ] );

  }

  public function agregar_menuadminconmaster(){
    add_submenu_page( 'crc_admin_inversiones',
    'Control Maestro',
    'Control Maestro',
    'administrator',
    'crc_admin_master',
    [ $this, 'interfaz_admincontrolmaster' ] );

  }

  public function agregar_menuadmindepmaster(){
    add_submenu_page( 'crc_admin_inversiones',
    'Depósitos Master',
    'Depósitos Master',
    'administrator',
    'crc_admin_depmas',
    [ $this, 'interfaz_admindepmaster' ] );

  }

  public function agregar_menuadminretmaster(){
    add_submenu_page( 'crc_admin_inversiones',
    'Retiros Master',
    'Retiros Master',
    'administrator',
    'crc_admin_retmas',
    [ $this, 'interfaz_adminretmaster' ] );

  }

  public function agregar_reportemensual(){
    add_submenu_page( 'crc_admin_inversiones',
    'Reporte Mensual',
    'Reporte Mensual',
    'administrator',
    'crc_admin_repmensual',
    [ $this, 'interfaz_reporteadormensual' ] );

  }

  public function agregar_menuadminuserdashboard(){
    add_submenu_page( null,
    'User Dashboard',
    'User Dashboard',
    'administrator',
    'crc_admin_userdashboard',
    [ $this, 'interfaz_adminuserdashboard' ] );

  }

  public function agregar_menuadminverdepmes(){
    add_submenu_page( null,
    'User Depositos Mes',
    'User Depositos Mes',
    'administrator',
    'crc_admin_verdepmes',
    [ $this, 'interfaz_adminverdepmes' ] );

  }

  public function agregar_menuadminverretmes(){
    add_submenu_page( null,
    'User Retiros Mes',
    'User Retiros Mes',
    'administrator',
    'crc_admin_verretmes',
    [ $this, 'interfaz_adminverretmes' ] );

  }

  public function agregar_menuadminverdepmasmes(){
    add_submenu_page( null,
    'Depositos Mes Master',
    'Depositos Mes Master',
    'administrator',
    'crc_admin_verdepmasmes',
    [ $this, 'interfaz_adminverdepmasmes' ] );

  }

  public function agregar_menuadminverretmasmes(){
    add_submenu_page( null,
    'Retiros Mes Master',
    'Retiros Mes Master',
    'administrator',
    'crc_admin_verretmasmes',
    [ $this, 'interfaz_adminverretmasmes' ] );

  }

  public function agregar_menuadminverutilrefmes(){
    add_submenu_page( null,
    'Utilidades de Referidos del Usuario',
    'Utilidades de Referidos del Usuario',
    'administrator',
    'crc_admin_verutilrefmes',
    [ $this, 'interfaz_adminverutilrefmes' ] );

  }

  public function agregar_menuadminrefusersnormal(){
    add_submenu_page( 'crc_referral_principal',
    'Usuarios Normal',
    'Usuarios Normal',
    'administrator',
    'crc_referral_dashboard',
    [ $this, 'interfaz_referral_dashboard' ] );

  }

  public function agregar_menuadminrefusersspecial(){
    add_submenu_page( 'crc_referral_principal',
    'Usuarios VIP',
    'Usuarios VIP',
    'administrator',
    'crc_referral_dashboardspe',
    [ $this, 'interfaz_referral_dashboardspe' ] );

  }

  public function agregar_menuadminrefvercuenta(){
    add_submenu_page( null,
    'Cuenta del Usuario BL',
    'Cuenta del Usuario BL',
    'administrator',
    'crc_admin_vercuentabl',
    [ $this, 'interfaz_adminrefvercuenta' ] );

  }

  public function agregar_menuadminrefvercuentaspe(){
    add_submenu_page( null,
    'Cuenta del Usuario BL Special',
    'Cuenta del Usuario BL Special',
    'administrator',
    'crc_admin_vercuentablspe',
    [ $this, 'interfaz_adminrefvercuentaspe' ] );

  }

  public function agregar_menuadminreftotalcuentas(){
    add_submenu_page( null,
    'Reporte total de cuentas',
    'Reporte total de cuentas',
    'administrator',
    'crc_admin_vertotalcuentasbl',
    [ $this, 'interfaz_adminreftotalcuentas' ] );

  }

  public function agregar_menuadminreftotalcuentasspe(){
    add_submenu_page( null,
    'Reporte total de cuentas',
    'Reporte total de cuentas',
    'administrator',
    'crc_admin_vertotalcuentasspebl',
    [ $this, 'interfaz_adminreftotalcuentasspe' ] );

  }

  public function agregar_menuadminrefdetallemes(){
    add_submenu_page( null,
    'Reporte Mensual de cuentas',
    'Reporte Mensual de cuentas',
    'administrator',
    'crc_admin_verdetallemesbl',
    [ $this, 'interfaz_adminrefdetallemes' ] );

  }

  public function agregar_menuadminrefdetallemesspe(){
    add_submenu_page( null,
    'Reporte Mensual de cuentas',
    'Reporte Mensual de cuentas',
    'administrator',
    'crc_admin_verdetallemesspebl',
    [ $this, 'interfaz_adminrefdetallemesspe' ] );

  }

  public function agregar_menuadminrefreportemesspe(){
    add_submenu_page( null,
    'Reporte Mensual Global de cuentas',
    'Reporte Mensual Global de cuentas',
    'administrator',
    'crc_admin_verreportemesspebl',
    [ $this, 'interfaz_reporteadormensualrefcuespe' ] );

  }

  public function agregar_menuadminrefreportemes(){
    add_submenu_page( null,
    'Reporte Mensual Global de cuentas',
    'Reporte Mensual Global de cuentas',
    'administrator',
    'crc_admin_verreportemesbl',
    [ $this, 'interfaz_reporteadormensualrefcue' ] );

  }

  public function agregar_menuadminrefnftprojects(){
    add_submenu_page( null,
    'NFT Project',
    'NFT Project',
    'administrator',
    'crc_admin_vernftproject',
    [ $this, 'interfaz_adminrefvernftproject' ] );

  }

  public function agregar_menuadminrefnftverdetallemes(){
    add_submenu_page( null,
    'NFT Project',
    'NFT Project',
    'administrator',
    'crc_admin_verdetallemesnft',
    [ $this, 'interfaz_adminrefregistrosnftmes' ] );

  }

  public function agregar_menuadminrefverdetallemesvar(){
    add_submenu_page( null,
    'Reporte Mensual de Ingresos',
    'Reporte Mensual de Ingresos',
    'administrator',
    'crc_admin_verdetallemesvar',
    [ $this, 'interfaz_adminrefingresosvarmes' ] );

  }

  public function agregar_menuadminagrdepositos(){
    add_submenu_page( 'crc_agresivo_control',
    'Control de Depósitos',
    'Control de Depósitos',
    'administrator',
    'crc_admin_agrdepositos',
    [ $this, 'interfaz_agreadmindepositos' ] );
  }

  public function agregar_menuadminagrretiros(){
    add_submenu_page( 'crc_agresivo_control',
    'Control de Retiros',
    'Control de Retiros',
    'administrator',
    'crc_admin_agrretiros',
    [ $this, 'interfaz_agreadminretiros' ] );
  }

  public function agregar_menuagrdepmaster(){
    add_submenu_page( 'crc_agresivo_control',
    'Depósitos Master',
    'Depósitos Master',
    'administrator',
    'crc_admin_agrdepmaster',
    [ $this, 'interfaz_agredepmaster' ] );
  }

  public function agregar_menuagrretmaster(){
    add_submenu_page( 'crc_agresivo_control',
    'Retiros Master',
    'Retiros Master',
    'administrator',
    'crc_admin_agrretmaster',
    [ $this, 'interfaz_agreretmaster' ] );
  }

  public function agregar_menuadminverdepagrmes(){
    add_submenu_page( null,
    'Detalle Depósitos Mes',
    'Detalle Depósitos Mes',
    'administrator',
    'crc_admin_verdepagrmes',
    [ $this, 'interfaz_adminverdepagrmes' ] );

  }

  public function agregar_menuadminverretagrmes(){
    add_submenu_page( null,
    'Detalle Retiros Mes',
    'Detalle Retiros Mes',
    'administrator',
    'crc_admin_verretagrmes',
    [ $this, 'interfaz_adminverretagrmes' ] );

  }

  public function agregar_menuadminagrverinvestorsmes(){
    add_submenu_page( null,
    'Detalle Inversionistas Mes',
    'Detalle Inversionistas Mes',
    'administrator',
    'crc_admin_verinvagrmes',
    [ $this, 'interfaz_adminagrinvmes' ] );

  }

  public function agregar_menuadminagrlistauser(){
    add_submenu_page( null,
    'Lista de usuarios',
    'Lista de usuarios',
    'administrator',
    'crc_admin_agrlistausuarios',
    [ $this, 'interfaz_agrlistausuarios' ] );

  }

  public function agregar_menuadminagruserdashboard(){
    add_submenu_page( null,
    'Dashboard del usuario',
    'Dashboard del usuario',
    'administrator',
    'crc_admin_agruserdashboard',
    [ $this, 'interfaz_agruserdashboard' ] );

  }

  public function agregar_menuadmincondepositos(){
    add_submenu_page( 'crc_conservador_control',
    'Control de Depósitos',
    'Control de Depósitos',
    'administrator',
    'crc_admin_condepositos',
    [ $this, 'interfaz_consadmindepositos' ] );
  }

  public function agregar_menuadminconretiros(){
    add_submenu_page( 'crc_conservador_control',
    'Control de Retiros',
    'Control de Retiros',
    'administrator',
    'crc_admin_conretiros',
    [ $this, 'interfaz_consadminretiros' ] );
  }

  public function agregar_menucondepmaster(){
    add_submenu_page( 'crc_conservador_control',
    'Depósitos Master',
    'Depósitos Master',
    'administrator',
    'crc_admin_condepmaster',
    [ $this, 'interfaz_consdepmaster' ] );
  }

  public function agregar_menuconretmaster(){
    add_submenu_page( 'crc_conservador_control',
    'Retiros Master',
    'Retiros Master',
    'administrator',
    'crc_admin_conretmaster',
    [ $this, 'interfaz_consretmaster' ] );
  }

  public function agregar_menuadminconlistauser(){
    add_submenu_page( 'crc_conservador_control',
    'Lista de usuarios',
    'Lista de usuarios',
    'administrator',
    'crc_admin_conlistausuarios',
    [ $this, 'interfaz_conslistausuarios' ] );

  }

  public function agregar_menuadminconuserdashboard(){
    add_submenu_page( null,
    'Dashboard del usuario',
    'Dashboard del usuario',
    'administrator',
    'crc_admin_conuserdashboard',
    [ $this, 'interfaz_conuserdashboard' ] );

  }

  public function agregar_adminconsverdepmes(){
    add_submenu_page( null,
    'User Depósitos Mes',
    'User Depósitos Mes',
    'administrator',
    'crc_consadminverdepmes',
    [ $this, 'interfaz_adminconsdepositosmes' ] );
  }

  public function agregar_adminconsverretmes(){
    add_submenu_page( null,
    'User Retiros Mes',
    'User Retiros Mes',
    'administrator',
    'crc_consadminverretmes',
    [ $this, 'interfaz_adminconsretirosmes' ] );
  }

}
