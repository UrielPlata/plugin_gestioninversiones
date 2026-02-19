$ = jQuery.noConflict();


( function( $ ) {

  let notas = $('#nft_enotas').val();

  $('#form-addregnft').on('submit', agregarRegistroNFT );
  $('#form-editregnft').on('submit', editarRegistroNFT );

  if(notas == null){
    $("#nft_enotas").val('');
  }else{
    let notasf = notas.replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
    $("#nft_enotas").val(notasf);
  }

  //Colocar bien el microtexto en tablas
  $('.tab-referraldetallemesnft').on('mouseenter','.microtexto', function(e){
    let columna = e.target;
    let texto = $(columna).attr('aria-label').replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
    $(columna).attr('aria-label',texto);
  });


  $('.tab-referraldetallemesnft').on('click','.btn-editar', function(e){

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

  $('.tab-referraldetallemesnft').on('click','.btn-eliminar', function(e){

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
              // $(".tab-referralregistrosnft").DataTable().ajax.reload();

                swal(
                'Registro eliminado',
                'Se ha eliminado correctamente el registro del proyecto NFT.',
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

}( jQuery ) );

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
