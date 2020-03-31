<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once "DBCRUDOperations.php";
require_once "../conexion/Conexion.php";
require_once 'RegistroEstacion.php';
require_once "../vendor/mongodb/mongodb/src/InsertOneResult.php";
require_once "../vendor/mongodb/mongodb/src/Operation/InsertOne.php";
require_once "../vendor/mongodb/mongodb/src/Operation/Delete.php";
require_once "../vendor/mongodb/mongodb/src/Operation/DeleteOne.php";

use MongoDB\BSON\Binary;
use MongoDB\Model\BSONArray;

/**
 * Clase entidad que es la asbtracción de una estación
 *
 * @author Daniel Ángel <jorge.angel16@itca.edu.sv>
 */
class Estacion implements DBCRUDOperations{
    private $objectID;
    private $correlativo;
    private $direccion;
    private $departamento;
    private $municipio;
    private $foto;
    private $posision_mapa_top;
    private $posicion_mapa_left;
    private $registros;   
    
    private $conexion;
    public const COLLECTIONNAME = "registros";
    
    // <editor-fold defaultstate="collapsed" desc="propiedades">
    function getObjectID() {
        return $this->objectID;
    }

    function getCorrelativo() {
        return $this->correlativo;
    }

    function getRegistros($desde, $hasta) {
        $salida;
        $filtros;
        
        if(!isset($this->registros)){
            $this->registros = new RegistroEstacion($this);
        }
        
        if(!isset($desde) || isset($hasta)){
            $filtros = ['correlativo' => $this->getCorrelativo()];
        }else{
            $filtros = ['correlativo' => $this->getCorrelativo()
                ,'fecha_hora' => [
                    '$gte' => $desde,
                    '$lte' => $hasta
                ]
            ];
        }
        
        $salida = $this->registros->consultar($filtros);
        
        return $salida;
    }

    function setObjectID($objectID) {
        $this->objectID = $objectID;
    }

    function setCorrelativo($correlativo) {
        $this->correlativo = (int) $correlativo;
    }
    function getDepartamento() {
        return $this->departamento;
    }

    function getMunicipio() {
        return $this->municipio;
    }

    function getFoto() {
        return $this->foto;
    }

    function getPosision_mapa_top() {
        return $this->posision_mapa_top;
    }

    function getPosicion_mapa_left() {
        return $this->posicion_mapa_left;
    }

    function setDepartamento($departamento) {
        $this->departamento = $departamento;
    }

    function setMunicipio($municipio) {
        $this->municipio = $municipio;
    }

    function setFoto($foto) {
        $this->foto = $foto;
    }

    function setPosision_mapa_top($posision_mapa_top) {
        $this->posision_mapa_top = $posision_mapa_top;
    }

    function setPosicion_mapa_left($posicion_mapa_left) {
        $this->posicion_mapa_left = $posicion_mapa_left;
    }
    
    function getDireccion() {
        return $this->direccion;
    }

    function setDireccion($direccion) {
        $this->direccion = $direccion;
    }


// </editor-fold> 

    // <editor-fold defaultstate="collapsed" desc="funciones crud">
    public function __construct(){
        $this->conexion = Conexion::getInstance();
    }

    public function consultar($filtros) {
        $arreglo;
        $salida;
        $opciones;
        
        $opciones = ['projection' => ['_id' => 1, 'correlativo' => 1, 'direccion' => 1
            , 'departamento' => 1, 'municipio' => 1, 'posision_mapa_top' => 1, 'posision_mapa_left' => 1]];
        $arreglo = $this->conexion->getCollection(self::COLLECTIONNAME)->find($filtros, $opciones)->toArray();  
        
        /*$estaciones = loqueseaqueusesparaconectaryconsultaralabase->find([], ['projection' => ['_id' => 1, 'correlativo' => 1, 'direccion' => 1
            , 'departamento' => 1, 'municipio' => 1, 'posision_mapa_top' => 1, 'posision_mapa_left' => 1]])*/
        
        $salida = array();
        for ($i = 0; $i < sizeof($arreglo); $i ++){
            $estacion = new Estacion();
            
            $estacion->setObjectID($arreglo[$i]["_id"]);
            $estacion->setCorrelativo($arreglo[$i]["correlativo"]);
            $estacion->setDireccion($arreglo[$i]["direccion"]);
            $estacion->setDepartamento($arreglo[$i]["departamento"]);
            $estacion->setMunicipio($arreglo[$i]["municipio"]);
            $estacion->setPosision_mapa_top($arreglo[$i]["posision_mapa_top"]);
            $estacion->setPosicion_mapa_left($arreglo[$i]["posision_mapa_left"]);
            
            array_push($salida, $estacion);
        }
        
        return $salida;
    }

    public function eliminar() {
        $filtros;
        
        $filtros = [
            "correlativo" => $this->getCorrelativo()
        ];
        
        $this->conexion->getCollection(self::COLLECTIONNAME)->deleteOne($filtros);
    }

    /**
     * Inserta esta instacia de estación a la colección como ducumento.
     * Actualmente no corrobora si existe otra estación con el mismo
     * correlativo por lo que pone en peligro la atomicidad de los documentos
     * usesé con precaución
     */
    public function insertar() {
        $document;
        $foto;
        $posision_mapa_top;
        $posision_mapa_left;
        
        $foto = $this->getFoto() != NULL ? $this->getFoto() : new Binary("", Binary::TYPE_GENERIC);
        $posision_mapa_left = $this->getPosicion_mapa_left() != NULL ? $this->getPosicion_mapa_left() : 0;
        $posision_mapa_top = $this->getPosision_mapa_top() != NULL ? $this->getPosision_mapa_top() : 0;
        
        $document = [
            "correlativo" => $this->getCorrelativo(),
            "direccion" => $this->getDireccion(),
            "departamento" => $this->getDepartamento(),
            "municipio" => $this->getMunicipio(),
            "foto" => $foto,
            "posision_mapa_top" => $posision_mapa_top,
            "posision_mapa_left" => $posision_mapa_left,
            "registros" => new BSONArray()
        ];
        $this->conexion->getCollection(self::COLLECTIONNAME)->insertOne($document);
    }

    public function modificar() {
        $filtros;
        $documento;
        $funcArgs;
        $opciones;
        
        $funcArgs = func_get_args();
        
        if(isset($funcArgs[0])){
            $documento = $funcArgs[0];
        }else{
            $documento = [
                '$set' => [
                    "direccion" => $this->getDireccion(),
                    "departamento" => $this->getDepartamento(),
                    "municipio" => $this->getMunicipio(),
                    "foto" => $this->getfoto(),
                    "posision_mapa_top" => $this->getPosision_mapa_top(),
                    "posision_mapa_left" => $this->getPosicion_mapa_left()
                ]
            ];
        }
        
        $filtros = [
            "correlativo" => $this->getCorrelativo()
        ];
        
        $opciones = [
            "upsert" => false
        ];
        
        $this->conexion->getCollection(self::COLLECTIONNAME)->UpdateOne($filtros, $documento, $opciones);
    }
    
    //funciones para manejar registros
    
// </editor-fold>

    
}
