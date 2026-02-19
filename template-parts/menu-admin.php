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
        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 <?php echo ( $menu == 'interes' ? 'boton-activo' : ''); ?>" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false" >
          Interés Compuesto
        </button>
        <div class="collapse <?php echo ( $menu == 'interes' ? 'show' : ''); ?>" id="home-collapse" style="">
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
        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 <?php echo ( $menu == 'agresivo' ? 'boton-activo' : ''); ?>" data-bs-toggle="collapse" data-bs-target="#agr-collapse" aria-expanded="false">
          Agresivo
        </button>
        <div class="collapse <?php echo ( $menu == 'agresivo' ? 'show' : ''); ?>" id="agr-collapse" style="">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
            <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_agresivo_control"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none <?php echo ( $submenu == 'agreadmincontrol' ? 'menu-activo' : ''); ?>">Dashboard Principal</a></li>
            <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_agrlistausuarios"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none <?php echo ( $submenu == 'listagresiva' ? 'menu-activo' : ''); ?>">Lista de usuarios</a></li>
            <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_agrdepositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none <?php echo ( $submenu == 'agreadmindepositos' ? 'menu-activo' : ''); ?>">Control de Depósitos</a></li>
            <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_agrretiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none <?php echo ( $submenu == 'agreadminretiros' ? 'menu-activo' : ''); ?>">Control de Retiros</a></li>
            <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_agrdepmaster"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none <?php echo ( $submenu == 'agredepositosmaster' ? 'menu-activo' : ''); ?>">Depósitos Master</a></li>
            <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_agrretmaster"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none <?php echo ( $submenu == 'agrearetirosmaster' ? 'menu-activo' : ''); ?>">Retiros Master</a></li>
          </ul>
        </div>
      </li>
      <li class="">
        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 <?php echo ( $menu == 'conservador' ? 'boton-activo' : ''); ?>" data-bs-toggle="collapse" data-bs-target="#con-collapse" aria-expanded="false">
          Conservador
        </button>
        <div class="collapse <?php echo ( $menu == 'conservador' ? 'show' : ''); ?>" id="con-collapse" style="">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
            <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_conservador_control"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none <?php echo ( $submenu == 'consadmincontrol' ? 'menu-activo' : ''); ?>">Dashboard Principal</a></li>
            <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_conlistausuarios"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none <?php echo ( $submenu == 'listconsvedor' ? 'menu-activo' : ''); ?>">Lista de usuarios</a></li>
            <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_condepositos"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none <?php echo ( $submenu == 'consadmindepositos' ? 'menu-activo' : ''); ?>">Control de Depósitos</a></li>
            <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_conretiros"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none <?php echo ( $submenu == 'consadminretiros' ? 'menu-activo' : ''); ?>">Control de Retiros</a></li>
            <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_condepmaster"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none <?php echo ( $submenu == 'consdepositosmaster' ? 'menu-activo' : ''); ?>">Historial Depósitos</a></li>
            <li><a href="<?php echo $url."/wp-admin/admin.php?page=crc_admin_conretmaster"; ?>" class="link-body-emphasis d-inline-flex text-decoration-none <?php echo ( $submenu == 'consaretirosmaster' ? 'menu-activo' : ''); ?>">Historial Retiros</a></li>
          </ul>
        </div>
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
