var pathname = window.location.pathname;
var table = pathname;
$(document).ready(function() {
    cargarGrillaCompras();
    cargarGrillaComprasModal();
    cargarGrillaComprasModalPagos();


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

function cargarGrillaCompras() {
    jQuery("#grillaCompras").jqGrid(
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
                        align: 'center',
                        edittype: 'link',
                        width: 5,
                        hidden: false,
                        classes: 'linkjqgrid',
                        sortable: false,
                        formatter: modalPagos
                    },
                    {
                        title: false,
                        name: 'COD_PROVEEDOR',
                        label: 'COD. PROV.',
                        id: 'COD_PROVEEDOR',
                        align: 'right',
                        width: 7,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'PROVEEDOR_NOMBRE',
                        label: 'PROVEEDOR',
                        id: 'PROVEEDOR_NOMBRE',
                        align: 'left',
                        width: 40,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'NRO_FACTURA_COMPRA',
                        label: 'CONTROL INTERNO',
                        id: 'NRO_FACTURA_COMPRA',
                        align: 'right',
                        width: 13,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'CONTROL_FISCAL',
                        label: 'CONTROL FISCAL',
                        id: 'CONTROL_FISCAL',
                        align: 'left',
                        width: 15,
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'FECHA_EMISION_FACTURA',
                        label: 'FECHA EMISION',
                        id: 'FECHA_EMISION_FACTURA',
                        width: 15,
                        align: 'center',
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'FECHA_VENCIMIENTO_FACTURA',
                        label: 'FECHA VENCIMIENTO',
                        id: 'FECHA_VENCIMIENTO_FACTURA',
                        width: 15,
                        align: 'center',
                        hidden: false
                    },
                    {
                        title: false,
                        name: 'MONTO_TOTAL_COMPRA',
                        label: 'MONTO COMPRA',
                        id: 'MONTO_TOTAL_COMPRA',
                        align: 'right',
                        width: 14,
                        hidden: false,
                        sorttype: 'float',
                        formatter: 'number'
                    },
                    {
                        title: false,
                        name: 'COD_MONEDA_COMPRA',
                        label: 'MONEDA',
                        id: 'COD_MONEDA_COMPRA',
                        width: 0,
                        hidden: true
                    },
                    {
                        title: false,
                        name: 'DESC_MONEDA',
                        label: 'MONEDA',
                        id: 'DESC_MONEDA',
                        align: 'center',
                        width: 8,
                        hidden: false
                    }
                    ,
                    {
                        title: false,
                        name: 'COD_FORMA_PAGO',
                        label: 'COD FORMA PAGO',
                        id: 'COD_FORMA_PAGO',
                        width: 8,
                        align: 'right',
                        hidden: true
                    },
                    {
                        title: false,
                        name: 'DES_FORMA_PAGO',
                        label: 'FORMA PAGO',
                        id: 'DES_FORMA_PAGO',
                        width: 10,
                        align: 'center',
                        hidden: false
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
        title: "Anular Compra",
        onClickButton: function() {
            anularcompra();	
        }
    }
    );


}
function cargarGrillaComprasModal() {
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
                height: "auto",
                gridview: false,
                pager: '#paginadorComprasModal',
                multiselect: false,
                viewrecords: true,
                autowidth: true,
                rowNum: 10,
                rowList: [10, 20, 30],
                colModel: [
                    {
                        name: 'idproveedor',
                        label: 'PROVEEDOR',
                        id: "idproveedor",
                        hidden: true,
                        width: 2,
                        sorttype: "int"

                    },
                    {
                        name: 'nombreproveedor',
                        label: 'PROV',
                        id: "nombreproveedor",
                        hidden: true,
                        width: 2

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
                        width: 10
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
                        "name": 'preciounitario',
                        "label": 'PRECIO UNI',
                        "id": 'preciounitario',
                        "align": "right",
                        "hidden": false,
                        width: 10
                    }, {
                        "title": false,
                        "name": 'codimpuesto',
                        "label": '',
                        "id": 'codimpuesto',
                        "align": "right",
                        "hidden": true,
                        width: 10
                    },
                    {
                        "title": false,
                        "name": 'iva5',
                        "label": 'IVA-5%',
                        "id": 'iva5',
                        "align": "right",
                        "hidden": false,
                        width: 10
                    },
                    {
                        "title": false,
                        "name": 'iva10',
                        "label": 'IVA-10%',
                        "id": 'iva10',
                        "align": "right",
                        "hidden": false,
                        width: 10
                    },
                    {
                        "title": false,
                        "name": 'totalparcial',
                        "label": 'TOTAL PARCIAL',
                        "id": 'totalparcial',
                        "align": "right",
                        "hidden": false,
                        width: 20,
                        formatter: "number",
                        summaryType: 'sum',
                        sorttype: "float"
                    }
                ],
                sortname: 'nombreproveedor',
                grouping: true,
                groupingView: {
                    groupField: ['nombreproveedor'],
                    groupSummary: [true],
                    groupColumnShow: [true],
                    groupCollapse: false,
                    groupOrder: ['asc']
                }

            }).navGrid('#paginadorComprasModal', {
        add: false,
        edit: false,
        del: false,
        view: false,
        search: false,
        refresh: false});

//	$("#grillaComprasModal").setGridWidth('100%');
    $("#grillaComprasModal").jqGrid('setGridWidth', widthOfGrid(), true);

    

}

function cargarGrillaComprasModalPagos() {
    jQuery("#grillaComprasModalPagos").jqGrid(
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
                multiselect: false,
                viewrecords: true,
                autowidth: true,
                pager: '#paginadorComprasModalPagos',
                rowNum: 10,
                rowList: [10, 20, 30],
                colModel: [
                    {
                        name: 'COD_PAGO_PROVEEDOR',
                        label: 'COD PAGO',
                        id: "COD_PAGO_PROVEEDOR",
                        hidden: false,
                        width: 5,
                        align: 'right'

                    },
                    {
                        name: 'MONTO_PAGO',
                        label: 'MONTO PAGO',
                        id: "MONTO_PAGO",
                        hidden: false,
                        width: 5,
                        sorttype: "int",
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
                        width: 5

                    },
                    {
                        title: false,
                        name: 'ESTADO_PAGO',
                        label: 'ESTADO',
                        id: 'ESTADO_PAGO',
                        align: "right",
                        hidden: false,
                        width: 5
                    }
                ]

            }).navGrid('#paginadorComprasModalPagos', {
        add: false,
        edit: false,
        del: false,
        view: false,
        search: false,
        refresh: true});

    $("#grillaComprasModalPagos").jqGrid('setGridWidth', widthOfGrid(), true);

    $("#grillaComprasModalPagos").jqGrid('navButtonAdd', '#paginadorComprasModalPagos', {
        buttonicon: 'ui-icon-trash',
        caption: "",
        title: "Anular pago",
        onClickButton: function() {
            anularPago();
        }
    }
    );



}

function cargarLinkModificar(cellvalue, options, rowObject)
{
    var parametros = new Object();

    parametros.COD_PROVEEDOR = rowObject[2];
    parametros.PROVEEDOR_NOMBRE = rowObject[3];
    parametros.NRO_FACTURA_COMPRA = rowObject[4];
    parametros.CONTROL_FISCAL = rowObject[5];
    parametros.FECHA_EMISION_FACTURA = rowObject[6];
    parametros.FECHA_VENCIMIENTO_FACTURA = rowObject[7];
    parametros.MONTO_TOTAL_COMPRA = rowObject[8];
    parametros.COD_MONEDA_COMPRA = rowObject[9];
    parametros.DESC_MONEDA = rowObject[10];
    parametros.COD_FORMA_PAGO = rowObject[11];
    parametros.DES_FORMA_PAGO = rowObject[12];
    parametros.ESTADO = rowObject[13];
//    console.log(JSON.stringify(parametros.ESTADO));
    json = JSON.stringify(parametros);
    return "<a><img title='EDITAR' src='../../css/images/edit.png' data-toggle='modal'  onclick='editarRegistro(" + json + ");'/></a>";
}

function modalPagos(cellvalue, options, rowObject)
{
    var permisoCompraPago = preguntaUsuario('tienecaja'); 


    var parametros = new Object();

    parametros.COD_PROVEEDOR = rowObject[2];
    parametros.PROVEEDOR_NOMBRE = rowObject[3];
    parametros.NRO_FACTURA_COMPRA = rowObject[4];
    parametros.CONTROL_FISCAL = rowObject[5];
    parametros.FECHA_EMISION_FACTURA = rowObject[6];
    parametros.FECHA_VENCIMIENTO_FACTURA = rowObject[7];
    parametros.MONTO_TOTAL_COMPRA = rowObject[8];
    parametros.COD_MONEDA_COMPRA = rowObject[9];
    parametros.DESC_MONEDA = rowObject[10];
    parametros.COD_FORMA_PAGO = rowObject[11];
    parametros.DES_FORMA_PAGO = rowObject[12];
    parametros.ESTADO = rowObject[13];
    json = JSON.stringify(parametros);


    return "<a><img title=PAGAR src='../../css/images/pago_boton.png' data-toggle='modal'  onclick='pagos(" + json + ");'/></a>";
}
function anularPago()
{
    var id = $("#grillaComprasModalPagos").jqGrid('getGridParam', 'selrow');
    var parametrosPagos = new Object();

    parametrosPagos.COD_PAGO_PROVEEDOR = $("#grillaComprasModalPagos").jqGrid('getCell', id, 'COD_PAGO_PROVEEDOR');
    parametrosPagos.MONTO_PAGO = $("#grillaComprasModalPagos").jqGrid('getCell', id, 'MONTO_PAGO');
    parametrosPagos.DES_BANCO = $("#grillaComprasModalPagos").jqGrid('getCell', id, 'DES_BANCO');
    parametrosPagos.NRO_CHEQUE = $("#grillaComprasModalPagos").jqGrid('getCell', id, 'NRO_CHEQUE');
    parametrosPagos.ESTADO_PAGO = $("#grillaComprasModalPagos").jqGrid('getCell', id, 'ESTADO_PAGO');
    parametrosPagos.NRO_FACTURA_COMPRA = $('#factura-modal-pagos').val();
     parametrosPagos.COD_MONEDA_COMPRA = $('#codigoMoneda-pagos').val();
    if (id == false) {
        alert("Para anular un pago debe seleccionarlo previamente.");
    } else {
        if (!confirm("Esta seguro de que desea anular el pago?"))
            return;
        else if (parametrosPagos.ESTADO_PAGO == 'ACTIVO') {
            parametrosPagos = JSON.stringify(parametrosPagos);
            $.ajax({
                url: table+'/anulacionpago',
                type: 'post',
                data: {"parametrosPagos": parametrosPagos},
                dataType: 'json',
                async: false,
                success: function(data) {
                    if (data.result == "ERROR") {
                        mostarVentana("warning-pagos", "Ha ocurrido un error, verifique sus datos");
                    } else {
                        $('#modalPagos').hide();
                        mostarVentana("success-title", "Pago anulado con exito");    
                        
                    }
                },
                error: function(event, request, settings) {
                    $.unblockUI();
                    mostarVentana("warning-pagos", "Ha ocurrido un error, verifique sus datos");
                }
            });
        } else {
        	mostarVentana("warning-pagos", "El pago se encuentra anulado");
        }
    }
    return false;
}

function anularcompra()
{
    var id = $("#grillaCompras").jqGrid('getGridParam', 'selrow');
    var nrofactura = 0;
    var estado = '';

    nrofactura = $("#grillaCompras").jqGrid('getCell', id, 'NRO_FACTURA_COMPRA');
    estado = $("#grillaCompras").jqGrid('getCell', id, 'ESTADO');
    if (id == false) {
        alert("Para anular una compra debe seleccionarlo previamente.");
    } else {
        if (!confirm("Esta seguro de que desea anular la compra?"))
            return;
        else if (estado == 'ACTIVO') {
           
            $.ajax({
                url: table+'/anularcompra',
                type: 'post',
                data: {"nrofactura": nrofactura},
                dataType: 'json',
                async: false,
                success: function(data) {
                    if (data.result == "ERROR") {
                        //alert("Ha ocurrido un error");
                    }else if(data.result == "PAGOPENDIENTE"){
                    	mostarVentana("warning-title","El registro posee pagos, anulelos previemente"); 
                    } else if (data.result == "EXITO"){
                    	$("#grillaCompras").trigger("reloadGrid");
                    	mostarVentana("success-title", "Compra anulada con exito");
                    	
                    } else {

                    	mostarVentana("warning-title", "Verifique los datos seleccinados");
                    }
                },
                error: function(event, request, settings) {
                    $.unblockUI();
//                    alert("Ha ocurrido verifique sus datos");
                }
            });
        } else {
            
            mostarVentana("warning-title", "El registro se encuentra anulado");
        }
    }
    return false;
}