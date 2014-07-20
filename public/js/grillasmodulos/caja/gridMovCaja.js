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
	       		"name" : "cod_usuario",
	       		"label" :"cod_usuario",
	       		"id" : "cod_usuario",
	       		"width" : 20,
	       		"sortable" : false,
	       		"align":"right",
	       		"search" : false,
	       		"remove" : false,
	       		formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:0},
	       		"hidden" : true
       	  },
       	  {
	       		"title" : false,
	       		"name" : "nombre_apellido",
	       		"label" : "USUARIO CAJA",
	       		"id" : "nombre_apellido",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 130,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "cod_caja",
	       		"label" : "NRO. CAJA",
	       		"id" : "cod_caja",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 50,
	       		"align":"right",
	       		"sortable" : false,
	       		formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:0},
	       		"hidden" : false
       		},			
			{
	       		"title" : false,
	       		"name" : "cod_mov_caja",
	       		"label" : "NRO. MOVIMIENTO",
	       		"id" : "cod_mov_caja",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 70,
	       		"align":"right",
	       		"sortable" : false,
	       		formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:0},
	       		"hidden" : false
       		},			       	 
       	  {
	       		"title" : false,
	       		"name" : "fecha_hora_mov",
	       		"label" : "FECHA MOVIMIENTO",
	       		"id" : "fecha_hora_mov",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 140,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "monto_mov",
	       		"label" : "MONTO MOVIMIENTO",
	       		"id" : "monto_mov",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 100,
	       		"align":"right",
	       		"sortable" : false,
	       		formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:0},
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "desc_tipo_mov",
	       		"label" : "TIPO MOVIMIENTO",
	       		"id" : "desc_tipo_mov",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 80,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "cod_tipo_mov",
	       		"label" : "cod_tipo_mov",
	       		"id" : "cod_tipo_mov",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 130,
	       		"align":"right",
	       		"sortable" : false,
	       		"hidden" : true
       		},
       	  {
	       		"title" : false,
	       		"name" : "factura_mov",
	       		"label" : "NRO. FACTURA",
	       		"id" : "factura_mov",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 80,
	       		"align":"right",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "desc_factura_mov",
	       		"label" : "TIPO MOV. FACTURA",
	       		"id" : "desc_factura_mov",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 200,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : true
       		},
       	  {				
	       		"title" : false,
	       		"name" : "tipo_factura_mov",
	       		"label" : "tipo_factura_mov",
	       		"id" : "tipo_factura_mov",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 200,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : true
       		},
       	  {
	       		"title" : false,
	       		"name" : "observacion_mov",
	       		"label" : "OBSERVACION",
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
	       		"name" : "tipo_mov",
	       		"label" : "TIPO",
	       		"id" : "tipo_mov",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 130,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : true
       		},
       	  {
	       		"title" : false,
	       		"name" : "arqueo_caja",
	       		"label" : "Caja Arqueada",
	       		"id" : "arqueo_caja",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 50,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "firmante_mov",
	       		"label" : "Firmante",
	       		"id" : "firmante_mov",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 80,
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
/*
cod_usuario
nombre_apellido
cod_caja
cod_mov_caja
fecha_hora_mov
monto_mov
desc_tipo_mov
cod_tipo_mov
factura_mov
desc_factura_mov
tipo_factura_mov
observacion_mov
tipo_mov    
*/
	var parametros = new Object();
	parametros.cod_usuario = rowObject[1];
	parametros.nombre_apellido = rowObject[2];
	parametros.cod_caja = rowObject[3];
        parametros.cod_mov_caja = rowObject[4];
        parametros.fecha_hora_mov = rowObject[5];
        parametros.monto_mov = rowObject[6];
        parametros.desc_tipo_mov = rowObject[7];
        parametros.cod_tipo_mov = rowObject[8];
        parametros.factura_mov = rowObject[9];
        parametros.desc_factura_mov = rowObject[10];
        parametros.tipo_factura_mov = rowObject[11];
        parametros.observacion_mov = rowObject[12];
        parametros.tipo_mov = rowObject[13];        
        parametros.arqueo_caja = rowObject[14];
        parametros.firmante_mov = rowObject[15];
	json = JSON.stringify(parametros);
	return "<a><img title='Editar' src='../../css/images/edit.png' data-toggle='modal'  onclick='editarRegistro("+json+");'/></a>";
}
