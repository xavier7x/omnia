/***********************************************************/
/*                                                         */
/*                   DOCUMENTO - READY                     */
/*                                                         */
/***********************************************************/
$(document).ready(function() {
    
    $("#formBuscarProductos").submit(function(){
        //console.log(encodeURI($("#search_products").val()));
        var datoUrl = encodeURI($("#buscar_producto").val());
        //var datoUrl = encodeURIComponent($("#search_products").val());
        document.location = $("#param_hostapp").val() + '/buscar/'+datoUrl;
        
        return false;
    });
    
});