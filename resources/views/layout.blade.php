<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Calidad del aire</title>
            <link rel="stylesheet" type="text/css" href="{{ asset('css/estiloMapa.css') }}">
            <script type="text/javascript" src="{{asset('js/librerias/jquery-3.3.1.min.js')}}"></script>
            <script type="text/javascript" src="{{asset('js/librerias/sweetalert2.js')}}"></script> 
            <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap/bootstrap.css')}}">
        <!-- Fonts -->

        <!-- Styles -->
        <style>
        </style>
        
    </head>
    <body>
        <div align="center" class="navbar navbar-dark bg-dark">
            <a style="padding-left: 45%; padding-top: 0.5%; color: #FFF" href="{{ url('/')}}"><ul class="view overlay text-center" style=" color: #FFF">Calidad del aire</ul></a>
        </div>
        @yield('content')
        
        <div align="center" class="card-footer" style="height: 135px;">
            <ul>Copyright &#169 2019 Ministerio de Medio Ambiente. Todos los derechos reservados.</ul>
        </div>
    </body>
</html> 