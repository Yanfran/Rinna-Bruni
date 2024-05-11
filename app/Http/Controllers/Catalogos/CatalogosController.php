<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Catalogos\Catalogo;
use App\Models\Catalogos\Linea;
use App\Models\Catalogos\Temporada;
use App\Models\Catalogos\Descripcion;
use App\Models\Product;
use GuzzleHttp\Client;
use App\Traits\TraitApiCROL;
use Config;

class CatalogosController extends Controller
{
    use TraitApiCROL;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $sortBy = $request->query('sortBy', 'nombre');
        $sortOrder = $request->query('sortOrder', 'asc');
        $nombre = $request->query('nombre', '');
        $estatus = $request->query('estatus', '');

        $query = Catalogo::query();

        if ($nombre) {
            $query->where('nombre', 'like', '%' . $nombre . '%');
        }

        if ($estatus !== '') {
            $query->where('estatus', $estatus);
        }

        $catalogos = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);


        $catalogos->appends([
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'nombre' => $nombre,
            'estatus' => $estatus,
        ]);

        return view('catalogos.index', [
            'catalogos' => $catalogos,
            'i' => ($catalogos->currentPage() - 1) * $catalogos->perPage(),
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'nombre' => $nombre,
            'estatus' => $estatus
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $productos = Product::
        // selectRaw('SUBSTRING(nombre_corto, 1, LOCATE("-", nombre_corto)) AS codigo,
        //             nombre_corto,
        //             estilo,
        //             id,
        //             marca_id,
        //             linea_id,
        //             temporada_id,
        //             talla,
        //             precio')
        // ->get();



        $lineas = Linea::All();
        $temporadas = Temporada::All();
        $descripciones = Descripcion::All();
        return view('catalogos.create',compact(
            //'productos',
            'lineas',
            'temporadas',
            'descripciones'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Client $client)
    {
        request()->validate([
            'nombre' => 'required|unique:catalogos',
            'productos' => 'required|array'
        ]);

        $filePortada      = $request->file('portada');
        $filePortadaFinal = $request->file('portada_final');
        $fileBanner       = $request->file('banner');

        $catalogo = New Catalogo();
        $catalogo->nombre = $request->nombre;
        $catalogo->estatus = $request->estatus ? '1' : '0';


        if ( ! is_null($filePortada) ) {
            $portadaImgExtension = $filePortada->getClientOriginalExtension();
            $portadaImgName = 'portada' . '.' . $portadaImgExtension;
            $catalogo->url_imagen_portada = $portadaImgName;
        }
       if ( ! is_null($filePortadaFinal) ) {
           $portadaFinalImgExtension = $filePortadaFinal->getClientOriginalExtension();
           $portadaFinalImgName = 'portada_final' . '.' . $portadaFinalImgExtension;
           $catalogo->url_imagen_final = $portadaFinalImgName;
       }
       if ( ! is_null($fileBanner) ) {
           $bannerImgExtension = $fileBanner->getClientOriginalExtension();
           $bannerImgName = 'banner' . '.' . $bannerImgExtension;
           $catalogo->url_imagen_portada_ecommerce = $bannerImgName;
       }

       $saved = $catalogo->save();

       $catalogoDir = "catalogos/" . $catalogo->id;

       $productosCodigosyTallas = [];


       foreach ($request->productos as $key => $producto) {

            $ids = explode("-", $request->ids[$key]);
            // Elimina los espacios en blanco adicionales
            //$tallas = array_map('trim', $tallas);

            foreach ($ids as $idProducto) {
                $productosCodigosyTallas[] = intval($idProducto);
            }
        }

        if ($saved) {

            $catalogo->products()->attach($productosCodigosyTallas);

            if ( ! is_null($filePortada) )      { Storage::disk('public')->putFileAs($catalogoDir, $filePortada, $portadaImgName); }
            if ( ! is_null($filePortadaFinal) ) { Storage::disk('public')->putFileAs($catalogoDir, $filePortadaFinal, $portadaFinalImgName); }
            if ( ! is_null($fileBanner ) )      { Storage::disk('public')->putFileAs($catalogoDir, $fileBanner, $bannerImgName); }

            self::generarArchivoPdf($catalogo, $client);

            return redirect()->route('catalogos.index')
                ->with('success','El Catálogo se ha creado con éxito.');

        }

         return redirect()->route('catalogos.index')
                         ->with('error','Ocurrio un error al crear el catálogo!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Catalogo $catalogo, Client $client)
    {
        //$productos = Product::All();
        $lineas = Linea::All();
        $temporadas = Temporada::All();
        $descripciones = Descripcion::All();

        $catalogo->nameDirectory = $catalogo->id;

        $products = $catalogo->products()
        ->selectRaw('SUBSTRING(nombre_corto, 1, LOCATE("-", nombre_corto)) AS codigo,
                    nombre_corto,
                    estilo,
                    products.id,
                    marca_id,
                    linea_id,
                    temporada_id,
                    descripcion_id,
                    talla,
                    precio'
        )
        ->get();

        $groupedProducts = [];

        foreach ($products as $product) {
            $product->load('marca');
            //$product->load('galerias');
            $codigo = $product->codigo;

            if($codigo == "") {
                $codigo = $product->nombre_corto;
            }

            $idListaPrecio = Config::get('constants.listas_precios.consumidor_final');

            if(isset($listaDePrecio)) {

                $tipoPrecio = 0;

                if($product->temporada) {
                    if($product->temporada->nombre == Config::get('constants.temporada.lanzamiento')) $tipoPrecio = 1;
                    if($product->temporada->nombre == Config::get('constants.temporada.linea')) $tipoPrecio = 2;
                    if($product->temporada->nombre == Config::get('constants.temporada.oferta')) $tipoPrecio = 3;
                    if($product->temporada->nombre == Config::get('constants.temporada.outlet')) $tipoPrecio = 4;
                }

                $precioListaPrecio =
                    $this->CROL_getPrecioProducto(
                        $listaDePrecio, $product->id, $tipoPrecio);

            } else {
                 $precioListaPrecio = null;
            }

            $tallas = [];
            $ids =[];

            $productos_agrupados = 0;
            foreach ($products->where('codigo', $codigo) as $productWithTalla) {
                $tallas[] = $productWithTalla->tallas;
                $ids[]    = $productWithTalla->id;
                $productos_agrupados++;
            }

            // $galerias = $product->galerias;

            // // Encuentra la imagen destacada con estado igual a 1
            // $imagenDestacada = $galerias->where('estatus', 1)->first();

            // if (!$imagenDestacada && $galerias->isNotEmpty()) {
            //     // Si no hay una imagen destacada pero hay imágenes, usa la primera imagen
            //     $imagenDestacada = $galerias->first();
            // }

            // // Asignar la imagen destacada al producto
            // $product->imagen_destacada = $imagenDestacada ? $imagenDestacada->ruta : null;

            $tallasString ="";
            foreach ($tallas as $key => $tallaProducto) {
                $tallasString .= $tallaProducto . ( (count($tallas)-1) == $key ? "" : "-");
            }

            $idsString ="";
            foreach ($ids as $key => $idProducto) {
                $idsString .= $idProducto . ( (count($ids)-1) == $key ? "" : "-");
            }
            if($idsString == "") {
                $idsString = $product->id;
            }

            $groupedProducts[$codigo] = [
                'codigo' => $codigo,
                'estilo' => $product->estilo,
                'productos_agrupados' => $productos_agrupados,
                'precio' => $precioListaPrecio,
                'imagen_destacada' => $product->imagen_destacada,
                'galeria' => $product->galerias,
                'marca'  => $product->marca,
                'linea'  => $product->linea,
                'temporada'  => $product->temporada,
                'tallas' => $tallas,
                'ids'    => ( (empty($ids)) ?  [$product->id] : $ids),
                'idsString' => $idsString,
                'tallasString' => $tallasString,
            ];
        }



        return view('catalogos.show',compact(
            'catalogo',
            'groupedProducts',
            'lineas',
            'temporadas',
            'descripciones'
        ));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Catalogo $catalogo, Client $client)
    {

        //$productos = Product::All();
        $lineas = Linea::All();
        $temporadas = Temporada::All();
        $descripciones = Descripcion::All();


        $products = $catalogo->products()
        ->selectRaw('SUBSTRING(nombre_corto, 1, LOCATE("-", nombre_corto)) AS codigo,
                    nombre_corto,
                    estilo,
                    products.id,
                    marca_id,
                    linea_id,
                    temporada_id,
                    descripcion_id,
                    talla,
                    precio'
        )
        ->get();

        // if(isset($tipoCliente)) {
        // if($tipoCliente !=3  && $tipoCliente !=2 && $tipoCliente !=4) {
        //     return $this->sendResponse([], 'El usuario no es valido para consultar lista de precios');
        // }

        // if($tipoCliente == 3) $idListaPrecio = Config::get('constants.listas_precios.distribuidores');
        // if($tipoCliente == 2) $idListaPrecio = Config::get('constants.listas_precios.independientes');
        // if($tipoCliente == 4) $idListaPrecio = Config::get('constants.listas_precios.consumidor_final');


        // $idListaPrecio = Config::get('constants.listas_precios.distribuidores'); //remover cuando ya se obtengan todos los datos
        // //Carga la lista de precio dependiendo del tipo de usuario
        // $listaDePrecio = $this->CROL_getListaDePrecio($client, $idListaPrecio);
        // }

        $idListaPrecio = Config::get('constants.listas_precios.consumidor_final');
        //Carga la lista de precio dependiendo del tipo de usuario
        $listaDePrecio = $this->CROL_getListaDePrecio($client, $idListaPrecio);


        $groupedProducts = [];

        foreach ($products as $product) {
            $product->load('marca');
            //$product->load('galerias');
            $codigo = $product->codigo;

            if($codigo == "") {
                $codigo = $product->nombre_corto;
            }

            if(isset($listaDePrecio)) {

                $tipoPrecio = 0;

                if($product->temporada) {
                    if($product->temporada->nombre == Config::get('constants.temporada.lanzamiento')) $tipoPrecio = 1;
                    if($product->temporada->nombre == Config::get('constants.temporada.linea')) $tipoPrecio = 2;
                    if($product->temporada->nombre == Config::get('constants.temporada.oferta')) $tipoPrecio = 3;
                    if($product->temporada->nombre == Config::get('constants.temporada.outlet')) $tipoPrecio = 4;
                }

                 $precioListaPrecio =
                     $this->CROL_getPrecioProducto(
                         $listaDePrecio, $product->id, $tipoPrecio);

            } else {
                 $precioListaPrecio = null;
            }

            $tallas = [];
            $ids =[];

            $productos_agrupados = 0;
            foreach ($products->where('codigo', $codigo) as $productWithTalla) {
                $tallas[] = $productWithTalla->tallas;
                $ids[]    = $productWithTalla->id;
                $productos_agrupados++;
            }

            // $galerias = $product->galerias;

            // // Encuentra la imagen destacada con estado igual a 1
            // $imagenDestacada = $galerias->where('estatus', 1)->first();

            // if (!$imagenDestacada && $galerias->isNotEmpty()) {
            //     // Si no hay una imagen destacada pero hay imágenes, usa la primera imagen
            //     $imagenDestacada = $galerias->first();
            // }

            // // Asignar la imagen destacada al producto
            // $product->imagen_destacada = $imagenDestacada ? $imagenDestacada->ruta : null;

            $tallasString ="";
            foreach ($tallas as $key => $tallaProducto) {
                $tallasString .= $tallaProducto . ( (count($tallas)-1) == $key ? "" : "-");
            }

            $idsString ="";
            foreach ($ids as $key => $idProducto) {
                $idsString .= $idProducto . ( (count($ids)-1) == $key ? "" : "-");
            }
            if($idsString == "") {
                $idsString = $product->id;
            }

            $groupedProducts[$codigo] = [
                'codigo' => $codigo,
                'estilo' => $product->estilo,
                'productos_agrupados' => $productos_agrupados,
                'precio' => $precioListaPrecio,
                'imagen_destacada' => $product->imagen_destacada,
                'galeria' => $product->galerias,
                'marca'  => $product->marca,
                'linea'  => $product->linea,
                'temporada'  => $product->temporada,
                'tallas' => $tallas,
                'ids'    => ( (empty($ids)) ?  [$product->id] : $ids),
                'idsString' => $idsString,
                'tallasString' => $tallasString,
            ];
        }

        $catalogo->nameDirectory = $catalogo->id;

        return view('catalogos.edit',compact(
            'catalogo',
            'groupedProducts',
            'lineas',
            'temporadas',
            'descripciones'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Catalogo $catalogo, Client $client)
    {
        request()->validate([
            'nombre' => 'required|unique:catalogos,nombre,'.$catalogo->id,
            'productos' => 'required|array'
        ]);

        $filePortada      = $request->file('portada');
        $filePortadaFinal = $request->file('portada_final');
        $fileBanner       = $request->file('banner');

        $catalogo->nombre = $request->nombre;
        $catalogo->estatus = $request->estatus ? '1' : '0';

        if ( ! is_null($filePortada) ) {
            $portadaImgExtension = $filePortada->getClientOriginalExtension();
            $portadaImgName = 'portada' . '.' . $portadaImgExtension;
            $catalogo->url_imagen_portada = $portadaImgName;
        }
        if ( ! is_null($filePortadaFinal) ) {
            $portadaFinalImgExtension = $filePortadaFinal->getClientOriginalExtension();
            $portadaFinalImgName = 'portada_final' . '.' . $portadaFinalImgExtension;
            $catalogo->url_imagen_final = $portadaFinalImgName;
        }
        if ( ! is_null($fileBanner) ) {
            $bannerImgExtension = $fileBanner->getClientOriginalExtension();
            $bannerImgName = 'banner' . '.' . $bannerImgExtension;
            $catalogo->url_imagen_portada_ecommerce = $bannerImgName;
        }


        $productosCodigosyTallas = [];

        foreach ($request->productos as $key => $producto) {

             $ids = explode("-", $request->ids[$key]);

             foreach ($ids as $idProducto) {
                 $productosCodigosyTallas[] = intval($idProducto);
             }
         }

        try {
            $catalogo->update();

            $catalogo->products()->sync($productosCodigosyTallas);

            $catalogoDir = "catalogos/" . $catalogo->id;

            if ( ! is_null($filePortada) )      { Storage::disk('public')->putFileAs($catalogoDir, $filePortada, $portadaImgName); }
            if ( ! is_null($filePortadaFinal) ) { Storage::disk('public')->putFileAs($catalogoDir, $filePortadaFinal, $portadaFinalImgName); }
            if ( ! is_null($fileBanner ) )      { Storage::disk('public')->putFileAs($catalogoDir, $fileBanner, $bannerImgName); }

            self::generarArchivoPdf($catalogo, $client);

        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('catalogos.index')
            ->with('error','Ocurrio un error al actualizar el catálogo!');

        }

        return redirect()->route('catalogos.index')
                        ->with('success','El Catálogo se ha actualizado con éxito.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Catalogo $catalogo)
    {

        $catalogo->products()->detach();
        $catalogo->delete();

        return redirect()->route('catalogos.index')
                        ->with('success','El Catálogo se ha borrado con éxito.');
    }

    public function generarArchivoPdf(Catalogo $catalogo, Client $client) {

        $name = str_replace(' ','_',$catalogo->nombre) . "_" . $catalogo->id . '.pdf';

        // $dataCatalogo = $catalogo->where('id',$catalogo->id)->with(
        //     [
        //         'products'=> function ($query) {
        //             $query->with('primeraImagen');
        //         }
        //     ]
        //     )->first();

        $products = $catalogo->products()
        ->selectRaw('SUBSTRING(nombre_corto, 1, LOCATE("-", nombre_corto)) AS codigo,
                    nombre_corto,
                    estilo,
                    products.id,
                    marca_id,
                    linea_id,
                    temporada_id,
                    descripcion_id,
                    talla,
                    precio,
                    url_imagen_catalogo'
        )
        ->orderBy('nombre_corto', 'ASC')
        ->get();

        $idListaPrecio = Config::get('constants.listas_precios.consumidor_final');
        //Carga la lista de precio dependiendo del tipo de usuario
        $listaDePrecio = $this->CROL_getListaDePrecio($client, $idListaPrecio);


        $groupedProducts = [];

        foreach ($products as $product) {
            $product->load('marca');
            //$product->load('galerias');
            $codigo = $product->codigo;

            if($codigo == "") {
                $codigo = $product->nombre_corto;
            }

            if(isset($listaDePrecio)) {

                $tipoPrecio = 0;

                if($product->temporada) {
                    if($product->temporada->nombre == Config::get('constants.temporada.lanzamiento')) $tipoPrecio = 1;
                    if($product->temporada->nombre == Config::get('constants.temporada.linea')) $tipoPrecio = 2;
                    if($product->temporada->nombre == Config::get('constants.temporada.oferta')) $tipoPrecio = 3;
                    if($product->temporada->nombre == Config::get('constants.temporada.outlet')) $tipoPrecio = 4;
                }

                 $precioListaPrecio =
                     $this->CROL_getPrecioProducto(
                         $listaDePrecio, $product->id, $tipoPrecio);

            } else {
                 $precioListaPrecio = null;
            }

            $tallas = [];
            $ids =[];

            $productos_agrupados = 0;
            foreach ($products->where('codigo', $codigo) as $productWithTalla) {
                $tallas[] = $productWithTalla->tallas;
                $ids[]    = $productWithTalla->id;
                $productos_agrupados++;
            }


            $tallasString ="";
            foreach ($tallas as $key => $tallaProducto) {
                $tallasString .= $tallaProducto . ( (count($tallas)-1) == $key ? "" : "-");
            }

            $idsString ="";
            foreach ($ids as $key => $idProducto) {
                $idsString .= $idProducto . ( (count($ids)-1) == $key ? "" : "-");
            }
            if($idsString == "") {
                $idsString = $product->id;
            }

            $groupedProducts[$codigo] = [
                'codigo' => $codigo,
                'estilo' => $product->estilo,
                'productos_agrupados' => $productos_agrupados,
                'precio' => $precioListaPrecio,
                'imagen_destacada' => $product->imagen_destacada,
                'galeria' => $product->galerias,
                'marca'  => $product->marca,
                'linea'  => $product->linea,
                'temporada'  => $product->temporada,
                'tallas' => $tallas,
                'ids'    => ( (empty($ids)) ?  [$product->id] : $ids),
                'idsString' => $idsString,
                'tallasString' => $tallasString,
                'url_imagen_catalogo' =>  $product->url_imagen_catalogo,
            ];
        }

        $catalogo->productos = $groupedProducts;

        $dataCatalogo = $catalogo;

        $pdf = Pdf::loadView('catalogos.pdf', ['catalogo' => $dataCatalogo]);
        $pdf->setPaper("letter","portrait");
        //$pdf->setPaper([0,0,481,850]);

        Storage::disk('public')->put('catalogos/'. $catalogo->id .'/' . $name,$pdf->output()) ;

    }

    public function getFilteredProducts(Request $request, Client $client) {

        $products = Product::
        selectRaw('SUBSTRING(nombre_corto, 1, LOCATE("-", nombre_corto)) AS codigo,
                    nombre_corto,
                    estilo,
                    id,
                    marca_id,
                    linea_id,
                    temporada_id,
                    descripcion_id,
                    talla,
                    precio'
        )
        ->when($request->estilo, function($query) use ($request) {
            $query->where('estilo', 'like', '%' . $request->estilo . '%');
        })
        ->when($request->temporadaId, function($query) use ($request) {
            $query->where('temporada_id', $request->temporadaId);
        })
        ->when($request->lineaId, function($query) use ($request) {
            $query->where('linea_id', $request->lineaId);
        })
        ->when($request->descripcionId, function($query) use ($request) {
            $query->where('descripcion_id', $request->descripcionId);
        })
        ->with([
            'linea',
            'temporada',
            'marca',
            'descripcion'
        ])
        ->get();

        // if(isset($tipoCliente)) {
        //     if($tipoCliente !=3  && $tipoCliente !=2 && $tipoCliente !=4) {
        //         return $this->sendResponse([], 'El usuario no es valido para consultar lista de precios');
        //     }

        //     if($tipoCliente == 3) $idListaPrecio = Config::get('constants.listas_precios.distribuidores');
        //     if($tipoCliente == 2) $idListaPrecio = Config::get('constants.listas_precios.independientes');
        //     if($tipoCliente == 4) $idListaPrecio = Config::get('constants.listas_precios.consumidor_final');


        //     $idListaPrecio = Config::get('constants.listas_precios.distribuidores'); //remover cuando ya se obtengan todos los datos
        //     //Carga la lista de precio dependiendo del tipo de usuario
        //     $listaDePrecio = $this->CROL_getListaDePrecio($client, $idListaPrecio);
        // }

        $idListaPrecio = Config::get('constants.listas_precios.consumidor_final');
        //Carga la lista de precio dependiendo del tipo de usuario
        $listaDePrecio = $this->CROL_getListaDePrecio($client, $idListaPrecio);


        $groupedProducts = [];

        foreach ($products as $product) {
            $product->load('marca');
            //$product->load('galerias');
            $codigo = $product->codigo;

            if($codigo == "") {
                $codigo = $product->nombre_corto;
            }

            if(isset($listaDePrecio)) {

                $tipoPrecio = 0;

                if($product->temporada) {
                    if($product->temporada->nombre == Config::get('constants.temporada.lanzamiento')) $tipoPrecio = 1;
                    if($product->temporada->nombre == Config::get('constants.temporada.linea')) $tipoPrecio = 2;
                    if($product->temporada->nombre == Config::get('constants.temporada.oferta')) $tipoPrecio = 3;
                    if($product->temporada->nombre == Config::get('constants.temporada.outlet')) $tipoPrecio = 4;
                }

                $precioListaPrecio =
                    $this->CROL_getPrecioProducto(
                        $listaDePrecio, $product->id, $tipoPrecio);

            } else {
                $precioListaPrecio = null;
            }

            $tallas = [];
            $ids =[];

            $productos_agrupados = 0;
            foreach ($products->where('codigo', $codigo) as $productWithTalla) {
                $tallas[] = $productWithTalla->tallas;
                $ids[]    = $productWithTalla->id;
                $productos_agrupados++;
            }

            //$galerias = $product->galerias;

            // Encuentra la imagen destacada con estado igual a 1
            // $imagenDestacada = $galerias->where('estatus', 1)->first();

            // if (!$imagenDestacada && $galerias->isNotEmpty()) {
            //     // Si no hay una imagen destacada pero hay imágenes, usa la primera imagen
            //     $imagenDestacada = $galerias->first();
            // }

            // Asignar la imagen destacada al producto
            //$product->imagen_destacada = $imagenDestacada ? $imagenDestacada->ruta : null;


            $groupedProducts[$codigo] = [
                'codigo' => $codigo,
                'estilo' => $product->estilo,
                'productos_agrupados' => $productos_agrupados,
                'precio' => $precioListaPrecio,
                //'imagen_destacada' => $product->imagen_destacada,
                //'galeria' => $product->galerias,
                'marca'  => $product->marca,
                'temporada' => $product->temporada,
                'linea' => $product->linea,
                'descripcion' => $product->descripcion,
                'tallas' => $tallas,
                'ids'    => ( (empty($ids)) ?  [$product->id] : $ids)
            ];
        }

        return $groupedProducts;
    }


}
