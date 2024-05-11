@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Catálogo cupon
              @can('cupons-create')
                    <span class="float-r"><a class="btn btn-success btn-catalogo" href="{{ route('cupons.create') }}" title=""><i
                                class="fas fa-plus"></i>
                            {{ trans('general.btn_nuevo') }}</a></span>
                @endcan
            </h3>

        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="panel-body">
            <table id="empresas" class="table table-striped responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Codigo</th>
                        <th class="text-center">Tipo de cupon</th>
                        <th class="text-center">Cantidad de usos</th>
                        <th class="text-center">Estatus</th>
                        <th width="180px" class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cupons as $data)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td class="text-center">{{ $data->nombre }}</td>
                            <td class="text-center">{{ $data->codigo }}</td>
                            <td class="text-center">{{ $data->getTipoCuponAttribute() }}</td>
                            <td class="text-center">{{ $data->cantidad_usos }}</td>

                            <td class="text-center"><span class="{{ $data->getCSS() }}">{{ $data->getEstatus() }}</span></td>
                            <td class="text-center">

                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Acción <span class="caret"></span>
                                    </button>
                                    <div class="dropdown-menu drop-custome dropdown-menu-right">
                                        <div class="row">

                                            @can('cupons-list')
                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block" href="{{ route('cupons.show', $data->id) }}">Ver</a>
                                            </div>
                                            <div class="linea"></div>
                                            @endcan

                                            @can('cupons-edit')
                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block" href="{{ route('cupons.edit', $data->id) }}">Editar</a>
                                            </div>
                                            <div class="linea"></div>
                                            @endcan

                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            {!! $cupons->links() !!}

        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection
