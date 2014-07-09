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
	loadAutocompleteProducto();
	loadAutocompleteClient();
//	$("#clientzone").css("border","1px solid #6da8dc");
//	$("#addProductos").css("border","1px solid #6da8dc");
	
	$('#modalEditar').css('overflow-y','auto');
    
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
    
    $("#cant-item").change(function() {
        var precio = $('#precio-item').attr("value");
        var cantidad = $('#cant-item').attr("value");
        if( isNaN(precio) || isNaN(cantidad) ){
        	alert('Please insert a validate number');  	
        } else {
        	$('#total-item').attr("value",precio*cantidad);
        }
        
    });
    
    $("#precio-item").change(function() {
        var precio = $('#precio-item').attr("value");
        var cantidad = $('#cant-item').attr("value");
        if( isNaN(precio) || isNaN(cantidad) ){
        	alert('Please insert a validate number');  	
        } else {
        	$('#total-item').attr("value",precio*cantidad);
        }
        
    });
    
    $("#nuevo-registro").click(function() {
        $('#modalEditar').show();
        $("#guardar").html("Guardar");
        $(".btn-compra").show();
        $("#detalle-receta").show();
        $("#editar-nuevo").html("Nuevo Registro");
        CleanFormItems();
        lockinputs('unlock');
        lockinputs('clear');
        $("#grillaRegistroModal").jqGrid("clearGridData");
        $('#codproducto-item').attr("disabled", false);
        $('#total-item').attr("disabled", true);
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
    
    $("#reloadFilter").click(function() {
    	CleanFiltersItems();
    });
    
    $("#reloadClient").click(function() {
    	lockinputs('unlock');
        lockinputs('clear');
    });
    
    $("#guardar").click(function() {
       // if (!confirm("Esta seguro de que desea almacenar los datos?"))
       //     return;
        var data = obtenerGrid();
        if (data !== null) {
            enviarParametrosRegistros(data);
        }
    });

}); // cerramos el ready de js
function loadAutocompleteClient(){
	
	$.getJSON(table+"/clientdata", function(data) {
        var nameClient = [];
        var rucClient = [];
        var codClient = [];
        $(data).each(function(key, value) {
        	nameClient.push(value.CLIENTE_DES);
        	rucClient.push(value.CLIENTE_RUC);
        });

        $("#clientenombre").autocomplete({
            source: nameClient
        });
        $("#clienteruc").autocomplete({
            source: rucClient
        });
    });
}
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
        $("#addItem").attr("disabled", true);  
    }

}

function obtenerJsonDetalles() {
    var jsonObject = new Object();
    jsonObject.codproducto = $('#codproducto-item').attr("value");
    jsonObject.descripcionproducto = $('#descripcionproducto-item').attr("value");
    jsonObject.cantidad = $('#cant-item').attr("value");
    jsonObject.codUnidadMedida = $('#codUnidadMedida-item').attr("value");
    jsonObject.unidadmedida = $("#unidadmedida-item").attr("value");
    jsonObject.precio = $("#precio-item").attr("value");
    jsonObject.total = $("#total-item").attr("value");

    mensaje = 'Ingrese:';
    focus= 0;


     if (jsonObject.precio === "" || jsonObject.precio == 0 || jsonObject.precio === null) {
        mensaje+= ' | Precio ';
        focus++;
        addrequiredattr('precio-item',focus);

    } 

    if (jsonObject.cantidad === "" || jsonObject.cantidad == 0 || jsonObject.cantidad === null) {
        mensaje+= ' | Cantidad ';
        focus++;
        addrequiredattr('cant-item',focus);
    } 
   

    if(mensaje != 'Ingrese:'){
        mensaje+= ' | ';
        mostarVentana("warning",mensaje);
        return null
    }else {
        jsonObject.codproducto = parseInt(jsonObject.codproducto);
        jsonObject.cantidad = parseFloat(jsonObject.cantidad);
        jsonObject.precio = parseFloat(jsonObject.precio);
        jsonObject.total = parseFloat(jsonObject.total);
//        alert(JSON.stringify(jsonObject));
        return jsonObject;
    }

}


function loadAutocompleteProducto() {
    $.getJSON(table+"/productodata", function(data) {
        var descripcionProducto = [];
        var codigoProducto = [];
        var descripcionProductoFiltro = [];
        
        
        $(data).each(function(key, value) {
        	if(value.COD_PRODUCTO_TIPO > 1){ // solo productos que no sean materia prima, los cuales se dan de alta por compra
        		descripcionProducto.push(value.PRODUCTO_DESC);
        		codigoProducto.push(value.COD_PRODUCTO);
        	   descripcionProductoFiltro.push(value.PRODUCTO_DESC);
            }
        	
        	
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
            url: table+'/productoFinalData',
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
                    $('#precio-item').attr("value", respuesta.precioventa);
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
    
    var dataGrilla = JSON.stringify(data.grilla);
    var dataClient= JSON.stringify(data.dataclient);
    
    var urlenvio = '';
    urlenvio = table+'/guardar';
    
    $.ajax({
        url: urlenvio,
        type: 'post',
        data: {"dataGrilla": dataGrilla, "dataClient": dataClient},
        dataType: 'json',
        async: true,
        success: function(respuesta) {
//                alert(respuesta+"hola");
            if (respuesta == null) {
                mostarVentana("error", "TIMEOUT");
            } else if (respuesta.result == "EXITO") {
                mostarVentana("success-title", "Datos Almacenados exitosamente");
                $('#modalEditar').hide();
                $("#grillaRegistro").trigger("reloadGrid");
                CleanFormItems();
            } else if (respuesta.result == "ERROR") {
                if (respuesta.code == 23505) {
                    mostarVentana("warning", "Datos duplicados");
                } else {
                    mostarVentana("warning", "Intente de nuevo");
                }
            }
        },
        error: function(event, request, settings) {
            mostarVentana("warning", "Intente de nuevo");
        }
    });
}

function buscar() {
    var dataJsonBusqueda = JSON.stringify(filtrosbusqueda());
   
    $.blockUI({
        message: "Aguarde un Momento"
    });

    $.ajax({
        url: table+'/listar',
        type: 'post',
        data: {
            "dataJsonBusqueda": dataJsonBusqueda
        },
        dataType: 'json',
        async: false,
        success: function(respuesta) {
        	if(respuesta.mensajeSinFilas == true){
        		mostarVentana("warning-title", "Sin datos para mostrar");
        		$("#grillaRegistro")[0].addJSONData(respuesta);
        	}else{
        		$("#grillaRegistro")[0].addJSONData(respuesta);
        	}
            $.unblockUI();
        },
        error: function(event, request, settings) {
            $.unblockUI();
            
        }
    });
}

/*
 * ESTABLECEMOS LOS FILTROS Y LAS LIMPIEZAS 
 * 
 * 
 * */
function addrequiredattr(id,focus){
    $('#'+id).attr("required", "required");
    if(focus == 1)
        $('#'+id).focus();
}
 


function obtenerGrid() {
    var jsonObject = new Object();
    var dataObjectGrillaDetalle = new Object();
    var dataclient = new Object();

    dataObjectGrillaDetalle = jQuery("#grillaRegistroModal").jqGrid('getRowData'); // saca datos de la grilla en formato json
    var rowsGrid = jQuery("#grillaRegistroModal").jqGrid('getRowData'); // saca tododos los datos, vamos a usar para sacar el length
   
   // Verifica que se seleccione un cliente o una mesa
   if($('#clientecod').attr("value").length > 0 ){
        dataclient.code = $('#clientecod').attr("value");
        dataclient.table = true;
    } else if ($('#numeromesa').attr("value").length > 0 && $('#numeromesa').attr("value") !== null){
        dataclient.table = $('#numeromesa').attr("value");
        dataclient.code = true;
    } else {
        dataclient.code = false;
        dataclient.table = false;
        console.log("3.-"+dataclient.code+" ; "+dataclient.table);
        // mostarVentana("warning", "Seleccione un cliente o una mesa");
    } 
    



    var mensaje = 'Ingrese: ';
    var focus = 0;
    
    console.log("4.-"+dataclient.code+" ; "+dataclient.table);
    
    if (!dataclient.code) {
        mensaje+= ' | C\u00F3digo del cliente ';
        focus++;
        addrequiredattr('clienteruc',focus);
    }  
    if (!dataclient.table) {
        mensaje+= ' | C\u00F3digo de mesa ';
        focus++;
        addrequiredattr('numeromesa',focus);
    }  
    if (rowsGrid.length == 0){
        mensaje+= ' | Detalles del pedido    ';
        focus++;
        addrequiredattr('codproducto-item',focus);
        addrequiredattr('descripcionproducto-item',focus);
    }




    if(mensaje !='Ingrese: ' ){
        mensaje+= ' |';
        mostarVentana("warning", mensaje);
        return null;
    }else{
        
        jsonObject.grilla = dataObjectGrillaDetalle;
        jsonObject.dataclient = dataclient;
    //    alert(JSON.stringify(jsonObject));
        return jsonObject;
    }
    
    
}


function filtrosbusqueda() {
    var obj = new Object();
    obj.codcliente = $('#codcliente-filtro').attr("value");
    obj.namecliente = $('#namecliente-filtro').attr("value");
    obj.codmesa = $('#codmesa-filtro').attr("value");
    obj.estado = $('#estado-filtro').attr("value");
    if(obj.estado == -1)
    	obj.estado = null;
   
    return obj;
}


function CleanFormItems() {
	$('#codproducto-item').attr("disabled", false);
    $('#descripcionproducto-item').attr("disabled", false);
    $('#codproducto-item').attr("value", null);
    $('#descripcionproducto-item').attr("value", null);
    $('#codUnidadMedida-item').attr("value", null);
    $("#unidadmedida-item").attr("value", null);
    $('#cant-item').attr("value",'0.000');
    $("#precio-item").attr("value",'0.000');
    $("#total-item").attr("value", '0.000'); 
	}

function clientevalidation(data) {
    var dataString = new Object();
    dataString.value = "vacio";
    dataString.reference = data;
    switch (data) {
        case 'documento':
            {
                dataString.value = $('#clienteruc').attr("value");
//                    alert(data);
                break;
            }
        case 'nombre':
            {
                dataString.value = $('#clientenombre').attr("value");
//                     alert(data);
                break;
            }
    
    }
    if (dataString.value !== "") {
        $.ajax({
            url: table+'/clientvalidatedata',
            type: 'post',
            dataType: 'json',
            data: {
                "parametro": dataString
            },
            async: false,
            success: function(respuesta) {
//                     alert(respuesta.cod+"-"+respuesta.name+"-"+respuesta.ruc);
            	if(respuesta.error == 'error'){
            		 alert('Busque por el RUC');
            	} else {
            		$("#clientecod").attr("value", respuesta.cod);
                    $("#clienteruc").attr("value", respuesta.ruc);
                    $("#clientenombre").attr("value", respuesta.name);
                    lockinputs('lock');
            		
            	}
                
            },
            error: function(event, request, settings) {
                mostarVentana("warning","No se encontraron datos, verifique los datos ingresados");

            }
        });
    } else {

        mostarVentana("warning","No se encontraron datos, verifique los datos ingresados");
    }

}
function lockinputs(key) {
	if(key == 'lock'){
		$("#clientecod").attr("disabled", true);
	    $("#clienteruc").attr("disabled", true);
	    $("#clientenombre").attr("disabled", true);
	    $("#numeromesa").attr("value", null);
        $("#numeromesa").attr("disabled", true);
	}
	if(key == 'unlock'){
		$("#clientecod").attr("disabled", false);
	    $("#clienteruc").attr("disabled", false);
	    $("#clientenombre").attr("disabled", false);
	    $("#numeromesa").attr("value", null);
        $("#numeromesa").attr("disabled", false);
	}
	if(key == 'clear'){
		$("#clientecod").attr("value", null);
	    $("#clienteruc").attr("value", null);
	    $("#clientenombre").attr("value", null);
	    $("#numeromesa").attr("value", null);
	}
	
}

function CleanFiltersItems() {
		$('#codcliente-filtro').attr("value", null);
		$('#namecliente-filtro').attr("value", null);
		$('#codmesa-filtro').attr("value", null);
		$('#estado-filtro').attr("value", -1);
	}

