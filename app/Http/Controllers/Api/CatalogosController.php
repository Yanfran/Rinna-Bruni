<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Catalogos\Catalogo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;
use GuzzleHttp\Client;
use App\Traits\TraitApiCROL;
use Config;

class CatalogosController extends Controller
{
    use TraitApiCROL;
    public function index(){
        try {

            $catalogos = Catalogo::where('estatus',1)
            ->select('id','nombre','url_imagen_portada_ecommerce','created_at as creado','updated_at as modificado')
            ->paginate(20);

        } catch (\Illuminate\Database\QueryException $e) {
            return response([ 'errors'=>$e->getMessage() ], 400);
        }

        if($catalogos->isEmpty()) {
            return response(['message'=>'No hay catálogos para mostrar'], 202);
        }

        //agrega la ruta completa del storage a los nombres de archivos
        foreach ($catalogos as $key => $catalogo) {
            $catalogo->url_imagen_portada_ecommerce = $catalogo->full_url_banner;
            $catalogo->url_pdf    = $catalogo->full_url_pdf;
        }

        return response()->json([ 'catalogos' => $catalogos ], 200);
    }

    public function viewUser() {
        $user = Auth::user();

        return response()->json([ 'user' => $user ], 200);

    }

    public function getCatalogo($id) {
        try {
            $catalogo = Catalogo::where('id',$id)
            ->select('id','nombre','url_imagen_portada_ecommerce','created_at as creado','updated_at as modificado')
            ->first();

        } catch (\Illuminate\Database\QueryException $e) {
            return response([ 'errors'=>$e->getMessage() ], 400);
        }

        if( $catalogo === null ) {
            return response(['message'=>'No se encontro el catálogo'], 404);
        }

        //agrega la ruta completa del storage a los nombres de archivos
        $catalogo->url_imagen_portada_ecommerce = $catalogo->full_url_banner;
        $catalogo->url_pdf    = $catalogo->full_url_pdf;


        return response()->json([ 'catalogo' => $catalogo ], 200);

    }

    // public function getCatalogoWithProducts(Request $request,$id) {
    //     $mostrarExistencias = false;
    //     $tiendaId = null;
    //     try {

    //         $catalogo = Catalogo::where('id',$id)
    //         ->select('id','nombre','url_imagen_portada_ecommerce','created_at','updated_at')
    //         ->first();

    //         if( $catalogo === null ) {
    //             return response(['message'=>'No se encontro el catálogo'], 404);
    //         }

    //         if ($request->bearerToken()) {

    //             $tokenData = self::getTokenData($request->bearerToken());

    //             if($tokenData) {
    //                 Auth::onceUsingId($tokenData->tokenable_id);

    //                 $tiendaId = Auth::user()->tienda_id;
    //                 $products = self::getProductosConExistencia($tiendaId,$catalogo);
    //                 $mostrarExistencias = true;

    //             } else {
    //                 return response()->json([
    //                     'message' => 'Acceso no autorizado',
    //                 ], 401);
    //             }

    //         } else {
    //             $products = $catalogo->products()
    //             ->orderBy('id', 'DESC')
    //             ->paginate(20);

    //         }

    //     } catch (\Illuminate\Database\QueryException $e) {
    //         return response([ 'errors'=>$e->getMessage() ], 400);
    //     }

    //     //agrega la ruta completa del storage a los nombres de archivos
    //     $catalogo->url_imagen_portada_ecommerce = $catalogo->full_url_banner;
    //     $catalogo->url_pdf    = $catalogo->full_url_pdf;

    //     $products = self::loadExistenciasImagenesToProducts($products,$mostrarExistencias,$tiendaId);

    //     $response[] = [
    //         'id'         => $catalogo->id,
    //         'nombre'     => $catalogo->nombre,
    //         'creado'     => $catalogo->created_at,
    //         'modificado' => $catalogo->updated_at,
    //         'url_banner' => $catalogo->url_imagen_portada_ecommerce,
    //         'url_pdf'    => $catalogo->url_pdf,
    //         'productos'  => $products,
    //     ];

    //     return response()->json([ 'catalogo' => $response ], 200);
    // }

    private function getProductosConExistencia($tiendaId,$catalogo) {

        $products = $catalogo->products()
        ->whereHas('existencias', function ($query) use ($tiendaId) {
            $query->where('cantidad', '>', 0);
            // validamos si existe tienda_id
            if(isset($tiendaId)) {
                $query->where('tienda_id', $tiendaId);
            }
        })
        ->selectRaw('SUBSTRING(nombre_corto, 1, LOCATE("-", nombre_corto)) AS codigo_group,
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
        ->orderBy('id', 'DESC')
        ->paginate(20);


        $groupedProducts = [];

        foreach ($products as $product) {
            $product->load('marca');
            $product->load('galerias');
            $codigo = $product->codigo_group;

            if($codigo == "") {
                $codigo = $product->nombre_corto;
            }

            $idListaPrecio = Config::get('constants.listas_precios.consumidor_final');

            if(isset($listaDePrecio)) {

                $precioListaPrecio =
                    $this->CROL_getPrecioProducto(
                        $listaDePrecio, $product->id, 1); //TODO:: enviar precio por temporada del producto

            } else {
                $precioListaPrecio = null;
            }

            $tallas = [];
            $ids =[];

            $productos_agrupados = 0;
            foreach ($products->where('codigo_group', $codigo) as $productWithTalla) {
                $tallas[] = $productWithTalla->tallas;
                $ids[]    = $productWithTalla->id;
                $productos_agrupados++;
            }

            $galerias = $product->galerias;

            // Encuentra la imagen destacada con estado igual a 1
            $imagenDestacada = $galerias->where('estatus', 1)->first();

            if (!$imagenDestacada && $galerias->isNotEmpty()) {
                // Si no hay una imagen destacada pero hay imágenes, usa la primera imagen
                $imagenDestacada = $galerias->first();
            }

            // Asignar la imagen destacada al producto
            $product->imagen_destacada = $imagenDestacada ? $imagenDestacada->ruta : null;


            $groupedProducts[$codigo] = [
                'tiendaId' => $tiendaId,
                'codigo' => $codigo,
                'nombre' => $product->estilo,
                'productos_agrupados' => $productos_agrupados,
                'precio' => $precioListaPrecio,
                'imagen_destacada' => $product->imagen_destacada,
                'url_imagen_catalogo' => $product->url_imagen_catalogo,
                'galeria' => $product->galerias,
                'marca'  => $product->marca,
                'tallas' => $tallas,
                'ids'    => $ids,
            ];

        }

        return $groupedProducts;



    }

    private function getTokenData($token) {

        [$idToken, $userToken] = explode('|', $token, 2);

        $tokenData = DB::table('personal_access_tokens')->where('token', hash('sha256', $userToken))->first();

        if($tokenData) {
            return $tokenData;
        }

        return null;

    }

    private function loadExistenciasImagenesToProducts($products,$mostrarExistencias,$tiendaId) {
        // Obtener las imágenes para cada producto y asignar la imagen destacada
        $productsWithImages = [];

        foreach ($products as $product) {

            if ($mostrarExistencias) {
                $product->load(['existencias' => function ($query) use ($tiendaId){
                    $query->where('tienda_id',$tiendaId);
                }]);
            }

            $product->load('galerias'); // Carga las imágenes asociadas al producto
            $product->load('linea');
            $product->load('temporada');
            $product->load('descripcion');
            $product->load('marca');

            $galerias = $product->galerias;

            // Encuentra la imagen destacada con estado igual a 1
            $imagenDestacada = $galerias->where('estatus', 1)->first();

            if (!$imagenDestacada && $galerias->isNotEmpty()) {
                // Si no hay una imagen destacada pero hay imágenes, usa la primera imagen
                $imagenDestacada = $galerias->first();
            }

            // Asignar la imagen destacada al producto
            $product->imagen_destacada = $imagenDestacada ? $imagenDestacada->ruta : null;

            $productsWithImages[] = $product;
        }

        return $products;

    }


    public function getCatalogoWithProducts(Request $request,$id) {
        $mostrarExistencias = false;
        $tiendaId = null;
        try {

            $catalogo = Catalogo::where('id',$id)
            ->select('id','nombre','url_imagen_portada_ecommerce','created_at','updated_at')
            ->first();

            if( $catalogo === null ) {
                return response(['message'=>'No se encontro el catálogo'], 404);
            }

            if ($request->bearerToken()) {

                $tokenData = self::getTokenData($request->bearerToken());

                if($tokenData) {
                    Auth::onceUsingId($tokenData->tokenable_id);

                    $tiendaId = Auth::user()->tienda_id;
                    $groupedProducts = self::getProductosConExistencia($tiendaId,$catalogo);
                    $mostrarExistencias = true;

                } else {
                    return response()->json([
                        'message' => 'Acceso no autorizado',
                    ], 401);
                }

            } else {
                $products = $catalogo->products()
                ->selectRaw('SUBSTRING(nombre_corto, 1, LOCATE("-", nombre_corto)) AS codigo_group,
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
                ->orderBy('id', 'DESC')
                ->paginate(20);

                $groupedProducts = [];

                foreach ($products as $product) {
                    $product->load('marca');
                    $product->load('galerias');
                    $codigo = $product->codigo_group;

                    if($codigo == "") {
                        $codigo = $product->nombre_corto;
                    }

                    $idListaPrecio = Config::get('constants.listas_precios.consumidor_final');

                    if(isset($listaDePrecio)) {

                        $precioListaPrecio =
                            $this->CROL_getPrecioProducto(
                                $listaDePrecio, $product->id, 1); //TODO:: enviar precio por temporada del producto

                    } else {
                        $precioListaPrecio = null;
                    }

                    $tallas = [];
                    $ids =[];

                    $productos_agrupados = 0;
                    foreach ($products->where('codigo_group', $codigo) as $productWithTalla) {
                        $tallas[] = $productWithTalla->tallas;
                        $ids[]    = $productWithTalla->id;
                        $productos_agrupados++;
                    }

                    $galerias = $product->galerias;

                    // Encuentra la imagen destacada con estado igual a 1
                    $imagenDestacada = $galerias->where('estatus', 1)->first();

                    if (!$imagenDestacada && $galerias->isNotEmpty()) {
                        // Si no hay una imagen destacada pero hay imágenes, usa la primera imagen
                        $imagenDestacada = $galerias->first();
                    }

                    // Asignar la imagen destacada al producto
                    $product->imagen_destacada = $imagenDestacada ? $imagenDestacada->ruta : null;


                    $groupedProducts[$codigo] = [
                        'tiendaId' => $tiendaId,
                        'codigo' => $codigo,
                        'nombre' => $product->estilo,
                        'productos_agrupados' => $productos_agrupados,
                        'precio' => $precioListaPrecio,
                        'imagen_destacada' => $product->imagen_destacada,
                        'url_imagen_catalogo' => $product->url_imagen_catalogo,
                        'galeria' => $product->galerias,
                        'marca'  => $product->marca,
                        'tallas' => $tallas,
                        'ids'    => $ids,
                    ];
                }




            }

        } catch (\Illuminate\Database\QueryException $e) {
            return response([ 'errors'=>$e->getMessage() ], 400);
        }

        //agrega la ruta completa del storage a los nombres de archivos
        $catalogo->url_imagen_portada_ecommerce = $catalogo->full_url_banner;
        $catalogo->url_pdf    = $catalogo->full_url_pdf;

        //$products = self::loadExistenciasImagenesToProducts($products,$mostrarExistencias,$tiendaId);

        $response[] = [
            'id'         => $catalogo->id,
            'nombre'     => $catalogo->nombre,
            'creado'     => $catalogo->created_at,
            'modificado' => $catalogo->updated_at,
            'url_banner' => $catalogo->url_imagen_portada_ecommerce,
            'url_pdf'    => $catalogo->url_pdf,
            'productos'  => $groupedProducts,
        ];

        return response()->json([ 'catalogo' => $response ], 200);
    }



}
