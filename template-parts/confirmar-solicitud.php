<?php

  $nombre = '';
  $cantidad = '';
  $tipo = '';
  $fecha_cuando = '';

?>
<?php get_header(); ?>

<div class="page_confirmacion">
  <div class="espera_confirmacion">
    <div class="sk-cube-grid">
      <div class="sk-cube sk-cube1"></div>
      <div class="sk-cube sk-cube2"></div>
      <div class="sk-cube sk-cube3"></div>
      <div class="sk-cube sk-cube4"></div>
      <div class="sk-cube sk-cube5"></div>
      <div class="sk-cube sk-cube6"></div>
      <div class="sk-cube sk-cube7"></div>
      <div class="sk-cube sk-cube8"></div>
      <div class="sk-cube sk-cube9"></div>
    </div>
  </div>
  <div id="notificacion_confirmacion">
    <a class="logo_theinc" href="<?php echo wp_login_url(); ?>"></a>
    <?php echo '<h3 class="exito-confirmacion">'. sprintf('Gracias!, <span class="nombreDOM"></span> tu solicitud de <span class="tipoDOM"></span> ha sido confirmada con éxito y sera procesada a la brevedad.', 'registrousuario' ) . '</h3><p>' . sprintf( 'Tus datos de solicitud son los siguientes:', 'registrousuario'  ) . '<ul><li><strong>Cantidad: </strong><span class="cantidadDOM"></span></li><li><strong>Fecha en que aplica: </strong><span class="fechaDOM"></span></li></ul></p>'; ?>
  </div>
  <div id="notificacion_imposible">
    <a class="logo_theinc" href="<?php echo wp_login_url(); ?>"></a>
    <?php echo '<h3 class="exito-confirmacion">'. sprintf('<span class="mensajeDOM"></span>', 'registrousuario' ) . '</h3>'; ?>
  </div>
  <div id="notificacion_error">
    <a class="logo_theinc" href="<?php echo wp_login_url(); ?>"></a>
    <?php echo '<h3 class="exito-confirmacion">'. sprintf('El enlace de confirmación ya ha sido utilizado o bien no pudo registrarse la confirmación. Por favor intente nuevamente más tarde', 'registrousuario' ) . '</h3>'; ?>
  </div>
</div>



<?php get_footer(); ?>
