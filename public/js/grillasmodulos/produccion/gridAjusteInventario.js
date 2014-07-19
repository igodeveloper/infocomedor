var pathname = window.location.pathname;
var table = pathname;
$(document).ready(function() {
    cargarGrillaRegistro();
    cargarGrillaRegistroModal();
	$(window).keydown(function(e) {   // or keyup, keypress
        if (e.keyCode == '27' ) {  // ESC
            e.preventDefault();
        	jQuery('#grillaRegistroModal').jqGrid('setSelection', '-1');
        }
	});
    
});

function widthOfGrid() {
    var windowsWidth = $(window).width();
    var gridWidth = ((90 * 94 * windowsWidth) / (100 * 100));
    return gridWidth;
}

function setTooltipsOnColumnHeader(grid, iColumn, text) {
    var thd = jQuery("thead:first", grid[0].grid.hDiv)[0];
    jQuery("tr.ui-jqgrid-labels th:eq(" + iColumn + ")", thd).attr("title", text);
}

function bloquearPantalla() {
    $.blockUI({
        message: "Aguarde Momento"
    });
}

function cargarGrillaRegistro() {
    jQuery("#grillaRegistro").jqGrid(
            {
                url: table+"/buscar",
                datatype: "json",
                mtype: "POST",
                beforeRequest: bloquearPantalla,
                loadComplete: function() {
                    $.unblockUI();
                },
                serializeGridData : function(data) {

					var objBusqueda = new Object();
					objBusqueda.tipoproducto = $('#descripciontipoproducto-filtro').attr("value");
					objBusqueda.producto = $('#descripcionproductofinal-filtro').attr("value");
					

					var t = $.param(data) + '&' + "data="
							+ JSON.stringify(objBusqueda);
					return t;
				},
                refresh: true,
                scrollerbar: true,
                altRows: true,
                height: "auto",
                width: "auto",
                pager: '#paginadorRegistro',
                autowidth: false,
                rowNum: 10,
                rowList: [10, 20, 30],
                colModel: [
{
    title: false,
    name: '',
    label: "",
    id: 'modificar',
    align: 'center',
    edittype: 'link',
    width: 5,
    hidden: false,
    classes: 'linkjqgrid',
    sortable: false,
    formatter: cargarLinkModificar
},
                    
                    {	 title: false,
                         name: 'COD_INVENTARIO',
                         label: 'COD_INVENTARIO',
                         id: 'INVENTARIO',
                         align: 'right',
                         width: 20,
                         formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces: 0},
                         hidden: false
                     },
                     {
                        title: false,
                        name: 'COD_PRODUCTO',
                        label: 'COD PRODUCTO',
                        id: 'COD_PRODUCTO',
                        align: 'right',
                        width: 20,
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces: 0},
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'PRODUCTO_DESC',
                        label: 'PRODUCTO',
                        id: 'PRODUCTO_DESC',
                        align: 'left',
                        width: 40,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'TIPO_PRODUCTO_DESCRIPCION',
                        label: 'TIPO PRODUCTO',
                        id: 'TIPO_PRODUCTO_DESCRIPCION',
                        align: 'left',
                        width: 20,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'INVENTARIO_FECHA',
                        label: 'FECHA INVENTARIO',
                        id: 'INVENTARIO_FECHA',
                        formatter: 'date', 
                        formatoptions: { srcformat: 'Y/m/d', newformat: 'd/m/Y'},
                        align: 'left',
                        width: 20,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'INVENTARIO_ENTRADA',
                        label: 'EXISTENCIA',
                        id: 'INVENTARIO_ENTRADA',
                        align: 'right',
                        width: 20,
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces: 2},
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'INVENTARIO_SALIDA',
                        label: 'INVENTARIO FISICO',
                        id: 'INVENTARIO_SALIDA',
                        align: 'right',
                        width: 20,
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces: 2},
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'INVENTARIO_SALDO',
                        label: 'DIFERENCIA',
                        id: 'INVENTARIO_SALDO',
                        align: 'right',
                        width: 20,
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces: 2},
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'DESC_UNIDAD_MEDIDA',
                        label: 'UNIDAD MEDIDA',
                        id: 'DESC_UNIDAD_MEDIDA',
                        align: 'left',
                        width: 20,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'ESTADO',
                        label: 'AJUSTADO',
                        id: 'ESTADO',
                        align: 'left',
                        width: 20,
                        hidden: false
                    }
                
                ],
                
                sortname : 'COD_INVENTARIO',
				gridview : true,
				"grouping" : true,
				"groupingView" : {
					groupField : [ 'COD_INVENTARIO' ],
					groupColumnShow : false,
					groupDataSorted : true,
					groupCollapse : true,
					groupText : [ '<b>Código de Inventario {0} -  Cantidad de Productos {1}</b>' ]
				}
            }).navGrid('#paginadorRegistro', {
        add: false,
        edit: false,
        del: false,
        view: true,
        search: false,
        refresh: false});
    $("#grillaRegistro").setGridWidth($('#contenedor').width());
//    $("#grillaRegistro").jqGrid('navButtonAdd', '#paginadorRegistro', {
//        buttonicon: 'ui-icon-trash',
//        caption: "",
//        title: "Borrar Receta",
//        onClickButton: function() {
//            borrarReceta();	
//        }
//    }
//    );


}
function cargarGrillaRegistroModal() {
	var lastsel2;
    jQuery("#grillaRegistroModal").jqGrid(
            {
                datatype: "local",
                multiselect: true,
                beforeRequest: bloquearPantalla,
                refresh: true,
                formatter: null,
                ExpandColumn: true,
                width: null,
                height: "150",
                gridview: false,
                pager: '#paginadorRegistroModal',
                rowList: [],        // disable page size dropdown
                pgbuttons: false,     // disable page control like next, back button
                pgtext: null,         // disable pager text like 'Page 0 of 10'
                viewrecords: true,
                autowidth: true,
               scrollerbar:true,
                cellEdit: true,
                cellsubmit : 'clientArray',
                editurl: 'clientArray',
                closeOnEscape: true,
                
                colModel: [
										
                    {
                        name: 'codproducto',
                        label: 'CODIGO',
                        id: "codproducto",
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces: 0},
                        hidden: false,
                        width: 8
                    },
                    {
                        "title": false,
                        "name": 'descripcionproducto',
                        "label": 'DESCRIPCION',
                        "id": 'descripcionproducto',
                        "align": "left",
                        "hidden": false,
                        width: 25
                    },
                    {
                        "title": false,
                        "name": 'codUnidadMedida',
                        "label": 'UDIDAD MEDIAD',
                        "id": 'codUnidadMedida',
                        "align": "left",
                        "hidden": true,
                        width: 0
                    },
                    {
                        "title": false,
                        "name": 'unidadmedida',
                        "label": 'UNI MED',
                        "id": 'unidadmedida',
                        "align": "left",
                        "sortable": false,
                        "hidden": false,
                        width: 7
                    },
                    {
                        "title": false,
                        "name": 'COD_RECETA',
                        "label": 'COD RECETA',
                        "id": 'COD_RECETA',
                        "align": "right",
                        "sortable": false,
                        "hidden": true,
                        width: 7
                    },
                    {
                        "title": false,
                        "name": 'saldo',
                        "label": 'EXISTENCIA',
                        "id": 'saldo',
                        "align": "right",
                        "sortable": false,
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:2},
                        "hidden": false,
                        width: 7,
                        
                    },
                    {
                        "title": false,
                        "name": 'saldos',
                        "label": 'INVENTARIO',
                        "id": 'saldos',
                        "align": "right",
                        "sortable": false,
                        "hidden": false,
                        width: 7,
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces: 2},
                        editable: true
                        
                    },
                    {
                        "title": false,
                        "name": 'DIFERENCIA',
                        "label": 'DIFERENCIA',
                        "id": 'DIFERENCIA',
                        "align": "right",
                        "sortable": false,
                        "hidden": false,
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces: 2},
                        width: 7
                        
                        
                    }
                    
                ]}).navGrid('#paginadorRegistroModal', {
        add: false,
        edit: false,
        del: false,
        view: false,
        search: false,
        refresh: false});
    $("#grillaRegistroModal").jqGrid('setGridWidth', widthOfGrid(), true);
    $("#grillaRegistroModal").jqGrid('navButtonAdd', '#paginadorRegistroModal', {
        buttonicon: 'ui-icon-trash',
        caption: "",
        title: "Borrar Item",
        onClickButton: function() {
            eliminarItem();	
        }
    }
    );
}

function eliminarItem(){
	var id = $("#grillaRegistroModal").jqGrid('getGridParam','selrow');
	if( id == false ){
		alert("Para eliminar un registro debe seleccionarlo previamente.");
	}else{
		if(!confirm("¿Esta seguro de que desea eliminar el registro seleccionado?")){
			return;
		}
		$('#grillaRegistroModal').jqGrid('delRowData',id);
	}
}

function cargarLinkModificar(cellvalue, options, rowObject)
{
    var parametros = new Object();
    
    parametros.nro_inventario = rowObject[1];
    parametros.INVENTARIO_SALDO = rowObject[8];
    parametros.ESTADO = rowObject[10];
//    console.log(JSON.stringify(parametros.ESTADO));
    json = JSON.stringify(parametros);
    return "<a><img title='EDITAR' src='../../css/images/edit.png' data-toggle='modal'  onclick='modalInventario(" + json + ");'/></a>";
}