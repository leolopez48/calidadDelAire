var  correlativo = "";
var actualizar = false;
var mapaMarkers = false;
var fechaIni = "";
var fechaFin = "";
$(document).ready(function() {
	crearGrafica();	
	setInterval("Actualizar()", 1000);
	$("#datosNacionales").on('click', function(event) {
		if(correlativo!=''){
			correlativo = "";
			actualizar = true;
		}
	});
	$("#limpiar").on('click', function(event) {
		if($("#fechaIni").val()!='' && $("#fechaFin").val()!=''){
			fechaIni = "";
			fechaFin = "";
			$("#fechaIni").val('');
			$("#fechaFin").val('');
			actualizar = true;
		}
		
	});
	$("#filtrar").on('click', function(event) {
		if( $("#fechaIni").val() != '' && $("#fechaFin").val() != '' ){
			var f1 = Date.parse( $("#fechaIni").val() );
			var f2 = Date.parse( $("#fechaFin").val() );
			var fechaAhora = new Date();
			if(f1 < f2 && fechaAhora>=f1){
					actualizar = true;
			}else{
				Swal.fire('¡Error!','Verifique las fechas del filtro ingresadas.','error');
			}
		}
	});
});

let Actualizar=()=>{
	if(actualizar){
		crearGrafica();
		actualizar = false;
	}
	if(mapaMarkers){
		Swal.fire('Mensaje','No se encontraron estaciones','warning');
		mapaMarkers = false;
	}
}
let graficar=(id,titulo,subtitulo,titulo_eje_y,nombre,datos,categorias,colorGraf)=>{
	Highcharts.chart(id, {
	    chart: {
	        type: 'area',
	        borderWidth:1,
	    },
	    title: {
	        text: titulo
	    },
	    subtitle: {
	        text: subtitulo
	    },
	    xAxis: {
	        allowDecimals: false,
	        labels: {
	            formatter: function () {
	                return this.value; // clean, unformatted number for year
	            }
	        },
	        // INFO DE LA DATA A MOSTRAR (EN QUE SE MIDE)
	        categories: categorias
	    },
	    yAxis: {
	    	// CAMBIAR EL TEXTO TITULO DEL EJE Y
	        title: {
	            text: titulo_eje_y
	        },
	        plotLines: [{
		        color: 'red',
		        value: calcuPromedio(datos), //Insert your average here
		        width: '1',
		        zIndex: 999
		    }]
	    },
	    credits: {
	        enabled: false
	    },
	    tooltip: {
	        pointFormat: '<b>{series.name}</b> <br/>'+titulo_eje_y+': <b>{point.y:,.0f}</b><br/> registro: <b>#{point.x}</b>'
	    },
	    series: [{
	        name: nombre,
	        data: datos,
	    }]
	});
}
let calcuPromedio=(vector)=>{
	let suma = 0;
	let total = vector.length;
	for(let i=0;i<total;i++){
		suma += parseFloat(vector[i]);
	}
	return suma/total;
}
let crearGrafica=()=>{
	fechaIni = $("#fechaIni").val();
	fechaFin = $("#fechaFin").val();
	$.ajax({
		beforeSend: function(){
			$("#cargando").show();
			$('body').css({'overflow':'hidden'});
		},
		url: '../../../registros',
		type: 'post',
		dataType: "json",
		data:{
			_token: $("input[type='hidden']").val(),
			id : correlativo,
			fechaIni: fechaIni,
			fechaFin: fechaFin
		}
	})
	.done(function(data) {		
		$('body').css({'overflow':'auto'});
		$("#cargando").hide();
		//console.log(data[0]);
		try {
			
			if(data[0]['registros'].length>0){

				var categoria = new Array();
				var matriz=new Array();
				var matriz2=new Array();
				var matriz3=new Array();
				var subtitulo = '';
				
				$("table tbody tr").remove();
				for (var i = 0; i < data[0]['registros'].length; i++) {
					categoria.push(data[0]['registros'][i]['fecha_hora']);
					matriz.push(parseFloat(data[0]['registros'][i]['pm25']));
					matriz2.push(parseFloat(data[0]['registros'][i]['pm10']));
					matriz3.push(parseFloat(data[0]['registros'][i]['temp']));
					$("#data tbody").append("<tr>"+
						"<td>"+(i+1)+"</td>"+
						"<td>"+data[0]['registros'][i]['fecha_hora']+"</td>"+
						"<td>"+data[0]['registros'][i]['pm25']+"</td>"+
						"<td>"+data[0]['registros'][i]['pm10']+"</td>"+
						"<td>"+data[0]['registros'][i]['temp']+"</td>"+
						"</tr>");
				}
				$("#dataUltimo tbody").append("<tr>"+
						"<td>"+data[0]['registros'][data[0]['registros'].length-1]['fecha_hora']+"</td>"+
						"<td>"+data[0]['registros'][data[0]['registros'].length-1]['pm25']+"</td>"+
						"<td>"+data[0]['registros'][data[0]['registros'].length-1]['pm10']+"</td>"+
						"<td>"+data[0]['registros'][data[0]['registros'].length-1]['temp']+"</td>"+
						"<td style='background-color:"+UCCAPM2_5(data[0]['registros'][data[0]['registros'].length-1]['pm25'])+";'></td>"+
						"</tr>");

				$("#departamento").text(data[0]['departamento']);
				$("#municipio").text(data[0]['municipio']);
				$("#direccion").text(data[0]['direccion']);
				$("#img_mostrar").attr('src','../../../storage/'+data[0]['txt_img']);
				$("#img_mostrar").removeClass('d-none');
				
				if(data[0]['correlativo'] ==' '){
					$("#tituloDePg").text('Datos promedio a nivel nacional');
					$("#totalEstaciones").text(data[0]['municipio']);
					$("#txtDatosNacionales").removeClass('d-none');
					$("#datosPorEstacion").addClass('d-none');
					var fechaI = data[0]['registros'][0]['fecha_hora'].substring(0, 10);
					var fechaF = data[0]['registros'][ data[0]['registros'].length-1 ]['fecha_hora'].substring(0, 10);
					graficar("grafico1","Gráfica de material particulado PM 2.5 ug/m3","del "+fechaI+" al "+fechaF,"PM 2.5 ug/m3","ESTACIONES EN EL SALVADOR",matriz,categoria,'#33D3E9');
					graficar("grafico2","Gráfica de material particulado PM 10 ug/m3","del "+fechaI+" al "+fechaF,"PM 10 ug/m3","ESTACIONES EN EL SALVADOR ",matriz2,categoria,'#3647EC');
					graficar("grafico3","Gráfica de material particulado TEMPERATURA ºC","del "+fechaI+" al "+fechaF,"Temp ºC","ESTACIONES EN EL SALVADOR ",matriz3,categoria,'#5D36EC');

				}else{
					$("#tituloDePg").text('Datos del departamento "'+data[0]['departamento']+'", municipio "'+data[0]['municipio']+'"');
					$("#txtDatosNacionales").addClass('d-none');
					$("#datosPorEstacion").removeClass('d-none');
					var fechaI = data[0]['registros'][0]['fecha_hora'].substring(0, 10);
					var fechaF = data[0]['registros'][ data[0]['registros'].length-1 ]['fecha_hora'].substring(0, 10);
					graficar("grafico1","Gráfica de material particulado PM 2.5 ug/m3","del "+fechaI+" al "+fechaF,"PM 2.5 ug/m3","Estación "+data[0]['correlativo']+' '+data[0]['municipio']+' '+ data[0]['direccion'],matriz,categoria,'#33D3E9');
					graficar("grafico2","Gráfica de material particulado PM 10 ug/m3","del "+fechaI+" al "+fechaF,"PM 10 ug/m3","Estación "+data[0]['correlativo']+' '+data[0]['municipio']+' '+ data[0]['direccion'],matriz2,categoria,'#3647EC');
					graficar("grafico3","Gráfica de material particulado TEMPERATURA ºC","del "+fechaI+" al "+fechaF,"Temp ºC","Estación "+data[0]['correlativo']+' '+data[0]['municipio']+' '+ data[0]['direccion'],matriz3,categoria,'#5D36EC');
					
				}

			}else{
				Swal.fire('Mensaje','No se encontraron datos en la estación','warning');
			}
		} catch(e) {
			Swal.fire('Mensaje','No se encontraron datos','warning');
		}
	})
	.fail(function() {
		$('body').css({'overflow':'auto'});
		$("#cargando").hide();
		Swal.fire('Mensaje','error al cargar los datos','warning');
	});
	
}

let UCCAPM2_5=(valor)=>{
	if(parseFloat(valor)>=0 && parseFloat(valor)<=50){
		return "#66C77F";
	}
	else if(parseFloat(valor)>=51 && parseFloat(valor)<=100){
		return "#ECFC24";
	}
	else if(parseFloat(valor)>=101 && parseFloat(valor)<=150){
		return "#F6C12A";
	}
	else if(parseFloat(valor)>=151 && parseFloat(valor)<=200){
		return "#FF1717";
	}
	else if(parseFloat(valor)>=201 && parseFloat(valor)<=300){
		return "#AC02E7";
	}
	else if(parseFloat(valor)>=300 && parseFloat(valor)<=500){
		return "#040405";
	}else{
		return "#66C77F";
	}
}

$(".proyecciones").on("click", function(){

    var ini = $("#fechaIni").val();
    var fin = $("#fechaFin").val();
    var id = correlativo;
    if(ini =="" || fin == "" || id==""){
	$.ajax({
		url: 'id',
		type: 'get',
		dataType: "json"
	})
	.done(function(data) {		
		console.log(data);
		var today = new Date();
		var dd = String(today.getDate()).padStart(2, '0');
		var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
		var yyyy = today.getFullYear();
		ini = 2019 + '-' + 11 + '-' + (dd-1);
		fin = yyyy + '-' + mm + '-' + dd;
		for(let i=0; i<data.length; i++){
			console.log(data[0]['_id']['$oid']);
			var txt = '{"id":'+data[i]['_id']['$oid']+', "fechaIni":'+ini+', "fechaFin":'+fin+'}';
			window.open("csv/"+data[i]['_id']['$oid']+'/'+ini + "/"+fin,"_self");	
		}
	})
	.fail(function() {
		Swal.fire('Mensaje','error al cargar los datos','warning');
	});
    }else{
    	var txt = '{"id":'+id+', "fechaIni":'+ini+', "fechaFin":'+fin+'}';
    var obj = jQuery.parseJSON(txt);
	console.log(txt);
    	window.open("proyecciones/"+ini + "/"+fin,"_self");
    }
});
		
