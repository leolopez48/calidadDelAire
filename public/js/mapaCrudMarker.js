var xInic, yInic;
var estaPulsado = false; 
var enMovimiento = "default";
var idPulsado = 0;
/*------------------datos de la base----------------------*/
// var descripcion=
// ["descripcion 1","descripcion 2","descripcion 3","descripcion 4","descripcion 5"];/*descripcion del marker*/
// var nombres=['img 1','img 2','img 3','img 4','img 5'];/*nombres de los marker*/
// var id = ['1','2','3','4','5'];/*ids de los marker*/
// var posision_left=['70%','50%','35%','46.2207%','55%'];/*posision en left del marker*/
// var posision_top=['45%','50%','60%','65.3314%','55%'];/*posision en top del marker*/
var estacion = new Array(); 
var descripcion = new Array();
var nombres = new Array();
var id = new Array();/*ids de los marquer*/
var posision_left = new Array();/*posision en left*/
var posision_top = new Array();/*posision en top*/
/*--------------------------------------------------------*/

$(document).ready(function(){
    cargarMarker($("#superposicion"));
    $("#nuevo", parent.document).on('click',function(){
        $("img[src='https://img2.freepng.es/20180528/gqu/kisspng-map-drawing-pin-clip-art-map-marker-5b0bc686c0b341.9842144315274983747893.jpg']").each(function(index) {
            $(this).remove();
        });
        $("#superposicion").append('<img id="default" src="https://img2.freepng.es/20180528/gqu/kisspng-map-drawing-pin-clip-art-map-marker-5b0bc686c0b341.9842144315274983747893.jpg" />');
        $("#default").css({"top":"50%","left":"50%"});
        mover("default");       
    }); 
    $("#agregar", parent.document).on('click',function(){
        let to = calcuTop($("#default"));
        let le = calcuLeft($("#default"));
        let des = $("#descripcion",parent.document).val();
        let nom = $("#nombre",parent.document).val();
        let esta = $("#estacion",parent.document).val();

        $.ajax({
            url: '{{ asset('controller/crudMarker.php') }}',
            type: 'post',
            data: {
                agregar : 'true',
                descipcion: des,
                nombre: nom,
                posLeft: le,
                posTop: to,
                estacion: esta
            }
        })
        .done(function(data) {
            // if(data=="BIEN"){
            //     $("#opcion",parent.document).val('1');
            // }
            cargarMarker($("#superposicion"));
        }).fail(function(data){
            cargarMarker($("#superposicion"));
        });
        $("#default").remove();
        cargarMarker($("#superposicion"));
    });
    $("#eliminar", parent.document).on('click',function(){
        $.ajax({
            url: '{{ asset('controller/crudMarker.php') }}',
            type: 'post',
            data: {
                eliminar : 'true',
                id : idPulsado
            }
        })
        .done(function(data) {
            // if(data=="BIEN"){
            //     $("#opcion",parent.document).val('1');
            // }
            cargarMarker($("#superposicion"));
        }).fail(function(data){
            cargarMarker($("#superposicion"));
        });
        cargarMarker($("#superposicion"));
        
    });
    $("#modificar", parent.document).on('click',function(){
        cargarMarker($("#superposicion"));
    });
    $("#cancelar", parent.document).on('click',function(){
        $("#agregar", parent.document).attr('disabled',true);
        $("#modificar", parent.document).attr('disabled',true);
        $("#eliminar", parent.document).attr('disabled',true);
        $("#cancelar", parent.document).attr('disabled',true);
        $("#default").remove();
        cargarMarker($("#superposicion"));
    });
         
});
let limpiarArray=()=>{
    while(estacion.length > 0){
        estacion.pop();
    }
        // estacion.splice(0, estacion.length);
    while(descripcion.length > 0){
        descripcion.pop();
    }
        // descripcion.splice(0, descripcion.length);
    while(nombres.length > 0){
        nombres.pop();
    }
        // nombres.splice(0, nombres.length);
    while(id.length > 0){
        id.pop();
    }
        // id.splice(0, id.length);
    while(posision_left.length > 0){
        posision_left.pop();
    }
        // posision_left.splice(0, posision_left.length);
    while(posision_top.length > 0){
        posision_top.pop();
    }
        // posision_top.splice(0, posision_top.length);
    
}
function ratonPulsado(evt) { 
//Obtener la posición de inicio
    evt.stopPropagation();
    xInic = evt.clientX;
    yInic = evt.clientY;    
    estaPulsado = true;
    //Para Internet Explorer: Contenido no seleccionable
    document.getElementById(enMovimiento).unselectable = true;
    
}
            
function ratonMovido(evt) {
    if(estaPulsado) {
        //Calcular la diferencia de posición
        var xActual = evt.clientX;
        var yActual = evt.clientY;    
        var xInc = xActual-xInic;
        var yInc = yActual-yInic;
        xInic = xActual;
        yInic = yActual;
           
        //Establecer la nueva posición
        var elemento = document.getElementById(enMovimiento);
        var position = getPosicion(elemento);
        let posy = position[0] + yInc;
        let posx = position[1] + xInc;
        let anchoMapa = parseFloat($("#superposicion").width());
        let altoMapa = parseFloat($("#superposicion").height());
        if(posy>=0 && posy<=altoMapa && posx>=0 && posx<=anchoMapa){
            elemento.style.top = (posy) + "px";
            elemento.style.left = (posx) + "px";
        }else{
            estaPulsado= false;
        }
    }
}
            
function ratonSoltado(evt) {
    estaPulsado = false;
}
            
/*
 * Función para obtener la posición en la que se encuentra el
 * elemento indicado como parámetro.
 * Retorna un array con las coordenadas x e y de la posición
 */
function getPosicion(elemento) {
    var posicion = new Array(2);
    if(document.defaultView && document.defaultView.getComputedStyle) {
        posicion[0] = parseInt(document.defaultView.getComputedStyle(elemento, null).getPropertyValue("top"))
        posicion[1] = parseInt(document.defaultView.getComputedStyle(elemento, null).getPropertyValue("left"));
    } else {
        //Para Internet Explorer
        posicion[0] = parseInt(elemento.currentStyle.top);             
        posicion[1] = parseInt(elemento.currentStyle.left);               
    }      
    return posicion;
}

let calcuTop=(elem)=>{
    let top1= parseFloat(elem.position().top);
    let alto = parseFloat($("#superposicion").height());
            
    return ((top1*100)/alto)+"%";

}
let calcuLeft=(elem)=>{
    let left1= parseFloat(elem.position().left);
    let ancho = parseFloat($("#superposicion").width());
    
    return (((left1-14)*100)/ancho)+"%";

}
let mover=(valor)=>{
    enMovimiento= valor;
    var el = document.getElementById(enMovimiento);
    if (el.addEventListener){
        el.addEventListener("mousedown", ratonPulsado, false);
        el.addEventListener("mouseup", ratonSoltado, false);
        document.addEventListener("mousemove", ratonMovido, false);
    } else { //Para IE
        el.attachEvent('onmousedown', ratonPulsado);
        el.attachEvent('onmouseup', ratonSoltado);
        document.attachEvent('onmousemove', ratonMovido);
    }   
}

let cargarMarker=(div)=>{
    $.ajax({
        url: '{{ asset('controller/Marker.php') }}',
        type: 'post',
        dataType: 'json'
    })
    .done(function(data) {
        limpiarArray();
        for (var i = 0; i < data.length; i++) {
            id.push(data[i]['id']);
            estacion.push(data[i]['estacion']);
            posision_left.push(data[i]['posLeft']);
            posision_top.push(data[i]['posTop']);
            nombres.push(data[i]['nombre']);
            descripcion.push(data[i]['descripcion']);
        }
        /*----genera los marker----*/
        /*elimina los marker si ya existen*/
        $("img[src='https://img2.freepng.es/20180528/gqu/kisspng-map-drawing-pin-clip-art-map-marker-5b0bc686c0b341.9842144315274983747893.jpg']").each(function(index) {
            $(this).remove();
        });
        /*crea los marker*/
        for(let j=0;j<posision_top.length;j++){
            div.append('<img class="sinArrastrar" id="'+id[j]+'" src="https://img2.freepng.es/20180528/gqu/kisspng-map-drawing-pin-clip-art-map-marker-5b0bc686c0b341.9842144315274983747893.jpg" />');
            $("#"+id[j]).css({'top':posision_top[j],'left':posision_left[j]});
        }
        /*efecto imagen blanco y negro al pulsar un marker*/
        $("img[src='https://img2.freepng.es/20180528/gqu/kisspng-map-drawing-pin-clip-art-map-marker-5b0bc686c0b341.9842144315274983747893.jpg']").each(function(index) {
            $(this).on('mousedown', function() {
                idPulsado = id[index];
                mover($(this).attr('id'));
                $("#estacion", window.parent.document).val(estacion[index]);
                $("#nombre", parent.document).val(nombres[index]);
                $("#descripcion", parent.document).val(descripcion[index]);
                for(let j=0;j<id.length;j++){
                    if(id[j] != this.id){
                        $("#"+id[j]).css({'-webkit-filter':'grayscale(100%)','filter':'grayscale(100%)'});
                    }else{
                        $("#"+id[j]).css({'-webkit-filter':'grayscale(0%)','filter':'grayscale(0%)'});
                    }
                }
                $("#modificar", parent.document).attr('disabled',false);
                $("#eliminar", parent.document).attr('disabled',false);
                $("#cancelar", parent.document).attr('disabled',false);
            });
        });
        /*----fin generar marker----*/
    })
    .fail(function(data) {
        alert('A ocurrido un error');
    });
}
