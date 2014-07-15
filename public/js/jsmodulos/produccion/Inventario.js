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
       
        jQuery("#grillaRegistroModal").setGridParam({multiselect: true});
        $("#grillaRegistroModal").jqGrid('showCol', 'cb'); 
        cargartipoproducto();
        $("#guardar").html("Imprimir");
        $(".btn-compra").show();
        $("#detalle-receta").show();
        $("#editar-nuevo").html("Nuevo Registro");
        CleanFormItems();
        $("#grillaRegistroModal").jqGrid("clearGridData");
        $("#grillaRegistroModal").hideCol("saldos");
        $('#codproducto-item').attr("disabled", false);
        $('#descripcionproducto-item').attr("disabled", false);
        $("#guardar").show();
        $("#guardarinventario").hide();
        $("#addProductos").show();
        $("#addTipoProducto").show();
        $("#addItem").attr("disabled", true);  
        $("#unidadmedida-item").attr("disabled", true);
        $('#modalEditar').show();
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
        
        var data = obtenerGrid("selected");
        if (data !== null) {
           enviarParametrosRegistros(data);    
        }
    });
    $("#guardarinventario").click(function() {
    	
    	jQuery('#grillaRegistroModal').jqGrid('setSelection', '-1');
        
    	var data = obtenerGrid("all");
        if (data !== null) {
            enviarParametrosRegistros(data);    
        }
    });

}); // cerramos el ready de js
function print_pdf(){
	$.ajax({
        url: table+'/printpdf',
        type: 'post',
        dataType: 'json',
        data: {
        },
        async: false,
        success: function(respuesta) {
        	 
             
        	window.open('../../'+respuesta.url, 'target', '_blank')
        },
        error: function(event, request, settings) {
            alert('No se imprimio');
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
function ocultarWarningBlockModal() {
    $("#warning-block-modal").hide(500);
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
    } else if (box == "warning-modal") {
        $("#warning-message-modal").text(mensaje);
        $("#warning-block-modal").show();
        setTimeout("ocultarWarningBlockModal()", 5000);
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
    }

}

function obtenerJsonDetalles() {
    var jsonObject = new Object();
    jsonObject.codproducto = $('#codproducto-item').attr("value");
    jsonObject.descripcionproducto = $('#descripcionproducto-item').attr("value");
    jsonObject.codUnidadMedida = $('#codUnidadMedida-item').attr("value");
    jsonObject.unidadmedida = $("#unidadmedida-item").attr("value");
    jsonObject.saldo = $("#saldo-item").attr("value");

    if (jsonObject.codproducto === "" || jsonObject.codproducto === null) {
        mostarVentana("warning", "Ingrese el Codigo del producto");
    } else if (jsonObject.descripcionproducto === "" || jsonObject.descripcionproducto === null) {
        mostarVentana("warning", "Ingrese la descripcion del producto");
    } else if (jsonObject.codUnidadMedida === "" || jsonObject.codUnidadMedida === null) {
        mostarVentana("warning", "Ingrese la unidad de medida");
    } else {
        jsonObject.codproducto = parseInt(jsonObject.codproducto);

//        alert(JSON.stringify(jsonObject));
        return jsonObject;
    }
    return null;
}


function loadAutocompleteProducto() {
    $.getJSON(table+"/productodata", function(data) {
        var descripcionProducto = [];
        var codigoProducto = [];
        var descripcionProductoFiltro = [];
        
        
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
            url: table+'/productvalidationdata',
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
                $('#codproducto-item').attr("disabled", true);
                $('#descripcionproducto-item').attr("disabled", true);
                $('#saldo-item').attr("value", respuesta.saldo);
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

function enviarParametrosRegistros(data) {
    
    var dataGrilla = JSON.stringify(data.grilla);
    var inventario= JSON.stringify(data.inventario);
    console.log("llego");
    var urlenvio = '';
    urlenvio = table+'/guardar';
    
    $.ajax({
        url: urlenvio,
        type: 'post',
        data: {"dataGrilla": dataGrilla, "inventario": inventario},
        dataType: 'JSON',
        async: true,
        success: function(respuesta) {
//                alert(respuesta+"hola");

            if (respuesta == null) {
                mostarVentana("error", "TIMEOUT");
            } else if (respuesta.result == "EXITO") {
                if(inventario == "null"){
                    
                     // print_pdf();
                    imprimirReporte();
                }
                mostarVentana("success-title", "Datos almacenados exitosamente");
                $('#modalEditar').hide();
                $("#grillaRegistro").trigger("reloadGrid");
                $("#grillaRegistroModal").trigger("reloadGrid");
                CleanFormItems();

                //print_pdf();
            } else if (respuesta.result == "ERROR") {
                if (respuesta.code == 23505) {
                    mostarVentana("warning-modal", "Datos duplicados");
                } else if(respuesta.code == 23000) {
                    mostarVentana("warning-modal", "Registros para el inventario duplicados");
                } else {
                	 mostarVentana("warning-modal", "Ocurrio un error verifique los datos");
                }
            }
        },
        error: function(event, request, settings) {
           // alert("OCURRIO UN ERROR");
        }
    });
}

function buscar() {
    var dataJsonBusqueda = JSON.stringify(filtrosbusqueda());
    $('#grillaRegistro').jqGrid('clearGridData');
    $('#grillaRegistro').trigger('reloadGrid');

    // $.blockUI({
    //     message: "Aguarde un Momento"
    // });

    // $.ajax({
    //     url: table+'/buscar',
    //     type: 'post',
    //     data: {
    //         "data": dataJsonBusqueda
    //     },
    //     dataType: 'json',
    //     async: false,
    //     success: function(respuesta) {
    //     	if(respuesta.mensajeSinFilas)
    //     		mostarVentana("warning", "Sin registros");
        	
    //         $("#grillaRegistro")[0].addJSONData(respuesta);
           
    //         $.unblockUI();
    //     },
    //     error: function(event, request, settings) {
    //         $.unblockUI();
    //         //alert(mostrarError("OCURRIO UN ERROR"));
    //     }
    // });
}

/*
 * ESTABLECEMOS LOS FILTROS Y LAS LIMPIEZAS 
 * 
 * 
 * */

function obtenerGrid(seleccion) {
    var jsonObject = new Object();
    var dataObjectGrillaDetalle = [];
    
    if(seleccion == "all"){
        jQuery('#grillaRegistroModal').jqGrid('setSelection', '-1');
        dataObjectGrillaDetalle = jQuery("#grillaRegistroModal").jqGrid('getRowData'); // saca datos de la grilla en formato json
        var inventario = $('#inventario-codigo').val();
    }
    if(seleccion == "selected"){
        var s = jQuery("#grillaRegistroModal").jqGrid('getGridParam','selarrrow');
        for (var i = 0; i < s.length; i++) {
            var rows = jQuery('#grillaRegistroModal').jqGrid ('getRowData', s[i]);
            dataObjectGrillaDetalle.push(rows);           
        }
        var inventario = (parseInt)($('#inventario-codigo').val());
    }
    
    
    if(dataObjectGrillaDetalle.length < 1){
        mostarVentana("warning-modal","Agregue al menos un producto");
        return null;
    }else{
        jsonObject.grilla = dataObjectGrillaDetalle;
        jsonObject.inventario = inventario;
      // alert(JSON.stringify(jsonObject));
    return jsonObject;    

    }
    
}


function filtrosbusqueda() {
   
    var objBusqueda = new Object();
	objBusqueda.tipoproducto = $('#descripciontipoproducto-filtro').attr("value");
	objBusqueda.producto = $('#descripcionproductofinal-filtro').attr("value");
    
    return objBusqueda;
}


function CleanFormItems() {
	$('#codproducto-item').attr("disabled", false);
    $('#descripcionproducto-item').attr("disabled", false);
    $('#codproducto-item').attr("value", null);
    $('#descripcionproducto-item').attr("value", null);
    $('#codUnidadMedida-item').attr("value", null);
    $("#unidadmedida-item").attr("value", null);
    $("#cantidad-item").attr("value", null);
    $("#saldo-item").attr("value", null);
    $("#inventario-codigo").attr("value", null);
}

function modalInventario(parametros) {
    
    $("#editar-nuevo").html("Ingresar datos");
    $("#addProductos").hide();
    $("#addTipoProducto").hide();
    $("#grillaRegistroModal").showCol("saldos");
    $("#grillaRegistroModal").jqGrid("clearGridData");
    if(parametros.INVENTARIO_SALDO == null){
        // console.log("entro aca en null");
        jQuery("#grillaRegistroModal").setGridParam({multiselect: false});
        jQuery("#grillaRegistroModal").setColProp('saldos',{editable:true});
        jQuery("#grillaRegistroModal").setGridParam({onSelectRow: function(id){
                    if(id && id!==lastSel){
                        jQuery('#grillaRegistroModal').restoreRow(lastSel); 
                        lastSel=id; 
                    }
                    jQuery('#grillaRegistroModal').editRow(id, true); 
               }});
         $("#guardarinventario").show();
    } else{
        mostarVentana("warning-modal","Inventario ya catastrado, solo puede visualizar los datos");
        jQuery("#grillaRegistroModal").setGridParam({multiselect: false});
        jQuery("#grillaRegistroModal").setColProp('saldos',{editable:false});
        $("#guardarinventario").hide();
    }
    
    $("#grillaRegistroModal").jqGrid('hideCol', 'cb'); 
    $("#modalEditar").show();
    cargarInventario(parametros.nro_inventario);
    $("#guardar").hide();
    
    $("#guardarinventario").html("Cargar");

}

function cargarInventario(nro_inventario) {

    $.ajax({
        url: table+'/modalinventario',
        type: 'post',
        data: {
            "nro_inventario": nro_inventario
        },
        dataType: 'json',
        async: false,
        success: function(respuesta) {
        	$("#inventario-codigo").attr("value", nro_inventario);
            for (var i = 0; i < respuesta.length; i++) {
                var rows = jQuery("#grillaRegistroModal").jqGrid('getRowData');
                jQuery("#grillaRegistroModal").jqGrid('addRowData', (rows.length) + 1, respuesta[i]);
            }
        },
        error: function(event, request, settings) {
            //alert(mostrarError("OCURRIO UN ERROR"));
        }
    });
}

function buscarProductos(tipoproducto){  
    if(tipoproducto != 0){
$.ajax({
            url: table+'/cargagrillaproducto',
            type: 'post',
            data: {"data": tipoproducto},
            dataType: 'json',
            async: true,
            success: function(respuesta) {
                jQuery("#grillaRegistroModal").jqGrid("clearGridData", true);
                if (respuesta.length == 0) {
                    mostarVentana("warning-modal", "No se encontraron productos"); 
                }else{
                    
                    for (var i = 0; i < respuesta.length; i++) {
                    var rows = jQuery("#grillaRegistroModal").jqGrid('getRowData');
                    jQuery("#grillaRegistroModal").jqGrid('addRowData', (rows.length) + 1, respuesta[i]);
                    }
                }
                 
            },
            error: function(event, request, settings) {
                mostarVentana("warning-modal", "No se encontraron productos");
            }
        });
    }  

}

function cargartipoproducto(){
    
//  alert('Tipo Producto');
    $.ajax({
        url: table+'/cargartipoproducto',
        type: 'post',
        dataType: 'html',
        async : false,
        success: function(respuesta){
            if(respuesta== 'error'){
                
            }else{
                $("#tipo-productos-modal").html(respuesta);             
            }
        },
        error: function(event, request, settings){
        }
    }); 
}

function imprimirReporte(){
              
        $.ajax({
            url: table+'/imprimirreporte',
            type: 'post',
            data: {},
            dataType: 'json',
            async: false,
            success: function(respuesta) {

                if (respuesta == null) {
                                    mostarVentana("error", "TIMEOUT");
                } else if (respuesta.result == "EXITO") {
                                    window.open('../pdfs/'+respuesta.archivo);
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

