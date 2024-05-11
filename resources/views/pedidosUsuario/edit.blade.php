@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Editar pedidos
                {{-- @can('pedido-list') --}}
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('pedidos.index') }}"> Regresar</a>
                </div>
                {{-- @endcan --}}
            </h3>

        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="panel-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Oops!</strong> Hubo algunos problemas con tus datos.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('pedidos.update', $pedido->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row mt-3">                    
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="input-group buscador-pedido">
                        <span class="input-group-addon" id="basic-addon1"><i class="fas fa-search"></i></span>
                        <input type="search" name="nombre_cliente" value="{{ old('nombre_cliente') }}"
                            class="form-control {{ $errors->has('nombre_cliente') ? 'is-invalid' : '' }}" placeholder="Buscar cliente">
                    </div>
                </div>                    
            </div>

            <div class="row mt-3">

                <div class="col-xs-12 col-sm-12 col-md-10">
                    <div class="row">
                        <h4 class="sub-title">Datos del cliente</h4>
                        <hr class="espaciador">
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <h5 class="ml-3">No de afiliación:</h5>                        
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <h5 class="ml-3">Telefono fijo:</h5>                        
                        </div>                    

                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <h5 class="ml-3">Nombre:</h5>                        
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <h5 class="ml-3">Movil:</h5>                        
                        </div>

                        <div class="col-md-6 col-md-offset-6">
                            <h5 class="ml-3">Correo:</h5>                        
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-2">
                    <div class="row">
                        <h4 class="sub-title">Estatus</h4>
                        <hr class="espaciador">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                {{-- <strong>Estatus:</strong> --}}
                                <select style="width: 100%;"
                                    class="form-control"
                                    name="estatus" id="estatus">
                                    <option value="">Estatus</option>
                                </select>                                                                
                            </div>        
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mt-4">
                <h4 class="sub-title">Busqueda de articulo</h4>
                <hr class="espaciador">
                <div>
                    <div class="col-xs-12 col-sm-12 col-md-10">
                        <div class="form-group">
                            {{-- <strong>Tipo de usuario* :</strong> --}}
                            <select name="articulo" id="articulo" class="js-example-basic-multiple form-contro"  multiple>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-2 mt-4">                        
                        <button type="button" class="btn btn-success pull-right save">Agregar</button>                        
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <h4 class="sub-title">Pedido</h4>
                <hr class="espaciador">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="table-responsive" style="height: 250px;">
                        <table id="empresas" class="table table-striped nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Estilo</th>
                                    <th class="text-center">Marca</th>
                                    <th class="text-center">Color</th>
                                    <th class="text-center">Acabado</th>
                                    <th class="text-center">Talla</th>
                                    <th class="text-center">Precio público</th>
                                    <th class="text-center">Precio socio</th>
                                    <th class="text-center">Total existencias</th>
                                    <th class="text-center">Existencia tienda</th>
                                    <th class="text-center">Cantidad solicitada</th>
                                    <th class="text-center">Cantidad pendiente</th>
                                    <th class="text-center">Cancelar</th>
                                </tr>
                            </thead>
                            <tbody>                                                                   
                                <tr>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>                            
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center">                                            
                                        <span class="text-primary total-existencias" data-toggle="modal" data-target="#myModal">13</span>
                                    </td>
                                    <td class="text-center">3</td>
                                    <td class="text-center">9</td>
                                    <td class="text-center">7</td>
                                    <td class="text-center"><i class="fa fa-trash"></i></td>  
                                <tr>                                                                    
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center">Estilo</th>
                                    <th class="text-center">Marca</th>
                                    <th class="text-center">Color</th>
                                    <th class="text-center">Acabado</th>
                                    <th class="text-center">Talla</th>
                                    <th class="text-center">Precio público</th>
                                    <th class="text-center">Precio socio</th>
                                    <th class="text-center">Total existencias</th>
                                    <th class="text-center">Existencia tienda</th>
                                    <th class="text-center">Cantidad solicitada</th>
                                    <th class="text-center">Cantidad pendiente</th>
                                    <th class="text-center">Cancelar</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <h4 class="sub-title">Observaciones del pedido:</h4>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <textarea name="observaciones" class="form-control" id="exampleFormControlTextarea1" rows="5">{{ old('observaciones') }}</textarea>
                    </div>
                </div>
            </div>                


            <div class="row mt-4">
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="row">
                        <h4 class="sub-title">Vales y descuentos</h4>
                        <hr class="espaciador">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Cupón de descuento:</strong>
                                <input type="number" name="descuento" value="" class="form-control"
                                    placeholder="Cupón de descuento">
                            </div>
                        </div>
                    
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Vale a fovor:</strong>
                                <input type="text" name="vale" value="" class="form-control"
                                    placeholder="Vale a fovor">
                            </div>
                        </div>

                    </div>
                        
                    
                    <div class="row">
                        <h4 class="sub-title">Envio</h4>
                        <hr class="espaciador">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Envio - dirección:</strong><br>
                                <select style="width: 100%;"
                                    class="js-example-basic-single form-control"
                                    name="envio_direccion" id="envio_direccion">
                                    <option value="">Seleccione un metodo de envio</option>
                                </select>                                                                
                            </div>                            
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Dirección - Alias:</strong><br>
                                <select style="width: 100%;"
                                    class="js-example-basic-single form-control"
                                    name="dirección_alias" id="dirección_alias">
                                    <option value="">Seleccione un metodo de envio</option>
                                </select>                                                                
                            </div>                            
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-8 mt-5"> 
                            <div class="form-group">
                                <h5>Total de cajas (Para el Admin)</h5>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 mt-5"> 
                            <div class="form-group">
                                <input class="js-example-basic-single form-control" type="number" name="total_cajas" id="total_cajas">
                            </div>                            
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 mt-5"> 
                            <div class="form-group">
                                <p>
                                    Leyenda sobre el manejo de la paqueteria, todo envio 
                                    tiene por default; 1 costo de envio, depende del volumen de compra.
                                </p>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="row">
                        <h4 class="sub-title">Total del pedido</h4>
                        <hr class="espaciador">     
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="table-responsive">                            
                                <table class="table table-striped nowrap" style="width:100%">                                
                                    <tr>
                                        <th>Concepto:</th>
                                        <td>Costo</td>                                    
                                    </tr>
                                    <tr>
                                        <th>Total en articulos:</th>
                                        <td>$0.00</td>                                    
                                    </tr>
                                    <tr>
                                        <th>Cupón de descuento:</th>
                                        <td>$0.00</td>                                    
                                    </tr>
                                    <tr>
                                        <th>Vale a favor:</th>
                                        <td>$0.00</td>                                    
                                    </tr>
                                    <tr>
                                        <th>Paqueteria:</th>
                                        <td>$0.00</td>                                    
                                    </tr>                                
                                    <tr>
                                        <th>Descuento de cliente:</th>
                                        <td>$0.00</td>                                    
                                    </tr>
                                </table>                                                           
                            </div>   
                        </div>                        
                    </div>
                    
                    <div class="row mt-1">
                        <h4 class="sub-title mt-5">Metodo de pago</h4>
                        <hr class="espaciador">                        
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Forma de pago:</strong><br>
                                <select style="width: 100%;"
                                    class="js-example-basic-single form-control"
                                    name="metodo_pago" id="metodo_pago">
                                    <option value="">Seleccione un metodo de pago</option>
                                </select>                                                                
                            </div>                            
                        </div>
                    </div>

                    <div class="row check-terminos">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            
                            <div class="form-group">
                                <div class="contenedor-select">
                                    <label class="switch">
                                        <input type="checkbox" name="estatus" checked="true">
                                        <span class="slider round"></span>
                                    </label>
                                    <label class="text-md-right" for="color_1">
                                        <span class="requerido">* </span>
                                        Aceptar <a href="#">Terminos y Condiciones del servicio</a> 
                                    </label>
                                </div>
                                <span><small>OFF / ON swich para aceptar los terminos terminos</small></span>                                             
                            </div>

                        </div>
                    </div>

                </div>                                                                    
            </div>

            <div class="row row-no-gutters seccion-final">
                <div class="col-xs-12 col-sm-6 col-md-7">                    
                        <button type="button" class="btn btn-warning ml-1 notifi-distribuidor">Eliminar</button>                    
                </div>                
                <div class="col-xs-12 col-sm-6 col-md-5">                    
                    <button type="button" class="btn btn-success ml-1 pull-right distribuidor">Comprar</button>
                    <button type="submit" class="btn btn-primary ml-1 pull-right">Guardar</button>
                    <a class="btn btn-danger pull-right" href="{{ route('pedidos.index') }}">Cancelar</a>                    
                </div>
            </div>

        </form>
        </div>
    </div>

    <!-- Button trigger modal -->      
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Existencias</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="table-responsive" style="">
                            <table id="" class="table table-striped nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sucursal</th>
                                        <th>Existencia en tienda</th>                                    
                                    </tr>
                                </thead>
                                <tbody>                                
                                    <tr>
                                        <td>RB LEON</td>
                                        <td>5</td>                                   
                                    </tr>    
                                    <tr>
                                        <td>RB Guadalajara</td>
                                        <td>10</td>                                   
                                    </tr>     
                                    <tr>
                                        <td>RB Puebla</td>
                                        <td>15</td>                                   
                                    </tr>                                                                                                    
                                </tbody>                        
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>            
            </div>
        </div>
        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                placeholder: 'Selecciona opciones',
                allowClear: true
              });
            $('.js-example-basic-single').select2();          
        });

        $('.distribuidor').on('click', function(e) {                
                e.preventDefault();

                // var form = $(this).closest('form');

                Swal.fire({
                    // title: 'Confirmar eliminación',
                    text: 'El distribuidor presenta deuda, favor de contactar a un administrador',
                    icon: 'info',                    
                    confirmButtonColor: '#3fc3ee',
                    // cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                    // cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            $('.notifi-distribuidor').on('click', function(e) {                
                e.preventDefault();

                // var form = $(this).closest('form');

                Swal.fire({
                    // title: 'Confirmar eliminación',
                    text: 'Se notificará a un distribuidor RB los articulos pendientes, se podrá consultar el estarus de dichos articulos en el siguiente pedido.',
                    icon: 'info',                    
                    confirmButtonColor: '#3fc3ee',
                    // cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                    // cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

    </script>
@stop

