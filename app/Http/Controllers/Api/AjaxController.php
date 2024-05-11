<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use App\Models\Estados;
use App\Models\Pais;
use App\Models\Municipios;
use App\Models\Direcciones;
use App\Models\Localidads;
use App\Models\Tiendas;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

class AjaxController extends BaseController
{
    public function listaEstados($id)
    {
        //tipo 2 distribuidores
        $data = Estados::where('pais_id', $id)->where('estatus', 1)->orderBy('id', 'DESC')->get();
        return $this->sendResponse($data, 'Lista de estados.');
    }

    public function listaTiendas()
    {
        //tipo 2 distribuidores
        $data = Tiendas::where('estatus', 1)->orderBy('id', 'DESC')->get();
        return $this->sendResponse($data, 'Lista de tiendas.');
    }

    public function listaMunicipio($id)
    {
        //tipo 2 distribuidores
        $data = Municipios::where('estado_id', $id)->where('estatus', 1)->orderBy('id', 'DESC')->get();
        return $this->sendResponse($data, 'Lista de municipios relacionados a estados.');
    }

    public function listaLocalidades($id)
    {
        //tipo 2 distribuidores
        $data = Localidads::where('municipio_id', $id)->where('estatus', 1)->orderBy('id', 'DESC')->get();
        return $this->sendResponse($data, 'Lista de localidads relacionados a municipios.');
    }

    public function listaSucursales($id)
    {
        //tipo 2 distribuidores
        $data = Direcciones::where('user_id', $id)->where('estatus', 1)->orderBy('id', 'DESC')->get();
        return $this->sendResponse($data, 'Lista de sucursales o direcciones .');
    }

    public function getPaisLista()
    {
        //tipo 2 distribuidores
        $data = Pais::where('estatus', 1)->orderBy('id', 'DESC')->get();
        return $this->sendResponse($data, 'Lista de paises.');
    }

    public function getPaisID($id)
    {

        new Pais();
        $data = Pais::withTrashed()->where("id", $id)->where('estatus', 1)->first();
        return $this->sendResponse($data, 'Pais por ID.');
    }

    public function getEstadoID($id)
    {

        new Estados();
        $data = Estados::withTrashed()->where("id", $id)->where('estatus', 1)->first();
        return $this->sendResponse($data, 'Estado por ID.');
    }

    public function getSucursalID($id)
    {

        new Direcciones();
        $data = Direcciones::withTrashed()->where("id", $id)->where('estatus', 1)->first();
        return $this->sendResponse($data, 'Sucursal direccion por ID.');
    }

    public function getMunicipiosID($id)
    {

        new Municipios();

        $data  = Municipios::withTrashed()->where("id", $id)->where('estatus', 1)->first();

        return $this->sendResponse($data, 'Municipio por ID.');
    }

    public function getLocalidadID($id)
    {

        new Localidads();
        $data  = Localidads::withTrashed()->where("id", $id)->where('estatus', 1)->first();
        return $this->sendResponse($data, 'Localidad por ID.');
    }




}
