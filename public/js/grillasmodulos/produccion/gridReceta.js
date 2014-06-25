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
        message: "Aguarde Momento"
    });
}

function cargarGrillaRegistro() {
    jQuery("#grillaRegistro").jqGrid(
            {
                url: table+"/listar",
                datatype: "json",
                mtype: "POST",
                beforeRequest: bloquearPantalla,
                loadComplete: function() {
                    $.unblockUI();
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
                        width: 3,
                        hidden: false,
                        classes: 'linkjqgrid',
                        sortable: false,
                        formatter: cargarLinkModificar
                    },
                    {
                        title: false,
                        name: 'COD_RECETA',
                        label: 'COD RECETA',
                        id: 'COD_RECETA',
                        align: 'right',
                        width: 20,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'RECETA_DESCRIPCION',
                        label: 'DESCRIPCION',
                        id: 'RECETA_DESCRIPCION',
                        align: 'left',
                        width: 70,
                        hidden: false
                    }
                ]
            }).navGrid('#paginadorRegistro', {
        add: false,
        edit: false,
        del: false,
        view: true,
        search: false,
        refresh: false});
    $("#grillaRegistro").setGridWidth($('#contenedor').width());
    $("#grillaRegistro").jqGrid('navButtonAdd', '#paginadorRegistro', {
        buttonicon: 'ui-icon-trash',
        caption: "",
        title: "Borrar Receta",
        onClickButton: function() {
            borrarReceta();	
        }
    }
    );


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
					    "title": false,
					    "name": 'RECETA_DET_ITEM',
					    "label": 'ITEM RECETA',
					    "id": 'RECETA_DET_ITEM',
					    "align": "center",
					    "sortable": false,
					    "hidden": false,
					    width: 7
					    },
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

function cargarLinkModificar(cellvalue, options, rowObject)
{
    var parametros = new Object();

    parametros.COD_RECETA = rowObject[1];
    parametros.RECETA_DESCRIPCION = rowObject[2];
//    console.log(JSON.stringify(parametros.ESTADO));
    json = JSON.stringify(parametros);
    return "<a><img title='EDITAR' src='../../css/images/edit.png' data-toggle='modal'  onclick='editarRegistro(" + json + ");'/></a>";
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

function borrarReceta() {
    var id = $("#grillaRegistro").jqGrid('getGridParam', 'selrow');
    var id_receta = $("#grillaRegistro").jqGrid('getCell', id, 'COD_RECETA');
    if (id == false) {
        alert("Para eliminar un registro debe seleccionarlo previamente.");
    } else {
        if (!confirm("Esta seguro de que desea eliminar el registro seleccionado?"))
            return;

        $.ajax({
            url: '/produccion/receta/eliminar',
            type: 'post',
            data: {"id": id_receta},
            dataType: 'json',
            async: false,
            success: function(data) {
                if (data.result == "ERROR") {
                    if (data.mensaje == 23504) {
                        mostarVentana("warning-title", "No se puede eliminar el Registro por que esta siendo utilizado");
                    } else {
//                        mostarVentana("warning-title", "Ha ocurrido un error");
                    }
                } else {
                    mostarVentana("success-title", "Los datos han sido eliminados exitosamente");
                    $("#grillaRegistro").trigger("reloadGrid");
                }
            },
            error: function(event, request, settings) {
                $.unblockUI();
//                alert("Ha ocurrido un error");
            }
        });
    }
    return false;
}