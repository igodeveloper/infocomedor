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
    
    $("#buscar-registro").click(function() {
        buscar();
    });

    $("#cerrar-bot").click(function() {
        $('#modalEditar').hide();
        CleanFormItems('exit');
    });

    $("#cancelar-bot").click(function() {
        $('#modalEditar').hide();
        CleanFormItems('exit');
    });

    $("#nuevo-registro").click(function() {
        $('#modalEditar').show();
        $("#guardar").html("Guardar");
        $(".btn-compra").show();
        $("#detalle-receta").show();
        $("#editar-nuevo").html("Nuevo Registro");
        $('#codigo-receta').attr("value",0);
        CleanFormItems('exit');
        //$("#grillaRegistroModal").trigger("reloadGrid");
        $("#grillaRegistroModal").jqGrid("clearGridData");
        jQuery("#grillaRegistroModal").jqGrid('hideCol','RECETA_DET_ITEM');
        $('#codproducto-item').attr("disabled", false);
        $('#descripcionproducto-item').attr("disabled", false);
        $("#guardar").show();
        $("#addProductos").show();
        $("#addItem").attr("disabled", true);
        $('#cantidad-item').attr("value", 0);
        $("#unidadmedida-item").attr("disabled", true);
        loadAutocompleteProducto();
    });

    $("#addItem").click(function() {
        addItem();
    });
    
    $("#reloadItem").click(function() {
    	CleanFormItems(null);
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


function mostarVentana(box, mensaje) {
    $("#success-block").hide();
    $("#info-block-listado").hide();
    if (box == "warning") {
//    	console.log(box,mensaje);
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
        CleanFormItems(null);
    }

}

function obtenerJsonDetalles() {
    var jsonObject = new Object();
    jsonObject.codproducto = $('#codproducto-item').attr("value");
    jsonObject.descripcionproducto = $('#descripcionproducto-item').attr("value");
    jsonObject.cantidad = $('#cantidad-item').attr("value");
    jsonObject.codUnidadMedida = $('#codUnidadMedida-item').attr("value");
    jsonObject.unidadmedida = $("#unidadmedida-item").attr("value");
    
    var mensaje = 'Complete los campos:';
    var focus = 0;
    
    if (jsonObject.codproducto === "" || jsonObject.codproducto === null) {
    	mensaje+= ' | C\u00F3digo del producto ';
    	focus++;
    	addrequiredattr('codproducto-item',focus);
    } 
    if (jsonObject.descripcionproducto === "" || jsonObject.descripcionproducto === null) {
    	mensaje+= ' | Descripci\u00F3n del producto ';
    	focus++;
    	addrequiredattr('descripcionproducto-item',focus);
    } 
    if (jsonObject.cantidad === "" || jsonObject.cantidad === "0" || jsonObject.cantidad === null) {
    	mensaje+= ' | Cantidad';
    	focus++;
    	addrequiredattr('cantidad-item',focus);
    } 
    
    if(mensaje != 'Complete los campos:'){
    	mensaje+= ' |';
    	mostarVentana("warning", mensaje);
    	return null;
    }else {
        jsonObject.codproducto = parseInt(jsonObject.codproducto);
        jsonObject.cantidad = parseFloat(jsonObject.cantidad);
//        alert(JSON.stringify(jsonObject));
        return jsonObject;
    }
    
}


function loadAutocompleteProducto() {
    $.getJSON(table+"/productodata", function(data) {
        var descripcionProducto = [];
        var codigoProducto = [];

        $(data).each(function(key, value) {
            descripcionProducto.push(value.PRODUCTO_DESC);
            codigoProducto.push(value.COD_PRODUCTO);
        });

        $("#codproducto-item").autocomplete({
            source: codigoProducto
        });
        $("#descripcionproducto-item").autocomplete({
            source: descripcionProducto
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
            url: table+'/productvalidationdata',
            type: 'post',
            dataType: 'json',
            data: {
                "parametro": dataString
            },
            async: false,
            success: function(respuesta) {

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
                    mostarVentana("warning","No se encontraron datos, verifique los datos ingresados");
                    addrequiredattr('codproducto-item',1);
            }
        });
    } else {
        mostarVentana("warning","Inserte un valor antes de validar");
        addrequiredattr('codproducto-item',1);
    }

}

function enviarParametrosRegistros(data) {
    var dataReceta = JSON.stringify(data.Receta);
    var dataRecetaDetalle = JSON.stringify(data.RecetaDetalle);
    var dataRecetaDetalleItem = JSON.stringify(data.RecetaDetalleItem);
//    alert(data.Receta.codigoReceta);
    var urlenvio = '';
    urlenvio = table+'/guardar';
    
    $.ajax({
        url: urlenvio,
        type: 'post',
        data: {"dataReceta": dataReceta, "dataRecetaDetalle": dataRecetaDetalle, "dataRecetaDetalleItem": dataRecetaDetalleItem},
        dataType: 'JSON',
        async: true,
        success: function(respuesta) {
//                alert(respuesta+"hola");
            if (respuesta == null) {
                mostarVentana("error", "TIMEOUT");
            } else if (respuesta.result == "EXITO") {
                mostarVentana("success-title", "Los datos se almacenaron correctamente");
                $('#modalEditar').hide();
                $("#grillaRegistro").trigger("reloadGrid");
                CleanFormItems('exit');
            } else if (respuesta.result == "ERROR") {
                if (respuesta.code == 23505) {
                    mostarVentana("warning", "Los datos estan duplicados");
                } else {
                    mostarVentana("warning", "Verifique los datos ingresados");
                }
            }
        },
        error: function(event, request, settings) {
//            alert(mostrarError("OCURRIO UN ERROR"));
        }
    });
}

function buscar() {
    var dataJsonBusqueda = JSON.stringify(filtrosbusqueda());

    $.blockUI({
        message: "Aguarde un Momento"
    });

    $.ajax({
        url: table+'/buscar',
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
//            alert(mostrarError("OCURRIO UN ERROR"));
        }
    });
}


function editarRegistro(parametros) {
	CleanFormItems('exit');
	jQuery("#grillaRegistroModal").jqGrid('showCol','RECETA_DET_ITEM');
    $("#modalEditar").show();
    $("#editar-nuevo").html("Editar Registro");
    $("#guardar").html("Modificar");
    $("#guardar").show();
    loadAutocompleteProducto();
//    $("#detalle-receta").hide();
    $('#codigo-receta').attr("value", parametros.COD_RECETA);
    $('#descripcionreceta-modal').attr("value", parametros.RECETA_DESCRIPCION);
    $("#grillaRegistroModal").jqGrid("clearGridData");
    cargargrillamodal(parametros);
}

function cargargrillamodal(parametros){
    $.ajax({
        url: table+'/modaleditar',
        type: 'post',
        data: {
            "codigo_receta": parametros.COD_RECETA
        },
        dataType: 'json',
        async: false,
        success: function(respuesta) {
//        	alert(JSON.stringify(respuesta));
            for (var i = 0; i < respuesta.length; i++) {
                var insertdetails = new Object();
                insertdetails.codproducto = respuesta[i].codproducto;
                insertdetails.descripcionproducto = respuesta[i].descripcionproducto;
                insertdetails.cantidad = respuesta[i].cantidad;
                insertdetails.codUnidadMedida = respuesta[i].codUnidadMedida;
                insertdetails.unidadmedida = respuesta[i].unidadmedida;
                insertdetails.COD_RECETA = respuesta[i].COD_RECETA;
                insertdetails.RECETA_DET_ITEM = respuesta[i].RECETA_DET_ITEM;

                var rows = jQuery("#grillaRegistroModal").jqGrid('getRowData');
                jQuery("#grillaRegistroModal").jqGrid('addRowData', (rows.length) + 1, insertdetails);
//                                $("#grillaComprasModal")[0].addJSONData(respuesta[i]);
//                                console.log(respuesta[i]);
            }

            $.unblockUI();
        },
        error: function(event, request, settings) {
            $.unblockUI();
//            alert(mostrarError("OCURRIO UN ERROR"));
        }
    });
}

function addrequiredattr(id,focus){
	$('#'+id).attr("required", "required");
	if(focus == 1)
		$('#'+id).focus();
}

function obtenerGrid() {
    var jsonObject = new Object();
    var dataObjectRecetaDet = new Object();
    var dataObjectReceta = new Object();
    
    dataObjectRecetaDet = jQuery("#grillaRegistroModal").jqGrid('getRowData'); // saca datos de la grilla en formato json
    var rowsGrid = jQuery("#grillaRegistroModal").jqGrid('getRowData'); // saca tododos los datos, vamos a usar para sacar el length
    
    
    
    var mensaje = 'Ingrese los campos: ';
    var focus = 0;
    
    
    if ($('#descripcionreceta-modal').attr("value") == "" || $('#descripcionreceta-modal').attr("value") == null) {
    	mensaje+= ' | Descripci\u00F3n de la receta';
    	focus++;
    	addrequiredattr('descripcionreceta-modal',focus);
    }  
    
    if (rowsGrid.length == 0) {
    	mensaje+= ' | Ingrese los detalles de la receta ';
    	addrequiredattr('codproducto-item',1);
    	addrequiredattr('descripcionproducto-item',focus++);
    	addrequiredattr('cantidad-item',focus++);
    }  
    
    if(mensaje != 'Ingrese los campos: '){
    	mensaje+= ' |';
    	mostarVentana("warning", mensaje);
    	return null;
    	
    }else {
    	dataObjectReceta.codigoReceta = $('#codigo-receta').attr("value");
        dataObjectReceta.descripcionReceta = $('#descripcionreceta-modal').attr("value");

        jsonObject.Receta = dataObjectReceta;
        jsonObject.RecetaDetalle = dataObjectRecetaDet;
        jsonObject.RecetaDetalleItem = rowsGrid.length;
//        alert(JSON.stringify(jsonObject));
        return jsonObject;
    }
    
    
}


function filtrosbusqueda() {
    var obj = new Object();
    obj.descripcionreceta = $('#descripcionreceta-filtro').attr("value");
    return obj;
}


function CleanFormItems(action) {
	if(action == 'exit'){
		$('#descripcionreceta-modal').attr("value", null);
	}
	$('#codproducto-item').attr("disabled", false);
    $('#descripcionproducto-item').attr("disabled", false);
    $('#codproducto-item').attr("value", null);
    $('#descripcionproducto-item').attr("value", null);
    $('#codUnidadMedida-item').attr("value", null);
    $("#unidadmedida-item").attr("value", null);
    $("#cantidad-item").attr("value", null);
    
	}



