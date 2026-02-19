$ = jQuery.noConflict();//para que el $ de jquery sea reconocido en el script

/*=============================================
Data Table
=============================================*/


  $(".tab-admconmaster").DataTable({
    "ajax": {
      url: tabla_admindep_url.tabla_ajax + '?action=mostrarTablaAdmConMaster',
      cache:false,
    },
    order: [ 0, 'desc' ],
    "columns": [
        { data: 'id' },
        { data: 'agno' },
        { data: 'mes' },
        { data: 'startbal' },
        { data: 'depositos' },
        { data: 'retiros' },
        { data: 'balbefcom' },
        { data: 'combroker' },
        { data: 'comtrader' },
        { data: 'balfinal' },
        { data: 'totalcuentas' },
        { data: 'profit' },
        { data: 'profitmes' },
        { data: 'acciones' }
    ],
 	  "deferRender": true,
  	"retrieve": true,
  	"processing": true,
    "language": {

      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_ balances",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "Mostrando balances # del _START_ al _END_ de un total de _TOTAL_",
      "sInfoEmpty":      "Mostrando balances # del 0 al 0 de un total de 0",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ balances)",
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
