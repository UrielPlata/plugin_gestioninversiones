$ = jQuery.noConflict();//para que el $ de jquery sea reconocido en el script

/*=============================================
Data Table
=============================================*/
// const queryString = window.location.search;
// const urlParams = new URLSearchParams(queryString);
// const proyecto = urlParams.get('id')
// console.log(cuenta);

if(!isNaN(proyecto) == true){
  $(".tab-referralregistrosnftmes").DataTable({
    pageLength : 12,
    lengthMenu: [[10, 12, -1], [10, 12, 'Todos']],
    "ajax": {
      url: tabla_referral_url.tabla_ajax + '?action=mostrarTablaReferralProyectoNFTMes',
      type: 'POST',
      data: {
        "nid": proyecto
      },
      cache:false,
    },
    order: [ 0, 'desc' ],
    "columns": [
        { data: 'id' },
        { data: 'agno' },
        { data: 'mes' },
        { data: 'total' },
        { data: 'team' },
        { data: 'personal' },
        { data: 'numsemanas' }
    ],
 	  "deferRender": true,
  	"retrieve": true,
  	"processing": true,
    "language": {

      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_ meses",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "Mostrando meses del _START_ al _END_ de un total de _TOTAL_",
      "sInfoEmpty":      "Mostrando meses del 0 al 0 de un total de 0",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ meses)",
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
