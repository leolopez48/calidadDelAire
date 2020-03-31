@extends('layouts.app')
<!DOCTYPE html>
<html lang="en">
	<body>
<html>
<head>
	<meta charset="utf-8">
	<title>Calidad del aire</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/estiloMapa.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap/bootstrap.css') }}">
	<!-- Styles -->
        <style>
            table{
                width: 75%;
                border-collapse: collapse;
            }

            td, tr, th{
                border: 1px solid black;
                width: 10%;
                text-align: center;
            }

            .div_tabla {
                max-height: 500px;
                overflow-x: auto;

            }
            .imagen{
                position: absolute;
            }

            @media only screen and (max-width: 768px){
                .iframe{
                    height: 250px;
                    width: 100%;
                }

                #dataUltimo{
                    width: 85%;
                }

                #data{
                    width: 98%;
                }
            }
            @media only screen and (min-width: 768px){
                .iframe{
                    height: 500px;
                    width: 100%;
                }

                #dataUltimo{
                    width: 98%;
                }

                #data{
                    width: 98%;
                }
            }
        </style>

</head>
<body>
    <div id="cargando" style="position: fixed;width: 100%;height: 100%;display: flex;justify-content: center;align-items: center; z-index: 999;" class="text-center">
        <h3 class="d-inline-block text-danger font-weight-bold" style="margin-right: -70px;z-index: 11;">Cargando</h3>
        <img src="{{ asset('img/cargando.gif') }}" style="width: 200px;height: 200px;z-index: 11;">
        <div class="bg-light" style="z-index:10;position: absolute;width: 100%;height: 100%;opacity: 0.7;"></div>
    </div>
      <form>
      	{{ csrf_field()}} 
      </form> 
      <!-- -------------------------------------- -->
      <!-- --------------------------------------->
@section('content')
<div align="center">
    <div class="container-fluid pt-1">
        <!-- -------------------titulo------------------------------>
        <div class="row">
            <div class="col-md-12 h3 font-weight-bold text-center" id="tituloDePg">
                Datos promedio a nivel nacional
            </div>
        </div>
        <!-- -------------------titulo------------------------------>
        <!-- -------------------filtros----------------------------->
        <div class="container">
            <div class="row">
            <div class="col-md-12 h5 text-left py-2"> 
                Periodo a mostrar de material particulado:
            </div>
            <div class="form-group col-md-2 col-sm-12">
                <label>Fecha inicio:</label>
                <input id="fechaIni" type="date" class="form-control">
            </div>
            <div class="form-group col-md-2 col-sm-12">
                <label>Fecha fin:</label>
                <input id="fechaFin" type="date" class="form-control">
            </div>
            <div class="form-group col-md-8 pt-4 col-sm-12">
                <button id="limpiar" type="button" class="btn btn-success">Limpiar</button>
                <button id="filtrar" type="button" class="btn btn-success">Filtrar</button>
                <button id="datosNacionales" type="button" class="btn btn-primary">Datos nacionales</button>
                <a id="excel" type="button" class="btn btn-success" href="{{ url('crearExcel') }}">Proyecciones</a>
                <!--<a href="#"><button class="btn btn-primary proyecciones">Proyecciones</button></a>-->
            </div>
        </div>
        </div>
        <!-- -------------------fin filtros------------------------->
            <!-- contenedor del mapa -->
        <div class="container">
            <div class="row">
            <div class="col-12">
                <br>
                    <iframe class="iframe" src="{{ url('mapas') }}" id="iframe" frameborder="0px">  
                </iframe>

            </div>
        </div>
        </div>
        <ul></ul>
    <!-- fin contenedor del mapa -->
        <div class="row">
            <!-- contenedor de los graficos -->
            <div class="col-md-7">
                <div id="grafico1" class="my-1" style="min-width: 310px; max-height: 400px; margin: 0 auto">
                </div>
                <div id="grafico2" class="my-2" style="min-width: 310px; max-height: 400px; margin: 0 auto">
                </div>
                <div id="grafico3" class="my-1" style="min-width: 310px; max-height: 400px; margin: 0 auto">
                </div>
            </div>
            <!--fin contenedor de los graficos -->
            <!-- contenedor de la tabla -->
            <div class="col-md-5">
                <div align="center">
                	<div>
                		<div class=" h3 font-weight-bold text-center">Datos Generales</div>
                		<img id="img_mostrar" class="d-none" src="" style="width: 100%;max-height: 200px;">
                		
                        <div id="datosPorEstacion">
                            <div align="left">
                                <label class="h6 font-weight-bold">Departamento:</label>
                                <label for="" id="departamento" class=""></label>
                            </div>
                            <div align="left">
                                <label class="h6 font-weight-bold">Municipio:</label>
                                <label for="" id="municipio" class=""></label>
                            </div>
                            <div align="left">
                                <label class="h6 font-weight-bold">Dirección:</label>
                                <label for="" id="direccion" class=""></label>
                            </div>
                        </div>
                        <div id="txtDatosNacionales">
                            <div align="left">
                                <label class="h6 font-weight-bold">País:</label>
                                <label for="" class="">El Salvador</label>
                            </div>
                            <div align="left">
                                <label class="h6 font-weight-bold">Total Estaciones:</label>
                                <label for="" id="totalEstaciones" class=""></label>
                            </div>
                        </div>
                	</div>
                    <h5 class="font-weight-bold text-center">Índice Centroaméricano de calidad del aire</h5>
                    <img width="100%" class="mb-3" src="{{ asset('img/cuadro.png') }}">
                    <!-- tabla de ultima lectura -->
                        <table class="px-0 mb-3 table table-dark" align="center" id="dataUltimo">
                        <thead>
                            <tr><td colspan="5" class="h6">ULTIMO REGISTRO</td></tr>
                            <tr>
                                <th style="width: 40%">Fecha - Hora </th>
                                <th>pm 2.5 ug/m3</th>
                                <th>pm 10 ug/m3</th>
                                <th>Temp ºC</th>
                                <th>ICCA</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                    <!--fin tabla de ultima lectura -->
                    <!-- tabla de lecturas graficadas -->
                    <div class="div_tabla">
                        <table align="center" id="data" class="table table-dark">
                            <thead>
                                <tr><td colspan="5" class="h5">REGISTROS GRAFICADOS</td></tr>
                                <tr>
                                    <th>#</th>
                                    <th style="width: 40%">Hora - Fecha </th>
                                    <th>PM 2.5 ug/m3</th>
                                    <th>PM 10 ug/m3</th>
                                    <th>Temp ºC</th>
                                </tr>
                            </thead>
                            <tbody>
        
                            </tbody>
                        </table>
                    </div>
                    <!--fin  tabla de lecturas graficadas -->
                    
                </div>
            </div>
            <!--fin contenedor de la tabla -->
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
    <script type="text/javascript" src="{{ asset('js/graficar.js') }}"></script>
        <script type="text/javascript" src=" {{ asset('js/librerias/jquery-3.3.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/librerias/sweetalert2.js') }}"></script> 
@endsection

</body>
</html>	