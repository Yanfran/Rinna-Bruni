@extends('layouts.app')

@section('contenido')
    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Productos
                @can('product-create')
                {{-- <span class="float-r"><a style="pointer-events: none;" class="btn btn-success btn-catalogo" href="{{ route('products.create') }}"
                        title="" @disabled(true)><i class="fas fa-plus"></i>
                        Crear producto</a></span> --}}

                <span class="float-r"><a class="btn btn-success btn-catalogo" href="{{ route('products.create') }}"
                    title=""><i class="fas fa-plus"></i>
                    Crear producto</a></span>
                @endcan
            </h3>

        </div>


        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif

        <div class="panel-body">

            {{-- <div class="row">
                <div class="col margin-tb">
                    <div class="pull-left">
                        <div class="form-group">
                            <select class="form-control" id="exampleFormControlSelect1">
                            <option>Activos</option>
                            <option>Inactivos</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col margin-tb">
                </div>
            </div> --}}

            @include('products.componentes.formulario_busqueda', [
                'ruta' => 'products',
                'placeholder' => 'Nombre del producto',
                'perPage' => $perPage,
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'estilo' => $estilo,
                'estatus' => $estatus,
                'temporadas' => $temporadas,
                'temporadaId' => $temporadaId
            ])

            {{-- <form class="contenedor-busqueda" method="GET" action="{{ route('products.index') }}">
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                            value="{{ $estilo }}" placeholder="algo">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="estatus">Temporada:</label>
                        <select class="form-control" id="temporada" name="temporada">

                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="estatus">Estatus:</label>
                        <select class="form-control" id="estatus" name="estatus">
                            <option value="" {{ $estatus == '' ? 'selected' : '' }}>Todos</option>
                            <option value="1" {{ $estatus == '1' ? 'selected' : '' }}>Activos</option>
                            <option value="0" {{ $estatus === '0' ? 'selected' : '' }}>Inactivos</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="sortOrder">Orden:</label>
                        <select class="form-control" id="sortOrder" name="sortOrder">
                            <option value="asc" {{ $sortOrder == 'asc' ? 'selected' : '' }}>Ascendente</option>
                            <option value="desc" {{ $sortOrder == 'desc' ? 'selected' : '' }}>Descendente</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="perPage">Mostrar:</label>
                        <select name="perPage" id="perPage" class="form-control">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2 pull-right text-right botones-buscar">
                        <button type="submit" class="btn btn-primary buscar-filtro">Buscar</button>
                        <a href="{{ route('products.index') }}" class="btn btn-default buscar-filtro-2">Limpiar</a>
                    </div>
                </div>
            </form> --}}


            <hr>

            <table class="table table-striped">
                <tr>
                    <th width="10%">Codigo</th>
                    <th width="10%">Estilo</th>
                    <th width="10%">Línea</th>
                    <th width="10%">Marca</th>
                    {{-- <th>Color</th>
                    <th>Talla</th> --}}
                    {{-- <th>Costo bruto</th> --}}
                    <th>Nombre corto</th>
                    <th>Temporada</th>
                    {{-- <th>Stock</th> --}}
                    <th class="text-right">Precio</th>
                    <th class="text-center">Acción</th>
                </tr>
                @foreach ($products as $product)
                <tr>
                    <td>{{ $product->codigo }}</td>
                    <td>{{ $product->estilo }}</td>
                    <td>
                        @isset($product->linea)
                            {{ $product->linea->nombre }}
                        @endisset
                    </td>
                    <td>
                        @isset($product->marca)
                            {{ $product->marca->nombre }}
                        @endisset
                    </td>
                    <td>
                        {{ $product->nombre_corto }}
                        {{-- @isset($product->descripcion)
                            {{ $product->descripcion->nombre }}
                        @endisset --}}
                    </td>
                    {{-- <td>{{ $product->color }}</td>
                    <td>{{ $product->talla }}</td> --}}
                    <td>
                        @isset($product->temporada)
                            {{ $product->temporada->nombre }}
                        @endisset
                    </td>
                    {{-- <td>{{ $product->costo_bruto }}</td> --}}
                    <td class="text-right">${{ $product->precio }}</td>

                    {{-- <td>1</td> --}}
                    <td>

                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Acción <span class="caret"></span>
                            </button>
                            <div class="dropdown-menu drop-custome dropdown-menu-right">
                                <div class="row">

                                    @can('product-list')
                                    <div class="col-md-12">
                                        <a class="btn btn-info-custome btn-block" href="{{ route('products.show',$product->id) }}">Ver</a>
                                    </div>
                                    <div class="linea"></div>
                                    @endcan

                                    @can('product-edit')
                                    <div class="col-md-12">
                                        <a class="btn btn-info-custome btn-block" href="{{ route('products.edit',$product->id) }}">Editar</a>
                                    </div>
                                    <div class="linea"></div>
                                    @endcan

                                    {{-- @can('product-delete')
                                    <div class="col-md-12">
                                        <a class="btn btn-info-custome btn-block" href="#">Eliminar</a>
                                    </div>
                                    @endcan  --}}

                                </div>
                            </div>
                        </div>

                        {{-- <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                            <a class="btn btn-info" href="{{ route('products.show',$product->id) }}">Ver</a>
                            @can('product-edit')
                            <a class="btn btn-primary" href="{{ route('products.edit',$product->id) }}">Editar</a>
                            @endcan


                            @csrf
                            @method('DELETE')
                            @can('product-delete')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                            @endcan
                        </form> --}}
                    </td>
                </tr>
                @endforeach
            </table>
            @include('products.componentes.botones_resultados', [
                'tabla' => $products,
                'perPage' => $perPage,
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'estilo' => $estilo,
                'estatus' => $estatus,
            ])
        </div>
    </div>




<p class="text-center text-primary"><small>-</small></p>
@endsection
