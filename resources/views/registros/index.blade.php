<!DOCTYPE html>
<html>
<head>
	<title>Registros</title>
</head>
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<body>
<br>
	<br>
		                <table align="center" class=" table table-striped table-dark table-hover" style="width: 100%">
                    <thead>
                        <th style="width: 1%">id</th>
                        <th>Dirección</th>
                        <th>Departamento</th>
                        <th>Municipio</th>
                        <th>Posicion top</th>
                        <th>Posición left</th>
                        <th>Registros</th>
                    </thead>
                    <?php  $no=1;?>
                    @foreach($entradas as $registros)
                    <tr >
                    	
                        <td style="width:3%"> {{ $no }}</td>
                        <td> {{ $registros->direccion }} </td>
                        <td> {{ $registros->departamento }} </td>
                        <td> {{ $registros->municipio }} </td>
                        <td> {{ $registros->posision_mapa_top }} </td>
                        <td> {{ $registros->posision_mapa_left }} </td>
                        <td> {{ $registros->registros }} </td>
                    </tr>
                    <?php  $no++;?>
                    @endforeach
                </table>
</body>
</html>