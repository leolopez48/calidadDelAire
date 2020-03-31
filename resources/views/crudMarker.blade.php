<!DOCTYPE html>
<html lang="en">
	<body>
<html>
<head>
	<title>Superposición</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/estiloMapa.css')}}">

	<script type="text/javascript" src="{{ asset('js/librerias/jquery-3.3.1.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/librerias/sweetalert2.all.min.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/mapaCrudMarker.js')}}"></script>
	<link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap/bootstrap.css')}}">
</head>
<body>
	<div id="cargando1" style="position: fixed;width: 100%;height: 100%;display: flex;justify-content: center;align-items: center; z-index: 999;" class="text-center">
	    <h3 class="d-inline-block text-danger font-weight-bold" style="margin-right: -70px;z-index: 11;">Cargando</h3>
	    <img src="{{ asset('img/cargando.gif') }}" style="width: 200px;height: 200px;z-index: 11;">
	    <div class="bg-light" style="z-index:10;position: absolute;width: 100%;height: 100%;opacity: 0.7;"></div>
	</div>
	<script type="text/javascript">
	    $("#cargando1").hide();
	</script>
	<div class="" id="superposicion">
		<img class="sinArrastrar" src="{{asset('img/elsalvador.png')}}" style="width: 100%;height: 100%;">
	</div>
</body>
</html>	