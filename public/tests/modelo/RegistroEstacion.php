<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../conexion/Conexion.php';
require_once '../modelo/Estacion.php';
require_once "../vendor/mongodb/mongodb/src/UpdateResult.php";
require_once "../vendor/mongodb/mongodb/src/Operation/Update.php";
require_once "../vendor/mongodb/mongodb/src/Operation/UpdateOne.php";
//require_once "../vendor/mongodb/mongodb/src/Model/BSONDocument.php";

/**
 * Clase entidad que es la abstracción de un Registro de Estación
 *
 * @author Daniel Ángel <jorge.angel16@itca.edu.sv>
 */
class RegistroEstacion implements DBCRUDOperations{
    private $fecha_hora;
    private $pm25;
    private $pm10;
    private $temp;
    
    private $conexion;
    private $estacionPertenece;
    
    // <editor-fold defaultstate="collapsed" desc="propiedades">
    public function getFecha_hora() {
        return $this->fecha_hora;
    }

    public function getPm25() {
        return $this->pm25;
    }

    public function getPm10() {
        return $this->pm10;
    }

    public function getTemp() {
        return $this->temp;
    }

    public function setFecha_hora($fecha_hora) {
        $this->fecha_hora = $fecha_hora;
    }

    public function setPm25($pm25) {
        $this->pm25 = $pm25;
    }

    public function setPm10($pm10) {
        $this->pm10 = $pm10;
    }

    public function setTemp($temp) {
        $this->temp = $temp;
    }
    
    public function getEstacionPertenece() {
        return $this->estacionPertenece;
    }
    
    private function setEstacionPertenece($estacionPertenece){
        $this->estacionPertenece = $estacionPertenece;
    }
// </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="constructores">
    public function __construct($estacionPertenece) {
        $this->setEstacionPertenece($estacionPertenece);
        $this->conexion = Conexion::getInstance();
    }

    
// </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="operaciones CRUD">
    public function consultar($filtros) {
        $arreglo;
        $salida;
        
        $arreglo = $this->conexion->getCollection(Estacion::COLLECTIONNAME)->find($filtros)->toArray();   
        
        $salida = array();
        foreach($arreglo as $arr){   
            foreach ($arr["registros"] as $regi){
                $registroEstacion = new RegistroEstacion($this->getEstacionPertenece());
                
                $registroEstacion->setFecha_hora($regi["fecha_hora"]);
                $registroEstacion->setPm25($regi["pm25"]);
                $registroEstacion->setPm10($regi["pm10"]);
                $registroEstacion->setTemp($regi["temp"]); 
                
                $salida[] = $registroEstacion;
            }
        }
        
        return $salida;
    }
    
    public function eliminar() {
        throw new Exception("Aún no implementado");
    }

    public function insertar() {
        $filtros;
        $update;
        $options;
        
        $filtros = ["correlativo" => $this->estacionPertenece->getCorrelativo()];
        //$push adjunta un nuevo registro a un arreglo
        $update = ['$push' => ["registros" => [
                "fecha_hora" => $this->getFecha_hora()
                ,"pm25" => $this->getPm25()
                ,"pm10" => $this->getPm10()
                ,"temp" => $this->getTemp()
                ]]];
        $options = ["upsert" => true];
// print_r($update);
// print_r($filtros);
        $this->conexion->getCollection(Estacion::COLLECTIONNAME)->updateOne($filtros, $update, $options);
    }

    public function modificar() {
        throw new Exception("Aún no implementado");
    }
// </editor-fold>
}
