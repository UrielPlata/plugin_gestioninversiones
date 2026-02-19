$ = jQuery.noConflict();//para que el $ de jquery sea reconocido en el script

/*=============================================
Data Table
=============================================*/
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const year = urlParams.get('p');
const mes = urlParams.get('m');
const id = urlParams.get('id');

// console.log(cuenta);

if(!isNaN(year) == true){
  $(".tab-consuserretmes").DataTable({
    pageLength : 10,
    lengthMenu: [[10, 12, -1], [10, 12, 'Todos']],
    "ajax": {
      url: url_solicitar_conservador.ajaxurl + '?action=mostrarTablaConUserRetMes',
      type: 'POST',
      data: {
        "year" : year,
        "mes" : mes,
        "id" : id
      },
      cache:false,
    },
    "columns": [
        { data: 'id' },
        { data: 'cantidad' },
        { data: 'cantidad_final' },
        { data: 'notas' },
        { data: 'status' },
        { data: 'id_retiro_ind' },
        { data: 'fecha_solicitud' },
        { data: 'fecha_fin' }
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
