@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Catálogo sucursales - Distribuidor: {{ $user->id }}
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('distribuidores.index') }}"> Regresar a distribuidores</a>
            </div>
                {{-- @can('localidad-create') --}}                {{-- @endcan  --}}
            </h3>

        </div>


        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif

        <div class="panel-body">
            @if($limite_cuentas_creadas !== 0 && $estatusTienda !== 0)
                <span class="float-r">
                    <a class="btn btn-success btn-catalogo" href="{{ route('sucursales.create', $user->id) }}" title="">
                        <i class="fas fa-plus"></i>
                        Crear nueva sucursal
                    </a>
                </span>
            @endif

            {{-- <span class="float-r">
                <a class="btn btn-success btn-catalogo" href="{{ route('sucursales.create', $user->id) }}"title="">
                    <i class="fas fa-plus"></i>
                    Crear nueva sucursal
                </a>
            </span> --}}

            <div class="row">
                <div class="col margin-tb">
                    <div class="pull-left">
                        <div class="form-group">
                            <select class="form-control" id="exampleFormControlSelect1">
                                <option>Activos</option>
                                <option>Inactivos</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col margin-tb">
                </div>
            </div>
            <table class="table table-striped">
                <tr>
                    <th>No</th>
                    <th class="text-center">Alias</th>
                    <th class="text-center">Encargado</th>
                    <th class="text-center">Estatus</th>
                    <th class="text-center">Acción</th>
                </tr>
                @foreach ($sucursales as $sucursal)
                    <tr>
                        <td>{{ ++$i }}</td>

                        <td class="text-center">
                            {{ $sucursal->alias }}
                        </td>
                        <td class="text-center">
                            {{ $sucursal->nombre_encargado }}
                        </td>       
                        <td class="text-center"><span class="{{ $sucursal->getCSS() }}">{{ $sucursal->getEstatus() }}</span></td>                        

                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Acción <span class="caret"></span>
                                </button>
                                <div class="dropdown-menu drop-custome dropdown-menu-right">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a class="btn btn-info-custome btn-block" href="{{ route('sucursales.show', $sucursal->id) }}">Ver</a>
                                        </div>
                                        <div class="linea"></div>
                                        <div class="col-md-12">
                                            <a class="btn btn-info-custome btn-block" href="{{ route('sucursales.edit', $sucursal->id) }}">Editar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </table>
            <div class="botones-resultados">


                <div class="row">
                    <div class="col-sm-6">
                        <p>Mostrando  de
                            {{ $sucursales->total() }} resultados</p>
                    </div>
                </div>
            </div>

        </div>

    </div>


    <p class="text-center text-primary"><small>-</small></p>
@endsection
