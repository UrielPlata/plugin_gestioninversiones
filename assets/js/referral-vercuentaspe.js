$ = jQuery.noConflict();


( function( $ ) {

  let notas = $('#cbl_enotas').val();

  $('#form-editcuentabl').on('submit', editarCuentaBl );
  $('#form-addregnsbl').on('submit', agregarRegistroBl );
  $('#form-editregnsbl').on('submit', editarRegistroBl );

  if(notas == null){
    $("#cbl_enotas").val('');
  }else{
    let notasf = notas.replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
    $("#cbl_enotas").val(notasf);
  }

  $('.tab-referralcuentas').on('click','.btn-editar', function(e){

    var id = $(this).attr('data-id');
    var tipo = 'editRegistro';

      $.ajax({
      type:'post',
      data: {
        'action' : 'traer_datos_referralregistro',
        'id' : id,
        'tipo' : tipo
      },
      url: url_referral.ajax_referral_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        console.log(resultado);
        if(resultado.data.length != 0){

          let datos = resultado.data[0];
          var utilmes = parseFloat(datos.utilmes);
          var comtra = parseFloat(datos.comtra);
          var combro = parseFloat(datos.combro);
          var salini = parseFloat(datos.salini);
          var mes = parseInt(datos.mes);
          var year = parseInt(datos.year);
          var notas = datos.notas;


          $("#rbl_eid").attr('value', id);
          $("#rbl_emes").val(mes);
          $("#rbl_eagno").val(year);
          $("#rbl_emes").attr('data-mes',mes);
          $("#rbl_eagno").attr('data-agno',year);
          $('#rbl_eutilidad').val(utilmes);
          $('#rbl_ecombro').val(combro);
          $('#rbl_ecomtra').val(comtra);
          $('#rbl_esalini').val(salini);

          if(notas == null){
            $("#rbl_enotas").val('');
          }else{
            let notasf = notas.replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
            $("#rbl_enotas").val(notasf);
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

  $('.tab-referralcuentas').on('click','.btn-eliminar', function(e){

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
            'action' : 'referral_borrarregistro',
            'rid':id
          },
          url: url_referral.ajax_referral_url,
          success: function(data) {
            var resultado = JSON.parse(data);
            if(resultado.respuesta==1){
              $(".tab-referralcuentas").DataTable().ajax.reload();

                swal(
                'Registro eliminado',
                'Se ha eliminado correctamente el registro de la cuenta.',
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

  $('.btn-deletecuentabl').on('click', eliminarCuenta );

  $('.tab-referralcuentan').on('mouseenter','.microtexto', function(e){
    let columna = e.target;
    let texto = $(columna).attr('aria-label').replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
    $(columna).attr('aria-label',texto);
    console.log(texto);
  });

}( jQuery ) );

function editarCuentaBl(e){

  e.preventDefault();

  let cid = $('#cbl_eid').val();
  let nombre = $('#cbl_enombre').val();
  let numero = $('#cbl_enumero').val();
  let notas = $('#cbl_enotas').val();

  $.ajax({
      type:'post',
      data: {
        'action' : 'referral_editarcuenta',
        'cid' : cid,
        'nombre' : nombre,
        'numero' : numero,
        'notas' : notas
      },
      url: url_referral.ajax_referral_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        if(resultado.respuesta==1){
          $("#modal-editcuentabl").modal('toggle');

          swal(
            'Cuenta editada',
            'Se ha editado correctamente la cuenta para este inversor Big Level. Recargando página ... ',
            'success'
          );
          setTimeout(function(){
            location.reload();
          },3000);
        }else{
          swal(
          'Ocurrió un error',
          'Se ha producido un error inesperado al tratar de editar la cuenta del inversor. Por favor intente nuevamente más tarde',
          'error'
          )
        }

      }
    });

}

function agregarRegistroBl(e){

  e.preventDefault();

  let cid = $('#rbl_cid').val();
  let mes = parseInt($('#rbl_mes').val());
  let year = parseInt($('#rbl_agno').val());
  let utilmes = parseFloat($('#rbl_utilidad').val());
  let comtra = parseFloat($('#rbl_comtra').val());
  let combro = parseFloat($('#rbl_combro').val());
  let salini = parseFloat($('#rbl_salini').val());
  let notas = $('#rbl_notas').val();

  // console.log(year);
  // console.log(mes);
  // console.log(cid);

  if(year < 2000) {
    swal({
      title: 'Error',
      text: "El año tiene que ser posterior a 1999",
      type: 'error',
    });
    return;
  }else{
    $.ajax({
    type:'post',
    data: {
      'action' : 'traer_datos_referralregistro',
      'cid' : cid,
      'mes' : mes,
      'year' : year,
      'tipo' : 'noDuplicarRegistro'
    },
    url: url_referral.ajax_referral_url,
    success: function(data) {
      var resultado1 = JSON.parse(data);
        console.log(resultado1);

        if(resultado1.data.length != 0){

          swal({
            title: 'Error',
            text: "Ya existe un registro para el mes y año seleccionado en esta cuenta.",
            type: 'error',
          });

        }else{
          $.ajax({
              type:'post',
              data: {
                'action' : 'referral_agregarregistrospe',
                'cid' : cid,
                'mes' : mes,
                'year' : year,
                'utilmes' : utilmes,
                'comtra' : comtra,
                'combro' : combro,
                'salini' : salini,
                'notas' : notas
              },
              url: url_referral.ajax_referral_url,
              success: function(data) {
                var resultado = JSON.parse(data);
                if(resultado.respuesta==1){
                  $("#modal-addregnbl").modal('toggle');
                  // $(".tab-referralcuentas").DataTable().ajax.reload();
                  // $('#rbl_cid').val("");
                  $('#rbl_utilidad').val(0.00);
                  $('#rbl_comtra').val(0.00);
                  $('#rbl_combro').val(0.00);
                  $('#rbl_salini').val(0.00);
                  $("#rbl_notas").val('');
                    swal(
                    'Registro creado',
                    'Se ha creado correctamente el registro mensual para esta cuenta. Actualizando ...',
                    'success'
                  )
                  setTimeout(function(){
                    location.reload();
                  },3000);
                }else{
                  swal(
                  'Ocurrió un error',
                  'Se ha producido un error inesperado al tratar de crear el registro mensual. Por favor intente nuevamente más tarde',
                  'error'
                  )
                }

              }
            });
        }
      }
    });
  }
}

function editarRegistroBl(e){

  e.preventDefault();

  let cid = $('#rbl_cid').val();
  let rid = $('#rbl_eid').val();
  let mes = $('#rbl_emes').val();
  let year = parseInt($('#rbl_eagno').val());
  let mesfijo = parseInt($('#rbl_emes').attr('data-mes'));
  let yearfijo = parseInt($('#rbl_eagno').attr('data-agno'));
  let utilmes = parseFloat($('#rbl_eutilidad').val());
  let comtra = parseFloat($('#rbl_ecomtra').val());
  let combro = parseFloat($('#rbl_ecombro').val());
  let salini = parseFloat($('#rbl_esalini').val());
  let notas = $('#rbl_enotas').val();

  console.log(utilmes);
  console.log(combro);

  if(year < 2000) {
    swal({
      title: 'Error',
      text: "El año tiene que ser posterior a 1999",
      type: 'error',
    });
    return;
  }else{
    $.ajax({
    type:'post',
    data: {
      'action' : 'traer_datos_referralregistro',
      'cid' : cid,
      'mes' : mes,
      'year' : year,
      'tipo' : 'noDuplicarRegistro'
    },
    url: url_referral.ajax_referral_url,
    success: function(data) {
      var resultado1 = JSON.parse(data);
        console.log(resultado1);

        if(resultado1.data.length != 0){

          if (resultado1.data[0].mes == mesfijo && resultado1.data[0].year == yearfijo) {
            $.ajax({
                type:'post',
                data: {
                  'action' : 'referral_editarregistro',
                  'rid' : rid,
                  'mes' : mes,
                  'year' : year,
                  'utilmes' : utilmes,
                  'comtra' : comtra,
                  'combro' : combro,
                  'salini' : salini,
                  'notas' : notas,
                  'tipo' : 1
                },
                url: url_referral.ajax_referral_url,
                success: function(data) {
                  var resultado = JSON.parse(data);
                  if(resultado.respuesta==1){
                    $("#modal-editregnbl").modal('toggle');
                    $(".tab-referralcuentas").DataTable().ajax.reload();
                    // $('#rbl_cid').val("");
                    $('#rbl_eutilidad').val(0.00);
                    $('#rbl_ecombro').val(0.00);
                    $('#rbl_ecomtra').val(0.00);
                    $('#rbl_esalini').val(0.00);
                    $("#rbl_enotas").val('');
                    swal(
                      'Registro editado',
                      'Se ha editado correctamente el registro mensual de la cuenta.',
                      'success'
                    );

                  }else{
                    swal(
                    'Ocurrió un error',
                    'Se ha producido un error inesperado al tratar de editar el registro mensual de la cuenta. Por favor intente nuevamente más tarde',
                    'error'
                    )
                  }

                }
              });

          }else{
            swal({
              title: 'Error',
              text: "Ya existe un registro para el mes y año seleccionado en esta cuenta.",
              type: 'error',
            });
          }

        }else{

          $.ajax({
              type:'post',
              data: {
                'action' : 'referral_editarregistro',
                'rid' : rid,
                'mes' : mes,
                'year' : year,
                'utilmes' : utilmes,
                'comtra' : comtra,
                'salini' : salini,
                'notas' : notas,
                'tipo' : 1
              },
              url: url_referral.ajax_referral_url,
              success: function(data) {
                var resultado = JSON.parse(data);
                if(resultado.respuesta==1){
                  $("#modal-editregnbl").modal('toggle');
                  $(".tab-referralcuentas").DataTable().ajax.reload();
                  // $('#rbl_cid').val("");
                  $('#rbl_eutilidad').val(0.00);
                  $('#rbl_ecombro').val(0.00);
                  $('#rbl_ecomtra').val(0.00);
                  $('#rbl_esalini').val(0.00);
                  $("#rbl_enotas").val('');
                  swal(
                    'Registro editado',
                    'Se ha editado correctamente el registro mensual de la cuenta.',
                    'success'
                  );

                }else{
                  swal(
                  'Ocurrió un error',
                  'Se ha producido un error inesperado al tratar de editar el registro mensual de la cuenta. Por favor intente nuevamente más tarde',
                  'error'
                  )
                }

              }
            });

        }
      }
    });
  }


}

function eliminarCuenta(e){

  e.preventDefault();

  var id = $(this).attr('data-id');

  swal({
    title: 'Confirmar',
    text: "¿Está seguro que desea eliminar esta cuenta? Esta acción no se puede deshacer",
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
          'action' : 'referral_borrarcuenta',
          'cid':id
        },
        url: url_referral.ajax_referral_url,
        success: function(data) {
          var resultado = JSON.parse(data);
          if(resultado.respuesta==1){


              swal(
              'Cuenta eliminada',
              'Se ha eliminado correctamente la cuenta, redirigiendo...',
              'success'
              );
              setTimeout(function(){
                let protocol = window.location.protocol;
            		let url = "admin.php?page=crc_referral_dashboardspe";

                $(location).attr('href',url);
              },3000);


          }else{
            swal(
              'Ocurrió un error',
              'Se ha producido un error inesperado al tratar de eliminar la cuenta. Por favor intente nuevamente más tarde',
              'error'
            );
          }

        }
      });
    }
  });
};
