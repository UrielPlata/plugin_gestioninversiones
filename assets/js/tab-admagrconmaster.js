$ = jQuery.noConflict();//para que el $ de jquery sea reconocido en el script

/*=============================================
Data Table
=============================================*/


  $(".tab-admagrconmaster").DataTable({
    "ajax": {
      url: url_solicitar_agresivo.ajaxurl + '?action=mostrarTablaAdmAgrConMaster',
      cache:false,
    },
    order: [ 0, 'desc' ],
    "columns": [
        { data: 'id' },
        { data: 'periodo' },
        { data: 'depositos' },
        { data: 'capinicial' },
        { data: 'utilinicial' },
        { data: 'combroker' },
        { data: 'utilreal' },
        { data: 'investors' },
        { data: 'theinc' },
        { data: 'gopro' },
        { data: 'utilrealpor' },
        { data: 'utilinvpor' },
        { data: 'retiros' },
        { data: 'totalcierremes' },
        { data: 'notas' },
        { data: 'fecharegistro' }
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
