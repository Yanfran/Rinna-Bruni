<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Catalogos\Catalogo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use App\Traits\TraitApiCROL;
use Config;


class PdfController extends Controller
{
    use TraitApiCROL;
    public function vista(Catalogo $catalogo, Client $client) {

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

                 $precioListaPrecio =
                     $this->CROL_getPrecioProducto(
                         $listaDePrecio, $product->id, 1); //TODO:: enviar precio por temporada del producto

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
        #$pdf->setPaper([0,0,481,850]); //medida especifica
        $pdf->setPaper("letter","portrait");

        return $pdf->stream(); #ver en el navegador

     }

     public function download(Catalogo $catalogo, Client $client) {

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

                 $precioListaPrecio =
                     $this->CROL_getPrecioProducto(
                         $listaDePrecio, $product->id, 1); //TODO:: enviar precio por temporada del producto

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
        //$pdf->setPaper([0,0,481,850]);
        $pdf->setPaper("letter","portrait");

        return $pdf->download($name); #descargar

     }

}
