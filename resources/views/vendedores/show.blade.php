@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Ver vendedor
                {{-- @can('tiendas-list')  --}}
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('vendedores.index') }}"> Regresar</a>
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

                <div class="row">
                    <div class="col-md-6">
                        <h4 class="sub-title-principal">Datos generales de la cuenta</h4>
                    </div>
                    <div class="col-md-6">
                        <h4 class="sub-title pull-right">ID del usuario ( {{ $vendedores->id }} )</h4>
                    </div>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Correo electronico* :</strong>
                            <input type="text" value="{{ $vendedores->email }}" class="form-control" readonly>
                        </div>
                    </div>                                   
                </div>

                <div class="row">
                        <input type="hidden" name="tipo" class="form-control" value="2">
                        <h4 class="sub-title">Datos generales</h4>
                        <hr class="espaciador">                        
                        
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>Nombres* :</strong>
                                <input type="text"  value="{{ $vendedores->name }}" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>1er apellido* :</strong>
                                <input type="text" name="1er apellido" value="{{ $vendedores->apellido_paterno }}" class="form-control" readonly>
                            </div>
                        </div>                                                


                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>2do apellido* :</strong>
                                <input type="text" name="" value="{{ $vendedores->apellido_materno }}"
                                    class="form-control"
                                    placeholder="2do apellido" readonly>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>Número de afiliación* :</strong>
                                <input type="text" value="{{ $vendedores->numero_afiliacion }}" name=""
                                    class="form-control"
                                    placeholder="Número de afiliación" readonly>
                            </div>
                        </div>


                        
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>Tienda a la que pertenece* :</strong>
                                <input type="text" value="{{ $tiendas ? $tiendas->nombre : '' }}" class="form-control" readonly>                                
                            </div>
                        </div>                  

                        
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>Distribuidor asociado* :</strong>

                                <select disabled class="form-control" name="distribuidor_id">
                                    <option selected value="">Seleccione un distribuidor</option>
                                    @foreach ($distribuidores as $distribuidor)
                                        <option value="{{ $distribuidor->id }}"
                                            {{ $vendedores->distribuidor_id == $distribuidor->id ? 'selected' : '' }}>
                                            {{ $distribuidor->name }} {{ $distribuidor->apellido_paterno }}
                                            {{ $distribuidor->apellido_materno }} - {{ $distribuidor->numero_afiliacion }}
                                        </option>
                                    @endforeach
                                </select>                                                                
                            </div>
                        </div>                      
                        
                        
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>Fecha de nacimiento :</strong>
                                <input type="text" value="{{ $vendedores->fecha_nacimiento }}" class="form-control" readonly>
                            </div>
                        </div>                                             
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>Fecha de ingreso :</strong>
                                <input type="text" value="{{ $vendedores->fecha_ingreso }}" class="form-control" readonly>
                            </div>
                        </div>
                    
                        
                </div>

                <div class="row">
                    <h4 class="sub-title">Dirección principal</h4>
                    <hr class="espaciador">   

                    @if($direcciones->alias)
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div id="selectDomicilio">
                            <div class="form-group">
                                <strong>Domicilio :</strong>                                
                                <input type="text" id="domicilio_input" class="form-control" name="domicilio_name" value="{{ $direcciones->alias }}" readonly="true">                                
                            </div>
                        </div>
                    </div>
                    @endif

                    <div style="" class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Pais:</strong>                            
                            <input type="text" value="México" class="form-control" readonly="true">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Estado:</strong>
                            <input type="text" value="{{ $estados ? $estados->nombre : '' }}" class="form-control" readonly>
                        </div>
                    </div>             
                    
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Municipio:</strong>
                            <input type="text" value="{{  $municipios ? $municipios->nombre : '' }}" class="form-control" readonly>
                        </div>
                    </div>        
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Colonia:</strong>
                            <input type="text" value="{{ $localidad ? $localidad->nombre : '' }}" class="form-control" readonly>
                        </div>
                    </div>             
                                  


                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Código postal* :</strong>
                            <input type="text" value="{{ $direcciones ? $direcciones->cp : '' }}"  class="form-control" readonly>
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Calle y número* :</strong>
                            <input type="text" value="{{ $direcciones ? $direcciones->calle : '' }}" class="form-control" readonly>
                        </div>
                    </div>



                </div>

                <div class="row">
                    <h4 class="sub-title">Contacto</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Movil* :</strong>
                            <input type="text" value="{{ $vendedores->celular }}" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Teléfono* :</strong>
                            <input type="text" value="{{ $vendedores->telefono_fijo }}" class="form-control" readonly>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <h4 class="sub-title">Datos fiscales</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>RFC* :</strong>
                            <input type="text" name="" value="{{ $vendedores->rfc }}"
                                class="form-control"
                                placeholder="RFC" readonly>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Régimen fiscal* :</strong>
                            <input type="text" name="" value="{{ $vendedores->regimen_fiscal }}"
                                class="form-control"
                            placeholder="Régimen fiscal" readonly>                            
                        </div>
                    </div>

                </div>

                {{-- <div class="row">
                    <h4 class="sub-title">Condiciones de crédito</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Descuento distribuidor/vendedor (%):</strong>
                            <input type="text" value="{{ $vendedores->descuento }}" class="form-control" readonly>
                        </div>
                    </div> --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <strong>Días de credito:</strong>
                            <input type="text" name="" value="{{ $vendedores->dia_credito }}"
                                class="form-control" placeholder="Días de credito" readonly>
                        </div>
                    </div> --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Descuento de ofertas:</strong>
                            <input type="text" name="" value="{{ $vendedores->descuento_oferta }}"
                                class="form-control" placeholder="Descuento de ofertas" readonly>
                        </div>
                    </div> --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <strong>Límite de crédito ($) :</strong>
                            <input  readonly="true" type="text" name="credito" value="@money($vendedores->credito)"
                                class="form-control miles" placeholder="Credito">                            
                        </div>
                    </div> --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Descuento de outlet:</strong>
                            <input type="text" name="" value="{{ $vendedores->descuento_outlet }}"
                                class="form-control" placeholder="Descuento de outlet" readonly>
                        </div>
                    </div> --}}

                {{-- </div>  --}}
                
                {{-- <div class="row">
                    <h4 class="sub-title">Descuento ofrecido a clientes:</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Descuento ofrecido a clientes :</strong>
                            <input  type="number" name="descuento_clientes" value="{{ $vendedores->descuento_clientes }}"
                                class="form-control"
                                readonly>
                        </div>
                    </div>                    
                </div> --}}

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <h4 class="sub-title">Observaciones</h4>
                        <div class="form-group">                            
                            <textarea name="observaciones" class="form-control" id="exampleFormControlTextarea1" rows="3" readonly>{{ $vendedores->observaciones }}</textarea>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="col-md-5">
                            <div class="contenedor-select">
                                <label class="text-md-right" for="color_1"><span class="requerido">* </span>
                                    Activación de vendedor</label>
                                <label class="switch">
                                    <input type="checkbox" name="estatus" disabled="disabled"
                                        @if($vendedores->estatus == 1) checked="true" @endif>
                                    <span class="read-switch slider round"></span>
                                </label>
                            </div>        
                        </div>    
                    </div>
                </div>


            </div>
        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection
