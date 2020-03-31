@extends('layouts.app')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Calidad del aire</title>

        <!-- Styles -->
        <style type="text/css">
        	#logo{
        			max-width: 300px; height: auto;
        	}

            #logo1{
                    max-width: 200px; height: auto;
            }

        	@media only screen and (max-width: 600px) {
			    #logo{
        			max-width: 40%; height: auto;
        		}

                #logo1{
                    max-width: 40%; height: auto;
                }
			} 
        </style>
    </head>
    <body>
        @section('content')
        <div class="container">
			<div class="row">
				<div class="col-md-12 col-xs-8 col-lg-12">
					<div class="text-center">
						<img src="{{ asset('img/logoitca.png') }}" id="logo">
                        <img src="{{ asset('img/logotecno.png') }}" id="logo1">
						<h3>Proyecto de investigación</h3>
						<h4>ITCA-FEPADE | UTEC</h4>
						<h5>Coordinadores de la investigación </h5>
						<p>Ing. Elvis Moisés Martínez Pérez (Investigador principal)</p>
						<p>Ing. Rina Elizabeth López (Co-Investigadora)</p>
                        <p>Ing. Ronny Cortez (Investigador principal)</p>
                        <p>Ing. Omar Otoniel Flores (Co-Investigador)</p>
						<h5>Equipo de investigación ITCA-FEPADE</h5>
						<p>Leonel Antonio López Valencia</p>
						<p>Manuel Alexander Delgado Henrriquez</p>
						<p>Jorge Daniel Ángel Fernandez</p>
					</div>
					
				</div>
			</div>
        </div>
        @endsection
    </body>
</html>
