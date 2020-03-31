/*posisiones de los marker*/
// var descripcion;
// var nombres;
// var id = ['img1','img2','img3','img4','img5'];/*ids de los marquer*/
// var posision_left=['70%','50%','35%','46.2207%','55%'];/*posision en left*/
// var posision_top=['45%','50%','60%','65.3314%','55%'];/*posision en top*/
var estacion = new Array(); 
var direccion = new Array();
var municipio = new Array();
var departamento = new Array();
var img = new Array();
var nombres = new Array();
var id = new Array();/*ids de los marquer*/
var posision_left = new Array();/*posision en left*/
var posision_top = new Array();/*posision en top*/

$(document).ready(function(){
	mostrar();
});

let mostrar=()=>{

	$.ajax({
		url: '../../../registros/cargar',
		type: 'get',
		dataType: 'json'
	})
	.done(function(data) {
		if(data.length>0){
			for (var i = 0; i < data.length; i++) {
				id.push(data[i]['_id']);
				estacion.push(data[i]['correlativo']);
				posision_left.push(data[i]['posision_mapa_left']);
				posision_top.push(data[i]['posision_mapa_top']);
				municipio.push(data[i]['municipio']);
				departamento.push(data[i]['departamento']);
				direccion.push(data[i]['direccion']);
				img.push(data[i]['txt_img']);
			}
			cargarMarker($("#superposicion"));
			// rebote();
		 //    setInterval("rebote()",1301);/*función para hacer bucle infinito de la animación*/
		}else{
			window.parent.mapaMarkers = true;
		}
		
	})
	.fail(function(data) {
		alert('Ha ocurrido un error');
	});
}
/*función que hace el efecto de rebote*/
rebote=()=>{
	/*recorre todas las imagenes con el id rebote*/
	$("img[src='../../../img/marcador.png']").each(function(i){
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

// -----------------
let cargarMarker=(div)=>{
	let posisionToltip = "right";
    $("img[src='../img/marcador.png']").each(function(index) {
        $(this).remove();
    });
    //console.log(id)
    for(let j=0 ; j < posision_top.length; j++){
	    let title = "<img width='100%' height='90px' src='../../../storage/"+img[j]+"'><div class='border-1'>Departamento: <i><u>"+departamento[j]+"</u></i></div><div class='border-1'>Municipio: <i>"+municipio[j]+"</i></div>Dirección: "+direccion[j];
	    if(parseInt(posision_left[j].substring(0, posision_left[j].length-2))>50){
	    	posisionToltip = "left";
	    }
        div.append('<img data-placement="'+posisionToltip+'" data-toggle="tooltip" data-html="true" title="'+title
        	+'" class="sinArrastrar" id="'+id[j]+'" src="../../../img/marcador.png" />');
        $("#"+id[j]).css({'top':posision_top[j],'left':posision_left[j]});
        
        $("#"+id[j]).on('click', function(event) {
        	if(window.parent.correlativo!=id[j]){
	        	window.parent.correlativo = id[j];
	        	window.parent.actualizar = true;
        	}
        });
    }
    $('[data-toggle="tooltip"]').tooltip();
}

// -----------------

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
