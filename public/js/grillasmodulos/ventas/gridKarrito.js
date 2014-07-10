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
                        name: 'COD_KARRITO',
                        label: 'COD KARRITO',
                        id: 'COD_KARRITO',  
                        align: 'right',
                        width: 10,
                        formatter: 'number',
                        formatoptions:{thousandsSeparator: ".", decimalPlaces:0},
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'KAR_FECH_MOV',
                        label: 'FECHA',
                        id: 'KAR_FECH_MOV',
                        align: 'center',
                        width: 25,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'COD_CLIENTE',
                        label: 'COD CLIENTE',
                        id: 'COD_CLIENTE',
                        align: 'right',
                        width: 20,
                       
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'CLIENTE_DES',
                        label: 'CLIENTE',
                        id: 'CLIENTE_DES',
                        align: 'left',
                        width: 40,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'COD_MESA',
                        label: 'COD MESA',
                        id: 'COD_MESA',
                        align: 'center',
                        width: 10,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'COD_PRODUCTO',
                        label: 'COD PRODUCTO',
                        id: 'COD_PRODUCTO',
                        align: 'right',
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:0},
                        width: 10,
                        hidden: true
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
                        name: 'KAR_CANT_PRODUCTO',
                        label: 'CANTIDAD',
                        id: 'KAR_CANT_PRODUCTO',
                        align: 'right',
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:2},
                        width: 10,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'KAR_CANT_FACTURAR',
                        label: 'CANT FACTURAR',
                        id: 'KAR_CANT_FACTURAR',
                        align: 'right',
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:2},
                        width: 10,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'KAR_PRECIO_PRODUCTO',
                        label: 'PRECIO',
                        id: 'KAR_PRECIO_PRODUCTO',
                        align: 'right',
                        width: 20,
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:0},
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'KAR_PRECIO_FACTURAR',
                        label: 'PRECIO FACTURAR',
                        id: 'KAR_PRECIO_FACTURAR',
                        align: 'right',
                        width: 20,
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:0},
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'COD_MOZO',
                        label: 'MOZO',
                        id: 'COD_MOZO',
                        align: 'left',
                        width: 10,
                        hidden: true
                    },
                    {
                        title: false,
                        name: 'FACT_NRO',
                        label: 'FACTURA',
                        id: 'FACT_NRO',
                        align: 'right',
                        width: 10,
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:0},
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'ESTADO',
                        label: 'ESTADO',
                        id: 'ESTADO',
                        align: 'left',
                        width: 10,
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
        title: "Anular Pedido",
        onClickButton: function() {
        	anularPedido();	
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
               
                scrollerbar:true,
                gridview: false,
                height: "180",
                
                rowList: [],        // disable page size dropdown
                pgbuttons: false,     // disable page control like next, back button
                pgtext: null,         // disable pager text like 'Page 0 of 10'
                viewrecords: false,   // disable current view record text like 'View 1-10 of 100' 
                
                pager: '#paginadorRegistroModal',
                multiselect: false,
                viewrecords: true,
                autowidth: true,
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
                        "name": 'total',
                        "label": 'TOTALS',
                        "id": 'total',
                        "align": "right",
                        "hidden": false,
                        width: 10,
                        sorttype: 'float',
                        formatter: 'number'
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

function anularPedido()
{
    var id = $("#grillaRegistro").jqGrid('getGridParam', 'selrow');
    var parametrosPagos = new Object();

    parametrosPagos.COD_KARRITO = $("#grillaRegistro").jqGrid('getCell', id, 'COD_KARRITO');
    parametrosPagos.ESTADO = $("#grillaRegistro").jqGrid('getCell', id, 'ESTADO');
    parametrosPagos.FACT_NRO = $("#grillaRegistro").jqGrid('getCell', id, 'FACT_NRO');
   
    if (id == false) {
        alert("Para anular un pedido debe seleccionarlo previamente.");
    } else {
        if (!confirm("Esta seguro de que desea anular el pedido?"))
            return;
        else if (parametrosPagos.ESTADO == 'PE' && parametrosPagos.FACT_NRO == 0 ) {
            var codigokarrito = parametrosPagos.COD_KARRITO;
            $.ajax({
                url: table+'/anularpedido',
                type: 'post',
                data: {"codigokarrito": codigokarrito},
                dataType: 'json',
                async: false,
                success: function(data) {
                    if (data.result == "ERROR") {
                    //	mostarVentana("warning-title", "Ha ocurrido un error"); 
                    } else {
                    	$("#grillaRegistro").trigger("reloadGrid"); 
                        mostarVentana("success-title", "Pedido anulado con exito");    
                        
                    }
                },
                error: function(event, request, settings) {
                    $.unblockUI();
                  //  alert("Ha ocurrido un error");
                }
            });
        } else {
        	mostarVentana("warning-title", "El Pedido se encuentra anulado o ya ha sido facturado"); 
         
        }
    }
    return false;
}
