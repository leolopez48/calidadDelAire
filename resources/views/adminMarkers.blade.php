@extends('layouts.app')
@section('content')

<script type="text/javascript">
var valiImg = true;
var bandera = true;
let alerta=(mensaje,boton)=>{
    Swal.fire({
        type: 'warning',
        title: '¡ADVERTENCIA!',
        text: mensaje,
        showCancelButton: true,
        cancelButtonColor: 'red',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, '+boton,
        confirmButtonColor: 'green'
    }).then(result=>{
        if(result.value){
           $("#formEnviar").submit();
        }
    });
}
$(document).ready(function() {  
    $("#valiMunicipio").hide();
    $("#valiDireccion").hide();
    $("#valiDepartamento").hide();
    $("#valCorrelativo").hide();    
    /*-------preparar la vista previa de las imagenes a subir*/
    $("#validacion_img").hide();
    $("#txt_img").on('change',function(){
        var archivos=document.getElementById("txt_img").files;
        var navegador= window.URL || window.webkitURL;
        var type=archivos[0].type;
        var name=archivos[0].name;
        if(type!='image/jpg' && type!='image/jpeg'){
            $("#validacion_img").html("Formato de imagen no valido seleccione una imagen <strong>jpg o jpeg...</strong>");
            $("#validacion_img").fadeIn();
            valiImg = true;
        }else if(archivos.length > 1){
            $("#validacion_img").html("No se puede seleccionar mas de una imagen...");
            $("#validacion_img").fadeIn();
            valiImg = true;
        }
        else{
            var objeto_url=navegador.createObjectURL(archivos[0]);
            $("#Mostrar_img").attr("src",objeto_url);
            $("#validacion_img").fadeOut();
            valiImg = false;
        }
    });
    // ----fin cargar img------------------------
    $("#agregar").on('click', function(event) {
        $("input[name='_method']").val("HEAD");
        $("#formEnviar").attr("method","POST")
        $("#formEnviar").attr("action","{{ url('registros/create') }}")
        $("#formEnviar").submit();
    });
    $("#modificar").on('click', function(event) {
        $("input[name='_method']").val("PATCH");
        $("#formEnviar").attr("method","POST")
        $("#formEnviar").attr("action","{{ url('registros') }}/"+$("#id").val())
        alerta("¿Desea modificar la estación?","Modificar");
    });
    $("#btnRestaurar").on('click', function(event) {
        $("input[name='_method']").val("HEAD");
        $("#formEnviar").attr("method","POST")
        $("#formEnviar").attr("action","{{ url('registros/restaurar') }}")
        alerta("¿Desea restaurar la estación?","Restaurar");
    });
    $("#eliminar").on('click', function(event) {
        $("input[name='_method']").val("DELETE");
        $("#formEnviar").attr("method","POST")
        $("#formEnviar").attr("action","{{ url('registros') }}/"+$("#id").val())
        alerta("Después de eliminar está estación no podrá ser recuperada ¿Desea eliminar la estación?","Eliminar");
    });
    $("#nuevo").on('click', function(event) {
        $("#agregar").attr('disabled',false);
        $("#cancelar").attr('disabled',false);
        $("#modificar").attr('disabled',true);
        $("#eliminar").attr('disabled',true);
        $("#estacion").val("");
        $("#departamento").val("");
        $("#direccion").val("");
        $("#municipio").val("");
        $("#pos_top").val("50%");
        $("#pos_lef").val("50%");
        $("#Mostrar_img").attr('src',"{{asset('img/usuario.png')}}");
    });        
    $("#formEnviar").submit(function(){
        $("#cargando").show();
        $("#valiMunicipio").fadeOut();
        $("#valiDireccion").fadeOut();
        $("#valiDepartamento").fadeOut();
        $("#valCorrelativo").fadeOut();

        if(bandera){
            if($("input[name='_method']").val()=="DELETE"){
                if($("#id").val()==""){
                    Swal.fire('Mensaje','Seleccione una estación para eliminar','danger');
                    $("#cargando").hide();
                    return false;
                }
            }else if($("#formEnviar").attr("action")=="{{ url('registros/restaurar') }}"){
                if($("#id").val()==""){
                    Swal.fire('Mensaje','Seleccione una estación para restaurar','danger');
                    $("#cargando").hide();
                    return false;
                }
            }else if( $("#formEnviar").attr("action")=="{{ url('registros/create') }}"){
                if($("#estacion").val()==""){
                    $("#valCorrelativo").html('Campo requerido...');
                    $("#valCorrelativo").fadeIn();
                    $("#cargando").hide();
                    return false;
                }else if($("#departamento").val()==null || $("#departamento").val()=='seleccione'){
                    $("#valiDepartamento").fadeIn();
                    $("#cargando").hide();
                    return false;
                }else if($("#municipio").val()==null || $("#municipio").val()=='seleccione'){
                    $("#valiMunicipio").fadeIn();
                    $("#cargando").hide();
                    return false;
                }else if($("#direccion").val()==""){
                    $("#valiDireccion").fadeIn();
                    $("#cargando").hide();
                    return false;
                }else if(valiImg){
                    $("#validacion_img").html("Imagen requerida...");
                    $("#validacion_img").fadeIn();
                    $("#cargando").hide();
                    return  false;
                }else{                   
                    $.ajax({
                        url: '../../../registros/validar',
                        type: 'get',
                        dataType: 'json',
                        data: {
                            correlativo: $("#estacion").val(),
                            departamento: $("#departamento").val()
                        },
                    })
                    .done(function(data) {
                        if(data>0){
                            $("#valCorrelativo").html('El correlativo ya existe...');
                            $("#valCorrelativo").fadeIn();
                            $("#cargando").hide();
                        }else{
                            bandera = false;
                            $("#formEnviar").submit();
                        }
                    })
                    .fail(function(data) {
                       Swal.fire('Mensaje','A ocurrido un error','danger');
                       $("#cargando").hide();
                    });
                    return false;
                }            
            }else if( $("input[name='_method']").val() == "PATCH" ){
                if($("#estacion").val()==""){
                    $("#valCorrelativo").html('Campo requerido...');
                    $("#valCorrelativo").fadeIn();
                    $("#cargando").hide();
                    return false;
                }else if($("#departamento").val()==null || $("#departamento").val()=='seleccione'){
                    $("#valiDepartamento").fadeIn();
                    $("#cargando").hide();
                    return false;
                }else if($("#municipio").val()==null || $("#municipio").val()=='seleccione'){
                    $("#valiMunicipio").fadeIn();
                    $("#cargando").hide();
                    return false;
                }else if($("#direccion").val()==""){
                    $("#valiDireccion").fadeIn();
                    $("#cargando").hide();
                    return false;
                }else{                  
                    $.ajax({
                        url: '../../../registros/validar',
                        type: 'get',
                        dataType: 'json',
                        data: {
                            correlativo: $("#estacion").val(),
                            departamento: $("#departamento").val(),
                            id: $("#id").val()
                        },
                    })
                    .done(function(data) {
                        if(data>0){
                            $("#valCorrelativo").html('El correlativo ya existe...');
                            $("#valCorrelativo").fadeIn();
                            $("#cargando").hide();                     
                        }else{
                            bandera = false;
                            $("#formEnviar").submit();
                        }
                    })
                    .fail(function(data) {
                       Swal.fire('Mensaje','A ocurrido un error','danger');
                       $("#cargando").hide();
                    });
                    return false;
                }
            }
        }
    }); 
});
var inicial = false;
function notificaciones(valor){
    if(valor=='1'){
        if( $("#restaurar").val() =='0'){
            Swal.fire('Mensaje','No se encontraron estaciones inactivas','warning');
            $("#btnCRUD").removeClass('d-none');
            $("#btnRest").addClass('d-none');

            $("#divFoto").removeClass('d-none');
            $("#modificar").attr('disabled',true);
            $("#eliminar").attr('disabled',true);
            $("#cancelar").attr('disabled',true);
            $("#agregar").attr('disabled',true);
        }else{
            Swal.fire('Mensaje','No se encontraron estaciones activas','warning');
            if(inicial){
                $("#btnCRUD").addClass('d-none');
                $("#btnRest").removeClass('d-none');

                $("#divFoto").addClass('d-none');
                $("#btnRestaurar").attr('disabled',true);
            }else{
                inicial = true;
            }
        }
    }else if(valor=='2'){
        Swal.fire('Mensaje','Ocurrio un error','danger');
    }
} 
    </script>
<?php 
if(isset($datos) && !empty($datos)){
    if($datos=='modificar')
        echo "<script>Swal.fire('Mensaje','Estación modificada exitosamente.','info')</script>";
    else if ($datos=='eliminar')
        echo "<script>Swal.fire('Mensaje','Estación eliminada exitosamente.','info')</script>";
    else if ($datos=='agregar')
        echo "<script>Swal.fire('Mensaje','Estación agregada exitosamente.','info')</script>";
    else if ($datos=='restaurar')
        echo "<script>Swal.fire('Mensaje','Estación restaurada exitosamente.','info')</script>";
}
?>
        <div align="center">
    <div id="cargando" style="position: fixed;width: 100%;height: 100%;display: flex;justify-content: center;align-items: center; z-index: 999;" class="text-center">
        <h3 class="d-inline-block text-danger font-weight-bold" style="margin-right: -70px;z-index: 11;">Procesando</h3>
        <img src="{{ asset('img/cargando.gif') }}" style="width: 200px;height: 200px;z-index: 11;">
        <div class="bg-light" style="z-index:10;position: absolute;width: 100%;height: 200%;opacity: 0.7;"></div>
    </div>
    <script type="text/javascript">
        $("#cargando").hide();
    </script>
        <div class="container-fluid ">
        <div class="row">
            <div class="col-md-8 col-sm-12 border">
                <div class="row">
                    <div class="col-md-4">
                        <label for="restaurar">Estaciones a mostrar:</label>
                        <select id="restaurar" class="form-control">
                            <option value="1">Activas</option>
                            <option value="0">Inactivas</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="clearfix"></div>  
                <div id="titleMapa" class="h4 text-center font-weight-bold text-muted font-italic">Estaciones Activas</div>              
                <iframe src="{{ url('crudMarker') }}" frameborder="0px" width="80%" height="500px">
                </iframe>
            </div>

            <div class="col-md-4 col-sm-12 border">
            <form action="{{ url('registros/create') }}" method="POST" id="formEnviar" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('HEAD') }}
                <div class="row mt-1" id="divFoto">
                    <input type="hidden" name="posision_mapa_top" id="pos_top">
                    <input type="hidden" name="posision_mapa_left" id="pos_lef">
                        <!-- Campo fotografia -->
                           <div class="form-group col-md-12 col-sm-12 col-lg-4">
                                <img class="imagen"  src="{{asset('img/usuario.png')}}" id="Mostrar_img">
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-lg-8 px-2">
                                <label for="txt_img">Fotografia: </label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="txt_img" name="txt_img">
                                    <label class="custom-file-label" for="txt_img">Click aqui...</label>
                                </div>
                                <div class="text-danger" id="validacion_img">
                                    Formato de imagen no valido seleccione una imagen <strong>jpg o jpeg...</strong>
                                 </div>
                            </div>
                </div>
                <div class="row">
                    <div class="clearfix"></div>
                    <div class="col-md-12 px-2 d-none">
                        <div class="form-group">
                            <label>id:</label>
                            <input class="form-control" id="id" name="id">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12 px-2">
                        <input type="hidden" id="opcion">
                        <div class="form-group">
                            <label>Correlativo:</label>
                            <input class="form-control" id="estacion" name="correlativo">
                            <div class="text-danger h5" id="valCorrelativo">
                                Campo requerido...
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12 px-2" >
                        <div class="form-group">
                            <label>Departamento:</label>
                            <select type="text" class="form-control" name="departamento" id="departamento">
                                <option value="seleccione">Seleccione...</option>
                                <option value="Santa Ana">Santa Ana</option>
                                <option value="Ahuachapán">Ahuachapán</option>
                                <option value="Sonsonate">Sonsonate</option>
                                <option value="La Libertad">La Libertad</option>
                                <option value="San Salvador">San Salvador</option>
                                <option value="Chalatenango">Chalatenango</option>
                                <option value="Cuscatlán">Cuscatlán</option>
                                <option value="La Paz">La Paz</option>
                                <option value="Cabañas">Cabañas</option>
                                <option value="San Vicente">San Vicente</option>
                                <option value="Usulután">Usulután</option>
                                <option value="San Miguel">San Miguel</option>
                                <option value="Morazán">Morazán</option>
                                <option value="La Unión">La Unión</option>
                            </select>
                            <div class="text-danger h5" id="valiDepartamento">
                                Campo requerido...
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12 px-2" >
                        <div class="form-group">
                            <label>Municipio:</label>
                            <select type="text" class="form-control" name="municipio" id="municipio" disabled="true">
                                <option value="seleccione">Seleccione...</option>
                            </select>
                            <div class="text-danger h5" id="valiMunicipio">
                                Campo requerido...
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12 px-2" >
                        <div class="form-group">
                            <label>Dirección:</label>
                            <textarea class="form-control" name="direccion" id="direccion"></textarea>
                            <div class="text-danger h5" id="valiDireccion">
                                Campo requerido...
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12 px-2" id="btnCRUD">
                        <div class="text-center">
                            <button type="button" id="nuevo" class="btn btn-primary" >NUEVO</button>
                            <button type="button" id="agregar" class="btn btn-success" disabled="true">AGREGAR</button>
                            <button type="button" id="modificar" class="btn btn-secondary" disabled="true">MODIFICAR</button>
                            <ul></ul>
                            <button type="button" id="eliminar" class="btn btn-danger" disabled="true">ELIMINAR</button>
                            <button id="cancelar" class="btn btn-danger" disabled="true">CANCELAR</button>
                        </div>
                    </div>
                    <div class="col-md-12 px-2 d-none" id="btnRest">
                        <div class="text-center">
                            <button type="button" id="btnRestaurar" class="btn btn-primary" disabled="true">RESTAURAR</button>
                        </div>
                    </div>
                </div>
            </form>
            </div>
            <div class="clearfix"></div>
            <div class="col-12 border">
                <div class="text-danger d-inline-block h4">NOTA:</div>
                <div class="d-inline-block text-muted h5">Para eliminar o modificar un marker necesitas seleccionarlo.
                </div>
            </div>
        </div>
    </div>
            <br>
        </div>
@endsection