@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Productos Negados</h3>
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
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Usuario</th>
                        <th>Tienda</th>
                        <th>Cantidad</th>
                        <th>Estatus</th>
                        <th>Origen</th>
                        <th>Fecha</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productosNegados as $pedidoNegado)
                        <tr>
                            <td>{{ $pedidoNegado->id }}</td>
                            <td>{{ $pedidoNegado->product->codigo }} {{ $pedidoNegado->product->linea }} </td>
                            <td>{{ $pedidoNegado->user->numero_afiliacion}} {{ $pedidoNegado->user->name }} {{ $pedidoNegado->user->apellido_paterno }}</td>
                            <td>{{ $pedidoNegado->tienda->nombre }}</td>
                            <td>{{ $pedidoNegado->cantidad }}</td>
                            <td><span class="badge badge-success">
                                {{ $pedidoNegado->estatus == 0 ? 'Por atender' : 'Otro estatus' }}
                                </span>
                            </td>
                            <td>
                                @if ($pedidoNegado->origen == 'pedido')
                                    <span class="label label-primary">Pedido N° {{ $pedidoNegado->pedido_id }}</span>
                                @else
                                    <span class="label label-success">{{ $pedidoNegado->origen }}</span>
                                @endif

                            </td>
                            <td>{{ $pedidoNegado->created_at }}</td>
                            <td><a href="" class="link">Pasar a pedido</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection
