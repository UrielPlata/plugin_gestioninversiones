$ = jQuery.noConflict();//para que el $ de jquery sea reconocido en el script

/*=============================================
Data Table
=============================================*/


  $(".tab-admindepositos").DataTable({
    "ajax": {
      url: tabla_admindep_url.tabla_ajax + '?action=mostrarTablaAdminDep',
      cache:false,
    },
    "columns": [
        { data: 'id' },
        { data: 'nombre' },
        { data: 'cantidad' },
        { data: 'cantidadfin' },
        { data: 'notas' },
        { data: 'fechadep' },
        { data: 'status' },
        { data: 'idmov_ind' },
        { data: 'idmov_gral' },
        { data: 'fecha' },
        { data: 'fechafin' },
        { data: 'wallet' },
        { data: 'walletcode' },
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
