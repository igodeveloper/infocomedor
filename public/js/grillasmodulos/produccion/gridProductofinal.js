var pathname = window.location.pathname;
var table = pathname;
$(document).ready(function() {
    cargarGrillaRegistro();
    cargarGrillaRegistroModal();
//    JQuery("grillaRegistroModal").hideCol("RECETA_DET_ITEM");
    
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
        message: "Aguarde un Momento"
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
                    objBusqueda.descripcionproducto = $('#descripcionproductofinal-filtro').attr("value");
                    objBusqueda.descripciontipoproducto = $('#descripciontipoproducto-filtro').attr("value");
                    

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
                        name: 'COD_PRODUCTO',
                        label: 'CODIGO',
                        id: 'COD_PRODUCTO',
                        align: 'right',
                        width: 20,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'PRODUCTO_DESC',
                        label: 'DESCRIPCION',
                        id: 'PRODUCTO_DESC',
                        align: 'left',
                        width: 40,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'SALDO_STOCK',
                        label: 'SALDO STOCK',
                        id: 'SALDO_STOCK',
                        align: 'right',
                        width: 20,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'DESC_UNIDAD_MEDIDA',
                        label: 'UNI MEDIDA',
                        id: 'DESC_UNIDAD_MEDIDA',
                        align: 'left',
                        width: 20,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'TIPO_PRODUCTO_DESCRIPCION',
                        label: 'TIPO PRODUCTO',
                        id: 'TIPO_PRODUCTO_DESCRIPCION',
                        align: 'left',
                        width: 30,
                        hidden: false
                    }
                
                ],
                 sortname : 'TIPO_PRODUCTO_DESCRIPCION',
                gridview : true,
                "grouping" : true,
                "groupingView" : {
                    groupField : [ 'TIPO_PRODUCTO_DESCRIPCION' ],
                    groupColumnShow : false,
                    groupDataSorted : true,
                    groupCollapse : true}
            }).navGrid('#paginadorRegistro', {
        add: false,
        edit: false,
        del: false,
        view: true,
        search: false,
        refresh: false});
    $("#grillaRegistro").setGridWidth($('#contenedor').width());
    // $("#grillaRegistro").jqGrid('navButtonAdd', '#paginadorRegistro', {
    //     buttonicon: 'ui-icon-trash',
    //     caption: "",
    //     title: "Borrar Receta",
    //     onClickButton: function() {
    //         borrarReceta();	
    //     }
    // }
    // );


}
function cargarGrillaRegistroModal() {
    jQuery("#grillaRegistroModal").jqGrid(
            {
                datatype: "local",
                beforeRequest: bloquearPantalla,
                loadComplete: function() {
                    $.unblockUI();
                },
                serializeGridData: function() {
                },
                refresh: true,
                formatter: null,
                ExpandColumn: true,
                width: null,
                height: "auto",
                gridview: false,
                pager: '#paginadorRegistroModal',
                multiselect: false,
                viewrecords: true,
                autowidth: true,
                rowNum: 10,
                rowList: [10, 20, 30],
                colModel: [
										
                    {
                        name: 'codproducto',
                        label: 'CODIGO',
                        id: "codproducto",
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
                        "name": 'cantidad',
                        "label": 'CANTIDAD',
                        "id": 'cantidad',
                        "align": "center",
                        "hidden": false,
                        width: 10,
                        formatter: 'number', 
                        formatoptions: { decimalPlaces: 2 }
                    },
                    {
                        "title": false,
                        "name": 'codUnidadMedida',
                        "label": 'codUnidadMedida',
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
                        "align": "center",
                        "sortable": false,
                        "hidden": false,
                        width: 7
                    },
                    {
                        "title": false,
                        "name": 'COD_RECETA',
                        "label": 'COD RECETA',
                        "id": 'COD_RECETA',
                        "align": "center",
                        "sortable": false,
                        "hidden": true,
                        width: 7
                    }
                    
                ]}).navGrid('#paginadorRegistroModal', {
        add: false,
        edit: false,
        del: false,
        view: false,
        search: false,
        refresh: false});

//	$("#grillaComprasModal").setGridWidth('100%');
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