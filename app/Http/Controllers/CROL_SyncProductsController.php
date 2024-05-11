<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Traits\TraitApiCROL;
use Config;

class CROL_SyncProductsController extends Controller
{
    use TraitApiCROL;

    public function testAuthCROL(Client $client) {
        $token =  $this->getTokenAuth($client);
         return $token ;
    }

    public function syncProducts(Client $client) {
        $this->CROL_syncProducts($client);
    }

    public function syncContactos(Client $client) {
        $this->CROL_syncContactos($client);
    }

    public function getProductoCROL($id, $tipoCliente, Client $client) {

        $idListaPrecio = null;

        if($tipoCliente == 3) $idListaPrecio = Config::get('constants.listas_precios.distribuidores');
        if($tipoCliente == 2) $idListaPrecio = Config::get('constants.listas_precios.independientes');
        if($tipoCliente == 4) $idListaPrecio = Config::get('constants.listas_precios.consumidor_final');

        if( $idListaPrecio == null) return "El tipo de contacto no es valido";

        return $this->CROL_getProducto($client, $id,
            [
                'listaId' => $idListaPrecio,
                'precioId' => 1,
                'sucursalId' => Config::get('constants.sucursalCROL'),
            ]
        );
    }

    public function getListaDePrecio($id,Client $client) {
        return $this->CROL_getListaDePrecio($client, $id);
    }

    public function getPrecioListaProducto($idProducto, $idlistaPrecio, $tipoPrecio, Client $client) {

        $responseListaPrecio = $this->CROL_getListaDePrecio($client, $idlistaPrecio);

        return $this->CROL_getPrecioProducto($responseListaPrecio, $idProducto, $tipoPrecio);
    }

}
