var pathname = window.location.pathname;
var table = pathname;
$().ready(function() {
    $('#descripcionproducto-filtro').attr("value",null);
	$("#buscarregistro").click(function() {
		 buscarRegistros();
	 });

	$("#cerrar-bot").click(function() {
		$("#modalEditar").hide();
	});

	$("#cancelar-bot").click(function() {
		$("#modalEditar").hide();
	});

	$('#modalEditar').modal({backdrop:false,show:false});



	$("#nuevoregistro").click(function() {
		$('#modalEditar').show();
		limpiarFormulario();
		$('#codigocliente-modal').attr("value",null);
		$("#guardar").html("Guardar");
		$("#editar-nuevo").html("Nuevo Registro");
		 
		
	});

	$('#guardar').click(function() {
//		 if(!confirm("Esta seguro de que desea almacenar los datos?"))
//				return;
		 var data = obtenerJsonFormulario();
		if(data != null){
			enviarParametrosRegistro(data);
		}
	 });


	//validarNumerosCampo();


});

function validarNumerosLetrasPorcentageEspacio(e) { // 1
	var te;
	if(document.all) {
		if (e.keyCode==37) return false; // %
		if (e.keyCode==63) return false; // guion bajo
		if (e.keyCode==95) return false; // guion bajo
		if (e.keyCode==8) return true; // back spacebar
	    if (e.keyCode==32) return true; // space bar
	    te = String.fromCharCode(e.keyCode); // 5
	} else {
		if (e.which==37) return false; // %
		if (e.which==0) return true; // izquierda,derecha,arriba,abajo
		if (e.which==95) return false; // guion bajo
		if (e.which==8) return true; // back space bar
	    if (e.which==32) return true; // space bar
	    te = String.fromCharCode(e.which); // 5
	}
    patron = /\w/;

    return patron.test(te); // 6
}


function enviarParametrosRegistro(data){
	$.blockUI({
        message: "Aguarde un momento por favor"
    });

	var urlenvio = '';
	if(data.COD_CLIENTE !== null && data.COD_CLIENTE.length !== 0){
		urlenvio = table+'/modificar';
	}else {
		urlenvio = table+'/guardar';
	}
	var dataString = JSON.stringify(data);

	$.ajax({
        url: urlenvio,
        type: 'post',
        data: {"parametros":dataString},
        dataType: 'json',
        async : true,
        success: function(respuesta){
        	if(respuesta == null){
        		mostarVentana("error","TIMEOUT");
        	} else if(respuesta.result == "EXITO") {
        		mostarVentana("success-registro-listado","Los datos han sido almacenados exitosamente");
        		$('#modalEditar').hide();
        		limpiarFormulario();
        		$("#grillaRegistro").trigger("reloadGrid");
        	} else if(respuesta.result == "ERROR") {
        		if(respuesta.mensaje == 23505){
        			mostarVentana("warning-registro","Ya existe un registro con la descripcion ingresada");
        		} else {
//        			mostarVentana("error-modal","Ha ocurrido un error");
        		}
        	}
        	$.unblockUI();
        },
        error: function(event, request, settings){
//        	mostarVentana("error-registro-listado","Ha ocurrido un error");
    		$.unblockUI();
        }
    });
}

function addrequiredattr(id,focus){
	$('#'+id).attr("required", "required");
	if(focus == 1)
		$('#'+id).focus();
}

function obtenerJsonFormulario() {
	var jsonObject = new Object();
	var mensaje = 'Ingrese los campos: ';
    var focus = 0;
    
	if($('#descripcioncliente-modal').attr("value") == null || $('#descripcioncliente-modal').attr("value").length == 0){
		mensaje+= ' | Descripci\u00F3n ';
    	focus++;
    	addrequiredattr('descripcioncliente-modal',focus);       
	}
	if($('#ruccliente-modal').attr("value") == null || $('#ruccliente-modal').attr("value").length == 0){
		mensaje+= ' | RUC ';
    	focus++;
    	addrequiredattr('ruccliente-modal',focus);
	}    
	
	if (mensaje != 'Ingrese los campos: '){
		mensaje+= ' |';
		mostarVentana("warning-registro", mensaje);
		return null;
	}else {
				jsonObject.COD_CLIENTE = $('#codigocliente-modal').attr("value");
                jsonObject.CLIENTE_DES = $('#descripcioncliente-modal').attr("value");
                jsonObject.CLIENTE_RUC = $("#ruccliente-modal").attr("value");
                jsonObject.CLIENTE_DIRECCION = $('#direccioncliente-modal').attr("value");
                jsonObject.CLIENTE_TELEFONO = $('#telefonocliente-modal').attr("value");
                jsonObject.CLIENTE_EMAIL = $('#emailcliente-modal').attr("value");
                jsonObject.COD_EMPRESA = $('#empresacliente-modal').attr("value");
                
                if( jsonObject.CLIENTE_TELEFONO.length == 0)
                	jsonObject.CLIENTE_TELEFONO = '0' ;
                if( jsonObject.CLIENTE_DIRECCION.length == 0)
                	jsonObject.CLIENTE_DIRECCION = '0' ;
                if( jsonObject.CLIENTE_EMAIL.length == 0 )
                	jsonObject.CLIENTE_EMAIL = '0' ;
     
                return jsonObject;
	}
}

function ocultarSuccessBlock(){
	$("#success-block").hide(500);
}

function ocultarInfoClean(){
	$("#info-block-listado").hide(500);
}

function ocultarErrorBlock(){
	$("#error-block").hide(500);
}

function ocultarErrorBlockList(){
	$("#error-block-registro-listado").hide(500);
}

function ocultarErrorBlockModal(){
	$("#error-block-modal").hide(500);
}

function ocultarWarningBlock(){
	$("#warning-block").hide(500);
}

function ocultarWarningBlockTitle(){
	$("#warning-block-registro-listado").hide(500);
}

function ocultarSuccessBlockTitle(){
	$("#success-block-registro-listado").hide(500);
}

function ocultarWarningRegistroBlock(){
	$("#warning-block-registro").hide(500);
}

function mostarVentana(box,mensaje){
	$("#success-block").hide();
	$("#info-block-listado").hide();
	if(box == "warning") {
		$("#warning-message").text(mensaje);
		$("#warning-block").show();
		setTimeout("ocultarWarningBlock()",5000);
	} else if(box == "warning-registro-listado") {
		$("#warning-message-registro-listado").text(mensaje);
		$("#warning-block-registro-listado").show();
		setTimeout("ocultarWarningBlockTitle()",5000);
	} else if(box == "success-registro-listado") {
		$("#success-message-registro-listado").text(mensaje);
		$("#success-block-registro-listado").show();
		setTimeout("ocultarSuccessBlockTitle()",5000);
	} else if(box == "warning-registro") {
		$("#warning-message-registro").text(mensaje);
		$("#warning-block-registro").show();
		setTimeout("ocultarWarningRegistroBlock()",5000);
	}  else if(box == "info") {
		$("#info-message").text(mensaje);
		$("#info-block-listado").show(500);
		setTimeout("ocultarInfoClean()",5000);
	} else if(box == "error"){
		$("#error-block").text(mensaje);
		$("#error-block").show(500);
		setTimeout("ocultarErrorBlock()",5000);
	} else if(box == "error-registro-listado"){
		$("#error-block-registro-listado").text(mensaje);
		$("#error-block-registro-listado").show(500);
		setTimeout("ocultarErrorBlockList()",5000);
	} else if(box == "error-modal"){
		$("#error-block-modal").text(mensaje);
		$("#error-block-modal").show(500);
		setTimeout("ocultarErrorBlockModal()",5000);
	}
}

function buscarRegistros(){
	var dataJson = obtenerJsonBuscar();
	$.blockUI({
        message: "Aguarde un momento por favor"
        });
	$.ajax({
        url: table+'/buscar',
        type: 'post',
        data: {"data":dataJson},
        dataType: 'html',
        async : false,
        success: function(respuesta){
        	$("#grillaRegistro")[0].addJSONData(JSON.parse(respuesta));
        	var obj = JSON.parse(respuesta);
        	if(obj.mensajeSinFilas == true){
        		mostarVentana("info","No se encontraron registros con los parametros ingresados");
        	}
        	$.unblockUI();
        },
        error: function(event, request, settings){
            $.unblockUI();
            alert("Ha ocurrido un error");
        }
    });
}

function editarRegistro(parametros){
	limpiarFormulario();
	$("#modalEditar").show();
	$("#editar-nuevo").html("Editar Registro");
	$("#guardar").html("Modificar");
//	alert(parametros.COD_EMPRESA);
	$("#codigocliente-modal").attr("value",parametros.COD_CLIENTE);
    $('#descripcioncliente-modal').attr("value",parametros.CLIENTE_DES);
    $("#direccioncliente-modal" ).val(parametros.CLIENTE_DIRECCION);
    $("#ruccliente-modal" ).val(parametros.CLIENTE_RUC);
    $("#empresacliente-modal" ).val(parametros.COD_EMPRESA);
    $("#telefonocliente-modal" ).val(parametros.CLIENTE_TELEFONO);
    $("#emailcliente-modal" ).val(parametros.CLIENTE_EMAIL);
	$("#guardar-registro").html("Modificar");
}

function limpiarFormulario(){
	$("#error-block-modal").hide();
	$("#warning-block").hide();
	$("#warning-block-registro").hide();
	$("#success-block").hide();
	cargarempresacliente();
	$("#codigocliente-modal").attr("value",null);
    $('#descripcioncliente-modal').attr("value",null);
    $("#direccioncliente-modal" ).attr("value",null);
    $("#ruccliente-modal" ).attr("value",null);
    $("#telefonocliente-modal" ).attr("value",null);
    $("#emailcliente-modal" ).attr("value",null);

}



function obtenerJsonBuscar(){
	var jsonObject = new Object();

	if($('#descripcioncliente-filtro').attr("value") != null && $('#descripcioncliente-filtro').attr("value").length != 0){
		jsonObject.descripcion = $('#descripcioncliente-filtro').attr("value");
	}
	if($('#ruccliente-filtro').attr("value") != null && $('#ruccliente-filtro').attr("value").length != 0){
		jsonObject.ruc = $('#ruccliente-filtro').attr("value");
	}

	var dataString = JSON.stringify(jsonObject);
	return dataString;
}


function cargarempresacliente(){
	
//	alert('Tipo Producto');
	$.ajax({
        url: table+'/empresacliente',
        type: 'post',
        dataType: 'html',
        async : false,
        success: function(respuesta){
        	if(respuesta== 'error'){
        		mostarVentana("error-title",mostrarError("OcurrioError"));
        	}else{
            	$("#empresacliente-modal").html(respuesta);       		
        	}
        },
        error: function(event, request, settings){
         //   $.unblockUI();
        	 alert(mostrarError("OcurrioError"));
        }
    });	
}