$ = jQuery.noConflict();
//
// Tabs Toggler
//



( function( $ ) {

  /*=============================================
  INPUT MASK
  =============================================*/

  $('[data-mask]').inputmask();

  const cantidadret = $("#cantidadret");
  const urgenteret = $("#urgenteret");
  const furgenteret = $("#furgenteret");
  const eurgenteret = $("#eurgenteret");
  const fecharet = $("#fecharet");
  const userret = $("#userret");

  const cantidaddep = $("#cantidaddep");
  const fechadep = $("#fechadep");
  const userdep = $("#userdep");

  const efechafin = $("#efechafin");
  const userstatus = $("#status");

  //$(cantidadret).on('input', no_letras);
  $(urgenteret).on('change', validarEsUrgente);
  $(furgenteret).on('change', validarEsFUrgente);
  $(eurgenteret).on('change', validarEsEUrgente);
  $(userstatus).on('change', () => {

    let valstatus = $("#status").attr('data-status');
    console.log(valstatus);
    swal({
      title: 'Aviso importante',
      text: "El cambio de status seleccionado y sus notas solo aplicarán del mes actual del inversionista al último mes. Los meses anteriores no modificarán automaticamente su status y notas por seguridad.",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Entendido',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if(result.value){

      }else{
        $(userstatus).val(valstatus);
      }
    })
  });


	//$("#mid_mascota_celular_dueno").on('input', no_letras );

	function no_letras(evt){
		// Allow only numbers.
		//jQuery(this).val(jQuery(this).val().replace(/[^0-9]/g, ''));
    //console.log(jQuery(this).val(jQuery(this).val()));
	}

  //$("#cantidad").on('input', no_letras_form );

  $("#form-solicitaret").on('submit', validarRetiro );
  $("#form-solicitadep").on('submit', validarDeposito );

  $("#balbefcom").on('change', totalNBalFinal );
  $("#comtrader").on('change', totalNBalFinal );
  $("#ebalbefcom").on('change', totalEBalFinal );
  $("#ecomtrader").on('change', totalEBalFinal );

  $(".tab-usersadm").on('click','.btn-proyeccion', function(e){
    var id = $(this).attr('data-usuario');

    let protocol = window.location.protocol;
		let url = "admin.php?page=crc_admin_userdashboard&id="+id;

    $(location).attr('href',url);
  });

  // Click para ver la tabla de depositos del mes del inversionista
  $(".tab-adminproyecinv").on('click','.btn-ver-dep', function(e){
    var id = $(this).attr('data-userid');
    var mes = $(this).attr('data-mes');
    var agno = $(this).attr('data-agno');

    let protocol = window.location.protocol;
		let url = "admin.php?page=crc_admin_verdepmes&id="+id+"&m="+mes+"&p="+agno;

    $(location).attr('href',url);
  });
  $(".tab-userproyecinv").on('click','.btn-ver-dep', function(e){
    var id = $(this).attr('data-userid');
    var mes = $(this).attr('data-mes');
    var agno = $(this).attr('data-agno');

    let protocol = window.location.protocol;
		let url = "admin.php?page=crc_verdepmes&m="+mes+"&p="+agno;

    $(location).attr('href',url);
  });

  // Click para ver la tabla de retiros del mes del inversionista
  $(".tab-adminproyecinv").on('click','.btn-ver-ret', function(e){
    var id = $(this).attr('data-userid');
    var mes = $(this).attr('data-mes');
    var agno = $(this).attr('data-agno');

    let protocol = window.location.protocol;
		let url = "admin.php?page=crc_admin_verretmes&id="+id+"&m="+mes+"&p="+agno;

    $(location).attr('href',url);
  });
  $(".tab-userproyecinv").on('click','.btn-ver-ret', function(e){
    var id = $(this).attr('data-userid');
    var mes = $(this).attr('data-mes');
    var agno = $(this).attr('data-agno');

    let protocol = window.location.protocol;
		let url = "admin.php?page=crc_verretmes&m="+mes+"&p="+agno;

    $(location).attr('href',url);
  });

  // Click para ver la tabla de retiros y depositos del mes del control Maestro
  $(".tab-admconmaster").on('click','.btn-ver-depmas', function(e){

    var mes = $(this).attr('data-mes');
    var agno = $(this).attr('data-agno');

    let protocol = window.location.protocol;
		let url = "admin.php?page=crc_admin_verdepmasmes&m="+mes+"&p="+agno;

    $(location).attr('href',url);
  });
  $(".tab-admconmaster").on('click','.btn-ver-retmas', function(e){

    var mes = $(this).attr('data-mes');
    var agno = $(this).attr('data-agno');

    let protocol = window.location.protocol;
		let url = "admin.php?page=crc_admin_verretmasmes&m="+mes+"&p="+agno;

    $(location).attr('href',url);
  });

  $('.tab-adminretiros').on('mouseenter','.microtexto', function(e){
    let columna = e.target;
    let texto = $(columna).attr('aria-label').replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
    $(columna).attr('aria-label',texto);
    console.log(texto);
  });
  $('.tab-invmesretiros').on('mouseenter','.microtexto', function(e){
    let columna = e.target;
    let texto = $(columna).attr('aria-label').replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
    $(columna).attr('aria-label',texto);
  });

  //Tab id thickbox
  $('.tab-adminretiros').on('click','.btn-finalizar', function(e){

    var id = $(this).attr('data-retiro');

      $.ajax({
      type:'post',
      data: {
        'action' : 'traer_datos',
        'id' : id,
        'tipo' : 'retiro'
      },
      url: url_opefin.ajax_opefin_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        console.log(resultado);
        if(resultado.data.length != 0){

          let datos = resultado.data[0];
          var cantini = datos.cantidad;
          var datesol = datos.fecha;
          let urgente = datos.urgente;
          let fecha_cuando = datos.fecha_cuando;
          var efechasol = datesol.split("/").reverse().join("-");

          $("#idret").attr('value', id);
          $("#cantidadini").attr('value', cantini);
          $("#cantidadfin").val(cantini);
          if (urgente == "1") {
            $("#furgenteret").prop('checked',true);
            $("#fecharet").val("0");
            $("#fecharet").prop('disabled',true);
          }else{
            $("#furgenteret").prop('checked',false);
            $("#fecharet").val(fecha_cuando);
            $("#fecharet").prop('disabled',false);
          }
          $("#fechasol").val(efechasol);
          $("#notas").val("");

        }else{
          tb_remove();
          swal({
            title: 'Error',
            text: "No se pudieron cargar los datos",
            type: 'error',
          });
        }
      }
    });
  });

  $('.tab-adminretiros').on('click','.btn-editar', function(e){

    var id = $(this).attr('data-retiro');

      $.ajax({
      type:'post',
      data: {
        'action' : 'traer_datos',
        'id' : id,
        'tipo' : 'retiro'
      },
      url: url_opefin.ajax_opefin_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        console.log(resultado);
        if(resultado.data.length != 0){

          let datos = resultado.data[0];
          var idmovind = datos.idmovind;
          var date = datos.fechafin;
          var datesol = datos.fecha;
          let urgente = datos.urgente;
          let fecha_cuando = datos.fecha_cuando;
          var efechafin = date.split("/").reverse().join("-");
          var efechasol = datesol.split("/").reverse().join("-");
          var cantfin = parseFloat(datos.cantidadfin);
          var cantini = datos.cantidad;
          var notas = datos.notas;


          $("#ideret").attr('value', id);
          $("#eidmovind").val(idmovind);
          $("#ecantidadfin").val(cantfin);
          $("#ecantidadini").attr('value', cantini);
          if (urgente == "1") {
            $("#eurgenteret").prop('checked',true);
            $("#efecharet").val("0");
            $("#efecharet").prop('disabled',true);
          }else{
            $("#eurgenteret").prop('checked',false);
            $("#efecharet").val(fecha_cuando);
            $("#efecharet").prop('disabled',false);
          }
          $("#efechafin").val(efechafin);
          $("#efechasol").val(efechasol);
          if(notas == null){
            $("#enotas").val('');
          }else{
            let notasf = notas.replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
            $("#enotas").val(notasf);
          }
        }else{
          tb_remove();
          swal({
            title: 'Error',
            text: "No se pudieron cargar los datos",
            type: 'error',
          });
        }
      }
    });



  });

  $('.tab-adminretiros').on('click','.btn-cancelar', function(e){

    var id = $(this).attr('data-retiro');

      $.ajax({
      type:'post',
      data: {
        'action' : 'traer_datos',
        'id' : id,
        'tipo' : 'retiro'
      },
      url: url_opefin.ajax_opefin_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        console.log(resultado);
        if(resultado.data.length != 0){

          let datos = resultado.data[0];
          var datesol = datos.fecha;
          var efechasol = datesol.split("/").reverse().join("-");
          var cantini = datos.cantidad;
          var notas = datos.notas;


          $("#idcret").attr('value', id);
          $("#ccantidadini").attr('value', cantini);
          $("#cfechasol").val(efechasol);
          if(notas == null){
            $("#cnotas").val('');
          }else{
            let notasf = notas.replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
            $("#cnotas").val(notasf);
          }
        }else{
          tb_remove();
          swal({
            title: 'Error',
            text: "No se pudieron cargar los datos",
            type: 'error',
          });
        }
      }
    });



  });

  $('.tab-admindepositos').on('mouseenter','.microtexto', function(e){
    let columna = e.target;
    let texto = $(columna).attr('aria-label').replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
    $(columna).attr('aria-label',texto);
  });

  $('.tab-invmesdepositos').on('mouseenter','.microtexto', function(e){
    let columna = e.target;
    let texto = $(columna).attr('aria-label').replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
    $(columna).attr('aria-label',texto);
  });

  //Colocar bien el microtexto en mesesinv
  $('.tab-mesesinv').on('mouseenter','.microtexto', function(e){
    let columna = e.target;
    let texto = $(columna).attr('aria-label').replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
    $(columna).attr('aria-label',texto);
  });
  //Tab id thickbox
  $('.tab-admindepositos').on('click','.btn-finalizar', function(e){

    var id = $(this).attr('data-deposito');

      $.ajax({
      type:'post',
      data: {
        'action' : 'traer_datos',
        'id' : id,
        'tipo' : 'deposito'
      },
      url: url_opefin.ajax_opefin_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        console.log(resultado);
        if(resultado.data.length != 0){

          let datos = resultado.data[0];
          var cantini = datos.cantidad;
          let fecha_cuando = datos.fecha_cuando;
          var datesol = datos.fecha;
          var efechasol = datesol.split("/").reverse().join("-");


          $("#iddep").attr('value', id);
          $("#cantidadini").attr('value', cantini);
          $("#fechadep").val(fecha_cuando);
          $("#fechasol").val(efechasol);
          $("#notas").val("");

        }else{
          tb_remove();
          swal({
            title: 'Error',
            text: "No se pudieron cargar los datos",
            type: 'error',
          });
        }
      }
    });
  });

  $('.tab-admindepositos').on('click','.btn-editar', function(e){

    var id = $(this).attr('data-deposito');

      $.ajax({
      type:'post',
      data: {
        'action' : 'traer_datos',
        'id' : id,
        'tipo' : 'deposito'
      },
      url: url_opefin.ajax_opefin_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        console.log(resultado);
        if(resultado.data.length != 0){

          let datos = resultado.data[0];
          var idmovind = datos.idmovind;
          var idmovgral = datos.idmovgral;
          var date = datos.fechafin;
          var datesol = datos.fecha;
          let fecha_cuando = datos.fecha_cuando;
          var efechadep = datos.fechadep;
          var efechafin = date.split("/").reverse().join("-");
          var efechasol = datesol.split("/").reverse().join("-");
          var cantfin = parseFloat(datos.cantidadfin);
          var cantini = datos.cantidad;
          var notas = datos.notas;


          $("#idedep").attr('value', id);
          $("#eidmovind").val(idmovind);
          $("#eidmovgral").val(idmovgral);
          $("#ecantidadfin").val(cantfin);
          $("#ecantidadini").attr('value', cantini);
          $("#efechadep").val(fecha_cuando);

          $("#efechafin").val(efechafin);
          $("#efechasol").val(efechasol);
          if(notas == null){
            $("#enotas").val('');
          }else{
            let notasf = notas.replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
            $("#enotas").val(notasf);
          }

        }else{
          tb_remove();
          swal({
            title: 'Error',
            text: "No se pudieron cargar los datos",
            type: 'error',
          });
        }
      }
    });



  });

  $('.tab-admindepositos').on('click','.btn-cancelar', function(e){

    var id = $(this).attr('data-deposito');

      $.ajax({
      type:'post',
      data: {
        'action' : 'traer_datos',
        'id' : id,
        'tipo' : 'deposito'
      },
      url: url_opefin.ajax_opefin_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        console.log(resultado);
        if(resultado.data.length != 0){

          let datos = resultado.data[0];
          var idmovind = datos.idmovind;
          var idmovgral = datos.idmovgral;
          var date = datos.fechafin;
          var datesol = datos.fecha;
          var efechadep = datos.fechadep;
          var efechasol = datesol.split("/").reverse().join("-");
          var cantini = datos.cantidad;
          var notas = datos.notas;


          $("#idcdep").attr('value', id);
          $("#ccantidadini").attr('value', cantini);
          $("#cfechadep").val(efechadep);


          $("#cfechasol").val(efechasol);
          if(notas == null){
            $("#cnotas").val('');
          }else{
            let notasf = notas.replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
            $("#cnotas").val(notasf);
          }

        }else{
          tb_remove();
          swal({
            title: 'Error',
            text: "No se pudieron cargar los datos",
            type: 'error',
          });
        }
      }
    });



  });

  $('.tab-mesesinv').on('click','.btn-editmes', function(e){
    var interes = $(this).attr('data-interes');
    var id = $(this).attr('data-mes');
    var notascol = $(this).parent().parent().find('.microtexto');
    let notas = $(notascol).attr('aria-label').replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
    $('#idmes').val(id);
    $('#intmes').val(interes);
    $('#menotas').val(notas);
  });

  $('.tab-constatus').on('click','.btn-editconstatus', function(e){
    var id = $(this).attr('data-id');
    var porcentaje = $(this).attr('data-porcentaje');
    var mes = parseInt($(this).attr('data-mes'));
    var year = parseInt($(this).attr('data-year'));
    var tipo = parseInt($(this).attr('data-tipo'));
    var notascol = $(this).parent().parent().find('.microtexto');
    let notas = $(notascol).attr('aria-label').replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
    $('#idconstatus').val(id);
    $('#statusmes').val(mes);
    $('#statusyear').val(year);
    if(tipo == 1){
      $('#eespsta').attr("checked", true);
      $('#eregsta').attr("checked", false);
      $('#statusporcentaje').attr("disabled", false);
      $('#statusporcentaje').val(porcentaje);
    }else{
      $('#eregsta').attr("checked", true);
      $('#eespsta').attr("checked", false);
      $('#statusporcentaje').attr("disabled", true);
      $('#statusporcentaje').val("0.00");
    }
    $('#statusnotas').val(notas);
  });

  $('.tab-admconmaster').on('click','.btn-editar', function(e){

    var id = $(this).attr('data-balance');

      $.ajax({
      type:'post',
      data: {
        'action' : 'traer_datosbal',
        'id' : id,
        'tipo' : 'editbalance'
      },
      url: url_opefin.ajax_opefin_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        console.log(resultado);
        if(resultado.data.length != 0){

          let datos = resultado.data[0];
          var mes = parseInt(datos.mes);
          var agno = parseInt(datos.agno);
          var startbal = parseFloat(datos.startbal);
          var balbefcom = parseFloat(datos.balbefcom);
          let comtrader = parseFloat(datos.comtrader);
          var combroker = parseFloat(datos.combroker);
          var balfinal = parseFloat(datos.balfinal);
          var totalcuentas = parseFloat(datos.totalcuentas);
          let retmas = parseFloat(datos.retmes);
          var depmas = parseFloat(datos.depmes);
          var notas = datos.notas;


          $("#idebal").attr('value', id);
          $("#emes").val(mes);
          $("#eagno").val(agno);
          $('#estartbal').val(startbal);
          $('#eretmes').val(retmas);
          $('#edepmes').val(depmas);
          $('#ebalbefcom').val(balbefcom);
          $('#ecomtrader').val(comtrader);
          $('#ecombroker').val(combroker);
          $('#ebalfinal').val(balfinal);
          $('#etotalcuentas').val(totalcuentas);

          if(notas == null){
            $("#enotas").val('');
          }else{
            let notasf = notas.replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
            $("#enotas").val(notasf);
          }

        }else{
          tb_remove();
          swal({
            title: 'Error',
            text: "No se pudieron cargar los datos",
            type: 'error',
          });
        }
      }
    });



  });

  $('.tab-admconmaster').on('click','.btn-elimbal', function(e){

    var id = $(this).attr('data-balance');

    swal({
      title: 'Confirmar',
      text: "¿Está seguro que desea eliminar este balance?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Eliminar',
      cancelButtonText: 'No'
    }).then((result) => {
      if(result.value){
          $.ajax({
          type:'post',
          data: {
            'action' : 'operacion_elimbal',
            'id' : id
          },
          url: url_opefin.ajax_opefin_url,
          success: function(data) {
            var resultado = JSON.parse(data);
            console.log(resultado);
            if(resultado.respuesta==1){
                swal(
                'Balance eliminado',
                'El balance se ha eliminado correctamente.',
                'success'
              );
              setTimeout(function(){
                location.reload();
              },3000);
            }else{
              swal(
              'Ocurrió un error',
              'Se ha producido un error inesperado al tratar de eliminar el balance. Por favor intente nuevamente más tarde',
              'error'
              )
            }
          }
        });
      }
    })

  });
  //Colocar bien el microtexto en admconmaster
  $('.tab-admconmaster').on('mouseenter','.microtexto', function(e){
    let columna = e.target;
    let texto = $(columna).attr('aria-label').replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
    $(columna).attr('aria-label',texto);
  });

  $('.tab-rephistgral').on('click','.btn-editar', function(e){

    let year = parseInt($(this).attr('data-year'));
    let mes = parseInt($(this).attr('data-mes'));

    $("#eryear").val(year);
    $("#ermes").val(mes);
    $("#erext").val('0')
    $('#ernotas').val('');

  });
  //Colocar bien el microtexto en rephistgral
  $('.tab-rephistgral').on('mouseenter','.microtexto', function(e){
    let columna = e.target;
    let texto = $(columna).attr('aria-label').replace(/(\\')/g, '\'').replace(/(\\")/g, '\"');
    $(columna).attr('aria-label',texto);
  });


  //depositos Master
  $('.tab-admindepmas').on('click','.btn-editar', function(e){

    var id = $(this).attr('data-deposito');

      $.ajax({
      type:'post',
      data: {
        'action' : 'traer_datos_opemaster',
        'id' : id,
        'tipo' : 'deposito'
      },
      url: url_opefin.ajax_opefin_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        console.log(resultado);
        if(resultado.data.length != 0){

          let datos = resultado.data[0];
          var cant = datos.cantidad;
          var cantfin = datos.cantidadfin;
          let fechadep = datos.fechadep;
          let idmovind = datos.idmov_ind;
          let idmovgral = datos.idmov_gral;
          var efechadep = fechadep.split("/").reverse().join("-");
          var notas = datos.notas;


          $("#idedep").attr('value', id);
          $("#ecantidaddepmas").val(cant);
          $("#ecantidadfindepmas").val(cantfin);
          $("#eidinddepmas").val(idmovind);
          $("#eidgraldepmas").val(idmovgral);
          $("#efechadepmas").val(efechadep);
          $("#enotasdepmas").val(notas);

        }else{
          tb_remove();
          swal({
            title: 'Error',
            text: "No se pudieron cargar los datos",
            type: 'error',
          });
        }
      }
    });
  });

  $('.tab-admindepmas').on('click','.btn-elimdepmas', function(e){

    var id = $(this).attr('data-deposito');

    swal({
      title: 'Confirmar',
      text: "¿Esta seguro que desea cancelar este depósito? Esta acción no se puede deshacer",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Cancelar'
    }).then((result) => {
      if(result.value){
        $.ajax({
          type:'post',
          data: {
            'action' : 'cancelar_opemaster',
            'tipo' : 'deposito',
            'id':id
          },
          url: url_opefin.ajax_opefin_url,
          success: function(data) {
            var resultado = JSON.parse(data);
            if(resultado.respuesta==1){
              tb_remove();
              $(".tab-admindepmas").DataTable().ajax.reload();
                swal(
                'Depósito cancelado',
                'Se ha cancelado correctamente el depósito a la cuenta maestra.',
                'success'
              );

            }else{
              swal(
                'Ocurrió un error',
                'Se ha producido un error inesperado al tratar de cancelar el depósito. Por favor intente nuevamente más tarde',
                'error'
              );
            }

          }
        });
      }
    });
  });
  //retiros Master
  $('.tab-adminretmas').on('click','.btn-editar', function(e){

    var id = $(this).attr('data-retiro');

      $.ajax({
      type:'post',
      data: {
        'action' : 'traer_datos_opemaster',
        'id' : id,
        'tipo' : 'retiro'
      },
      url: url_opefin.ajax_opefin_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        console.log(resultado);
        if(resultado.data.length != 0){

          let datos = resultado.data[0];
          var cant = datos.cantidad;
          var cantfin = datos.cantidadfin;
          let fecharet = datos.fecharet;
          let idmovind = datos.idmov_ind;
          var efecharet = fecharet.split("/").reverse().join("-");
          var notas = datos.notas;


          $("#ideret").attr('value', id);
          // $("#ecantidadretmas").val(cant);
          $("#ecantidadfinretmas").val(cantfin);
          $("#eidindretmas").val(idmovind);
          $("#efecharetmas").val(efecharet);
          $("#enotasretmas").val(notas);

        }else{
          tb_remove();
          swal({
            title: 'Error',
            text: "No se pudieron cargar los datos",
            type: 'error',
          });
        }
      }
    });
  });

  $('.tab-adminretmas').on('click','.btn-elimretmas', function(e){

    var id = $(this).attr('data-retiro');

    swal({
      title: 'Confirmar',
      text: "¿Esta seguro que desea cancelar este retiro? Esta acción no se puede deshacer",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Cancelar'
    }).then((result) => {
      if(result.value){
        $.ajax({
          type:'post',
          data: {
            'action' : 'cancelar_opemaster',
            'tipo' : 'retiro',
            'id':id
          },
          url: url_opefin.ajax_opefin_url,
          success: function(data) {
            var resultado = JSON.parse(data);
            if(resultado.respuesta==1){
              tb_remove();
              $(".tab-adminretmas").DataTable().ajax.reload();
                swal(
                'Retiro cancelado',
                'Se ha cancelado correctamente el retiro a la cuenta maestra.',
                'success'
              );

            }else{
              swal(
                'Ocurrió un error',
                'Se ha producido un error inesperado al tratar de cancelar el retiro. Por favor intente nuevamente más tarde',
                'error'
              );
            }

          }
        });
      }
    });
  });

  $('#form-finret').on('submit', finalizarRetiro );
  $('#form-editret').on('submit', editarRetiro );
  $('#form-cancret').on('submit', cancelarRetiro );
  $('#form-findep').on('submit', finalizarDeposito );
  $('#form-editdep').on('submit', editarDeposito );
  $('#form-cancdep').on('submit', cancelarDeposito );
  $('#form-editmes').on('submit', editarMes );
  $('#form-newbalance').on('submit', crearBalance );
  $('#form-editbalance').on('submit', editarBalance );
  $('#form-editreg').on('submit', editarRegHistorico );
  $('.button-newbalance').on('click', cargarDatosBal );
  $('#form-depmaster').on('submit', crearDepMaster );
  $('#form-retmaster').on('submit', crearRetMaster );
  $('#form-edepmaster').on('submit', editarDepMaster );
  $('#form-eretmaster').on('submit', editarRetMaster );
  $('#crearmeses').on('click', agregarMeses);
  $('#crearconstatus').on('click', agregarConStatus);
  $('.cambio-cuenta').on('change',cambiarInputs );
  $('.cambio-cuenta-e').on('change',cambiarInputsEdit );
  $('#form-editconstatus').on('submit', editarStatus );

}( jQuery ) );

function no_letras_form(evt){
  // Allow only numbers.
  jQuery(this).val(jQuery(this).val().replace(/[^0-9]/g, ''));
}



function validarRetiro(evento) {
  evento.preventDefault();
  let cantidad = parseFloat($(cantidadret).val());
  let totaldisp = parseFloat($("#totaldisp").val());
  let fecha_cuando = $(fecharet).val();
  let user = $(userret).val();
  let urgente = $(urgenteret).is(':checked');


  // console.log(cantidad);
  if(cantidad == 0) {
    swal({
      title: 'Error',
      text: "La cantidad a retirar debe ser mayor a 0",
      type: 'error',
    });
    return;
  }else if( cantidad > totaldisp){
    swal({
      title: 'Error',
      text: "La cantidad a retirar solicitada excede el total disponible del mes cerrado inmediato anterior",
      type: 'error',
    });
    return;
  }else{
    if (fecha_cuando == '' && urgente == false) {
      swal({
        title: 'Error',
        text: "Debe seleccionar una fecha de retiro",
        type: 'error',
      });
      return;
    }else{

      if(urgente == false){
        urgente = 'no';
      }else{
        urgente = 'si';
      }

      $.ajax({
        type:'post',
        data: {
          'action' : 'solicitar_retiro',
          'cantidad' : cantidad,
          'user' : user,
          'urgente' : urgente,
          'fecha_cuando' : fecha_cuando
        },
        url: url_solicitar_retiro.ajaxurl,
        success: function(data) {
          var resultado = JSON.parse(data);
          console.log(resultado);
          if(resultado.respuesta==1){
            tb_remove();
            $(cantidadret).val('0.00');
            $(fecharet).val('');
            $(urgenteret).prop('checked',false);
            $(fecharet).prop('disabled',false);
              swal(
              'Solicitud generada',
              'Se ha enviado una liga de confirmación de solicitud a su correo. En caso de no encontrar el correo revise su bandeja de spam.',
              'success'
            )
          }else{
            swal(
            'Ocurrió un error',
            'Se ha producido un error inesperado al tratar de solicitar el retiro. Por favor intente nuevamente más tarde',
            'error'
            )
          }
        }
      });
    }
  }

}

function validarDeposito(evento) {
  evento.preventDefault();
  let cantidad = parseFloat($(cantidaddep).val());
  let fecha_cuando = $(fechadep).val();
  let user = $(userdep).val();
  let interes = $(userint).val();


  // console.log(cantidad);
  if(cantidad == 0) {
    swal({
      title: 'Error',
      text: "La cantidad a depositar debe ser mayor a 0",
      type: 'error',
    });
    return;
  }else{
    if (fecha_cuando == '') {
      swal({
        title: 'Error',
        text: "Debe seleccionar una fecha de depósito",
        type: 'error',
      });
      return;
    }else{
      $.ajax({
        type:'post',
        data: {
          'action' : 'solicitar_deposito',
          'cantidad' : cantidad,
          'user' : user,
          'interes' : interes,
          'fecha_cuando' : fecha_cuando
        },
        url: url_solicitar_retiro.ajaxurl,
        success: function(data) {
          var resultado = JSON.parse(data);
          console.log(resultado);
          if(resultado.respuesta==1){
            tb_remove();
            $(cantidaddep).val('0.00');
            $(fechadep).val('');
              swal(
              'Solicitud generada',
              'Se ha enviado una liga de confirmación de solicitud a su correo. En caso de no encontrar el correo revise su bandeja de spam.',
              'success'
            )
          }else{
            swal(
            'Ocurrió un error',
            'Se ha producido un error inesperado al tratar de solicitar el depósito. Por favor intente nuevamente más tarde',
            'error'
          )
          }
        }
      });
    }
  }

}

function validarEsUrgente(e){
  if($(urgenteret).is(':checked')){
    $(fecharet).prop('disabled',true);
    //console.log("Si");
    swal({
      title: 'Aviso importante',
      text: "Se te pagarán tus utilidades generadas hasta el cierre programado mas cercano (Ejemplo: Si cobras un día 19 tu rendimiento se te pagará hasta el día de corte mas cercano (última quincena pasada).",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, Acepto',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if(result.value){
      //this.submit();
      //console.log("Enviado");
        $(fecharet).val('');
      }else{
        $(urgenteret).prop('checked',false);
        $(fecharet).prop('disabled',false);
      }
    })
  }else{
    $(fecharet).prop('disabled',false);
  }
}

function validarEsFUrgente(e){
  if($(furgenteret).is(':checked')){
    $("#fecharet").prop('disabled',true);
    //console.log("Si");
    swal({
      title: 'Aviso importante',
      text: "Se te pagarán tus utilidades generadas hasta el cierre programado mas cercano (Ejemplo: Si cobras un día 19 tu rendimiento se te pagará hasta el día de corte mas cercano (última quincena pasada).",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, Acepto',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if(result.value){
      //this.submit();
      //console.log("Enviado");
        $("#fecharet").val('0');
      }else{
        $(furgenteret).prop('checked',false);
        $("#fecharet").prop('disabled',false);
      }
    })
  }else{
    $("#fecharet").prop('disabled',false);
  }
}

function validarEsEUrgente(e){
  if($(eurgenteret).is(':checked')){
    $("#efecharet").prop('disabled',true);
    //console.log("Si");
    swal({
      title: 'Aviso importante',
      text: "Se te pagarán tus utilidades generadas hasta el cierre programado mas cercano (Ejemplo: Si cobras un día 19 tu rendimiento se te pagará hasta el día de corte mas cercano (última quincena pasada).",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, Acepto',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if(result.value){
      //this.submit();
      //console.log("Enviado");
        $("#efecharet").val('0');
      }else{
        $(eurgenteret).prop('checked',false);
        $("#efecharet").prop('disabled',false);
      }
    })
  }else{
    $("#efecharet").prop('disabled',false);
  }
}

function avisoCambioStatus(e){
  let usersta = $('#status').attr("selected",true);
  let valstatus = $(usersta).val();
  console.log(valstatus);
  swal({
    title: 'Aviso importante',
    text: "El cambio de status seleccionado solo aplicará del mes actual del inversionista al último mes. Los meses anteriores no modificarán automaticamente su status por seguridad.",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Entendido',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if(result.value){

    }else{
      $(usersta).val(valstatus);
    }
  });
}

function finalizarRetiro(e){

  e.preventDefault();

  let id = $("#idret").val();
  let idmovind = $("#idmovind").val();
  let cantidadfin = parseFloat($("#cantidadfin").val());
  let urgente = $("#furgenteret").is(':checked');
  let urgenteval = 0;
  if (urgente) {
    urgenteval = 1;
  }
  let fecha_cuando = $("#fecharet").val();
  let fechasol = $("#fechasol").val();
  let fechasolform = fechasol.split("/").reverse().join("-");
  let fechafin = $("#fechafin").val();
  let fechafinform = fechafin.split("/").reverse().join("-");
  let notas = $("#notas").val();

  // console.log(cantidad);
  if(cantidadfin == 0) {
    swal({
      title: 'Error',
      text: "La cantidad final del retiro debe ser mayor a 0",
      type: 'error',
    });
    return;
  } else if(fecha_cuando == 0 && urgente == false ) {
    swal({
      title: 'Error',
      text: "Debe seleccionar una fecha de retiro",
      type: 'error',
    });
    return;
  }else{
    swal({
      title: 'Confirmar',
      text: "Al autorizar el retiro, este pasará a tener un status Autorizado y ser contabilizado en el historial del inversionista. La acción no se puede deshacer",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Autorizar'
    }).then((result) => {
      if(result.value){
          $.ajax({
          type:'post',
          data: {
            'action' : 'operacion_finalizar',
            'id' : id,
            'idmovind' : idmovind,
            'idmovgral' : '',
            'cantidadfin' : cantidadfin,
            'urgente' : urgenteval,
            'fecha_cuando' : fecha_cuando,
            'fechasol' : fechasolform,
            'fechafin' : fechafinform,
            'notas' : notas,
            'tipo' : 'retiro'
          },
          url: url_opefin.ajax_opefin_url,
          success: function(data) {
            var resultado = JSON.parse(data);
            console.log(resultado);
            if(resultado.respuesta==1){
              $("#modal-finret").modal('toggle');
              $("#cantidadfin").val('0.00');
              $("#idmovind").val('');
              $("#fechafin").val('');
              $("#fechasol").val('');
              $(".tab-adminretiros").DataTable().ajax.reload();
                swal(
                'Retiro autorizado',
                'El inversionista vera ahora su retiro como autorizado dentro de su historial.',
                'success'
              )
            }else{
              swal(
              'Ocurrió un error',
              'Se ha producido un error inesperado al tratar de finalizar el retiro. Por favor intente nuevamente más tarde',
              'error'
              )
            }
          }
        });
      }
    })
  }

}

function finalizarDeposito(e){

  e.preventDefault();

  let id = $("#iddep").val();
  let idmovind = $("#idmovind").val();
  let idmovgral = $("#idmovgral").val();
  let fecha_cuando = $("#fechadep").val();
  let fechasol = $("#fechasol").val();
  let fechasolform = fechasol.split("/").reverse().join("-");
  let fechafin = $("#fechafin").val();
  let fechafinform = fechafin.split("/").reverse().join("-");
  let notas = $("#notas").val();
  let cantidadfin = parseFloat($("#cantidadfin").val());

  // console.log(cantidad);
  if(cantidadfin == 0) {
    swal({
      title: 'Error',
      text: "La cantidad real del depósito debe ser mayor a 0",
      type: 'error',
    });
    return;
  }else{
    swal({
      title: 'Confirmar',
      text: "Al finalizar el depósito, este pasará a tener un status finalizado y ser contabilizado en el historial del inversionista. La acción no se puede deshacer",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Finalizar'
    }).then((result) => {
      if(result.value){
          $.ajax({
          type:'post',
          data: {
            'action' : 'operacion_finalizar',
            'id' : id,
            'idmovind' : idmovind,
            'idmovgral' : idmovgral,
            'cantidadfin' : cantidadfin,
            'fecha_cuando' : fecha_cuando,
            'fechasol' : fechasolform,
            'fechafin' : fechafinform,
            'notas' : notas,
            'tipo' : 'deposito'
          },
          url: url_opefin.ajax_opefin_url,
          success: function(data) {
            var resultado = JSON.parse(data);
            console.log(resultado);
            if(resultado.respuesta==1){
              $("#modal-findep").modal('toggle');
              $("#cantidadfin").val('0.00');
              $("#idmovgral").val('');
              $("#idmovind").val('');
              $("#fechafin").val('');
              $("#fechasol").val('');
              $(".tab-admindepositos").DataTable().ajax.reload();
                swal(
                'Depósito autorizado',
                'El inversionista vera ahora su depósito como autorizado dentro de su historial.',
                'success'
              )
            }else{
              swal(
              'Ocurrió un error',
              'Se ha producido un error inesperado al tratar de finalizar el depósito. Por favor intente nuevamente más tarde',
              'error'
              )
            }
          }
        });
      }
    })
  }

}

function editarRetiro(e){

  e.preventDefault();

  let id = $("#ideret").val();
  let idmovind = $("#eidmovind").val();
  let cantidadfin = parseFloat($("#ecantidadfin").val());
  let urgente = $("#eurgenteret").is(':checked');
  let urgenteval = 0;
  if (urgente) {
    urgenteval = 1;
  }
  let fecha_cuando = $("#efecharet").val();
  let fechasol = $("#efechasol").val();
  let fechasolform = fechasol.split("/").reverse().join("-");
  let fechafin = $("#efechafin").val();
  let fechafinform = fechafin.split("/").reverse().join("-");
  let notas = $("#enotas").val();
  let fechaval = validarFecha(fechafin);
  console.log(fechafin);

  // console.log(cantidad);
  if(cantidadfin == 0) {
    swal({
      title: 'Error',
      text: "La cantidad final del retiro debe ser mayor a 0",
      type: 'error',
    });
    return;
  }else if(fecha_cuando == 0 && urgente == false) {
    swal({
      title: 'Error',
      text: "Debe seleccionar una fecha de retiro",
      type: 'error',
    });
    return;
  }else{
    swal({
      title: 'Confirmar',
      text: "¿Esta seguro que desea editar los campos de este retiro?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Editar'
    }).then((result) => {
      if(result.value){
          $.ajax({
          type:'post',
          data: {
            'action' : 'operacion_editar',
            'id' : id,
            'idmovind' : idmovind,
            'urgente' : urgenteval,
            'fecha_cuando' : fecha_cuando,
            'fechasol' : fechasolform,
            'fechafin' : fechafinform,
            'cantidadfin' : cantidadfin,
            'notas' : notas,
            'tipo' : 'retiro'
          },
          url: url_opefin.ajax_opefin_url,
          success: function(data) {
            var resultado = JSON.parse(data);
            console.log(resultado);
            if(resultado.respuesta==1){
              $("#modal-editret").modal('toggle');
              $("#ecantidadfin").val('0.00');
              $("#eidmovind").val('');
              $('#enotas').val('');
              $(".tab-adminretiros").DataTable().ajax.reload();
                swal(
                'Retiro editado',
                'El retiro del inversionista se ha editado correctamente.',
                'success'
              )
            }else{
              swal(
              'Ocurrió un error',
              'Se ha producido un error inesperado al tratar de editar el retiro. Por favor intente nuevamente más tarde',
              'error'
              )
            }
          }
        });
      }
    })
  }

}

function cancelarRetiro(e){

  e.preventDefault();

  let id = $("#idcret").val();
  let fechasol = $("#cfechasol").val();
  let fechasolform = fechasol.split("/").reverse().join("-");
  let fechafin = $("#cfechafin").val();
  let fechafinform = fechafin.split("/").reverse().join("-");
  let notas = $("#cnotas").val();
  let fechaval = validarFecha(fechafin);

  // console.log(cantidad);
  swal({
    title: 'Confirmar',
    text: "¿Esta seguro que desea cancelar este retiro?",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, Cancelar',
    cancelButtonText: 'No'
  }).then((result) => {
    if(result.value){
        $.ajax({
        type:'post',
        data: {
          'action' : 'operacion_cancelar',
          'id' : id,
          'fechasol' : fechasolform,
          'fechafin' : fechafinform,
          'notas' : notas,
          'tipo' : 'retiro'
        },
        url: url_opefin.ajax_opefin_url,
        success: function(data) {
          var resultado = JSON.parse(data);
          console.log(resultado);
          if(resultado.respuesta==1){
            $("#modal-cancret").modal('toggle');
            $('#cnotas').val('');
            $(".tab-adminretiros").DataTable().ajax.reload();
              swal(
              'Retiro cancelado',
              'El retiro del inversionista se ha cancelado correctamente.',
              'success'
            )
          }else{
            swal(
            'Ocurrió un error',
            'Se ha producido un error inesperado al tratar de cancelar el retiro. Por favor intente nuevamente más tarde',
            'error'
            )
          }
        }
      });
    }
  })

}

function editarDeposito(e){

  e.preventDefault();

  let id = $("#idedep").val();
  let idmovind = $("#eidmovind").val();
  let idmovgral = $("#eidmovgral").val();
  let cantidadfin = parseFloat($("#ecantidadfin").val());
  let fecha_cuando = $("#efechadep").val();
  let fechasol = $("#efechasol").val();
  let fechasolform = fechasol.split("/").reverse().join("-");
  let fechafin = $("#efechafin").val();
  let fechafinform = fechafin.split("/").reverse().join("-");
  let notas = $("#enotas").val();
  let fechaval = validarFecha(fechafin);
  //console.log(interes);
  //console.log(idmovgral);

  // console.log(cantidad);
  if(cantidadfin == 0) {
    swal({
      title: 'Error',
      text: "La cantidad final del deposito debe ser mayor a 0",
      type: 'error',
    });
    return;
  }else{
    swal({
      title: 'Confirmar',
      text: "¿Esta seguro que desea editar los campos de este depósito?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Editar'
    }).then((result) => {
      if(result.value){
          $.ajax({
          type:'post',
          data: {
            'action' : 'operacion_editar',
            'id' : id,
            'idmovind' : idmovind,
            'idmovgral' : idmovgral,
            'fechasol' : fechasolform,
            'fechafin' : fechafinform,
            'cantidadfin' : cantidadfin,
            'fecha_cuando' : fecha_cuando,
            'notas' : notas,
            'tipo' : 'deposito'
          },
          url: url_opefin.ajax_opefin_url,
          success: function(data) {
            var resultado = JSON.parse(data);
            console.log(resultado);
            if(resultado.respuesta==1){
              $("#modal-editdep").modal('toggle');
              $("#ecantidadfin").val('0.00');
              $("#eidmovind").val('');
              $("#eidmovgral").val('');
              $('#enotas').val('');
              $(".tab-admindepositos").DataTable().ajax.reload();
                swal(
                'Depósito editado',
                'El depósito del inversionista se ha editado correctamente.',
                'success'
              )
            }else{
              swal(
              'Ocurrió un error',
              'Se ha producido un error inesperado al tratar de editar el depósito. Por favor intente nuevamente más tarde',
              'error'
              )
            }
          }
        });
      }
    })
  }

}

function cancelarDeposito(e){

  e.preventDefault();

  let id = $("#idcdep").val();
  let fechasol = $("#cfechasol").val();
  let fechasolform = fechasol.split("/").reverse().join("-");
  let fechafin = $("#cfechafin").val();
  let fechafinform = fechafin.split("/").reverse().join("-");
  let notas = $("#cnotas").val();
  let fechaval = validarFecha(fechafin);
  //console.log(interes);
  //console.log(idmovgral);

  // console.log(cantidad);
  swal({
    title: 'Confirmar',
    text: "¿Esta seguro que desea cancelar este depósito?",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, Cancelar',
    cancelButtonText: 'No'
  }).then((result) => {
    if(result.value){
        $.ajax({
        type:'post',
        data: {
          'action' : 'operacion_cancelar',
          'id' : id,
          'fechasol' : fechasolform,
          'fechafin' : fechafinform,
          'notas' : notas,
          'tipo' : 'deposito'
        },
        url: url_opefin.ajax_opefin_url,
        success: function(data) {
          var resultado = JSON.parse(data);
          console.log(resultado);
          if(resultado.respuesta==1){
            $("#modal-cancdep").modal('toggle');
            $('#cnotas').val('');
            $(".tab-admindepositos").DataTable().ajax.reload();
              swal(
              'Depósito cancelado',
              'El depósito del inversionista se ha cancelado correctamente.',
              'success'
            )
          }else{
            swal(
            'Ocurrió un error',
            'Se ha producido un error inesperado al tratar de cancelar el depósito. Por favor intente nuevamente más tarde',
            'error'
            )
          }
        }
      });
    }
  })

}

function editarMes(e){

  e.preventDefault();

  let id = $("#idmes").val();
  let interes = $("#intmes").val();
  let notas = $("#menotas").val();

  // console.log(cantidad);
  if(interes == '') {
    swal({
      title: 'Error',
      text: "Debe seleccionar una opción para el status del mes.",
      type: 'error',
    });
    return;
  }else{
    swal({
      title: 'Confirmar',
      text: "¿Esta seguro que desea editar el % de status de este mes?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Editar'
    }).then((result) => {
      if(result.value){
          $.ajax({
          type:'post',
          data: {
            'action' : 'operacion_editmes',
            'id' : id,
            'interes' : interes,
            'notas' : notas
          },
          url: url_opefin.ajax_opefin_url,
          success: function(data) {
            var resultado = JSON.parse(data);
            console.log(resultado);
            if(resultado.respuesta==1){
              tb_remove();
              $(".td-"+id).html(interes+"%");
              $(".td-"+id+"-notas").attr('aria-label', notas);
                swal(
                'Status editado',
                'El status del mes se ha editado correctamente.',
                'success'
              )
            }else{
              swal(
              'Ocurrió un error',
              'Se ha producido un error inesperado al tratar de editar el status del mes. Por favor intente nuevamente más tarde',
              'error'
              )
            }
          }
        });
      }
    })
  }

}

function agregarMeses(e){
  e.preventDefault();

  let nummeses = $("#mesesextra").val();
  let usersta = $('#status').attr("selected",true).val();
  let id = $("#mesesextra").attr('data-user');

  // console.log(cantidad);
  if(nummeses == 0 || !nummeses) {
    swal({
      title: 'Error',
      text: "Debe de indicar el número de meses a agregar.",
      type: 'error',
    });
    return;
  }else if(nummeses < 1 || nummeses > 60){
    swal({
      title: 'Error',
      text: "Solo se permite ingresar entre 1 a 60 meses adicionales a la vez",
      type: 'error',
    });
    return;
  }else{
    swal({
      title: 'Confirmar',
      text: `¿Estas seguro que deseas agregar ${nummeses} mes(es) al inversionista dentro de su cuenta?`,
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Agregar'
    }).then((result) => {
      if(result.value){
        // console.log(id);
        $.ajax({
        type:'post',
        data: {
          'action' : 'operacion_agregarmes',
          'id': id,
          'nummeses' : nummeses,
          'usersta' : usersta
        },
        url: url_opefin.ajax_opefin_url,
        success: function(data) {
          var resultado = JSON.parse(data);
          // console.log(resultado);
          if(resultado.respuesta==1){
            $("#mesesextra").val("0");
            swal(
              'Meses agregados correctamente',
              'Los meses para el inversionista han sido agregados correctamente. Recargando pagina...',
              'success'
            );
            setTimeout(function(){
              location.reload();
            },3000);
          }else{
            swal(
            'Ocurrió un error',
            'Se ha producido un error inesperado al tratar de agregar los meses del inversionista. Por favor intente nuevamente más tarde',
            'error'
            )
          }
        }
      });
      }
    });
  }
}

function agregarConStatus(e){
  e.preventDefault();

  let mes = parseInt($("#constatusmes").val());
  let year = parseInt($("#constatusyear").val());
  let porcentaje = parseFloat($("#constatusporcentaje").val());
  let tipo = parseInt($('input:radio[name=tipostatus]:checked').val());
  let notas = $("#constatusnotas").val();
  let id = $("#constatusmes").attr('data-user');

  console.log(tipo);
  if(porcentaje < 0) {
    swal({
      title: 'Error',
      text: "El porcentaje de utilidad debe ser superior a cero.",
      type: 'error',
    });
    return;
  }else{
    swal({
      title: 'Confirmar',
      text: `¿Estas seguro que deseas agregar un nuevo porcentaje de utilidad para el inversionista? Este porcentaje aplicará desde el año y mes seleccionado hasta que agregues un nuevo porcentaje de utilidad, de tipo regular con fecha posterior.`,
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Agregar'
    }).then((result) => {
      if(result.value){
        // console.log(id);
        $.ajax({
        type:'post',
        data: {
          'action' : 'operacion_agregarconstatus',
          'id': id,
          'mes' : mes,
          'year' : year,
          'porcentaje' : porcentaje,
          'tipo' : tipo,
          'notas' : notas
        },
        url: url_opefin.ajax_opefin_url,
        success: function(data) {
          var resultado = JSON.parse(data);
          // console.log(resultado);
          if(resultado.respuesta==1){
            $("#constatusporcentaje").val("0");
            $("#constatusnotas").val("");
            swal(
              'Porcentaje agregado correctamente',
              'El porcentaje de utilidad se ha agregado correctamente. Recargando pagina...',
              'success'
            );
            setTimeout(function(){
              location.reload();
            },3000);
          }else{
            swal(
            'Ocurrió un error',
            'Se ha producido un error inesperado al tratar de agregar el porcentaje de utilidad se ha agregado correctamente. Por favor intente nuevamente más tarde',
            'error'
            )
          }
        }
      });
      }
    });
  }
}

function editarStatus(e){

  e.preventDefault();

  let id = parseInt($("#idconstatus").val());
  let mes = parseInt($("#statusmes").val());
  let year = parseInt($("#statusyear").val());
  let porcentaje = parseFloat($("#statusporcentaje").val());
  let tipo = parseInt($('input:radio[name=tipostatuse]:checked').val());
  let notas = $("#statusnotas").val();

  console.log(tipo);
  if(year == '') {
    swal({
      title: 'Error',
      text: "Debe seleccionar un año para el porcentaje nuevo.",
      type: 'error',
    });
    return;
  }else{
    swal({
      title: 'Confirmar',
      text: "¿Esta seguro que desea editar el porcentaje de utilidad?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Editar'
    }).then((result) => {
      if(result.value){
          $.ajax({
          type:'post',
          data: {
            'action' : 'operacion_editstatus',
            'id' : id,
            'mes' : mes,
            'year' : year,
            'porcentaje' : porcentaje,
            'tipo' : tipo,
            'notas' : notas
          },
          url: url_opefin.ajax_opefin_url,
          success: function(data) {
            var resultado = JSON.parse(data);
            console.log(resultado);
            if(resultado.respuesta==1){
              tb_remove();
                swal(
                'Status editado',
                'El porcentaje de utilidad se ha editado correctamente.',
                'success'
              );
              setTimeout(function(){
                location.reload();
              },3000);
            }else{
              swal(
              'Ocurrió un error',
              'Se ha producido un error inesperado al tratar de editar el porcentaje de utilidad. Por favor intente nuevamente más tarde',
              'error'
              )
            }
          }
        });
      }
    })
  }

}

$('.tab-constatus').on('click','.btn-elimstatus', function(e){

  e.preventDefault();

  var id = $(this).attr('data-id');

  swal({
    title: 'Confirmar',
    text: "¿Está seguro que desea eliminar este porcentaje de utilidad especial? Si este registro tiene fecha pasada, la utilidad del inversionista dejará de considerar y aplicar dicho porcentaje de utilidad.",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, Eliminar',
    cancelButtonText: 'No'
  }).then((result) => {
    if(result.value){
        $.ajax({
        type:'post',
        data: {
          'action' : 'operacion_elimstatus',
          'id' : id
        },
        url: url_opefin.ajax_opefin_url,
        success: function(data) {
          var resultado = JSON.parse(data);
          console.log(resultado);
          if(resultado.respuesta==1){
              swal(
              'Porcentaje de utilidad eliminado',
              'El porcentaje de utilidad se ha eliminado correctamente.',
              'success'
            );
            setTimeout(function(){
              location.reload();
            },3000);
          }else{
            swal(
            'Ocurrió un error',
            'Se ha producido un error inesperado al tratar de eliminar el porcentaje de utilidad. Por favor intente nuevamente más tarde',
            'error'
            )
          }
        }
      });
    }
  })

});

function crearBalance(e){

  e.preventDefault();

  let mes = $('#mes').val();
  let agno = parseInt($('#agno').val());
  let startbal = parseFloat($('#startbal').val());
  let balbefcom = parseFloat($('#balbefcom').val());
  let comtrader = parseFloat($('#comtrader').val());
  let combroker = parseFloat($('#combroker').val());
  let balfinal = parseFloat($('#balfinal').val());
  let totalcuentas = parseFloat($('#totalcuentas').val());
  let notas = $('#notas').val();

  /*console.log(mes);
  console.log(agno);
  console.log(startbal);
  console.log(balbefcom);
  console.log(comtrader);
  console.log(combroker);
  console.log(balfinal);
  console.log(totalcuentas);
  console.log(notas);*/

  // console.log(cantidad);
  if(agno < 2000) {
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
        'action' : 'traer_datosbal',
        'id' : 0,
        'mes' : mes,
        'agno' : agno,
        'tipo' : 'newbalance'
      },
      url: url_opefin.ajax_opefin_url,
      success: function(data) {
        var resultado1 = JSON.parse(data);

        if(resultado1.data.length != 0){

          swal({
            title: 'Error',
            text: "Ya existe un balance para el mes y año seleccionado.",
            type: 'error',
          });

        }else{

          $.ajax({
            type:'post',
            data: {
              'action' : 'operacion_newbalance',
              'mes' : mes,
              'agno' : agno,
              'startbal' : startbal,
              'balbefcom' : balbefcom,
              'comtrader' : comtrader,
              'combroker' : combroker,
              'balfinal' : balfinal,
              'totalcuentas' : totalcuentas,
              'notas' : notas
            },
            url: url_opefin.ajax_opefin_url,
            success: function(data) {
              var resultado = JSON.parse(data);
              console.log(resultado);
              if(resultado.respuesta==1){
                tb_remove();
                $('#startbal').val('0');
                $('#balbefcom').val('0');
                $('#comtrader').val('0');
                $('#combroker').val('0');
                $('#balfinal').val('0');
                $('#totallastmonth').val('0');
                $('#totalcuentas').val('0');
                $('#notas').val('');
                //$(".tab-admconmaster").DataTable().ajax.reload();
                  swal(
                  'Balance registrado',
                  'El balance se ha registrado correctamente.',
                  'success'
                );
                setTimeout(function(){
                  location.reload();
                },3000);
              }else{
                swal(
                'Ocurrió un error',
                'Se ha producido un error inesperado al tratar de registrar el balance. Por favor intente nuevamente más tarde',
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

function editarBalance(e){

  e.preventDefault();

  let id = parseInt($('#idebal').val());
  let mes = parseInt($('#emes').val());
  let agno = parseInt($('#eagno').val());
  let startbal = parseFloat($('#estartbal').val());
  let balbefcom = parseFloat($('#ebalbefcom').val());
  let comtrader = parseFloat($('#ecomtrader').val());
  let combroker = parseFloat($('#ecombroker').val());
  let balfinal = parseFloat($('#ebalfinal').val());
  let totalcuentas = parseFloat($('#etotalcuentas').val());
  let notas = $('#enotas').val();

  /*console.log(mes);
  console.log(agno);
  console.log(startbal);
  console.log(balbefcom);
  console.log(comtrader);
  console.log(combroker);
  console.log(balfinal);
  console.log(totalcuentas);
  console.log(notas);*/

  console.log(balbefcom);
  console.log(balfinal);
  console.log(totalcuentas);
  if(agno < 2000) {
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
        'action' : 'traer_datosbal',
        'id' : 0,
        'mes' : mes,
        'agno' : agno,
        'tipo' : 'newbalance'
      },
      url: url_opefin.ajax_opefin_url,
      success: function(data) {
        var resultado1 = JSON.parse(data);
        if(resultado1.data.length != 0 ){
          console.log(resultado1);
          if(resultado1.data[0].id == id){
            $.ajax({
              type:'post',
              data: {
                'action' : 'operacion_editbalance',
                'id' : id,
                'mes' : mes,
                'agno' : agno,
                'startbal' : startbal,
                'balbefcom' : balbefcom,
                'comtrader' : comtrader,
                'combroker' : combroker,
                'balfinal' : balfinal,
                'totalcuentas' : totalcuentas,
                'notas' : notas
              },
              url: url_opefin.ajax_opefin_url,
              success: function(data) {
                var resultado = JSON.parse(data);
                console.log(resultado);
                if(resultado.respuesta==1){
                  tb_remove();
                  $('#estartbal').val('0');
                  $('#ebalbefcom').val('0');
                  $('#ecomtrader').val('0');
                  $('#ecombroker').val('0');
                  $('#ebalfinal').val('0');
                  $('#etotallastmonth').val('0');
                  $('#etotalcuentas').val('0');
                  $('#enotas').val('');
                  //$(".tab-admconmaster").DataTable().ajax.reload();
                    swal(
                    'Balance editado',
                    'El balance se ha editado correctamente.',
                    'success'
                  );
                  setTimeout(function(){
                    location.reload();
                  },3000);

                }else{
                  swal(
                  'Ocurrió un error',
                  'Se ha producido un error inesperado al tratar de editar el balance. Por favor intente nuevamente más tarde (Error de respuesta diferente de 1)',
                  'error'
                  )
                }
              }
            });
          }else{
            swal({
              title: 'Error',
              text: "Ya existe un balance para el mes y año seleccionado. (El data y el id no son iguales)",
              type: 'error',
            });
          }

        }else{

          $.ajax({
            type:'post',
            data: {
              'action' : 'operacion_editbalance',
              'id' : id,
              'mes' : mes,
              'agno' : agno,
              'startbal' : startbal,
              'balbefcom' : balbefcom,
              'comtrader' : comtrader,
              'combroker' : combroker,
              'balfinal' : balfinal,
              'totalcuentas' : totalcuentas,
              'notas' : notas
            },
            url: url_opefin.ajax_opefin_url,
            success: function(data) {
              var resultado = JSON.parse(data);
              console.log(resultado);
              if(resultado.respuesta==1){
                tb_remove();
                $('#estartbal').val('0');
                $('#ebalbefcom').val('0');
                $('#ecomtrader').val('0');
                $('#ecombroker').val('0');
                $('#ebalfinal').val('0');
                $('#etotallastmonth').val('0');
                $('#etotalcuentas').val('0');
                $('#enotas').val('');
                $(".tab-admconmaster").DataTable().ajax.reload();
                  swal(
                  'Balance editado',
                  'El balance se ha editado correctamente.',
                  'success'
                );
                setTimeout(function(){
                  location.reload();
                },3000);
              }else{
                swal(
                'Ocurrió un error',
                'Se ha producido un error inesperado al tratar de editar el balance. Por favor intente nuevamente más tarde (No existe el mes-agno a editar)',
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

function editarRegHistorico(e){

  e.preventDefault();

  let mes = parseInt($('#ermes').val());
  let year = parseInt($('#eryear').val());
  let utilext = parseFloat($("#erext").val());
  let notas = $('#ernotas').val();

  if(utilext == 0) {
    swal({
      title: 'Error',
      text: "La cantidad de utilidades externas debe ser mayor a 0",
      type: 'error',
    });
    return;
  } else {
    $.ajax({
      type:'post',
      data: {
        'action' : 'operacion_editreghist',
        'mes' : mes,
        'year' : year,
        'utilext' : utilext,
        'notas' : notas
      },
      url: url_opefin.ajax_opefin_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        console.log(resultado);
        if(resultado.respuesta==1){
          tb_remove();
          // $('#estartbal').val('0');
          // $('#ebalbefcom').val('0');
          // $('#ecomtrader').val('0');
          // $('#ecombroker').val('0');
          // $('#ebalfinal').val('0');
          // $('#etotallastmonth').val('0');
          $("#erext").val('0')
          $('#ernotas').val('');
          $(".tab-rephistgral").DataTable().ajax.reload();
            swal(
            'Registro editado',
            'El registro se ha editado correctamente.',
            'success'
          );

        }else{
          swal(
          'Ocurrió un error',
          'Se ha producido un error inesperado al tratar de editar el registro. Por favor intente nuevamente más tarde',
          'error'
          )
        }
      }
    });
    // console.log(year);
    // console.log(mes);
    // console.log(utilext);
  }

}
function crearDepMaster(e){

  e.preventDefault();

  let idinddepmas = $('#idinddepmas').val();
  let idgraldepmas = $('#idgraldepmas').val();
  let fechadepmas = $('#fechadepmas').val();
  let fechadepmasform = fechadepmas.split("/").reverse().join("-");
  let cantdepmas = parseFloat($('#cantidaddepmas').val());
  let cantfindepmas = parseFloat($('#cantidadfindepmas').val());
  let notasdepmas = $('#notasdepmas').val();

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
  if(cantfindepmas <= 0) {
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
        'action' : 'crear_opemaster',
        'tipo' : 'deposito',
        'idmov_ind' : idinddepmas,
        'idmov_gral' : idgraldepmas,
        'fecha_deposito' : fechadepmasform,
        'cantidad' : cantdepmas,
        'cantidad_real' : cantfindepmas,
        'notas' : notasdepmas
      },
      url: url_opefin.ajax_opefin_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        if(resultado.respuesta==1){
          tb_remove();
            swal(
            'Depósito registrado',
            'Se ha registrado correctamente el depósito a la cuenta maestra.',
            'success'
          );
          setTimeout(function(){
            location.reload();
          },3000);
        }else{
          swal(
            'Ocurrió un error',
            'Se ha producido un error inesperado al tratar de registrar el depósito. Por favor intente nuevamente más tarde',
            'error'
          );
        }

      }
    });

  }

}

function crearRetMaster(e){

  e.preventDefault();

  let idindretmas = $('#idindretmas').val();
  let fecharetmas = $('#fecharetmas').val();
  let fecharetmasform = fecharetmas.split("/").reverse().join("-");
  // let cantretmas = parseFloat($('#cantidadretmas').val());
  let cantfinretmas = parseFloat($('#cantidadfinretmas').val());
  let notasretmas = $('#notasretmas').val();

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
  if(cantfinretmas <= 0) {
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
        'action' : 'crear_opemaster',
        'tipo' : 'retiro',
        'idmov_ind' : idindretmas,
        'fecha_retiro' : fecharetmasform,
        'cantidad' : cantfinretmas,
        'cantidad_real' : cantfinretmas,
        'notas' : notasretmas
      },
      url: url_opefin.ajax_opefin_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        if(resultado.respuesta==1){
          tb_remove();
            swal(
            'Retiro registrado',
            'Se ha registrado correctamente el retiro a la cuenta maestra.',
            'success'
          );
          setTimeout(function(){
            location.reload();
          },3000);
        }else{
          swal(
            'Ocurrió un error',
            'Se ha producido un error inesperado al tratar de registrar el retiro. Por favor intente nuevamente más tarde',
            'error'
          );
        }

      }
    });

  }

}

function editarDepMaster(e){

  e.preventDefault();

  let id = parseInt($('#idedep').val());
  let idinddepmas = $('#eidinddepmas').val();
  let idgraldepmas = $('#eidgraldepmas').val();
  let fechadepmas = $('#efechadepmas').val();
  let fechadepmasform = fechadepmas.split("/").reverse().join("-");
  let cantdepmas = parseFloat($('#ecantidaddepmas').val());
  let cantfindepmas = parseFloat($('#ecantidadfindepmas').val());
  let notasdepmas = $('#enotasdepmas').val();

  // console.log(id);
  if(cantfindepmas == 0) {
    swal({
      title: 'Error',
      text: "La cantidad final del deposito debe ser mayor a 0",
      type: 'error',
    });
    return;
  }else{
    swal({
      title: 'Confirmar',
      text: "¿Esta seguro que desea editar los campos de este depósito?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Editar'
    }).then((result) => {
      if(result.value){
        $.ajax({
          type:'post',
          data: {
            'action' : 'editar_opemaster',
            'tipo' : 'deposito',
            'id':id,
            'idmov_ind' : idinddepmas,
            'idmov_gral' : idgraldepmas,
            'fecha_deposito' : fechadepmasform,
            'cantidad' : cantdepmas,
            'cantidad_real' : cantfindepmas,
            'notas' : notasdepmas
          },
          url: url_opefin.ajax_opefin_url,
          success: function(data) {
            var resultado = JSON.parse(data);
            if(resultado.respuesta==1){
              $("#modal-edepmaster").modal('toggle');
              $(".tab-admindepmas").DataTable().ajax.reload();
                swal(
                'Depósito editado',
                'Se ha editado correctamente el depósito a la cuenta maestra.',
                'success'
              );

            }else{
              swal(
                'Ocurrió un error',
                'Se ha producido un error inesperado al tratar de editar el depósito. Por favor intente nuevamente más tarde',
                'error'
              );
            }

          }
        });
      }
    })
  }

}

function editarRetMaster(e){

  e.preventDefault();

  let id = parseInt($('#ideret').val());
  let idindretmas = $('#eidindretmas').val();
  let fecharetmas = $('#efecharetmas').val();
  let fecharetmasform = fecharetmas.split("/").reverse().join("-");
  // let cantretmas = parseFloat($('#ecantidadretmas').val());
  let cantfinretmas = parseFloat($('#ecantidadfinretmas').val());
  let notasretmas = $('#enotasretmas').val();


  // console.log(id);
  if(cantfinretmas <= 0) {
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
        'action' : 'editar_opemaster',
        'tipo' : 'retiro',
        'id' : id,
        'idmov_ind' : idindretmas,
        'fecha_retiro' : fecharetmasform,
        'cantidad' : cantfinretmas,
        'cantidad_real' : cantfinretmas,
        'notas' : notasretmas
      },
      url: url_opefin.ajax_opefin_url,
      success: function(data) {
        var resultado = JSON.parse(data);
        if(resultado.respuesta==1){
          $("#modal-eretmaster").modal('toggle');
          $(".tab-adminretmas").DataTable().ajax.reload();
            swal(
            'Retiro editado',
            'Se ha editado correctamente el retiro a la cuenta maestra.',
            'success'
          );

        }else{
          swal(
            'Ocurrió un error',
            'Se ha producido un error inesperado al tratar de editar el retiro. Por favor intente nuevamente más tarde',
            'error'
          );
        }

      }
    });

  }

}

function validarFecha(fecha){
  let fechasel = new Date(fecha);
  let fechahoy = new Date();

  if(fechasel > fechahoy){
    return true;
  }else{
    return false;
  }

}

function totalNBalFinal(){
  let balbefcom = parseFloat($('#balbefcom').val());
  let comtrader = parseFloat($('#comtrader').val());

  let balfinal = balbefcom - comtrader;

  $('#balfinal').val(balfinal);
}

function totalEBalFinal(){
  let balbefcom = parseFloat($('#ebalbefcom').val());
  let comtrader = parseFloat($('#ecomtrader').val());

  let balfinal = balbefcom - comtrader;

  $('#ebalfinal').val(balfinal);
}

function cargarDatosBal(e){
  let totallm = parseFloat($("#totinvlm").attr('data-totinvlm'));
  let ultbalfinal = parseFloat($("#totinvhoy").attr('data-ultbalfinal'));
  let depmes = parseFloat($("#totinvhoy").attr('data-dephoy'));
  let retmes = parseFloat($("#totinvhoy").attr('data-rethoy'));
  let totalhoy = parseFloat($("#totinvhoy").attr('data-totinvhoy'));
  let balbefcom = ultbalfinal + depmes - retmes;

  // console.log(balbefcom);
  //console.log(ultbalfinal);
  $('#totallastmonth').val(totallm);
  $('#totalcuentas').val(totalhoy);
  $('#startbal').val(ultbalfinal);
  $('#balbefcom').val(balbefcom);
}

function cargarDatosOpeMas(e){
  let totallm = parseFloat($("#totinvlm").attr('data-totinvlm'));
  let ultbalfinal = parseFloat($("#totinvhoy").attr('data-ultbalfinal'));
  let totalhoy = parseFloat($("#totinvhoy").attr('data-totinvhoy'));
  //console.log(ultbalfinal);
  $('#totallastmonth').val(totallm);
  $('#totalcuentas').val(totalhoy);
  $('#startbal').val(ultbalfinal);
}

function cambiarInputs(e){
  e.preventDefault();

  let tipoSel = $(this).val();

  if (tipoSel == "1") {
    $('#constatusporcentaje').attr("disabled", false);
    $('#constatusporcentaje').val("0.00");
  }else {
    $('#constatusporcentaje').attr("disabled", true);
    $('#constatusporcentaje').val("0.00");
  }

}

function cambiarInputsEdit(e){
  e.preventDefault();

  let tipoSel = $(this).val();

  if (tipoSel == "1") {
    $('#statusporcentaje').attr("disabled", false);
    $('#statusporcentaje').val("0.00");
  }else {
    $('#statusporcentaje').attr("disabled", true);
    $('#statusporcentaje').val("0.00");
  }

}

function escapeHtml(text) {
    'use strict';
    return text.replace(/[\"&<>]/g, function (a) {
        return { '"': '&quot;', '&': '&amp;', '<': '&lt;', '>': '&gt;' }[a];
    });
}
