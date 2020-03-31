<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require_once "../vendor/mongodb/mongodb/src/Client.php";
require_once "../vendor/mongodb/mongodb/src/Database.php";
require_once "../vendor/mongodb/mongodb/src/Collection.php";
require_once "../vendor/mongodb/mongodb/src/functions.php";
require_once "../vendor/mongodb/mongodb/src/Model/BSONArray.php";
require_once "../vendor/mongodb/mongodb/src/Model/BSONDocument.php";
require_once "../vendor/mongodb/mongodb/src/Operation/Executable.php";
require_once "../vendor/mongodb/mongodb/src/Operation/Explainable.php";
require_once "../vendor/mongodb/mongodb/src/Operation/Find.php";
require_once "../vendor/mongodb/mongodb/src/Operation/FindOne.php";
require_once "../vendor/mongodb/mongodb/src/Exception/Exception.php";
require_once "../vendor/mongodb/mongodb/src/Exception/InvalidArgumentException.php";

use MongoDB\Client;
/**
 * Nombre: Conexion
 * Version: 1.0
 * 
 * Description of Conexion
 * Está clase provee una forma rapida, simple y sumamente básica de crear y
 * utilizar un cliente autenticado hacia una unica base de mongoDb, todas las opciones con las que los objetos
 * pretenecientes al namespace "MongoDB" son construidas con sus opciones por
 * defecto.
 * 
 * Si se necesita especificar otras opciones, se recomienda extender esta clase
 * o crear otra
 * 
 * Copyrights: ITCA-FEPADE
 * @author Daniel Ángel <jorge.angel16@itca.edu.sv>
 */
class Conexion {
    public const HOST = "localhost";
    public const PUERTO = 27017;
    public const NOMBRE_BD = "calidadAire";
    public const NOMBRE_USUARIO = "root";
    private const CONTRASENNA_USUARIO = "";
    
    private static $instance;
    private $cliente;
    private $baseDatos;
    
    // <editor-fold defaultstate="collapsed" desc="propiedades">
    function getCliente() {
        return $this->cliente;
    }

    function getBaseDatos() {
        if(!isset($this->base)){
            try{
                $this->baseDatos = $this->cliente->selectDataBase(self::NOMBRE_BD);
                $this->baseDatos->getDatabaseName();
            } catch (Exception $e){
                throw $e;
            }
        }
        return $this->baseDatos;
    }
    
     //devuelve el MongoDB/Driver/Manager del cliente
    public function getManager(){
        return self::$instance->getManager();
    }
    
    //devuelve la coleccion con el nombre especificado de la base de datos
    //establecida en esta instancia
    public function getCollection($nombre){
        try{
            return $this->getBaseDatos()->selectCollection($nombre);
        } catch (Exception $e) {
            throw $e;
        } 
    }
// </editor-fold>
        
    // <editor-fold defaultstate="collapsed" desc="constructores">
    private function __construct() {
        $stringConexion = sprintf("mongodb://%s:%s@%s:%d", Conexion::NOMBRE_USUARIO
                , Conexion::CONTRASENNA_USUARIO, Conexion::HOST, Conexion::PUERTO);
        try {
            $this->cliente = new Client();
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    //"constructor singleton"
    static function getInstance() {
        if(!isset(self::$instance)){
            self::$instance = new Conexion();
        }
        return self::$instance;
    }
// </editor-fold>
}
?>
