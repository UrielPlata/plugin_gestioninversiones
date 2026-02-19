$ = jQuery.noConflict();
//
// Tabs Toggler
//

( function( $ ) {

  $( document ).ready(function() {

    const espera = $('.espera_confirmacion');
    const notconfirm = $('#notificacion_confirmacion');
    const noterror = $('#notificacion_error');
    const notchangenow = $('#notificacion_imposible');
    const nombreDOM = $('.nombreDOM');
    const tipoDOM = $('.tipoDOM');
    const cantidadDOM = $('.cantidadDOM');
    const fechaDOM = $('.fechaDOM');
    const mensajeDOM = $('.mensajeDOM');

    let codigo = getParameterByName("code_submitted");
    let tipo = getParameterByName("tipo");

      if(codigo != ''){
        if (tipo == 'dep') {
          var postData = {
            action: 'confirmSolicitud',
            codigo: codigo,
            tipo: "deposito"
          }

          jQuery.ajax({
            url: confirm_url.ajax_url,
            type: 'post',
            data:postData
          }).done(function(response) {
            let respuesta = JSON.parse(response);
            console.log(respuesta);
            if(respuesta["respuesta"] == 'bien'){

              $(espera).hide();
              $(nombreDOM).text(respuesta["nombre"]);
              $(tipoDOM).text(respuesta["tipo"]);
              $(fechaDOM).text(respuesta["fecha_cuando"]);
              $(cantidadDOM).text(respuesta["cantidad"]);
              $(notconfirm).show();
            }else if(respuesta["respuesta"] == 'imposible' || respuesta["respuesta"] == 'no-encontrado'){
              $(espera).hide();
              $(mensajeDOM).text(respuesta["texto"]);
              $(notchangenow).show();
            }else{
              $(espera).hide();
              $(noterror).show();
            }
          });
        }else if (tipo == 'agrdep') {
          var postData = {
            action: 'confirmSolicitud',
            codigo: codigo,
            tipo: "agrdeposito"
          }

          jQuery.ajax({
            url: confirm_url.ajax_url,
            type: 'post',
            data:postData
          }).done(function(response) {
            let respuesta = JSON.parse(response);
            console.log(respuesta);
            if(respuesta["respuesta"] == 'bien'){

              $(espera).hide();
              $(nombreDOM).text(respuesta["nombre"]);
              $(tipoDOM).text(respuesta["tipo"]);
              $(fechaDOM).text(respuesta["fecha_cuando"]);
              $(cantidadDOM).text(respuesta["cantidad"]);
              $(notconfirm).show();
            }else if(respuesta["respuesta"] == 'imposible' || respuesta["respuesta"] == 'no-encontrado'){
              $(espera).hide();
              $(mensajeDOM).text(respuesta["texto"]);
              $(notchangenow).show();
            }else{
              $(espera).hide();
              $(noterror).show();
            }
          });

        }else if (tipo == 'agrret') {
          var postData = {
            action: 'confirmSolicitud',
            codigo: codigo,
            tipo: "agrretiro"
          }

          jQuery.ajax({
            url: confirm_url.ajax_url,
            type: 'post',
            data:postData
          }).done(function(response) {
            let respuesta = JSON.parse(response);
            console.log(respuesta);
            if(respuesta["respuesta"] == 'bien'){

              $(espera).hide();
              $(nombreDOM).text(respuesta["nombre"]);
              $(tipoDOM).text(respuesta["tipo"]);
              $(fechaDOM).text(respuesta["fecha_cuando"]);
              $(cantidadDOM).text(respuesta["cantidad"]);
              $(notconfirm).show();
            }else if(respuesta["respuesta"] == 'imposible' || respuesta["respuesta"] == 'no-encontrado'){
              $(espera).hide();
              $(mensajeDOM).text(respuesta["texto"]);
              $(notchangenow).show();
            }else{
              $(espera).hide();
              $(noterror).show();
            }
          });

        }else if (tipo == 'condep') {
          var postData = {
            action: 'confirmSolicitud',
            codigo: codigo,
            tipo: "condeposito"
          }

          jQuery.ajax({
            url: confirm_url.ajax_url,
            type: 'post',
            data:postData
          }).done(function(response) {
            let respuesta = JSON.parse(response);
            console.log(respuesta);
            if(respuesta["respuesta"] == 'bien'){

              $(espera).hide();
              $(nombreDOM).text(respuesta["nombre"]);
              $(tipoDOM).text(respuesta["tipo"]);
              $(fechaDOM).text(respuesta["fecha_cuando"]);
              $(cantidadDOM).text(respuesta["cantidad"]);
              $(notconfirm).show();
            }else if(respuesta["respuesta"] == 'imposible' || respuesta["respuesta"] == 'no-encontrado'){
              $(espera).hide();
              $(mensajeDOM).text(respuesta["texto"]);
              $(notchangenow).show();
            }else{
              $(espera).hide();
              $(noterror).show();
            }
          });

        }else if (tipo == 'conret') {
          var postData = {
            action: 'confirmSolicitud',
            codigo: codigo,
            tipo: "conretiro"
          }

          jQuery.ajax({
            url: confirm_url.ajax_url,
            type: 'post',
            data:postData
          }).done(function(response) {
            let respuesta = JSON.parse(response);
            console.log(respuesta);
            if(respuesta["respuesta"] == 'bien'){

              $(espera).hide();
              $(nombreDOM).text(respuesta["nombre"]);
              $(tipoDOM).text(respuesta["tipo"]);
              $(fechaDOM).text(respuesta["fecha_cuando"]);
              $(cantidadDOM).text(respuesta["cantidad"]);
              $(notconfirm).show();
            }else if(respuesta["respuesta"] == 'imposible' || respuesta["respuesta"] == 'no-encontrado'){
              $(espera).hide();
              $(mensajeDOM).text(respuesta["texto"]);
              $(notchangenow).show();
            }else{
              $(espera).hide();
              $(noterror).show();
            }
          });

        }else{
          var postData = {
            action: 'confirmSolicitud',
            codigo: codigo,
            tipo: "retiro"
          }

          jQuery.ajax({
            url: confirm_url.ajax_url,
            type: 'post',
            data:postData
          }).done(function(response) {

            let respuesta = JSON.parse(response);
            if(respuesta["respuesta"] == 'bien'){
              $(espera).hide();
              $(nombreDOM).text(respuesta["nombre"]);
              $(tipoDOM).text(respuesta["tipo"]);
              $(fechaDOM).text(respuesta["fecha_cuando"]);
              $(cantidadDOM).text(respuesta["cantidad"]);
              $(notconfirm).show();
            }else if(respuesta["respuesta"] == 'imposible' || respuesta["respuesta"] == 'no-encontrado'){
              $(espera).hide();
              $(mensajeDOM).text(respuesta["texto"]);
              $(notchangenow).show();
            }else{
              $(espera).hide();
              $(noterror).show();
            }
          });
        }

      }

  });

function getParameterByName(name) {
  name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
  results = regex.exec(location.search);
  return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

}( jQuery ) );
