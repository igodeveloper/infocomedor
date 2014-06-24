$(document).ready(function() {

    $.getJSON("compra2/proveedordata", function(data) {
        var nombreProveedor = [];
        var rucProveedor = [];
        var codProveedor = [];

        $(data).each(function(key, value) {
                nombreProveedor.push(value.PROVEEDOR_NOMBRE); 
                rucProveedor.push(value.PROVEEDOR_RUC);
                codProveedor.push(value.COD_PROVEEDOR);
                
//            console.log(value.PROVEEDOR_NOMBRE);
        });
        $("#razonsocial-modal").autocomplete({
                   source: nombreProveedor
                });
        $("#proveedor").autocomplete({
                   source: nombreProveedor
                });
        $("#descripcionproducto-item").autocomplete({
                   source: nombreProveedor
                });

    });



});
//    function proveedordata() {
//
//    $.ajax({
//        url: 'compra2/proveedordata',
//            type: 'post',
//            dataType: 'html',
//            async: false,
//            success: function(respuesta) {
//               console.log(respuesta);
//               var items = [];
////               items.push(respuesta.label);
////                console.log(respuesta.PROVEEDOR_NOMBRE);
//              $(respuesta).each(function(value){
//                 items.push(value.PROVEEDOR_NOMBRE); 
//               });
//                console.log(items);
//                
////                $("#razonsocial-modal").autocomplete({
////                    source: items
////                });
//            },
//            error: function(event, request, settings) {
//                alert(mostrarMensaje('Error'));
//            }
//        });
//    }






