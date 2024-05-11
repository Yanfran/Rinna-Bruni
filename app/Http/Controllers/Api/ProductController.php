<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Product;
use App\Models\Galeria;
use Validator;
use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use GuzzleHttp\Client;
use App\Traits\TraitApiCROL;
use Config;


class ProductController extends BaseController
{
    use TraitApiCROL;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function getProducts(): JsonResponse
    // {

    //     $products = Product::orderBy('id', 'DESC')->take(20)->get();

    //     // Obtener las imágenes para cada producto
    //     $productsWithImages = [];

    //     foreach ($products as $product) {
    //         $product->load('galerias'); // Carga las imágenes asociadas al producto
    //         $productsWithImages[] = $product;
    //     }


    //     return $this->sendResponse($productsWithImages, 'Lista de distribuidores.');

    //     // $products = Product::all();
    //     // return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully.');
    // }

    public function getProducts(Request $request, Client $client): JsonResponse
{
    $usuarioId = $request->usuario_id;
    $tiendaId = null;
    // validacion para recibir el parametro
    if(isset($usuarioId)) {
        $usuario = User::find($usuarioId);
    // } else {
    //     return $this->sendResponse([], 'El id del usuario es requerido');
    }
    // validacion de usuario encontrado
    if(isset($usuario)) {
        $tienda = $usuario->Tienda;
        if($tienda) {
            $tiendaId = $tienda->id;
        } else {
            return $this->sendResponse([], 'El usuario no tiene tienda asignada');
        }

        $tipoCliente = $usuario->tipo;
    }

    // $products = Product::whereHas('existencias', function ($query) use ($tiendaId) {
    //         $query->where('cantidad', '>', 0);
    //         // validamos si existe tienda_id
    //         if(isset($tiendaId)) {
    //             $query->where('tienda_id', $tiendaId);
    //         }
    //     })
    //     ->orderBy('id', 'DESC')
    //     ->take(20)
    //     ->get();

    $products = Product::whereHas('existencias', function ($query) use ($tiendaId) {
                 $query->where('cantidad', '>', 0);
                 // validamos si existe tienda_id
                 if(isset($tiendaId)) {
                     $query->where('tienda_id', $tiendaId);
                 }
             })
    ->selectRaw('SUBSTRING(nombre_corto, 1, LOCATE("-", nombre_corto)) AS codigo,
               nombre_corto,
               estilo,
               id,
               marca_id,
               imagen_destacada,
               external_id,
               precio
               '
    )
    ->orderBy('id', 'DESC')
    ->take(20)
    ->get();

    if(isset($tipoCliente)) {
        if($tipoCliente !=3  && $tipoCliente !=2 && $tipoCliente !=4) {
            return $this->sendResponse([], 'El usuario no es valido para consultar lista de precios');
        }

        if($tipoCliente == 3) $idListaPrecio = Config::get('constants.listas_precios.distribuidores');
        if($tipoCliente == 2) $idListaPrecio = Config::get('constants.listas_precios.independientes'); //TODO:: LISTA PRECIOS ASOCIADOS
        if($tipoCliente == 4) $idListaPrecio = Config::get('constants.listas_precios.distribuidores'); //aqui va es la de independientes


        //$idListaPrecio = Config::get('constants.listas_precios.distribuidores'); //remover cuando ya se obtengan todos los datos
        //Carga la lista de precio dependiendo del tipo de usuario
        $listaDePrecio = $this->CROL_getListaDePrecio($client, $idListaPrecio);
    } else {
        //Si no esta logueado se usa la lista de precios del consumidor final
        $listaDePrecio = $this->CROL_getListaDePrecio($client, Config::get('constants.listas_precios.consumidor_final'));
    }

    $groupedProducts = [];

    foreach ($products as $product) {
        $product->load('marca');
        $product->load('galerias');
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
                    $listaDePrecio, $product->id, $tipoPrecio, $product->external_id);

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

        $galerias = $product->galerias;

        // Encuentra la imagen destacada con estado igual a 1
        $imagenDestacada = $galerias->where('estatus', 1)->first();

        if (!$imagenDestacada && $galerias->isNotEmpty()) {
            // Si no hay una imagen destacada pero hay imágenes, usa la primera imagen
            $imagenDestacada = $galerias->first();
        }

        // Asignar la imagen destacada al producto
        $product->imagen_destacada = $imagenDestacada ? $imagenDestacada->ruta : null;


        if($precioListaPrecio !=0 && $precioListaPrecio != null) {
            $groupedProducts[$codigo] = [
                'tiendaId' => $tiendaId,
                'codigo' => $codigo,
                'nombre' => $product->estilo,
                'productos_agrupados' => $productos_agrupados,
                'precio' => $precioListaPrecio,
                'imagen_destacada' => $product->imagen_destacada,
                'galeria' => $product->galerias,
                'marca'  => $product->marca,
                'tallas' => $tallas,
                'ids'    => $ids,
            ];
        }
    }


    return $this->sendResponse($groupedProducts, 'Lista de productos agrupados.');

    // Obtener las imágenes para cada producto y asignar la imagen destacada
    // $productsWithImages = [];

    // foreach ($products as $product) {
    //     $product->load('linea');
    //     $product->load('temporada');
    //     $product->load('descripcion');
    //     $product->load('marca');
    //     $product->load('galerias');
    //     $galerias = $product->galerias;

    //     // Encuentra la imagen destacada con estado igual a 1
    //     $imagenDestacada = $galerias->where('estatus', 1)->first();

    //     if (!$imagenDestacada && $galerias->isNotEmpty()) {
    //         // Si no hay una imagen destacada pero hay imágenes, usa la primera imagen
    //         $imagenDestacada = $galerias->first();
    //     }

    //     // Asignar la imagen destacada al producto
    //     $product->imagen_destacada = $imagenDestacada ? $imagenDestacada->ruta : null;

    //     $productsWithImages[] = $product;
    // }

    // return $this->sendResponse($productsWithImages, 'Lista de productos.');
}
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSizeColor(): JsonResponse
    {

        $products = Product::orderBy('id', 'DESC')->take(20)->get();

        // Obtener las imágenes para cada producto
        $productsWithImages = [];

        foreach ($products as $product) {

            $product->load('galerias'); // Carga las imágenes asociadas al producto
            $productsWithImages[] = $product;
        }

        return $this->sendResponse($productsWithImages, 'Lista de productos.');

        // $products = Product::all();
        // return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $product = Product::create($input);

        return $this->sendResponse(new ProductResource($product), 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): JsonResponse
    {
        $product = Product::find($id);

        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }

        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $product->name = $input['name'];
        $product->detail = $input['detail'];
        $product->save();

        return $this->sendResponse(new ProductResource($product), 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return $this->sendResponse([], 'Product deleted successfully.');
    }
}
