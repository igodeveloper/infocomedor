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
       	"rowNum":30,
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
       		"width" : 5,
       		"edittype" :'link',
       		"remove" : false,
       		"hidden" : false,
       		"classes" : "linkjqgrid",
       		"formatter" :cargarLinkModificar
       },
       {
	       		"title" : false,
	       		"name" : "COD_PRODUCTO",
	       		"label" :"CODIGO",
	       		"id" : "COD_PRODUCTO",
	       		"width" : 10,
	       		"sortable" : false,
	       		"align":"right",
	       		"search" : false,
	       		"remove" : false,
	       		formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces: 0},
	       		"hidden" : false
       	  },
       	  {
	       		"title" : false,
	       		"name" : "PRODUCTO_DESC",
	       		"label" : "DESCRIPCION",
	       		"id" : "PRODUCTO_DESC",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 50,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       		
       	  {
	       		"title" : false,
	       		"name" : "COD_PRODUCTO_TIPO",
	       		"label" : "COD_PRODUCTO_TIPO",
	       		"id" : "COD_PRODUCTO_TIPO",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 10,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : true
       		},
       	  {
	       		"title" : false,
	       		"name" : "TIPO_PRODUCTO_DESCRIPCION",
	       		"label" : "TIPO PRODUCTO",
	       		"id" : "TIPO_PRODUCTO_DESCRIPCION",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 20,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "COD_UNIDAD_MEDIDA",
	       		"label" : "COD_UNIDAD_MEDIDA",
	       		"id" : "COD_UNIDAD_MEDIDA",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 10,
	       		"align":"right",
	       		"sortable" : false,
	       		"hidden" : true
       		},
       	  {
	       		"title" : false,
	       		"name" : "DESC_UNIDAD_MEDIDA",
	       		"label" : "UNIDAD MEDIDA",
	       		"id" : "DESC_UNIDAD_MEDIDA",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 18,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "COD_RECETA",
	       		"label" : "COD_RECETA",
	       		"id" : "COD_RECETA",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 20,
	       		"align":"right",
	       		"sortable" : false,
	       		"hidden" : true
       		},
       	  {
	       		"title" : false,
	       		"name" : "RECETA_DESCRIPCION",
	       		"label" : "RECETA",
	       		"id" : "RECETA_DESCRIPCION",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 24,
	       		"align":"left",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "COD_IMPUESTO",
	       		"label" : "COD_IMPUESTO",
	       		"id" : "COD_IMPUESTO",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 20,
	       		"align":"right",
	       		"sortable" : false,
	       		"hidden" : true
       		},
       	  {
	       		"title" : false,
	       		"name" : "IMP_PORCENTAJE",
	       		"label" : "IMPUESTO %",
	       		"id" : "IMP_PORCENTAJE",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 12,
	       		"align":"right",
	       		"sortable" : false,
	       		"hidden" : false
       		},
       	  {
	       		"title" : false,
	       		"name" : "PRECIO_VENTA",
	       		"label" : "PRECIO VENTA",
	       		"id" : "PRECIO_VENTA",
	       		"search" : false,
	       		"remove" : false,
	       		"width" : 20,
	       		"align":"right",
	       		"sortable" : false,
	       		formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces: 0},
	       		"hidden" : false
       		}],

                grouping: true,
                groupingView: {
                    groupField: ['TIPO_PRODUCTO_DESCRIPCION'],
                    groupSummary: [true],
                    groupColumnShow: [true],
                    groupCollapse: false,
                    groupOrder: ['asc']
                }
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
	id = $("#grillaRegistro").jqGrid('getCell', id, 'COD_PRODUCTO');
	if( id == false ){
		alert("Para eliminar un registro debe seleccionarlo previamente.");
	}else{
		if(!confirm("¿Esta seguro de que desea eliminar el registro seleccionado?"))
			return;

		$.ajax({
	        url: table+'/eliminar',
	        type: 'post',
	        data: {"id":id},
	        dataType: 'json',
	        async : false,
	        success: function(data){
	        	console.log(data);
	        	if(data.result == "ERROR"){
                    if(data.mensaje == 23504) {
                            mostarVentana("warning-registro-listado","No se puede eliminar el Registro por que esta siendo utilizado");
        			} else {
        				console.log("no muestra");
        				mostarVentana("warning-registro-listado","Ha ocurrido un error");
	    			}
	    			console.log(data);
				} else {
					mostarVentana("success-registro-listado","Los datos han sido eliminados exitosamente");
				    $("#grillaRegistro").trigger("reloadGrid");
				}
	        },
	        error: function(event, request, settings){
	            $.unblockUI();
	            // alert("Ha ocurrido un error");
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
	parametros.COD_PRODUCTO = rowObject[1];
	parametros.PRODUCTO_DESC = rowObject[2];
	parametros.COD_PRODUCTO_TIPO = rowObject[3];
	parametros.TIPO_PRODUCTO_DESCRIPCION = rowObject[4];
	parametros.COD_UNIDAD_MEDIDA = rowObject[5];
	parametros.DESC_UNIDAD_MEDIDA = rowObject[6];
	parametros.COD_RECETA = rowObject[7];
	parametros.RECETA_DESCRIPCION = rowObject[8];
	parametros.COD_IMPUESTO = rowObject[9];
	parametros.IMP_PORCENTAJE = rowObject[10];
	parametros.PRECIO_VENTA = rowObject[11];
	json = JSON.stringify(parametros);
	return "<a><img title='Editar' src='../../css/images/edit.png' data-toggle='modal'  onclick='editarRegistro("+json+");'/></a>";
}
