<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function multiImport($directorio){
    $files;
    $fullpath;
    
    $fullpath = $directorio."/*.php";
    echo $fullpath."<br>";
    $files = glob($fullpath);
    echo sizeof($files);
    foreach($files as $file){
        echo $file."<br>";
        require_once $file;
    }
}

multiImport("../vendor/mongodb/mongodb/src");

$clienteMongo;

$clienteMongo = new \MongoDB\Client();