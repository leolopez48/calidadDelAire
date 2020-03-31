<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utilidades
 *
 * @author Daniel Ángel <jorge.angel16@itca.edu.sv>
 */
class Utilidades {
    public static function multiImport($directorio){
        foreach(glob($directorio."/*.php") as $file){
            require_once $file;
        }
    }
}
