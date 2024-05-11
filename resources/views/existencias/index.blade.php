@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Catálogo existencia
              @can('existencias-create')
                    {{-- <span class="float-r"><a style="pointer-events: none;" class="btn btn-success btn-catalogo" href="{{ route('existencias.create') }}" title=""  @disabled(true)> --}}
                        <span class="float-r"><a class="btn btn-success btn-catalogo" href="{{ route('existencias.create') }}" title=""  @disabled(true)>
                        <i class="fas fa-plus"></i>
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
                        <th class="text-center">No</th>
                        <th class="text-center">Nombre del producto</th>
                        <th class="text-center">Cantidad global en tiendas</th>
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($existencias as $data)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{  $data->getProduct()->codigo  }}-{{$data->getProduct()->estilo}} @if($data->getProduct()->linea)  {{"-" . $data->getProduct()->linea->nombre }}-{{$data->getProduct()->nombre_corto}} @endif</td>
                            <td class="text-center">{{ $data->getSuma() }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Acción <span class="caret"></span>
                                    </button>
                                    <div class="dropdown-menu drop-custome dropdown-menu-right">
                                        <div class="row">

                                            @can('existencias-list')
                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block" href="{{ route('existencias.show', $data->getProduct()->id) }}">Ver</a>
                                            </div>
                                            <div class="linea"></div>
                                            @endcan
{{--
                                            @can('existencias-edit')
                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block" href="{{ route('existencias.edit', $data->getProduct()->id) }}">Editar</a>
                                            </div>
                                            <div class="linea"></div>
                                            @endcan --}}

                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            {!! $existencias->links() !!}

        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection
