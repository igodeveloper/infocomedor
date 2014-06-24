mensajeWarning = new Array(        
		"",
		" |  Descripci\u00f3n ",
        " |  Ruc ",
        " |  Direcci\u00f3n ",
        " |  Tel\u00e9 fono ",
        " |  Nombre del contacto ",
        " |  Email ",
        " |  L\u00edmite de cr\u00e9 dito "
);

idCamposGrilla = new Array(
        "id-registro",
        "descripcionBusqueda-modal",
        "ruc-modal",
        "direccion-modal",
        "telefono-modal",
        "nombrecontacto-modal",
        "email-modal",
        "limitecredito-modal");

$().ready(function() {
	
    $("#buscarregistro").click(function() {
        buscarRegistros();
    });

    $("#cerrar-bot").click(function() {
        $("#modalEditar").hide();
    });

    $("#cancelar-bot").click(function() {
        $("#modalEditar").hide();
    });

    $('#modalEditar').modal({backdrop: false, show: false});



    $("#nuevoregistro").click(function() {
        $('#modalEditar').show();
        //ID de registro
        $('#' + idCamposGrilla[0]).attr("value", null);
        $("#guardar-registro").html("Guardar");
        $("#editar-nuevo").html("Nuevo Registro");
        limpiarFormulario();
    });

    $('#guardar-registro').click(function() {
//        if (!confirm("Esta seguro de que desea almacenar los datos?"))
//            return;
        var data = obtenerJsonFormulario();
        if (data != null) {
            enviarParametrosRegistro(data);
        }
    });


    //validarNumerosCampo();


});

function validarNumerosLetrasPorcentageEspacio(e) { // 1
    var te;
//    console.log("key : "+e.keyCode);
//    console.log("which : "+e.which);
    if (document.all) {
        if (e.keyCode == 37)
            return false; // %
//		if (e.keyCode==63) return false; // guion bajo
//		if (e.keyCode==95) return false; // guion bajo
        if (e.keyCode == 8)
            return true; // back spacebar
        
        if (e.keyCode == 32)
            return true; // space bar
        te = String.fromCharCode(e.keyCode); // 5
    } else {
        if (e.which == 37)
            return false; // %
        if (e.which == 0)
            return true; // izquierda,derecha,arriba,abajo
//		if (e.which==95) return false; // guion bajo
        if (e.which == 8)
            return true; // back space bar
        if (e.which == 32)
            return true; // space bar
         if (e.which == 45)
            return true; // guion
        
        te = String.fromCharCode(e.which); // 5
    }
    patron = /\w/;
    return patron.test(te); // 6
    
}
function validarNro(e) {

    var key;
    // IE 
    if (window.event){
       key = e.keyCode;
    }
    // Netscape/Firefox/Opera
    else if (e.which)  {
        key = e.which;
    }
    if (key < 48 || key > 57){
        // Detectar . (punto) y backspace (retroceso)
        if (key == 46 || key == 8) {
            return true;
        }
        else{
            return false;
        }
    }
    return true;
}




function enviarParametrosRegistro(data) {
    $.blockUI({
        message: "Aguarde un momento por favor"
    });

    var urlenvio = '';
    if (data.idRegistro != null && data.idRegistro.length != 0) {
        urlenvio = table + 'modificar';
    } else {
        urlenvio = table + 'guardar';
    }
    var dataString = JSON.stringify(data);

    $.ajax({
        url: urlenvio,
        type: 'post',
        data: {"parametros": dataString},
        dataType: 'json',
        async: true,
        success: function(respuesta) {
            if (respuesta == null) {
                mostarVentana("error", "TIMEOUT");
            } else if (respuesta.result == "EXITO") {
                mostarVentana("success-registro-listado", "Los datos han sido almacenados exitosamente");
                $('#modalEditar').hide();
                $("#grillaRegistro").trigger("reloadGrid");
            } else if (respuesta.result == "ERROR") {
                if (respuesta.code == 23000) {
                    mostarVentana("warning-registro", "Ya existe un registro con el RUC ingresado");
                } else {
                    mostarVentana("error-modal", "Ha ocurrido un error");
                }
            }
            $.unblockUI();
        },
        error: function(event, request, settings) {
            mostarVentana("error-registro-listado", "Ha ocurrido un error");
            $.unblockUI();
        }
    });
}


function obtenerJsonFormulario() {
    var jsonObject = new Object();
    var mensaje = 'Complete los campos:';
    
    for ( var i = 1; i < 8; i++ ) {
    	console.log(idCamposGrilla[i]);
    	if ($('#' + idCamposGrilla[i]).attr("value") == null || $('#' + idCamposGrilla[i]).attr("value").length == 0) {
    		mensaje+= mensajeWarning[i] ;
    		console.log(mensaje);
    		$('#' + idCamposGrilla[i]).attr("required", "required");
    	}
    }
    
    
    if (mensaje == 'Complete los campos:') {
    	
        jsonObject.idRegistro = $('#' + idCamposGrilla[0]).attr("value");
        jsonObject.descripcionProveedor = $('#' + idCamposGrilla[1]).attr("value");
        jsonObject.rucProveedor = $('#' + idCamposGrilla[2]).attr("value");
        jsonObject.direccionProveedor = $('#' + idCamposGrilla[3]).attr("value");
        jsonObject.telefonoProveedor = $('#' + idCamposGrilla[4]).attr("value");
        jsonObject.nombrecontactoProveedor = $('#' + idCamposGrilla[5]).attr("value");
        jsonObject.emailProveedor = $('#' + idCamposGrilla[6]).attr("value");
        jsonObject.limitecreditoProveedor = $('#' + idCamposGrilla[7]).attr("value");
        return jsonObject;
    } else {
    	mostarVentana("warning-registro", mensaje);
    	return null;	
    
    }
    
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

function ocultarErrorBlockList() {
    $("#error-block-registro-listado").hide(500);
}

function ocultarErrorBlockModal() {
    $("#error-block-modal").hide(500);
}

function ocultarWarningBlock() {
    $("#warning-block").hide(500);
}

function ocultarWarningBlockTitle() {
    $("#warning-block-registro-listado").hide(500);
}

function ocultarSuccessBlockTitle() {
    $("#success-block-registro-listado").hide(500);
}

function ocultarWarningRegistroBlock() {
    $("#warning-block-registro").hide(500);
}

function mostarVentana(box, mensaje) {
    $("#success-block").hide();
    $("#info-block-listado").hide();
    if (box == "warning") {
        $("#warning-message").text(mensaje);
        $("#warning-block").show();
        setTimeout("ocultarWarningBlock()", 5000);
    } else if (box == "warning-registro-listado") {
        $("#warning-message-registro-listado").text(mensaje);
        $("#warning-block-registro-listado").show();
        setTimeout("ocultarWarningBlockTitle()", 5000);
    } else if (box == "success-registro-listado") {
        $("#success-message-registro-listado").text(mensaje);
        $("#success-block-registro-listado").show();
        setTimeout("ocultarSuccessBlockTitle()", 5000);
    } else if (box == "warning-registro") {
        $("#warning-message-registro").text(mensaje);
        $("#warning-block-registro").show();
        setTimeout("ocultarWarningRegistroBlock()", 5000);
    } else if (box == "info") {
        $("#info-message").text(mensaje);
        $("#info-block-listado").show(500);
        setTimeout("ocultarInfoClean()", 5000);
    } else if (box == "error") {
        $("#error-block").text(mensaje);
        $("#error-block").show(500);
        setTimeout("ocultarErrorBlock()", 5000);
    } else if (box == "error-registro-listado") {
        $("#error-block-registro-listado").text(mensaje);
        $("#error-block-registro-listado").show(500);
        setTimeout("ocultarErrorBlockList()", 5000);
    } else if (box == "error-modal") {
        $("#error-block-modal").text(mensaje);
        $("#error-block-modal").show(500);
        setTimeout("ocultarErrorBlockModal()", 5000);
    }
}

function buscarRegistros() {
    var dataJson = obtenerJsonBuscar();
    $.blockUI({
        message: "Aguarde un momento por favor"
    });
    $.ajax({
        url: table + 'buscar',
        type: 'post',
        data: {"data": dataJson},
        dataType: 'html',
        async: false,
        success: function(respuesta) {
            $("#grillaRegistro")[0].addJSONData(JSON.parse(respuesta));
            var obj = JSON.parse(respuesta);
            if (obj.mensajeSinFilas == true) {
                mostarVentana("info", "No se encontraron registros con los parametros ingresados");
            }
            $.unblockUI();
        },
        error: function(event, request, settings) {
            $.unblockUI();
            alert("Ha ocurrido un error");
        }
    });
}

function editarRegistro(parametros) {
    limpiarFormulario();
    $("#modalEditar").show();
    $("#editar-nuevo").html("Editar Registro");
    $("#" + idCamposGrilla[0]).attr("value", parametros.idRegistro);
    $("#" + idCamposGrilla[1]).attr("value", parametros.descripcionProveedor);
    $("#" + idCamposGrilla[2]).attr("value", parametros.rucProveedor);
    $("#" + idCamposGrilla[3]).attr("value", parametros.direccionProveedor);
    $("#" + idCamposGrilla[4]).attr("value", parametros.telefonoProveedor);
    $("#" + idCamposGrilla[5]).attr("value", parametros.nombrecontactoProveedor);
    $("#" + idCamposGrilla[6]).attr("value", parametros.emailProveedor);
    $("#" + idCamposGrilla[7]).attr("value", parametros.limitecreditoProveedor);
    $("#guardar-registro").html("Modificar");

}

function limpiarFormulario() {
    $("#error-block-modal").hide();
    $("#warning-block").hide();
    $("#warning-block-registro").hide();
    $("#success-block").hide();
    $("#" + idCamposGrilla[0]).attr("value", null);
    $("#" + idCamposGrilla[1]).attr("value", null);
    $("#" + idCamposGrilla[2]).attr("value", null);
    $("#" + idCamposGrilla[3]).attr("value", null);
    $("#" + idCamposGrilla[4]).attr("value", null);
    $("#" + idCamposGrilla[5]).attr("value", null);
    $("#" + idCamposGrilla[6]).attr("value", null);
    $("#" + idCamposGrilla[7]).attr("value", null);

}



function obtenerJsonBuscar() {
    var jsonObject = new Object();

    if ($('#descripcionBusqueda').attr("value") != null && $('#descripcionBusqueda').attr("value").length != 0) {
        jsonObject.descripcion = $('#descripcionBusqueda').attr("value");
    }

    var dataString = JSON.stringify(jsonObject);
    return dataString;
}







