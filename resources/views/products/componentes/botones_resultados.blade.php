
<div class="botones-resultados">
    {!! $tabla->appends([
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'estilo' => $estilo,
            'estatus' => $estatus,
        ])->links() !!}

    <div class="row">
        <div class="col-sm-6">
            <p>Mostrando {{ $tabla->firstItem() }}-{{ $tabla->lastItem() }} de
                {{ $tabla->total() }} resultados</p>
        </div>
    </div>
</div>
