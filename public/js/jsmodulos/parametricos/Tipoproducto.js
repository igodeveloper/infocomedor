var pathname = window.location.pathname;
var table = pathname;
$().ready(function() {
	     jQuery('.just-number').keypress(function(tecla) {
        console.log(tecla.charCode);
        if(tecla.charCode < 48 || tecla.charCode > 57){
            if(tecla.charCode == 0 || tecla.charCode == 46){
                return true;
            } else{
                return false;
            }
        } 
    });
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
		$('#modalEditar').show();
                //ID de registro
		$('#codigotipoproducto-modal').attr("value",null);
		$("#guardar").html("Guardar");
		 $("#editar-nuevo").html("Nuevo Registro");
		 limpiarFormulario();
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
	if(data.COD_TIPO_PRODUCTO !== null && data.COD_TIPO_PRODUCTO.length !== 0){
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
	if($('#descripciontipoproducto-modal').attr("value") == null || $('#descripciontipoproducto-modal').attr("value").length == 0){
    	mensaje+= ' | Ingrese la descripci\u00F3n | ';
    	
    	addrequiredattr('descripciontipoproducto-modal',1);    
		mostarVentana("warning-registro",mensaje);
		return null;
	}else {
            //LOS CAMPOS DEBEN LLAMARSE IGUAL QUE EN gridTipoInsumo.js
			jsonObject.COD_TIPO_PRODUCTO = $('#codigotipoproducto-modal').attr("value");
            jsonObject.TIPO_PRODUCTO_DESCRIPCION = $('#descripciontipoproducto-modal').attr("value");
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
	$("#codigotipoproducto-modal").attr("value",null);
	$("#descripciontipoproducto-modal").attr("value",null);
}



function obtenerJsonBuscar(){
	var jsonObject = new Object();

	if($('#descripciontipoproducto-filtro').attr("value") != null && $('#descripciontipoproducto-filtro').attr("value").length != 0){
		jsonObject.descripcion = $('#descripciontipoproducto-filtro').attr("value");
	}

	var dataString = JSON.stringify(jsonObject);
	return dataString;
}
