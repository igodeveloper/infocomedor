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
                $.ajax({
                    url: table+'/cajaabiertadata',
                    type: 'post',
                    dataType: 'json',
                    async : false,
                    success: function(respuesta){            
                            if(respuesta.resultado == 'error'){
                                    mostarVentana("error-title",mostrarError("OcurrioError"));
                            }else if(respuesta.resultado != 'cerrado'){
                                limpiarFormulario();
                                //cajaAbierta();                
                                cargarTipoMovimiento();            
                                $('#modalEditar').show();
                        //ID de registro
                                $('#codmovcaja-modal').attr("value",null);
                                $("#guardar").html("Guardar");
                                $("#editar-nuevo").html("Nuevo Registro");                                
                                
                                $('#codcaja-modal').attr("value",respuesta.cod_caja);
                                $('#codigousuariocaja-modal').attr("value",respuesta.cod_usuario);
                                $('#nombreusuariocaja-modal').attr("value",respuesta.nombre_apellido);
                                $('#fechaaperturacaja-modal').attr("value",respuesta.fecha_hora_apertura);
                            }else if(respuesta.resultado == 'cerrado'){
                                mostarVentana("warning-block-title","No existe caja abierta");
                            }
                    },
                    error: function(event, request, settings){
                     //   $.unblockUI();
                             alert(mostrarError("OcurrioError"));
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
	if(data.cod_mov_caja !== null && data.cod_mov_caja.length !== 0){
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
	if($('#codcaja-modal').attr("value") == null || $('#codcaja-modal').attr("value").length == 0){
    	mensaje+= ' | No se identifico la caja abierta | ';
		error = 1;
	}
	if($('#codtipomov-modal').attr("value") == -1 || $('#codtipomov-modal').attr("value").length == 0){
		mensaje+= ' | Debe seleccionar un concepto | ';
		$('#codtipomov-modal').attr("required", "required");
		error = 1;
    }
	if($('#montomov-modal').attr("value") == -1 || $('#montomov-modal').attr("value").length == 0){
		mensaje+= ' | Debe un monto | ';
		$('#montomov-modal').attr("required", "required");
		error = 1;
    }	
    if(error == 1){
		mostarVentana("warning-registro",mensaje);
		return null;            
    }else {
		//LOS CAMPOS DEBEN LLAMARSE IGUAL QUE EN gridTipoInsumo.js
		jsonObject.cod_mov_caja = $('#codmovcaja-modal').attr("value");
		jsonObject.cod_caja = $('#codcaja-modal').attr("value");
		jsonObject.cod_tipo_mov = $("#codtipomov-modal option:selected").val();
		jsonObject.monto_mov = $('#montomov-modal').attr("value");		
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
	$("#modalEditar").show();
	$("#editar-nuevo").html("Editar Registro");
	$("#codigotipoproducto-modal").attr("value",parametros.COD_TIPO_PRODUCTO);
	$("#descripciontipoproducto-modal").attr("value",parametros.TIPO_PRODUCTO_DESCRIPCION);
	$("#guardar-registro").html("Modificar");
}

function limpiarFormulario(){
	$("#error-block-modal").hide();
	$("#warning-block").hide();
	$("#warning-block-registro").hide();
	$("#success-block").hide();
	$("#codcaja-modal").attr("value",null);
	$("#codmovcaja-modal").attr("value",null);
	$("#codigousuariocaja-modal").attr("value",null);
	$("#nombreusuariocaja-modal").attr("value",null);
	$("#fechaaperturacaja-modal").attr("value",null);
	$("#montomov-modal").attr("value",null);	
}



function obtenerJsonBuscar(){
	var jsonObject = new Object();

	if($('#descripciontipoproducto-filtro').attr("value") != null && $('#descripciontipoproducto-filtro').attr("value").length != 0){
		jsonObject.descripcion = $('#descripciontipoproducto-filtro').attr("value");
	}

	var dataString = JSON.stringify(jsonObject);
	return dataString;
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
        		mostarVentana("error-title",mostrarError("OcurrioError"));
        	}else if(respuesta.resultado != 'cerrado'){
				$('#codcaja-modal').attr("value",respuesta.cod_caja);
				$('#codigousuariocaja-modal').attr("value",respuesta.cod_usuario);
				$('#nombreusuariocaja-modal').attr("value",respuesta.nombre_apellido);
				$('#fechaaperturacaja-modal').attr("value",respuesta.fecha_hora_apertura);
        	}else if(respuesta.resultado == 'cerrado'){
                    mostarVentana("warning-block-title","No existe caja abierta");
                }
        },
        error: function(event, request, settings){
         //   $.unblockUI();
        	 alert(mostrarError("OcurrioError"));
        }
    });	
}

function cargarTipoMovimiento(){
	
//	alert('Tipo Producto');
	$.ajax({
        url: table+'/tipomovimientodata',
        type: 'post',
        dataType: 'html',
        async : false,
        success: function(respuesta){
        	if(respuesta== 'error'){
        		mostarVentana("error-title",mostrarError("OcurrioError"));
        	}else{
            	$("#codtipomov-modal").html(respuesta);       		
        	}
        },
        error: function(event, request, settings){
         //   $.unblockUI();
        	 alert(mostrarError("OcurrioError"));
        }
    });	
}