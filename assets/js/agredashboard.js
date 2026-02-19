$ = jQuery.noConflict();

( function( $ ) {

  $("#form-addagreret").on('submit', validarAgrRetiro );
  $("#form-addagredep").on('submit', validarAgrDeposito );

  /*=============================================
  Data Table
  =============================================*/

  $(".tab-agredep").DataTable({
    pageLength : 25,
    lengthMenu: [[10, 12, 25, -1], [10, 12, 25, 'Todos']],
    order: [ 0, 'desc' ],
    "ajax": {
      url: url_solicitar_agresivo.ajaxurl + '?action=mostrarTablaAgrHistoDep',
      cache:false,
    },
    "columns": [
        { data: 'mes' },
        { data: 'cantidad' },
        { data: 'status' }
    ],
    "language": {

      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_ depositos",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "Depósitos del _START_ al _END_ de un total de _TOTAL_",
      "sInfoEmpty":      "Depósitos del 0 al 0 de un total de 0",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ depositos)",
      "sInfoPostFix":    "",
      "sSearch":         "Buscar:",
      "sUrl":            "",
      "sInfoThousands":  ",",
      "sLoadingRecords": "Cargando...",
      "oPaginate": {
      "sFirst":    "Primero",
      "sLast":     "Último",
      "sNext":     "Siguiente",
      "sPrevious": "Anterior"
      },
      "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }

    }

  });

  $(".tab-agreret").DataTable({
    pageLength : 25,
    lengthMenu: [[10, 12, 25, -1], [10, 12, 25, 'Todos']],
    order: [ 0, 'desc' ],
    "ajax": {
      url: url_solicitar_agresivo.ajaxurl + '?action=mostrarTablaAgrHistoRet',
      cache:false,
    },
    "columns": [
        { data: 'mes' },
        { data: 'cantidad' },
        { data: 'status' }
    ],
    "language": {

      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_ retiros",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "Retiros del _START_ al _END_ de un total de _TOTAL_",
      "sInfoEmpty":      "Retiros del 0 al 0 de un total de 0",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ retiros)",
      "sInfoPostFix":    "",
      "sSearch":         "Buscar:",
      "sUrl":            "",
      "sInfoThousands":  ",",
      "sLoadingRecords": "Cargando...",
      "oPaginate": {
      "sFirst":    "Primero",
      "sLast":     "Último",
      "sNext":     "Siguiente",
      "sPrevious": "Anterior"
      },
      "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }

    }

  });

}( jQuery ) );


function validarAgrDeposito(evento) {

  evento.preventDefault();
  $("#agregaragredep").hide();
  let cantidaddep = $("#dagr_cantidad");
  let userdep = $("#userdep");

  let dagr_cantidad = parseFloat($(cantidaddep).val());
  let user = $(userdep).val();

  // console.log(cantidad);
  if(dagr_cantidad == 0) {
    swal({
      title: 'Error',
      text: "La cantidad a depositar debe ser mayor a 0",
      type: 'error',
    });
    return;
  }else{
    $.ajax({
      type:'post',
      data: {
        'action' : 'solicitar_agrdeposito',
        'cantidad' : dagr_cantidad,
        'user' : user
      },
      url: url_solicitar_agresivo.ajaxurl,
      success: function(data) {
        var resultado = JSON.parse(data);
        // console.log(resultado);
        if(resultado.respuesta==1){
          $("#modal-addagredep").modal('toggle');
          $(cantidaddep).val('0.00');
          $(".tab-agredep").DataTable().ajax.reload();
          swal(
            'Solicitud generada',
            'Se ha enviado una liga de confirmación de solicitud a su correo. En caso de no encontrar el correo revise su bandeja de spam.',
            'success'
          );
        }else{
          swal(
          'Ocurrió un error',
          'Se ha producido un error inesperado al tratar de solicitar el depósito. Por favor intente nuevamente más tarde',
          'error'
        )
        }
        $("#agregaragredep").show();
      }
    });
  }

}

function validarAgrRetiro(evento) {
  evento.preventDefault();
  $("#agregaragreret").hide();
  let cantidadret = $("#ragr_cantidad");
  let userret = $("#userret");

  let ragr_cantidad = parseFloat($(cantidadret).val());
  let totaldisp = parseFloat($("#totaldisp").val());
  let user = $(userret).val();

  // console.log(cantidad);
  if(ragr_cantidad == 0) {
    swal({
      title: 'Error',
      text: "La cantidad a retirar debe ser mayor a 0",
      type: 'error',
    });
    return;
  }else if( ragr_cantidad > totaldisp){
    swal({
      title: 'Error',
      text: "La cantidad a retirar solicitada excede el total disponible del mes cerrado inmediato anterior",
      type: 'error',
    });
    return;
  }else{
    $.ajax({
      type:'post',
      data: {
        'action' : 'solicitar_agrretiro',
        'cantidad' : ragr_cantidad,
        'user' : user
      },
      url: url_solicitar_agresivo.ajaxurl,
      success: function(data) {
        var resultado = JSON.parse(data);
        // console.log(resultado);
        if(resultado.respuesta==1){
          $("#modal-addagreret").modal('toggle');
          $(cantidadret).val('0.00');
          $(".tab-agreret").DataTable().ajax.reload();
          swal(
            'Solicitud generada',
            'Se ha enviado una liga de confirmación de solicitud a su correo. En caso de no encontrar el correo revise su bandeja de spam.',
            'success'
          );
        }else{
          swal(
          'Ocurrió un error',
          'Se ha producido un error inesperado al tratar de solicitar el retiro. Por favor intente nuevamente más tarde',
          'error'
          );
        }
        $("#agregaragreret").show();
      }
    });
  }

}
