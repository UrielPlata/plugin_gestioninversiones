$ = jQuery.noConflict();


( function( $ ) {

  $('#form-editblproject').on('submit', editarProyectoBl );
  $('#form-adduserbl').on('submit', registrarUserBl );
  $('#form-edituserbl').on('submit', editarUserBl );
  $('#form-addcuentabl').on('submit', agregarCuentaBl );

  $('.tab-referralusersspe').on('click','.btn-editar', function(e){

    var id = $(this).attr('data-id');

      $.ajax({
      type:'post',
      data: {
        'action' : 'traer_datos_referraluser',
        'id' : id
      },
      url: url_referral.ajax_referral_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        // console.log(resultado);
        if(resultado.data.length != 0){

          let datos = resultado.data[0];
          var nombre = datos.nombre;
          var apellidos = datos.apellidos;
          var email = datos.email;
          var notas = datos.notas;


          $("#ubl_eid").attr('value', id);
          $("#ubl_enombre").val(nombre);
          $("#ubl_eapellidos").val(apellidos);
          $('#ubl_eemail').val(email);

          // if(tipo == 1){
          //   $('#ubl_etipo_especial').prop('checked',true);
          //   $('#ubl_etipo_normal').prop('checked',false);
          // }else{
          //   $('#ubl_etipo_especial').prop('checked',false);
          //   $('#ubl_etipo_normal').prop('checked',true);
          // }

          if(notas == null){
            $("#ubl_enotas").val('');
          }else{
            let notasf = notas.replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
            $("#ubl_enotas").val(notasf);
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

  $('.tab-referralusersspe').on('click','.btn-addcuenta', function(e){

    var id = $(this).attr('data-id');
    $("#cbl_uid").attr('value', id);

    var tipo = $(this).attr('data-tipo');
    $("#cbl_uid").attr('data-tipo', tipo);
    // console.log(id);

  });

  $('.tab-referralusersspe').on('click','.btn-verreporte', function(e){

    var id = $(this).attr('data-id');

    let protocol = window.location.protocol;
		let url = "admin.php?page=crc_admin_vertotalcuentasbl&id="+id;

    $(location).attr('href',url);
  });

  $('.tab-referralusersspe').on('click','.btn-verreportespe', function(e){

    var id = $(this).attr('data-id');

    let protocol = window.location.protocol;
		let url = "admin.php?page=crc_admin_vertotalcuentasspebl&id="+id;

    $(location).attr('href',url);
  });

  $('.tab-referralusersspe').on('click','.btn-eliminar', function(e){

    var id = $(this).attr('data-id');

    swal({
      title: 'Confirmar',
      text: "¿Está seguro que desea eliminar este usuario Big Level? Esta acción no se puede deshacer",
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
            'action' : 'referral_borrarusuariobl',
            'uid':id
          },
          url: url_referral.ajax_referral_url,
          success: function(data) {
            var resultado = JSON.parse(data);
            if(resultado.respuesta==1){
              $(".tab-referralusersspe").DataTable().ajax.reload();

                swal(
                'Registro eliminado',
                'Se ha eliminado correctamente el usuario Big Level.',
                'success'
              );
              // setTimeout(function(){
              //   location.reload();
              // },3000);

            }else{
              swal(
                'Ocurrió un error',
                'Se ha producido un error inesperado al tratar de eliminar el usuario Big Level. Por favor intente nuevamente más tarde',
                'error'
              );
            }

          }
        });
      }
    });
  });

  $('.tab-referralusersspe').on('mouseenter','.microtexto', function(e){
    let columna = e.target;
    let texto = $(columna).attr('aria-label').replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
    $(columna).attr('aria-label',texto);
    console.log(texto);
  });

  $('.btn-deleteproyectobl').on('click', eliminarProyectoBL );

}( jQuery ) );

function editarProyectoBl(e){

  e.preventDefault();

  let pid = $('#pbl_eid').val();
  let nombre = $('#pbl_enombre').val();
  let comision = parseFloat($('#pbl_ecomision').val());
  let comtiger = parseFloat($('#pbl_ecomtiger').val());
  let comandres = parseFloat($('#pbl_ecomandres').val());
  let color = parseInt($('#pbl_ecolor').val());
  let notas = $('#pbl_enotas').val();

  $.ajax({
      type:'post',
      data: {
        'action' : 'referral_editarproyectobl',
        'pid' : pid,
        'nombre' : nombre,
        'comision' : comision,
        'comtiger' : comtiger,
        'comandres' : comandres,
        'color' : color,
        'notas' : notas
      },
      url: url_referral.ajax_referral_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        if(resultado.respuesta==1){
          $("#modal-editblproject").modal('toggle');

          swal(
            'Referral Project editado',
            'Se ha editado correctamente la información del Referral Project. Recargando página ... ',
            'success'
          );
          setTimeout(function(){
            location.reload();
          },3000);
        }else{
          swal(
          'Ocurrió un error',
          'Se ha producido un error inesperado al tratar de editar la información del Referral Project. Por favor intente nuevamente más tarde',
          'error'
          )
        }

      }
    });

}

function registrarUserBl(e){

  e.preventDefault();

  let nombre = $('#ubl_nombre').val();
  let apellidos = $('#ubl_apellidos').val();
  let especial = parseInt($('#ubl_tipo').val());
  let project = parseInt($('#ubl_project').val());
  let email = $('#ubl_email').val();
  let notas = $('#ubl_notas').val();
  let destacado = 0;

  console.log(project);

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
        'project': project,
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

function agregarCuentaBl(e){

  e.preventDefault();

  let uid = $('#cbl_uid').val();
  let nombre = $('#cbl_nombre').val();
  let numero = $('#cbl_numero').val();
  let notas = $('#cbl_notas').val();
  let tipo = $('#cbl_uid').attr('data-tipo');

  $.ajax({
      type:'post',
      data: {
        'action' : 'referral_agregarcuenta',
        'uid' : uid,
        'nombre' : nombre,
        'numero' : numero,
        'tipo' : tipo,
        'notas' : notas
      },
      url: url_referral.ajax_referral_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        if(resultado.respuesta==1){
          $("#modal-addcuentabl").modal('toggle');
          $(".tab-referralusersspe").DataTable().ajax.reload();
          $('#cbl_uid').val("");
          $('#cbl_nombre').val("");
          $('#cbl_numero').val("");
          $('#cbl_notas').val("");
            swal(
            'Cuenta creada',
            'Se ha creado correctamente la nueva cuenta para este inversor Big Level',
            'success'
          )
        }else{
          swal(
          'Ocurrió un error',
          'Se ha producido un error inesperado al tratar de crear la cuenta del inversor. Por favor intente nuevamente más tarde',
          'error'
          )
        }

      }
    });

}

function eliminarProyectoBL(e){

  e.preventDefault();

  var id = $(this).attr('data-id');

  swal({
    title: 'Confirmar',
    text: "¿Está seguro que desea eliminar este Referral Project? Esta acción no se puede deshacer",
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
          'action' : 'referral_borrarblproject',
          'pid':id
        },
        url: url_referral.ajax_referral_url,
        success: function(data) {
          var resultado = JSON.parse(data);
          if(resultado.respuesta==1){


              swal(
              'Referral Project eliminado',
              'Se ha eliminado correctamente el proyecto, redirigiendo...',
              'success'
              );
              setTimeout(function(){
                let protocol = window.location.protocol;
            		let url = "admin.php?page=crc_referral_principal";

                $(location).attr('href',url);
              },3000);


          }else{
            swal(
              'Ocurrió un error',
              'Se ha producido un error inesperado al tratar de eliminar el referral project. Por favor intente nuevamente más tarde',
              'error'
            );
          }

        }
      });
    }
  });
};
