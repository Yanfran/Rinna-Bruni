

<form class="contenedor-busqueda" method="GET" action="{{ route($ruta . '.index') }}">
    <div class="form-row">
        <div class="form-group col-md-2">
            <label for="estilo">Estilo:</label>
            <input type="text" class="form-control" id="estilo" name="estilo"
                value="{{ $estilo }}" placeholder="{{ $placeholder }}">
        </div>
        <div class="form-group col-md-2">
            <label for="estatus">Linea:</label>
            <select class="form-control" id="lineaId" name="lineaId">
                <option value="" {{ $lineaId == '' ? 'selected' : '' }}>Todas</option>
                @foreach ($lineas as $key => $linea)
                    <option value="{{ $linea->id }}" {{ $linea->id == $lineaId ? 'selected' : ''}}>{{ $linea->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-2">
            <label for="estatus">Temporada:</label>
            <select class="form-control" id="temporadaId" name="temporadaId">
                <option value="" {{ $temporadaId == '' ? 'selected' : '' }}>Todas</option>
                @foreach ($temporadas as $key => $temporada)
                    <option value="{{ $temporada->id }}" {{ $temporada->id == $temporadaId ? 'selected' : ''}}>{{ $temporada->nombre }}</option>
                @endforeach
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
            <a href="{{ route($ruta . '.index') }}" class="btn btn-default buscar-filtro-2">Limpiar</a>
        </div>
    </div>
</form>
