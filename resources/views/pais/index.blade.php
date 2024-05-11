@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Catálogo pais
               {{--  @can('pais-create')
                    <span class="float-r"><a class="btn btn-success btn-catalogo" href="{{ route('pais.create') }}" title=""><i
                                class="fas fa-plus"></i>
                            {{ trans('general.btn_nuevo') }}</a></span>
                @endcan --}}
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
                        <th width="25%" class="text-center">No</th>
                        <th width="30%" class="text-center">Nombre</th>
                        <th width="25%" class="text-center">Estatus</th>
                        <th width="25%" class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pais as $data)
                        <tr>
                            <td class="text-center">{{ ++$i }}</td>
                            <td class="text-center">{{ $data->nombre }}</td>

                            <td class="text-center"><span class="{{ $data->getCSS() }}">{{ $data->getEstatus() }}</span></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Acción <span class="caret"></span>
                                    </button>
                                    <div class="dropdown-menu drop-custome dropdown-menu-right">
                                        <div class="row">
                                            @can('pais-list')
                                            <div class="col-md-12">
                                                <a class="btn btn-info-custome btn-block" href="{{ route('pais.show', $data->id) }}">Ver</a>
                                            </div>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            {!! $pais->links() !!}

        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection
