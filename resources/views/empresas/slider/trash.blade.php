@extends('layouts.app')
@section('css')
<style media="screen" type="text/css">
    .panel {
        width: 99% !important;

    }
</style>
@stop
@section('contenido')
 @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif(session()->has('error'))
    <div class="alert alert-danger">
            {{ session()->get('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
    </div>
    @endif
   <div class="panel panel-default">
    <div class="panel-heading">
        <h4>
          {{ trans('empresas.titulo_panel_papelera_slider') }}
           <span class="float-r"><a class="boton-trash" href="{{ route('slider') }}" title=""><i class="fas fa-undo-alt"></i> {{ trans('empresas.titulo_panel_catalogo_slider') }}</a></span>
        </h4>
    </div>
    <div class="panel-body">
         <table id="empresas" class="table display responsive nowrap" style="width:100%">
                    <thead>
                    <tr>
                        <th>N°</th>
                        <th>{{ trans('usuarios.th_titulo_es') }}</th>
                        <th>{{ trans('usuarios.th_titulo_en') }}</th>
                        <th>{{ trans('usuarios.th_descripcion_es') }}</th>
                        <th>{{ trans('usuarios.th_descripcion_en') }}</th>
                        <th>{{ trans('usuarios.th_empresa') }}</th>
                        <th>{{ trans('usuarios.th_accion') }}</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($find as $data)
                        <tr>
                            <td>{{ $data->getID() }}</td>
                            <td>{{ $data->getTituloEs() }}</td>
                            <td>{{ $data->getTituloEn() }}</td>
                            <td>{{ $data->getDescripcionEs() }}</td>
                            <td>{{ $data->getDescripcionEn() }}</td>
                            <td>{{ $data->EmpresaData()->getNombre() }}</td>
                            <td>
                            <a class="restaurar-link" href="{{ route('sliderRestore', ['id' => $data->getID()]) }}" title="{{ trans('empresas.th_editar') }}">{{ trans('usuarios.th_restaurar') }}</a>
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
