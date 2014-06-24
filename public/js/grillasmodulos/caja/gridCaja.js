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
       		"hidden" : false,
       		"classes" : "linkjqgrid",
       		"formatter" :cargarLinkModificar
       },
       {
	       		"title" : false,
	       		"name" : "cod_caja",
	       		"label" :"NRO. CAJA",
	       		"id" : "cod_caja",
	       		"width" : 70,
	       		"sortable" : false,
	       		"align":"right",
	       		"search" : false,
	       		"remove" : false,
	       		"hidden" : false
       	  },
       	  {
	       		"title" : false,
	       		"name" : "cod_usuario_caja",
	       		"label" : "cod_usuario_caja",
	       		"id" : "cod_usuario_caja",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 200,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : true
       		},
       	  {
	       		"title" : false,
	       		"name" : "nombre_apellido",
	       		"label" : "USUARIO",
	       		"id" : "nombre_apellido",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 200,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "fecha_hora_apertura",
	       		"label" : "FECHA APERTURA",
	       		"id" : "fecha_hora_apertura",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 150,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "fecha_hora_cierre",
	       		"label" : "FECHA CIERRE",
	       		"id" : "fecha_hora_cierre",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 150,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "monto_caja_apertura",
	       		"label" : "MONTO APERTURA",
	       		"id" : "monto_caja_apertura",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 150,
	       		"align":"right",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "monto_caja_cierre",
	       		"label" : "MONTO CIERRE",
	       		"id" : "monto_caja_cierre",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 150,
	       		"align":"right",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "monto_diferencia_arqueo",
	       		"label" : "MONTO DIFERENCIA CIERRE",
	       		"id" : "monto_diferencia_arqueo",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 170,
	       		"align":"right",
	       		"sortable" : false,
	       		"hidden" : false
       		},
                {
	       		"title" : false,
	       		"name" : "desc_arqueo_caja",
	       		"label" : "ARQUEADO",
	       		"id" : "desc_arqueo_caja",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 80,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "arqueo_caja",
	       		"label" : "ARQUEADO",
	       		"id" : "arqueo_caja",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 200,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : true
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
		title: "Eliminar fila seleccionada",
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
	id = $("#grillaRegistro").jqGrid('getCell', id, 'cod_tipo_mov');
	if( id == false ){
		alert("Para eliminar un registro debe seleccionarlo previamente.");
	}else{
//		if(!confirm("¿Esta seguro de que desea eliminar el registro seleccionado?"))
//			return;

		$.ajax({
	        url: table+'/eliminar',
	        type: 'post',
	        data: {"id":id},
	        dataType: 'json',
	        async : false,
	        success: function(data){
	        	if(data.result == "ERROR"){
	                    if(data.mensaje == 23000) {
	                    	mostarVentana("warning-registro","No se puede eliminar el Registro por que esta siendo utilizado");
				        } else {
//				        	mostarVentana("warning-block-title","Ha ocurrido un error");
					    }
				} else {
					mostarVentana("success-block-title","Los datos han sido eliminados exitosamente");
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
