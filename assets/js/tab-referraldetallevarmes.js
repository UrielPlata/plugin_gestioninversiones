$ = jQuery.noConflict();//para que el $ de jquery sea reconocido en el script

/*=============================================
Data Table
=============================================*/
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const year = urlParams.get('y');
const mes = urlParams.get('m');
// console.log(cuenta);

if(!isNaN(year) == true){
  $(".tab-referraldetallemesvar").DataTable({
    pageLength : 12,
    lengthMenu: [[10, 12, -1], [10, 12, 'Todos']],
    "ajax": {
      url: tabla_referral_url.tabla_ajax + '?action=mostrarTablaReferralDetalleMesVar',
      type: 'POST',
      data: {
        "year" : year,
        "mes" : mes
      },
      cache:false,
    },
    "columns": [
        { data: 'id' },
        { data: 'cantidad' },
        { data: 'concepto' },
        { data: 'registro' },
        { data: 'acciones' },
    ],
 	  "deferRender": true,
  	"retrieve": true,
  	"processing": true,
    "language": {

      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_ registros",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
      "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
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
}
