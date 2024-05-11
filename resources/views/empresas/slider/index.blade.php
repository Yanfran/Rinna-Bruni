@extends('layouts.app')
@section('css')
<style media="screen" type="text/css">
    .panel {
        width: 99% !important;

    }
</style>

@stop
@section('contenido')
<div class="panel panel-default">
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @elseif(session()->has('error'))
    <div class="alert alert-danger">
            {{ session()->get('error') }}
    </div>
    @endif
    <div class="panel-heading">

        <h4>
          {{ trans('empresas.titulo_panel_catalogo_slider') }}

        </h4>


    </div>
    <div class="panel-body">
         <table id="empresas" class="table display responsive nowrap" style="width:100%">
                    <thead>
                    <tr>
                    <th data-priority="1">{{ trans('empresas.th_slider') }}</th>
                    <th>{{ trans('empresas.th_titulo_es') }}</th>
                    <th>{{ trans('empresas.th_titulo_en') }}</th>
                     <th>{{ trans('empresas.th_descripcion_es') }}</th>
                    <th>{{ trans('empresas.th_descripcion_en') }}</th>
                    <th>{{ trans('empresas.th_estatus') }}</th>
                    <th data-priority="2">{{ trans('empresas.th_accion') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($find as $data)
                        <tr>
                            <td><img width="35px" src="{{ asset($data->getImagen()) }}" alt=""></td>
                            <td>{{ $data->getTituloEs() }}</td>
                            <td>{{ $data->getTituloEn() }}</td>
                            <td>{{ $data->getDescripcionEs() }}</td>
                            <td>{{ $data->getDescripcionEn() }}</td>
                            <td>{{ $data->getEstatus() }}</td>
                            <td class="link-botones-catalogo">

                            <a href="{{ route('sliderEdit', ['id' => $data->getID()]) }}" title="{{ trans('empresas.th_editar') }}"><i class="far fa-edit"></i></a>
                            <a href="{{ route('sliderDelete', ['id' => $data->getID()]) }}" title="{{ trans('empresas.th_borrar') }}"><i class="far fa-trash-alt"></i></a>

                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
    </div>
</div>
@stop
@section('js')
<script>

  $(document).ready( function () {

    $('#empresas').DataTable({
            responsive: true,
            columnDefs: [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: -1 }
            ],
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]], //
            searching: true,
            order: [0, 'asc'],
            dom: "Blfrtip",
            displayLength: 10,
            pageLength: 10,
            language: {
                @if(App::getLocale() == 'es')
                "url":"/lang/es/dataTable/datatables",
                @endif
            },


        });
   });
</script>
@stop
