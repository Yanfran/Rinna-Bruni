<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Direcciones;
use Spatie\Permission\Models\Role;
use App\Models\Tiendas;
use App\Models\Estados;
use App\Models\Municipios;
use App\Models\Localidads;
use Auth;
use DB;
use Hash;
use Illuminate\Support\Arr;
use GuzzleHttp\Client;
use App\Traits\TraitApiCROL;
use App\Enums\UserType;

class DistribuidoresController extends Controller
{
    use TraitApiCROL;

    function __construct()
    {
        /* INSERT INTO `permissions`
         (`id`, `name`, `guard_name`, `created_at`, `updated_at`)
         VALUES
         (NULL, 'distribuidores-menu', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'distribuidores-create', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'distribuidores-edit', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'distribuidores-list', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
         (NULL, 'distribuidores-delete', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59') */
         $this->middleware('permission:distribuidores-list|distribuidores-create|distribuidores-edit|distribuidores-delete', ['only' => ['index','store']]);
         $this->middleware('permission:distribuidores-create', ['only' => ['create','store']]);
         $this->middleware('permission:distribuidores-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:distribuidores-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (Auth::user()->tipo == '3') {

            $perPage = $request->query('perPage', 10);
            $sortBy = $request->query('sortBy', 'name');
            $sortOrder = $request->query('sortOrder', 'asc');
            $distribuidorNombre = $request->query('distribuidorNombre', '');
            $estatus = $request->query('estatus', '');

            $query = User::query();

            if ($distribuidorNombre) {
                $query->where('name', 'like', '%' . $distribuidorNombre . '%');
            }

            if ($estatus !== '') {
                $query->where('estatus', $estatus);
            }

            $distribuidorId = Auth::user()->id;

            $distribuidores = $query->where('id', '=' , $distribuidorId)->where('tipo', '!=', 4)->orderBy($sortBy, $sortOrder)->paginate($perPage);


            $distribuidores->appends([
                'perPage' => $perPage,
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'distribuidorNombre' => $distribuidorNombre,
                'estatus' => $estatus,
            ]);

            return view('distribuidores.index', [
                'distribuidores' => $distribuidores,
                'i' => ($distribuidores->currentPage() - 1) * $distribuidores->perPage(),
                'perPage' => $perPage,
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'distribuidorNombre' => $distribuidorNombre,
                'estatus' => $estatus
            ]);
        } else {

            $perPage = $request->query('perPage', 10);
            $sortBy = $request->query('sortBy', 'name');
            $sortOrder = $request->query('sortOrder', 'asc');
            $distribuidorNombre = $request->query('distribuidorNombre', '');
            $estatus = $request->query('estatus', '');

            $query = User::query();

            if ($distribuidorNombre) {
                $query->where('name', 'like', '%' . $distribuidorNombre . '%');
            }

            if ($estatus !== '') {
                $query->where('estatus', $estatus);
            }

            $distribuidores = $query->where('tipo', '!=' , 1)->where('tipo', '!=' , 2)->where('tipo', '!=', 4)->orderBy($sortBy, $sortOrder)->paginate($perPage);


            $distribuidores->appends([
                'perPage' => $perPage,
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'distribuidorNombre' => $distribuidorNombre,
                'estatus' => $estatus,
            ]);

            return view('distribuidores.index', [
                'distribuidores' => $distribuidores,
                'i' => ($distribuidores->currentPage() - 1) * $distribuidores->perPage(),
                'perPage' => $perPage,
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'distribuidorNombre' => $distribuidorNombre,
                'estatus' => $estatus
            ]);

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('id', 3)->pluck('name','name')->all();
        $tiendas = Tiendas::where('estatus', '!=' , 0)->get();
        $estados = Estados::all();
        return view('distribuidores.create',compact('roles','tiendas','estados'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Client $client)
    {

        $this->validate($request, [
            'email' => 'required|min:3|email|unique:users,email',
            'password' => 'required|min:5|same:confirm-password',
            'confirm-password' => 'required|min:5|same:password',
            'name' => 'required|min:2',
            'apellido_paterno' => 'required|min:2',
            // 'numero_afiliacion' => 'required|unique:users',
            'tienda_id' => 'required',
            'nombre_empresa' => 'required|min:2',
            'estado_id' => 'required',
            'municipio_id' => 'required',
            'localidad_id' => 'required',
            'codigo_postal' => 'required|min:5',
            'calle_numero' => 'required|min:2',
            'celular' => 'required|min:10',
            'telefono_fijo' => 'required|min:10',
        ], [
            'rfc.max' => 'El RFC debe tener máximo 13 caracteres',
            'email.min' => 'El correo electrónico debe contener al menos 3 caracteres.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.unique' => 'El correo electrónico ya está en uso por otro usuario.',
            'password.required' => 'El campo clave es obligatorio.',
            'password.min' => 'La contraseña debe contener al menos 5 caracteres.',
            'confirm-password.required' => 'El campo verificar contraseña es obligatorio.',
            'confirm-password.min' => 'El campo verificar contraseña debe contener al menos 5 caracteres.',
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe contener al menos 2 caracteres.',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
            'apellido_paterno.min' => 'El apellido paterno debe contener al menos 2 caracteres.',
            'apellido_materno.required' => 'El apellido materno es obligatorio.',
            'apellido_materno.min' => 'El apellido materno debe contener al menos 2 caracteres.',
            'numero_afiliacion.unique:users' => 'El elemento número afiliación ya ha sido registrado.',
            'tienda_id.required' => 'Selecciona una tienda.',
            'estado_id.required' => 'Selecciona un estado.',
            'municipio_id.required' => 'Selecciona un municipio.',
            'localidad_id.required' => 'Selecciona una localidad.',
            'codigo_postal.required' => 'El código postal es obligatorio.',
            'codigo_postal.min' => 'El código postal debe contener al menos 5 caracteres.',
            'calle_numero.required' => 'La calle y número son obligatorios.',
            'calle_numero.min' => 'La calle y número deben contener al menos 2 caracteres.',
            'celular.required' => 'El número de móvil es obligatorio.',
            'celular.min' => 'El número de móvil debe contener al menos 10 dígitos.',
            'telefono_fijo.required' => 'El número de teléfono es obligatorio.',
            'telefono_fijo.min' => 'El número de teléfono debe contener al menos 10 dígitos.',
        ]);


        $numero = $request->input('credito');
        $numero_sin_comas = str_replace(",", "", $numero);

        if ($request->has('estatus')) {
            $request->merge(['estatus' => 1]);
        } else {
            $request->merge(['estatus' => 0]);
        }
        if ($request->has('bloqueo_pedido')) {
            $request->merge(['bloqueo_pedido' => 1]);
        } else {
            $request->merge(['bloqueo_pedido' => 0]);
        }

        $request->merge(['external_id' => null]);

        $input = $request->all();

        $idTienda = $input['tienda_id'];

        $input['credito'] = $numero_sin_comas;

        $input['password'] = Hash::make($input['password']);

        //Consumo de Servicio Creacion de Contactos en el erp CROL
        $request->merge(['tipo_contacto' => UserType::Distribuidor->value]); //Id Distribuidores CROL
        $statusCreaContactoCrol = $this->CROL_createContact($request, $client);


        if(! is_array($statusCreaContactoCrol) ) {
            return redirect()->route('distribuidores.index')
            ->with('error','Hubo un problema al crear el Distribuidor')
            ->withInput($request->input());
        }

        if($statusCreaContactoCrol[0] == 200) {

            $input['external_id'] = $statusCreaContactoCrol[1]['entidadId'];

            $distribuidores = User::create($input);

            $id = $distribuidores->id;
            $afiliaciones =     \App\Helpers\GlobalHelper::generateAfiliacionCode('D', $id, $idTienda);
            $distribuidores->numero_afiliacion = $afiliaciones;
            $distribuidores->save();

            $distribuidores->cuentas_creadas = 0;

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
            $direccion->cp = $request->input('codigo_postal');
            // $direccion->colonia = $request->input('ciudad');
            $direccion->estatus = 1;
            $direccion->save();

            return redirect()->route('distribuidores.index')
                            ->with('success','Distribuidor creado correctamente')
                            ->withInput($request->input());

        } else {

            return redirect()->route('distribuidores.index')
            ->with('error','Hubo un problema al crear el Distribuidor')
            ->withInput($request->input());

        };


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



        return view('distribuidores.show',compact('distribuidores','roles','tiendas','estados','municipios','localidad','direcciones'));
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

        $tiendas = Tiendas::where('estatus', '!=' , 0)->get();
        $idTienda = User::find(auth()->user()->id)->tienda_id;


        $estados = Estados::all();
        $idUser = $distribuidores->id;
        $direcciones = Direcciones::where('user_id', $idUser)->first();

        return view('distribuidores.edit',compact('estados','distribuidores','roles','userRole','tiendas','idTienda','direcciones','idUser'));
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
            'email' => 'nullable|min:3|email|unique:users,email,'.$id,
            'password' => 'nullable|min:5|same:confirm-password',
            'confirm-password' => 'nullable|min:5|same:password',
            'name' => 'required|min:2',
            'apellido_paterno' => 'required|min:2',
            'numero_afiliacion' => 'nullable|unique:users,numero_afiliacion,' . $id,
            'tienda_id' => 'required',
            'nombre_empresa' => 'required|min:2',
            'estado_id' => 'required',
            'municipio_id' => 'required',
            'localidad_id' => 'required',
            'codigo_postal' => 'required|min:5',
            'calle_numero' => 'required|min:2',
            'celular' => 'required|min:10',
            'telefono_fijo' => 'required|min:10',
        ], [
            'email.min' => 'El correo electrónico debe contener al menos 3 caracteres.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.unique' => 'El correo electrónico ya está en uso por otro usuario.',
            'password.required' => 'El campo clave es obligatorio.',
            'password.min' => 'La contraseña debe contener al menos 5 caracteres.',
            'confirm-password.required' => 'El campo verificar contraseña es obligatorio.',
            'confirm-password.min' => 'El campo verificar contraseña debe contener al menos 5 caracteres.',
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe contener al menos 2 caracteres.',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
            'apellido_paterno.min' => 'El apellido paterno debe contener al menos 2 caracteres.',
            'apellido_materno.required' => 'El apellido materno es obligatorio.',
            'apellido_materno.min' => 'El apellido materno debe contener al menos 2 caracteres.',
            'numero_afiliacion.unique:users' => 'El elemento número afiliación ya ha sido registrado.',
            'tienda_id.required' => 'Selecciona una tienda.',
            'nombre_empresa.required' => 'El número de empresa es obligatorio.',
            'estado_id.required' => 'Selecciona un estado.',
            'municipio_id.required' => 'Selecciona un municipio.',
            'localidad_id.required' => 'Selecciona una localidad.',
            'codigo_postal.required' => 'El código postal es obligatorio.',
            'codigo_postal.min' => 'El código postal debe contener al menos 5 caracteres.',
            'calle_numero.required' => 'La calle y número son obligatorios.',
            'calle_numero.min' => 'La calle y número deben contener al menos 2 caracteres.',
            'celular.required' => 'El número de móvil es obligatorio.',
            'celular.min' => 'El número de móvil debe contener al menos 10 dígitos.',
            'telefono_fijo.required' => 'El número de teléfono es obligatorio.',
            'telefono_fijo.min' => 'El número de teléfono debe contener al menos 10 dígitos.',
        ]);

        $numero = $request->input('credito');
        $numero_sin_comas = str_replace(",", "", $numero);

        if ($request->has('estatus')) {
            $request->merge(['estatus' => 1]);
        } else {
            $request->merge(['estatus' => 0]);
        }

        if ($request->has('bloqueo_pedido')) {
            $request->merge(['bloqueo_pedido' => 1]);
        } else {
            $request->merge(['bloqueo_pedido' => 0]);
        }
        $input = $request->all();
        if(!empty($input['estado_id']) || !empty($input['municipio_id']) || !empty($input['localidad_id'])){
            $input['estado_id'] = $input['estado_id'];
            $input['municipio_id'] = $input['municipio_id'];
            $input['localidad_id'] = $input['localidad_id'];
        }else{
            $input = Arr::except($input, ['estado_id']);
            $input = Arr::except($input, ['municipio_id']);
            $input = Arr::except($input, ['localidad_id']);
        }

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $input['credito'] = $numero_sin_comas;

        $distribuidores = User::find($id);
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
            $direccion->cp = $request->input('codigo_postal');
            // $direccion->colonia = $request->input('ciudad');
            $direccion->save();
        }

        return redirect()->route('distribuidores.index')
                        ->with('success','Distribuidor  actualizado correctamente')
                        ->withInput($request->input());
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
        return redirect()->route('distribuidores.index')
                        ->with('success','Distribuidor eliminadocorrectamente');

        // User::find($id)->delete();
        // return redirect()->route('distribuidores.index')
        //                 ->with('success','User deleted successfully');
    }

}
