@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Ver distribuidor
                {{-- @can('tiendas-list') --}}
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('distribuidores.index') }}"> Regresar</a>
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
            <form action="{{ route('distribuidores.update', $distribuidores->id) }}" method="POST">
                @csrf
                <div class="row">

                    <div class="col-md-6">
                        <h4 class="sub-title-principal">Datos generales de la cuenta</h4>
                    </div>
                    <div class="col-md-6">
                        <h4 class="sub-title pull-right">ID del usuario ( {{ $distribuidores->id }} )</h4>
                    </div>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Correo electronico* :</strong>
                            <input readonly="true" type="text" name="email" value="{{ $distribuidores->email }}"
                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="Email">
                        </div>
                    </div>

                </div>

                <div class="row">

                    <h4 class="sub-title">Datos generales</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Nombres* :</strong>
                            <input readonly="true" type="text" name="name" value="{{ $distribuidores->name }}"
                                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Nombre">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>1er apellido* :</strong>
                            <input readonly="true" type="text" name="apellido_paterno" value="{{ $distribuidores->apellido_paterno }}" class="form-control"
                                placeholder="1er apellido">
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>2do apellido* :</strong>
                            <input readonly="true" type="text" name="apellido_materno" value="{{ $distribuidores->apellido_materno }}" class="form-control"
                                placeholder="2do apellido">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Número de afiliación* :</strong>
                            <input readonly="true" type="text" name="numero_afiliacion" value="{{ $distribuidores->numero_afiliacion }}" class="form-control"
                                placeholder="Número de afiliación">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Tienda a la que pertenece* :</strong>
                            <input readonly="true" type="text" value="{{ $distribuidores->Tienda ? $distribuidores->Tienda->nombre : 'No Asignada'}}" class="form-control"
                            placeholder="Número de afiliación">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Nombre de la empresa* :</strong>
                            <input readonly="true"  type="text" name="nombre_empresa" value="{{ $distribuidores->nombre_empresa }}" class="form-control"
                                placeholder="Nombre de la empresa">
                        </div>
                    </div>
                </div>


                <div class="row">
                    <h4 class="sub-title">Dirección principal</h4>
                    <hr class="espaciador">

                    {{-- <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Dirección principal:</strong>
                            <input  readonly="true" type="text" name="direccion_principal" value="" id=""
                                class="form-control {{ $errors->has('alias') ? 'is-invalid' : '' }}"
                                placeholder="Direccion Principal">
                        </div>
                    </div> --}}

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Pais:</strong>
                            <input type="hidden" name="pais_id" value="1" class="form-control" placeholder="Pais">
                            <input type="text" readonly="true" name="pais_nombre" value="México"
                                class="form-control" placeholder="Pais">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Estado* :</strong>
                            <input  readonly="true" type="text" readonly="true" value="{{ $estados ? $estados->nombre : ''}}"
                            class="form-control">

                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Municipio* :</strong><br>
                            <input  readonly="true" type="text" readonly="true" value="{{ $municipios ? $municipios->nombre : ''}}"
                            class="form-control">

                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Colonia* :</strong><br>
                            <input type="text" readonly="true" value="{{ $direcciones ? $localidad->nombre : '' }}"
                            class="form-control">
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Código postal* :</strong>
                            <input  readonly="true" type="text" name="codigo_postal" value="{{$direcciones ?  $direcciones->cp : '' }}" id="postal"
                                class="form-control {{ $errors->has('codigo_postal') ? 'is-invalid' : '' }}"
                                placeholder="Código postal">
                        </div>
                    </div>

                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Ciudad* :</strong>
                            <input readonly="true" type="text" name="ciudad" id="ciudad"
                                value="{{  $distribuidores->Direcciones->Localidad->ciudad }}"
                                class="form-control {{ $errors->has('ciudad') ? 'is-invalid' : '' }}"
                                placeholder="Ciudad">
                        </div>
                    </div> --}}
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Calle y número* :</strong>
                            <input  readonly="true" type="text" name="calle_numero"
                                value="{{ $direcciones ? $direcciones->calle : '' }}"
                                class="form-control {{ $errors->has('calle_numero') ? 'is-invalid' : '' }}"
                                placeholder="Calle Numero">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <h4 class="sub-title">Contacto</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Movil* :</strong>
                            <input  readonly="true" type="text" name="celular" value="{{ $distribuidores->celular }}"
                                class="form-control {{ $errors->has('celular') ? 'is-invalid' : '' }}"
                                placeholder="Celular">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Teléfono* :</strong>
                            <input   readonly="true" type="text" name="telefono_fijo" value="{{ $distribuidores->telefono_fijo }}"
                                class="form-control {{ $errors->has('telefono_fijo') ? 'is-invalid' : '' }}"
                                placeholder="Teléfono fijo">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <h4 class="sub-title">Datos fiscales</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>RFC* :</strong>
                            <input  readonly="true" type="text" name="rfc" value="{{ $distribuidores->rfc }}" class="form-control" placeholder="RFC">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Régimen fiscal* :</strong>
                            <input   readonly="true" type="text" name="telefono_fijo" value="{{ $distribuidores->regimen_fiscal }}"
                            class="form-control {{ $errors->has('telefono_fijo') ? 'is-invalid' : '' }}"
                            placeholder="Teléfono fijo">

                        </div>
                    </div>
                </div>


                {{-- <div class="row">
                    <h4 class="sub-title">Condiciones de crédito</h4>
                    <hr class="espaciador">
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <strong>Descuento distribuidor/vendedor (%):</strong>
                            <input  readonly="true" type="text" name="descuento" value="{{ $distribuidores->descuento }}"
                                class="form-control" placeholder="Descuento">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <strong>Días de credito:</strong>
                            <input  readonly="true" type="text" name="dia_credito" value="{{ $distribuidores->dia_credito }}" class="form-control"
                                placeholder="Días de credito">
                        </div>
                    </div> --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Descuento de outlet (%):</strong>
                            <input  readonly="true" type="text" name="descuento_outlet" value="{{ $distribuidores->descuento_outlet }}" class="form-control"
                                placeholder="Descuento de outlet" readonly>
                        </div>
                    </div>                     --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="form-group">
                            <strong>Límite de crédito ($) :</strong>
                            <input  readonly="true" type="text" name="credito" value="@money($distribuidores->credito)"
                                class="form-control miles" placeholder="Credito">
                        </div>
                    </div> --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Descuento de ofertas (%):</strong>
                            <input  readonly="true" type="text" name="descuento_oferta" value="{{ $distribuidores->descuento_oferta }}" class="form-control"
                                placeholder="Descuento de ofertas" readonly>
                        </div>
                    </div> --}}
                {{-- </div> --}}

                <div class="row">
                    <h4 class="sub-title">Afiliaciones y Días de devolución</h4>
                    <hr class="espaciador">

                    <div class="col-xs-12 col-sm-12 col-md-3">
                        <div class="form-group">
                            <strong>Afiliaciones disponibles:</strong>
                            <input readonly type="number" min="0" name="cuentas_restantes" value="{{ $distribuidores->cuentas_restantes }}" class="form-control" placeholder="Cuenta restantes">
                            {{-- <span style="color:red;">Número de afiliaciones creadas: {{ $distribuidores->cuentas_creadas }}</span> --}}
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-3">
                        <div class="form-group">
                            <strong>Afiliaciones creadas:</strong>
                            <input readonly type="number" min="0" name="cuentas_creadas" value="{{ $distribuidores->cuentas_creadas }}" class="form-control" placeholder="Cuenta creadas">
                            {{-- <span style="color:red;">Número de afiliaciones creadas: {{ $distribuidores->cuentas_creadas }}</span> --}}
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <strong>Días de devolución :</strong>
                            <input readonly="true" type="number" min="0"  name="dias_devolucion" value="{{ $distribuidores ? $distribuidores->dias_devolucion : '' }}" class="form-control" placeholder="Días de devolución">
                        </div>
                    </div>

                </div>


                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <h4 class="sub-title">Observaciones</h4>
                        <div class="form-group">
                            <textarea readonly="true" name="observaciones" class="form-control" id="exampleFormControlTextarea1" rows="3">{{ $distribuidores->observaciones }}</textarea>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="col-md-5">
                            <div class="contenedor-select">
                                <label class="text-md-right" ><span class="requerido">* </span>
                                    Bloquear pedidos</label>
                                    <label class="switch">
                                        <input type="checkbox" name="bloquear_pedido" disabled="disabled"
                                            @if($distribuidores->bloqueo_pedido == 1) checked="true" @endif>
                                        <span class="read-switch slider round"></span>
                                    </label>
                            </div>
                            <span><small>OFF / ON swich para bloqueos de pedidos del distribuidor</small></span>

                        </div>

                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="col-md-5">
                            <div class="contenedor-select">
                                <label class="text-md-right" for="color_1"><span class="requerido">* </span>
                                    Activación de distribuidor</label>
                                <label class="switch">
                                    <input type="checkbox" name="estatus" disabled="disabled"
                                        @if($distribuidores->estatus == 1) checked="true" @endif>
                                    <span class="read-switch slider round"></span>
                                </label>
                            </div>
                            <span><small>OFF / ON swich para activacion o desactivacion de distribuidor por defecto se creara el usuario activo</small></span>

                        </div>

                    </div>
                </div>




            </form>
        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection


