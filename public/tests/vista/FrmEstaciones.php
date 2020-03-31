<?php
    require_once '../modelo/Estacion.php';
    require_once '../modelo/SessionManager.php';
    require_once "../modelo/RegistroEstacion.php";
    
    use \MongoDB\BSON\UTCDateTime;
    
    session_start();  
?>
<table border="5">
    <thead>
        <th>id</th>
        <th>correlativo</th>
        <th>registros</th>
    </thead>
    <tbody>
<?php
        $estacion = new Estacion();
        
        foreach($estacion->consultar([]) as $est){
            echo "<tr><td>".$est->getObjectID()."</td>";
            echo "<td>".$est->getCorrelativo()."</td>";
            foreach($est->getRegistros(NULL, NULL) as $regEst){
                
                echo '<td>'.$regEst->getFecha_hora();
                echo "<br>".$regEst->getPm25();
                echo "<br>".$regEst->getPm10();
                echo "<br>".$regEst->getTemp()."</br>";
            }
        } 
?>
    </tbody>
</table>
<br><br>
<form name="anniadir estación" action="#" method="POST">
    correlativo <input name="correlativo"/>
    direccion <input name="direccion"/>
    departamento <input name="departamento"/>
    municipio <input name="municipio"/>
    posición top <input name="posición_top"/>
    posición left <input name="posición_left"/>
    
    <br>
    <input type="submit" name="enviarEst"/>
    <input type="submit" name="modEst"/>
    <input type="submit" name="elEst" value="eliminar"/>
</form>

<form name="anniadir registro" action="#" method="POST">
    pm25 <input name="pm25"/>
    pm10 <input name="pm10"/>
    temp <input name="temp"/>
    
    <br>
    <input type="submit" name="enviar"/>
</form>



<?php
    //ingresar registroEstación
    if(isset($_POST["enviar"])){
        $est;
        $regEst;
        $dt;
        
        $est = new Estacion();
        $est->setCorrelativo(1);
        $regEst = new RegistroEstacion($est);
        $dt = new DateTime();
        $regEst->setFecha_hora(new UTCDateTime($dt->getTimestamp()));
        $regEst->setPm25($_POST["pm25"]);
        $regEst->setPm10($_POST["pm10"]);
        $regEst->setTemp($_POST["temp"]);
        
        $regEst->insertar();
        
        
    }
    
    //ingresar estación
    if(isset($_POST["enviarEst"])){
        $est;
        
        $est = new Estacion();
        $est->setCorrelativo($_POST["correlativo"]);
        $est->setDireccion($_POST["direccion"]);
        $est->setDepartamento($_POST["departamento"]);
        $est->setMunicipio($_POST["municipio"]);
        $est->setPosision_mapa_top($_POST["posición_top"]);
        $est->setPosicion_mapa_left($_POST["posición_left"]);
        
        $est->insertar();
    }
    
    if(isset($_POST["modEst"])){
        $est;
        
        $est = new Estacion();
        $est->setCorrelativo($_POST["correlativo"]);
        $est->setDireccion($_POST["direccion"]);
        $est->setDepartamento($_POST["departamento"]);
        $est->setMunicipio($_POST["municipio"]);
        $est->setPosision_mapa_top($_POST["posición_top"]);
        $est->setPosicion_mapa_left($_POST["posición_left"]);
        
        $est->modificar([
            '$set' => ["direccion" => $est->getDireccion()
                , "departamento" => $est->getDepartamento()
                , "municipio" => $est->getMunicipio()
                , "posision_mapa_top" => $est->getPosision_mapa_top()
                , "posision_mapa_left" => $est->getPosicion_mapa_left()
            ]
        ]);
    }
    
    if(isset($_POST["elEst"])){
        $est;
        
        $est = new Estacion();
        $est->setCorrelativo($_POST["correlativo"]);
        
        $est->eliminar();
    }
?>

