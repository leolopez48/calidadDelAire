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
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center py-3">
                    <h1>Calidad del aire</h1>
                </div>
                <div class="col-md-8 py-3">
                    <h4>¿Sabias qué?</h4>
                    <p>
                        - En 2016, el 91% de la población vivía en lugares donde no se respetaban las Directrices de la OMS sobre la calidad del aire.
                        <br>
                        - Cuanto más bajos sean los niveles de contaminación del aire mejor será la salud cardiovascular y respiratoria de la población, tanto a largo como a corto plazo. 
                        <br>
                        - Un 91% de esas defunciones prematuras se producen en países de bajos y medianos ingresos, y las mayores tasas de morbilidad se registran en las regiones de Asia Sudoriental y el Pacífico Occidental de la OMS.
                    </p>
                </div>
                <div class="col-md-4 py-3">
                    <div class="text-center">
                        <img src="{{ asset('img/contaminacion.jpg')}}" style="width: 100%; height: auto">
                    </div>
                </div>
                <div class="col-md-12 py-4">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ICCA</th>
                                <th>Interpretación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Buena</td>
                                <td>Sin impactos sobre la salud.</td>
                            </tr>
                            <tr>
                                <td>Satisfactorio</td>
                                <td>Sin impactos sobre la salud.</td>
                            </tr>
                            <tr>
                                <td>No satisfactorio</td>
                                <td>Dañino para niños y personas con enfermedades respiratorias, tales como Asma.</td>
                            </tr>
                            <tr>
                                <td>No saludable</td>
                                <td>Personas con enfermedades respiratorias, niños y ancianos, deben evitar estar al aire libre.</td>
                            </tr>
                            <tr>
                                <td>Dañino para la salud</td>
                                <td>Personas con enfermedades respiratorias, niños y ancianos, deben evitar estar al aire libre.</td>
                            </tr>
                            <tr>
                                <td>Peligroso</td>
                                <td>Cualquier persona debe evitar estar al aire libre, sobretodo personas con problemas respiratorios, ancianos y niños deben quedarse en casa.</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-12 py-4">
                            <h4>Partículas (PM)</h4>
                            <h5>Definición y fuentes principales</h5>
                        </div>
                        <div class="col-md-5">
                            <div class="text-center">
                                <img src="{{ asset('img/contaminacion2.jpg')}}" style="width: 95%; height: auto">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <p> Las PM son un indicador representativo común de la contaminación del aire. Afectan a más personas que cualquier otro contaminante. Los principales componentes de las PM son los sulfatos, los nitratos, el amoníaco, el cloruro de sodio, el hollín, los polvos minerales y el agua. Consisten en una compleja mezcla de partículas sólidas y líquidas de sustancias orgánicas e inorgánicas suspendidas en el aire. Si bien las partículas con un diámetro de 10 micrones o menos (≤ PM10) pueden penetrar y alojarse profundamente dentro de los pulmones, existen otras partículas aún más dañinas para la salud, que son aquellas con un diámetro de 2,5 micrones o menos (≤ PM2.5). Las PM2.5 pueden atravesar la barrera pulmonar y entrar en el sistema sanguíneo La exposición crónica a partículas contribuye al riesgo de desarrollar enfermedades cardiovasculares y respiratorias, así como cáncer de pulmón.</p>
                        </div>
                        <div class="col-md-12 py-2">
                            <p>
                                Generalmente, las mediciones de la calidad del aire se notifican como concentraciones medias diarias o anuales de partículas PM10 por metro cúbico (m3) de aire. Las mediciones sistemáticas de la calidad del aire describen esas concentraciones de PM expresadas en microgramos (μ)/m3. Cuando se dispone de instrumentos de medición suficientemente sensibles, se notifican también las concentraciones de partículas finas (PM2,5 o más pequeñas).
                            </p>
                        </div>
                    </div>
                    <div>
                        <h1>Más información</h1>
                    </div>
                    <div class="container" style="display: inline-flex">
                        <p>¿Deseas saber cómo está la calidad del aire en nuestro país? <a href="{{ url('/') }}"> ¡Entra aquí!</a></p>
                    </div>
                </div>
            </div>
        </div>
        @endsection
    </body>
</html>
