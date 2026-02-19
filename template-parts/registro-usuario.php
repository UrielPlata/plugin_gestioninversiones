<?php get_header(); ?>

<div class="page_registro">
  <div id="notificacion_registro">
    <a class="logo_theinc" href="<?php echo wp_login_url(); ?>"></a>
    <?php echo do_shortcode( '[crc_registrar_usuario_shortcode id="mid-mascota-registrar-mascota" exito="true"]' ); ?>
    <a  href="<?php echo wp_login_url(); ?>"><button class="button-primary">Ingresar</button></a>
  </div>
   <div id="formulario_registro">
     <a class="logo_theinc" href="<?php echo wp_login_url(); ?>"></a>
     <?php echo do_shortcode( '[crc_registrar_usuario_shortcode id="mid-mascota-registrar-mascota" exito="false"]' ); ?>
   </div>
   <a class="yetuser" href="<?php echo wp_login_url(); ?>">¿Ya tienes una cuenta? <br><span>Ingresa.<span></a>
</div>



<?php get_footer(); ?>
