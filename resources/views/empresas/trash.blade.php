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
          {{ trans('empresas.titulo_panel_papelera_empresa') }}
           <span class="float-r"><a class="boton-trash" href="{{ route('empresas.index') }}" title=""><i class="fas fa-undo-alt"></i> {{ trans('empresas.titulo_panel_catalogo_empresa') }}</a></span>
        </h4>
    </div>
    <div class="panel-body">
         <table id="empresas" class="table display responsive nowrap" style="width:100%">
                    <thead>
                    <tr>
                    <th data-priority="1">{{ trans('empresas.th_logo') }}</th>
                    <th>{{ trans('empresas.th_nombre') }}</th>
                    <th>{{ trans('empresas.th_email') }}</th>
                    <th>{{ trans('empresas.th_color_1') }}</th>
                    <th>{{ trans('empresas.th_color_2') }}</th>
                    <th>{{ trans('empresas.th_direccion') }}</th>
                    <th>{{ trans('empresas.th_telefonos') }}</th>
                    <th>{{ trans('empresas.th_estatus') }}</th>
                    <th data-priority="2">{{ trans('empresas.th_accion') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($find as $data)
                        <tr>
                            <td><img width="35px" src="{{ asset('uploads/logos/'.$data->getLogo()) }}" alt=""></td>
                            <td>{{ $data->getNombre() }}</td>
                            <td>{{ $data->getEmail() }}</td>
                            <td>{{ $data->getColorPrimario() }}<span class="colores_tabla circulo" style="background: {{ $data->getColorPrimario() }}">e</span></td>
                            <td>{{ $data->getColorSecundario() }}<span class="colores_tabla circulo" style="background: {{ $data->getColorSecundario() }}">e</span></td>
                            <td>{{ $data->getDireccion() }}</td>
                            <td>{{ $data->getTelefonos() }}</td>
                            <td>{{ $data->getEstatus() }}</td>
                            <td>
                            <a class="restaurar-link" href="{{ route('empresaRestore', ['id' => $data->getID()]) }}" title="{{ trans('empresas.th_editar') }}">{{ trans('usuarios.th_restaurar') }}</a>
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
