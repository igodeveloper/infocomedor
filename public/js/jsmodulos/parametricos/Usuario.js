var pathname = window.location.pathname;
var table = pathname;
$().ready(function() {


    $('#idusuario-filtro').attr("value",null);
    $('#descripcionusuario-filtro').attr("value",null);
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
		$('#codigousuario-modal').attr("value",null);
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
	if(data.COD_USUARIO !== null && data.COD_USUARIO.length !== 0){
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
    
	if($('#idusuario-modal').attr("value") == null || $('#idusuario-modal').attr("value").length == 0){
		mensaje+= ' | Identificador ';
    	focus++;
    	addrequiredattr('idusuario-modal',focus);       
	}
	if($('#descripcionusuario-modal').attr("value") == null || $('#descripcionusuario-modal').attr("value").length == 0){
		mensaje+= ' | Nombre y apellido ';
    	focus++;
    	addrequiredattr('descripcionusuario-modal',focus);
	}  
	if($('#passwordusuario-modal').attr("value") != $('#passwordusuario2-modal').attr("value")){
		console.log($('#passwordusuario-modal').attr("value") ,$('#passwordusuario2-modal').attr("value"));
		mensaje+= ' | Los passwords no coniciden ';
    	focus++;
    	addrequiredattr('passwordusuario-modal',focus);
	} 
	if($('#perfil-modal').attr("value") == -1){
		mensaje+= ' |  Perfil del usuario ';
    	focus++;
    	addrequiredattr('perfil-modal',focus);
	}    
	
	if (mensaje != 'Ingrese los campos: '){
		mensaje+= ' |';
		mostarVentana("warning-registro", mensaje);
		return null;
	}else {
				jsonObject.COD_USUARIO = $('#codigousuario-modal').attr("value");
                jsonObject.ID_USUARIO = $('#idusuario-modal').attr("value");
                jsonObject.NOMBRE_APELLIDO = $("#descripcionusuario-modal").attr("value");
                jsonObject.USUARIO_PASSWORD = $('#passwordusuario-modal').attr("value");    
                jsonObject.PERMISO = $('#perfil-modal').attr("value");    
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
	$("#codigousuario-modal").attr("value",parametros.COD_USUARIO);
	$("#idusuario-modal").attr("value",parametros.ID_USUARIO);
    $('#descripcionusuario-modal').attr("value",parametros.NOMBRE_APELLIDO);
    $('#perfil-modal').attr("value",parametros.PERMISO);
	$("#guardar-registro").html("Modificar");
}

function limpiarFormulario(){
	$("#error-block-modal").hide();
	$("#warning-block").hide();
	$("#warning-block-registro").hide();
	$("#success-block").hide();
	$("#idusuario-modal").attr("value",null);
    $('#descripcionusuario-modal').attr("value",null);
    $("#passwordusuario-modal" ).attr("value",null);
    $("#passwordusuario2-modal" ).attr("value",null);
}



function obtenerJsonBuscar(){
	var jsonObject = new Object();

	if($('#idusuario-filtro').attr("value") != null && $('#idusuario-filtro').attr("value").length != 0){
		jsonObject.ID_USUARIO = $('#idusuario-filtro').attr("value");
	}
	if($('#descripcionusuario-filtro').attr("value") != null && $('#descripcionusuario-filtro').attr("value").length != 0){
		jsonObject.NOMBRE_APELLIDO = $('#descripcionusuario-filtro').attr("value");
	}

	var dataString = JSON.stringify(jsonObject);
	return dataString;
}
