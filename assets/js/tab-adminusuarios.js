$ = jQuery.noConflict();//para que el $ de jquery sea reconocido en el script

/*=============================================
Data Table
=============================================*/


  $(".tab-usersadm").DataTable({
    "ajax": {
      url: tabla_adminuser_url.tabla_ajax + '?action=mostrarTablaAdminUsers',
      cache:false,
    },
    "columns": [
        { data: 'id' },
        { data: 'userid' },
        { data: 'nombre' },
        { data: 'email' },
        { data: 'acceso' },
        { data: 'status' },
        { data: 'acciones' },
        { data: 'wallet' },
        { data: 'walletcode' },
        { data: 'pais' },
        { data: 'inicial' },
        { data: 'intacu' },
    ],
 	  "deferRender": true,
  	"retrieve": true,
  	"processing": true,
    pageLength : 25,
    lengthMenu: [[10, 12, 25, -1], [10, 12, 25, 'Todos']],
    "language": {

      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_ inversionistas",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "Mostrando inversionistas ID del _START_ al _END_ de un total de _TOTAL_",
      "sInfoEmpty":      "Mostrando inversionistas ID del 0 al 0 de un total de 0",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ inversionistas)",
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
