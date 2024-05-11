@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Ver tiendas
                @can('tiendas-list')
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('tiendas.index') }}"> Regresar</a>
                    </div>
                @endcan
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
                    <h4 class="sub-title-principal">Datos generales de tiendas</h4>
                </div>
                <div class="col-md-6">
                    <h4 class="sub-title pull-right">ID del tienda ( {{ $tienda->id }} )</h4>
                </div>
                <hr class="espaciador">
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Nombre:</strong>
                        <input type="text" value="{{ $tienda->nombre }}" class="form-control label-rinna" readonly>
                    </div>
                </div>
                {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Código de la tienda o identificador:</strong>
                        <input type="text" value="{{ $tienda->codigo }}" class="form-control label-rinna" readonly>
                    </div>
                </div> --}}

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>País:</strong>
                        <input type="text" value="{{ $tienda->Pais->nombre }}" class="form-control label-rinna" readonly>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Estado:</strong>
                        <input type="text" name="usuario" value="{{ $tienda->Estado->nombre }}" class="form-control label-rinna" readonly>
                    </div>
                </div>


                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Municipio:</strong>
                        <input type="text" value="{{ $tienda->Municipio->nombre }}" class="form-control label-rinna" readonly>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Colonia:</strong>
                        <input type="text" value="{{ $tienda->Localidad->nombre }}" class="form-control label-rinna" readonly>
                    </div>
                </div>


                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Código postal:</strong>
                        <input type="text" value="{{ $tienda->cp }}" class="form-control label-rinna" readonly>
                    </div>
                </div>


                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group mt-1">
                        <strong>Calle y número* :</strong><br>
                        <input type="text" name="calle_numero" value="{{ $tienda->calle_numero }}"
                            class="form-control label-rinna"
                            placeholder="Calle Numero" readonly>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <strong>Estatus:</strong>
                        <input type="text" value="{{ $tienda->getEstatus() }}" class="form-control label-rinna" readonly>
                    </div>
                </div>                
                
            </div>

        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection
