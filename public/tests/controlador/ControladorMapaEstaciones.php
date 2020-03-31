<?php

require_once "../modelo/Estacion.php";
require_once "../modelo/RegistroEstacion.php";
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


        
$estacion = new Estacion();


require '../Classes/PHPExcel/IOFactory.php'; //Agregamos la librería 
	
	//Variable con el nombre del archivo
	$nombreArchivo = '../SMiguel.csv';
	// Cargo la hoja de cálculo
	$objPHPExcel = PHPExcel_IOFactory::load($nombreArchivo);
	
	//Asigno la hoja de calculo activa
	$objPHPExcel->setActiveSheetIndex(0);
	//Obtengo el numero de filas del archivo
	$numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
	
	for ($i = 2; $i <= $numRows; $i++) {

		$num = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
		$fecha = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
		$pm2_5 = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
		$pm10 = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue();
		$temp = $objPHPExcel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue();
		
// inserta registros a la estacion-----------
		 $estacion->setCorrelativo(3);
		 $rEstacion = new RegistroEstacion($estacion);
		 $rEstacion->setFecha_hora($fecha);
         $rEstacion->setPm25($pm2_5);
         $rEstacion->setPm10($pm10);
      $rEstacion->setTemp($temp);
		$rEstacion->insertar();
	}
	// var_dump($estacion->getRegistros(null,null)[0]->getFecha_hora()) ;
// inserta una estacion nueva-------------
    // $estacion->setCorrelativo(1);

    // $estacion->setDepartamento('sonsonate');

    // $estacion->setMunicipio('sonsonate');

    // $estacion->setFoto('asnajbxajzbxjbx');

    // $estacion->setPosision_mapa_top('19.12');

    // $estacion->setPosicion_mapa_left('12.12');

    // $estacion->setDireccion('sonsonate, sonsonate');
    // $estacion->insertar();



$salida = array();
// --------imprime los datos de una estacion
/*foreach($estacion->consultar([]) as $est){
   $arrTmp;
    
   $arrTmp = array();
   $arrTmp["id"] = $est->getObjectId();
    $arrTmp["estacion"] = $est->getCorrelativo();
  $arrTmp["posTop"] = $est->getPosision_mapa_top();
    $arrTmp["posLeft"] = $est->getPosicion_mapa_left();
    $arrTmp["nombre"] = $est->getDepartamento();
    $arrTmp["descripcion"] = $est->getDireccion();
    
    $salida[] = $arrTmp;
}*/

// --------imprime las los registros de una estacion --------
 foreach($estacion->getRegistros(null,null) as $est){
     $arrTmp;
    
//     $arrTmp = array();
//     $arrTmp["correlativo"] = $est->getEstacionPertenece()->getCorrelativo();
     $arrTmp["Fecha_hora"] = $est->getFecha_hora();
//     $arrTmp["Pm25"] = $est->getPm25();
//     $arrTmp["Pm10"] = $est->getPm10();
//     $arrTmp["Temp"] = $est->getTemp();
    
//     $salida[] = $arrTmp;
 }

echo json_encode($salida);
