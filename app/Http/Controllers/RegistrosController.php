<?php
namespace App\Http\Controllers;

use App\registros;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PHPExcel; 
use PHPExcel_IOFactory;
use DB;

class RegistrosController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('registros.index');
    }

    

    public function restaurar(Request $request)
    {
        $datos = request()->except('_token');

        $very = registros::where(["_id" => $datos['id']])->update(['estado' => 1]);
        if($very){
            return redirect('adminMarkers/restaurar');
        }else{
            return redirect('adminMarkers');
        }
    }

    public function validar()
    {
        if(isset($_GET['id'])){
            $data = registros::where(['correlativo'=>$_GET['correlativo']])->where('_id','!=',$_GET['id'])->count();
            echo json_encode($data);

        }else{
            $data = registros::where(['correlativo'=>$_GET['correlativo']])->count();
            echo json_encode($data);
         }
    }

    public function cargar()
    {
        $entradas = "";
        if(isset($_GET['estado'])){
            $entradas = registros::where(['estado'=>intval($_GET['estado'])])->select('_id','correlativo', 'direccion', 'departamento','municipio','posision_mapa_top','posision_mapa_left','txt_img')->get();
        }else{
            $entradas = registros::where(['estado'=>1])->select('_id','correlativo', 'direccion', 'departamento','municipio','posision_mapa_top','posision_mapa_left','txt_img')->get();
        }
        // $entradas = $_GET['estado'];
        echo json_encode($entradas);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $data = request()->except('_token','_method','id');
        $data['registros'] =  array();

        if($request->hasFile('txt_img')){
            $data['txt_img'] = $request->file('txt_img')->store('uploads','public');
            $data['estado'] = 1;
            registros::insert($data);
            return redirect('adminMarkers/agregar');
        }else{
            return redirect('adminMarkers');
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $entadas;
        $totalEstaciones = false;
        $data = request()->all();
        // obtener fecha y hora----------
        date_default_timezone_set('America/El_Salvador');
        $fechaFin= date('Y-m-j H:i:s'); 
        $fechaIni = strtotime ( '-1 day' , strtotime ($fechaFin) ) ; 
        $fechaIni = date ( 'Y-m-j H:i:s' , $fechaIni); 
        // fin obtener fecha y hora------

        if($data['id'] == ""){
            $entradas['txt_img'] = 'uploads/bandera.png';
            $entradas['correlativo'] = ' ';
            $entradas['municipio'] = registros::count();
            $entradas['direccion'] = ' ';
            $entradas['departamento'] = ' ';
            $entradas['registros'] = array();
            $datos = registros::where(['estado'=>1])->select('correlativo','registros')->get();

            // recolectar ultimos datos---------------
            $datosAux = array();
            $t = count($datos);
            for($i=0;$i<$t;$i++){
                $r = count($datos[$i]['registros']);
                if($r>0){
                    if($r>100){
                        $datosAux[$i]['registros'] = array_slice($datos[$i]['registros'],(count($datos[$i]['registros'])-100));
                    }else{
                        $datosAux[$i]['registros'] = $datos[$i]['registros'];
                    }
                }else{
                    $datosAux[$i]['registros'][] = array();
                }
            }
            // fin recolectar ultimos datos------------
            // filtrar----------------------------
            if($data['fechaIni']!="" && $data['fechaFin']!=""){
                $fechaFin = $data['fechaFin'].' 00:00:00';
                $fechaIni = $data['fechaIni'].' 00:00:00';
            }
            $array = array();
            $tamanio = count($datos);
            for($j = 0; $j < $tamanio; $j++ ){
                $tamanioRe = count($datos[$j]['registros']);
                $array = $datos[$j]['registros'];
                for( $i = 0; $i < $tamanioRe ; $i++ ) {
                    $fec = ($datos[$j]['registros'][$i]['fecha_hora']);
                    if(!((strtotime($fechaIni)<=strtotime($fec)) 
                        && (strtotime($fechaFin)>=strtotime($fec)))){
                        unset($array[$i]);
                    }
                }
                $array= array_values($array);
                $datos[$j]['registros'] = $array;
            }
            $bandera = true;
            for($i=0;$i<$t;$i++){
                $r = count($datos[$i]['registros']);
                if($r>0){
                    $bandera = false;
                    break;
                }
            }
            if($bandera){
                $datos = $datosAux;
            }
            // fin filtrar------------------------
            if(count($datos)>0){
                foreach ($datos as $value) {
                    if(count($value['registros'])>0){
                        $totalEstaciones = true;
                        break;
                    }
                }
            }
            if($totalEstaciones){
                // sacar promedio-------------------------
                $count = count($datos);

                $dividir = array();
                $valiSuma = true;
                for($i = 0; $i < $count; $i++){
                    $count2 = count($datos[$i]['registros']);
                    for($j = 0; $j < $count2; $j++){


                            $tEn = count($entradas['registros']);
                            $valiSuma = true;
                            for($k = 0; $k < $tEn; $k++){

                                if($entradas['registros'][$k]['fecha_hora']==$datos[$i]['registros'][$j]['fecha_hora']){

                                    $entradas['registros'][$k]['pm25'] = ($entradas['registros'][$k]['pm25']) + $datos[$i]['registros'][$j]['pm25'];
                                    $entradas['registros'][$k]['pm10'] = ($entradas['registros'][$k]['pm10']) + $datos[$i]['registros'][$j]['pm10'];
                                    $entradas['registros'][$k]['temp'] = ($entradas['registros'][$k]['temp']) + $datos[$i]['registros'][$j]['temp'];
                                    $valiSuma = false;
                                    $dividir[$k]++;
                                    break;

                                }

                            }
                            if($valiSuma){
                                $indice = count($entradas['registros']);
                                $entradas['registros'][$indice]['fecha_hora'] = $datos[$i]['registros'][$j]['fecha_hora'];
                                $entradas['registros'][$indice]['pm25'] = $datos[$i]['registros'][$j]['pm25'];
                                $entradas['registros'][$indice]['pm10'] = $datos[$i]['registros'][$j]['pm10'];
                                $entradas['registros'][$indice]['temp'] =  $datos[$i]['registros'][$j]['temp'];

                                if(($dividir[$indice] ?? null) == null){
                                    $dividir[$indice] = 1;
                                }else{
                                    $dividir[$indice]++;
                                }
                            }
                        

                    }
                }
                $count = count($entradas['registros']);
                for($j = 0; $j < $count; $j++){
                        $entradas['registros'][$j]["pm25"] = ($entradas['registros'][$j]["pm25"]/$dividir[$j]);
                        $entradas['registros'][$j]["pm10"] = ($entradas['registros'][$j]["pm10"]/$dividir[$j]);
                        $entradas['registros'][$j]["temp"] = ($entradas['registros'][$j]["temp"]/$dividir[$j]);
                }
                $entradas[0] = $entradas;
               
                // fin sacar promedio-------------------------
            }else{
                $entradas['registros'] = array();
                $entradas[0] = $entradas;
            }
        }else{
            $entradas = registros::where(["_id" => $data['id'] ])->where(['estado'=>1])->get();

            // filtrar----------------------------
            if($data['fechaIni']!="" && $data['fechaFin']!=""){
                $fechaFin = $data['fechaFin'].' 00:00:00';
                $fechaIni = $data['fechaIni'].' 00:00:00';
            }
            $array = array();
            $tamanio = count($entradas);
            for($j = 0; $j < $tamanio; $j++ ){
                $tamanioRe = count($entradas[$j]['registros']);
                $array = $entradas[$j]['registros'];
                for( $i = 0; $i < $tamanioRe ; $i++ ) {
                    $fec = ($entradas[0]['registros'][$i]['fecha_hora']);
                    if(!((strtotime($fechaIni)<=strtotime($fec)) 
                        && (strtotime($fechaFin)>=strtotime($fec)))){
                        unset($array[$i]);
                    }
                }
                $array= array_values($array);
                $entradas[$j]['registros'] = $array;
            }
            if(!($data['fechaIni']!="" && $data['fechaFin']!="")){
                if(!(count($entradas[0]['registros'])>0)){
                    $entradas = registros::where(["_id" => $data['id'] ])->where(['estado'=>1])->get();
                // recolectar ultimos datos---------------
                    $datosAux = array();
                    if(count($entradas[0]['registros'])>0){
                        $datosAux[0] = $entradas[0]; 
                        if(count($entradas[0]['registros'])>100){
                            $datosAux[0]['registros'] = array_slice($entradas[0]['registros'],(count($entradas[0]['registros'])-100));
                        }else{
                            $datosAux[0]['registros'] = $entradas[0]['registros'];
                        }
                    }else{
                        $datosAux[0]['registros'] = array();
                    }
                    $entradas=$datosAux;
                // fin recolectar ultimos datos------------
                }
            }
            // fin filtrar------------------------
        }

        echo json_encode($entradas);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\registros  $registros
     * @return \Illuminate\Http\Response
     */
    public function show(registros $registros)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\registros  $registros
     * @return \Illuminate\Http\Response
     */
    public function edit(registros $registros)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\registros  $registros
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $very = false;
        if($request->hasFile('txt_img')){
            $data = request()->except('_token','_method');
            $entradas = registros::select('txt_img')->where(["_id" => $id])->get();
            Storage::delete("public/".$entradas[0]['txt_img']);
            $data['txt_img'] = $request->file('txt_img')->store('uploads','public');
            $very = registros::where(["_id" => $id])->update($data);
        }else{
            $data = request()->except('_token','_method','txt_img');
            $very = registros::where(["_id" => $id])->update($data);
        }
        if($very){
            return redirect('adminMarkers/modificar');
        }else{
            return redirect('adminMarkers');
        }

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\registros  $registros
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $entradas = registros::select('txt_img')->where(["_id" => $id])->get();
        Storage::delete("public/".$entradas[0]['txt_img']);
        if(registros::where(["_id" => $id])->update(['estado'=>0])){
            return redirect('adminMarkers/eliminar');
        }else{
            return redirect('adminMarkers');
        }
        
    }

    public function crearExcel(Request $request)
    {
        // ------------------------------------
            // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();
            $content="";
            $title="Registros";
            $SavePath = null;
     // Set properties
            $objPHPExcel->getProperties()->setCreator("ITCA-FEPADE 2019")
                        ->setLastModifiedBy("ITCA-FEPADE 2019")
                        ->setTitle("DOCUMENTO CSV REGISTRO DE ESTACIONES")
                        ->setSubject("DOCUMENTO CSV")
                        ->setDescription("DOCUMENTO CSV GENERADO CON LARAVEL")
                        ->setKeywords("CSV PHP")->setCategory("ARCHIVO FINAL");
     // Set default font
            $request = array();
            $request = [
                'id' => '',
                'fechaIni' => date('Y').'/'.(date('m')-1).'/'.cal_days_in_month(CAL_GREGORIAN, (date('m')-1), date('Y')),
                'fechaFin' => date('Y').'/'.date('m').'/'.cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'))
            ];
            $datos = $this->excel($request);
            $contador = 0;
            $data = json_decode($datos);
            //dd($data);  
            $registros = array();
            if($data->registros ?? null != null){
                $registros = $data->registros;
            }else{
                $registros = $data[0]->registros;
            }
            foreach ($registros  as $value) {
            	//dd($contador);
            	//dd($registros);
            	if($contador == 1){
            		//dd("Contador".$contador);
            		$objPHPExcel->setActiveSheetIndex(0)
                		->setCellValue('A'.$contador , '#')
                        ->setCellValue('B'.$contador , 'Fecha')
                        ->setCellValue('C'.$contador , 'PM25')
                        ->setCellValue('D'.$contador , 'PM10')
                        ->setCellValue('E'.$contador , 'TEMP')
                        ->setCellValue('F'.$contador , 'dia')
                        ->setCellValue('G'.$contador , 'Mes')
                        ->setCellValue('H'.$contador , 'Prom')
                        ->setCellValue('I'.$contador , ',,,,,,,,,,,,,,,,,,');
            	}else{
            		    $objPHPExcel->setActiveSheetIndex(0)
                		->setCellValue('A'.$contador , $contador)
                        ->setCellValue('B'.$contador , date('d/m/Y', strtotime($value->fecha_hora)))
                        ->setCellValue('C'.$contador , $value->pm25)
                        ->setCellValue('D'.$contador , $value->pm10)
                        ->setCellValue('E'.$contador , $value->temp)
                        ->setCellValue('F'.$contador , date('d', strtotime($value->fecha_hora)))
                        ->setCellValue('G'.$contador , date('m', strtotime($value->fecha_hora)))
                        ->setCellValue('H'.$contador , number_format(($value->pm25+$value->pm10+$value->temp)/3, 2, '.', ''))
                        ->setCellValue('I'.$contador , ',,,,,,,,,,,,,,,,,,');
            	}
                $contador++;
                //dd($objPHPExcel);
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Simple');
     // Set active sheet index to the first sheet, so Excel opens this as the first sheet
     $objPHPExcel->setActiveSheetIndex(0);
     // Save Excel 2003 file
     /*Configuración para descargar archivo
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . 'registros.csv');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV')->setDelimiter(',')->setEnclosure('"')->setLineEnding("\r\n")->setSheetIndex(0);
     //$SavePath = $SavePath ? $SavePath : '/var/www/aire/public/' . date('YmdHis') . '.csv';
     //$SavePath = $SavePath ? $SavePath : '/var/www/aire/public/' . 'registros' . '.csv';
    //$objWriter->save($SavePath);
    $objWriter->save('php://output');*/
    // Save Excel 2003 file);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV')->setDelimiter(',')->setEnclosure('')->setLineEnding("\r\n")->setSheetIndex(0);
     //$SavePath = $SavePath ? $SavePath : '/var/www/aire/public/' . date('YmdHis') . '.csv';
            $SavePath = $SavePath ? $SavePath : '/var/www/aire/public/machine/' . 'registro_pmitca' . '.csv';
            $objWriter->save($SavePath);
            return view('proyecciones');

    }


    public function crearCsv($data)
    {
        // ------------------------------------
            // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();
            $content="";
            $title="Registros";
            $SavePath = null;
     // Set properties
            $objPHPExcel->getProperties()->setCreator("ITCA-FEPADE 2019")
                        ->setLastModifiedBy("ITCA-FEPADE 2019")
                        ->setTitle("DOCUMENTO CSV REGISTRO DE ESTACIONES")
                        ->setSubject("DOCUMENTO CSV")
                        ->setDescription("DOCUMENTO CSV GENERADO CON LARAVEL")
                        ->setKeywords("CSV PHP")
                        ->setCategory("ARCHIVO FINAL");
     // Set default font
            $request = array();
            $request = $data;
            //dd($request);
            $datos = $this->excel($request);
            $contador = 0;
            $data = json_decode($datos);
            //var_dump($data);  
            $registros = array();
            if($data->registros ?? null != null){
                $registros = $data->registros;
            }else{
                $registros = $data[0]->registros;
            }
//dd($registros);
            foreach ($registros  as $value) {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$contador , $value->fecha_hora)
                        ->setCellValue('B'.$contador , $value->pm25)
                        ->setCellValue('C'.$contador , $value->pm10)
                        ->setCellValue('D'.$contador , $value->temp);
                $contador++;
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Simple');
     // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
     // Save Excel 2003 file);
            header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . 'registros.csv');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV')->setDelimiter(',')->setEnclosure('"')->setLineEnding("\r\n")->setSheetIndex(0);
     //$SavePath = $SavePath ? $SavePath : '/var/www/aire/public/' . date('YmdHis') . '.csv';
     //$SavePath = $SavePath ? $SavePath : '/var/www/aire/public/' . 'registros' . '.csv';
    //$objWriter->save($SavePath);
    $objWriter->save('php://output');
    
    }

    function FullBD(){
        $data = DB::table("registros")->get();
        
    }

    function excel($data)
    {
        //dd($data);
        $entradas;
        $totalEstaciones = false;
        // obtener fecha y hora----------
        date_default_timezone_set('America/El_Salvador');
        $fechaFin= $data["fechaFin"]; 
        $fechaIni = $data["fechaIni"] ;
        //dd($fechaIni); 
        //$fechaIni = date ( 'Y-m-j' , $fechaIni); 
        // fin obtener fecha y hora------
        $total = registros::count();
        $idfinal = registros::where(['estado'=>1])->select('_id', 'correlativo')->get()->last();
        $correlativo = registros::where(['estado'=>1])->select('correlativo')->get();
        //dd("idFInal:" . $idfinal["_id"]);
        //$registros = registros::where(['correlativo'=>$total])->select('registros', 'correlativo')->get();

            // filtrar----------------------------
        if($data['id'] == ""){
            $entradas['txt_img'] = 'uploads/bandera.png';
            $entradas['correlativo'] = $idfinal['correlativo'];
            $entradas['municipio'] = '';
            $entradas['direccion'] = ' ';
            $entradas['departamento'] = ' ';
            $entradas['registros'] = array();
            $datos = registros::where(['estado'=>1], ['id_'=>$idfinal["_id"]])->select('registros')->get();

            // recolectar ultimos datos---------------
            $datosAux = array();
            $t = count($datos);
            for($i=0;$i<$t;$i++){
                $r = count($datos[$i]['registros']);
                if($r>0){
                    if($r>100){
                        $datosAux[$i]['registros'] = array_slice($datos[$i]['registros'],(count($datos[$i]['registros'])-100));
                    }else{
                        $datosAux[$i]['registros'] = $datos[$i]['registros'];
                    }
                }else{
                    $datosAux[$i]['registros'][] = array();
                }
            }
            // fin recolectar ultimos datos------------
            // filtrar----------------------------
            if($data['fechaIni']!="" && $data['fechaFin']!=""){
                $fechaFin = $data['fechaFin'].' 00:00:00';
                $fechaIni = $data['fechaIni'].' 00:00:00';
            }
            $array = array();
            $tamanio = count($datos);
            for($j = 0; $j < $tamanio; $j++ ){
                $tamanioRe = count($datos[$j]['registros']);

                $array = $datos[$j]['registros'];
                for( $i = 0; $i < $tamanioRe ; $i++ ) {
                	if($i == 0)
                    $fecI = ($datos[$j]['registros'][$i]['fecha_hora']);
                	$fecF = ($datos[$j]['registros'][$i]['fecha_hora']);
                }
                //dd($fecI, $fecF);
                for( $i = 0; $i < $tamanioRe ; $i++ ) {
                    $fec = ($datos[$j]['registros'][$i]['fecha_hora']);
                    //dd($datos[$j]['registros']);
                    //dd($fec);
                    if(!((strtotime($fechaIni)<=strtotime($fecI)) && (strtotime($fechaFin)>=strtotime($fecF)))){
                        unset($array[$i]);

                    }
                }
                $array= array_values($array);

                $datos[$j]['registros'] = $array;

            }
            $bandera = true;
            for($i=0;$i<$t;$i++){
                $r = count($datos[$i]['registros']);
                if($r>0){
                    $bandera = false;
                    break;
                }
            }
            if($bandera){
                $datos = $datosAux;
            }
            // fin filtrar------------------------
            if(count($datos)>0){
                foreach ($datos as $value) {
                    if(count($value['registros'])>0){
                        $totalEstaciones = true;
                        break;
                    }
                }
            }
            if($totalEstaciones){
                // sacar promedio-------------------------
                $count = count($datos);
                //dd($count);
                $dividir = array();
                $valiSuma = true;
                for($i = 0; $i < $count; $i++){
                    $count2 = count($datos[$i]['registros']);
                    for($j = 0; $j < $count2; $j++){


                            $tEn = count($entradas['registros']);
                            //dd($count2);
                            $valiSuma = true;
                            for($k = 0; $k < $tEn; $k++){

                                if($entradas['registros'][$k]['fecha_hora']==$datos[$i]['registros'][$j]['fecha_hora']){

                                    $entradas['registros'][$k]['pm25'] = ($entradas['registros'][$k]['pm25']) + $datos[$i]['registros'][$j]['pm25'];
                                    $entradas['registros'][$k]['pm10'] = ($entradas['registros'][$k]['pm10']) + $datos[$i]['registros'][$j]['pm10'];
                                    $entradas['registros'][$k]['temp'] = ($entradas['registros'][$k]['temp']) + $datos[$i]['registros'][$j]['temp'];
                                    $valiSuma = false;
                                    $dividir[$k]++;
                                    break;

                                }

                            }
                            if($valiSuma){
                                $indice = count($entradas['registros']);
                                $entradas['registros'][$indice]['fecha_hora'] = $datos[$i]['registros'][$j]['fecha_hora'];
                                $entradas['registros'][$indice]['pm25'] = $datos[$i]['registros'][$j]['pm25'];
                                $entradas['registros'][$indice]['pm10'] = $datos[$i]['registros'][$j]['pm10'];
                                $entradas['registros'][$indice]['temp'] =  $datos[$i]['registros'][$j]['temp'];

                                if(($dividir[$indice] ?? null) == null){
                                    $dividir[$indice] = 1;
                                }else{
                                    $dividir[$indice]++;
                                }
                            }
                    }
                }
                //dd($count);
                $count = count($entradas['registros']);
                //dd($count);
                for($j = 0; $j < $count; $j++){
                        $entradas['registros'][$j]["pm25"] = ($entradas['registros'][$j]["pm25"]/$dividir[$j]);
                        $entradas['registros'][$j]["pm10"] = ($entradas['registros'][$j]["pm10"]/$dividir[$j]);
                        $entradas['registros'][$j]["temp"] = ($entradas['registros'][$j]["temp"]/$dividir[$j]);
                }
                $entradas[0] = $entradas;
               
                // fin sacar promedio-------------------------
            }else{
                $entradas['registros'] = array();
                $entradas[0] = $entradas;
            }
        }else{
            $entradas = registros::where(["_id" => $data['id'] ])->where(['estado'=>1])->get();

            // filtrar----------------------------
            if($data['fechaIni']!="" && $data['fechaFin']!=""){
                $fechaFin = $data['fechaFin'].' 00:00:00';
                $fechaIni = $data['fechaIni'].' 00:00:00';
            }
            $array = array();
            $tamanio = count($entradas);
            for($j = 0; $j < $tamanio; $j++ ){
                $tamanioRe = count($entradas[$j]['registros']);
                $array = $entradas[$j]['registros'];
                for( $i = 0; $i < $tamanioRe ; $i++ ) {
                    $fec = ($entradas[0]['registros'][$i]['fecha_hora']);
                    if(!((strtotime($fechaIni)<=strtotime($fec)) 
                        && (strtotime($fechaFin)>=strtotime($fec)))){
                        unset($array[$i]);
                    }
                }
                $array= array_values($array);
                $entradas[$j]['registros'] = $array;
            }
            if(!($data['fechaIni']!="" && $data['fechaFin']!="")){
                if(!(count($entradas[0]['registros'])>0)){
                    $entradas = registros::where(["_id" => $data['id'] ])->where(['estado'=>1])->get();
                // recolectar ultimos datos---------------
                    $datosAux = array();
                    if(count($entradas[0]['registros'])>0){
                        $datosAux[0] = $entradas[0]; 
                        if(count($entradas[0]['registros'])>100){
                            $datosAux[0]['registros'] = array_slice($entradas[0]['registros'],(count($entradas[0]['registros'])-100));
                        }else{
                            $datosAux[0]['registros'] = $entradas[0]['registros'];
                        }
                    }else{
                        $datosAux[0]['registros'] = array();
                    }
                    $entradas=$datosAux;
                // fin recolectar ultimos datos------------
                }
            }
            // fin filtrar------------------------
        }
        //dd($entradas);
        return json_encode($entradas);
    }

    public function ejecutar($id, $ini, $fin){
        //dd($request);
//        $data = request();
        //dd($id);
        $data = array('id'=>$id, 'fechaIni'=>$ini, 'fechaFin'=>$fin);
        $datos = json_encode($data);
        //dd($datos);
        $this->crearCsv($data);
        //usleep(5e+6);
                
        //$ar = array();
        //exec("conda init bash");
        //exec("conda activate R3.4");
        //exec("jupyter nbconvert --to notebook --execute /var/www/aire/public/machine/prediccion_pm25itca.ipynb");
        //$resultado = exec("ls -lh");
        //echo $resultado;
        //exec("google-chrome");
        //usleep(3e+7);
        //exec("jupyter nbconvert --to html /var/www/aire/public/machine/prediccion_pm25itca.ipynb",$datos, $ar);
        //exec("jupyter nbconvert --template=nbextensions --to=html /var/www/aire/public/machine/prediccion_pm25itca.html");
        //exec("google-chrome /var/www/aire/public/machine/prediccion_pm25itca.html");
        //dd($ar);
        return view('proyecciones');
    }

    public function id(){
        $id = DB::table('registros')->where('estado','=',1)->select('_id')->get();
        return $id;
    }
}
