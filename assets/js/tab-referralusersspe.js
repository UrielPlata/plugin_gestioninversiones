$ = jQuery.noConflict();//para que el $ de jquery sea reconocido en el script

/*=============================================
Data Table
=============================================*/
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const projects_bl = urlParams.get('p');

  $(".tab-referralusersspe").DataTable({
    pageLength : 12,
    lengthMenu: [[10, 12, -1], [10, 12, 'Todos']],
    "ajax": {
      url: tabla_referral_url.tabla_ajax + '?action=mostrarTablaReferralUsersSpecial',
      type: 'POST',
      data: {
        "pid": projects_bl
      },
      cache:false,
    },
    "columns": [
        { data: 'id' },
        { data: 'nombre' },
        { data: 'ncuenta' },
        { data: 'acciones' },
        { data: 'namec' }
    ],
 	  "deferRender": true,
  	"retrieve": true,
  	"processing": true,
    responsive: {
        breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 768 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]
    },
    "language": {

      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_ usuarios",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "Mostrando usuarios del _START_ al _END_ de un total de _TOTAL_",
      "sInfoEmpty":      "Mostrando usuarios del 0 al 0 de un total de 0",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ usuarios)",
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
