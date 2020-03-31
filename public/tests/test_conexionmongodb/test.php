<?php

//include "../vendor/mongodb/mongodb/src/Client.php";
//include "../vendor/mongodb/mongodb/src/Database.php";
//include "../vendor/mongodb/mongodb/src/Collection.php";
//include "../vendor/mongodb/mongodb/src/functions.php";
//include "../vendor/mongodb/mongodb/src/Operation/Executable.php";
//include "../vendor/mongodb/mongodb/src/Operation/Explainable.php";
//include "../vendor/mongodb/mongodb/src/Operation/Find.php";
//include "../vendor/mongodb/mongodb/src/Operation/FindOne.php";
//include "../vendor/mongodb/mongodb/src/Operation/InsertOne.php";
//include "../vendor/mongodb/mongodb/src/Operation/ListDatabases.php";
//include "../vendor/mongodb/mongodb/src/InsertOneResult.php";
//include "../vendor/mongodb/mongodb/src/Model/BSONArray.php";
//include "../vendor/mongodb/mongodb/src/Model/BSONDocument.php";
require_once "../modelo/SessionManager.php";
require_once '../modelo/Estacion.php';
require_once '../modelo/RegistroEstacion.php';
require_once '../vendor/mongodb/mongodb/src/Operation/Delete.php';
require_once '../vendor/mongodb/mongodb/src/Operation/DeleteMany.php';
require_once '../vendor/mongodb/mongodb/src/DeleteResult.php';


//include '../clases/SessionManager.php';

$estacion = new Estacion();

foreach ($estacion->consultar([]) as $regEst){
    /*$salida;
    $salida = $est->getObjectID().", ".$est->getCorrelativo()."<br>";
    
    foreach ($est->getRegistros(NULL, NULL) as $regEst){
        $salida = $salida."regs: ".$regEst->getFecha_hora().", ".$regEst->getPm25().", ".$regEst->getPm10().", ".$regEst->getTemp()."<br><br>";
        echo $salida;
    }*/
    
    $query;
    $conexion = Conexion::getInstance();
        
    $query = ["expired_at" => [ '$lt' => time()]];

    try {
        printf("Deleted %d document(s)\n", $conexion->getCollection("sesiones")->deleteMany($query)->getDeletedCount());
    } catch (Exception $exc) {
        echo $exc->getTraceAsString();
    }
}
?>