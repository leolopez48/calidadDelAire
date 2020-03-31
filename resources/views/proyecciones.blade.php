@extends('layouts.app')
<!DOCTYPE html>
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center">Proyecciones</h1>
            </div>
            <h3 class="text-left px-3">Predicciones para la semana actual</h3>
            <div class="row px-3">
                <div class="col-md-6">
                    <img src="{{ asset('/machine/predicciones.png') }}" height="auto" width="100%">
                </div>
                <div class="col-md-6">
                    <h5>Sean los días de la semana de la siguiente manera: 
                    </h5>
                    1- Domingo
                        <br>2- Lunes
                        <br>3- Martes
                        <br>4- Miércoles
                        <br>5- Jueves
                        <br>6- Viernes
                        <br>7- Sabado
                </div>
            </div>
                <div class="row px-3 pt-3">
                    <div class="col-md-4 d-inline">
                    <h3 class="text-left">PM 2.5</h3>
                    <img src="{{ asset('/machine/prom25.png') }}" height="auto" width="100%">
                </div>
                <div class="col-md-4 d-inline pt-5">
                    <img src="{{ asset('/machine/serie25.png') }}" height="auto" width="100%">
                </div>
                <div class="col-md-4 d-inline pt-5">
                    <img src="{{ asset('/machine/arima25.png') }}" height="auto" width="100%">
                </div>
                <div class="col-md-4 d-inline pt-3">
                    <h3 class="text-left">PM 10</h3>
                    <img src="{{ asset('/machine/prom10.png') }}" height="auto" width="100%">
                </div>
                <div class="col-md-4 d-inline pt-5">
                        <img src="{{ asset('/machine/series10.png') }}" height="auto" width="100%">
                </div>
                <div class="col-md-4 d-inline pt-5">
                        <img src="{{ asset('/machine/arima10.png') }}" height="auto" width="100%">
                </div>    
            <div class="col-md-4 pt-3">
                <h3 class="text-left">Temperaturas</h3>
                <img src="{{ asset('/machine/temper.png') }}" height="auto" width="100%">
                
            </div>
            <div class="col-md-4 pt-5">
                <img src="{{ asset('/machine/arimaT.png') }}" height="auto" width="100%">
                
            </div>
            <div class="col-md-4 pt-5">
                <img src="{{ asset('/machine/tempera.png') }}" height="auto" width="100%">
                
            </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('js/librerias/jquery-3.3.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/librerias/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/librerias/bootstrap/bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/mapaIndex.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/librerias/bootstrap/bootstrap.js') }}"></script>

    <script type="text/javascript" src="{{ asset('js/librerias/highcharts/highcharts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/librerias/highcharts/export-data.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/librerias/highcharts/exporting.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/librerias/highcharts/highcharts-3d.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/librerias/sweetalert2.all.min.js') }}"></script>
        <script type="text/javascript" src=" {{ asset('js/librerias/jquery-3.3.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/librerias/sweetalert2.js') }}"></script> 
@endsection