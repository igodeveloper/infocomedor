var pathname = window.location.pathname;
var table = pathname;
var cantidad_pendiente = 0;
$(document).ready(function() {
    cargarGrillaFacturas();
    cargarGrillaFacturasModal();


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

function cargarGrillaFacturas() {
    jQuery("#grillaCompras").jqGrid(
            {
                url: table+"/buscar",
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
                pager: '#paginadorCompras',
                autowidth: true,
                rowNum: 30,
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
                    {
                        title: false,
                        name: '',
                        label: "",
                        id: 'pagos',
                        hidden: true
                        
                    },
                    {
                        title: false,
                        name: 'COD_CLIENTE',
                        label: 'CODIGO',
                        id: 'COD_CLIENTE',
                        align: 'right',
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:0},
                        width: 7,
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
                        name: 'CONTROL_FISCAL',
                        label: 'CONTROL FISCAL',
                        id: 'CONTROL_FISCAL',
                        align: 'left',
                        width: 13,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'FAC_NRO',
                        label: 'CONTROL INTERNO',
                        id: 'FAC_NRO',
                        align: 'right',
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:0},
                        width: 15,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'FAC_FECHA_EMI',
                        label: 'FECHA EMISION',
                        id: 'FAC_FECHA_EMI',
                        width: 15,
                        align: 'center',
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'FAC_FECH_VTO',
                        label: 'FECHA VENCIMIENTO',
                        id: 'FAC_FECH_VTO',
                        width: 15,
                        align: 'center',
                        hidden: true
                    },
                    {
                        title: false,
                        name: 'FAC_MONTO_TOTAL',
                        label: 'MONTO COMPRA',
                        id: 'FAC_MONTO_TOTAL',
                        align: 'right',
                        width: 14,
                        hidden: false,
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:0}
                    },
                    {
                        title: false,
                        name: 'ESTADO',
                        label: 'ESTADO',
                        id: 'ESTADO',
                        width: 8,
                        align: 'center',
                        hidden: false
                    }
                ]
            }).navGrid('#paginadorCompras', {
        add: false,
        edit: false,
        del: false,
        view: true,
        search: false,
        refresh: false});
    $("#grillaCompras").setGridWidth($('#contenedor').width());
    $("#grillaCompras").jqGrid('navButtonAdd', '#paginadorCompras', {
        buttonicon: 'ui-icon-trash',
        caption: "",
        title: "Anular Venta",
        onClickButton: function() {
            anularventa();	
        }
    }
    );


}
function cargarGrillaFacturasModal() {
    jQuery("#grillaComprasModal").jqGrid(
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
                height: "180",
                gridview: false,
                scrollerbar:true,
                rowList: [],        // disable page size dropdown
                pgbuttons: false,     // disable page control like next, back button
                pgtext: null,         // disable pager text like 'Page 0 of 10'
                viewrecords: false,   // disable current view record text like 'View 1-10 of 100' 
                pager: '#paginadorComprasModal',
                multiselect: false,
                autowidth: true,
                cellEdit: true,
                cellsubmit : 'clientArray',
                editurl: 'clientArray',
                closeOnEscape: true,
                beforeEditCell: function (rowid, name, val, iRow, iCol) {

                    cantidad_pendiente = parseFloat($("#grillaComprasModal").jqGrid('getCell', rowid, 'KAR_CANT_FACTURAR'));
                },
                afterSaveCell: function(rowid, name,val,iRow,iCol){

                       var precio =  parseInt($("#grillaComprasModal").jqGrid('getCell', rowid, 'KAR_PRECIO_PRODUCTO'));
                       var cantidad = parseFloat($("#grillaComprasModal").jqGrid('getCell', rowid, 'KAR_CANT_PRODUCTO'));
                       var precio_unitario = parseFloat(precio/cantidad);
                       var cantidad_facturar = parseFloat($("#grillaComprasModal").jqGrid('getCell', rowid, 'KAR_CANT_FACTURAR'));
                       console.log(cantidad_facturar+"fac-pend"+cantidad_pendiente);
                       if(cantidad_facturar<=cantidad_pendiente){
                            var precio_facturar = parseFloat(precio_unitario*cantidad_facturar);
                            $("#grillaComprasModal").jqGrid('setRowData',rowid,{KAR_PRECIO_FACTURAR: precio_facturar});
                       }else{
                            mostarVentana("warning", "El valor que desea ingresar supera el monto pendiente");
                            $("#grillaComprasModal").jqGrid('setRowData',rowid,{KAR_CANT_FACTURAR: cantidad_pendiente});
                       }
                       
                        
                    // alert($("#grillaComprasModal").jqGrid('getCell', rowid, 'KAR_CANT_FACTURAR'));

                    },
                colModel: [
					                    {
                        title: false,
                        name: 'COD_KARRITO',
                        label: 'COD PEDIDO',
                        id: 'COD PEDIDO',
                        align: 'right',
                        width: 10,
                        hidden: true
                    },
                    {
                        title: false,
                        name: 'KAR_FECH_MOV',
                        label: 'FECHA',
                        id: 'KAR_FECH_MOV',
                        align: 'center',
                        width: 20,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'COD_CLIENTE',
                        label: 'COD CLIENTE',
                        id: 'COD_CLIENTE',
                        align: 'right',
                        width: 20,
                        hidden: true
                    },
                    {
                        title: false,
                        name: 'CLIENTE_DES',
                        label: 'CLIENTE',
                        id: 'CLIENTE_DES',
                        align: 'left',
                        width: 30,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'COD_MESA',
                        label: 'MESA',
                        id: 'COD_MESA',
                        align: 'center',
                        width: 5,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'COD_PRODUCTO',
                        label: 'COD PRODUCTO',
                        id: 'COD_PRODUCTO',
                        align: 'right',
                        width: 10,
                        hidden: true
                    },
                    {
                        title: false,
                        name: 'PRODUCTO_DESC',
                        label: 'PRODUCTO',
                        id: 'PRODUCTO_DESC',
                        align: 'left',
                        width: 30,
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
                        width: 20,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'KAR_PRECIO_PRODUCTO',
                        label: 'PRECIO',
                        id: 'KAR_PRECIO_PRODUCTO',
                        align: 'right',
                        width: 15,
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
                        width: 25,
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:0},
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'COD_MOZO',
                        label: 'COD MOZO',
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
                        hidden: true
                    },
                    {
                        title: false,
                        name: 'ESTADO',
                        label: 'ESTADO',
                        id: 'ESTADO',
                        align: 'right',
                        width: 10,
                        hidden: true
                    }
                ]

            }).navGrid('#paginadorComprasModal', {
        add: false,
        edit: false,
        del: false,
        view: false,
        search: false,
        refresh: false});

//	$("#grillaComprasModal").setGridWidth('100%');
    $("#grillaComprasModal").jqGrid('setGridWidth', widthOfGrid(), true);
    $("#grillaComprasModal").jqGrid('navButtonAdd', '#paginadorComprasModal', {
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

    parametros.COD_CLIENTE = rowObject[2];
    parametros.CLIENTE_DES = rowObject[3];
    parametros.CONTROL_FISCAL = rowObject[4];
    parametros.FAC_NRO = rowObject[5];
    parametros.FAC_FECHA_EMI = rowObject[6];
    parametros.FAC_FECH_VTO = rowObject[7];
   
//    console.log(JSON.stringify(parametros.ESTADO));
    json = JSON.stringify(parametros);
    return "<a><img title='EDITAR' src='../../css/images/edit.png' data-toggle='modal'  onclick='editarRegistro(" + json + ");'/></a>";
}



function anularventa()
{
    console.log("im here");
    var id = $("#grillaCompras").jqGrid('getGridParam', 'selrow');
    var nrofactura = 0;
    var estado = '';
    console.log("im here"+id);
    nrofactura = $("#grillaCompras").jqGrid('getCell', id, 'FAC_NRO');
    estado = $("#grillaCompras").jqGrid('getCell', id, 'ESTADO');
    if (id == null) {
        mostarVentana("warning-title","Para anular una compra debe seleccionarlo previamente.");
    } else {
         if (estado != 'ANULADO') {

            $.ajax({
                url: '/ventas/factura/anularventa',
                dataType: 'post',
                data: {"codigofactura": nrofactura},
                dataType: 'json',
                async: false,
                success: function(data) {
                    if (data.result == "ERROR") {
                        mostarVentana("warning-title","No se pudo anular el registro, recargue la pagina e intente de nuevo"); 
                    } else if (data.result == "EXITO"){
                    	$("#grillaCompras").trigger("reloadGrid");
                    	mostarVentana("success-title", "Venta anulada con existo");
                    } else {
                    	//alert("Ha ocurrido un error");
                    }
                },
                error: function(event, request, settings) {
                    $.unblockUI();
                    // alert("Ha ocurrido un error");
                }
            });
        } else {

            mostarVentana("warning-title","El registro esta anulado");
        }
    }
    return false;
}


function cargarGrillaFacturasModalKarrito() {
    jQuery("#grillaRegistroKarrito").jqGrid(
            {
            	 datatype: "local",
            	 multiselect: true,
                beforeRequest: bloquearPantalla,
                loadComplete: function() {
                    $.unblockUI();
                },
                refresh: true,
                altRows: true,
                scrollerbar:true,
                height: "180",
                rowList: [],        // disable page size dropdown
                pgbuttons: false,     // disable page control like next, back button
                pgtext: null,         // disable pager text like 'Page 0 of 10'
                pager: '#paginadorRegistroKarrito',
                autowidth: true,
                colModel: [
                   
                    {
                        title: false,
                        name: 'COD_KARRITO',
                        label: 'COD PEDIDO',
                        id: 'COD_KARRITO',
                        align: 'right',
                        width: 10,
                        hidden: true
                    },
                    {
                        title: false,
                        name: 'KAR_FECH_MOV',
                        label: 'FECHA',
                        id: 'KAR_FECH_MOV',
                        align: 'center',
                        width: 20,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'COD_CLIENTE',
                        label: 'COD CLIENTE',
                        id: 'COD_CLIENTE',
                        align: 'right',
                        width: 20,
                        hidden: true
                    },
                    {
                        title: false,
                        name: 'CLIENTE_DES',
                        label: 'CLIENTE',
                        id: 'CLIENTE_DES',
                        align: 'left',
                        width: 30,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'COD_MESA',
                        label: 'MESA',
                        id: 'COD_MESA',
                        align: 'center',
                        width: 5,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'COD_PRODUCTO',
                        label: 'COD PRODUCTO',
                        id: 'COD_PRODUCTO',
                        align: 'right',
                        width: 10,
                        hidden: true
                    },
                    {
                        title: false,
                        name: 'PRODUCTO_DESC',
                        label: 'PRODUCTO',
                        id: 'PRODUCTO_DESC',
                        align: 'left',
                        width: 30,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'KAR_CANT_PRODUCTO',
                        label: 'CANTIDAD',
                        id: 'KAR_CANT_PRODUCTO',
                        align: 'right',
                        width: 10,
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:2},
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'KAR_CANT_FACTURAR',
                        label: 'CANT FACTURAR',
                        id: 'KAR_CANT_FACTURAR',
                        align: 'right',
                        width: 15,
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:2},
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'KAR_PRECIO_PRODUCTO',
                        label: 'PRECIO',
                        id: 'KAR_PRECIO_PRODUCTO',
                        align: 'right',
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:0},
                        width: 15,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'KAR_PRECIO_FACTURAR',
                        label: 'PRECIO FACTURAR',
                        id: 'KAR_PRECIO_FACTURAR',
                        align: 'right',
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:0},
                        width: 20,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'COD_MOZO',
                        label: 'MOZO',
                        id: 'COD_MOZO',
                        align: 'left',
                        width: 5,
                        hidden: true
                    },
                    {
                        title: false,
                        name: 'FACT_NRO',
                        label: 'FACT NRO',
                        id: 'FACT_NRO',
                        align: 'right',
                        width: 10,
                        hidden: true
                    },
                    {
                        title: false,
                        name: 'ESTADO',
                        label: 'ESTADO',
                        id: 'ESTADO',
                        align: 'right',
                        width: 10,
                        hidden: true
                    }
                
                ]
            }).navGrid('#paginadorRegistroKarrito', {
        add: false,
        edit: false,
        del: false,
        view: false,
        search: false,
        refresh: false});
    $("#grillaRegistroKarrito").setGridWidth('100%');


}

function eliminarItem(){
	var id = $("#grillaComprasModal").jqGrid('getGridParam','selrow');
    var rowdata = jQuery("#grillaComprasModal").jqGrid('getRowData', id);
	if( id == false ){
		alert("Para eliminar un registro debe seleccionarlo previamente.");
	}else{
        
        var rows = jQuery("#grillaRegistroKarrito").jqGrid('getRowData');
        if(rows.length > 0){
            
            jQuery("#grillaRegistroKarrito").jqGrid('addRowData', (rows.length) + 1, rowdata);
        }
        $('#grillaComprasModal').jqGrid('delRowData',id);
	}
}

function cargarGrillaRegistroPagoVenta() {
    jQuery("#grillaRegistroPagoVenta").jqGrid(
            {
                datatype: "local",
                refresh: true,
                formatter: null,
                ExpandColumn: true,
                width: null,
                height: "150",
                gridview: false,
                multiselect: false,
                viewrecords: true,
                autowidth: true,
                rowNum: 10,
                rowList: [10, 20, 30],
                colModel: [
                    {
                        name: 'FORMA_PAGO',
                        label: 'FORMA PAGO',
                        id: "FORMA_PAGO",
                        hidden: true,
                        width: 5,
                        sorttype: "int",
                        align: 'right'

                    },
                    {
                        name: 'CODIGO_CAJA',
                        label: 'COD CAJA',
                        id: "CODIGO_CAJA",
                        hidden: true,
                        width: 5,
                        sorttype: "int",
                        align: 'right'

                    },
                    {
                        name: 'USUARIO_CAJA',
                        label: 'USUARIO',
                        id: "USUARIO_CAJA",
                        hidden: true,
                        width: 5,
                        align: 'right'

                    },{
                        name: 'MONTO_PAGO',
                        label: 'MONTO PAGO',
                        id: "MONTO_PAGO",
                        hidden: false,
                        width: 5,
                        formatter: 'number',
                formatoptions:{thousandsSeparator: ".", decimalPlaces:0},
                        
                        align: 'right'

                    },
                    {
                        name: 'DES_BANCO',
                        label: 'BANCO',
                        id: "DES_BANCO",
                        hidden: false,
                        width: 5,
                        align: 'right'
                    },
                    {
                        title: false,
                        name: 'NRO_CHEQUE',
                        label: 'CHEQUE',
                        id: 'NRO_CHEQUE',
                        align: "right",
                        hidden: false,
                        formatter: 'number',
                        
                        width: 5

                    }
                ]

            }).navGrid('#paginadorRegistroPagoVenta', {
        add: false,
        edit: false,
        del: false,
        view: false,
        search: false,
        refresh: true});

    $("#grillaRegistroPagoVenta").jqGrid('setGridWidth', widthOfGrid(), true);

    $("#grillaRegistroPagoVenta").jqGrid('navButtonAdd', '#paginadorRegistroPagoVenta', {
        buttonicon: 'ui-icon-trash',
        caption: "",
        title: "Borrar Pago",
        onClickButton: function() {
            var id = $("#grillaRegistroPagoVenta").jqGrid('getGridParam','selrow');
                     $('#grillaRegistroPagoVenta').jqGrid('delRowData',id);
           
        }
    });
}