$().ready(function() {
    /*
     * Formateamos los campos de fecha
     */

    $("#FechaFactura-modal").datepicker();
    $("#FechaFactura-modal").datepicker("option", "dateFormat", "yy-mm-dd");
    $("#FechaFactura-modal").datepicker("setDate", new Date());
    $("#FechaVencimiento-modal").datepicker();
    $("#FechaVencimiento-modal").datepicker("option", "dateFormat", "yy-mm-dd");
    $("#fechavencimiento-filtro").datepicker();
    $("#fechavencimiento-filtro").datepicker("option", "dateFormat", "yy-mm-dd");
    $("#fechaemision-filtro").datepicker();
    $("#fechaemision-filtro").datepicker("option", "dateFormat", "yy-mm-dd");
    $("#FechaFactura").datepicker();
    $("#FechaFactura").datepicker("option", "dateFormat", "dd-mm-yy");
    $("#FechaFactura").datepicker("setDate", new Date());

    $("#cargar-karrito").click(function() {
        loadAutocompleteProducto();
    	$("#addProductos").show();
    	
    });
    
    $("#buscar-karrito").click(function() {
    	$("#addProductos").hide();
    	$("#modalKarrito").show();
//    	cargarGrillaFacturasModalKarrito();
    });
    
    $("#searchtransaction").click(function() {
    			var data = checkvalues();
    			
    			if(data !== 0){
    				finditems(data);				
    			}
    			
    });
    $("#buscarCompra").click(function() {
        buscar();
    });

    $("#cerrar-bot-pagos").click(function() {
        $('#modalPagos').hide();
    });
    $("#cancelar-bot-pagos").click(function() {
        $('#modalPagos').hide();

    });

    $("#cerrar-bot").click(function() {
        $('#modalEditar').hide();
        cleanFormModalHide("exit");
    });

    $("#cancelar-bot").click(function() {
        $('#modalEditar').hide();

        cleanFormModalHide("exit");
    });
    
    $("#cerrar-transactions").click(function() {
        $('#modalKarrito').hide();
    });
    $("#cerrar-bot-karrito").click(function() {
        $('#modalKarrito').hide();
    });
    
    $("#select-transactions").click(function() {	
    	var s;
    	s = jQuery("#grillaRegistroKarrito").jqGrid('getGridParam','selarrrow');
    	var data = [];
    	alert(s.length);
    	for (var i = 0; i < s.length; i++) {
         	var rows = jQuery('#grillaRegistroKarrito').jqGrid ('getRowData', s[i]);
         	data.push(rows);
    	}
    	
    	if(loadGridModalFacturacion(data)){
    		for (var j = 0; j < s.length; i++) {
             	$('#grillaRegistroKarrito').jqGrid('delRowData',s[j]);
        	}
    		$("#grillaRegistroKarrito").trigger("reloadGrid");
    		$('#modalKarrito').hide();
    	}
//    	console.log(data);
    });
    
    
    $("#nuevoCompra").click(function() {
        $('#modalEditar').show();
        $("#guardar-registro").html("Guardar");
        $(".btn-compra").show();
        $("#editar-nuevo").html("Nuevo Registro");
        $("#numeroFacturaLb").css("display", "none");
        $("#numeroFacturaIn").css("display", "none");
        $("#guardar").show();
        $("#addProductos").hide();
        $("#addItem").attr("disabled", "disabled");
        $('#cantidad-item').attr("value", 0);
        $('#preciounitario-item').attr("value", 0);
        $('#totalparcial-item').attr("value", 0);
        $("#totalparcial-item").attr("disabled", "disabled");
        $("#unidadmedida-item").attr("disabled", "disabled");
        $('#estado-compra').attr("value", 'T');
        

    });

    $("#addItem").click(function() {
        addItem();
    });

    $("#guardar").click(function() {
        if (!confirm("Esta seguro de que desea almacenar los datos?"))
            return;
        var data = obtenerGrid();
        if (data !== null) {
            enviarParametrosCompras(data);
        }
    });

    $("#guardar-pagos").click(function() {
        if (!confirm("Esta seguro de que desea almacenar el pago?"))
            return;
        var data = obtenerMontoPago();
        if (data !== null) {
            console.log(data);
//            alert(JSON.stringify(data));
            guardarPago(data);
        }
    });
    $.getJSON("factura/clientedata", function(data) {
        var nombreCliente = [];
        var rucCliente = [];
        var codCliente = [];
        $(data).each(function(key, value) {
        	nombreCliente.push(value.CLIENTE_DES);
        	rucCliente.push(value.CLIENTE_RUC);
        	codCliente.push(value.COD_CLIENTE);
            //            console.log(value.PROVEEDOR_NOMBRE);
        });

        $("#razonsocial-modal").autocomplete({
            source: nombreCliente
        });
        $("#ruc-modal").autocomplete({
            source: rucCliente
        });
        $("#codigocliente-modal").autocomplete({
            source: codCliente
        });

        $("#codcliente-filtro").autocomplete({
            source: codCliente
        });
        $("#namecliente-filtro").autocomplete({
            source: nombreCliente
        });
        $("#codcliente-karrito").autocomplete({
            source: codCliente
        });
        $("#namecliente-karrito").autocomplete({
            source: nombreCliente
        });
    });

}); // cerramos el ready de js

function loadGridModalFacturacion(data){
//	$("#grillaComprasModal").trigger("reloadGrid");
	for (var i = 0; i < data.length; i++) {
		var rows = jQuery("#grillaComprasModal").jqGrid('getRowData');
		jQuery("#grillaComprasModal").jqGrid('addRowData', (rows.length) + 1, data[i]);
	}
	return true;
}
function checkvalues(){
	var karritofilter = new Object();
	karritofilter.codigocliente = $('#codcliente-karrito').attr("value");
	karritofilter.nombrecliente = $('#namecliente-karrito').attr("value");
	karritofilter.codigomesa = $('#codmesa-karrito').attr("value");
	var bandera = 0;
		if (karritofilter.codigocliente === "" || karritofilter.codigocliente === null) {
//	        mostarVentana("warning", "Ingrese el Codigo del producto");
	       bandera++;
	    } 
		if (karritofilter.nombrecliente === "" || karritofilter.nombrecliente === null) {
//	        mostarVentana("warning", "Ingrese la descripcion del producto");
	        bandera++;
	    } 
		if (karritofilter.codigomesa === "" || karritofilter.codigomesa === null) {
//	        mostarVentana("warning", "Ingrese la unidad de medida");
	        bandera++;
	    }
		
			if(bandera == 3)
				return 0;
			else
				return karritofilter;
			
}

function finditems(data){
//	alert(JSON.stringify(data));
    $.ajax({
        url: '/ventas/factura/getkarritodata',
        type: 'post',
        dataType: 'json',
        data: {
            "data": data
        },
        async: true,
        success: function(respuesta) {
        	
        	cargarGrillaFacturasModalKarrito();
        	$("#grillaRegistroKarrito").jqGrid("clearGridData", true);
        	for (var i = 0; i < respuesta.length; i++) {
	         	var rows = jQuery("#grillaRegistroKarrito").jqGrid('getRowData');
	         	jQuery("#grillaRegistroKarrito").jqGrid('addRowData', (rows.length) + 1, respuesta[i]);
	}
        },
        error: function(event, request, settings) {
            alert('No se encontro el valor');
        }
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
    } else if (box == "warning-pagos") {
        $("#warning-message-pagos").text(mensaje);
        $("#warning-block-pagos").show();
        setTimeout("ocultarWarningBlockPagos()", 500);
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
        var rows = jQuery("#grillaComprasModal").jqGrid('getRowData');
        jQuery("#grillaComprasModal").jqGrid('addRowData', (rows.length) + 1, data);
        CleanFormItems();
        BlockProveedorData("additem");
    }

}

function obtenerJsonDetalles() {
    var jsonObject = new Object();
    var impuestos = document.getElementById("tipoimpuesto-item");
    var impuesto = parseInt(impuestos.options[impuestos.selectedIndex].value);
    jsonObject.idproveedor = $('#idproveedor').attr("value");
    jsonObject.nombreproveedor = $('#razonsocial-modal').attr("value");
    jsonObject.codproducto = $('#codproducto-item').attr("value");
    jsonObject.descripcionproducto = $('#descripcionproducto-item').attr("value");
    jsonObject.cantidad = $('#cantidad-item').attr("value");
    jsonObject.codUnidadMedida = $('#codUnidadMedida-item').attr("value");
    jsonObject.unidadmedida = $("#unidadmedida-item").attr("value");
    jsonObject.preciounitario = $("#preciounitario-item").attr("value");
    jsonObject.totalparcial = $("#totalparcial-item").attr("value");

    if (impuesto === 5) {
        jsonObject.codimpuesto = impuesto;
        jsonObject.iva5 = (jsonObject.totalparcial * 5) / 105;
        jsonObject.iva10 = 0;
    }
    if (impuesto === 10) {
        jsonObject.codimpuesto = impuesto;
        jsonObject.iva5 = 0;
        jsonObject.iva10 = (jsonObject.totalparcial * 10) / 110;
    }
    if (jsonObject.codproducto === "" || jsonObject.codproducto === null) {
        mostarVentana("warning", "Ingrese el Codigo del producto");
    } else if (jsonObject.descripcionproducto === "" || jsonObject.descripcionproducto === null) {
        mostarVentana("warning", "Ingrese la descripcion del producto");
    } else if (jsonObject.descripcionproducto === "" || jsonObject.descripcionproducto === null) {
        mostarVentana("warning", "Ingrese la descripcion del producto");
    } else if (jsonObject.codUnidadMedida === "" || jsonObject.codUnidadMedida === null) {
        mostarVentana("warning", "Ingrese la unidad de medida");
    } else if (jsonObject.cantidad === "" || jsonObject.cantidad === "0" || jsonObject.cantidad === null) {
        mostarVentana("warning", "Ingrese la cantidad");
    } else if (jsonObject.preciounitario === "" || jsonObject.preciounitario === null || jsonObject.preciounitario === "0") {
        mostarVentana("warning", "Ingrese el precio unitario");
    } else if (impuesto === -1 || impuesto === null) {
        mostarVentana("warning", "Ingrese el tipo de impuestoid");
    } else if (jsonObject.totalparcial === "" || jsonObject.to === null || jsonObject.totalparcial === "0") {
        mostarVentana("warning", "Ingrese el total parcial");
    } else if ($('#controlfiscal-modal_3').attr("value") === "") {
        mostarVentana("warning", "Ingrese el control fiscal");
    } else {
        jsonObject.idproveedor = parseInt(jsonObject.idproveedor);
        jsonObject.codproducto = parseInt(jsonObject.codproducto);
        jsonObject.cantidad = parseInt(jsonObject.cantidad);
        jsonObject.preciounitario = parseInt(jsonObject.preciounitario);
        jsonObject.totalparcial = parseInt(jsonObject.totalparcial);
//        alert(JSON.stringify(jsonObject));
        return jsonObject;
    }
    return null;
}

function validacliente(data, what) {
    var dataString = new Object();
    dataString.value = "vacio";
    dataString.reference = data;
    var pago = "";
    if (what === "pago") {
        pago = "-pagos";
    } 
    switch (data) {
        case 'cod':
            {
                dataString.value = $('#codigocliente-modal' + pago).attr("value");
//                    alert(data);
                break;
            }
        case 'ruc':
            {
                dataString.value = $('#ruc-modal' + pago).attr("value");
//                     alert(data);
                break;
            }
        case 'name':
            {
                dataString.value = $('#razonsocial-modal' + pago).attr("value");
//                      alert(data);
                break;
            }
    }
    if (dataString.value !== "") {
        $.ajax({
            url: '/ventas/factura/validaclientedata',
            type: 'post',
            dataType: 'json',
            data: {
                "parametro": dataString
            },
            async: false,
            success: function(respuesta) {
//                     alert(respuesta.cod+"-"+respuesta.name+"-"+respuesta.ruc);
                $("#codigocliente-modal" + pago).attr("value", respuesta.cod);
                $("#ruc-modal" + pago).attr("value", respuesta.ruc);
                $("#razonsocial-modal" + pago).attr("value", respuesta.name);
                $("#codcliente-karrito").attr("value", respuesta.cod);
                $("#namecliente-karrito").attr("value", respuesta.name);
                blockclientdata('block');
            },
            error: function(event, request, settings) {
                alert('No se encontro el valor');
            }
        });
    } else {
        alert("Inserte un valor");
    }

}
function blockclientdata(action) {
	if(action == 'block'){
		 $("#codigocliente-modal").attr("disabled", true);
	     $("#ruc-modal").attr("disabled", true);
	     $("#razonsocial-modal").("disabled", true);
	     $("#codcliente-karrito").attr("disabled", true);
         $("#namecliente-karrito").attr("disabled", true);
	} else {
		 $("#codigocliente-modal").("value", null);
	     $("#ruc-modal").attr("value", null);
	     $("#razonsocial-modal").("value", null);
	     $("#codcliente-karrito").("value", null);
         $("#namecliente-karrito").attr("value", null);
		 $("#codigocliente-modal").attr("disabled", false);
	     $("#ruc-modal").attr("disabled", false);
	     $("#razonsocial-modal").("disabled", false);
	     $("#codcliente-karrito").attr("disabled", false);
         $("#namecliente-karrito").attr("disabled", false);
	}


}

function loadAutocompleteProducto() {
    $.getJSON("factura/productodata", function(data) {
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
            url: '/compras/compra2/productvalidationdata',
            type: 'post',
            dataType: 'json',
            data: {
                "parametro": dataString
            },
            async: false,
            success: function(respuesta) {
                $('#codproducto-item').attr("value", respuesta.cod);
                $('#descripcionproducto-item').attr("value", respuesta.descripcion);
                $('#codUnidadMedida-item').attr("value", respuesta.unimedcod);
                $('#unidadmedida-item').attr("value", respuesta.unimeddesc);
//                $("#addItem").attr("enabled", "enabled");
                $("#addItem").removeAttr('disabled');
            },
            error: function(event, request, settings) {
                alert('No se encontro el valor');
            }
        });
    } else {
        alert("Inserte un valor");
    }

}

function calculoTotalParcial() {
    var TotalParcial = 0;
    var cantidad = $('#cantidad-item').attr("value");
    var precioUnitario = $('#preciounitario-item').attr("value");
    TotalParcial = cantidad * precioUnitario;
    $('#totalparcial-item').attr("value", TotalParcial);
}

function enviarParametrosCompras(data) {
    var dataCompra = data.compra;
    var dataCompraDetalle = data.compraDetalle;
    var dataCompraCantItems = data.compraDetalleItem;

    $.ajax({
        url: '/compras/compra2/guardar',
        type: 'post',
        data: {"dataCompra": dataCompra, "dataCompraDetalle": dataCompraDetalle, "dataCompraCantItems": dataCompraCantItems},
        dataType: 'json',
        async: true,
        success: function(respuesta) {
//                alert(respuesta+"hola");
            if (respuesta == null) {
                mostarVentana("error", "TIMEOUT");
            } else if (respuesta.result == "EXITO") {
                mostarVentana("success-title", "DATOS ALMACENADOS EXITOSAMENTE");
                $('#modalEditar').hide();
                cleanFormModalHide("exit");
            } else if (respuesta.result == "ERROR") {
                if (respuesta.mensaje == 23505) {
                    mostarVentana("warning", "DATOS DUPLICADOS");
                } else {
                    mostarVentana("warning", "OCURRIO UN ERROR");
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
        url: '/ventas/factura/buscar',
        type: 'post',
        data: {
            "dataJsonBusqueda": dataJsonBusqueda
        },
        dataType: 'json',
        async: false,
        success: function(respuesta) {
//                            $("#grillaCompras").jqGrid("clearGridData");
            $("#grillaCompras")[0].addJSONData(respuesta);
            clearfilters();
            $.unblockUI();
        },
        error: function(event, request, settings) {
            $.unblockUI();
            alert(mostrarError("OCURRIO UN ERROR"));
        }
    });
}


function editarRegistro(parametros) {
    cleanFormModalHide("edit");
    $("#modalEditar").show();
    $("#editar-nuevo").html("Editar Registro");
    $.blockUI({
        message: "Aguarde un Momento"
    });
    var estado = '';
    if(parametros.ESTADO == 'ACTIVO'){
    	estado = 'T';
    }else{
    	estado = 'A';
    }
    $('#codigoproveedor-modal').attr("value", parametros.COD_PROVEEDOR);
    $('#estado-compra').attr("value", estado);
    validaProveedor("cod", "edit");
    $('#factura-modal').attr("value", parametros.NRO_FACTURA_COMPRA);
    var control_1 = parametros.CONTROL_FISCAL.substr(0, 3);
    var control_2 = parametros.CONTROL_FISCAL.substr(4, 3);
    var control_3 = parametros.CONTROL_FISCAL.substr(8, (parametros.CONTROL_FISCAL.length - 8));
    $('#controlfiscal-modal_1').attr("value", control_1);
    $('#controlfiscal-modal_2').attr("value", control_2);
    $('#controlfiscal-modal_3').attr("value", control_3);
    jQuery('input:radio[name="formaPago"]').filter('[value="' + parametros.COD_FORMA_PAGO + '"]').attr('checked', true);
    $('#FechaFactura-modal').attr("value", parametros.FECHA_EMISION_FACTURA);
    $('#FechaVencimiento-modal').attr("value", parametros.FECHA_VENCIMIENTO_FACTURA);
    $("#guardar").hide();
    $("#addProductos").hide();
    BlockProveedorData("editar");
    $.ajax({
        url: '/compras/compra2/modaleditar',
        type: 'post',
        data: {
            "NumeroInterno": parametros.NRO_FACTURA_COMPRA
        },
        dataType: 'json',
        async: false,
        success: function(respuesta) {
            for (var i = 0; i < respuesta.length; i++) {
                var insertdetails = new Object();
                insertdetails.idproveedor = parametros.COD_PROVEEDOR;
                insertdetails.nombreproveedor = $('#razonsocial-modal').attr("value");
                insertdetails.codproducto = respuesta[i].codproducto;
                insertdetails.descripcionproducto = respuesta[i].descripcionproducto;
                insertdetails.cantidad = respuesta[i].cantidad;
                insertdetails.codUnidadMedida = respuesta[i].codUnidadMedida;
                insertdetails.unidadmedida = respuesta[i].unidadmedida;
                insertdetails.preciounitario = respuesta[i].preciounitario;
                insertdetails.totalparcial = respuesta[i].totalparcial;
                insertdetails.codimpuesto = respuesta[i].codimpuesto;
                insertdetails.iva5 = respuesta[i].iva5;
                insertdetails.iva10 = respuesta[i].iva10;

                var rows = jQuery("#grillaComprasModal").jqGrid('getRowData');
                jQuery("#grillaComprasModal").jqGrid('addRowData', (rows.length) + 1, insertdetails);
//                                $("#grillaComprasModal")[0].addJSONData(respuesta[i]);
//                                console.log(respuesta[i]);
            }

            $.unblockUI();
        },
        error: function(event, request, settings) {
            $.unblockUI();
            alert(mostrarError("OCURRIO UN ERROR"));
        }
    });
}
function cargarPagos(facturaCompra) {

    $.ajax({
        url: '/compras/compra2/modalpagos',
        type: 'post',
        data: {
            "NumeroInterno": facturaCompra
        },
        dataType: 'json',
        async: false,
        success: function(respuesta) {
            for (var i = 0; i < respuesta.length; i++) {
                var rows = jQuery("#grillaComprasModalPagos").jqGrid('getRowData');
                jQuery("#grillaComprasModalPagos").jqGrid('addRowData', (rows.length) + 1, respuesta[i]);
                if (respuesta[i].ESTADO_PAGO === 'ANULADO') {
                    $("#grillaComprasModalPagos").jqGrid('setRowData', (rows.length) + 1, true,
                            {color: 'red', weightfont: 'bold'});
                }
            }
        },
        error: function(event, request, settings) {
            alert(mostrarError("OCURRIO UN ERROR"));
        }
    });
}

function pagos(parametros) {
    $("#modalPagos").show();
    $('#AgregarPagos').show();
    $("#editar-nuevo").html("Registrar Pago");
    $("#grillaComprasModalPagos").jqGrid("clearGridData");
    $('.cheque').hide();
    //cerar datos
    $('#banco-modal-pagos').attr("value", null);
    $('#cheque-modal-pagos').attr("value", null);
    $('#montoPago-modal-pagos').attr("value", null);
    $('#montoPago-modal-pagos').attr("disabled", false);
    $('#pagosrealizados-modal-pagos').attr("value", null);
    $('#saldoPendiente-modal-pagos').attr("value", null);
    $('#codigoproveedor-modal-pagos').attr("value", parametros.COD_PROVEEDOR);
    validaProveedor("cod", "pago");
    $(".btn-compra").hide();
    $('#factura-modal-pagos').attr("value", parametros.NRO_FACTURA_COMPRA);
    $('#codigoMoneda-pagos').attr("value", parametros.COD_MONEDA_COMPRA);

    cargarPagos(parametros.NRO_FACTURA_COMPRA);
    var control_1 = parametros.CONTROL_FISCAL.substr(0, 3);
    var control_2 = parametros.CONTROL_FISCAL.substr(4, 3);
    var control_3 = parametros.CONTROL_FISCAL.substr(8, (parametros.CONTROL_FISCAL.length - 8));
    $('#controlfiscal-modal_1-pagos').attr("value", control_1);
    $('#controlfiscal-modal_2-pagos').attr("value", control_2);
    $('#controlfiscal-modal_3-pagos').attr("value", control_3);
    $('#montoTotal-pagos').attr("value", parseInt(parametros.MONTO_TOTAL_COMPRA));
    $("#guardar").hide();
    if(parametros.ESTADO == 'ANULADO'){
    	$("#guardar-pagos").hide();
    	$('#AgregarPagos').hide();
    }else {
    	$("#guardar-pagos").show();
    	calcularSaldo(parametros.NRO_FACTURA_COMPRA);
    }
    BlockProveedorData("pagos");
    $("#modalPagos").show();
}
/*
 * ESTABLECEMOS LOS FILTROS Y LAS LIMPIEZAS 
 * 
 * 
 * */

function obtenerGrid() {
    var jsonObject = new Object();
    var dataObjectCompraDet = new Object();
    var dataObjectCompra = new Object();

    dataObjectCompraDet = jQuery("#grillaComprasModal").jqGrid('getRowData'); // saca datos de la grilla en formato json
    var rowsGrid = jQuery("#grillaComprasModal").jqGrid('getRowData'); // saca tododos los datos, vamos a usar para sacar el length
    var montoTotal = 0;
    var montoImpuesto5 = 0;
    var montoImpuesto10 = 0;

    for (var i = 0; i < rowsGrid.length; i++) {
        montoTotal = montoTotal + parseInt(dataObjectCompraDet[i].totalparcial);
        montoImpuesto5 = montoImpuesto5 + parseInt(dataObjectCompraDet[i].iva5);
        montoImpuesto10 = montoImpuesto10 + parseInt(dataObjectCompraDet[i].iva10);

    }
    
    dataObjectCompra.codproveedor = $('#codigoproveedor-modal').attr("value");
    var controlFiscal = ($('#controlfiscal-modal_1').attr("value") + "-" + $('#controlfiscal-modal_2').attr("value") + "-" + $('#controlfiscal-modal_3').attr("value"));
    dataObjectCompra.controlFiscal = controlFiscal;
    dataObjectCompra.formaPago = $("input[name='formaPago']:checked").val();
    dataObjectCompra.fechaEmision = $('#FechaFactura-modal').attr("value");
    dataObjectCompra.fechaVencimiento = $('#FechaVencimiento-modal').attr("value");
    dataObjectCompra.montoTotalCompra = montoTotal;
    dataObjectCompra.codigoMoneda = 1;
    dataObjectCompra.codigoUsuario = 1;
    dataObjectCompra.montoImpuesto5 = montoImpuesto5;
    dataObjectCompra.montoImpuesto10 = montoImpuesto10;
    dataObjectCompra.estado = $('#estado-compra').attr("value");

    jsonObject.compra = dataObjectCompra;
    jsonObject.compraDetalle = dataObjectCompraDet;
    jsonObject.compraDetalleItem = rowsGrid.length;
//         alert(JSON.stringify(jsonObject));
    return jsonObject;
}


function filtrosbusqueda() {
    var obj = new Object();
    obj.codcliente= $('#codcliente-filtro').attr("value");
    obj.namecliente = $('#namecliente-filtro').attr("value");
    obj.codigointerno = $('#codigointerno-filtro').attr("value");
    if ((($('#controlfiscal-filtro_3').attr("value")).length > 0) || ($('#controlfiscal-filtro_3').attr("value") !== "")) {
        var controlFiscalFiltros = ($('#controlfiscal-filtro_1').attr("value") + "-" + $('#controlfiscal-filtro_2').attr("value") + "-" + $('#controlfiscal-filtro_3').attr("value"));
        obj.controlfiscal = controlFiscalFiltros;
    } else {
        obj.controlfiscal = null;
    }

    obj.fechaemision = $('#fechaemision-filtro').attr("value");
    obj.fechavencimiento = $('#fechavencimiento-filtro').attr("value");
    var estado = document.getElementById("estado-filtro");
    obj.estado = formapago.options[formapago.selectedIndex].value;
    return obj;
}
function clearfilters() {

    $('#codcliente-filtro').attr("value", null);
    $('#namecliente-filtro').attr("value", null);
    $('#codigointerno-filtro').attr("value", null);
    $('#controlfiscal-filtro_3').attr("value", null);
    $('#fechaemision-filtro').attr("value", null);
    $('#fechavencimiento-filtro').attr("value", null);
//    $('formapago-filtro').attr("value",-1);
    $("#estado-filtro option[value=-1]").attr("selected", true);

}
function cleanFormModalHide(from) {

    $('#idproveedor').attr("value", null);
    $('#codigoproveedor-modal').attr("value", null);
    $('#ruc-modal').attr("value", null);
    $('#razonsocial-modal').attr("value", null);
    $('#controlfiscal-modal_3').attr("value", null);
    $('#FechaVencimiento-modal').attr("value", null);
    $('#FechaFactura-modal').attr("value", new Date());
    $("#grillaComprasModal").jqGrid("clearGridData");
    if (from === "exit") {
        $("#grillaCompras").trigger("reloadGrid");
        $("#codigoproveedor-modal").attr("disabled", false);
        $("#ruc-modal").attr("disabled", false);
        $("#razonsocial-modal").attr("disabled", false);
        $("#controlfiscal-modal_1").attr("disabled", false);
        $("#controlfiscal-modal_2").attr("disabled", false);
        $("#controlfiscal-modal_3").attr("disabled", false);
        $("#FechaFactura-modal").attr("disabled", false);
        $("#FechaVencimiento-modal").attr("disabled", false);
    }


}

function CleanFormItems() {

    $('#codproducto-item').attr("value", null);
    $('#descripcionproducto-item').attr("value", null);
    $('#codUnidadMedida-item').attr("value", null);
    $("#unidadmedida-item").attr("value", null);
    $("#tipoimpuesto-item option[value=-1]").attr("selected", true);
    $('#cantidad-item').attr("value", 0);
    $('#preciounitario-item').attr("value", 0);
    $('#totalparcial-item').attr("value", 0);

}

function BlockProveedorData(modal) {
    var pagos = "";
    if (modal === "pagos") {
        pagos = "-pagos";
        $('#montoTotal-pagos').attr("disabled", true);
    } else {
        $("#FechaFactura-modal").attr("disabled", true);
        $("#FechaVencimiento-modal").attr("disabled", true);
    }
    $("#codigoproveedor-modal" + pagos).attr("disabled", true);
    $("#ruc-modal" + pagos).attr("disabled", true);
    $("#razonsocial-modal" + pagos).attr("disabled", true);
    $("#controlfiscal-modal_1" + pagos).attr("disabled", true);
    $("#controlfiscal-modal_2" + pagos).attr("disabled", true);
    $("#controlfiscal-modal_3" + pagos).attr("disabled", true);
    $('#factura-modal' + pagos).attr("disabled", true);
}

function calcularSaldo(numeroFactura) {
    var montoTotalFactura = parseInt($('#montoTotal-pagos').val());
    $('#pagosrealizados-modal-pagos').attr("value", 0);
    $('#saldoPendiente-modal-pagos').attr("value", 0);
    var saldoPendientefactura = parseInt(0);
    $.ajax({
        url: '/compras/compra2/calculasaldo',
        type: 'post',
        data: {nroFactura: numeroFactura},
        dataType: 'json',
        async: false,
        success: function(respuesta) {
            respuesta = respuesta + 0;
            saldoPendientefactura = montoTotalFactura - respuesta;
            $('#pagosrealizados-modal-pagos').attr("value", parseFloat(respuesta));
            $("#pagosrealizados-modal-pagos").attr("disabled", true);
            $('#saldoPendiente-modal-pagos').attr("value", parseFloat(saldoPendientefactura));
            $("#saldoPendiente-modal-pagos").attr("disabled", true);
            $('#saldoPendiente-modal-pagos').css("color", "green");
            if (saldoPendientefactura <= 0) {
                $('#AgregarPagos').hide();
                alert("La factura se encuentra cancelada");
            } else {
                $('#saldoPendiente-modal-pagos').css("color", "red");
            }

        },
        error: function(event, request, settings) {
            alert(mostrarError("OCURRIO UN ERROR"));
        }
    });

}
function selectFormaPago(formaPago) {
    if (formaPago === "cheque") {
        $('.cheque').show();
        $('#banco-modal-pagos').attr("disabled", false);
        $('#cheque-modal-pagos').attr("disabled", false);
        $('#montoPago-modal-pagos').attr("disabled", false);
        $('input[name=formaPagoEfectivo]').attr('checked', false);
    } else {
        $('.cheque').hide();
        $('#banco-modal-pagos').attr("disabled", true);
        $('#cheque-modal-pagos').attr("disabled", true);
        $('input[name=formaPagoCheque]').attr('checked', false);
    }

}

function obtenerMontoPago() {
    var data = new Object();
    if ($('#factura-modal-pagos').val() === "" || $('#factura-modal-pagos').val() === null) {
        mostarVentana("warning-pagos", "Verifique los datos, el numero de factura no esta especificado");
    } else if ($('#montoPago-modal-pagos').val() === "" || $('#montoPago-modal-pagos').val() < 0) {
        mostarVentana("warning-pagos", "Verifique el monto ingresado");
    } else if (parseInt($('#saldoPendiente-modal-pagos').val()) < parseInt($('#montoPago-modal-pagos').val())) {
        mostarVentana("warning-pagos", "El monto a pagar supera el saldo pendiente.");
    } else if (($('input[name=formaPagoCheque]').is(':checked')) && ($("#banco-modal-pagos").val() === "" || $("#cheque-modal-pagos").val() === "")) {
        mostarVentana("warning-pagos", "Ingrese los datos del cheque");
    } else {
        data.numero_factura = $('#factura-modal-pagos').val();
        data.monto_pago = $('#montoPago-modal-pagos').val();
        data.moneda_pago = $('#codigoMoneda-pagos').val();
        data.estado_pago = 'T';
        if ($('input[name=formaPagoCheque]').is(':checked')) {
            data.nombre_banco = $("#banco-modal-pagos").val();
            data.numero_cheque = $("#cheque-modal-pagos").val();
        } else {
            data.nombre_banco = "0";
            data.numero_cheque = 0;
        }
        parseFloat(data.monto_pago);
//        alert(data.monto_pago);
        return data;
    }
    return null;

}

function guardarPago(data) {

    data = JSON.stringify(data);
    $.ajax({
        url: '/compras/compra2/guardarpagos',
        type: 'post',
        data: {"data": data},
        dataType: 'json',
        async: true,
        success: function(respuesta) {
//                alert(respuesta+"hola");
            if (respuesta == null) {
                mostarVentana("error", "TIMEOUT");
            } else if (respuesta.result == "EXITO") {
                $('#modalPagos').hide();
                mostarVentana("success-title", "DATOS ALMACENADOS EXITOSAMENTE");
            } else if (respuesta.result == "ERROR") {
                if (respuesta.mensaje == 23505) {
                    mostarVentana("warning", "DATOS DUPLICADOS");
                } else {
                    mostarVentana("warning", "OCURRIO UN ERROR");
                }
            }
        },
        error: function(event, request, settings) {
            alert(mostrarError("OCURRIO UN ERROR"));
        }
    });
}
