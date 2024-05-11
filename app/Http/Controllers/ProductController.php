<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Galeria;
use App\Models\Catalogos\Linea;
use App\Models\Catalogos\Temporada;
use App\Models\Catalogos\Descripcion;
use App\Models\Catalogos\Marca;
use GuzzleHttp\Client;
use App\Traits\TraitApiCROL;
use Config;
use Auth;

class ProductController extends Controller
{
    use TraitApiCROL;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
         $this->middleware('permission:product-create', ['only' => ['create','store']]);
         $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Client $client)
    {

        $perPage = $request->query('perPage', 10);
        $sortBy = $request->query('sortBy', 'estilo');
        $sortOrder = $request->query('sortOrder', 'asc');
        $estilo = $request->query('estilo', '');
        $estatus = $request->query('estatus', '');
        $temporadaId = $request->query('temporadaId', '');
        $lineaId = $request->query('lineaId', '');

        $query = Product::query();

        if ($estilo) {
            $query->where('estilo', 'like', '%' . $estilo . '%');
        }

        if ($estatus !== '') {
            $query->where('estatus', $estatus);
        }

        if ($temporadaId) {
            $query->where('temporada_id', $temporadaId);
        }

        if ($lineaId) {
            $query->where('linea_id', $lineaId);
        }

        $products = $query
                       ->orderBy($sortBy, $sortOrder)
                       ->orderBy('nombre_corto', 'ASC')
                       ->paginate($perPage);
        $temporadas = Temporada::All();
        $lineas = Linea::All();

        $products->appends([
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'estilo' => $estilo,
            'estatus' => $estatus,
            'temporadaId' => $temporadaId,
            'lineaId' => $lineaId
        ]);

        /*insertamos el precio del listado de acuerdo al tipo de usuario */
        self::agregrarPrecioLista($products, $client);

        return view('products.index', [
            'products' => $products,
            'i' => ($products->currentPage() - 1) * $products->perPage(),
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'estilo' => $estilo,
            'estatus' => $estatus,
            'temporadas' => $temporadas,
            'lineas' => $lineas,
            'temporadaId' => $temporadaId,
            'lineaId' => $lineaId

        ]);

        //$products = Product::latest()->paginate(5);
        //return view('products.index',compact('products'))
        //    ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lineas = Linea::all();
        $temporadas = Temporada::all();
        $descripciones = Descripcion::all();
        $marcas = Marca::all();
        $colors = Config::get('constants.colors');
        $composiciones = Config::get('constants.composiciones');
        $conceptos = Config::get('constants.conceptos');
        $tallas = Config::get('constants.tallas');

        //dd($colors);

        return view('products.create', compact(
            'lineas',
            'temporadas',
            'descripciones',
            'marcas',
            'colors',
            'conceptos',
            'composiciones',
            'tallas'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'precio' => 'required',
            // 'tipo' => 'required',
            // 'codigo' => 'required',
            // 'estilo' => 'required',
            'linea_id' => 'required',
            // 'talla_menor' => 'required',
            // 'talla_mayor' => 'required',
            'marca_id' => 'required',
            // 'ancho' => 'required',
            // 'color' => 'required',
            // 'concepto' => 'required',
            // 'acabado' => 'required',
            'temporada_id' => 'required',
            'descripcion_id' => 'required',
            // 'costo_bruto' => 'required|numeric',
            // 'descuento_1' => 'required|integer',
            // 'descuento_2' => 'required|integer',
            // 'proveedor' => 'required',
            // 'suela' => 'required',
            // 'nombre_suela' => 'required',
            // 'forro' => 'required',
            // 'horma' => 'required',
            // 'planilla' => 'required',
            // 'tacon' => 'required',
            // 'inicial' => 'required|numeric',
            // 'promedio' => 'required|numeric',
            // 'actual' => 'required|numeric',
        ]);

        $fileCatalogo = $request->file('img_catalogo');

        if ( ! is_null($fileCatalogo) ) {
            $fileImgExtension = $fileCatalogo->getClientOriginalExtension();
            $fileImgName = uniqid() . '.' . $fileImgExtension;
            $request->merge(['url_imagen_catalogo' => $fileImgName]);
        }

        if ($request->has('bloqueo_devolucion')) {
            $request->merge(['bloqueo_devolucion' => 1]);
        } else {
            $request->merge(['bloqueo_devolucion' => 0]);
        }

        if ($request->has('estatus')) {
            $request->merge(['estatus' => 1]);
        } else {
            $request->merge(['estatus' => 0]);
        }

        $producto = Product::create($request->all());

        $productDir = "products_imgs_catalogs/";

        if ($producto) {
            if ( ! is_null($fileCatalogo) ) {
                Storage::disk('public')->putFileAs($productDir, $fileCatalogo, $fileImgName);
            }
        }

        // Obtener la lista de imágenes cargadas


        $imagenes = $request->file('ruta');

        if ($imagenes && count($imagenes) > 0) {
            $contador = 0;
            // Procesar cada imagen


            foreach ($imagenes as $imagen) {
                // Generar un nombre único para la imagen
                $nombreImagen = uniqid() . '.' . $imagen->getClientOriginalExtension();


                // Mover la imagen a la ubicación deseada (public/galeria)
                $imagen->move('galeria', $nombreImagen);

                // Crear una nueva instancia de Galeria y asociarla al producto
                $galeria = new Galeria([
                    'ruta' => $nombreImagen,
                ]);

                 // destaca la imagen
                if ($contador == intval($request->imagen_destacada)) {
                    $galeria->estatus = 1;
                }

                $producto->galerias()->save($galeria);

                $contador++;  // Incrementar el contador

            }
        }


        return redirect()->route('products.index')
                        ->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product, Client $client)
    {

        $imagenes = $product->galerias;

        /*actualizamos el precio con el del precio de la lista correspondiente*/
        self::agregrarPrecioListaById($product, $client);

        return view('products.show', compact('product','imagenes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product, Client $client)
    {
         // Obtener las imágenes asociadas al producto
        $imagenes = $product->galerias;
        $lineas = Linea::all();
        $temporadas = Temporada::all();
        $descripciones = Descripcion::all();
        $marcas = Marca::all();
        $colors = Config::get('constants.colors');
        $composiciones = Config::get('constants.composiciones');
        $conceptos = Config::get('constants.conceptos');
        $tallas = Config::get('constants.tallas');


        /*actualizamos el precio con el del precio de la lista correspondiente*/
        self::agregrarPrecioListaById($product, $client);

        return view('products.edit', compact(
            'product',
            'imagenes',
            'lineas',
            'temporadas',
            'descripciones',
            'marcas',
            'colors',
            'conceptos',
            'composiciones',
            'tallas'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {

        $request->validate([
            'precio' => 'required',
            // 'tipo' => 'required',
            // 'codigo' => 'required',
            // 'estilo' => 'required',
            'linea_id' => 'required',
            // 'talla_menor' => 'required',
            // 'talla_mayor' => 'required',
            'marca_id' => 'required',
            // 'ancho' => 'required',
            // 'color' => 'required',
            // 'concepto' => 'required',
            // 'acabado' => 'required',
            //'temporada_id' => 'required',
            'descripcion_id' => 'required',
            // 'costo_bruto' => 'required|numeric',
            // 'descuento_1' => 'required|integer',
            // 'descuento_2' => 'required|integer',
            // 'proveedor' => 'required',
            // 'suela' => 'required',
            // 'nombre_suela' => 'required',
            // 'forro' => 'required',
            // 'horma' => 'required',
            // 'planilla' => 'required',
            // 'tacon' => 'required',
            // 'inicial' => 'required|numeric',
            // 'promedio' => 'required|numeric',
            // 'actual' => 'required|numeric',
        ]);

        $fileCatalogo      = $request->file('img_catalogo');


        if ( ! is_null($fileCatalogo) ) {
            $fileImgExtension = $fileCatalogo->getClientOriginalExtension();
            $fileImgName = uniqid() . '.' . $fileImgExtension;
            $request->merge(['url_imagen_catalogo' => $fileImgName]);
        }

        if ($request->has('bloqueo_devolucion')) {
            $request->merge(['bloqueo_devolucion' => 1]);
        } else {
            $request->merge(['bloqueo_devolucion' => 0]);
        }

        if ($request->has('estatus')) {
            $request->merge(['estatus' => 1]);
        } else {
            $request->merge(['estatus' => 0]);
        }

        $product->update($request->all());

        $productDir = "products_imgs_catalogs/";

        if ($product) {
            if ( ! is_null($fileCatalogo) ) {
                Storage::disk('public')->putFileAs($productDir, $fileCatalogo, $fileImgName);
            }
        }


        if(! $product->galerias->isEmpty() ) {
            //verificar si de las existentes no se borro alguna
            $imagenesGuardadas = $product->galerias;

            foreach ($imagenesGuardadas as $imagenGuardada) {
                if (! self::existeImagen($request->imagenesExistentes,$imagenGuardada->id)) {
                    self::eliminarImagen($imagenGuardada->id);
                }
            }
        }

        self::limpiarEstadosImagenes($product->id);

        if($request->donde_imagen_destacada=="existente") {
            self::destacarImagen(intval($request->imagen_destacada));
        }

        // Obtener la lista de imágenes cargadas
        $imagenes = $request->file('ruta');

        if ($imagenes && count($imagenes) > 0) {
            $contador = 0;
            // Procesar cada imagen
            foreach ($imagenes as $imagen) {
                // Generar un nombre único para la imagen
                $nombreImagen = uniqid() . '.' . $imagen->getClientOriginalExtension();

                // Mover la imagen a la ubicación deseada (public/galeria)
                $imagen->move('galeria', $nombreImagen);

                // Crear una nueva instancia de Galeria y asociarla al producto
                if($request->donde_imagen_destacada=="nueva" and intval($request->imagen_destacada) == $contador)
                    $galeria = new Galeria([ 'ruta' => $nombreImagen, 'estatus' => 1 ]);
                else {
                    $galeria = new Galeria([ 'ruta' => $nombreImagen ]);
                }

                $product->galerias()->save($galeria);

                $contador++;  // Incrementar el contador

            }
        }


        return redirect()->route('products.index')
                        ->with('success', 'Producto actualizado exitosamente.');
    }

    private function existeImagen($array, $idBuscado) {
        if (empty($array)) {
            return false;
        }
        foreach ($array as $fila) {
            if (intval($fila["id"]) === $idBuscado) {
                return true;
            }
        }
        return false;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
                        ->with('success', 'Producto eliminado exitosamente.');
    }



     /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */

    private function eliminarImagen($id)
    {
        // Obtener la imagen desde la base de datos
        $imagen = Galeria::findOrFail($id);

        // Obtener la ruta completa de la imagen en el servidor
        $rutaImagen = public_path('galeria/' . $imagen->ruta);

        // Verificar si la imagen existe en el servidor
        if (file_exists($rutaImagen)) {
            // Eliminar la imagen del servidor
            unlink($rutaImagen);
        }

         // Obtener el ID del producto al que pertenece la imagen
        //$productId = $imagen->product_id;

        // Eliminar el registro de la imagen de la base de datos
        $imagen->delete();

        //return response()->json(['success' => true]);

        // return redirect()->route('products.edit', $productId )
        //                 ->with('success', 'La imagen ha sido eliminada exitosamente.');

    }

    private function destacarImagen($imagenId){
         // Actualizar el estado de la imagen seleccionada a 1
         $imagen = Galeria::find($imagenId);
         if ($imagen) {
             $imagen->estatus = 1;
             $imagen->save();
         }
     }



    /**
     * Update status the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */

    public function limpiarEstadosImagenes($productoId)  {
        // Actualizar el estado de todas las imágenes asociadas al producto
        Galeria::where('product_id', $productoId)->update(['estatus' => null]);
    }

    private function agregrarPrecioLista($products, Client $client){

        $usuario = Auth::user();

        //por defecto sera la de consumisor final
        $idListaPrecio = Config::get('constants.listas_precios.consumidor_final');;

        //si es Distribuidor se cambia a su lista
        if($usuario->tipo == 3) $idListaPrecio = Config::get('constants.listas_precios.distribuidores');

        //Carga la lista de precio dependiendo del tipo de usuario
        $listaDePrecio = $this->CROL_getListaDePrecio($client, $idListaPrecio);


        foreach ($products as $product) {

            $tipoPrecio = 0;

            if($product->temporada) {
                if($product->temporada->nombre == Config::get('constants.temporada.lanzamiento')) $tipoPrecio = 1;
                if($product->temporada->nombre == Config::get('constants.temporada.linea')) $tipoPrecio = 2;
                if($product->temporada->nombre == Config::get('constants.temporada.oferta')) $tipoPrecio = 3;
                if($product->temporada->nombre == Config::get('constants.temporada.outlet')) $tipoPrecio = 4;
            }

            $product->precio =
                $this->CROL_getPrecioProducto(
                    $listaDePrecio, $product->id, $tipoPrecio, $product->external_id);

        }


    }

    private function agregrarPrecioListaById($product, Client $client){

        $usuario = Auth::user();

        //por defecto sera la de consumisor final
        $idListaPrecio = Config::get('constants.listas_precios.consumidor_final');;

        //si es Distribuidor se cambia a su lista
        if($usuario->tipo == 3) $idListaPrecio = Config::get('constants.listas_precios.distribuidores');

        //Carga la lista de precio dependiendo del tipo de usuario
        $listaDePrecio = $this->CROL_getListaDePrecio($client, $idListaPrecio);

        $tipoPrecio = 0;

        if($product->temporada) {
            if($product->temporada->nombre == Config::get('constants.temporada.lanzamiento')) $tipoPrecio = 1;
            if($product->temporada->nombre == Config::get('constants.temporada.linea')) $tipoPrecio = 2;
            if($product->temporada->nombre == Config::get('constants.temporada.oferta')) $tipoPrecio = 3;
            if($product->temporada->nombre == Config::get('constants.temporada.outlet')) $tipoPrecio = 4;
        }

        $product->precio =
            $this->CROL_getPrecioProducto(
                $listaDePrecio, $product->id, $tipoPrecio, $product->external_id);


    }


}
