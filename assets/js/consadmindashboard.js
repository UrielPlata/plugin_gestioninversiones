$ = jQuery.noConflict();

( function( $ ) {

  $("#form-addconsretmas").on('submit', crearRetConMaster );
  $("#form-addconsdepmas").on('submit', crearDepConMaster );
  let userid = parseInt($(".tab-consdep").attr('data-user'));

  /*=============================================
  Data Table
  =============================================*/

  $(".tab-consdep").DataTable({
    pageLength : 25,
    lengthMenu: [[10, 12, 25, -1], [10, 12, 25, 'Todos']],
    order: [ 0, 'desc' ],
    "ajax": {
      url: url_solicitar_conservador.ajaxurl + '?action=mostrarTablaConHistoDep',
      type: 'POST',
      data: {
        "id": userid
      },
      cache:false,
    },
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
      "sInfo":           "Depósitos del _START_ al _END_ de un total de _TOTAL_",
      "sInfoEmpty":      "Depósitos del 0 al 0 de un total de 0",
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

  $(".tab-consret").DataTable({
    pageLength : 25,
    lengthMenu: [[10, 12, 25, -1], [10, 12, 25, 'Todos']],
    order: [ 0, 'desc' ],
    "ajax": {
      url: url_solicitar_conservador.ajaxurl + '?action=mostrarTablaConHistoRet',
      type: 'POST',
      data: {
        "id": userid
      },
      cache:false,
    },
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
      "sInfo":           "Retiros del _START_ al _END_ de un total de _TOTAL_",
      "sInfoEmpty":      "Retiros del 0 al 0 de un total de 0",
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

  // Click para ver la tabla de depositos del mes del inversionista
  // $(".tab-admagrconmaster").on('click','.btn-ver-depagr', function(e){
  //   var mes = $(this).attr('data-mes');
  //   var agno = $(this).attr('data-agno');
  //
  //   let protocol = window.location.protocol;
	// 	let url = "admin.php?page=crc_admin_verdepagrmes&m="+mes+"&y="+agno;
  //
  //   $(location).attr('href',url);
  // });
  //
  // $(".tab-admagrconmaster").on('click','.btn-ver-retagr', function(e){
  //   var mes = $(this).attr('data-mes');
  //   var agno = $(this).attr('data-agno');
  //
  //   let protocol = window.location.protocol;
	// 	let url = "admin.php?page=crc_admin_verretagrmes&m="+mes+"&y="+agno;
  //
  //   $(location).attr('href',url);
  // });
  //
  // $(".tab-admagrconmaster").on('click','.btn-ver-invagr', function(e){
  //   var mes = $(this).attr('data-mes');
  //   var agno = $(this).attr('data-agno');
  //
  //   let protocol = window.location.protocol;
	// 	let url = "admin.php?page=crc_admin_verinvagrmes&m="+mes+"&y="+agno;
  //
  //   $(location).attr('href',url);
  // });



}( jQuery ) );

function crearDepConMaster(e){

  e.preventDefault();
  $("#agregarconsdepmas").hide();
  let idinddepmas = $('#dmcon_idinddepmas').val();
  let idgraldepmas = $('#dmcon_idgraldepmas').val();
  let fechadepmas = $('#dmcon_fechadepmas').val();
  let fechadepmasform = fechadepmas.split("/").reverse().join("-");
  let fechafindepmas = $('#dmcon_fechafindepmas').val();
  let fechafindepmasform = '';
  if (fechafindepmas == '') {
    fechafindepmasform = '';
  }else {
    fechafindepmasform = fechafindepmas.split("/").reverse().join("-");
  }
  let cantdepmas = parseFloat($('#dmcon_cantidaddepmas').val());
  let cantfindepmas = parseFloat($('#dmcon_cantidadfindepmas').val());
  let notasdepmas = $('#dmcon_notasdepmas').val();
  let solicitante = $('#dmcon_solicitante').val();

  let userdep = $("#userdep");
  let user = $(userdep).val();

  // console.log(idinddepmas);
  // console.log(idgraldepmas);
  // console.log(fechadepmas);
  // console.log(cantdepmas);
  // console.log(cantfindepmas);
  // console.log(notasdepmas);
  /*console.log(balfinal);
  console.log(totalcuentas);
  console.log(notas);*/

  // console.log(cantidad);
  if(cantfindepmas <= 0 && solicitante == 0) {
    swal({
      title: 'Error',
      text: "La cantidad final tiene que ser mayor a 0",
      type: 'error',
    });
    return;
  }else if (cantdepmas <= 0) {
    swal({
      title: 'Error',
      text: "La cantidad tiene que ser mayor a 0",
      type: 'error',
    });
    return;
  }else{
    $.ajax({
      type:'post',
      data: {
        'action' : 'crear_opemaster_cons',
        'tipo' : 'deposito',
        'idmov_ind' : idinddepmas,
        'idmov_gral' : idgraldepmas,
        'fecha_deposito' : fechadepmasform,
        'fecha_findeposito' : fechafindepmasform,
        'cantidad' : cantdepmas,
        'cantidad_real' : cantfindepmas,
        'notas' : notasdepmas,
        'usuario' : user
      },
      url: url_solicitar_conservador.ajaxurl,
      success: function(data) {
        var resultado = JSON.parse(data);
        if(resultado.respuesta==1){
          $("#modal-addconsdepmas").modal('toggle');
          $("#dmagr_cantidaddepmas").val('0.00');
          $("#dmagr_cantidadfindepmas").val('0.00');
          $('#dmagr_notasdepmas').val("");
          $(".tab-consdep").DataTable().ajax.reload();
          // $(".tab-admconconmaster").DataTable().ajax.reload();
            swal(
            'Depósito registrado',
            'Se ha registrado correctamente el depósito a la cuenta maestra.',
            'success'
          );
        }else{
          swal(
            'Ocurrió un error',
            'Se ha producido un error inesperado al tratar de registrar el depósito. Por favor intente nuevamente más tarde',
            'error'
          );
        }
        $("#agregarconsdepmas").show();
      }
    });

  }

}

function crearRetConMaster(e) {
  e.preventDefault();
  $("#agregarconsretmas").hide();
  let idindretmas = $('#rmcon_idindretmas').val();
  let fecharetmas = $('#rmcon_fecharetmas').val();
  let fecharetmasform = fecharetmas.split("/").reverse().join("-");
  let fechafinretmas = $('#rmcon_fechafinretmas').val();
  let fechafinretmasform = '';
  if (fechafinretmas == '') {
    fechafinretmasform = '';
  }else {
    fechafinretmasform = fechafinretmas.split("/").reverse().join("-");
  }
  // let cantretmas = parseFloat($('#cantidadretmas').val());
  let cantfinretmas = parseFloat($('#rmcon_cantidadfinretmas').val());
  let notasretmas = $('#rmcon_notasretmas').val();
  let solicitante = $('#rmcon_solicitante').val();

  let userret = $("#userret");
  let user = $(userret).val();
  let totaldisp = parseFloat($("#totaldisp").val());

  /*
  console.log(idinddepmas);
  console.log(idgraldepmas);
  console.log(fechadepmas);
  console.log(cantdepmas);
  console.log(cantfindepmas);
  console.log(notasdepmas);
  /*console.log(balfinal);
  console.log(totalcuentas);
  console.log(notas);*/

  // console.log(cantidad);
  if(cantfinretmas > totaldisp){
    swal({
      title: 'Error',
      text: "La cantidad a retirar solicitada excede el total disponible del mes cerrado inmediato anterior",
      type: 'error',
    });
    return;
  }else if(cantfinretmas <= 0) {
    swal({
      title: 'Error',
      text: "La cantidad final tiene que ser mayor a 0",
      type: 'error',
    });
    return;
  }else{
    $.ajax({
      type:'post',
      data: {
        'action' : 'crear_opemaster_cons',
        'tipo' : 'retiro',
        'idmov_ind' : idindretmas,
        'fecha_retiro' : fecharetmasform,
        'fecha_finretiro' : fechafinretmasform,
        'cantidad' : cantfinretmas,
        'cantidad_real' : cantfinretmas,
        'notas' : notasretmas,
        'solicitante' : solicitante,
        'usuario' : user
      },
      url: url_solicitar_conservador.ajaxurl,
      success: function(data) {
        var resultado = JSON.parse(data);
        if(resultado.respuesta==1){
          $("#modal-addconsretmas").modal('toggle');
          $('#rmcon_cantidadfinretmas').val("0.00");
          $('#rmcon_notasdepmas').val("");
          $(".tab-consret").DataTable().ajax.reload();
          // $(".tab-admconconmaster").DataTable().ajax.reload();
            swal(
            'Retiro registrado',
            'Se ha registrado correctamente el retiro a la cuenta maestra.',
            'success'
          );
        }else{
          swal(
            'Ocurrió un error',
            'Se ha producido un error inesperado al tratar de registrar el retiro. Por favor intente nuevamente más tarde',
            'error'
          );
        }
        $("#agregarconsretmas").show();
      }
    });

  }

}

function agregarRegistroAgr(e){

  e.preventDefault();

  let mes = parseInt($('#reagr_mes').val());
  let year = parseInt($('#reagr_year').val());
  let utilmes = parseFloat($('#reagr_util_mes').val());
  let combro = parseFloat($('#reagr_com_bro').val());
  let por_inver = parseFloat($('#reagr_por_inver').val());
  let por_refer = parseFloat($('#reagr_por_refer').val());
  let fecharegistro = $("#reagr_fecha_control").val();
  let fecharegistroform = fecharegistro.split("/").reverse().join("-");
  let notas = $('#reagr_notas').val();

  // console.log(year);
  // console.log(mes);
  // console.log(cid);

  if(year < 2000) {
    swal({
      title: 'Error',
      text: "El año tiene que ser posterior a 1999",
      type: 'error',
    });
    return;
  }else{
    $.ajax({
    type:'post',
    data: {
      'action' : 'traer_datos_registro_agr',
      'mes' : mes,
      'year' : year,
      'tipo' : 'noDuplicarRegistro'
    },
    url: url_solicitar_agresivo.ajaxurl,
    success: function(data) {
      var resultado1 = JSON.parse(data);
        console.log(resultado1);

        if(resultado1.data.length != 0){

          swal({
            title: 'Error',
            text: "Ya existe un registro para el mes y año seleccionado en esta cuenta.",
            type: 'error',
          });

        }else{
          $.ajax({
              type:'post',
              data: {
                'action' : 'referral_agregarregistro_agr',
                'mes' : mes,
                'year' : year,
                'utilmes' : utilmes,
                'combro' : combro,
                'por_inver' : por_inver,
                'por_refer' : por_refer,
                'fecha' : fecharegistroform,
                'notas' : notas
              },
              url: url_solicitar_agresivo.ajaxurl,
              success: function(data) {
                var resultado = JSON.parse(data);
                if(resultado.respuesta==1){
                  $("#modal-addregagr").modal('toggle');
                  // $(".tab-admagrconmaster").DataTable().ajax.reload();
                  // $('#rbl_cid').val("");
                  $('#reagr_util_mes').val(0.00);
                  $('#reagr_com_bro').val(0.00);
                  $('#reagr_por_inver').val(0.00);
                  $('#reagr_por_refer').val(0.00);
                  $("#reagr_notas").val('');
                    swal(
                    'Registro creado',
                    'Se ha creado correctamente el registro mensual de los inversionistas. Actualizando ...',
                    'success'
                  )
                  setTimeout(function(){
                    location.reload();
                  },3000);
                }else{
                  swal(
                  'Ocurrió un error',
                  'Se ha producido un error inesperado al tratar de crear el registro mensual. Por favor intente nuevamente más tarde',
                  'error'
                  )
                }

              }
            });
        }
      }
    });
  }
}
