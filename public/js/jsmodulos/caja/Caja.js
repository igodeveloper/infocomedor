//table = '/infocomedor/infocomedor/public/index.php/caja/caja/';
var pathname = window.location.pathname;
var table = pathname;
$().ready(function() {    
    $('#descripciontipoproducto-filtro').attr("value",null);
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
		//cajaAbierta();
//	alert('Unidad Medida');
	$.ajax({
            url: table+'/cajaabiertadata',
            type: 'post',
            dataType: 'json',
            async : false,
            success: function(respuesta){            
                if(respuesta.resultado == 'error'){
                        mostarVentana("error-title","Ocurrio Error");
                }else if(respuesta.resultado != 'cerrado'){
                    //alert("Ya existe una caja abierta. Usuario : "+respuesta.nombre_apellido+" Fecha : "+respuesta.fecha_hora_apertura);
                    //exit;
                    mostarVentana("warning-block-title","Ya existe una caja abierta. Usuario : "+respuesta.nombre_apellido+" Fecha : "+respuesta.fecha_hora_apertura);
                    return;                                
                }
		limpiarFormulario();
		cargarUsuarioCaja();                                
		$('#modalEditar').show();
                $('#montoentrantecaja-modal-idioma').css("display", "none");
                $('#montoentranteefectivo-modal').css("display", "none");
                $('#montosalientecaja-modal-idioma').css("display", "none");
                $('#montosalienteefectivo-modal').css("display", "none");
                $('#montoentrantecheque-label').css("display", "none");
                $('#montoentrantecheque-modal').css("display", "none");
                $('#montosalientecheque-label').css("display", "none");
                $('#montosalientecheque-modal').css("display", "none");
                $('#fechacierrecaja-modal-idioma').css("display", "none");
                $('#fechacierrecaja-modal').css("display", "none");
                $('#montocierrecaja-modal-idioma').css("display", "none");
                $('#montocierrecaja-modal').css("display", "none");
                $('#montocierrecheque-modal-idioma').css("display", "none");
                $('#montocierrecheque-modal').css("display", "none");
                
		$("#contenedorcierrecaja-modal").css("display", "none");
		//ID de registro
		$('#codigotipoproducto-modal').attr("value",null);
		$("#guardar").html("Guardar");
		$("#editar-nuevo").html("Apertura de Caja");                                 
            },
            error: function(event, request, settings){
             //   $.unblockUI();
                     mostarVentana("error-title","Ocurrio Error");
            }
        });	                
    });

    $('#guardar').click(function() {
//		 if(!confirm("Esta seguro de que desea almacenar los datos?"))
//				return;
		var data = obtenerJsonFormulario();
		if(data != null){
                    enviarParametrosRegistro(data);
		}
     });
    validarNumerosCampo();
});
function validarNumerosCampo(){
    $("#montoaperturacaja-modal").keydown(function(event) {
	   // Allow: backspace, delete, tab, escape, and enter
	   if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
			// Allow: Ctrl+A
		   (event.keyCode == 65 && event.ctrlKey === true) ||
			// Allow: home, end, left, right
		   (event.keyCode >= 35 && event.keyCode <= 39)) {
				// let it happen, don't do anything
				return;
	   }
	   else {
		   // Ensure that it is a number and stop the keypress
		   if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
			   event.preventDefault();
		   }
	   }
    });
    $("#montocierrecaja-modal").keydown(function(event) {
	   // Allow: backspace, delete, tab, escape, and enter
	   if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
			// Allow: Ctrl+A
		   (event.keyCode == 65 && event.ctrlKey === true) ||
			// Allow: home, end, left, right
		   (event.keyCode >= 35 && event.keyCode <= 39)) {
				// let it happen, don't do anything
				return;
	   }
	   else {
		   // Ensure that it is a number and stop the keypress
		   if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
			   event.preventDefault();
		   }
	   }
    });    
}
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
	if(data.cod_caja !== null && data.cod_caja.length !== 0){
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
        		mostarVentana("success-block-title","Los datos han sido almacenados exitosamente");
        		$('#modalEditar').hide();
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
	var mensaje = '';
	var error = 0;
	if($('#codigousuariocaja-modal').attr("value") == null || $('#codigousuariocaja-modal').attr("value").length == 0){
    	mensaje+= ' | No se identifico el usuario de logeo | ';
		error = 1;
	}
	if($('#montoaperturacaja-modal').attr("value") == null || $('#montoaperturacaja-modal').attr("value").length == 0){
		mensaje+= ' | Ingrese un monto de apertura | ';
		$('#montoaperturacaja-modal').attr("required", "required");
		error = 1;
    }
	if(($.trim($('#codcaja-modal').attr("value")) != '' || $('#codcaja-modal').attr("value").length != 0) &&
		($('#montocierrecaja-modal').attr("value") == null || $('#montocierrecaja-modal').attr("value").length == 0 )){
		mensaje+= ' | Ingrese un monto de cierre efectivo| ';
		$('#montocierrecaja-modal').attr("required", "required");
		error = 1;
    }	
	if(($.trim($('#codcaja-modal').attr("value")) != '' || $('#codcaja-modal').attr("value").length != 0) &&
		($('#montocierrecheque-modal').attr("value") == null || $('#montocierrecheque-modal').attr("value").length == 0 )){
		mensaje+= ' | Ingrese un monto de cierre cheque| ';
		$('#montocierrecheque-modal').attr("required", "required");
		error = 1;
    }	
    if(error == 1){
		mostarVentana("warning-registro",mensaje);
		return null;            
    }else {
		//LOS CAMPOS DEBEN LLAMARSE IGUAL QUE EN gridTipoInsumo.js
		jsonObject.cod_caja = $('#codcaja-modal').attr("value");
		jsonObject.cod_usuario_caja = $('#codigousuariocaja-modal').attr("value");
		jsonObject.monto_caja_apertura = $('#montoaperturacaja-modal').attr("value");	
		jsonObject.monto_caja_cierre_efectivo = $('#montocierrecaja-modal').attr("value");
		jsonObject.monto_caja_cierre_cheque = $('#montocierrecheque-modal').attr("value");
		jsonObject.monto_entrante_efectivo = $('#montoentranteefectivo-modal').attr("value");
		jsonObject.monto_saliente_efectivo = $('#montosalienteefectivo-modal').attr("value");
		jsonObject.monto_entrante_cheque = $('#montoentrantecheque-modal').attr("value");
		jsonObject.monto_saliente_cheque = $('#montosalientecheque-modal').attr("value");
                jsonObject.fecha_hora_apertura = $('#fechaaperturacaja-modal').attr("value");
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
     $("#warning-block-title").hide(500);
	//$("#warning-block-registro-listado").hide(500);
}

function ocultarSuccessBlockTitle(){
	$("#success-block-title").hide(3500);
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
	} else if(box == "warning-block-title") {
		$("#warning-message-title").text(mensaje);
		$("#warning-block-title").show();
		setTimeout("ocultarWarningBlockTitle()",5000);
	} else if(box == "success-block-title") {
//		console.log('entro');
		$("#success-message-title").text(mensaje);
		$("#success-block-title").show();
		setTimeout("ocultarSuccessBlockTitle()",5000);
	} else if(box == "warning-registro") {
		$("#warning-message").text(mensaje);
		$("#warning-block").show();
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
            mostarVentana("info","No se encontraron registros con los parametros ingresados");
        }
    });
}

function editarRegistro(parametros){
	limpiarFormulario();
        if(parametros.arqueo_caja == 'S'){
            //alert("La caja ya se encuentra arqueada!!");
            mostarVentana("warning-block-title","La caja ya se encuentra arqueada!!");
            return;
        }
        $('#montoentrantecaja-modal-idioma').css("display", "block");
        $('#montoentranteefectivo-modal').css("display", "block");
        $('#montosalientecaja-modal-idioma').css("display", "block");
        $('#montosalienteefectivo-modal').css("display", "block");
        $('#montoentrantecheque-label').css("display", "block");
        $('#montoentrantecheque-modal').css("display", "block");
        $('#montosalientecheque-label').css("display", "block");
        $('#montosalientecheque-modal').css("display", "block");
        $('#fechacierrecaja-modal-idioma').css("display", "block");
        $('#fechacierrecaja-modal').css("display", "block");
        $('#montocierrecaja-modal-idioma').css("display", "block");
        $('#montocierrecaja-modal').css("display", "block");
        $('#montocierrecheque-modal-idioma').css("display", "block");
        $('#montocierrecheque-modal').css("display", "block");
        
	$("#modalEditar").show();
	$("#editar-nuevo").html("Cierre de Caja");
	$("#codcaja-modal").attr("value",parametros.cod_caja);
	cargarCierreCaja();
	$("#guardar-registro").html("Cerrar Caja");        
}

function limpiarFormulario(){
	$("#error-block-modal").hide();
	$("#warning-block").hide();
	$("#warning-block-registro").hide();
	$("#success-block").hide();
	$("#codcaja-modal").attr("value",null);
	$("#codigousuariocaja-modal").attr("value",null);
	$("#nombreusuariocaja-modal").attr("value",null);
	$("#fechaaperturacaja-modal").attr("value",null);
	$("#montoaperturacaja-modal").attr("value",null);
	$("#montocierrecaja-modal").attr("value",null);
	$("#montoentrantecaja-modal").attr("value",null);
	$("#montosalientecaja-modal").attr("value",null);
        $("#montocierrecheque-modal").attr("value",null);        
}



function obtenerJsonBuscar(){
	var jsonObject = new Object();

	if($('#descripciontipoproducto-filtro').attr("value") != null && $('#descripciontipoproducto-filtro').attr("value").length != 0){
		jsonObject.descripcion = $('#descripciontipoproducto-filtro').attr("value");
	}

	var dataString = JSON.stringify(jsonObject);
	return dataString;
}

function cargarUsuarioCaja(){
	
//	alert('Unidad Medida');
	$.ajax({
        url: table+'/usuariocajadata',
        type: 'post',
        dataType: 'json',
        async : false,
        success: function(respuesta){            
        	if(respuesta== 'error'){
        		mostarVentana("error-title",mostrarError("OcurrioError"));
        	}else{
				$("#codigousuariocaja-modal").attr("value",respuesta.cod_usuario);       		
				$("#nombreusuariocaja-modal").attr("value",respuesta.nombre_apellido);  
				$("#fechaaperturacaja-modal").attr("value",respuesta.fechaaperturacaja);   
				$('#montoaperturacaja-modal').attr("readonly", false);				
        	}
        },
        error: function(event, request, settings){
         //   $.unblockUI();
        	 alert(mostrarError("OcurrioError"));
        }
    });	
}

function cajaAbierta(){
	
//	alert('Unidad Medida');
	$.ajax({
        url: table+'/cajaabiertadata',
        type: 'post',
        dataType: 'json',
        async : false,
        success: function(respuesta){            
        	if(respuesta.resultado == 'error'){
        		mostarVentana("error-title","Ocurrio Error");
        	}else if(respuesta.resultado != 'cerrado'){
                    //alert("Ya existe una caja abierta. Usuario : "+respuesta.nombre_apellido+" Fecha : "+respuesta.fecha_hora_apertura);
                    //exit;
                    mostarVentana("warning-block-title","Ya existe una caja abierta. Usuario : "+respuesta.nombre_apellido+" Fecha : "+respuesta.fecha_hora_apertura);
                    return;                                
        	}
        },
        error: function(event, request, settings){
         //   $.unblockUI();
        	 mostarVentana("error-title","Ocurrio Error");
        }
    });	
}

function cargarCierreCaja(){	
//	alert('Unidad Medida');	
	var jsonObject = new Object();
	jsonObject.cod_caja = $('#codcaja-modal').attr("value");
	var dataString = JSON.stringify(jsonObject);	
	$.ajax({
        url: table+'/cierrecajadata',
		data: {"parametros":dataString},
        type: 'post',
        dataType: 'json',
        async : false,
        success: function(respuesta){            
        	if(respuesta== 'error'){
        		mostarVentana("error-title",mostrarError("OcurrioError"));
        	}else{
				$("#codigousuariocaja-modal").attr("value",respuesta.cod_usuario_caja);       		
				$("#nombreusuariocaja-modal").attr("value",respuesta.nombre_apellido);  
				$("#fechaaperturacaja-modal").attr("value",respuesta.fecha_hora_apertura);
				$("#montoaperturacaja-modal").attr("value",respuesta.monto_caja_apertura);
				$("#fechacierrecaja-modal").attr("value",respuesta.fecha_hora_cierre);
				$('#montoaperturacaja-modal').attr("readonly", true);	
				$("#montoentranteefectivo-modal").attr("value",respuesta.monto_entrante_efectivo);
				$("#montosalienteefectivo-modal").attr("value",respuesta.monto_saliente_efectivo);
				$('#montoentranteefectivo-modal').attr("readonly", true);
				$('#montosalienteefectivo-modal').attr("readonly", true);
				$("#montoentrantecheque-modal").attr("value",respuesta.monto_entrante_cheque);
				$("#montosalientecheque-modal").attr("value",respuesta.monto_saliente_cheque);
				$('#montoentrantecheque-modal').attr("readonly", true);
				$('#montosalientecheque-modal').attr("readonly", true);				
        	}
        },
        error: function(event, request, settings){
         //   $.unblockUI();
        	 alert("OcurrioError");
        }
    });	
}