$ = jQuery.noConflict();


( function( $ ) {

  let notas = $('#nft_enotas').val();

  $('#form-editnftproject').on('submit', editarProyectoNFT );
  $('#form-addregnft').on('submit', agregarRegistroNFT );
  $('#form-addretnft').on('submit', agregarRetiroNFT );
  $('#form-editregnft').on('submit', editarRegistroNFT );
  $('#form-editretnft').on('submit', editarRetiroNFT );

  if(notas == null){
    $("#nft_enotas").val('');
  }else{
    let notasf = notas.replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
    $("#nft_enotas").val(notasf);
  }

  //Colocar bien el microtexto en tablas
  $('.tab-referralregistrosnft').on('mouseenter','.microtexto', function(e){
    let columna = e.target;
    let texto = $(columna).attr('aria-label').replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
    $(columna).attr('aria-label',texto);
  });

  $('.tab-referralretirosnft').on('mouseenter','.microtexto', function(e){
    let columna = e.target;
    let texto = $(columna).attr('aria-label').replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
    $(columna).attr('aria-label',texto);
  });

  $('.tab-referralregistrosnftmes').on('mouseenter','.microtexto', function(e){
    let columna = e.target;
    let texto = $(columna).attr('aria-label').replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
    $(columna).attr('aria-label',texto);
  });

  $('.imagen-tarjeta').click(function(){
    $('#nft_eimagen').trigger("click");
  });

  $('#nft_eimagen').change(function(){
		validarUserFoto(this);
	});


  $('.tab-referralregistrosnft').on('click','.btn-editar', function(e){

    var id = $(this).attr('data-id');
    var tipo = 'editRegistro';

      $.ajax({
      type:'post',
      data: {
        'action' : 'traer_datos_referralregistronft',
        'id' : id,
        'tipo' : tipo
      },
      url: url_referral.ajax_referral_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        console.log(resultado);
        if(resultado.data.length != 0){

          let datos = resultado.data[0];
          var total = parseFloat(datos.total);
          var team = parseFloat(datos.team);
          var mes = parseInt(datos.mes);
          var year = parseInt(datos.year);
          var semana = parseInt(datos.semana);
          var status = parseInt(datos.status);
          var notas = datos.notas;


          if(status == 0){
            $('#rnft_status_cerr').prop('checked',true);
            $('#rnft_status_abie').prop('checked',false);
          }else{
            $('#rnft_status_cerr').prop('checked',false);
            $('#rnft_status_abie').prop('checked',true);
          }

          $("#rnft_eid").attr('value', id);
          $("#rnft_emes").val(mes);
          $("#rnft_eagno").val(year);
          $("#rnft_esemana").val(semana);
          $("#rnft_emes").attr('data-mes',mes);
          $("#rnft_eagno").attr('data-agno',year);
          $('#rnft_etotal').val(total);
          $('#rnft_eteam').val(team);

          if(notas == null){
            $("#rnft_enotas").val('');
          }else{
            let notasf = notas.replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
            $("#rnft_enotas").val(notasf);
          }

        }else{
          tb_remove();
          swal({
            title: 'Error',
            text: "No se pudieron cargar los datos",
            type: 'error',
          });
        }
      }
    });

  });

  $('.tab-referralregistrosnft').on('click','.btn-eliminar', function(e){

    var id = $(this).attr('data-id');

    swal({
      title: 'Confirmar',
      text: "¿Está seguro que desea eliminar este registro? Esta acción no se puede deshacer",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Eliminar'
    }).then((result) => {
      if(result.value){
        $.ajax({
          type:'post',
          data: {
            'action' : 'referral_borrarregistronft',
            'nid':id
          },
          url: url_referral.ajax_referral_url,
          success: function(data) {
            var resultado = JSON.parse(data);
            if(resultado.respuesta==1){
              $(".tab-referralregistrosnft").DataTable().ajax.reload();

                swal(
                'Registro eliminado',
                'Se ha eliminado correctamente el registro del proyecto NFT.',
                'success'
              );

            }else{
              swal(
                'Ocurrió un error',
                'Se ha producido un error inesperado al tratar de eliminar el registro. Por favor intente nuevamente más tarde',
                'error'
              );
            }

          }
        });
      }
    });
  });

  $('.tab-referralretirosnft').on('click','.btn-editar', function(e){

    var id = $(this).attr('data-id');
    var tipo = 'editRetiro';

      $.ajax({
      type:'post',
      data: {
        'action' : 'traer_datos_referralretironft',
        'id' : id,
        'tipo' : tipo
      },
      url: url_referral.ajax_referral_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        console.log(resultado);
        if(resultado.data.length != 0){

          let datos = resultado.data[0];
          var cantidad = parseFloat(datos.cantidad);
          var valusd = parseFloat(datos.valusd);
          var date = datos.fecharetiro;
          var efecharetiro = date.split("/").reverse().join("-");
          var notas = datos.notas;


          $("#rtnft_eid").attr('value', id);
          $('#rtnft_etotal').val(cantidad);
          $('#rtnft_eusdactual').val(valusd);
          $('#rtnft_efecha_retiro').val(efecharetiro);


          if(notas == null){
            $("#rtnft_enotas").val('');
          }else{
            let notasf = notas.replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
            $("#rtnft_enotas").val(notasf);
          }

        }else{
          tb_remove();
          swal({
            title: 'Error',
            text: "No se pudieron cargar los datos",
            type: 'error',
          });
        }
      }
    });

  });

  $('.tab-referralretirosnft').on('click','.btn-eliminar', function(e){

    var id = $(this).attr('data-id');

    swal({
      title: 'Confirmar',
      text: "¿Está seguro que desea eliminar este retiro? Esta acción no modifica el status de los registros cerrados por el retiro",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Eliminar'
    }).then((result) => {
      if(result.value){
        $.ajax({
          type:'post',
          data: {
            'action' : 'referral_borrarretironft',
            'nid':id
          },
          url: url_referral.ajax_referral_url,
          success: function(data) {
            var resultado = JSON.parse(data);
            if(resultado.respuesta==1){
              $(".tab-referralretirosnft").DataTable().ajax.reload();

                swal(
                'Retiro eliminado',
                'Se ha eliminado correctamente el retiro del proyecto NFT.',
                'success'
              );

            }else{
              swal(
                'Ocurrió un error',
                'Se ha producido un error inesperado al tratar de eliminar el retiro. Por favor intente nuevamente más tarde',
                'error'
              );
            }

          }
        });
      }
    });
  });

  $('.btn-deleteproyectonft').on('click', eliminarProyectoNFT );

  $('.tab-referralregistrosnft').on('mouseenter','.microtexto', function(e){
    let columna = e.target;
    let texto = $(columna).attr('aria-label').replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
    $(columna).attr('aria-label',texto);
    // console.log(texto);
  });

}( jQuery ) );

function validarUserFoto(inputCatFoto) {

		$(".help-block span.help-block-archivos").hide();
		$(".help-block span.help-block-peso").hide();

		var imagen = inputCatFoto.files[0];

		/*=============================================
	  	VALIDAMOS EL FORMATO DE LA IMAGEN SEA JPG O PNG
	  	=============================================*/

	  	if(imagen["type"] != "image/jpeg" && imagen["type"] != "image/png"){

				$(".help-block span.help-block-archivos").show();

	  		$(inputCatFoto).val("");

	  		// toastr["warning"]("La imagen debe estar en formato JPG o PNG","Atención al subir la foto");

	  	}else if(imagen["size"] > 5000000){

				$(".help-block span.help-block-peso").show();

	  		$(inputCatFoto).val("");

				// toastr["warning"]("La imagen no debe pesar más de 5MB","Atención al subir la foto");

	  	}else{

	  		var datosImagen = new FileReader;
	  		datosImagen.readAsDataURL(imagen);

	  		$(datosImagen).on("load", function(event){

	  			var rutaImagen = event.target.result;

	  			$(".imagen-tarjeta").attr("src", rutaImagen);

          console.log(rutaImagen);

	  		})

	  	}
	}


function editarProyectoNFT(e){

  e.preventDefault();

  let nid = $('#nft_eid').val();
  let nombre = $('#nft_enombre').val();
  let color = parseInt($('#nft_ecolor').val());
  let notas = $('#nft_enotas').val();
  // var formData = new FormData();
  var files = $('#nft_eimagen')[0].files[0];
  // formData.append('file',files);
  var datos = new FormData(); // https://developer.mozilla.org/es/docs/Web/Guide/Usando_Objetos_FormData
  datos.append('action', 'referral_editarproyectonft');
  datos.append('nid', nid);
  datos.append('nombre', nombre);
  datos.append('color', color);
  datos.append('notas', notas);
  datos.append('imagen', files);

  console.log(files);

  $.ajax({
      type:'post',
      data: datos,
      // data: {
      //   'action' : 'referral_editarproyectonft',
      //   'nid' : nid,
      //   'nombre' : nombre,
      //   'color' : color,
      //   'notas' : notas,
      //   'files' : files
      // },
      cache: false,
      contentType: false,
      processData: false,
      url: url_referral.ajax_referral_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        if(resultado.respuesta==1){
          $("#modal-editnftproject").modal('toggle');

          swal(
            'NFT Project editado',
            'Se ha editado correctamente la información del NFT Project. Recargando página ... ',
            'success'
          );
          setTimeout(function(){
            location.reload();
          },3000);
        }else{
          swal(
          'Ocurrió un error',
          'Se ha producido un error inesperado al tratar de editar la información del NFT Project. Por favor intente nuevamente más tarde',
          'error'
          )
        }

      }
    });

}

function agregarRegistroNFT(e){

  e.preventDefault();

  let tipo = parseInt($('#btn-addregnft').attr("data-tipo"));
  let nid = $('#rnft_nid').val();
  let mes = parseInt($('#rnft_mes').val());
  let year = parseInt($('#rnft_agno').val());
  let semana = parseInt($('#rnft_semana').val());
  let total = parseFloat($('#rnft_total').val());
  let team = parseFloat($('#rnft_team').val());
  let notas = $('#rnft_notas').val();

  // console.log(semana);
  // console.log(notas);
  // console.log(cid);

  if(year < 2000) {
    swal({
      title: 'Error',
      text: "El año tiene que ser posterior a 1999",
      type: 'error',
    });
    return;
  }else{
    if (tipo == 0) {
      $.ajax({
          type:'post',
          data: {
            'action' : 'referral_agregarregistronft',
            'nid' : nid,
            'mes' : mes,
            'year' : year,
            'semana': semana,
            'total' : total,
            'team' : team,
            'notas' : notas
          },
          url: url_referral.ajax_referral_url,
          success: function(data) {
            var resultado = JSON.parse(data);
            if(resultado.respuesta==1){
              $("#modal-addregnft").modal('toggle');
              // $(".tab-referralcuentan").DataTable().ajax.reload();
              // $('#rbl_cid').val("");
              $('#rnft_total').val(0.00);
              $('#rnft_team').val(0.00);
              $("#rnft_notas").val('');
              $("#rnft_semana").val(1);
                swal(
                'Registro creado',
                'Se ha creado correctamente el registro mensual para este proyecto. Actualizando ...',
                'success'
              )
              setTimeout(function(){
                location.reload();
              },3000);
            }else{
              swal(
              'Ocurrió un error',
              'Se ha producido un error inesperado al tratar de crear el registro para este proyecto. Por favor intente nuevamente más tarde',
              'error'
              )
            }

          }
        });
    }else{
      $.ajax({
          type:'post',
          data: {
            'action' : 'referral_agregarregistronft',
            'nid' : nid,
            'mes' : mes,
            'year' : year,
            'semana': semana,
            'total' : total,
            'team' : team,
            'notas' : notas
          },
          url: url_referral.ajax_referral_url,
          success: function(data) {
            var resultado = JSON.parse(data);
            if(resultado.respuesta==1){
              $("#modal-addregnft").modal('toggle');
              // $(".tab-referralcuentan").DataTable().ajax.reload();
              // $('#rbl_cid').val("");
              $('#rnft_total').val(0.00);
              $('#rnft_team').val(0.00);
              $("#rnft_notas").val('');
              $("#rnft_semana").val(1);
                swal(
                'Registro creado',
                'Se ha creado correctamente el registro mensual para este proyecto. Actualizando ...',
                'success'
              )
              setTimeout(function(){
                location.reload();
              },3000);
            }else{
              swal(
              'Ocurrió un error',
              'Se ha producido un error inesperado al tratar de crear el registro para este proyecto. Por favor intente nuevamente más tarde',
              'error'
              )
            }

          }
        });
    }

  }
}

function agregarRetiroNFT(e){

  e.preventDefault();

  let nid = $('#rtnft_nid').val();
  let total = parseFloat($('#rtnft_total').val());
  let usdval = parseFloat($('#rtnft_usdactual').val());
  let ultregid = parseInt($('#rtnft_ultreg').val());
  let fecharetiro = $("#rtnft_fecha_retiro").val();
  let fecharetiroform = fecharetiro.split("/").reverse().join("-");
  let notas = $('#rtnft_notas').val();

  // console.log(semana);
  // console.log(notas);
  // console.log(cid);

  if(total <= 0) {
    swal({
      title: 'Error',
      text: "La cantidad de retiro tiene que ser mayor de 0",
      type: 'error',
    });
    return;
  }else{
    swal({
      title: 'Confirmar Retiro',
      text: "¿Está seguro que desea registrar un retiro? Al Total Actual volverá a ser 0, los registros pasados no se eliminarán.",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Registrar'
    }).then((result) => {
      if(result.value){
        $.ajax({
            type:'post',
            data: {
              'action' : 'referral_agregarretironft',
              'nid' : nid,
              'total' : total,
              'usdval' : usdval,
              'notas' : notas,
              'fecharetiro' : fecharetiroform,
              'cierre' : ultregid
            },
            url: url_referral.ajax_referral_url,
            success: function(data) {
              var resultado = JSON.parse(data);
              if(resultado.respuesta==1){
                $("#modal-addretnft").modal('toggle');
                // $(".tab-referralcuentan").DataTable().ajax.reload();
                // $('#rbl_cid').val("");
                $('#rtnft_total').val(0.00);
                $("#rtnft_notas").val('');
                  swal(
                  'Retiro registrado',
                  'Se ha registrado correctamente el retiro para este proyecto. Actualizando ...',
                  'success'
                )
                setTimeout(function(){
                  location.reload();
                },3000);
              }else{
                swal(
                'Ocurrió un error',
                'Se ha producido un error inesperado al tratar de registrar el retiro para este proyecto. Por favor intente nuevamente más tarde',
                'error'
                )
              }

            }
          });
      }
    });
  }
}

function editarRetiroNFT(e){

  e.preventDefault();

  let rid = $('#rtnft_eid').val();
  let total = parseFloat($('#rtnft_etotal').val());
  let usdval = parseFloat($('#rtnft_eusdactual').val());
  let fecharetiro = $("#rtnft_efecha_retiro").val();
  let fecharetiroform = fecharetiro.split("/").reverse().join("-");
  let notas = $('#rtnft_enotas').val();

  if(total <= 0.0000 || usdval <= 0.00) {
    swal({
      title: 'Error',
      text: "La cantidad de retiro y valor USD tienen que ser mayor de 0",
      type: 'error',
    });
    return;
  }else{
    $.ajax({
        type:'post',
        data: {
          'action' : 'referral_editarretironft',
          'rid' : rid,
          'total' : total,
          'usdval' : usdval,
          'notas' : notas,
          'fecharetiro' : fecharetiroform
        },
        url: url_referral.ajax_referral_url,
        success: function(data) {
          var resultado = JSON.parse(data);
          console.log(resultado);
          if(resultado.respuesta==1){
            $("#modal-editretnft").modal('toggle');
            $(".tab-referralretirosnft").DataTable().ajax.reload();

            // $('#rbl_cid').val("");
            $('#rtnft_etotal').val(0.00);
            $('#rtnft_eusdactual').val(0.00);
            $("#rtnft_enotas").val('');
            $("#rtnft_efecha_retiro").val('');
            swal(
              'Retiro editado',
              'Se ha editado correctamente el retiro del proyecto NFT.',
              'success'
            );

          }else{
            swal(
            'Ocurrió un error',
            'Se ha producido un error inesperado al tratar de editar el retiro del proyecto NFT. Por favor intente nuevamente más tarde',
            'error'
            )
          }

        }
      });

  }

}

function editarRegistroNFT(e){

  e.preventDefault();

  let tipo = parseInt($('#btn-addregnft').attr("data-tipo"));
  let statuscerr = $('#rnft_status_cerr').is(":checked");
  let rid = $('#rnft_eid').val();
  let nid = $('#rnft_nid').val();
  let mes = parseInt($('#rnft_emes').val());
  let year = parseInt($('#rnft_eagno').val());
  let mesfijo = parseInt($('#rnft_emes').attr('data-mes'));
  let yearfijo = parseInt($('#rnft_eagno').attr('data-agno'));
  let semana = parseInt($('#rnft_esemana').val());
  let total = parseFloat($('#rnft_etotal').val());
  let team = parseFloat($('#rnft_eteam').val());
  let notas = $('#rnft_enotas').val();
  let tstatus = 1;

  if(statuscerr){
    tstatus = 0;
  }

  console.log(tstatus);
  // console.log(semana);
  // console.log(notas);

  if(year < 2000) {
    swal({
      title: 'Error',
      text: "El año tiene que ser posterior a 1999",
      type: 'error',
    });
    return;
  }else{
    if (tipo == 0) {
      $.ajax({
          type:'post',
          data: {
            'action' : 'referral_editarregistronft',
            'rid' : rid,
            'mes' : mes,
            'year' : year,
            'semana': semana,
            'total' : total,
            'team' : team,
            'status' : tstatus,
            'notas' : notas
          },
          url: url_referral.ajax_referral_url,
          success: function(data) {
            var resultado = JSON.parse(data);
            console.log(resultado);
            if(resultado.respuesta==1){
              $("#modal-editregnft").modal('toggle');
              // $(".tab-referralregistrosnft").DataTable().ajax.reload();
              setTimeout(function(){
                location.reload();
              },3000);
              // $('#rbl_cid').val("");
              $('#rnft_etotal').val(0.00);
              $('#rnft_eteam').val(0.00);
              $("#rnft_enotas").val('');
              swal(
                'Registro editado',
                'Se ha editado correctamente el registro mensual del proyecto NFT.',
                'success'
              );

            }else{
              swal(
              'Ocurrió un error',
              'Se ha producido un error inesperado al tratar de editar el registro mensual del proyecto NFT. Por favor intente nuevamente más tarde',
              'error'
              )
            }

          }
        });
    }else {
      $.ajax({
          type:'post',
          data: {
            'action' : 'referral_editarregistronft',
            'rid' : rid,
            'mes' : mes,
            'year' : year,
            'semana': semana,
            'total' : total,
            'team' : team,
            'status' : tstatus,
            'notas' : notas
          },
          url: url_referral.ajax_referral_url,
          success: function(data) {
            var resultado = JSON.parse(data);
            console.log(resultado);
            if(resultado.respuesta==1){
              $("#modal-editregnft").modal('toggle');
              // $(".tab-referralregistrosnft").DataTable().ajax.reload();
              setTimeout(function(){
                location.reload();
              },3000);
              // $('#rbl_cid').val("");
              $('#rnft_etotal').val(0.00);
              $('#rnft_eteam').val(0.00);
              $("#rnft_enotas").val('');
              swal(
                'Registro editado',
                'Se ha editado correctamente el registro mensual del proyecto NFT.',
                'success'
              );

            }else{
              swal(
              'Ocurrió un error',
              'Se ha producido un error inesperado al tratar de editar el registro mensual del proyecto NFT. Por favor intente nuevamente más tarde',
              'error'
              )
            }

          }
        });
    }

  }


}

function eliminarProyectoNFT(e){

  e.preventDefault();

  var id = $(this).attr('data-id');

  swal({
    title: 'Confirmar',
    text: "¿Está seguro que desea eliminar este NFT Project? Esta acción no se puede deshacer",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, Eliminar'
  }).then((result) => {
    if(result.value){
      $.ajax({
        type:'post',
        data: {
          'action' : 'referral_borrarnftproject',
          'nid':id
        },
        url: url_referral.ajax_referral_url,
        success: function(data) {
          var resultado = JSON.parse(data);
          if(resultado.respuesta==1){


              swal(
              'NFT Project eliminado',
              'Se ha eliminado correctamente el proyecto, redirigiendo...',
              'success'
              );
              setTimeout(function(){
                let protocol = window.location.protocol;
            		let url = "admin.php?page=crc_referral_nftprojects";

                $(location).attr('href',url);
              },3000);


          }else{
            swal(
              'Ocurrió un error',
              'Se ha producido un error inesperado al tratar de eliminar el proyecto. Por favor intente nuevamente más tarde',
              'error'
            );
          }

        }
      });
    }
  });
};
