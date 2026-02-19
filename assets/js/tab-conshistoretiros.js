$ = jQuery.noConflict();//para que el $ de jquery sea reconocido en el script

/*=============================================
Data Table
=============================================*/


  $(".tab-conshistoretiros").DataTable({
    "ajax": {
      url: url_solicitar_conservador.ajaxurl + '?action=mostrarConTablaHistoRetFull',
      cache:false,
    },
    "columns": [
        { data: 'id' },
        { data: 'cantidad' },
        { data: 'cantidadfin' },
        { data: 'status' },
        { data: 'idmov_ind' },
        { data: 'fecha' },
        { data: 'fechafin' }
    ],
 	  "deferRender": true,
  	"retrieve": true,
  	"processing": true,
    "language": {

      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_ retiros",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "Mostrando retiros ID del _START_ al _END_ de un total de _TOTAL_",
      "sInfoEmpty":      "Mostrando retiros ID del 0 al 0 de un total de 0",
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
