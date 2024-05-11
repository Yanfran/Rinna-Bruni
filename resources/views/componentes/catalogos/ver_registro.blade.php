
<div class="panel panel-default">
    <div class="panel-heading">

        <h3>Ver {{ $ruta }}
            @can($ruta . '-list')
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route($ruta . '.index') }}"> Regresar</a>
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
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Nombre:</strong>
                    {{ $model->nombre }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Estaus:</strong>
                    {{ $model->getEstatus() }}
                </div>
            </div>
        </div>
    </div>
</div>
