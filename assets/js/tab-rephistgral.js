$ = jQuery.noConflict();//para que el $ de jquery sea reconocido en el script

/*=============================================
Data Table
=============================================*/


  $(".tab-rephistgral").DataTable({
    pageLength : 12,
    lengthMenu: [[12], [12]],
    // order: [ 0, 'asc' ],
    // "columnDefs": [
    //   { "visible": false, "targets": 0 }
    // ],
    "ajax": {
      url: tabla_admindep_url.tabla_ajax + '?action=mostrarTablaRepHistGral',
      cache:false,
    },
    "columns": [
        { data: 'id' },
        { data: 'agno' },
        { data: 'mes' },
        { data: 'subtotalinv' },
        { data: 'utilacum' },
        { data: 'utilref' },
        { data: 'utiltot' },
        { data: 'external' },
        { data: 'utilfinal' },
        { data: 'totalinv' },
        { data: 'acciones' }
    ],
 	  "deferRender": true,
  	"retrieve": true,
  	"processing": true,
    "language": {

      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_ depositos",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "Mostrando depositos ID del _START_ al _END_ de un total de _TOTAL_",
      "sInfoEmpty":      "Mostrando depositos ID del 0 al 0 de un total de 0",
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
