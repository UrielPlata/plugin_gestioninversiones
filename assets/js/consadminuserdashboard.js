$ = jQuery.noConflict();

( function( $ ) {

  $("#form-addconsret").on('submit', validarConRetiro );
  $("#form-addconsdep").on('submit', validarConDeposito );
  let userid = parseInt($(".tab-consdep").attr('data-user'));

  /*=============================================
  Data Table
  =============================================*/

  $(".tab-consdep").DataTable({
    pageLength : 25,
    lengthMenu: [[10, 12, 25, -1], [10, 12, 25, 'Todos']],
    order: [ 0, 'desc' ],
    "ajax": {
      url: url_solicitar_conservador.ajaxurl + '?action=mostrarTablaConHistoDep',
      type: 'POST',
      data: {
        "id": userid
      },
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

  $(".tab-consret").DataTable({
    pageLength : 25,
    lengthMenu: [[10, 12, 25, -1], [10, 12, 25, 'Todos']],
    order: [ 0, 'desc' ],
    "ajax": {
      url: url_solicitar_conservador.ajaxurl + '?action=mostrarTablaConHistoRet',
      type: 'POST',
      data: {
        "id": userid
      },
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

  $(".tab-consutiluser").DataTable({
    pageLength : 12,
    lengthMenu: [[10, 12, -1], [10, 12, 'Todos']],
    // columns: [
    // { "orderSequence": [ "asc" ] },
    // ],
    // order: [ 0, 'asc' ],
    // "columnDefs": [
    //   { "visible": false, "targets": 0 }
    // ],
    // columns: [10, 9, 8, 7, 6, 5, 4, 3, 2, 1, 0],
    "language": {

      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_ meses",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "Mostrando meses ID del _START_ al _END_ de un total de _TOTAL_",
      "sInfoEmpty":      "Mostrando meses ID del 0 al 0 de un total de 0",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ meses)",
      "sInfoPostFix":    "",
      "sSearch":         "Buscar:",
      "sUrl":            "",
      "sInfoThousands":  ",",
      "sLoadingRecords": "Cargando...",
      "oPaginate": {
      "sFirst":    "Primero",
      "sLast":     "Último",
      "sNext":     "Anterior",
      "sPrevious": "Siguiente"
      },
      "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }

    }

  });

  // Click para ver la tabla de depositos del mes del inversionista
  $(".tab-consutiluser").on('click','.btn-ver-dep', function(e){
    var id = $(this).attr('data-userid');
    var mes = $(this).attr('data-mes');
    var agno = $(this).attr('data-agno');

    let protocol = window.location.protocol;
    let url = "admin.php?page=crc_consadminverdepmes&id="+id+"&m="+mes+"&p="+agno;

    $(location).attr('href',url);
  });

  // Click para ver la tabla de retiros del mes del inversionista
  $(".tab-consutiluser").on('click','.btn-ver-ret', function(e){
    var id = $(this).attr('data-userid');
    var mes = $(this).attr('data-mes');
    var agno = $(this).attr('data-agno');

    let protocol = window.location.protocol;
    let url = "admin.php?page=crc_consadminverretmes&id="+id+"&m="+mes+"&p="+agno;

    $(location).attr('href',url);
  });


}( jQuery ) );


function validarConDeposito(evento) {

  evento.preventDefault();
  $("#agregarconsdep").hide();
  let cantidaddep = $("#dcon_cantidad");
  let userdep = $("#userdep");

  let dcon_cantidad = parseFloat($(cantidaddep).val());
  let user = $(userdep).val();

  // console.log(cantidad);
  if(dcon_cantidad == 0) {
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
        'action' : 'solicitar_condeposito',
        'cantidad' : dcon_cantidad,
        'user' : user
      },
      url: url_solicitar_conservador.ajaxurl,
      success: function(data) {
        var resultado = JSON.parse(data);
        // console.log(resultado);
        if(resultado.respuesta==1){
          $("#modal-addconsdep").modal('toggle');
          $(cantidaddep).val('0.00');
          $(".tab-consdep").DataTable().ajax.reload();
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
        $("#agregarconsdep").show();
      }
    });
  }

}

function validarConRetiro(evento) {
  evento.preventDefault();
  $("#agregarconsret").hide();
  let cantidadret = $("#rcon_cantidad");
  let userret = $("#userret");

  let rcon_cantidad = parseFloat($(cantidadret).val());
  let totaldisp = parseFloat($("#totaldisp").val());
  let user = $(userret).val();

  // console.log(cantidad);
  if(rcon_cantidad == 0) {
    swal({
      title: 'Error',
      text: "La cantidad a retirar debe ser mayor a 0",
      type: 'error',
    });
    return;
  }else if( rcon_cantidad > totaldisp){
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
        'action' : 'solicitar_conretiro',
        'cantidad' : rcon_cantidad,
        'user' : user
      },
      url: url_solicitar_conservador.ajaxurl,
      success: function(data) {
        var resultado = JSON.parse(data);
        // console.log(resultado);
        if(resultado.respuesta==1){
          $("#modal-addconsret").modal('toggle');
          $(cantidadret).val('0.00');
          $(".tab-consret").DataTable().ajax.reload();
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
        $("#agregarconsret").show();
      }
    });
  }

}
