<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use Illuminate\Support\Arr;

class UserController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $sortBy = $request->query('sortBy', 'name');
        $sortOrder = $request->query('sortOrder', 'asc');
        $usuarioNombre = $request->query('usuarioNombre', '');
        $estatus = $request->query('estatus', '');

        $roles = Role::where('id', '!=', 2)->where('id', '!=', 3)->pluck('name', 'id');
    
        $query = User::query();

        if ($usuarioNombre) {
            $query->where('name', 'like', '%' . $usuarioNombre . '%');
        }

        if ($estatus !== '') {
            $query->where('estatus', $estatus);
        }

        $users = $query->where('tipo', '!=', 2)->where('tipo', '!=', 3)->where('tipo', '!=', 4)->orderBy($sortBy, $sortOrder)->paginate($perPage);


        $users->appends([
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'usuarioNombre' => $usuarioNombre,
            'estatus' => $estatus,
        ]);

        return view('users.index', [
            'users' => $users,
            'i' => ($users->currentPage() - 1) * $users->perPage(),
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'usuarioNombre' => $usuarioNombre,
            'estatus' => $estatus,
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('id', '!=', 2)->where('id', '!=', 3)->pluck('name', 'id');
        $tiendas = Tiendas::where('estatus', '!=' , 0)->get();
        $estados = Estados::all();
        return view('users.create', compact('roles', 'tiendas', 'estados'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        

        // dd($request);
        $this->validate($request, [
            'name' => 'required|min:2',
            'apellido_paterno' => 'required|min:2',        
            'usuario' => 'required|min:2',
            'email' => 'required|min:3|email|unique:users,email',
            // 'numero_empleado' => 'required',
            'password' => 'required|min:5|same:confirm-password',
            'confirm-password' => 'required|min:5|same:password',
            'tienda_id' => 'required',
            'roles' => 'required',
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
            'numero_empleado.required' => 'El número de empleado es obligatorio.',
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
            'tienda_id.required' => 'Selecciona una tienda.',
            'roles.required' => 'El tipo de usuario es obligatorio',
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
        $input = $request->all();
        $idTienda = $input['tienda_id'];
        $input['password'] = Hash::make($input['password']);

        if ($request->has('estatus')) {
            $request->merge(['estatus' => 1]);
        } else {
            $request->merge(['estatus' => 0]);
        }

        $input['credito'] = $numero_sin_comas;        

        $user = User::create($input);          

        $id = $user->id;
        $afiliaciones =     \App\Helpers\GlobalHelper::generateAfiliacionCode('E', $id, $idTienda);                            
        $user->numero_afiliacion = $afiliaciones;
        $user->save();

        $user->assignRole($request->input('roles'));
        $id = $user->id;

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


        return redirect()->route('users.index')
            ->with('success', 'Usuario creado correctamente')
            ->withInput($request->input());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::where('id', '!=', 2)->where('id', '!=', 3)->pluck('name', 'id');
        $userRole = $user->roles->pluck('name', 'name')->all();

        $tiendas = Tiendas::where('estatus', '!=' , 0)->get();
        $idTienda = User::find(auth()->user()->id)->tienda_id;

        $estados = Estados::all();
        $idUser = $user->id;
        $direcciones = Direcciones::where('user_id', $idUser)->first();

        // dd($user);

        return view('users.edit', compact('estados', 'user', 'roles', 'userRole', 'tiendas', 'idTienda', 'direcciones', 'idUser'));
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
            'name' => 'required|min:2',
            'apellido_paterno' => 'required|min:2',        
            'usuario' => 'required|min:2',
            'email' => 'required|min:3|email|unique:users,email,' . $id,
            // 'numero_empleado' => 'required',
            'password' => 'nullable|min:5|same:confirm-password',
            'confirm-password' => 'nullable|min:5|same:password',
            'tienda_id' => 'required',
            'roles' => 'required',
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
            'numero_empleado.required' => 'El número de empleado es obligatorio.',
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
            'tienda_id.required' => 'Selecciona una tienda.',
            'roles.required' => 'El tipo de usuario es obligatorio',
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
        $input = $request->all();
        if (!empty($input['estado_id']) || !empty($input['municipio_id']) || !empty($input['localidad_id'])) {                                            
            $input['estado_id'] = $input['estado_id'];
            $input['municipio_id'] = $input['municipio_id'];
            $input['localidad_id'] = $input['localidad_id'];
        } else {            
            $input = Arr::except($input, ['estado_id']);
            $input = Arr::except($input, ['municipio_id']);
            $input = Arr::except($input, ['localidad_id']);            
        }

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }
                                   

        // if ($request->has('estatus') == true) {
        //     // $input['estatus'] = 1;        
        // } else {
        //     // $input['estatus'] = 0;        
        // } 
                                              
        

        $input['credito'] = $numero_sin_comas;


        $user = User::find($id);               
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));


        $direccion = Direcciones::where('user_id', $id)->first();
        if(Empty($direccion)){
            $direccion = new Direcciones();
            $direccion->user_id = $id; // Asigna el ID del usuario
        }

        if ($direccion) {
            $direccion->alias = 'Dirección principal';
            $direccion->estado_id = $request->input('estado_id');
            $direccion->municipio_id = $request->input('municipio_id');
            $direccion->localidad_id = $request->input('localidad_id');
            $direccion->calle = $request->input('calle_numero');
            $direccion->cp = $request->input('codigo_postal');
            $direccion->save();
        }

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado correctamente')
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
        return redirect()->route('users.index')
            ->with('success', 'Usuario eliminado correctamente');
    }
}
