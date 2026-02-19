$ = jQuery.noConflict();
//
// Tabs Toggler
//

( function( $ ) {

  $('#form-coneditdep').on('submit', editarDepositoCon );
  $('#form-confindep').on('submit', finalizarDepositoCon );
  $('#form-concancdep').on('submit', cancelarDepositoCon );

  $('.tab-consadmindepositos').on('click','.btn-editar', function(e){

    var id = $(this).attr('data-deposito');

      $.ajax({
      type:'post',
      data: {
        'action' : 'traer_datos_cons',
        'id' : id,
        'tipo' : 'deposito'
      },
      url: url_operacion_conservador.ajaxopeurl,
      success: function(data) {
        var resultado = JSON.parse(data);
        // console.log(resultado);
        if(resultado.data.length != 0){

          let datos = resultado.data[0];
          var idmovind = datos.idmovind;
          var idmovgral = datos.idmovgral;
          var date = datos.fechafin;
          var datesol = datos.fecha;
          var efechafin = date.split("/").reverse().join("-");
          var efechasol = datesol.split("/").reverse().join("-");
          var cantfin = parseFloat(datos.cantidadfin);
          var cantini = datos.cantidad;
          var notas = datos.notas;


          $("#idedep").attr('value', id);
          $("#eidmovind").val(idmovind);
          $("#eidmovgral").val(idmovgral);
          $("#ecantidadfin").val(cantfin);
          $("#ecantidadini").attr('value', cantini);

          $("#efechafin").val(efechafin);
          $("#efechasol").val(efechasol);
          if(notas == null){
            $("#enotas").val('');
          }else{
            let notasf = notas.replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
            $("#enotas").val(notasf);
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

  $('.tab-consadmindepositos').on('click','.btn-finalizar', function(e){

    var id = $(this).attr('data-deposito');

      $.ajax({
      type:'post',
      data: {
        'action' : 'traer_datos_cons',
        'id' : id,
        'tipo' : 'deposito'
      },
      url: url_operacion_conservador.ajaxopeurl,
      success: function(data) {
        var resultado = JSON.parse(data);
        // console.log(resultado);
        if(resultado.data.length != 0){

          let datos = resultado.data[0];
          var cantini = datos.cantidad;
          var datesol = datos.fecha;
          var efechasol = datesol.split("/").reverse().join("-");


          $("#iddep").attr('value', id);
          $("#cantidadini").attr('value', cantini);
          $("#fechasol").val(efechasol);
          $("#notas").val("");

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

  $('.tab-consadmindepositos').on('click','.btn-cancelar', function(e){

    var id = $(this).attr('data-deposito');

      $.ajax({
      type:'post',
      data: {
        'action' : 'traer_datos_cons',
        'id' : id,
        'tipo' : 'deposito'
      },
      url: url_operacion_conservador.ajaxopeurl,
      success: function(data) {
        var resultado = JSON.parse(data);
        console.log(resultado);
        if(resultado.data.length != 0){

          let datos = resultado.data[0];
          var idmovind = datos.idmovind;
          var idmovgral = datos.idmovgral;
          var date = datos.fechafin;
          var datesol = datos.fecha;
          var efechadep = datos.fechadep;
          var efechasol = datesol.split("/").reverse().join("-");
          var cantini = datos.cantidad;
          var notas = datos.notas;


          $("#idcdep").attr('value', id);
          $("#ccantidadini").attr('value', cantini);
          $("#cfechadep").val(efechadep);


          $("#cfechasol").val(efechasol);
          if(notas == null){
            $("#cnotas").val('');
          }else{
            let notasf = notas.replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
            $("#cnotas").val(notasf);
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


$('.tab-consadmindepositos').on('mouseenter','.microtexto', function(e){
  let columna = e.target;
  let texto = $(columna).attr('aria-label').replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
  $(columna).attr('aria-label',texto);
});

}( jQuery ) );

function editarDepositoCon(e){

  e.preventDefault();

  let id = $("#idedep").val();
  let idmovind = $("#eidmovind").val();
  let idmovgral = $("#eidmovgral").val();
  let cantidadfin = parseFloat($("#ecantidadfin").val());
  let fechasol = $("#efechasol").val();
  let fechasolform = fechasol.split("/").reverse().join("-");
  let fechafin = $("#efechafin").val();
  let fechafinform = fechafin.split("/").reverse().join("-");
  let notas = $("#enotas").val();
  let fechaval = validarFecha(fechafin);
  //console.log(interes);
  //console.log(idmovgral);

  // console.log(cantidad);
  if(cantidadfin == 0) {
    swal({
      title: 'Error',
      text: "La cantidad final del deposito debe ser mayor a 0",
      type: 'error',
    });
    return;
  }else{
    swal({
      title: 'Confirmar',
      text: "¿Esta seguro que desea editar los campos de este depósito?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Editar'
    }).then((result) => {
      if(result.value){
          $.ajax({
          type:'post',
          data: {
            'action' : 'operacion_editar_cons',
            'id' : id,
            'idmovind' : idmovind,
            'idmovgral' : idmovgral,
            'fechasol' : fechasolform,
            'fechafin' : fechafinform,
            'cantidadfin' : cantidadfin,
            'notas' : notas,
            'tipo' : 'deposito'
          },
          url: url_operacion_conservador.ajaxopeurl,
          success: function(data) {
            var resultado = JSON.parse(data);
            console.log(resultado);
            if(resultado.respuesta==1){
              $("#modal-coneditdep").modal('toggle');
              $("#ecantidadfin").val('0.00');
              $("#eidmovind").val('');
              $("#eidmovgral").val('');
              $('#enotas').val('');
              $(".tab-consadmindepositos").DataTable().ajax.reload();
                swal(
                'Depósito editado',
                'El depósito del inversionista se ha editado correctamente.',
                'success'
              )
            }else{
              swal(
              'Ocurrió un error',
              'Se ha producido un error inesperado al tratar de editar el depósito. Por favor intente nuevamente más tarde',
              'error'
              )
            }
          }
        });
      }
    })
  }

}

function finalizarDepositoCon(e){

  e.preventDefault();

  let id = $("#iddep").val();
  let idmovind = $("#idmovind").val();
  let idmovgral = $("#idmovgral").val();
  let fechasol = $("#fechasol").val();
  let fechasolform = fechasol.split("/").reverse().join("-");
  let fechafin = $("#fechafin").val();
  let fechafinform = fechafin.split("/").reverse().join("-");
  let notas = $("#notas").val();
  let cantidadfin = parseFloat($("#cantidadfin").val());

  // console.log(cantidad);
  if(cantidadfin == 0) {
    swal({
      title: 'Error',
      text: "La cantidad real del depósito debe ser mayor a 0",
      type: 'error',
    });
    return;
  }else{
    swal({
      title: 'Confirmar',
      text: "Al finalizar el depósito, este pasará a tener un status finalizado y ser contabilizado en el historial del inversionista. La acción no se puede deshacer",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Finalizar'
    }).then((result) => {
      if(result.value){
          $.ajax({
          type:'post',
          data: {
            'action' : 'operacion_finalizar_cons',
            'id' : id,
            'idmovind' : idmovind,
            'idmovgral' : idmovgral,
            'cantidadfin' : cantidadfin,
            'fechasol' : fechasolform,
            'fechafin' : fechafinform,
            'notas' : notas,
            'tipo' : 'deposito'
          },
          url: url_operacion_conservador.ajaxopeurl,
          success: function(data) {
            var resultado = JSON.parse(data);
            console.log(resultado);
            if(resultado.respuesta==1){
              $("#modal-confindep").modal('toggle');
              $("#cantidadfin").val('0.00');
              $("#idmovgral").val('');
              $("#idmovind").val('');
              $("#fechafin").val('');
              $("#fechasol").val('');
              $(".tab-consadmindepositos").DataTable().ajax.reload();
                swal(
                'Depósito autorizado',
                'El inversionista vera ahora su depósito como autorizado dentro de su historial.',
                'success'
              )
            }else{
              swal(
              'Ocurrió un error',
              'Se ha producido un error inesperado al tratar de finalizar el depósito. Por favor intente nuevamente más tarde',
              'error'
              )
            }
          }
        });
      }
    })
  }

}

function cancelarDepositoCon(e){

  e.preventDefault();

  let id = $("#idcdep").val();
  let fechasol = $("#cfechasol").val();
  let fechasolform = fechasol.split("/").reverse().join("-");
  let fechafin = $("#cfechafin").val();
  let fechafinform = fechafin.split("/").reverse().join("-");
  let notas = $("#cnotas").val();
  let fechaval = validarFecha(fechafin);
  //console.log(interes);
  //console.log(idmovgral);

  // console.log(cantidad);
  swal({
    title: 'Confirmar',
    text: "¿Esta seguro que desea cancelar este depósito?",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, Cancelar',
    cancelButtonText: 'No'
  }).then((result) => {
    if(result.value){
        $.ajax({
        type:'post',
        data: {
          'action' : 'operacion_cancelar_cons',
          'id' : id,
          'fechasol' : fechasolform,
          'fechafin' : fechafinform,
          'notas' : notas,
          'tipo' : 'deposito'
        },
        url: url_operacion_conservador.ajaxopeurl,
        success: function(data) {
          var resultado = JSON.parse(data);
          console.log(resultado);
          if(resultado.respuesta==1){
            $("#modal-concancdep").modal('toggle');
            $('#cnotas').val('');
            $(".tab-consadmindepositos").DataTable().ajax.reload();
              swal(
              'Depósito cancelado',
              'El depósito del inversionista se ha cancelado correctamente.',
              'success'
            )
          }else{
            swal(
            'Ocurrió un error',
            'Se ha producido un error inesperado al tratar de cancelar el depósito. Por favor intente nuevamente más tarde',
            'error'
            )
          }
        }
      });
    }
  })

}
