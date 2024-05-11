@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Productos Gestionables</h3>
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
                        <th>Pedido</th>
                        <th class="text-center">Cantidad por surtir</th>
                        <th>Fecha</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productosGestion as $productoGestion)
                        <tr>
                            <td>{{ $productoGestion->id }}</td>
                            <td>{{ $productoGestion->product->codigo }} {{ $productoGestion->product->linea }}</td>
                            <td>{{ $productoGestion->user->numero_afiliacion }} {{ $productoGestion->user->name }} {{ $productoGestion->user->apellido_paterno }}</td>
                            <td>{{ $productoGestion->tienda->nombre }}</td>
                            <td > <span class="label label-primary">N° {{ $productoGestion->pedido->id }}</span> </td>
                            <td class="text-center">{{ $productoGestion->cantidad }}</td>
                            <td>{{ $productoGestion->created_at }}</td>
                            <td><a href="" class="link">Pasar a pedido</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection
