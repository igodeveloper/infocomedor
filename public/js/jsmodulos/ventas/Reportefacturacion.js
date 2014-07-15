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
    formatearFechas();
    $("#cargar-karrito").click(function() {
        loadAutocompleteProducto();
        $("#buscar-karrito").attr("disabled", true);
        $("#cabecera-factura").show();
    	$("#addProductos").show();
    	
    });
    
    $("#cant-item").change(function() {
    	var TotalParcial = 0;
        var cantidad = $('#cant-item').attr("value");
        var precioUnitario = $('#precio-item').attr("value");
        TotalParcial = cantidad * precioUnitario;
        $('#total-item').attr("value", TotalParcial);
    	
    });
    $("#precio-item").change(function() {
    	var TotalParcial = 0;
        var cantidad = $('#cant-item').attr("value");
        var precioUnitario = $('#precio-item').attr("value");
        TotalParcial = cantidad * precioUnitario;
        $('#total-item').attr("value", TotalParcial);
    	
    });
    
    $("#buscar-karrito").click(function() {
        $("#cargar-karrito").attr("disabled", true);
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

   
    $("#cerrar-bot").click(function() {
        $('#modalEditar').hide();
        cleanFormModalHide("exit");
        blockclientdata('clear');
    });
    $("#cerrar-detalle").click(function() {
        $('#modalDetalleFactura').hide();
        
    });
     $("#cancelar-detalle").click(function() {
        $('#modalDetalleFactura').hide();
        
    });

    $("#cancelar-bot").click(function() {
        $('#modalEditar').hide();

        cleanFormModalHide("exit");
        blockclientdata('clear');
        bloqueamosCeldas('desbloqueamos');
        CleanFormItems();
    });
    
    $("#cerrar-transactions").click(function() {
        $('#modalKarrito').hide();
//        blockclientdata('clear','karrito');
    });
    $("#cerrar-bot-karrito").click(function() {
        $('#modalKarrito').hide();
    });
    
    $("#select-transactions").click(function() {	
    	var s;
    	s = jQuery("#grillaRegistroKarrito").jqGrid('getGridParam','selarrrow');
        var rowsID = [];
        var rowwsLength = s.length; 
        var data = [];
    	
    	for (var i = 0; i < s.length; i++) {
         	var rows = jQuery('#grillaRegistroKarrito').jqGrid ('getRowData', s[i]);
         	data.push(rows);
            rowsID.push(s[i]);
    	}
    	
    	if(loadGridModalFacturacion(data)){
    		var j;
            for (j=0; j < rowwsLength; j++) {
              $('#grillaRegistroKarrito').jqGrid('delRowData',rowsID[j]);
            }
    		$("#grillaRegistroKarrito").trigger("reloadGrid");	
    	}
        //hacemos editable las grillas
        jQuery("#grillaComprasModal").setColProp('KAR_CANT_FACTURAR',{editable:true});
        // jQuery("#grillaComprasModal").setColProp('KAR_PRECIO_FACTURAR',{editable:true});
        
        $('#cabecera-factura').show();
        $('#modalKarrito').hide();
//    	console.log(data);
    });
    
    
    $("#nuevoCompra").click(function() {
        verificarCaja();
    // $("#gbox_grillaComprasModal").show();
    // $("#gbox_grillaDetalleFactura").hide();
    });

    $("#addItem").click(function() {
        addItem();
    });
    $("#addPago").click(function() {
        addPagos();
    });
    $("#reloadItem").click(function() {
        CleanFormItems();
        bloqueamosCeldas('desbloqueo');
    });
     $("#cerrar-venta").click(function() {
        // $.unblockUI();
        $('#modalEditar').show();
        $('#modalFormaPago').hide();
        $("#grillaRegistroPagoVenta").jqGrid("clearGridData", true);
        $('#factura-modal-pagos').attr("value", null);
    });
    $("#guardar-venta").click(function() {
         var data = obtenerGrid();
         var pago = obtenerPagos();
        if (data,pago) {enviarParametros(data,pago);}
    });

    $("#guardar").click(function() {  
        var data = obtenerGrid();
        if (data) {
            // $.blockUI();
            
            cargarGrillaRegistroPagoVenta();
            $('#modalFormaPago').show();
            $('#modalEditar').hide();
            $('#montoPago-modal-pagos').attr("value", data.venta.montoTotal);
            $('#saldoPendiente-modal-pagos').attr("value", data.venta.montoTotal);
            $('#saldoPendiente-modal-pagos').attr("disabled", true);
        }
    });

    $.getJSON(table+"/clientedata", function(data) {
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
    $("#imprimirReporte").click(function() {                           
            imprimirreporte();
    });
}); // cerramos el ready de js
function imprimirreporte(){
    var dataString = JSON.stringify(filtrosbusqueda());      
    $.ajax({
        url: table+'/imprimirreporte',
        type: 'post',
        data: {"parametros":dataString},
        dataType: 'json',
        async: false,
        success: function(respuesta) {
            if (respuesta == null) {
                mostarVentana("error", "TIMEOUT");
            } else if (respuesta.result == "EXITO") {
                window.open('../tmp/'+respuesta.archivo);
            }                                        
            $.unblockUI();
        },
        error: function(event, request, settings) {
            $.unblockUI();
            //alert(mostrarError("OCURRIO UN ERROR"));
            mostarVentana("warning-block-title", "Ocurrio un error en la generacion del reporte");
        }        
    });                  	
}
function formatearFechas(){
        $("#FechaFactura-modal").datepicker();
        $("#FechaVencimiento-modal").datepicker();
        $("#FechaFactura-modal").datepicker("option", "dateFormat", "yy-mm-dd");
        $("#FechaVencimiento-modal").datepicker("option", "dateFormat", "yy-mm-dd");
        $("#FechaFactura-modal").datepicker("setDate", new Date());
        $("#FechaVencimiento-modal").datepicker("setDate", new Date());
        $("#FechaVencimiento-modal").datepicker();
        $("#FechaVencimiento-modal").datepicker("option", "dateFormat", "yy-mm-dd");
        $("#fechavencimiento-filtro").datepicker();
        $("#fechavencimiento-filtro").datepicker("option", "dateFormat", "yy-mm-dd");
        $("#fechaemision-filtro").datepicker();
        $("#fechaemision-filtro").datepicker("option", "dateFormat", "yy-mm-dd");
        $("#FechaFactura").datepicker();
        $("#FechaFactura").datepicker("option", "dateFormat", "dd-mm-yy");
        $("#FechaFactura").datepicker("setDate", new Date());

        $("#FechaVencimiento-modal-idioma").hide();
        $("#FechaVencimiento-modal").hide();
         $("#fechavencimiento-filtro").hide();
     }

function loadGridModalFacturacion(data){
//	$("#grillaComprasModal").trigger("reloadGrid");
	for (var i = 0; i < data.length; i++) {
		var rows = jQuery("#grillaComprasModal").jqGrid('getRowData');
		jQuery("#grillaComprasModal").jqGrid('addRowData', (rows.length) + 1, data[i]);
	}
    if(data[i-1].COD_CLIENTE){

    }
    $("#codigocliente-modal").val(data[i-1].COD_CLIENTE);
    validacliente('cod');
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
        url: table+'/getkarritodata',
        type: 'post',
        dataType: 'json',
        data: {
            "data": data
        },
        async: true,
        success: function(respuesta) {
            console.log(respuesta);
            if(respuesta.result == 'void'){
                console.log("llegue");
                mostarVentana("warning-block-karrito", "No se encontraron pedidos con lo datos ingresados");
            } else {
                cargarGrillaFacturasModalKarrito();
                $("#grillaRegistroKarrito").jqGrid("clearGridData", true);
                for (var i = 0; i < respuesta.length; i++) {
                    var rows = jQuery("#grillaRegistroKarrito").jqGrid('getRowData');
                    console.log(respuesta[i]);
                    jQuery("#grillaRegistroKarrito").jqGrid('addRowData', (rows.length) + 1, respuesta[i]);    
                }
            }
        	
	       
        },
        error: function(event, request, settings) {
            mostarVentana("warning-block-karrito", "Verifique los datos");
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
function ocultarWarningBlockKarrito() {
    $("#warning-block-karrito").hide(300);
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
        setTimeout("ocultarWarningBlock()", 1000);
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
    }else if (box == "warning-block-karrito") {
        $("#warning-message-karrito").text(mensaje);
        $("#warning-block-karrito").show();
        setTimeout("ocultarWarningBlockKarrito()", 5000);
    }else if (box == "warning-block-pagos") {
        $("#warning-message-pagos").text(mensaje);
        $("#warning-block-pagos").show();
        setTimeout("ocultarWarningBlockPagos()", 5000);
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
        bloqueamosCeldas('desbloqueamos');
//        BlockProveedorData("additem");
    }

}

function addPagos() {
//    alert("ingreso");
    var data = obtenerPagosDetalles();
//    alert(JSON.stringify(data));
    if (data !== null) {
        console.log(data);
        var rows = jQuery("#grillaRegistroPagoVenta").jqGrid('getRowData');
        jQuery("#grillaRegistroPagoVenta").jqGrid('addRowData', (rows.length) + 1, data);
        var saldo = parseInt($("#saldoPendiente-modal-pagos").attr("value"))-parseInt(data.MONTO_PAGO);
        $("#saldoPendiente-modal-pagos").attr("value",saldo);
        $("#montoPago-modal-pagos").attr("value",saldo);
        $("#banco-modal-pagos").attr("value",null);
        $("#cheque-modal-pagos").attr("value",null);

    }

}

function addrequiredattr(id,focus){
    $('#'+id).attr("required", "required");
    if(focus == 1)
        $('#'+id).focus();
}

function obtenerJsonDetalles() {
    var jsonObject = new Object();

    var mensaje = 'Ingrese: ';
    var focus = 0;

    jsonObject.COD_KARRITO = 0;
    jsonObject.KAR_FECH_MOV = $('#FechaFactura-modal').attr("value");  
    jsonObject.COD_CLIENTE = $('#codigocliente-modal').attr("value");  
    jsonObject.CLIENTE_DES = $('#razonsocial-modal').attr("value");  
    jsonObject.COD_MESA = 1;
    jsonObject.COD_PRODUCTO = $('#codproducto-item').attr("value");
    jsonObject.PRODUCTO_DESC = $('#descripcionproducto-item').attr("value");
    jsonObject.KAR_CANT_PRODUCTO = parseFloat($('#cant-item').attr("value"));
    jsonObject.KAR_CANT_FACTURAR = parseFloat($('#cant-item').attr("value"));
    jsonObject.KAR_PRECIO_PRODUCTO =  parseInt($("#total-item").attr("value"));
    jsonObject.KAR_PRECIO_FACTURAR =  parseInt($("#total-item").attr("value"));
    jsonObject.COD_MOZO = 1;  
    jsonObject.FACT_NRO = 0;  
    jsonObject.ESTADO = 'PE';  
    
    if (jsonObject.PRODUCTO_DESC === "" || jsonObject.PRODUCTO_DESC === null) {
        mensaje+= ' | Descripci\u00F3n del producto';
        focus++;
        addrequiredattr('descripcionproducto-item',focus);  
    } 
    if (jsonObject.KAR_CANT_PRODUCTO === "" || jsonObject.KAR_CANT_PRODUCTO.length == 0 || jsonObject.KAR_CANT_PRODUCTO === 0) {
        mensaje+= ' | Cantidad mayor a cero';
        focus++;
        addrequiredattr('cant-item',focus);
    }

    if (mensaje != 'Ingrese: '){
        mensaje+= ' |';
        mostarVentana("warning", mensaje);
        return null;
    }else {
        return jsonObject;
    }
}

function obtenerPagosDetalles() {
    var data = new Object();
    var mensaje = '';
    var focus = 0;
     
    if ($('#montoPago-modal-pagos').val() === "" || $('#montoPago-modal-pagos').val() < 0 || isNaN($('#montoPago-modal-pagos').val())) {
        mensaje+= ' | El monto ingresado no es correcto ';
        focus++;
        addrequiredattr('montoPago-modal-pagos',focus);
    } 
    if (parseInt($('#saldoPendiente-modal-pagos').val()) < parseInt($('#montoPago-modal-pagos').val())) {
        mensaje+= ' | El monto a pagar supera el saldo pendiente';
        focus++;
        addrequiredattr('montoPago-modal-pagos',focus);
    } 
    if (($('input[name=formaPagoCheque]').is(':checked')) && ($("#banco-modal-pagos").val() === "" || $("#cheque-modal-pagos").val() === "")) {
        mensaje+= ' | Ingrese los datos del cheque';
        focus++;
        addrequiredattr('banco-modal-pagos',focus);
        addrequiredattr('cheque-modal-pagos',focus++);
    }
    if(mensaje !=''){
        mensaje+= ' |';
        mostarVentana("warning-block-pagos", mensaje);
        return null;
    } else {
        
        data.MONTO_PAGO = $('#montoPago-modal-pagos').val();    
        if ($('input[name=formaPagoCheque]').is(':checked')) {
            data.FORMA_PAGO = 'CHEQUE';
            data.DES_BANCO = $("#banco-modal-pagos").val();
            data.NRO_CHEQUE = $("#cheque-modal-pagos").val();
        } else {
            data.FORMA_PAGO = 'EFECTIVO';
            data.nombre_banco = "0";
            data.numero_cheque = 0;
        }
       
    }
        data.CODIGO_CAJA = $("#codigoCaja-modal-ventas").val();
        data.USUARIO_CAJA = $("#usuarioCaja-modal-ventas").val();
        parseFloat(data.MONTO_PAGO);
//        alert(data.monto_pago);
        return data;
    }


function validacliente(data, what) {
    var dataString = new Object();
    dataString.value = "vacio";
    dataString.reference = data;
    var pago = "";
    if (what === "Detalle") {
        pago = "Detalle";
    } 
    console.log(pago+"--"+data);
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
            url: table+'/validaclientedata',
            type: 'post',
            dataType: 'json',
            data: {
                "parametro": dataString
            },
            async: false,
            success: function(respuesta) {
                // alert(respuesta.cod+"-"+respuesta.name+"-"+respuesta.ruc);
                
                if(respuesta.cod){
                    $("#codigocliente-modal" + pago).attr("value", respuesta.cod);
                    $("#ruc-modal" + pago).attr("value", respuesta.ruc);
                    $("#razonsocial-modal" + pago).attr("value", respuesta.name);
                    blockclientdata('block','Detalle');
                } else {
                    mostarVentana("warning", 'No se encontro el valor');    
                }

            },
            error: function(event, request, settings) {
                mostarVentana("warning", 'No se encontro el valor');
            }
        });
    } else {
        mostarVentana("warning", 'Ingrese un valor valido');
    }

}
function blockclientdata(action,what) {
    var detalle = what;
	if(action == 'block'){
		 $("#codigocliente-modal"+detalle).attr("disabled", true);
	     $("#ruc-modal"+detalle).attr("disabled", true);
	     $("#razonsocial-modal"+detalle).attr("disabled", true);
	    // $("#codcliente-karrito").attr("disabled", true);
        // $("#namecliente-karrito").attr("disabled", true);
         //$("#reloadFilter").attr("disabled", true);
	} else {
			 $("#codigocliente-modal").attr("value", null);
		     $("#ruc-modal").attr("value", null);
		     $("#razonsocial-modal").attr("value", null);
		     $("#codigocliente-modal").attr("disabled", false);
		     $("#ruc-modal").attr("disabled", false);
		     $("#razonsocial-modal").attr("disabled", false);
		     $("#grillaRegistroKarrito").jqGrid("clearGridData");
				$("#codcliente-karrito").attr("value", null);
		         $("#namecliente-karrito").attr("value", null);
		         $("#codcliente-karrito").attr("disabled", false);
		         $("#namecliente-karrito").attr("disabled", false);
	}


}

function loadAutocompleteProducto() {
    $.getJSON(table+"/productodata", function(data) {
        var descripcionProducto = [];
        var codigoProducto = [];



        $(data).each(function(key, value) {
    
        if(value.COD_PRODUCTO_TIPO > 1){ // solo productos que no sean materia prima, los cuales se dan de alta por compra
                descripcionProducto.push(value.PRODUCTO_DESC);
                codigoProducto.push(value.COD_PRODUCTO);
            }
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
                    $("#addItem").removeAttr('disabled');
                    bloqueamosCeldas('block');
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
         mostarVentana("warning","No se encontraron datos, verifique los datos ingresados");
                addrequiredattr('codproducto-item',1);
    }

}
function bloqueamosCeldas(action){
    if(action=='block'){
        $('#codproducto-item').attr("disabled", true);
        $('#descripcionproducto-item').attr("disabled", true);
        $('#unidadmedida-item').attr("disabled", true);
        
    }else{
        $('#codproducto-item').attr("disabled", false);
        $('#descripcionproducto-item').attr("disabled", false);
         $('#unidadmedida-item').attr("disabled", false);
        $('#cant-item').attr("disabled", false);
        $('#precio-item').attr("disabled", false);
    }

}
function calculoTotalParcial() {
    var TotalParcial = 0;
    var cantidad = $('#cantidad-item').attr("value");
    var precioUnitario = $('#preciounitario-item').attr("value");
    TotalParcial = cantidad * precioUnitario;
    $('#totalparcial-item').attr("value", TotalParcial);
}

function enviarParametros(data,pago) {
//	console.log(JSON.stringify(data));
$('#modalFormaPago').hide();
$('#modalEditar').show();
    // $.unblockUI();
	var dataVenta = JSON.stringify(data.venta);
    var dataVentaDetalle = JSON.stringify(data.ventaDetalle);
	var dataVentaPago = JSON.stringify(pago);
    $.ajax({
        url: table+'/guardar',
        type: 'post',
        data: {"dataVenta": dataVenta, "dataVentaDetalle": dataVentaDetalle, "dataVentaPago":dataVentaPago},
        dataType: 'json',
        async: true,
        success: function(respuesta) {
//                alert(respuesta+"hola");
            if (respuesta == null) {
                
                
                mostarVentana("error", "TIMEOUT");

            } else if (respuesta.result == "EXITO") {
                mostarVentana("success-title", "Datos Almacenados exitosamente");
                
                $("#grillaRegistroPagoVenta").jqGrid("clearGridData", true);
                $('#saldoPendiente-modal-pagos').attr("value", null);
                $('#modalEditar').hide();
                 // $('#modal-footer-ventas').show();
                cleanFormModalHide("exit");
            } else if (respuesta.result == "ERROR") {
                 $('#modalFormaPago').hide();
                  // $('#modal-footer-ventas').show();
                if (respuesta.mensaje == 23505) {
                    mostarVentana("warning", "Datos duplicados");
                   
                } else {
                    mostarVentana("warning", "Intente mas tarde");
                }
            }
        },
        error: function(event, request, settings) {
             $('#modalFormaPago').hide();
            mostarVentana("warning", "Intente mas tarde");
        }
    });
}

function buscar() {
    var dataJsonBusqueda = JSON.stringify(filtrosbusqueda());
    // console.log(dataJsonBusqueda);
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
            $("#grillaCompras")[0].addJSONData(respuesta);
            clearfilters();
            $.unblockUI();
        },
        error: function(event, request, settings) {
            $.unblockUI();
            
        }
    });
}


function editarRegistro(parametros) {
    $("#modalDetalleFactura").show();
    $('#factura-modalDetalle').attr("value", parametros.FAC_NRO);
    
    $('#codigocliente-modalDetalle').attr("value", parametros.COD_CLIENTE);
    validacliente('cod','Detalle');
    var control_1 = parametros.CONTROL_FISCAL.substr(0, 3);
    var control_2 = parametros.CONTROL_FISCAL.substr(4, 3);
    var control_3 = parametros.CONTROL_FISCAL.substr(8, (parametros.CONTROL_FISCAL.length - 8));
    $('#controlfiscal-modalDetalle_1').attr("value", control_1);
    $('#controlfiscal-modalDetalle_2').attr("value", control_2);
    $('#controlfiscal-modalDetalle_3').attr("value", control_3);
    $('#FechaFactura-modalDetalle').attr("value", parametros.FAC_FECHA_EMI);
    $('#FechaVencimiento-modalDetalle').attr("value", parametros.FAC_FECH_VTO);
    cargarGrillaFacturasDetalle();
    loadGridDetails(parametros.FAC_NRO);
   
}
function loadGridDetails(factura){
	
	 $.ajax({
	        url: table+'/modaleditar',
	        type: 'post',
	        data: {
	            "NumeroInterno": factura
	        },
	        dataType: 'json',
	        async: false,
	        success: function(respuesta) {

                if(respuesta.result == 'void'){
    
                } else {
                    $("#grillaDetalleFactura").jqGrid("clearGridData", true);
                    for (var i = 0; i < respuesta.length; i++) {
                        var rows = jQuery("#grillaDetalleFactura").jqGrid('getRowData');
                        console.log(respuesta[i]);
                        jQuery("#grillaDetalleFactura").jqGrid('addRowData', (rows.length) + 1, respuesta[i]);    
                     }
                }
	            $.unblockUI();
	        },
	        error: function(event, request, settings) {
	            $.unblockUI();
	        
	        }
	    });
	
}
function cargarPagos(facturaCompra) {

    $.ajax({
        url: table+'/modalpagos',
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

function addrequiredattr(id,focus){
    $('#'+id).attr("required", "required");
    if(focus == 1)
        $('#'+id).focus();
}

function obtenerGrid() {
	
	
	var jsonObject = new Object();
    var dataObjectVentaDetalle = new Object();
    var dataObjectVenta = new Object();

    dataObjectVentaDetalle = jQuery("#grillaComprasModal").jqGrid('getRowData'); // saca datos de la grilla en formato json
    var montoTotal = 0;

    //Calculamos el precio total
    for (var i = 0; i < dataObjectVentaDetalle.length; i++) {
        // montoTotal = montoTotal + parseInt(dataObjectVentaDetalle[i].KAR_PRECIO_PRODUCTO); // ya no se usa se usa el precio_facturar
        montoTotal = montoTotal + parseInt(dataObjectVentaDetalle[i].KAR_PRECIO_FACTURAR);

    }
    
    dataObjectVenta.codcliente = $('#codigocliente-modal').attr("value");
    var controlFiscal = ($('#controlfiscal-modal_1').attr("value") + "-" + $('#controlfiscal-modal_2').attr("value") + "-" + $('#controlfiscal-modal_3').attr("value"));
    dataObjectVenta.controlFiscal = controlFiscal;
    dataObjectVenta.fechaEmision = $('#FechaFactura-modal').attr("value");
    dataObjectVenta.fechaVencimiento = $('#FechaVencimiento-modal').attr("value");
    dataObjectVenta.montoTotal = montoTotal;
    dataObjectVenta.codigo_caja = $('#codigoCaja-modal-ventas').attr("value");
    dataObjectVenta.usuario_caja = $('#usuarioCaja-modal-ventas').attr("value");

    var mensaje = 'Ingrese:';
    var focus = 0;
    
    // validar los campos por orden 
    if(dataObjectVenta.codcliente === "" || dataObjectVenta.codcliente === null){
        mensaje+= ' | Codigo del cliente';
        focus++;
        addrequiredattr('codigocliente-modal',focus);
    }

    if($('#controlfiscal-modal_3').attr("value") === ""){
        mensaje+= ' | Control fiscal';
        focus++;
        addrequiredattr('controlfiscal-modal_3',focus);
    }
     
    if(dataObjectVentaDetalle.length < 1){
        mensaje+= ' | Un producto para la venta';
        focus++;
        addrequiredattr('controlfiscal-modal_3',focus);
    }

    if (mensaje != 'Ingrese:') {
        mensaje+= ' |';
        mostarVentana("warning", mensaje);
        return null;
    } else {
    	jsonObject.venta = dataObjectVenta;
        jsonObject.ventaDetalle = dataObjectVentaDetalle;
        return jsonObject;
    }
    return null;
}

function obtenerPagos() {
    
    
    var pagoGridDetalles = new Object();

    pagoGridDetalles = jQuery("#grillaRegistroPagoVenta").jqGrid('getRowData');
    var totalFactura = parseInt($("#totalFactura-modal-pagos").attr("value"));
    var montoTotal = 0;

    //Calculamos el precio total
    for (var i = 0; i < pagoGridDetalles.length; i++) {
        montoTotal = montoTotal + parseInt(pagoGridDetalles[i].MONTO_PAGO);

    }




    var mensaje = 'Ingrese:';
    var focus = 0;

    
    // validar los campos por orden      
    if(pagoGridDetalles.length < 1){
        mensaje+= ' | Un pago como minimo';
        focus++;
        addrequiredattr('controlfiscal-modal_3',focus);
    }
    if(parseInt(totalFactura) < parseInt(montoTotal)){
        mensaje+= ' | La cantidad de pagos ingresados supera el monto de la factura';
    }
    if(parseInt(totalFactura) > parseInt(montoTotal)){
        mensaje+= ' | La cantidad de pagos ingresados es menor al monto de la factura';
    }
    if (mensaje != 'Ingrese:') {
        mensaje+= ' |';
        mostarVentana("warning-block-pagos", mensaje);
        return null;
    } else {

        return pagoGridDetalles;
    }
    return null;
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
    // obj.fechavencimiento = $('#fechavencimiento-filtro').attr("value");
    var estado = document.getElementById("estado-filtro");
    obj.estado = estado.options[estado.selectedIndex].value;
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

	$('#idcliente').attr("value", null);
    $('#codigocliente-modal').attr("value", null);
    $('#ruc-modal').attr("value", null);
    $('#razonsocial-modal').attr("value", null);
    $('#controlfiscal-modal_3').attr("value", null);
    $('#FechaVencimiento-modal').attr("value", null);
    $('#FechaFactura-modal').attr("value", new Date());
    $("#grillaComprasModal").jqGrid("clearGridData");
    if (from === "exit") {
        $("#grillaComprasModal").trigger("reloadGrid");
        $("#codigocliente-modal").attr("disabled", false);
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
    $('#cant-item').attr("value", 0);
    $('#precio-item').attr("value", 0);
    $('#total-item').attr("value", 0);

    
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

function verificarCaja(){
     $.ajax({
        url: table+'/ventasusuario',
        type: 'post',
        dataType: 'json',
        async: true,
        success: function(respuesta) {
            console.log(JSON.stringify(respuesta));
            if(respuesta.COD_USUARIO_CAJA){
                $('#codigoCaja-modal-ventas').attr("value", respuesta.COD_CAJA);
                $('#usuarioCaja-modal-ventas').attr("value", respuesta.COD_USUARIO_CAJA);
                levantamodal();
            }else{
                mostarVentana("warning-title", "Debe tener una caja abierta para acceder a facturacion");    
            }
            
        },
        error: function(event, request, settings) {
             mostarVentana("warning-title", "Debe tener una caja abierta para acceder a facturacion");
        }
    });    
}

function levantamodal(){
    $("#guardar-registro").html("Guardar");

        $("#cabecera-factura").hide();
        $(".btn-compra").show();
        $("#buscar-karrito").show();
        $("#cargar-karrito").show();
        $("#buscar-karrito").attr("disabled",false);
        $("#cargar-karrito").attr("disabled",false);
        $("#editar-nuevo").html("Nuevo Registro");
        $("#numeroFacturaLb").css("display", "none");
        $("#numeroFacturaIn").css("display", "none");
        $("#guardar").show();
        $("#addProductos").hide();
        $("#addItem").attr("disabled", "disabled");
        $('#cant-item').attr("value", 0);
        $('#precio-item').attr("value", 0);
        $('#total-item').attr("value", 0);
        $("#total-item").attr("disabled", "disabled");
        cleanFormModalHide("exit");
        blockclientdata('clear');
        bloqueamosCeldas('desbloqueamos');
        CleanFormItems();
        formatearFechas();
        jQuery("#grillaComprasModal").setColProp('KAR_CANT_FACTURAR',{editable:false});
        jQuery("#grillaComprasModal").setColProp('KAR_PRECIO_FACTURAR',{editable:false});
        $('#modalEditar').show();
}

function selectFormaPago(formaPago) {
    if (formaPago == "cheque") {
        $('#banco-modal-pagos').attr("disabled", false);
        $('#cheque-modal-pagos').attr("disabled", false);
        $('#montoPago-modal-pagos').attr("disabled", false);
        $('input[name=formaPagoEfectivo]').attr('checked', false);
        $("#montoPago-modal-pagos").attr("disabled", false);
    } else if(formaPago =='efectivo'){      
        $('#banco-modal-pagos').attr("disabled", true);
        $('#cheque-modal-pagos').attr("disabled", true); 
        $('#banco-modal-pagos').attr("value", null);
        $('#cheque-modal-pagos').attr("value", null);
        $('input[name=formaPagoCheque]').attr('checked', false);
        $("#montoPago-modal-pagos").attr("disabled", false);
         
    }

}
