@extends('layouts.app')


@section('css')
    <style>
        .overflow-table-y {
            height: 400px;
            overflow-y: auto;
        }
    </style>
@endsection
@section('contenido')

    <div class="panel panel-default">
        <div class="panel-heading">

            <h3>Ver Catálogo
                @can('catalogos-list')
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('catalogos.index') }}"> Regresar</a>
                    </div>
                @endcan
            </h3>

        </div>

        <div class="panel-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Oops!</strong> Hubo algunos problemas con tus datos.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- <h4>Especificaciones</h4> --}}

            <form action="{{ route('catalogos.update', $catalogo->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group col-xs-12 col-sm-12 col-md-4">
                        <label for="nombre">Nombre del catálogo:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                            placeholder="Escriba un nombre para el catálgo"
                            value="{{ $catalogo->nombre }}" required disabled>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-3">
                        {{-- <div class="form-group">
                            <label>{{ trans('empresas.label_estatus') }}</label>
                            <select name="estatus" class="form-control" id="estatus">
                                <option class="form-control" value="0"
                                    @if ($catalogo->getEstatusValue() == 0) @selected(true) @endif>
                                    {{ trans('empresas.select_inactivo') }}
                                </option>
                                <option @if ($catalogo->getEstatusValue() == 1) @selected(true) @endif class="form-control"
                                    value="1">{{ trans('empresas.select_activo') }}</option>

                            </select>
                        </div> --}}
                    </div>

                </div>

                {{-- <div class="row">

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-9">
                                <h4>Productos del Catálogo</h4>
                            </div>
                            <div class="text-right col-xs-12 col-sm-12 col-md-3">
                                <button id="btnAgregarProductos" type="button" class="btn btn-info" disabled>Agregar Productos</button>
                            </div>

                        </div>


                        <table id="productosCatalogo" class="table table-striped responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="d-none">ID</th>
                                    <th>Estilo</th>
                                    <th>Linea</th>
                                    <th>Tallas</th>
                                    <th>Color</th>
                                    <th>Temporada</th>
                                    <th>Precio Público</th>
                                    <th class="text-center">Borrar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groupedProducts as $key => $item)
                                    <tr>
                                        <td class="d-none">
                                            <input name="productos[]" type="text" value="{{ $item->id }}" readonly>'
                                        </td>
                                        <td>{{ $item->estilo }}</td>
                                        <td>
                                            @if($item->linea)
                                                {{ $item->linea->nombre }}
                                            @endif
                                        </td>
                                        <td>{{ $item->talla}}</td>
                                        <td>{{ $item->color}}</td>
                                        <td>
                                            @if($item->temporada)
                                                {{ $item->temporada->nombre}}
                                            @endif
                                        </td>
                                        <td>{{ $item->precio}}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn" onclick="$(this).closest('tr').remove()" disabled>
                                                <i style="color: red; !important"class="far fa-trash-alt"></i>
                                            </button>
                                            </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div> --}}


                <div class="row">

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-9">
                                <h4>Productos del Catálogo</h4>
                            </div>
                            <div class="text-right col-xs-12 col-sm-12 col-md-3">
                                <button id="btnAgregarProductos" type="button" class="btn btn-info" disabled>Agregar Productos</button>
                            </div>

                        </div>
                        <div class="row form-group">
                            <div class="form-group col-xs-12 col-sm-12 col-md-3 text-left">
                                <input type="text" class="form-control" id="filtroProductosCatalogo"
                                    placeholder="Filtrar Productos"
                                >
                            </div>
                        </div>
                        <table style="padding-bottom: 0; margin-bottom: 0" class="table table-striped responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="d-none">ID</th>
                                    <th style="width:12% !important">Codigo</th>
                                    <th style="width:15% !important">Estilo</th>
                                    <th style="width:15% !important">Linea</th>
                                    <th style="width:10% !important">Tallas</th>
                                    <th style="width:10% !important">Marca</th>
                                    <th style="width:10% !important">Temporada</th>
                                    <th style="width:15% !important" class="text-right">Precio Público</th>
                                    <th style="width:10% !important;"  class="text-center"><u>Borrar</u></th>
                                    <th style="width:2% !important"></th>
                                </tr>
                            </thead>
                        </table>
                        <div style="padding-top:0" class="overflow-table-y">

                            <table style="padding-top:0" id="productosCatalogo" class="table table-striped responsive nowrap" style="width:100%">

                                <tbody>

                                    @foreach ($groupedProducts as $codigo => $item)
                                        <tr>
                                            <td class="d-none">
                                                <input name="productos[]" type="text"
                                                    value="{{ $item['codigo'] && substr($item['codigo'], -1) === '-' ? substr($item['codigo'], 0, -1) : $item['codigo'] }}"
                                                    readonly
                                                >
                                            </td>
                                            <td style="width:10% !important">
                                                    {{ $item['codigo'] && substr($item['codigo'], -1) === '-' ? substr($item['codigo'], 0, -1) : $item['codigo'] }}
                                            </td>
                                            <td style="width:15% !important">{{ $item['estilo'] }}</td>
                                            <td style="width:15% !important">
                                                @if($item['linea'])
                                                    {{ $item['linea']->nombre }}
                                                @endif
                                            </td>
                                            <td style="width:10% !important">
                                                {{-- @foreach ($item['tallas'] as $key => $talla)
                                                    {{trim($talla).( (count($item['tallas'])-1) == $key ? "" : "-")}}
                                                @endforeach --}}
                                                {{$item['tallasString']}}
                                            </td>
                                            <td style="width:10% !important">
                                                @if($item['marca'])
                                                    {{ $item['marca']->nombre}}
                                                @endif
                                            </td>
                                            <td style="width:10% !important">
                                                @if($item['temporada'])
                                                    {{ $item['temporada']->nombre}}
                                                @endif
                                            </td>
                                            <td  style="width:15% !important" class="text-right">{{ $item['precio']}}</td>
                                            <td  style="width:10% !important" class="text-center">
                                                <button type="button" class="btn" disabled>
                                                    <i style="color: red; !important"class="far fa-trash-alt"></i>
                                                </button>
                                            </td>
                                            <td class="d-none">
                                                <input name="ids[]" type="text"
                                                    value="{{$item['idsString']}}"
                                                    readonly
                                                >
                                            </td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>





                        </div>

                    </div>

                </div>
                <hr>

                <div style="padding:10px;border-radius:10px;margin-bottom:20px"  class="content bg-danger">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <h4>Generar PDF</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6">

                            <div class="form-group">
                                <label class="form-label">Imagen Portada</label>
                                <input type="file"
                                       name="portada"
                                       id="portada"
                                       class="form-control validar-img"
                                       accept="image/jpeg, image/png"
                                       data-max-width="1200"   {{-- px --}}
                                       data-max-height="1600" {{-- px --}}
                                       data-max-size="200"    {{-- Kb --}}
                                       data-container-img="preview_portada"
                                       data-container-error="invalidImage"
                                       disabled
                                >
                            </div>
                            <span style="color:red !important;" id="invalidImage" class="d-none text-danger p-3"></span>

                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label class="form-label">Imagen Final</label>
                                <input type="file"
                                       name="portada_final"
                                       id="portada_final"
                                       class="form-control validar-img"
                                       accept="image/jpeg, image/png"
                                       data-max-width="1200"   {{-- px --}}
                                       data-max-height="1600" {{-- px --}}
                                       data-max-size="200"    {{-- Kb --}}
                                       data-container-img="preview_final"
                                       data-container-error="invalidImageFinal"
                                       disabled
                                >
                            </div>
                            <span style="color:red !important;" id="invalidImageFinal" class="d-none text-danger p-3"></i></span>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                @if ($catalogo->url_imagen_portada)
                                    <img style="max-width: 250px"
                                         id="preview_portada"
                                         name="preview_portada"
                                         src="{{ route('storage', ['typeFile' => 'catalogos/' . $catalogo->nameDirectory , 'filename' => $catalogo->url_imagen_portada]) }}"
                                    >
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                @if ($catalogo->url_imagen_final)
                                    <img style="max-width: 250px"
                                         id="preview_final"
                                         name="preview_final"
                                         src="{{ route('storage', ['typeFile' => 'catalogos/' . $catalogo->nameDirectory , 'filename' => $catalogo->url_imagen_final]) }}"
                                    >
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                <div style="padding:10px;border-radius:10px;margin-bottom:20px" class="content bg-warning">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <h4>Aplicación Movil</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6">

                            <div class="form-group">
                                <label class="form-label">Imagen Banner</label>
                                <input type="file"
                                       name="banner"
                                       id="banner"
                                       class="form-control validar-img"
                                       accept="image/jpeg, image/png"
                                       data-max-width="1020"   {{-- px --}}
                                       data-max-height="400" {{-- px --}}
                                       data-max-size="200"    {{-- Kb --}}
                                       data-container-img="preview_banner"
                                       data-container-error="invalidImageBanner"
                                       disabled
                                >

                            </div>
                            <span style="color:red !important;" id="invalidImageBanner" class="d-none p-3"></span></i>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                @if ($catalogo->url_imagen_portada_ecommerce)
                                    <img style="max-width: 500px"
                                         id="preview_banner"
                                         name="preview_banner"
                                         src="{{ route('storage', ['typeFile' => 'catalogos/' . $catalogo->nameDirectory , 'filename' => $catalogo->url_imagen_portada_ecommerce]) }}"
                                    >
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="contenedor-select">
                            <label class="text-md-right" for="color_1">
                                <span class="requerido">* </span>
                                Activación de catalogo
                            </label>
                            <label class="switch">
                                <input type="checkbox" name="estatus" @if($catalogo->estatus == 1) checked="true" @endif disabled>
                                <span class="slider round"></span>
                            </label>

                        </div>
                        <span>
                            <small>OFF / ON swich para activar o desactivar el catalogo
                                para que sea visible o no desde la Aplicación Móvil, por defecto estará en OFF
                            </small>
                        </span>
                    </div>
                </div>

                <div class="row">

                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <a class="btn btn-primary" href="{{ route('catalogos.index') }}">Regresar</a>
                        {{-- <button type="submit" class="btn btn-primary">Guardar</button> --}}
                    </div>
                </div>

            </form>
        </div>
    </div>

    <p class="text-center text-primary"><small>-</small></p>
@endsection

@section('js')

    <script>

    function getTemplateModalProducts() {
        return `
        <div class="row">
            <div class="form-group col-xs-12 col-sm-12 col-md-3 text-left">
                        <label for="nombre">Estilo:</label>
                        <input type="text" class="form-control" id="estilo" name="estilo"
                            placeholder=""
                            value="">
            </div>


            <div class="form-group col-xs-12 col-sm-12 col-md-3 text-left">
                <label for="estatus">Temporada:</label>
                <select class="form-control" id="temporada" name="temporada">
                    <option value="0" >Todos</option>
                    @foreach ($temporadas as $key => $temporada)
                        <option value="{{ $temporada->id }}">{{ $temporada->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-xs-12 col-sm-12 col-md-2 text-left">
                <label for="estatus">Linea:</label>
                <select class="form-control" id="linea" name="linea">
                    <option value="0" >Todos</option>
                    @foreach ($lineas as $key => $linea)
                        <option value="{{ $linea->id }}">{{ $linea->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-xs-12 col-sm-12 col-md-2 text-left">
                <label for="estatus">Descripción:</label>
                <select class="form-control" id="descripcion" name="descripcion">
                    <option value="0" >Todos</option>
                    @foreach ($descripciones as $key => $item)
                        <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-xs-12 col-sm-12 col-md-2 pull-right text-right botones-buscar">
                <button type="button" onclick="getFilteredProducts()" class="btn btn-primary buscar-filtro">
                    Buscar
                </button>
                <button type="button" onclick="quitarFiltros()" class="btn btn-default buscar-filtro-2">
                    Limpiar
                </button>
            </div>


        </div>
        <hr>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <table style="padding-bottom:0; margin-bottom:0" class="table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th style="width:5% !important">ID</th>
                                <th style="width:15% !important">Estilo</th>
                                <th style="width:15% !important">Linea</th>
                                <th style="width:10% !important" class="text-center">Tallas</th>
                                <th style="width:10% !important" class="text-center">Color</th>
                                <th style="width:10% !important" class="text-center">Temporada</th>
                                <th style="width:5% !important">Precio</th>
                                <th style="width:10% !important" class="text-center">Seleccionar
                                </th>
                            </tr>
                        </thead>
                </table>
                <div style="padding-top:0" class="overflow-table-y">
                    <table style="padding-top:0" id="seleccionProductos" class="table table-striped" width="100%">
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        `;

    }

    </script>

    <script src="{{ asset('js/catalogos.js?v=' . time()) }}"></script>
@stop




