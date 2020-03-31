<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once "../conexion/Conexion.php";
require_once "../vendor/mongodb/mongodb/src/Database.php";
require_once "../vendor/mongodb/mongodb/src/Collection.php";
require_once "../vendor/mongodb/mongodb/src/functions.php";
require_once "../vendor/mongodb/mongodb/src/Operation/Executable.php";
require_once "../vendor/mongodb/mongodb/src/Operation/Explainable.php";
require_once "../vendor/mongodb/mongodb/src/UpdateResult.php";
require_once "../vendor/mongodb/mongodb/src/Operation/Update.php";
require_once "../vendor/mongodb/mongodb/src/Operation/UpdateOne.php";
require_once "../vendor/mongodb/mongodb/src/Operation/Find.php";
require_once "../vendor/mongodb/mongodb/src/Operation/FindOne.php";
require_once "../vendor/mongodb/mongodb/src/Model/BSONArray.php";
require_once "../vendor/mongodb/mongodb/src/Model/BSONDocument.php";
require_once "../vendor/mongodb/mongodb/src/Exception/Exception.php";
require_once "../vendor/mongodb/mongodb/src/Exception/InvalidArgumentException.php";
require_once '../vendor/mongodb/mongodb/src/Operation/Delete.php';
require_once '../vendor/mongodb/mongodb/src/Operation/DeleteMany.php';
require_once '../vendor/mongodb/mongodb/src/DeleteResult.php';


/**
 * Description of SessionManager
 *
 * @author Daniel Ángel <jorge.angel16@itca.edu.sv>
 */
class SessionManager implements SessionHandlerInterface {
    //nombre de la colección en MongoDb que tendrá los datos de las sesiones
    private const COLECCION = "sesiones";
    
    //expira depues de 10 minutos de anactividad
    private const SESION_TIMEOUT = 600;
    //duración de la sesion 1 hora
    private const SESION_TIEMPO_VIDA = 3600;
    //nombre de la coockie de sesion
    private const SESION_NOMBRE = "sesionmongoid";
    
    private const SESION_COCKIE_RUTA = "/";
    
    //ruta del dominio de para las coockies
    private const SESION_COOKIE_DOMAIN = ""; 
    
    private $conexion;
    private $coleccionSesiones;
    
    public function __construct() {
        try{
            $this->conexion = Conexion::getInstance();
            $this->coleccionSesiones = $this->conexion->getCollection(self::COLECCION);
            
            session_set_save_handler($this);
            
            //establecer el periodo de recoleccion de basura
            ini_set("session.gc_maxlifetime", self::SESION_TIEMPO_VIDA);
            
            //establacer parametros para las cookies
            session_set_cookie_params(self::SESION_TIEMPO_VIDA
                    , self::SESION_COCKIE_RUTA
                    , self::SESION_COOKIE_DOMAIN);
            
            session_name(self::SESION_NOMBRE);
            session_cache_limiter("nocache");
            
            //iniciar la sesion
            //session_start();
        }catch(Exception $e){
            throw $e;
        }
    }
    
    public function close () : bool{
        return true;
    }
    
    public function destroy($session_id) : bool{
        $this->coleccionSesiones->deleteOne(["session_id" => $session_id]);
        
        return true;
    }
    
    public function gc($maxlifetime) : bool{
        $query;
        
        $query = ["expired_at" => ['$lt' => time()]];
        
        $this->coleccionSesiones->deleteMany($query);
        
        return true;
    }
    
    public function open($save_path, $session_name){
        return true;
    }
    
    public function read($session_id) : string{
        $query;
        $resultado;
        
        $query = [
            "session_id" => $session_id,
            "timedout_at" => ['$gte' => time()],
            "expired_at" => ['$gte' => time()],
        ];
        
        try{
            $resultado = $this->coleccionSesiones->findOne($query);
        } catch (Exception $ex) {
            throw $ex;
        }
               
        //$this->currentSession = $result;
        return isset($resultado["data"]) ? $resultado["data"]: ""; 
    }
    
    public function write($session_id, $session_data) : bool{
        $session;
        $query;
       
        $session = [
            '$set' => ["data" => $session_data
                    , "timedout_at" => time() + self::SESION_TIMEOUT
                    , "expired_at" => time() + self::SESION_TIEMPO_VIDA
                ]
        ];
        
        /*$session = [
            '$set' => ["data" => $session_data],
            '$set' => ["timedout_at" => (time() + self::SESION_TIMEOUT)],
            '$set' => ["expired_at" => (time() + self::SESION_TIEMPO_VIDA)]
        ];*/
        
        $query = [
            "session_id" => $session_id
        ];
        
        try{
            $this->coleccionSesiones->updateOne($query, $session, ["upsert" => true]);
        } catch (Exception $ex) {
            throw $ex;
        }
        
        
        return true;
    }
}

$sessionManager = new SessionManager();

//$sessionManager->gc(3600);

//echo $sessionManager->destroy("ama fiwing ma leysa");
        
?>
