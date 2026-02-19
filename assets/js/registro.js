$ = jQuery.noConflict();
//
// Tabs Toggler
//

( function( $ ) {


  $( document ).ready(function() {

    var codigo = getParameterByName("user_submitted");
    const formregister = $( '#formulario_registro' );
    const dataregister = $( '#notificacion_registro' );

    if( codigo !== "" ){
      $(formregister).hide();
      $(dataregister).show();
    }
  });



  let nickname = $('#crc_usuario_registrar #crc_usuario_nickname');
  let email = $('#crc_usuario_registrar #crc_usuario_email');
  let pais = $('#crc_usuario_registrar #crc_usuario_pais');
  let estado = $('#crc_usuario_registrar #crc_usuario_estado');
  let municipio = $('#crc_usuario_registrar #crc_usuario_municipio');

  $(estado).attr('disabled', 'disabled');

  //Activacion Select2
	//$(estado).select2();
	//$(municipio).select2();

  $(nickname).on('blur', verificarUser);
  $(email).on('blur', verificarUser);
  $(pais).on('change',cambiarUsaMex );

  function verificarUser(evento) {
	  evento.preventDefault();

    console.log("Aqui verificación");
    var padre = $(this).parent();
    var texto = $(this).val().trim();
    var tipo = $(this).attr('id');
    console.log(padre);
    console.log(texto);
    console.log(tipo);

    if(tipo == 'crc_usuario_email'){
      var hayuser = $(this).parent().find('.hay-user');
      var loading = $(this).parent().find('.mascotas-load');


      $(loading).show();
      $(hayuser).css('display','none');

      var postData = {
        action: 'buscarUserRegEmail',
        codigo: texto,
        tipo: "email"
      }

      jQuery.ajax({
        url: admin_url.ajax_url,
        type: 'post',
        data:postData
      }).done(function(response) {
        console.log(response);
        if(response[0].existe == false){
          // console.log(response[0]);

          $(loading).hide();
        }else{
          $(padre).find( "#crc_usuario_email" ).val('');
          $(loading).hide();
          $(hayuser).css('display','block');
        }
      });
    }else{
      var hayuser = $(this).parent().find('.hay-user');
      var loading = $(this).parent().find('.mascotas-load');


      $(loading).show();
      $(hayuser).css('display','none');

      var postData = {
        action: 'buscarUserRegNick',
        codigo: texto,
        tipo: "nickname"
      }

      jQuery.ajax({
        url: admin_url.ajax_url,
        type: 'post',
        data:postData
      }).done(function(response) {
        console.log(response);

        if(response[0].existe == false){
          // console.log(response[0]);

          $(loading).hide();
        }else{
          $(padre).find( "#crc_usuario_nickname" ).val('');
          $(loading).hide();
          $(hayuser).css('display','block');
        }
      });
    }


  }

  function cambiarUsaMex(e){

      e.preventDefault();

      let paisSel = $(this).val();

      if(paisSel == 'USA'){
        $('.cmb2-id-crc-usuario-municipio .cmb-th label').text('Ciudad:');
      }else{
        $('.cmb2-id-crc-usuario-municipio .cmb-th label').text('Municipio:');
      }

  }

  $(pais).on('change', mostrarestados);


  function mostrarciudades(evento) {
	  evento.preventDefault();

		// console.log(updatemasc);
		let estadoSel = $(this).val();

    if ( estadoSel != '') {
      // console.log(estadoSel);
      let municipiosList = municipiosJSON.full_data[0];
      // console.log(municipiosList[estadoSel]);
      $(municipio).attr('disabled', false);
      $(municipio).find("option").remove();
      // create the option and append to Select2
      var option = new Option("Seleccione un municipio", "", true, true);
      municipio.append(option).trigger('change');

      // manually trigger the `select2:select` event
      municipio.trigger({
          type: 'select2:select',
          params: {
              data: ""
          }
      });

      municipiosList[estadoSel].forEach((item, i) => {
        // console.log(item);

        $(municipio).append(`<option value="${item}">${item}</option>`);
      });
    }else{
      $(municipio).attr('disabled', 'disabled');
      $(municipio).find("option").remove();
      // create the option and append to Select2
      var option = new Option("Seleccione un municipio", "", true, true);
      municipio.append(option).trigger('change');

      // manually trigger the `select2:select` event
      municipio.trigger({
          type: 'select2:select',
          params: {
              data: ""
          }
      });
    }

	}

  function mostrarestados(evento) {
	  evento.preventDefault();

		// console.log(updatemasc);
		let paisSel = $(this).val();

    if ( paisSel != '') {
      // console.log(estadoSel);
      let estadosList = municipiosJSON.full_data;
      // console.log(municipiosList[estadoSel]);
      $(estado).attr('disabled', false);
      $(estado).find("option").remove();

      $(estado).append(`<option value="" selected>Seleccione una opción</option>`);
      //console.log(estadosList[0].MEX);
      if (paisSel == 'MEX') {
        estadosList[0].MEX.forEach((item, i) => {
          // console.log(item);

          $(estado).append(`<option value="${item.clave}">${item.nombre}</option>`);
        });
      }else{
        estadosList[1].USA.forEach((item, i) => {
          // console.log(item);

          $(estado).append(`<option value="${item.clave}">${item.nombre}</option>`);
        });
      }

    }else{
      $(estado).attr('disabled', 'disabled');
      $(estado).find("option").remove();
      $(estado).append(`<option value="" selected>Seleccione una opción</option>`);
    }

	}


  function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
  }

}( jQuery ) );
