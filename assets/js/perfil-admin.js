$ = jQuery.noConflict();
//
// Tabs Toggler
//

( function( $ ) {

  // Activamos los switch
  $("#modagresivo").simpleSwitch();

  $("#modagresivo").on('click', e => {
    let activo = $("#modagresivo").attr('data-switch');

    if(activo == "true") {
      $("#modagresivo").attr('checked',true);
    }else {
      $("#modagresivo").attr('checked',false);
    }
  });

  $("#modagresivopart").simpleSwitch();

  $("#modagresivopart").on('click', e => {
    let activo = $("#modagresivopart").attr('data-switch');

    if(activo == "true") {
      $("#modagresivopart").attr('checked',true);
    }else {
      $("#modagresivopart").attr('checked',false);
    }
  });

  $("#modconservador").simpleSwitch();

  $("#modconservador").on('click', e => {
    let activo = $("#modconservador").attr('data-switch');

    if(activo == "true") {
      $("#modconservador").attr('checked',true);
    }else {
      $("#modconservador").attr('checked',false);
    }
  });

  let pais = $('#profile-page #pais');
  let estado = $('#profile-page #estado');

  $(estado).attr('disabled', 'disabled');

  $(pais).on('change',cambiarUsaMex );

  function cambiarUsaMex(e){

      e.preventDefault();

      let paisSel = $(this).val();

      if(paisSel == 'USA'){
        $('.user-municipio-wrap th label').text('Ciudad:');
      }else{
        $('.user-municipio-wrap th label').text('Municipio:');
      }

  }

  $(pais).on('change', mostrarestados);

  function mostrarestados(evento) {
	  evento.preventDefault();

		// console.log(updatemasc);
		let paisSel = $(this).val();

    if ( paisSel != '') {
      // console.log(estadoSel);
      let estadosList = municipiosadminJSON.full_data;
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
}( jQuery ) );
