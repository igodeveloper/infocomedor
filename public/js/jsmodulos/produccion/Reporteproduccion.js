//table = '/infocomedor/infocomedor/public/index.php/caja/caja/';
var pathname = window.location.pathname;
var table = pathname;
$().ready(function() {    
      
	
	$("#imprimirReporte").click(function() {                           
		imprimirReporte();
	});

	cargartipoproducto();

    
});

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
           
		var dataString = JSON.stringify(obtenerJsonBuscar());      
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








function obtenerJsonBuscar(){
	var jsonObject = new Object();

	
	if($('#tipo-productos-modal').val() != -1){
		jsonObject.cod_producto_tipo = $('#tipo-productos-modal').val();
	}

	
	return jsonObject;
}


function ocultarSuccessBlock(){
	$("#success-block").hide(500);
}

function ocultarInfoClean(){
	$("#info-block-listado").hide(500);
}

function ocultarErrorBlock(){
	$("#error-block").hide(500);
}

function ocultarErrorBlockList(){
	$("#error-block-registro-listado").hide(500);
}

function ocultarErrorBlockModal(){
	$("#error-block-modal").hide(500);
}

function ocultarWarningBlock(){
	$("#warning-block").hide(500);
}

function ocultarWarningBlockTitle(){
     $("#warning-block-title").hide(500);
	//$("#warning-block-registro-listado").hide(500);
}

function ocultarSuccessBlockTitle(){
	$("#success-block-title").hide(3500);
}

function ocultarWarningRegistroBlock(){
	$("#warning-block-registro").hide(500);
}

function mostarVentana(box,mensaje){
	$("#success-block").hide();
	$("#info-block-listado").hide();
	if(box == "warning") {
		$("#warning-message").text(mensaje);
		$("#warning-block").show();
		setTimeout("ocultarWarningBlock()",5000);
	} else if(box == "warning-block-title") {
		$("#warning-message-title").text(mensaje);
		$("#warning-block-title").show();
		setTimeout("ocultarWarningBlockTitle()",5000);
	} else if(box == "success-block-title") {
//		console.log('entro');
		$("#success-message-title").text(mensaje);
		$("#success-block-title").show();
		setTimeout("ocultarSuccessBlockTitle()",5000);
	} else if(box == "warning-registro") {
		$("#warning-message").text(mensaje);
		$("#warning-block").show();
		setTimeout("ocultarWarningRegistroBlock()",5000);
	}  else if(box == "info") {
		$("#info-message").text(mensaje);
		$("#info-block-listado").show(500);
		setTimeout("ocultarInfoClean()",5000);
	} else if(box == "error"){
		$("#error-block").text(mensaje);
		$("#error-block").show(500);
		setTimeout("ocultarErrorBlock()",5000);
	} else if(box == "error-registro-listado"){
		$("#error-block-registro-listado").text(mensaje);
		$("#error-block-registro-listado").show(500);
		setTimeout("ocultarErrorBlockList()",5000);
	} else if(box == "error-modal"){
		$("#error-block-modal").text(mensaje);
		$("#error-block-modal").show(500);
		setTimeout("ocultarErrorBlockModal()",5000);
	}
}