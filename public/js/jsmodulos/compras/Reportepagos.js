var pathname = window.location.pathname;
var table = pathname;
$().ready(function() {

    $("#imprimirReporte").click(function() {                           
            imprimirreporte();
    });    
});

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
function ocultarSuccessBlock() {
    $("#success-block").hide(500);
}




function ocultarWarningBlock() {
    $("#warning-block").hide(500);
}

function ocultarWarningBlockTitle() {
    $("#warning-block-title").hide(500);
}


function ocultarSuccessBlockTitle() {
    $("#success-block-title").hide(500);
}


function mostarVentana(box, mensaje) {
    $("#success-block").hide();
    $("#info-block-listado").hide();
    if (box == "warning-pagos") {
        $("#warning-message-title").text(mensaje);
        $("#warning-block-title").show();
        setTimeout("ocultarWarningBlockTitle()", 5000);
    } else if (box == "success-title") {
        $("#success-message-title").text(mensaje);
        $("#success-block-title").show();
        setTimeout("ocultarSuccessBlockTitle()", 5000);
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




function mostrarSuccessBlock() {
    $("#success-block").show(500);
    setTimeout("ocultarSuccessBlock()", 5000);
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
            $("#grillaPagos")[0].addJSONData(respuesta);
            $.unblockUI();
        },
        error: function(event, request, settings) {
            $.unblockUI();
            
        }
    });
}

function filtrosbusqueda() {
    var obj = new Object();
        obj.NRO_FACTURA_COMPRA = $('#codigointerno-filtro').attr("value");
        obj.NRO_CHEQUE = $('#nrocheque-filtro').attr("value");
    var estado = document.getElementById("formapago-filtro");
    obj.ESTADO_PAGO = estado.options[estado.selectedIndex].value;
    return obj;
}