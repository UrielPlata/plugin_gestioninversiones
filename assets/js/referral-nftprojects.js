$ = jQuery.noConflict();


( function( $ ) {

  // $('#form-adduserbl').on('submit', registrarUserBl );
  // $('#form-edituserbl').on('submit', editarUserBl );
  $('#form-addnftproject').on('submit', agregarNFTProject );

  // $('.tab-referralusersspe').on('click','.btn-editar', function(e){
  //
  //   var id = $(this).attr('data-id');
  //
  //     $.ajax({
  //     type:'post',
  //     data: {
  //       'action' : 'traer_datos_referraluser',
  //       'id' : id
  //     },
  //     url: url_referral.ajax_referral_url,
  //     success: function(data) {
  //       var resultado = JSON.parse(data);
  //       // console.log(resultado);
  //       if(resultado.data.length != 0){
  //
  //         let datos = resultado.data[0];
  //         var nombre = datos.nombre;
  //         var apellidos = datos.apellidos;
  //         var email = datos.email;
  //         var notas = datos.notas;
  //
  //
  //         $("#ubl_eid").attr('value', id);
  //         $("#ubl_enombre").val(nombre);
  //         $("#ubl_eapellidos").val(apellidos);
  //         $('#ubl_eemail').val(email);
  //
  //         // if(tipo == 1){
  //         //   $('#ubl_etipo_especial').prop('checked',true);
  //         //   $('#ubl_etipo_normal').prop('checked',false);
  //         // }else{
  //         //   $('#ubl_etipo_especial').prop('checked',false);
  //         //   $('#ubl_etipo_normal').prop('checked',true);
  //         // }
  //
  //         if(notas == null){
  //           $("#ubl_enotas").val('');
  //         }else{
  //           let notasf = notas.replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
  //           $("#ubl_enotas").val(notasf);
  //         }
  //
  //       }else{
  //         tb_remove();
  //         swal({
  //           title: 'Error',
  //           text: "No se pudieron cargar los datos",
  //           type: 'error',
  //         });
  //       }
  //     }
  //   });
  //
  // });

  // $('.tab-referralusersspe').on('click','.btn-addcuenta', function(e){
  //
  //   var id = $(this).attr('data-id');
  //   $("#cbl_uid").attr('value', id);
  //
  //   var tipo = $(this).attr('data-tipo');
  //   $("#cbl_uid").attr('data-tipo', tipo);
  //   // console.log(id);
  //
  // });
  //
  // $('.tab-referralusersspe').on('click','.btn-verreporte', function(e){
  //
  //   var id = $(this).attr('data-id');
  //
  //   let protocol = window.location.protocol;
	// 	let url = "admin.php?page=crc_admin_vertotalcuentasbl&id="+id;
  //
  //   $(location).attr('href',url);
  // });
  //
  // $('.tab-referralusersspe').on('click','.btn-verreportespe', function(e){
  //
  //   var id = $(this).attr('data-id');
  //
  //   let protocol = window.location.protocol;
	// 	let url = "admin.php?page=crc_admin_vertotalcuentasspebl&id="+id;
  //
  //   $(location).attr('href',url);
  // });
  //
  // $('.tab-referralusersspe').on('click','.btn-eliminar', function(e){
  //
  //   var id = $(this).attr('data-id');
  //
  //   swal({
  //     title: 'Confirmar',
  //     text: "¿Está seguro que desea eliminar este usuario Big Level? Esta acción no se puede deshacer",
  //     type: 'warning',
  //     showCancelButton: true,
  //     confirmButtonColor: '#3085d6',
  //     cancelButtonColor: '#d33',
  //     confirmButtonText: 'Si, Eliminar'
  //   }).then((result) => {
  //     if(result.value){
  //       $.ajax({
  //         type:'post',
  //         data: {
  //           'action' : 'referral_borrarusuariobl',
  //           'uid':id
  //         },
  //         url: url_referral.ajax_referral_url,
  //         success: function(data) {
  //           var resultado = JSON.parse(data);
  //           if(resultado.respuesta==1){
  //             $(".tab-referralusersspe").DataTable().ajax.reload();
  //
  //               swal(
  //               'Registro eliminado',
  //               'Se ha eliminado correctamente el usuario Big Level.',
  //               'success'
  //             );
  //             // setTimeout(function(){
  //             //   location.reload();
  //             // },3000);
  //
  //           }else{
  //             swal(
  //               'Ocurrió un error',
  //               'Se ha producido un error inesperado al tratar de eliminar el usuario Big Level. Por favor intente nuevamente más tarde',
  //               'error'
  //             );
  //           }
  //
  //         }
  //       });
  //     }
  //   });
  // });
  //
  // $('.tab-referralusersspe').on('mouseenter','.microtexto', function(e){
  //   let columna = e.target;
  //   let texto = $(columna).attr('aria-label').replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
  //   $(columna).attr('aria-label',texto);
  //   console.log(texto);
  // });

}( jQuery ) );

function registrarUserBl(e){

  e.preventDefault();

  let nombre = $('#ubl_nombre').val();
  let apellidos = $('#ubl_apellidos').val();
  let especial = parseInt($('#ubl_tipo').val());
  let email = $('#ubl_email').val();
  let notas = $('#ubl_notas').val();
  let destacado = 0;

  console.log(especial);

  if(especial == 1){
    destacado = 1;
  }

  $.ajax({
      type:'post',
      data: {
        'action' : 'referral_registraruser',
        'nombre' : nombre,
        'apellidos' : apellidos,
        'email' : email,
        'tipo' : destacado,
        'notas' : notas
      },
      url: url_referral.ajax_referral_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        if(resultado.respuesta==1){
          $("#modal-adduserbl").modal('toggle');
          $(".tab-referralusersspe").DataTable().ajax.reload();
          $('#ubl_nombre').val("");
          $('#ubl_apellidos').val("");
          $('#ubl_email').val("");
          $('#ubl_notas').val("");
          $('#ubl_tipo_especial').prop('checked',false);
          $('#ubl_tipo_normal').prop('checked',true);
            swal(
            'Inversor registrado',
            'Se ha registrado al inversor big level correctamente en la base de datos.',
            'success'
          )
        }else{
          swal(
          'Ocurrió un error',
          'Se ha producido un error inesperado al tratar de registrar al inversor. Por favor intente nuevamente más tarde',
          'error'
          )
        }

      }
    });

}

function editarUserBl(e){

  e.preventDefault();

  let id = parseInt($('#ubl_eid').val());
  let nombre = $('#ubl_enombre').val();
  let apellidos = $('#ubl_eapellidos').val();
  let especial = $('#ubl_etipo_especial').is(':checked');
  let email = $('#ubl_eemail').val();
  let notas = $('#ubl_enotas').val();
  // let destacado = 0;
  //
  // if (especial) {
  //   destacado = 1;
  // }

  $.ajax({
      type:'post',
      data: {
        'action' : 'referral_editaruser',
        'id' : id,
        'nombre' : nombre,
        'apellidos' : apellidos,
        'email' : email,
        'notas' : notas
      },
      url: url_referral.ajax_referral_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        if(resultado.respuesta==1){
          $("#modal-edituserbl").modal('toggle');
          $(".tab-referralusersspe").DataTable().ajax.reload();
          $('#ubl_enombre').val("");
          $('#ubl_eapellidos').val("");
          $('#ubl_eemail').val("");
          $('#ubl_enotas').val("");
          $('#ubl_etipo_especial').prop('checked',false);
          $('#ubl_etipo_normal').prop('checked',true);
            swal(
            'Inversor editado',
            'La información del inversor Big level se ha editado correctamente.',
            'success'
          )
        }else{
          swal(
          'Ocurrió un error',
          'Se ha producido un error inesperado al tratar de editar la información del inversor. Por favor intente nuevamente más tarde',
          'error'
          )
        }

      }
    });

}

function agregarNFTProject(e){

  e.preventDefault();

  // let uid = $('#cbl_uid').val();
  let nombre = $('#nft_nombre').val();
  // let numero = $('#nft_numero').val();
  let notas = $('#nft_notas').val();
  let stipo = $('#nft_tipo_mensual').is(":checked");

  if (stipo) {
    tipo = 0;
  }else {
    tipo = 1;
  }

  $.ajax({
      type:'post',
      data: {
        'action' : 'referral_agregarnftproject',
        'nombre' : nombre,
        'tipo' : tipo,
        'notas' : notas
      },
      url: url_referral.ajax_referral_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        if(resultado.respuesta==1){
          $("#modal-addnftproject").modal('toggle');
          // $(".tab-referralusersspe").DataTable().ajax.reload();
          $('#nft_nombre').val("");
          $('#nft_numero').val("");
          $('#nft_notas').val("");
          $('#nft_tipo_mensual').prop('checked',false);
          $('#nft_tipo_semanal').prop('checked',false);
            swal(
            'NFT Project creado',
            'Se ha creado correctamente el NFT Project',
            'success'
          );
          setTimeout(function(){
            location.reload();
          },3000);
        }else{
          swal(
          'Ocurrió un error',
          'Se ha producido un error inesperado al tratar de crear el NFT Project. Por favor intente nuevamente más tarde',
          'error'
          )
        }

      }
    });

}
