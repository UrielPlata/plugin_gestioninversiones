$ = jQuery.noConflict();


( function( $ ) {

  $('#form-addregvar').on('submit', agregarRegistroVar );

  $('.tab-referralvarmes').on('click','.btn-addregistrovar', function(e){

    var mes = $(this).attr('data-mes');
    var year = $(this).attr('data-year');

    $('#rvar_mes').val(mes);
    $('#rvar_agno').val(year);

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
