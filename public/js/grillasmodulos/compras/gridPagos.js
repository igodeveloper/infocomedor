var pathname = window.location.pathname;
var table = pathname;
$(document).ready(function() {
    cargarGrillaPagos();


});

function widthOfGrid() {
    var windowsWidth = $(window).width();
    var gridWidth = ((98 * 98 * windowsWidth) / (100 * 100));
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


function cargarGrillaPagos() {
    jQuery("#grillaPagos").jqGrid(
            {
                url: table+"/buscar",
                datatype: "json",
                mtype: "POST",
                beforeRequest: bloquearPantalla,
                loadComplete: function() {
                    $.unblockUI();
                },
                serializeGridData: function() {
                },
                refresh: true,
                formatter: null,
                ExpandColumn: true,
                height: "auto",
                gridview: false,
                multiselect: false,
                viewrecords: true,
                autowidth: true,
                pager: '#paginadorPagos',
                rowNum: 10,
                rowList: [10, 20, 30],
                colModel: [
                    {
                        name: 'COD_PAGO_PROVEEDOR',
                        label: 'COD PAGO',
                        id: "COD_PAGO_PROVEEDOR",
                        hidden: false,
                        width: 30,
                        align: 'right'

                    },
                    
                    {
                        name: 'NRO_FACTURA_COMPRA',
                        label: 'CONTROL INTERNO',
                        id: "NRO_FACTURA_COMPRA",
                        hidden: false,
                        width: 30,
                        sorttype: "int",
                        formatter: 'number',
                        formatoptions:{thousandsSeparator: ".", decimalPlaces: 0},
                        
                        align: 'right'

                    },
                    {
                        name: 'DES_BANCO',
                        label: 'BANCO',
                        id: "DES_BANCO",
                        hidden: false,
                        width: 30,
                        align: 'left'
                    },
                    {
                        title: false,
                        name: 'NRO_CHEQUE',
                        label: 'CHEQUE',
                        id: 'NRO_CHEQUE',
                        align: "right",
                        hidden: false,
                        width: 30

                    },{
                        name: 'MONTO_PAGO',
                        label: 'MONTO',
                        id: "MONTO_PAGO",
                        hidden: false,
                        width: 30,
                        sorttype: "int",
                        formatter: 'number',
                        formatoptions:{thousandsSeparator: ".", decimalPlaces: 0},
                        align: 'right'

                    },
                    {
                        title: false,
                        name: 'ESTADO_PAGO',
                        label: 'ESTADO',
                        id: 'ESTADO_PAGO',
                        align: "left",
                        hidden: false,
                        width: 30
                    }
                ]

            }).navGrid('#paginadorPagos', {
        add: false,
        edit: false,
        del: false,
        view: true,
        search: false,
        refresh: false});

    $("#grillaPagos").jqGrid('setGridWidth', widthOfGrid(), true);

    $("#grillaPagos").jqGrid('navButtonAdd', '#paginadorPagos', {
        buttonicon: 'ui-icon-trash',
        caption: "",
        title: "Anular pago",
        onClickButton: function() {
            anularPago();
        }
    }
    );



}

function anularPago()
{
    var id = $("#grillaPagos").jqGrid('getGridParam', 'selrow');
    var parametrosPagos = new Object();

    parametrosPagos.COD_PAGO_PROVEEDOR = $("#grillaPagos").jqGrid('getCell', id, 'COD_PAGO_PROVEEDOR');
    parametrosPagos.MONTO_PAGO = $("#grillaPagos").jqGrid('getCell', id, 'MONTO_PAGO');
    parametrosPagos.DES_BANCO = $("#grillaPagos").jqGrid('getCell', id, 'DES_BANCO');
    parametrosPagos.NRO_CHEQUE = $("#grillaPagos").jqGrid('getCell', id, 'NRO_CHEQUE');
    parametrosPagos.ESTADO_PAGO = $("#grillaPagos").jqGrid('getCell', id, 'ESTADO_PAGO');
    parametrosPagos.NRO_FACTURA_COMPRA = $("#grillaPagos").jqGrid('getCell', id, 'NRO_FACTURA_COMPRA');
     parametrosPagos.COD_MONEDA_COMPRA = 1;
     console.log(parametrosPagos);
    if (id == false) {
        alert("Para anular un pago debe seleccionarlo previamente.");
    } else {
        
        if (parametrosPagos.ESTADO_PAGO == 'ACTIVO') {
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
                        $("#grillaPagos").trigger("reloadGrid");
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