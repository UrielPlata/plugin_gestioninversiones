$ = jQuery.noConflict();

// Buscador desde Input
$(document).ready( function() {

  /*=============================================
  Data Table
  =============================================*/

    $(".tab-referralreportemesspe").DataTable({
      pageLength : 25,
      lengthMenu: [[10, 12, 25, -1], [10, 12, 25, 'Todos']],
      "columns": [
        { data: 'id' },
        { data: 'cuenta' },
        { data: 'comandres' },
        { data: 'utilmes' },
        { data: 'combro' },
        { data: 'comtra' },
        { data: 'pcomtra' },
        { data: 'utilafter' },
        { data: 'salini' },
        { data: 'putilrealfin' },
        { data: 'comtiger' },
      ],
      "language": {

        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ cuentas",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando cuentas del _START_ al _END_ de un total de _TOTAL_",
        "sInfoEmpty":      "Mostrando cuentas del 0 al 0 de un total de 0",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ cuentas)",
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

  $("#refrepspe-fecha").on('submit', buscarmes );

  //Boton para ir a ver el dashboard del usuario
  // $(".tab-repmensual").on('click','.btn-proyec-user', function(e){
  //   var id = $(this).attr('data-usuario');
  //
  //   let protocol = window.location.protocol;
	// 	let url = "admin.php?page=crc_admin_userdashboard&id="+id;
  //
  //   $(location).attr('href',url);
  // });

  // Boton para ir a ver la tabla de utilidad ref cierre del mes
  // $(".tab-repmensual").on('click','.btn-ver-refmesuser', function(e){
  //   var id = $(this).attr('data-userid');
  //   var mes = $(this).attr('data-mes');
  //   var agno = $(this).attr('data-agno');
  //
  //   let protocol = window.location.protocol;
	// 	let url = "admin.php?page=crc_admin_verutilrefmes&id="+id+"&m="+mes+"&p="+agno;
  //
  //   $(location).attr('href',url);
  // });

  // if (mesllega && agnollega) {
  //   let newmes = parseInt(mesllega);
  //   let newagno = parseInt(agnollega);
  //
  //   $('#rep-mes').val(newmes);
  //   $('#rep-agno').val(newagno);
  //
  //   $('#rep-fecha').trigger('submit');
  //   // console.log("pedido");
  // }

  // Click para ver la tabla de retiros del mes del inversionista
  // $(".tab-repmensual").on('click','.btn-ver-ret', function(e){
  //   var id = $(this).attr('data-userid');
  //   var mes = $(this).attr('data-mes');
  //   var agno = $(this).attr('data-agno');
  //
  //   let protocol = window.location.protocol;
	// 	let url = "admin.php?page=crc_admin_verretmes&id="+id+"&m="+mes+"&p="+agno;
  //
  //   $(location).attr('href',url);
  // });
  //
  // // Click para ver la tabla de depositos del mes del inversionista
  // $(".tab-repmensual").on('click','.btn-ver-dep', function(e){
  //   var id = $(this).attr('data-userid');
  //   var mes = $(this).attr('data-mes');
  //   var agno = $(this).attr('data-agno');
  //
  //   let protocol = window.location.protocol;
	// 	let url = "admin.php?page=crc_admin_verdepmes&id="+id+"&m="+mes+"&p="+agno;
  //
  //   $(location).attr('href',url);
  // });


});

function buscarmes(e){
  e.preventDefault();

  let year = parseInt($('#rep-agno').val());
  let month = parseInt($('#rep-mes').val());

  let queryString = window.location.search;
  let urlParams = new URLSearchParams(queryString);
  let cuenta = urlParams.get('p')

  let mesesNombre = new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
  // let mes = year+"-"+month+"-01";

  let messel = new Date(year, month-1, 1, 0, 0, 0);
  let meshoy = new Date();

  if(messel >= meshoy){
    swal(
    'Periodo incorrecto',
    'Solo puede seleccionar para consulta meses anteriores al actual',
    'error'
    )
  }else {
    $(".caja-tabla").hide();
    $(".caja-spin").show();
    $.ajax({
      type:'post',
      data: {
        'action' : 'mostrarTablaRefRepMesSpe',
        'mes' : month,
        'year' : year,
        'pbl': cuenta
      },
      url: url_operep.ajax_operep_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        let tabla = $(".tab-referralreportemesspe").DataTable();
        console.log(resultado);
        let datos = resultado.data.tabla;

        if(datos === null){
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
          let totalcom = resultado.data.totalcom;
          let totalutlaft = resultado.data.totalutlaft;
          // console.log();

          $(".totalutlaft").html(totalutlaft);
          $(".totalcomm").html(totalcom);

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
