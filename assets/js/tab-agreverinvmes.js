$ = jQuery.noConflict();//para que el $ de jquery sea reconocido en el script

/*=============================================
Data Table
=============================================*/
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const year = urlParams.get('y');
const mes = urlParams.get('m');
// console.log(cuenta);

  $(".tab-agrinvmes").DataTable({
    "ajax": {
      url: url_solicitar_agresivo.ajaxurl + '?action=mostrarTablaAdmAgrInvMes',
      type: 'POST',
      data: {
        "year" : year,
        "mes" : mes
      },
      cache:false,
    },
    order: [ 0, 'desc' ],
    "columns": [
        { data: 'id' },
        { data: 'nombre' },
        { data: 'depositos' },
        { data: 'capinicial' },
        { data: 'porparticip' },
        { data: 'utilmes' },
        { data: 'utilacum' },
        { data: 'total' },
        { data: 'porrendimiento' },
        { data: 'retiros' }
    ],
 	  "deferRender": true,
  	"retrieve": true,
  	"processing": true,
    "language": {

      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_ registros",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "Mostrando registros # del _START_ al _END_ de un total de _TOTAL_",
      "sInfoEmpty":      "Mostrando registros # del 0 al 0 de un total de 0",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
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
