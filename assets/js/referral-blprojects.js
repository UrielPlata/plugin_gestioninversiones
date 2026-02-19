$ = jQuery.noConflict();


( function( $ ) {

  // $('#form-adduserbl').on('submit', registrarUserBl );
  // $('#form-edituserbl').on('submit', editarUserBl );
  $('#form-addblproject').on('submit', agregarBLProject );

  $('.cambio-cuenta').on('change',cambiarInputs );

}( jQuery ) );

function cambiarInputs(e){
  e.preventDefault();

  let tipoSel = $(this).val();

  if (tipoSel == "normal") {
    $('#pbl_comision').attr("disabled", false);
    $('#pbl_comandres').attr("disabled", true);
    $('#pbl_comandres').val("0.00");
    $('#pbl_comtiger').attr("disabled", true);
    $('#pbl_comtiger').val("0.00");
  }else {
    $('#pbl_comision').attr("disabled", true);
    $('#pbl_comision').val("0.00");
    $('#pbl_comandres').attr("disabled", false);
    $('#pbl_comtiger').attr("disabled", false);
  }

}

function agregarBLProject(e){

  e.preventDefault();

  // let uid = $('#cbl_uid').val();
  let nombre = $('#pbl_nombre').val();
  let comision = $('#pbl_comision').val();
  let comandres = $('#pbl_comandres').val();
  let comtiger = $('#pbl_comtiger').val();
  let notas = $('#pbl_notas').val();
  let color = $('#pbl_color').val();
  let stipo = $('#pbl_tipo_normal').is(":checked");

  if (stipo) {
    tipo = 0;
  }else {
    tipo = 1;
  }

  $.ajax({
      type:'post',
      data: {
        'action' : 'referral_agregarblproject',
        'nombre' : nombre,
        'comision' : comision,
        'comandres' : comandres,
        'comtiger' : comtiger,
        'tipo' : tipo,
        'color' : color,
        'notas' : notas
      },
      url: url_referral.ajax_referral_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        if(resultado.respuesta==1){
          $("#modal-addblproject").modal('toggle');
          // $(".tab-referralusersspe").DataTable().ajax.reload();
          $('#pbl_nombre').val("");
          $('#pbl_comision').val("");
          $('#pbl_comandres').val("");
          $('#pbl_comtiger').val("");
          $('#pbl_notas').val("");
          $('#pbl_color').val("1");
          $('#pbl_tipo_normal').prop('checked',false);
          $('#pbl_tipo_vip').prop('checked',false);
            swal(
            'Referral Project creado',
            'Se ha creado correctamente el Referral Project',
            'success'
          );
          setTimeout(function(){
            location.reload();
          },3000);
        }else{
          swal(
          'Ocurrió un error',
          'Se ha producido un error inesperado al tratar de crear el Referral Project. Por favor intente nuevamente más tarde',
          'error'
          )
        }

      }
    });

}
