
$(document).ready(function() {



});

function preguntaUsuario(permiso){

	switch (permiso) {
        case 'tienecaja':
            {
               
                break;
            }
        case 'compra':
            {
                dataString.value = $('#ruc-modal' + pago).attr("value");
//                     alert(data);
                break;
            }
        case 'venta':
            {
                dataString.value = $('#razonsocial-modal' + pago).attr("value");
//                      alert(data);
                break;
            }
        case 'inventario':
        {
            dataString.value = $('#razonsocial-modal' + pago).attr("value");
//                      alert(data);
            break;
        }

}