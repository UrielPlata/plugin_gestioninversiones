<?php
$url1 = get_site_url();
$user1 = get_current_user_id();
$user_data1 = get_userdata( absint( $user1 ) );
$modconsacce = get_user_meta( $user1, 'modconservador', true);
$modagreacce = get_user_meta( $user1, 'modagresivo', true);
 ?>
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
    <a href="<?php echo $url."/wp-admin/admin.php?page=crc_userdashboard"; ?>" class="collapsed border-bottom  text-decoration-none"><button class="btn btn-link ham-primario2 btn-block m-0">Home</button></a>
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
        <a href="<?php echo $url."/wp-admin/admin.php?page=crc_userdashboard"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed " >
          Home
        </button>
        </a>
      </li>
      <li class="">
        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
          Interés Compuesto
        </button>
        <div class="collapse" id="home-collapse" style="">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
            <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_inversiones"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Dashboard</a></li>
            <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none ">Historial de Retiros</a></li>
            <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  ">Historial de Depósitos</a></li>
          </ul>
        </div>
      </li>
      <?php if ($modagreacce == 1) { ?>
      <li class="">
        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 <?php echo ( $menu == 'agresivo' ? 'boton-activo' : ''); ?>" data-bs-toggle="collapse" data-bs-target="#agresivo-collapse" aria-expanded="false">
          Agresivo
        </button>
        <div class="collapse <?php echo ( $menu == 'agresivo' ? 'show' : ''); ?>" id="agresivo-collapse" style="">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
            <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_agresivo"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none <?php echo ( $submenu == 'agredashboard' ? 'menu-activo' : ''); ?>">Dashboard</a></li>
            <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_agre_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none <?php echo ( $submenu == 'agrehistodepositos' ? 'menu-activo' : ''); ?>">Historial de Depósitos</a></li>
            <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_agre_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none <?php echo ( $submenu == 'agrehistoretiros' ? 'menu-activo' : ''); ?>">Historial de Retiros</a></li>
          </ul>
        </div>
      </li>
      <?php } ?>
      <?php if ($modconsacce == 1) { ?>
      <li class="">
        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 <?php echo ( $menu == 'conservador' ? 'boton-activo' : ''); ?>" data-bs-toggle="collapse" data-bs-target="#conservador-collapse" aria-expanded="false">
          Conservador
        </button>
        <div class="collapse <?php echo ( $menu == 'conservador' ? 'show' : ''); ?>" id="conservador-collapse" style="">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
            <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_conservador"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none <?php echo ( $submenu == 'consdashboard' ? 'menu-activo' : ''); ?>">Dashboard</a></li>
            <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_cons_retiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none <?php echo ( $submenu == 'conshistoretiros' ? 'menu-activo' : ''); ?>">Historial de Retiros</a></li>
            <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_cons_depositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none  <?php echo ( $submenu == 'conshistodepositos' ? 'menu-activo' : ''); ?>">Historial de Depósitos</a></li>
          </ul>
        </div>
      </li>
      <?php } ?>
      <li class="">
        <a href="<?php echo $url."/wp-admin/profile.php"; ?>" class="text-decoration-none"><button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" >
          Perfil
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
