$().ready(function() {
	loadAutocompleteProducto();
    $("#buscar-registro").click(function() {
        buscar();
    });

    $("#cerrar-bot").click(function() {
        $('#modalEditar').hide();
        CleanFormItems();
    });

    $("#cancelar-bot").click(function() {
        $('#modalEditar').hide();
        CleanFormItems();
    });

    $("#nuevo-registro").click(function() {
        $('#modalEditar').show();
        $("#guardar").html("Guardar");
        $(".btn-compra").show();
        $("#detalle-receta").show();
        $("#editar-nuevo").html("Nuevo Registro");
        CleanFormItems();
        $("#grillaRegistroModal").jqGrid("clearGridData");
        $('#codproducto-item').attr("disabled", false);
        $('#descripcionproducto-item').attr("disabled", false);
        $("#guardar").show();
        $("#addProductos").show();
        $("#addItem").attr("disabled", true);  
        $("#unidadmedida-item").attr("disabled", true);
       // loadAutocompleteProducto();
    });

    $("#addItem").click(function() {
        addItem();
    });
    
    $("#reloadItem").click(function() {
    	CleanFormItems();
    });

    $("#guardar").click(function() {
//        if (!confirm("Esta seguro de que desea almacenar los datos?"))
//            return;
        var data = obtenerGrid();
        if (data !== null) {
            enviarParametrosRegistros(data);
        }
    });

}); // cerramos el ready de js

function validarNumerosLetrasPorcentageEspacio(e) { // 1
    var te;
    if (document.all) {
        if (e.keyCode == 37)
            return true; // %
        if (e.keyCode == 38)
            return true; // &
        if (e.keyCode == 63)
            return false; // guion bajo
        if (e.keyCode == 95)
            return false; // guion bajo
        if (e.keyCode == 8)
            return true; // back spacebar
        if (e.keyCode == 32)
            return true; // space bar
        te = String.fromCharCode(e.keyCode); // 5
    } else {
        if (e.which == 37)
            return true; // %
        if (e.which == 38)
            return true; // &
        if (e.which == 0)
            return true; // izquierda,derecha,arriba,abajo
        if (e.which == 95)
            return false; // guion bajo
        if (e.which == 8)
            return true; // back space bar
        if (e.which == 32)
            return true; // space bar
        te = String.fromCharCode(e.which); // 5
    }
    patron = /\w/;
    return patron.test(te); // 6
}

function ocultarSuccessBlock() {
    $("#success-block").hide(500);
}

function ocultarInfoClean() {
    $("#info-block-listado").hide(500);
}

function ocultarErrorBlock() {
    $("#error-block").hide(500);
}

function ocultarWarningBlock() {
    $("#warning-block").hide(500);
}

function ocultarWarningBlockTitle() {
    $("#warning-block-title").hide(500);
}
function ocultarWarningBlockPagos() {
    $("#warning-block-pagos").hide(300);
}

function ocultarSuccessBlockTitle() {
    $("#success-block-title").hide(500);
}
function addrequiredattr(id,focus){
	$('#'+id).attr("required", "required");
	if(focus == 1)
		$('#'+id).focus();
}

function mostarVentana(box, mensaje) {
    $("#success-block").hide();
    $("#info-block-listado").hide();
    if (box == "warning") {
        $("#warning-message").text(mensaje);
        $("#warning-block").show();
        setTimeout("ocultarWarningBlock()", 5000);
    } else if (box == "warning-title") {
        $("#warning-message-title").text(mensaje);
        $("#warning-block-title").show();
        setTimeout("ocultarWarningBlockTitle()", 5000);
    } else if (box == "success-title") {
        $("#success-message-title").text(mensaje);
        $("#success-block-title").show();
        setTimeout("ocultarSuccessBlockTitle()", 5000);
    } else if (box == "info") {
        $("#info-message").text(mensaje);
        $("#info-block-listado").show(500);
        setTimeout("ocultarInfoClean()", 5000);
    } else if (box == "error") {
        $("#error-block").text(mensaje);
        $("#error-block").show(500);
        setTimeout("ocultarErrorBlock()", 5000);
    } else if (box == "error-title") {
        $("#error-message").text(mensaje);
        $("#error-block-title").show(500);
        setTimeout("ocultarErrorBlockTitle()", 5000);
    }
    
}
function ocultarErrorBlockTitle() {
    $("#error-block-title").hide(500);
}

function validate(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(key);
    var regex = /[0-9]|\./;
    if (!regex.test(key)) {
        theEvent.returnValue = false;
        if (theEvent.preventDefault)
            theEvent.preventDefault();
    }
}


function mostrarSuccessBlock() {
    $("#success-block").show(500);
    setTimeout("ocultarSuccessBlock()", 5000);
}

function addItem() {
//    alert("ingreso");
    var data = obtenerJsonDetalles();
//    alert(JSON.stringify(data));
    if (data !== null) {
        var rows = jQuery("#grillaRegistroModal").jqGrid('getRowData');
        jQuery("#grillaRegistroModal").jqGrid('addRowData', (rows.length) + 1, data);
        CleanFormItems();
        $('#addItem').attr("disabled", true);
    }

}

function obtenerJsonDetalles() {
    var jsonObject = new Object();
    jsonObject.codproducto = $('#codproducto-item').attr("value");
    jsonObject.descripcionproducto = $('#descripcionproducto-item').attr("value");
    jsonObject.cantidad = $('#cantidad-item').attr("value");
    jsonObject.codUnidadMedida = $('#codUnidadMedida-item').attr("value");
    jsonObject.unidadmedida = $("#unidadmedida-item").attr("value");

    var mensaje = '';
    var focus = 0;
        
    if (jsonObject.codproducto === "" || jsonObject.codproducto === null) {
    	mensaje+= ' | Ingrese el producto y valide ';
    	focus++;
    	addrequiredattr('codproducto-item',focus);
    	addrequiredattr('descripcionproducto-item',focus);
    } 
    if (jsonObject.cantidad === "" || jsonObject.cantidad === "0" || jsonObject.cantidad === null) {
    	mensaje+= ' | Ingrese la cantidad ';
    	focus++;
    	addrequiredattr('cantidad-item',focus);
    } 
    
    
    if(mensaje != ''){
    	mensaje+= ' |'; 
    	return null;
    }else {
        jsonObject.codproducto = parseInt(jsonObject.codproducto);
        jsonObject.cantidad = parseFloat(jsonObject.cantidad);
//        alert(JSON.stringify(jsonObject));
        return jsonObject;
    }

}


function loadAutocompleteProducto() {
    $.getJSON("/compras/compra2/productodata", function(data) {
        var descripcionProducto = [];
        var codigoProducto = [];
        var descripcionProductoFiltro = [];
        
        
        $(data).each(function(key, value) {
        	if(value.COD_PRODUCTO_TIPO > 1){ // solo productos que no sean materia prima, los cuales se dan de alta por compra
        		descripcionProducto.push(value.PRODUCTO_DESC);
        		codigoProducto.push(value.COD_PRODUCTO);
        	}
        	descripcionProductoFiltro.push(value.PRODUCTO_DESC);
        	
        });

        $("#codproducto-item").autocomplete({
            source: codigoProducto
        });
        $("#descripcionproducto-item").autocomplete({
            source: descripcionProducto
        });
        $("#descripcionproductofinal-filtro").autocomplete({
            source: descripcionProductoFiltro
        });

    });

}

function productvalidation(data) {
    var dataString = new Object();
    dataString.value = "vacio";
    dataString.reference = data;
    switch (data) {
        case 'cod':
            {
                dataString.value = $('#codproducto-item').attr("value");
//                    alert(data);
                break;
            }

        case 'descripcion':
            {
                dataString.value = $('#descripcionproducto-item').attr("value");
//                      alert(data);
                break;
            }
    }
    if (dataString.value !== "") {
        $.ajax({
            url: '/compras/compra2/productvalidationdata',
            type: 'post',
            dataType: 'json',
            data: {
                "parametro": dataString
            },
            async: false,
            success: function(respuesta) {
                 console.log(respuesta);
                if(respuesta != null){
                    $('#codproducto-item').attr("value", respuesta.cod);
                    $('#descripcionproducto-item').attr("value", respuesta.descripcion);
                    $('#codUnidadMedida-item').attr("value", respuesta.unimedcod);
                    $('#unidadmedida-item').attr("value", respuesta.unimeddesc);
                    $('#codproducto-item').attr("disabled", true);
                    $('#descripcionproducto-item').attr("disabled", true);
                    $("#addItem").removeAttr('disabled');
                 }else{
                    
                    mostarVentana("warning","No se encontraron datos, verifique los datos ingresados");
                    addrequiredattr('codproducto-item',1);
                }

            },
            error: function(event, request, settings) {
                mostarVentana("error", "Su servidos esta lento, intente de nuevo mas tarde");
            }
        });
    } else {
        mostarVentana("warning", "Ingrese datos para la validacion");
    }

}

function enviarParametrosRegistros(data) {
    
    var dataGrilla = JSON.stringify(data.grilla);
    var dataGrillaLength= JSON.stringify(data.grillalength);
    
    var urlenvio = '';
    urlenvio = '/produccion/productofinal/guardar';
    
    $.ajax({
        url: urlenvio,
        type: 'post',
        data: {"dataGrilla": dataGrilla, "dataGrillaLength": dataGrillaLength},
        dataType: 'JSON',
        async: true,
        success: function(respuesta) {
//                alert(respuesta+"hola");
            if (respuesta == null) {
                mostarVentana("error", "TIMEOUT");
            } else if (respuesta.result == "EXITO") {
                mostarVentana("success-title", "Datos almacenados exitosamente");
                $('#modalEditar').hide();
                $("#grillaRegistro").trigger("reloadGrid");
                CleanFormItems();
            } else if (respuesta.result == "ERROR") {
                if (respuesta.code == 23505) {
                    mostarVentana("warning", "Datos Duplicados");
                } else {
                    mostarVentana("warning", "Verifique los datos ingresados");
                }
            }
        },
        error: function(event, request, settings) {
            alert(mostrarError("OCURRIO UN ERROR"));
        }
    });
}

function buscar() {
    var dataJsonBusqueda = JSON.stringify(filtrosbusqueda());

    $.blockUI({
        message: "Aguarde un Momento"
    });

    $.ajax({
        url: '/produccion/productofinal/buscar',
        type: 'post',
        data: {
            "dataJsonBusqueda": dataJsonBusqueda
        },
        dataType: 'json',
        async: false,
        success: function(respuesta) {
//                            $("#grillaCompras").jqGrid("clearGridData");
            $("#grillaRegistro")[0].addJSONData(respuesta);
           
            $.unblockUI();
        },
        error: function(event, request, settings) {
            $.unblockUI();
            alert(mostrarError("OCURRIO UN ERROR"));
        }
    });
}

/*
 * ESTABLECEMOS LOS FILTROS Y LAS LIMPIEZAS 
 * 
 * 
 * */

function obtenerGrid() {
    var jsonObject = new Object();
    var dataObjectGrillaDetalle = new Object();
    

    dataObjectGrillaDetalle = jQuery("#grillaRegistroModal").jqGrid('getRowData'); // saca datos de la grilla en formato json
    var rowsGrid = jQuery("#grillaRegistroModal").jqGrid('getRowData'); // saca tododos los datos, vamos a usar para sacar el length
    
    var mensaje = '';
    var focus = 0;
    
    
    if (rowsGrid.length == 0) {
    	mensaje+= ' | Ingrese los detalles de la receta ';
    	addrequiredattr('codproducto-item',focus++);
    	addrequiredattr('descripcionproducto-item',focus++);
    	addrequiredattr('cantidad-item',focus++);
    }  
    
    if(mensaje != ''){
    	mensaje+= ' |';
    	mostarVentana("warning", mensaje);
    	return null;
    	
    } else{
    
    	jsonObject.grilla = dataObjectGrillaDetalle;
    	jsonObject.grillalength = rowsGrid.length;
    //    alert(JSON.stringify(jsonObject));
    	return jsonObject;
    }
}


function filtrosbusqueda() {
    var obj = new Object();
    obj.descripcionproducto = $('#descripcionproductofinal-filtro').attr("value");
    obj.descripciontipoproducto = $('#descripciontipoproducto-filtro').attr("value");
    return obj;
}


function CleanFormItems() {
	$('#codproducto-item').attr("disabled", false);
    $('#descripcionproducto-item').attr("disabled", false);
    $('#codproducto-item').attr("value", null);
    $('#descripcionproducto-item').attr("value", null);
    $('#codUnidadMedida-item').attr("value", null);
    $("#unidadmedida-item").attr("value", null);
    $("#cantidad-item").attr("value", null);  
	}



