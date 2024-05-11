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
use Auth;
use Illuminate\Support\Arr;

class PerfilController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:user-menu|user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    public function index($userId)
    {                         
        $data = User::find($userId);
        $roles = Role::pluck('name','id');
        $userRole = $data->roles->pluck('name','name')->all();

        $tiendas = Tiendas::all();
        $idTienda = User::find(auth()->user()->id)->tienda_id;        

        $estados = Estados::all();
        $idUser = $data->id;
        $direcciones = Direcciones::where('user_id', $idUser)->first();

        $distribuidores = User::get();
        
               
        return view('perfil.index',compact('data','roles','userRole','tiendas','idTienda','estados','idUser','direcciones','distribuidores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {      
       

        return redirect()->route('users.index')
                        ->with('success','Usuario creado correctamente')
                        ->withInput($request->input());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {                
        
        $this->validate($request, [            
            // 'email' => 'required|min:3|email|unique:users,email',            
            'name' => 'required',    
            // 'usuario' => 'required',                      
            // 'numero_empleado' => 'required',
            'password' => 'nullable|min:5|same:confirm-password',
            'confirm-password' => 'nullable|min:5|same:password',
            'estado_id' => 'required',
            'municipio_id' => 'required',
            'localidad_id' => 'required',                    
            'codigo_postal' => 'required|min:5',
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

        $userId = $request->input('userId');
            
        $user = User::find($userId);
        $user->update($input);
        // $user->fill($request->all());
        // $user->save();

        $direccion = Direcciones::where('user_id', $userId)->first();

        if ($direccion) {       
            $direccion->alias = '';     
            $direccion->estado_id = $request->input('estado_id');
            $direccion->municipio_id = $request->input('municipio_id');
            $direccion->localidad_id = $request->input('localidad_id');
            $direccion->calle = $request->input('calle_numero');
            $direccion->cp = $request->input('codigo_postal');
            $direccion->colonia = $request->input('ciudad');
            $direccion->save();
        } else {
            $direccion = new Direcciones(); 
            $direccion->user_id = $userId;
            $direccion->alias = '';
            $direccion->estado_id = $request->input('estado_id');
            $direccion->municipio_id = $request->input('municipio_id');
            $direccion->localidad_id = $request->input('localidad_id');
            $direccion->calle = $request->input('calle_numero');
            $direccion->cp = $request->input('codigo_postal');
            $direccion->colonia = $request->input('ciudad');
            $direccion->save();
        }

        return redirect()->route('perfil.index', ['userId' => $userId])
                        ->with('success','Perfil actualizado correctamente')
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
    }

}
