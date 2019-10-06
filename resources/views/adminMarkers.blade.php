@extends('layouts.app')

@section('content')

    <script type="text/javascript">
        let mensaje=(titulo,texto)=>{
            swal({
                title: titulo,
                text: texto,
                icon: 'success',
                timer: 10000,
                confirmButtonColor: "#FF6B5C",
                showConfirmButton: true,
            });
        }
        $(document).ready(function() {
            $("#agregar").on('click', function(event) {
                // if($("#opcion").val()=='1'){
                    mensaje('Estación nueva','Estacion agregada con exito');
               //     $("#opcion").val('0')
                // }else{
                //  mensaje('ERROR','error al agregar');
                // }
                $("#modificar").attr('disabled',false);
                $("#eliminar").attr('disabled',false);
                $(this).attr('disabled',true);
            });
            $("#modificar").on('click', function(event) {
                mensaje('Estaciones modificadas exitosamente','Estacion modificada con exito');
                $("#modificar").attr('disabled',true);
                $("#eliminar").attr('disabled',true);
                $("#cancelar").attr('disabled',true);
                $(this).attr('disabled',true);
            });
            $("#eliminar").on('click', function(event) {
                // if($("#opcion").val()=='1'){
                    mensaje('Estación eliminada exitosamente','Estacion eliminada con exito');
                //   $("#opcion").val('0');
                // }else{
                //  mensaje('ERROR','error al eliminar');
                // }
                $("#modificar").attr('disabled',true);
                $("#eliminar").attr('disabled',true);
                $("#cancelar").attr('disabled',true);
                $(this).attr('disabled',true);
            });
            $("#nuevo").on('click', function(event) {
                $("#agregar").attr('disabled',false);
                $("#cancelar").attr('disabled',false);
                $("#modificar").attr('disabled',true);
                $("#eliminar").attr('disabled',true);
                $("#estacion").val("1")
                $("#nombre").val("")
                $("#descripcion").val("")
            });
        });

        
    </script>
        <div align="center">

                <div class="container-fluid ">
        <div class="row">
            <div class="col-8 border">
                <br>
                <div class="clearfix"></div>                
                <iframe src="{{ url('mapas') }}" frameborder="0px" height="500px" width="100%">
                </iframe>

            </div>

            <div class="col-md-4 border">
                <div class="row">
                    <div class="col-md-12 px-5">
                        <input type="hidden" id="opcion">
                        <div class="form-group">
                            <label>Estación:</label>
                            <select class="form-control" id="estacion">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12 px-5" >
                        <div class="form-group">
                            <label>Nombre:</label>
                            <input type="text" class="form-control" id="nombre">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12 px-5" >
                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea class="form-control" id="descripcion"></textarea>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12 px-4">
                        <div class="text-center">
                            <button id="nuevo" class="btn btn-primary" >NUEVO</button>
                            <button id="agregar" class="btn btn-success" disabled="true">AGREGAR</button>
                            <button id="modificar" class="btn btn-secondary" disabled="true">MODIFICAR</button>
                            <ul></ul>
                            <button id="eliminar" class="btn btn-danger" disabled="true">ELIMINAR</button>
                            <button id="cancelar" class="btn btn-danger" disabled="true">CANCELAR</button>
                        </div>
                    </div>
                </div>
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