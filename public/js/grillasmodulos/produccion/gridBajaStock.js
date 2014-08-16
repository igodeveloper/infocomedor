//table = '/infocomedor/infocomedor/public/index.php/caja/caja/';
var pathname = window.location.pathname;
var table = pathname;
$(document).ready(function(){
	cargarGrillaRegistro();
});

function setTooltipsOnColumnHeader(grid, iColumn, text){
    var thd = jQuery("thead:first", grid[0].grid.hDiv)[0];
    jQuery("tr.ui-jqgrid-labels th:eq(" + iColumn + ")", thd).attr("title", text);
}
/**
 * Bloquea la pantalla a trav�s de un contenedor de tal manera que el usuario no pueda realizar ninguna acci�n
 */
function bloquearPantalla() {
	$.blockUI({message: "Aguarde un momento por favor"});
}
/**
 * Desbloquea la pantalla de tal manera que el usuario pueda realizar acci�nes o invocar eventos en la vista
 */
function desbloquearPantalla() {
    $.unblockUI();
}
/**
 * Carga la tabla visual con el listado de registros. La estructura de la tabla es especificada.
 */
function cargarGrillaRegistro() {
	jQuery("#grillaRegistro").jqGrid({
        "url":table+'/listar',
        "mtype" : "POST",
       	"refresh": true,
       	"datatype" :"json",
       	"height" : "auto",
       	"rownumbers" : false,
        "ExpandColumn": "menu",
        "autowidth": true,
       	"gridview" : true,
       	"multiselect" : false,
       	"viewrecords" : true,
       	"rowNum":10,
       	"formatter": null,
       	"rowList":[10,20,30],
       	"pager": '#paginadorRegistro',
        "viewrecords": true,
        "beforeRequest" : bloquearPantalla,
        //"colNames":['modificar','nombre', 'sigla', 'porcentaje','montofijo','tipoaplicacion','empresa','sucursal'],
        "loadComplete": desbloquearPantalla,
       	"colModel":               
       	[{
       		"title" : false,
       		"name" : "modificar",
       		"label" : " ",
       		"id" : "modificar",
       		"align":"right",
       		"search" : false,
       		"sortable" : false,
       		"width" : 10,
       		"edittype" :'link',
       		"remove" : false,
       		"hidden" : true,
       		"classes" : "linkjqgrid",
       		"formatter" :cargarLinkModificar
       },      
       {
	       		"title" : false,
	       		"name" : "cod_producto",
	       		"label" :"cod_producto",
	       		"id" : "cod_producto",
	       		"width" : 70,
	       		"sortable" : false,
	       		"align":"right",
	       		"search" : false,
	       		"remove" : false,
	       		"hidden" : true
       	  },
       	  {
	       		"title" : false,
	       		"name" : "producto_desc",
	       		"label" : "DESC. PRODUCTO",
	       		"id" : "producto_desc",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 200,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "cantidad_baja",
	       		"label" : "CANTIDAD BAJA",
	       		"id" : "cantidad_baja",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 80,
	       		"align":"right",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "desc_unidad_medida",
	       		"label" : "UNIDAD MEDIDA",
	       		"id" : "desc_unidad_medida",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 80,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "fecha_hora_baja",
	       		"label" : "FECHA HORA MOV.",
	       		"id" : "fecha_hora_baja",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 150,
	       		"align":"left",
	       		formatter: 'date', 
                formatoptions: { srcformat: 'Y/m/d H:i:s', newformat: 'd/m/Y H:i:s' },
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "observacion_mov",
	       		"label" : "OBS. MOVIMIENTO",
	       		"id" : "observacion_mov",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 200,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "cod_baja_stock",
	       		"label" : "cod_baja_stock",
	       		"id" : "cod_baja_stock",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 150,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : true
       		},
       	  {
	       		"title" : false,
	       		"name" : "estado",
	       		"label" : "ESTADO",
	       		"id" : "estado",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 50,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : false
       		}]            
    }).navGrid('#paginadorRegistro',{
        add:false,
        edit:false,
        del:false,
        view:true,
        search:false,
        refresh:false});
	$("#grillaRegistro").setGridWidth($('#contenedor').width());

	$("#grillaRegistro").jqGrid('navButtonAdd','#paginadorRegistro',{
		buttonicon :'ui-icon-trash',
		caption: "",
		title: "Anular Fila Seleccionada",
		onClickButton : function (){
			borrar();	//Funcion de borrar
		}
	});
}
/**
 * Elimina una fila de la tabla visual de registros
 */
function borrar(){
	var id = $("#grillaRegistro").jqGrid('getGridParam','selrow');
	var cod_baja_stock = $("#grillaRegistro").jqGrid('getCell', id, 'cod_baja_stock');
        var desc_estado = $("#grillaRegistro").jqGrid('getCell', id, 'estado');
	if( id == false ){
		alert("Para anular un registro debe seleccionarlo previamente.");
	}else{
//		if(!confirm("¿Esta seguro de que desea eliminar el registro seleccionado?"))
//			return;
            if(desc_estado == 'Anulado'){
                mostarVentana("warning-block-title","El registro ya se encuentra anulado!!"); 
                return;
            } 
		$.ajax({
	        url: table+'/anulacion',
	        type: 'post',
	        data: {"id":cod_baja_stock},
	        dataType: 'json',
	        async : false,
	        success: function(data){
	        	if(data.result == "ERROR"){
	                    if(data.mensaje == 23000) {
	                    	mostarVentana("warning-registro","No se puede anular el Registro por que esta siendo utilizado");
				        } else {
//				        	mostarVentana("warning-block-title","Ha ocurrido un error");
					    }
				} else {
					mostarVentana("success-block-title","Los datos han sido anulados exitosamente");
				    $("#grillaRegistro").trigger("reloadGrid");
				}
	        },
	        error: function(event, request, settings){
	            $.unblockUI();
//	            alert("Ha ocurrido un error");
	        }
	    });
	}
	return false;
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
/**
 * M�todo que carga la funcionalidad de Edici�n de filas de la tabla visual del registro
 */
function cargarLinkModificar ( cellvalue, options, rowObject )
{
	var parametros = new Object();
	parametros.cod_caja = rowObject[1];
	parametros.cod_usuario_caja = rowObject[2];
	parametros.nombre_apellido = rowObject[3];
	parametros.fecha_hora_apertura = rowObject[4];
	parametros.fecha_hora_cierre = rowObject[5];
	parametros.monto_caja_apertura = rowObject[6];
	parametros.monto_caja_cierre = rowObject[7];
	parametros.monto_diferencia_arqueo = rowObject[8];
	parametros.desc_arqueo_caja = rowObject[9];
	parametros.arqueo_caja = rowObject[10];
	json = JSON.stringify(parametros);
	return "<a><img title='Cierre de Caja' src='../../css/images/edit.png' data-toggle='modal'  onclick='editarRegistro("+json+");'/></a>";
}
