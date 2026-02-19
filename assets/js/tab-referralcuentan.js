$ = jQuery.noConflict();//para que el $ de jquery sea reconocido en el script

/*=============================================
Data Table
=============================================*/
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const cuenta = urlParams.get('id')
// console.log(cuenta);

if(!isNaN(cuenta) == true){
  $(".tab-referralcuentan").DataTable({
    pageLength : 12,
    lengthMenu: [[10, 12, -1], [10, 12, 'Todos']],
    "ajax": {
      url: tabla_referral_url.tabla_ajax + '?action=mostrarTablaReferralCuentaN',
      type: 'POST',
      data: {
        "cid": cuenta
      },
      cache:false,
    },
    order: [ 0, 'desc' ],
    "columns": [
        { data: 'id' },
        { data: 'agno' },
        { data: 'mes' },
        { data: 'utilmes' },
        { data: 'combro' },
        { data: 'utilreal' },
        { data: 'comfinal' },
        { data: 'acciones' }
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
