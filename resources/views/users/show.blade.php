@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Ver usuarios
                {{-- @can('tiendas-list') --}}
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('users.index') }}"> Regresar</a>
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
                    <h4 class="sub-title pull-right">ID del usuario ( {{ $user->id }} )</h4>
                </div>
                <hr class="espaciador">
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Nombres* :</strong>
                        <input type="text" value="{{ $user->name }}" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>1er apellido* :</strong>
                        <input type="text" value="{{ $user->apellido_paterno }}" class="form-control" readonly>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>2do apellido :</strong>
                        <input type="text" value="{{ $user->apellido_materno }}" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Usuario* :</strong>
                        <input type="text" name="usuario" value="{{ $user->usuario }}" class="form-control" readonly>
                    </div>
                </div>


                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Correo electronico* :</strong>
                        <input type="text" value="{{ $user->email }}" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Número de empleado* :</strong>
                        <input type="text" value="{{ $user->numero_afiliacion }}" class="form-control" readonly>
                    </div>
                </div>


                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Tienda a la que pertenece* :</strong>
                        <input type="text" value="{{ $user->Tienda ? $user->Tienda->nombre : '' }}" class="form-control" readonly>
                    </div>
                </div>


                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Fecha de ingreso :</strong>
                        <input type="text" value="{{ $user->fecha_ingreso }}" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Fecha de nacimiento :</strong>
                        <input type="text" value="{{ $user->fecha_nacimiento }}" class="form-control" readonly>
                    </div>
                </div>


                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Tipo de usuario* :</strong>
                        <input type="text" class="form-control" value="{{ implode(', ', $user->roles->pluck('name')->toArray()) }}" readonly>
                    </div>
                </div>
            </div>


            <div class="row">
                <h4 class="sub-title">Dirección</h4>
                <hr class="espaciador">
                <div style="" class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Pais:</strong>
                        <input type="text" value="México" class="form-control" readonly="true">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Estado:</strong>
                        <input type="text" value="{{ $user->Direcciones ? $user->Direcciones->Estado->nombre : ''  }}" class="form-control" readonly>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Municipio:</strong>
                        <input type="text" value="{{ $user->Direcciones ? $user->Direcciones->Municipio->nombre : '' }}" class="form-control"
                            readonly>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Colonia:</strong>
                        <input type="text" value="{{ $user->Direcciones ? $user->Direcciones->Localidad->nombre : ''}}" class="form-control"
                            readonly>
                    </div>
                </div>



                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Código postal* :</strong>
                        <input type="text" value="{{ $user->Direcciones ? $user->Direcciones->cp : '' }}" class="form-control" readonly>
                    </div>
                </div>

                {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Colonia* :</strong>
                        <input type="text" value="{{ $user->Direcciones ? $user->Direcciones->colonia : ''}}" class="form-control"
                            readonly>
                    </div>
                </div> --}}
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Calle y número* :</strong>
                        <input type="text" value="{{ $user->Direcciones ? $user->Direcciones->calle : '' }}" class="form-control"
                            readonly>
                    </div>
                </div>
            </div>

            <div class="row">
                <h4 class="sub-title">Contacto</h4>
                <hr class="espaciador">
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Movil* :</strong>
                        <input type="text" data-mask="9999-999-9999" value="{{ $user->celular }}" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Teléfono* :</strong>
                        <input type="text" data-mask="99-9999-9999" value="{{ $user->telefono_fijo }}" class="form-control" readonly>
                    </div>
                </div>
            </div>


            <div class="row">
                <h4 class="sub-title">Condiciones de crédito</h4>
                <hr class="espaciador">
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Descuento empleado (%) :</strong>
                        <input type="text" value="{{ $user->descuento }}" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Límite de crédito ($) :</strong>
                        <input type="text" value="{{ $user->credito }}" class="form-control miles" readonly>
                    </div>
                </div>
            </div>


            <div class="row">
                <h4 class="sub-title">Observaciones</h4>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <textarea name="observaciones" class="form-control" id="exampleFormControlTextarea1" rows="3" readonly>{{ $user->observaciones }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="col-md-5">
                        <div class="contenedor-select">
                            <label class="text-md-right" for="color_1"><span class="requerido">* </span>
                                Activación de usuario de sistema</label>
                            <label class="switch">
                                <input type="checkbox" name="estatus" disabled="disabled"
                                    @if($user->estatus == 1) checked="true" @endif>
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
