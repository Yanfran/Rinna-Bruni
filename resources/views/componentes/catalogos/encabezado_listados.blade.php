
<div class="panel-heading">

    <h3>Catálogo de {{ $ruta }}
        @can($ruta . '-create')
            <span class="float-r"><a class="btn btn-success btn-catalogo" href="{{ route($ruta . '.create') }}" title=""><i
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
