/*posisiones de los marker*/
// var descripcion;
// var nombres;
// var id = ['img1','img2','img3','img4','img5'];/*ids de los marquer*/
// var posision_left=['70%','50%','35%','46.2207%','55%'];/*posision en left*/
// var posision_top=['45%','50%','60%','65.3314%','55%'];/*posision en top*/
var estacion = new Array(); 
var descripcion = new Array();
var nombres = new Array();
var id = new Array();/*ids de los marquer*/
var posision_left = new Array();/*posision en left*/
var posision_top = new Array();/*posision en top*/

$(document).ready(function(){
	mostrar();
});

let mostrar=()=>{
	$.ajax({
		url: '{{ asset('controller/Marker.php')}}',
		type: 'post',
		dataType: 'json'
	})
	.done(function(data) {
		for (var i = 0; i < data.length; i++) {
			id.push(data[i]['id']);
			estacion.push(data[i]['estacion']);
			posision_left.push(data[i]['posLeft']);
			posision_top.push(data[i]['posTop']);
			nombres.push(data[i]['nombre']);
			descripcion.push(data[i]['descripcion']);
		}
		cargarMarker(estacion,posision_top,posision_left,$("#superposicion"));
		rebote();
	    setInterval("rebote()",1301);/*función para hacer bucle infinito de la animación*/
		
	})
	.fail(function(data) {
		alert('Ha ocurrido un error');
	});
}
/*función que hace el efecto de rebote*/
rebote=()=>{
	/*recorre todas las imagenes con el id rebote*/
	$("img[src='https://img2.freepng.es/20180528/gqu/kisspng-map-drawing-pin-clip-art-map-marker-5b0bc686c0b341.9842144315274983747893.jpg']").each(function(i){
		/*asigna la posision del marker*/
		$(this).css({"left": posision_left[i],"top":posision_top[i]});
		/*se crea una duración randon para la animación de los números comprendidos 
		del 400 al 700*/
		let duracion = getRandomInt(400,601);
		/*mueve la imagen hacia arriba*/
		$(this).animate({"top":'-=0.5%'},duracion,function(){
			/*mueve la imagen hacia abajo completando el efecto rebote*/
			$(this).animate({"top":'+=0.5%'},duracion);
		});
	});
}
/*función para generar un número randon en un rango de números*/	
function getRandomInt(min, max) {		
	let numero = 600;		  
	try {
	  	numero = Math.floor(Math.random() * (parseInt(max) - parseInt(min))) + parseInt(min);
	} catch(e) {
	  	numero = 600;
	}
	return numero;
}

let cargarMarker=(ids,pos_top,pos_left,div)=>{
    $("img[src='https://img2.freepng.es/20180528/gqu/kisspng-map-drawing-pin-clip-art-map-marker-5b0bc686c0b341.9842144315274983747893.jpg']").each(function(index) {
        $(this).remove();
    });
    for(let j=0;j<pos_top.length;j++){
        div.append('<img class="sinArrastrar" id="'+ids[j]+'" src="https://img2.freepng.es/20180528/gqu/kisspng-map-drawing-pin-clip-art-map-marker-5b0bc686c0b341.9842144315274983747893.jpg" />');
        $("#"+ids[j]).css({'top':pos_top[j],'left':pos_left[j]});
    }
}
let calcuTop=(elem)=>{
    let top1= parseFloat(elem.position().top);
    let alto = parseFloat($("#superposicion").height());
            
    return ((top1*100)/alto);

}
let calcuLeft=(elem)=>{
    let left1= parseFloat(elem.position().left);
    let ancho = parseFloat($("#superposicion").width());
    
    return (((left1-14)*100)/ancho);

}
