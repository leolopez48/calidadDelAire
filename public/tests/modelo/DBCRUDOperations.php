<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Daniel Ángel <jorge.angel16@itca.edu.sv>
 */
interface DBCRUDOperations {
    public function insertar();
    public function modificar();
    public function eliminar();
    public function consultar($filtros);
}
