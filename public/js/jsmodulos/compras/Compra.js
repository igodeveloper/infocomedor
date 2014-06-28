var pathname = window.location.pathname;
var table = pathname;
$().ready(function() {
    /*
     * Formateamos los campos de fecha
     */
     setInputsDate();



    $("#buscarCompra").click(function() {
        buscar();
    });

    $("#cerrar-bot-modal-egreso").click(function() {
        reloadEgresoModal();

    });

    $("#cancelar-bot-modal-egreso").click(function() {
        reloadEgresoModal();
    });

    $("#seleccionar-egreso").click(function() {
        insertaEgreso();
    });

    $("#cerrar-bot-pagos").click(function() {
        $('#modalPagos').hide();
        $('#codigoCaja-modal-pagos').attr("value", 0);
        $('#usuarioCaja-modal-pagos').attr("value", 0);
    });
    $("#cancelar-bot-pagos").click(function() {
        $('#modalPagos').hide();
        $('#codigoCaja-modal-pagos').attr("value", 0);
        $('#usuarioCaja-modal-pagos').attr("value", 0);

    });




    $("#cerrar-bot").click(function() {
        $('#modalEditar').hide();
        cleanFormModalHide("exit");
    });

    $("#cancelar-bot").click(function() {
        $('#modalEditar').hide();
        cleanFormModalHide("exit");
        reloadproveedor();
    });

    $("#nuevoCompra").click(function() {
        
        setInputsDate();
        $("#guardar-registro").html("Guardar");
        $(".btn-compra").show();
        $("#editar-nuevo").html("Nuevo Registro");
        $("#numeroFacturaLb").css("display", "none");
        $("#numeroFacturaIn").css("display", "none");
        $("#guardar").show();
        $("#addProductos").show();
        $("#addItem").attr("disabled", "disabled");
        $('#cantidad-item').attr("value", 0);
        $('#preciounitario-item').attr("value", 0);
        $('#totalparcial-item').attr("value", 0);
        $("#totalparcial-item").attr("disabled", "disabled");
        $("#unidadmedida-item").attr("disabled", "disabled");
        $('#estado-compra').attr("value", 'T');
        loadAutocompleteProducto();
        $('#modalEditar').show();
    });

    $("#addItem").click(function() {
        addItem();
    });
    
    $("#reloadProveedor").click(function() {
        
        reloadproveedor();
    });
    
    $("#reloadProducto").click(function() {
        CleanFormItems();
    });

    $("#guardar").click(function() {
//        if (!confirm("Esta seguro de que desea almacenar los datos?"))
//            return;
        var data = obtenerGrid();
        if (data !== null) {
            enviarParametrosCompras(data);
        }
    });

    $("#guardar-pagos").click(function() {
//        if (!confirm("Esta seguro de que desea almacenar el pago?"))
//            return;
        var data = obtenerMontoPago();
        if (data !== null) {
            guardarPagos(data);
        }
    });
    $.getJSON(table+"/proveedordata", function(data) {
        var nombreProveedor = [];
        var rucProveedor = [];
        var codProveedor = [];
        $(data).each(function(key, value) {
            nombreProveedor.push(value.PROVEEDOR_NOMBRE);
            rucProveedor.push(value.PROVEEDOR_RUC);
            codProveedor.push(value.COD_PROVEEDOR);
            //            console.log(value.PROVEEDOR_NOMBRE);
        });

        $("#razonsocial-modal").autocomplete({
            source: nombreProveedor
        });
        $("#proveedor").autocomplete({
            source: nombreProveedor
        });
        $("#ruc-modal").autocomplete({
            source: rucProveedor
        });
        $("#codigoproveedor-modal").autocomplete({
            source: codProveedor
        });

        $("#codproveedor-filtro").autocomplete({
            source: codProveedor
        });
        $("#nameproveedor-filtro").autocomplete({
            source: nombreProveedor
        });
    });

}); // cerramos el ready de js

function reloadEgresoModal(){
    $('#modalBuscarEgreso').hide();
    jQuery("#grillaModalEgreso").jqGrid("clearGridData", true);
}

function setInputsDate(){
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
}


function reloadproveedor(){
    $("#codigoproveedor-modal").attr("value", null);
    $("#ruc-modal").attr("value", null);
    $("#razonsocial-modal").attr("value", null);
    $("#codigoproveedor-modal").attr("disabled", false);
    $("#ruc-modal" ).attr("disabled", false);
    $("#razonsocial-modal").attr("disabled", false);

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
function ocultarWarningBlockModalEgreso() {
    $("#warning-block-modal-egreso").hide(700);
}


function mostarVentana(box, mensaje) {
    $("#success-block").hide();
    $("#info-block-listado").hide();
    if (box == "warning") {
        $("#warning-message").text(mensaje);
        $("#warning-block").show();
        setTimeout("ocultarWarningBlock()", 4000);
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
        setTimeout("ocultarWarningBlockPagos()", 4500);
    } else if (box == "warning-block-modal-egreso") {
        $("#warning-message-modal-egreso").text(mensaje);
        $("warning-block-modal-egreso").show();
        setTimeout("ocultarWarningBlockModalEgreso()", 4500);
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
    
    var mensaje = 'Ingrese los campos: ';
    var focus = 0;
    
    
    
    if (jsonObject.codproducto === "" || jsonObject.codproducto === null) {
        mensaje+= ' | C\u00F3digo del producto ';
        focus++;
        addrequiredattr('codproducto-item',focus);
    }  
   
    if (jsonObject.cantidad === "" || jsonObject.cantidad === "0" || jsonObject.cantidad === null) {
        mensaje+= ' | Cantidad ';
        focus++;
        addrequiredattr('cantidad-item',focus);
        
    } 
    if (jsonObject.preciounitario === "" || jsonObject.preciounitario === null || jsonObject.preciounitario === "0") {
        mensaje+= ' | Precio unitario ';
        focus++;
        addrequiredattr('preciounitario-item',focus);
        
    } 
    if (impuesto === -1 || impuesto === null) {
        mensaje+= ' | Impuesto ';
        focus++;
        addrequiredattr('tipoimpuesto-item',focus);
        
    } 
   
    
    if(mensaje != 'Ingrese los campos: '){
        mensaje+= ' |';
        mostarVentana("warning", mensaje);
        return null;
        
    }else {
        jsonObject.idproveedor = parseInt(jsonObject.idproveedor);
        jsonObject.codproducto = parseInt(jsonObject.codproducto);
        jsonObject.cantidad = parseInt(jsonObject.cantidad);
        jsonObject.preciounitario = parseInt(jsonObject.preciounitario);
        jsonObject.totalparcial = parseInt(jsonObject.totalparcial);
//        alert(JSON.stringify(jsonObject));
        return jsonObject;
    }
   
}

function validaProveedor(data, what) {
    var dataString = new Object();
    dataString.value = "vacio";
    dataString.reference = data;
    var pago = "";
    if (what === "pago") {
        pago = "-pagos";
    } else {
    }
    switch (data) {
        case 'cod':
            {
                dataString.value = $('#codigoproveedor-modal' + pago).attr("value");
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
            url: table+'/validaproveedordata',
            type: 'post',
            dataType: 'json',
            data: {
                "parametro": dataString
            },
            async: false,
            success: function(respuesta) {
                
                if(respuesta.cod){
                    $("#codigoproveedor-modal" + pago).attr("value", respuesta.cod);
                    $("#ruc-modal" + pago).attr("value", respuesta.ruc);
                    $("#razonsocial-modal" + pago).attr("value", respuesta.name);
                    $("#codigoproveedor-modal" + pago).attr("disabled", true);
                    $("#ruc-modal" + pago).attr("disabled", true);
                    $("#razonsocial-modal" + pago).attr("disabled", true);
                }else{
                    mostarVentana("warning", "No se encontraron valores, intente de nuevo");
                }
                
            },
            error: function(event, request, settings) {
                mostarVentana("warning",'Verfique los datos ingresados');
            }
        });
    } else {
        mostarVentana("warning","Inserte un valor antes de validar");
        addrequiredattr('codigoproveedor-modal',1);
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
                console.log(respuesta);
                if(respuesta != null){
                    $('#codproducto-item').attr("value", respuesta.cod);
                    $('#descripcionproducto-item').attr("value", respuesta.descripcion);
                    $('#codUnidadMedida-item').attr("value", respuesta.unimedcod);
                    $('#unidadmedida-item').attr("value", respuesta.unimeddesc);
//                  $("#addItem").attr("enabled", "enabled");
                    $("#addItem").removeAttr('disabled');
                    $("#codproducto-item").attr('disabled',true);
                    $("#descripcionproducto-item").attr('disabled',true);    
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
        url: table+'/guardar',
        type: 'post',
        data: {"dataCompra": dataCompra, "dataCompraDetalle": dataCompraDetalle, "dataCompraCantItems": dataCompraCantItems},
        dataType: 'json',
        async: true,
        success: function(respuesta) {
//                alert(respuesta+"hola");
            if (respuesta == null) {
                mostarVentana("error", "TIMEOUT");
            } else if (respuesta.result == "EXITO") {
                mostarVentana("success-title", "Factura almacenada exitosamente");
                $('#modalEditar').hide();
                cleanFormModalHide("exit");
            } else if (respuesta.result == "ERROR") {
                if (respuesta.mensaje == 23505) {
                    mostarVentana("warning", "Los datos ingresados estan duplicados ");
                } else {
                    mostarVentana("warning", "Verifique los datos ingresados");
                }
            }
        },
        error: function(event, request, settings) {
            mostarVentana("warning", "Verifique los datos ingresados");
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
            $("#grillaCompras")[0].addJSONData(respuesta);
            clearfilters();
            $.unblockUI();
        },
        error: function(event, request, settings) {
            $.unblockUI();
            mostarVentana("warning", "Verifique los datos ingresados");
        }
    });
}


function editarRegistro(parametros) {
    cleanFormModalHide("edit");
    $("#modalEditar").show();
    $("#reloadProveedor").hide();
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
    $("#modalEditar").show();
    $.ajax({
        url: table+'/modaleditar',
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
//            mostarVentana("warning", "Verifique los datos ingresados");
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
//            alert(mostrarError("OCURRIO UN ERROR"));
        }
    });
}

function pagos(parametros) {

     $.ajax({
        url: table+'/compraspagosusuario',
        type: 'post',
        dataType: 'json',
        async: true,
        success: function(respuesta) {
            console.log(JSON.stringify(respuesta));
            if(respuesta.COD_USUARIO_CAJA){
                $('#codigoCaja-modal-pagos').attr("value", respuesta.COD_CAJA);
                $('#usuarioCaja-modal-pagos').attr("value", respuesta.COD_USUARIO_CAJA);
                levantapagos(parametros);
            }else{
                mostarVentana("warning-title", "Debe tener una caja abierta para acceder a pagos");    
            }
            
        },
        error: function(event, request, settings) {
             mostarVentana("warning-title", "Debe tener una caja abierta para acceder a pagos");
        }
    });    
}

function levantapagos(parametros){
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
        $('#vuelto-modal-pagos').attr("value", null);
        $('#vuelto-modal-pagos').hide();
        $('#vuelto-modal-pagos-idioma').hide();
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
      
        $('#formaPagoEfectivo').prop('checked', true);
        $('#formaPagoCheque').prop('checked', false);
        $('#formaPagoEgreso').prop('checked', false);
        $("#guardar").hide();
        if(parametros.ESTADO == 'ANULADO'){
            $("#pagosrealizados-modal-pagos").attr("disabled", true);
            $("#saldoPendiente-modal-pagos").attr("disabled", true);
            $("#guardar-pagos").hide();
            $('#AgregarPagos').hide();
             mostarVentana("warning-pagos", "La factura compra se encuentra ANULADA");
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
function addrequiredattr(id,focus){
    $('#'+id).attr("required", "required");
    if(focus == 1)
        $('#'+id).focus();
}
function obtenerGrid() {
    var jsonObject = new Object();
    var dataObjectCompraDet = new Object();
    var dataObjectCompra = new Object();
    // recuperamos valores de la grilla
    dataObjectCompraDet = jQuery("#grillaComprasModal").jqGrid('getRowData'); // saca datos de la grilla en formato json
    var rowsGrid = jQuery("#grillaComprasModal").jqGrid('getRowData'); // saca tododos los datos, vamos a usar para sacar el length
   
    var mensaje = 'Complete los campos:';
    var focus = 0;
    
    // validar los campos por orden 
    if($('#codigoproveedor-modal').attr("value").length == 0){
        mensaje+= ' | Codigo del proveedor ';
        focus++;
        addrequiredattr('codigoproveedor-modal',focus);
    }
        
    if($('#controlfiscal-modal_3').attr("value").length == 0){
        mensaje+= ' | Control fiscal '; 
        focus++;
        addrequiredattr('controlfiscal-modal_3',focus);
    }
        
    if($('#FechaFactura-modal').attr("value").length == 0){
        mensaje+= ' | Fecha factura ';
        focus++;
        addrequiredattr('FechaFactura-modal',focus);
    }
        
    if($('#FechaVencimiento-modal').attr("value").length == 0){
        mensaje+= ' | Fecha vencimiento ';
        focus++;
        addrequiredattr('FechaVencimiento-modal',focus);
    }
        
    if(rowsGrid.length == 0)
        mensaje+= ' | Detalle de compra ';
    
    
    if(mensaje == 'Complete los campos:'){
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
        
    }else{
        mostarVentana("warning", mensaje);
        return null;
    }
            
}


function filtrosbusqueda() {
    var obj = new Object();
    obj.codproveedor = $('#codproveedor-filtro').attr("value");
    obj.nameproveedor = $('#nameproveedor-filtro').attr("value");
    obj.codigointerno = $('#codigointerno-filtro').attr("value");
    if ((($('#controlfiscal-filtro_3').attr("value")).length > 0) || ($('#controlfiscal-filtro_3').attr("value") !== "")) {
        var controlFiscalFiltros = ($('#controlfiscal-filtro_1').attr("value") + "-" + $('#controlfiscal-filtro_2').attr("value") + "-" + $('#controlfiscal-filtro_3').attr("value"));
        obj.controlfiscal = controlFiscalFiltros;
    } else {
        obj.controlfiscal = null;
    }

    obj.fechaemision = $('#fechaemision-filtro').attr("value");
    obj.fechavencimiento = $('#fechavencimiento-filtro').attr("value");
    var formapago = document.getElementById("formapago-filtro");
    obj.formapago = formapago.options[formapago.selectedIndex].value;
    return obj;
}
function clearfilters() {

    $('#codproveedor-filtro').attr("value", null);
    $('#nameproveedor-filtro').attr("value", null);
    $('#codigointerno-filtro').attr("value", null);
    $('#controlfiscal-filtro_3').attr("value", null);
    $('#fechaemision-filtro').attr("value", null);
    $('#fechavencimiento-filtro').attr("value", null);
//    $('formapago-filtro').attr("value",-1);
    $("#formapago-filtro option[value=-1]").attr("selected", true);

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
    $('#codproducto-item').attr("disabled", false);
    $('#descripcionproducto-item').attr("disabled", false);

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
        url: table+'/calculasaldo',
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
           
        }
    });

}
function selectFormaPago(formaPago) {
    if (formaPago == "cheque") {
        $('.cheque').show();
        $("#modalBuscarEgreso").hide();
        $('#banco-modal-pagos').attr("disabled", false);
        $('#cheque-modal-pagos').attr("disabled", false);
        $('#montoPago-modal-pagos').attr("disabled", false);
        $('input[name=formaPagoEfectivo]').attr('checked', false);
        $('input[name=formaPagoEgreso]').attr('checked', false);
         $("#montoPago-modal-pagos").attr("disabled", false);
         $("#vuelto-modal-pagos").attr("disabled", true);
         $("#vuelto-modal-pagos").val(0);
    } else if(formaPago =='efectivo'){
        $('.cheque').hide();
        $("#modalBuscarEgreso").hide();
        $('#banco-modal-pagos').attr("disabled", true);
        $('#cheque-modal-pagos').attr("disabled", true);
        $('input[name=formaPagoCheque]').attr('checked', false);
        $('input[name=formaPagoEgreso]').attr('checked', false);
         $("#montoPago-modal-pagos").attr("disabled", false);
         $("#vuelto-modal-pagos").attr("disabled", true);
         $("#vuelto-modal-pagos").val(0);
    } else if (formaPago == 'egreso'){
        cargartipoegreso();
        cargarGrillaEgreso();
        //buscarEgreso();
        $('.cheque').hide();
        $('#banco-modal-pagos').attr("disabled", true);
        $('#cheque-modal-pagos').attr("disabled", true);
        $('input[name=formaPagoCheque]').attr('checked', false);
        $('input[name=formaPagoEfectivo]').attr('checked', false);
         $("#montoPago-modal-pagos").attr("disabled", true);
         $("#vuelto-modal-pagos").attr("disabled", true);
    }

}

function buscarEgreso(egreso){  
    if(egreso != 0){
$.ajax({
            url: table+'/cargagrillaegreso',
            type: 'post',
            data: {"data": egreso},
            dataType: 'json',
            async: true,
            success: function(respuesta) {
                jQuery("#grillaModalEgreso").jqGrid("clearGridData", true);
                if (respuesta.length == 0) {
                    mostarVentana("warning-block-modal-egreso", "No se encontraron egresos"); 
                }else{
                    
                    for (var i = 0; i < respuesta.length; i++) {
                    var rows = jQuery("#grillaModalEgreso").jqGrid('getRowData');
                    jQuery("#grillaModalEgreso").jqGrid('addRowData', (rows.length) + 1, respuesta[i]);
                    }
                }
                $("#modalBuscarEgreso").show();
                 
            },
            error: function(event, request, settings) {
                $("#modalBuscarEgreso").show();
                mostarVentana("warning-block-modal-egreso", "No se encontraron egresos");
            }
        });
    }  
            


}

function cargartipoegreso(){
    
//  alert('Tipo Producto');
    $.ajax({
        url: table+'/cargartipoegreso',
        type: 'post',
        dataType: 'html',
        async : false,
        success: function(respuesta){
            if(respuesta== 'error'){
                
            }else{
                $("#modalBuscarEgreso").show();
                $("#descripciontipomovimiento-modal").html(respuesta);             
            }
        },
        error: function(event, request, settings){
        }
    }); 
}


function obtenerMontoPago() {
    var data = new Object();
    
    var mensaje = '';
    var focus = 0;
    
    if ($('#factura-modal-pagos').val() === "" || $('#factura-modal-pagos').val() === null) {
        mensaje+= ' | El numero de factura no esta especificado ';
        focus++;
        addrequiredattr('factura-modal-pagos',focus);
    } 
    if ($('#montoPago-modal-pagos').val() === "" || $('#montoPago-modal-pagos').val() < 0) {
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
        mostarVentana("warning-pagos", mensaje);
        return null;
    } else {
        data.numero_factura = $('#factura-modal-pagos').val();
        data.monto_pago = $('#montoPago-modal-pagos').val();
        data.moneda_pago = $('#codigoMoneda-pagos').val();
        data.estado_pago = 'T';
        if ($('input[name=formaPagoCheque]').is(':checked')) {
            data.nombre_banco = $("#banco-modal-pagos").val();
            data.numero_cheque = $("#cheque-modal-pagos").val();
            data.forma_pago = "cheque";
        } else {
            data.forma_pago = "efectivo";
            data.nombre_banco = "0";
            data.numero_cheque = 0;
        }
        if ($('input[name=formaPagoEgreso]').is(':checked')) {
            data.forma_pago = "egreso";
            data.vuelto = $("#vuelto-modal-pagos").val();
            data.codigo_egreso = $("#idEgreso-modal-pagos").val();
           
        } else {
            data.vuelto = "0";
            data.codigo_egreso = 0;
        }
        data.codigo_caja = $("#codigoCaja-modal-pagos").val();
        data.usuario_caja = $("#usuarioCaja-modal-pagos").val();
        parseFloat(data.monto_pago);
//        alert(data.monto_pago);
        return data;
    }

}

function guardarPagos(data) {

    data = JSON.stringify(data);
    $.ajax({
        url: table+'/guardarpagos',
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
                $('#codigoCaja-modal-pagos').attr("value", 0);
                $('#usuarioCaja-modal-pagos').attr("value", 0);
                mostarVentana("success-title", "Pago almacenado exitosamente");
            } else if (respuesta.result == "ERROR") {
                if (respuesta.mensaje == 23505) {
                    mostarVentana("warning", "Los datos ingresados estan duplicados");
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

function insertaEgreso(){
    // Saca los datos de la fila, guarda id del egreso.
    var rowGridEgreso = $("#grillaModalEgreso").jqGrid('getGridParam', 'selrow');
    var codMovCaja = $("#grillaModalEgreso").jqGrid('getCell', rowGridEgreso, 'COD_MOV_CAJA');
     $("#idEgreso-modal-pagos").val(codMovCaja);
    var montoMovCaja = parseInt($("#grillaModalEgreso").jqGrid('getCell', rowGridEgreso, 'MONTO_MOV'));
    var saldoPediente = parseInt($("#saldoPendiente-modal-pagos").val());
    //verificar si el egreso es mayor, menor o igual
    console.log(montoMovCaja+" - "+saldoPediente);
   if(montoMovCaja>saldoPediente)   {
               console.log("RECIBIR VUELTO, dar ingreso de dinero en caja");
               var vuelto = montoMovCaja - saldoPediente;
               $("#vuelto-modal-pagos").show();
               $("#vuelto-modal-pagos-idioma").show();
               $("#vuelto-modal-pagos").val(vuelto);
               $("#montoPago-modal-pagos").val(saldoPediente);
               $("#montoPago-modal-pagos").attr("disabled", true);
               reloadEgresoModal();

    }else if(montoMovCaja<saldoPediente){
                   console.log("cancelar egreso, ingresar pago parcial");
                   $("#vuelto-modal-pagos").hide();
                   $("#vuelto-modal-pagos-idioma").hide();
                   $("#vuelto-modal-pagos").val(0);
                   $("#montoPago-modal-pagos").val(montoMovCaja);
                   $("#montoPago-modal-pagos").attr("disabled", true);
                    reloadEgresoModal();
    }else if(montoMovCaja==saldoPediente){
                    $("#vuelto-modal-pagos").hide();
                   $("#vuelto-modal-pagos-idioma").hide();
                   $("#vuelto-modal-pagos").val(0);
                   $("#montoPago-modal-pagos").val(montoMovCaja);
                   $("#montoPago-modal-pagos").attr("disabled", true);
    }else{
    console.log("no se recupero el pago");
    }

    // si es mayor registrar ingreso por vuelto

    // si es menor registrar pago por egreso.

    // si es igual registrar egreso

    // marcar egreso con el nro de factura compra 
}
