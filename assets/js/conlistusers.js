$ = jQuery.noConflict();

( function( $ ) {

  $(".tab-conlistusers").on('click','.btn-proyeccion', function(e){
    var id = $(this).attr('data-usuario');

    let protocol = window.location.protocol;
		let url = "admin.php?page=crc_admin_conuserdashboard&id="+id;

    $(location).attr('href',url);
  });

  $(".tab-conlistusers").on('click','.btn-perfil', function(e){
    var id = $(this).attr('data-usuario');

    let protocol = window.location.protocol;
		let url = "user-edit.php?user_id="+id;

    $(location).attr('href',url);
  });

}( jQuery ) );
