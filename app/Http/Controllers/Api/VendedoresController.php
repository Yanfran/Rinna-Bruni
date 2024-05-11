<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Direcciones;
use Spatie\Permission\Models\Role;
use App\Models\Tiendas;
use App\Models\Estados;
use App\Models\Municipios;
use App\Models\Localidads;
use DB;
use Hash;
use Illuminate\Http\JsonResponse;

class VendedoresController extends BaseController
{


    public function actualizarVendedor(Request $request)
    {

        $id = $request->id;


        $input = $request->all();


        if ($request->has('estatus')) {
            $input['estatus'] = 1;
        } else {
            $input['estatus'] = 0;
        }

        $vendedores = User::find($id);
        $vendedores->update($input);
        $direccion = Direcciones::where('user_id', $id)->first();

        if ($direccion) {
            $direccion->estado_id = $request->estado_id;
            $direccion->municipio_id = $request->municipio_id;
            $direccion->localidad_id = $request->localidad_id;
            $direccion->cp = $request->cp;
            $direccion->calle = $request->calle_numero;
            $direccion->save();
        }

        $data = [
            'usuario' =>  $vendedores,
            'direccion' =>  $direccion,
        ];


        return $this->sendResponse($data, 'Usuario actualizado correctamente.');


    }
    public function getVendedor($id)
    {
        //dd($id);
        $vendedores = User::find($id);
        $direccion = Direcciones::where('user_id', $id)->first();

        $data = [
            'usuario' =>  $vendedores,
            'direccion' =>  $direccion,
        ];


        return $this->sendResponse($data, 'vendedor por id.');


    }
}
