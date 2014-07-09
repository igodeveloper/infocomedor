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
		$('#codigoproducto-modal').attr("value",null);
		$("#guardar").html("Guardar");
		$("#editar-nuevo").html("Nuevo Registro");
		 
		
	});

	$('#guardar').click(function() {
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
	if(data.COD_PRODUCTO !== null && data.COD_PRODUCTO.length !== 0){
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
        			mostarVentana("error-modal","Ha ocurrido un error");
        		}
        	}
        	$.unblockUI();
        },
        error: function(event, request, settings){
        	mostarVentana("error-registro-listado","Ha ocurrido un error");
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

	if($('#descripcionproducto-modal').attr("value") == null || $('#descripcionproducto-modal').attr("value").length == 0){
        mensaje+= ' | Descripci\u00F3n ';
    	focus++;
    	addrequiredattr('descripcionproducto-modal',focus); 
	}
	if($("#tipoproducto-modal" ).val() == -1){
        mensaje+= ' | Tipo Producto ';
    	focus++;
    	addrequiredattr('tipoproducto-modal',focus); 
    }
    if($("#unidadmedida-modal" ).val() == -1){
        mensaje+= ' | Unidad de Medida ';
    	focus++;
    	addrequiredattr('unidadmedida-modal',focus);    }
    if($("#impuesto-modal" ).val() == -1){
        mensaje+= ' | Impuesto ';
    	focus++;
    	addrequiredattr('impuesto-modal',focus);
    }
    if($("#precioventa-modal" ).attr("value") == null || $('#precioventa-modal').attr("value").length == 0){
        mensaje+= ' | Precio ';
    	focus++;
    	addrequiredattr('precioventa-modal',focus);
	}


	if (mensaje != 'Ingrese los campos: '){
		mensaje+= ' |';
		mostarVentana("warning-registro", mensaje);
		return null;
	}else {
		jsonObject.COD_PRODUCTO = $('#codigoproducto-modal').attr("value");
        jsonObject.PRODUCTO_DESC = $('#descripcionproducto-modal').attr("value");
        jsonObject.COD_PRODUCTO_TIPO = $("#tipoproducto-modal").val();
        jsonObject.COD_UNIDAD_MEDIDA = $("#unidadmedida-modal").val();
        jsonObject.COD_RECETA = $("#receta-modal").val();
        jsonObject.COD_IMPUESTO = $("#impuesto-modal").val();
        jsonObject.PRECIO_VENTA = $("#precioventa-modal").val();
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
	$("#success-block-title").hide(500);
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
		$("#warning-message-title").text(mensaje);
		$("#warning-block-title").show();
		setTimeout("ocultarWarningBlockTitle()",5000);
	} else if(box == "success-registro-listado") {
		$("#success-block-title").text(mensaje);
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
        	$.unblockUI();
        	 $("#grillaRegistro").jqGrid("clearGridData", true);
        	jQuery("#grillaRegistro").setGridParam({grouping: false});
        	$("#grillaRegistro")[0].addJSONData(JSON.parse(respuesta));
        	// $("#grillaRegistro")[0].addJSONData(respuesta);
        	var obj = JSON.parse(respuesta);
        	if(obj.mensajeSinFilas == true){
        		mostarVentana("info","No se encontraron registros con los parametros ingresados");
        		jQuery("#grillaRegistro").setGridParam({grouping: true});
        	}
        	$.unblockUI();
        	jQuery("#grillaRegistro").setGridParam({grouping: true});
        },
        error: function(event, request, settings){
            $.unblockUI();
            // alert("Ha ocurrido un error");
        }
    });
}

function editarRegistro(parametros){
	limpiarFormulario();
	$("#modalEditar").show();
	$("#editar-nuevo").html("Editar Registro");
//	alert(parametros.COD_PRODUCTO_TIPO);
	$("#codigoproducto-modal").attr("value",parametros.COD_PRODUCTO);
    $('#descripcionproducto-modal').attr("value",parametros.PRODUCTO_DESC);
    $("#tipoproducto-modal" ).val(parametros.COD_PRODUCTO_TIPO);
    $("#unidadmedida-modal" ).val(parametros.COD_UNIDAD_MEDIDA);
    $("#receta-modal" ).val(parametros.COD_RECETA);
    $("#impuesto-modal" ).val(parametros.COD_IMPUESTO);
    $("#precioventa-modal" ).val(parametros.PRECIO_VENTA);
	$("#guardar-registro").html("Modificar");
}

function limpiarFormulario(){
	$("#error-block-modal").hide();
	$("#warning-block").hide();
	$("#warning-block-registro").hide();
	$("#success-block").hide();
	cargarTipoProducto();
	cargarUnidadMedida();
	cargarReceta();
	cargarImpuesto();
	$("#codigoproducto-modal").attr("value",null);
	$("#descripcionproducto-modal").attr("value",null);
	$("#precioventa-modal").attr("value",null);

}



function obtenerJsonBuscar(){
	var jsonObject = new Object();

	if($('#descripcionproducto-filtro').attr("value") != null && $('#descripcionproducto-filtro').attr("value").length != 0){
		jsonObject.descripcion = $('#descripcionproducto-filtro').attr("value");
	}
	if($('#descripciontipoproducto-filtro').attr("value") != null && $('#descripciontipoproducto-filtro').attr("value").length != 0){
		jsonObject.descripciontipoproducto = $('#descripciontipoproducto-filtro').attr("value");
	}

	var dataString = JSON.stringify(jsonObject);
	return dataString;
}

function cargarTipoProducto(){
	
//	alert('Tipo Producto');
	$.ajax({
        url: table+'/tipoproducto',
        type: 'post',
        dataType: 'html',
        async : false,
        success: function(respuesta){
        	if(respuesta== 'error'){
        		mostarVentana("error-title",mostrarError("OcurrioError"));
        	}else{
            	$("#tipoproducto-modal").html(respuesta);       		
        	}
        },
        error: function(event, request, settings){
         //   $.unblockUI();
        	 alert(mostrarError("OcurrioError"));
        }
    });	
}
function cargarUnidadMedida(){
	
//	alert('Unidad Medida');
	$.ajax({
        url: table+'/unidadmedida',
        type: 'post',
        dataType: 'html',
        async : false,
        success: function(respuesta){
        	if(respuesta== 'error'){
        		mostarVentana("error-title",mostrarError("OcurrioError"));
        	}else{
            	$("#unidadmedida-modal").html(respuesta);       		
        	}
        },
        error: function(event, request, settings){
         //   $.unblockUI();
        	 alert(mostrarError("OcurrioError"));
        }
    });	
}

function cargarReceta(){
	
//	alert('Tipo Producto');
	$.ajax({
        url: table+'/receta',
        type: 'post',
        dataType: 'html',
        async : false,
        success: function(respuesta){
        	if(respuesta== 'error'){
    
        	}else{
            	$("#receta-modal").html(respuesta);       		
        	}
        },
        error: function(event, request, settings){

        }
    });	
}

function cargarImpuesto(){
	
//	alert('Tipo Producto');
	$.ajax({
        url: table+'/impuesto',
        type: 'post',
        dataType: 'html',
        async : false,
        success: function(respuesta){
        	if(respuesta== 'error'){
        		mostarVentana("error-title",mostrarError("OcurrioError"));
        	}else{
            	$("#impuesto-modal").html(respuesta);       		
        	}
        },
        error: function(event, request, settings){
         //   $.unblockUI();
        	 alert(mostrarError("OcurrioError"));
        }
    });	
}