$ = jQuery.noConflict();


( function( $ ) {

  $('#form-addregvar').on('submit', agregarRegistroVar );
  $('#form-editregvar').on('submit', editarRegistroVar );

  $('.tab-referraldetallemesvar').on('click','.btn-editar', function(e){

    var id = $(this).attr('data-id');
    var tipo = 'editRegistro';

      $.ajax({
      type:'post',
      data: {
        'action' : 'traer_datos_referralregistrovar',
        'id' : id,
        'tipo' : tipo
      },
      url: url_referral.ajax_referral_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        console.log(resultado);
        if(resultado.data.length != 0){

          let datos = resultado.data[0];
          var mes = parseInt(datos.mes);
          var year = parseInt(datos.year);
          var total = parseFloat(datos.total);
          var titulo = datos.titulo;
          var notas = datos.notas;

          $("#rvar_eid").attr('value', id);
          $("#rvar_emes").val(mes);
          $("#rvar_eagno").val(year);
          $('#rvar_ecantidad').val(total);
          $('#rvar_enombre').val(titulo);

          if(notas == null){
            $("#rvar_enotas").val('');
          }else{
            let notasf = notas.replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
            $("#rvar_enotas").val(notasf);
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

  $('.tab-referraldetallemesvar').on('click','.btn-eliminar', function(e){

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
            'action' : 'referral_borrarregistrovar',
            'rid':id
          },
          url: url_referral.ajax_referral_url,
          success: function(data) {
            var resultado = JSON.parse(data);
            if(resultado.respuesta==1){
              // $(".tab-referralcuentas").DataTable().ajax.reload();

                swal(
                'Registro eliminado',
                'Se ha eliminado correctamente el registro del ingreso.',
                'success'
              );

              setTimeout(function(){
                location.reload();
              },3000);

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

  $('.tab-referraldetallemesvar').on('mouseenter','.microtexto', function(e){
    let columna = e.target;
    let texto = $(columna).attr('aria-label').replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
    $(columna).attr('aria-label',texto);
    console.log(texto);
  });

}( jQuery ) );

function agregarRegistroVar(e){

  e.preventDefault();

  let mes = parseInt($('#rvar_mes').val());
  let year = parseInt($('#rvar_agno').val());
  let cantidad = parseFloat($('#rvar_cantidad').val());
  let titulo = $('#rvar_nombre').val();
  let notas = $('#rvar_notas').val();

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
          'action' : 'referral_agregarregistrovar',
          'mes' : mes,
          'year' : year,
          'cantidad' : cantidad,
          'titulo' : titulo,
          'notas' : notas
        },
        url: url_referral.ajax_referral_url,
        success: function(data) {
          var resultado = JSON.parse(data);
          if(resultado.respuesta==1){
            $("#modal-addregvar").modal('toggle');
            // $(".tab-referralcuentas").DataTable().ajax.reload();
            // $('#rbl_cid').val("");
            $('#rvar_nombre').val(0.00);
            $('#rvar_titulo').val(0.00);
            $("#rvar_notas").val('');
              swal(
              'Ingreso registrado',
              'Se ha registrado correctamente el ingreso. Actualizando ...',
              'success'
            )
            setTimeout(function(){
              location.reload();
            },3000);
          }else{
            swal(
            'Ocurrió un error',
            'Se ha producido un error inesperado al tratar de regisrar el ingreso. Por favor intente nuevamente más tarde',
            'error'
            )
          }

        }
      });

  }
}

function editarRegistroVar(e){

  e.preventDefault();

  let rid = $('#rvar_eid').val();
  let mes = parseInt($('#rvar_emes').val());
  let year = parseInt($('#rvar_eagno').val());
  let titulo = $('#rvar_enombre').val();
  let total = parseFloat($('#rvar_ecantidad').val());
  let notas = $('#rvar_enotas').val();


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
    $.ajax({
        type:'post',
        data: {
          'action' : 'referral_editarregistrovar',
          'rid' : rid,
          'mes' : mes,
          'year' : year,
          'titulo': titulo,
          'total' : total,
          'notas' : notas
        },
        url: url_referral.ajax_referral_url,
        success: function(data) {
          var resultado = JSON.parse(data);
          console.log(resultado);
          if(resultado.respuesta==1){
            $("#modal-editregvar").modal('toggle');
            // $(".tab-referralregistrosnft").DataTable().ajax.reload();
            setTimeout(function(){
              location.reload();
            },3000);
            // $('#rbl_cid').val("");
            $('#rvar_etotal').val(0.00);
            $('#rvar_enombre').val('');
            $("#rvar_enotas").val('');
            swal(
              'Registro editado',
              'Se ha editado correctamente el ingreso.',
              'success'
            );

          }else{
            swal(
            'Ocurrió un error',
            'Se ha producido un error inesperado al tratar de editar el ingreso. Por favor intente nuevamente más tarde',
            'error'
            )
          }

        }
      });

  }


}
