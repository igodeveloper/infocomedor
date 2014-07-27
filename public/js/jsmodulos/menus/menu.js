//table = "/infocomedor/infocomedor/public/index.php/menus/menu/";
var pathname = window.location.pathname;
var table = pathname;
$().ready(function() {    
        //cargarComboSucursal();
        userFocus(); 
        limpiarFormulario();
	$('#signin_submit').click(function() {
            if($('#username').val() == ''){
                alert("Debe ingresar un usuario!!");
                $("#username").focus();
                retunr;
            }
            if($('#password').val() == ''){
                alert("Debe ingresar una contrasena!!");
                $("#password").focus();
                retunr;
            }    
            if($('#SelecAgencia').val() == -1){
                alert("Debe seleccionar sucursal!!");
                $("#SelecAgencia").focus();
                retunr;
            } 
            var data = obtenerJsonFormulario();
            if(data != null){
                    enviarParametrosRegistro(data);
            }
	 });        
});

function cargarComboSucursal(){
	$.ajax({
        url: table+'/sucursaldata',
        type: 'post',
        dataType: 'html',
        async : false,
        success: function(respuesta){
        	$("#SelecAgencia").html(respuesta);
        },
        error: function(event, request, settings){
         //   $.unblockUI();
            alert("Ha ocurrido un error");
        }
    });	
}
function enviarParametrosRegistro(data){
    $.blockUI({
        message: "Aguarde un momento por favor"
    });

    var dataString = JSON.stringify(data);
    $.ajax({
    url: table+'/usuariodata',
    type: 'post',
    data: {"parametros":dataString},
    dataType: 'html',
    async : true,
    success: function(respuesta){
        if(respuesta == null){
                mostarVentana("error","TIMEOUT");
        } else{
           var obj = JSON.parse(respuesta);            
           if(obj.resultado == '-1'){               
               alert('Ingreso datos incorrectos vuelva a intentar por favor!!');
           }
           else{
				var url = table;
				url = url.replace(/menus/,"parametricos");
                //var url = "/infocomedor/infocomedor/public/index.php/parametricos/menu";    
                $(location).attr('href',url);                               
           }            
           $.unblockUI();
        }
    },
    error: function(event, request, settings){
            mostarVentana("error-registro-listado","Ha ocurrido un error");
            $.unblockUI();
    }
});
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
function obtenerJsonFormulario() {
    var jsonObject = new Object();
    jsonObject.username = $('#username').attr("value");
    jsonObject.password = $('#password').attr("value");
    jsonObject.SelecAgencia = $('#SelecAgencia').attr("value");
    return jsonObject;
}
function limpiarFormulario(){
        $("#SelecAgencia option[value=-1]").attr("selected",true);
        $("#username").attr("value",null);
	$("#password").attr("value",null);
        $("#username").html("");  
        $("#password").html("");  
}
function userFocus(){
    //document.getElementById('username').onfocus;
    $("#username").focus();
}
function passFocus(){
    //document.getElementById('password').onfocus;
        $("#password").focus();
}
function agenciaFocus()
{
    $("#SelecAgencia").focus();
}
function botonFocus()
{
    $("#signin_submit").focus();
}



