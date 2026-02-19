$ = jQuery.noConflict();

// Buscador desde Input
$(document).ready( function() {

  var queryString = window.location.search;
  var urlParams = new URLSearchParams(queryString);
  let mesllega = urlParams.get('m');
  let agnollega = urlParams.get('p');

  /*=============================================
  Data Table
  =============================================*/


    $(".tab-repmensual").DataTable({
      pageLength : 25,
      lengthMenu: [[10, 12, 25, -1], [10, 12, 25, 'Todos']],
      "columns": [
          { data: 'nombre' },
          { data: 'depmes' },
          { data: 'capini' },
          { data: 'util' },
          { data: 'utilacum' },
          { data: 'retmes' },
          { data: 'subtotalinv' },
          { data: 'utilref' },
          { data: 'utiltot' },
          { data: 'total' },
          { data: 'acciones' }
      ],
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

    $(".tab-repmesdep").DataTable({
      pageLength : 25,
      lengthMenu: [[10, 12, 25, -1], [10, 12, 25, 'Todos']],
      "columns": [
          { data: 'mes' },
          { data: 'cantidad' },
          { data: 'status' }
      ],
      "language": {

        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ depositos",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando depositos ID del _START_ al _END_ de un total de _TOTAL_",
        "sInfoEmpty":      "Mostrando depositos ID del 0 al 0 de un total de 0",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ depositos)",
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

    $(".tab-repmesret").DataTable({
      pageLength : 25,
      lengthMenu: [[10, 12, 25, -1], [10, 12, 25, 'Todos']],
      "columns": [
          { data: 'mes' },
          { data: 'cantidad' },
          { data: 'status' }
      ],
      "language": {

        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ retiros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando retiros ID del _START_ al _END_ de un total de _TOTAL_",
        "sInfoEmpty":      "Mostrando retiros ID del 0 al 0 de un total de 0",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ retiros)",
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

  $("#rep-fecha").on('submit', buscarmes );

  //Boton para ir a ver el dashboard del usuario
  $(".tab-repmensual").on('click','.btn-proyec-user', function(e){
    var id = $(this).attr('data-usuario');

    let protocol = window.location.protocol;
		let url = "admin.php?page=crc_admin_userdashboard&id="+id;

    $(location).attr('href',url);
  });

  // Boton para ir a ver la tabla de utilidad ref cierre del mes
  $(".tab-repmensual").on('click','.btn-ver-refmesuser', function(e){
    var id = $(this).attr('data-userid');
    var mes = $(this).attr('data-mes');
    var agno = $(this).attr('data-agno');

    let protocol = window.location.protocol;
		let url = "admin.php?page=crc_admin_verutilrefmes&id="+id+"&m="+mes+"&p="+agno;

    $(location).attr('href',url);
  });

  if (mesllega && agnollega) {
    let newmes = parseInt(mesllega);
    let newagno = parseInt(agnollega);

    $('#rep-mes').val(newmes);
    $('#rep-agno').val(newagno);

    $('#rep-fecha').trigger('submit');
    // console.log("pedido");
  }

  // Click para ver la tabla de retiros del mes del inversionista
  $(".tab-repmensual").on('click','.btn-ver-ret', function(e){
    var id = $(this).attr('data-userid');
    var mes = $(this).attr('data-mes');
    var agno = $(this).attr('data-agno');

    let protocol = window.location.protocol;
		let url = "admin.php?page=crc_admin_verretmes&id="+id+"&m="+mes+"&p="+agno;

    $(location).attr('href',url);
  });

  // Click para ver la tabla de depositos del mes del inversionista
  $(".tab-repmensual").on('click','.btn-ver-dep', function(e){
    var id = $(this).attr('data-userid');
    var mes = $(this).attr('data-mes');
    var agno = $(this).attr('data-agno');

    let protocol = window.location.protocol;
		let url = "admin.php?page=crc_admin_verdepmes&id="+id+"&m="+mes+"&p="+agno;

    $(location).attr('href',url);
  });


});

function buscarmes(e){
  e.preventDefault();

  let year = parseInt($('#rep-agno').val());
  let month = parseInt($('#rep-mes').val());
  let pdyear = parseInt($('#pd-agno').val());
  let pdmonth = parseInt($('#pd-mes').val());

  let mesesNombre = new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
  // let mes = year+"-"+month+"-01";

  let messel = new Date(year, month-1, 1, 0, 0, 0);
  let mespd = new Date(pdyear, pdmonth-1, 1, 0, 0, 0);
  let meshoy = new Date();

  if(messel >= meshoy){
    swal(
    'Periodo incorrecto',
    'Solo puede seleccionar para consulta meses anteriores al actual',
    'error'
    )
  }else if (messel < mespd){
    swal(
    'Periodo incorrecto',
    'Seleccione un año y mes igual o posterior al inicio de depósitos en el sistema ('+mesesNombre[pdmonth-1]+' de '+pdyear+')',
    'error'
    )
  }else{
    $(".caja-tabla").hide();
    $(".caja-spin").show();
    $.ajax({
      type:'post',
      data: {
        'action' : 'mostrarTablaRepMensual',
        'mes' : month,
        'agno' : year
      },
      url: url_operep.ajax_operep_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        let tabla = $(".tab-repmensual").DataTable();
        let tabla1 = $(".tab-repmesdep").DataTable();
        let tabla2 = $(".tab-repmesret").DataTable();
        console.log(resultado);
        let datos = resultado.data.tabla;
        let datosdep = resultado.data.tabla1;
        let datosret = resultado.data.tabla2;

        if(datos === null || !datos.length){
          swal(
          'Ocurrio un error',
          'Se ha producido un error al momento de tratar de recuperar la información, por favor seleccione otro periodo',
          'error'
          );
          tabla.clear();
          tabla.draw();
          $(".caja-spin").hide();
          $(".caja-tabla").show();
        }else{
          let depmas = resultado.data.depmaster;
          let retmas = resultado.data.retmaster;
          let totalmes = resultado.data.totalmes;
          // console.log();
          $(".cant-depmas").html(depmas);
          $(".cant-retmas").html(retmas);
          $(".cant-totalinv").html(totalmes);

          tabla1.clear();
          tabla1.rows.add(datosdep).draw();

          tabla2.clear();
          tabla2.rows.add(datosret).draw();

          tabla.clear();
          tabla.rows.add(datos).draw();
          $(".caja-spin").hide();
          $(".caja-tabla").show();
        }

      }
    });
  }
  // console.log(month);
  // console.log(messel);
  // console.log(meshoy);
  // console.log(pdmonth);
}
