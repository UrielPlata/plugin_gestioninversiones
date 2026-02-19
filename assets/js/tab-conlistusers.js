$ = jQuery.noConflict();//para que el $ de jquery sea reconocido en el script

/*=============================================
Data Table
=============================================*/
// console.log(cuenta);

$(".tab-conlistusers").DataTable({
  "ajax": {
    url: url_solicitar_conservador.ajaxurl + '?action=mostrarTablaConListaUsuarios',
    cache:false,
  },
  "columns": [
    { data: 'id' },
    { data: 'nombre' },
    { data: 'email' },
    { data: 'acceso' },
    { data: 'proyeccion' },
    { data: 'perfil' },
    { data: 'capprincipal' },
    { data: 'tipowallet' },
    { data: 'wallet' }
  ],
  "deferRender": true,
  "retrieve": true,
  "processing": true,
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
