$ = jQuery.noConflict();
//
// Tabs Toggler
//

( function( $ ) {

  $('#form-agreditret').on('submit', editarRetiroAgr );
  $('#form-agrfinret').on('submit', finalizarRetiroAgr );
  $('#form-agrcancret').on('submit', cancelarRetiroAgr );

  $('.tab-agreadminretiros').on('click','.btn-editar', function(e){

    var id = $(this).attr('data-retiro');

      $.ajax({
      type:'post',
      data: {
        'action' : 'traer_datos_agre',
        'id' : id,
        'tipo' : 'retiro'
      },
      url: url_operacion_agresivo.ajaxopeurl,
      success: function(data) {
        var resultado = JSON.parse(data);
        // console.log(resultado);
        if(resultado.data.length != 0){

          let datos = resultado.data[0];
          var idmovind = datos.idmovind;
          var date = datos.fechafin;
          var datesol = datos.fecha;
          var efechafin = date.split("/").reverse().join("-");
          var efechasol = datesol.split("/").reverse().join("-");
          var cantfin = parseFloat(datos.cantidadfin);
          var cantini = datos.cantidad;
          var notas = datos.notas;


          $("#ideret").attr('value', id);
          $("#eidmovind").val(idmovind);
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

  $('.tab-agreadminretiros').on('click','.btn-finalizar', function(e){

    var id = $(this).attr('data-retiro');

      $.ajax({
      type:'post',
      data: {
        'action' : 'traer_datos_agre',
        'id' : id,
        'tipo' : 'retiro'
      },
      url: url_operacion_agresivo.ajaxopeurl,
      success: function(data) {
        var resultado = JSON.parse(data);
        console.log(resultado);
        if(resultado.data.length != 0){

          let datos = resultado.data[0];
          var cantini = datos.cantidad;
          var datesol = datos.fecha;
          var efechasol = datesol.split("/").reverse().join("-");

          $("#idret").attr('value', id);
          $("#cantidadini").attr('value', cantini);
          $("#cantidadfin").val(cantini);
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

  $('.tab-agreadminretiros').on('click','.btn-cancelar', function(e){

    var id = $(this).attr('data-retiro');

      $.ajax({
      type:'post',
      data: {
        'action' : 'traer_datos_agre',
        'id' : id,
        'tipo' : 'retiro'
      },
      url: url_operacion_agresivo.ajaxopeurl,
      success: function(data) {
        var resultado = JSON.parse(data);
        console.log(resultado);
        if(resultado.data.length != 0){

          let datos = resultado.data[0];
          var datesol = datos.fecha;
          var efechasol = datesol.split("/").reverse().join("-");
          var cantini = datos.cantidad;
          var notas = datos.notas;


          $("#idcret").attr('value', id);
          $("#ccantidadini").attr('value', cantini);
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

$('.tab-agreadminretiros').on('mouseenter','.microtexto', function(e){
  let columna = e.target;
  let texto = $(columna).attr('aria-label').replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
  $(columna).attr('aria-label',texto);
});

}( jQuery ) );

function editarRetiroAgr(e){

  e.preventDefault();

  let id = $("#ideret").val();
  let idmovind = $("#eidmovind").val();
  let cantidadfin = parseFloat($("#ecantidadfin").val());

  let fechasol = $("#efechasol").val();
  let fechasolform = fechasol.split("/").reverse().join("-");
  let fechafin = $("#efechafin").val();
  let fechafinform = fechafin.split("/").reverse().join("-");
  let notas = $("#enotas").val();
  let fechaval = validarFecha(fechafin);
  console.log(fechafin);

  // console.log(cantidad);
  if(cantidadfin == 0) {
    swal({
      title: 'Error',
      text: "La cantidad final del retiro debe ser mayor a 0",
      type: 'error',
    });
    return;
  }else{
    swal({
      title: 'Confirmar',
      text: "¿Esta seguro que desea editar los campos de este retiro?",
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
            'action' : 'operacion_editar_agre',
            'id' : id,
            'idmovind' : idmovind,
            'fechasol' : fechasolform,
            'fechafin' : fechafinform,
            'cantidadfin' : cantidadfin,
            'notas' : notas,
            'tipo' : 'retiro'
          },
          url: url_operacion_agresivo.ajaxopeurl,
          success: function(data) {
            var resultado = JSON.parse(data);
            console.log(resultado);
            if(resultado.respuesta==1){
              $("#modal-agreditret").modal('toggle');
              $("#ecantidadfin").val('0.00');
              $("#eidmovind").val('');
              $('#enotas').val('');
              $(".tab-agreadminretiros").DataTable().ajax.reload();
                swal(
                'Retiro editado',
                'El retiro del inversionista se ha editado correctamente.',
                'success'
              )
            }else{
              swal(
              'Ocurrió un error',
              'Se ha producido un error inesperado al tratar de editar el retiro. Por favor intente nuevamente más tarde',
              'error'
              )
            }
          }
        });
      }
    })
  }

}

function finalizarRetiroAgr(e){

  e.preventDefault();

  let id = $("#idret").val();
  let idmovind = $("#idmovind").val();
  let cantidadfin = parseFloat($("#cantidadfin").val());
  let fechasol = $("#fechasol").val();
  let fechasolform = fechasol.split("/").reverse().join("-");
  let fechafin = $("#fechafin").val();
  let fechafinform = fechafin.split("/").reverse().join("-");
  let notas = $("#notas").val();

  // console.log(cantidad);
  if(cantidadfin == 0) {
    swal({
      title: 'Error',
      text: "La cantidad final del retiro debe ser mayor a 0",
      type: 'error',
    });
    return;
  } else{
    swal({
      title: 'Confirmar',
      text: "Al autorizar el retiro, este pasará a tener un status Autorizado y ser contabilizado en el historial del inversionista. La acción no se puede deshacer",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Autorizar'
    }).then((result) => {
      if(result.value){
          $.ajax({
          type:'post',
          data: {
            'action' : 'operacion_finalizar_agre',
            'id' : id,
            'idmovind' : idmovind,
            'idmovgral' : '',
            'cantidadfin' : cantidadfin,
            'fechasol' : fechasolform,
            'fechafin' : fechafinform,
            'notas' : notas,
            'tipo' : 'retiro'
          },
          url: url_operacion_agresivo.ajaxopeurl,
          success: function(data) {
            var resultado = JSON.parse(data);
            console.log(resultado);
            if(resultado.respuesta==1){
              $("#modal-agrfinret").modal('toggle');
              $("#cantidadfin").val('0.00');
              $("#idmovind").val('');
              $("#fechafin").val('');
              $("#fechasol").val('');
              $(".tab-agreadminretiros").DataTable().ajax.reload();
                swal(
                'Retiro autorizado',
                'El inversionista vera ahora su retiro como autorizado dentro de su historial.',
                'success'
              )
            }else{
              swal(
              'Ocurrió un error',
              'Se ha producido un error inesperado al tratar de finalizar el retiro. Por favor intente nuevamente más tarde',
              'error'
              )
            }
          }
        });
      }
    })
  }

}

function cancelarRetiroAgr(e){

  e.preventDefault();

  let id = $("#idcret").val();
  let fechasol = $("#cfechasol").val();
  let fechasolform = fechasol.split("/").reverse().join("-");
  let fechafin = $("#cfechafin").val();
  let fechafinform = fechafin.split("/").reverse().join("-");
  let notas = $("#cnotas").val();
  let fechaval = validarFecha(fechafin);

  // console.log(cantidad);
  swal({
    title: 'Confirmar',
    text: "¿Esta seguro que desea cancelar este retiro?",
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
          'action' : 'operacion_cancelar_agre',
          'id' : id,
          'fechasol' : fechasolform,
          'fechafin' : fechafinform,
          'notas' : notas,
          'tipo' : 'retiro'
        },
        url: url_operacion_agresivo.ajaxopeurl,
        success: function(data) {
          var resultado = JSON.parse(data);
          console.log(resultado);
          if(resultado.respuesta==1){
            $("#modal-agrcancret").modal('toggle');
            $('#cnotas').val('');
            $(".tab-agreadminretiros").DataTable().ajax.reload();
              swal(
              'Retiro cancelado',
              'El retiro del inversionista se ha cancelado correctamente.',
              'success'
            )
          }else{
            swal(
            'Ocurrió un error',
            'Se ha producido un error inesperado al tratar de cancelar el retiro. Por favor intente nuevamente más tarde',
            'error'
            )
          }
        }
      });
    }
  })

}
