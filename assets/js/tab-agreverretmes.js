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
  $(".tab-agrretmes").DataTable({
    "ajax": {
      url: url_solicitar_agresivo.ajaxurl + '?action=mostrarTablaAgrDetalleRetMes',
      type: 'POST',
      data: {
        "year" : year,
        "mes" : mes
      },
      cache:false,
    },
    "columns": [
      { data: 'id' },
      { data: 'nombre' },
      { data: 'cantidad' },
      { data: 'cantidadfin' },
      { data: 'notas' },
      { data: 'status' },
      { data: 'idmov_ind' },
      { data: 'fecha' },
      { data: 'fechafin' },
      { data: 'wallet' },
      { data: 'walletcode' }
    ],
 	  "deferRender": true,
  	"retrieve": true,
  	"processing": true,
    "language": {

      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_ retiros",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "Mostrando retiros del _START_ al _END_ de un total de _TOTAL_",
      "sInfoEmpty":      "Mostrando retiros del 0 al 0 de un total de 0",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ retiros)",
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
