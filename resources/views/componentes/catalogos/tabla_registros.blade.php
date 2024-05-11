
<table id="registros" class="table table-striped responsive nowrap" style="width:100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Nombre</th>
            <th class="text-center">Estatus</th>
            <th class="text-center">Acción</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tabla as $key => $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->nombre }}</td>
                <td class="text-center"><span class="{{ $item->getCSS() }}">{{ $item->getEstatus() }}</span></td>


                <td class="text-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Acción <span class="caret"></span>
                        </button>
                        <div class="dropdown-menu drop-custome dropdown-menu-right">
                            <div class="row">

                                @can($ruta . '-list')
                                <div class="col-md-12">
                                    <a class="btn btn-info-custome btn-block" href="{{ route($ruta . '.show', $item->id) }}">Ver</a>
                                </div>
                                <div class="linea"></div>
                                @endcan



                                @if($ruta != 'lineas' && $ruta != 'marcas' )

                                    @can($ruta . '-edit')
                                    <div class="col-md-12">
                                        <a class="btn btn-info-custome btn-block" href="{{ route($ruta . '.edit', $item->id) }}">Editar</a>
                                    </div>
                                    <div class="linea"></div>
                                    @endcan

                                    @can($ruta . '-delete')
                                    <div class="col-md-12">
                                        <form action="{{route($ruta . '.destroy',$item->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-info-custome btn-block delete-btn">Eliminar</button>
                                        </form>

                                    </div>
                                    @endcan
                                @endif


                            </div>
                        </div>
                    </div>

                </td>

            </tr>
        @endforeach

    </tbody>
</table>
