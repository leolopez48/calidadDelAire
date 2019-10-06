@extends('layouts.app')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Calidad del aire</title>

        <!-- Styles -->
    </head>
    <body>
@section('content')
        <div align="center">
                <div class="container-fluid ">
        <div class="row">
            <div align="center" style="padding-left: 20% ; width: 80%; height: 600px" >
                <br>
                <div class="clearfix"></div>            
                <iframe src="{{ url('crudMarker') }}" frameborder="0" height="500px" width="100%">  
                </iframe>

            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <ul></ul>
    <h1 class="text-center">Registros</h1>
            <div class="container-fluid">
                <!-- Tabla -->
                <iframe src="{{ url('registros') }}" frameborder="0" width="80%" scrolling="no" height="300px">  
                </iframe>
            </div>
            <br>
        </div>
        @endsection
    </body>
</html>
