<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use App\Models\Empresas;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

class DistribuidoresController extends BaseController
{
    public function listaDistribuidores()
    {
        //tipo 2 distribuidores
        $data = User::where('tipo', 3)->orderBy('id','DESC')->get();
        return $this->sendResponse($data, 'Lista de distribuidores.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('id', 3)->pluck('name','name')->all();
        $tiendas = Tiendas::all();
        $estados = Estados::all();
        //return view('distribuidores.create',compact('roles','tiendas','estados'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|same:confirm-password',
            'roles' => 'required',
            'tienda_id' => 'required',
            'estado_id' => 'required',
            'municipio_id' => 'required',
            'localidad_id' => 'required',
            'codigo_postal' => 'required',
            'ciudad' => 'required',
            'calle_numero' => 'required',
            'celular' => 'required',
            'telefono_fijo' => 'required',
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        if ($request->has('estatus')) {
            $request->merge(['estatus' => 1]);
        } else {
            $request->merge(['estatus' => 0]);
        }

        $distribuidores = User::create($input);
        $distribuidores->assignRole($request->input('roles'));
        $id = $distribuidores->id;

        $direccion = new Direcciones();
        $direccion->user_id = $id; // Asigna el ID del usuario
        $direccion->alias = 'Dirección principal';
        $direccion->estado_id = $request->input('estado_id');
        $direccion->municipio_id = $request->input('municipio_id');
        $direccion->localidad_id = $request->input('localidad_id');
        $direccion->calle = $request->input('calle_numero');
        $direccion->tipo = 1;
        $direccion->estatus = 1;
        $direccion->save();


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $distribuidores = User::find($id);

        if ($distribuidores) {
            $idRol = $distribuidores->rol;

            if ($idRol !== 0) {
                $roles = Role::where('id', $idRol)->first();
            } else {
                $roles = (object) ['name' => 'Sin Rol'];
            }
        } else {
            $roles = null;
        }


        $idTienda = $distribuidores->tienda_id;
        $tiendas = Tiendas::where('id', $idTienda)->first();

        $idUser = $distribuidores->id;
        $direcciones = Direcciones::where('user_id', $idUser)->first();

        if ($direcciones) {
            $estados = Estados::where('id', $direcciones->estado_id)->first();
            $municipios = Municipios::where('id', $direcciones->municipio_id)->first();
            $localidad = Localidads::where('id', $direcciones->localidad_id)->first();
        } else {
            // Manejo de caso cuando no hay datos en la tabla 'direcciones'
            $estados = null;
            $municipios = null;
            $localidad = null;
        }


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $distribuidores = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $distribuidores->roles->pluck('name','name')->all();

        $tiendas = Tiendas::all();
        $idTienda = User::find(auth()->user()->id)->tienda_id;

        $estados = Estados::all();
        $idUser = $distribuidores->id;
        $direcciones = Direcciones::where('user_id', $idUser)->first();


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'min:5|same:confirm-password',
            'roles' => 'required',


            'tienda_id' => 'required',
            'estado_id' => 'required',
            'municipio_id' => 'required',
            'localidad_id' => 'required',
            'codigo_postal' => 'required',
            'ciudad' => 'required',
            'calle_numero' => 'required',
            'celular' => 'required',
            'telefono_fijo' => 'required',
        ]);

        $input = $request->all();
        if(!empty($input['password']) || !empty($input['estado_id']) || !empty($input['municipio_id']) || !empty($input['localidad_id'])){
            $input['password'] = Hash::make($input['password']);
            $input['estado_id'] = $input['estado_id'];
            $input['municipio_id'] = $input['municipio_id'];
            $input['localidad_id'] = $input['localidad_id'];
        }else{
            $input = Arr::except($input,array('password'));
            $input = Arr::except($input, ['estado_id']);
            $input = Arr::except($input, ['municipio_id']);
            $input = Arr::except($input, ['localidad_id']);
        }

        $distribuidores = User::find($id);
        if ($request->has('estatus')) {
            $request->merge(['estatus' => 1]);
        } else {
            $request->merge(['estatus' => 0]);
        }
        $distribuidores->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $distribuidores->assignRole($request->input('roles'));

        $direccion = Direcciones::where('user_id', $id)->first();

        if ($direccion) {
            $direccion->alias = 'Dirección principal';
            $direccion->estado_id = $request->input('estado_id');
            $direccion->municipio_id = $request->input('municipio_id');
            $direccion->localidad_id = $request->input('localidad_id');
            $direccion->calle = $request->input('calle_numero');
            $direccion->save();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        User::find($id)->where('id', $id)->update(['estatus' => '0']);


        // User::find($id)->delete();
        // return redirect()->route('distribuidores.index')
        //                 ->with('success','User deleted successfully');
    }
}
