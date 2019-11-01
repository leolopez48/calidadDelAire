var  correlativo = 1;
$(document).ready(function() {
	crearGrafica();	
});
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
	$.ajax({
		url: '../../../registros',
		type: 'post',
		dataType: "json",
		data:{
			_token: $("input[type='hidden']").val(),
			correlativo : correlativo
		}
	})
	.done(function(data) {
			var categoria = new Array();
			var matriz=new Array();
			var matriz2=new Array();
			var matriz3=new Array();
			var subtitulo = '';
			var datos = JSON.stringify(data);
			// console.log(data[0]['registros'][0]['fecha_hora']);
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

			graficar("grafico1","PM 2.5 ug/m3",subtitulo,"PM 2.5 ug/m3","ESTACION 1",matriz,categoria,'#33D3E9');
			graficar("grafico2","PM 10 ug/m3",subtitulo,"PM 10 ug/m3","ESTACION 1",matriz2,categoria,'#3647EC');
			graficar("grafico3","TEMPERATURA ºC",subtitulo,"Temp ºC","ESTACION 1",matriz3,categoria,'#5D36EC');


	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
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

		
