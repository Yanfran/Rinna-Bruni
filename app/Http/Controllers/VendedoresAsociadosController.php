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
use GuzzleHttp\Client;
use App\Traits\TraitApiCROL;
use App\Enums\UserType;


class VendedoresAsociadosController extends Controller
{
    use TraitApiCROL;

    function __construct()
    {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {

        $perPage = $request->query('perPage', 10);
        $sortBy = $request->query('sortBy', 'name');
        $sortOrder = $request->query('sortOrder', 'asc');
        $vendedorNombre = $request->query('vendedorNombre', '');
        $estatus = $request->query('estatus', '');

        $query = User::query();

        if ($vendedorNombre) {
            $query->where('name', 'like', '%' . $vendedorNombre . '%');
        }

        if ($estatus !== '') {
            $query->where('estatus', $estatus);
        }

        $vendedores = $query->where('tipo', '!=' , 3)->where('tipo', '!=' , 1)->where('distribuidor_id', $user->id)->orderBy($sortBy, $sortOrder)->paginate($perPage);

        // $vendedores->load('direccionesSucursale');



        //  if ($request->user()->tipo === 3) {
        //     // Obtener el distribuidor logueado
        //     $distribuidor = $request->user();

        //     // Obtener todos los vendedores asociados a ese distribuidor
        //     $vendedoresAsociados = $distribuidor->vendedores()->paginate($perPage);
        //     $vendedoresAsociados->load('direccionesSucursale');

        //     return view('vendedoresAsociados.index', [
        //         'vendedores' => $vendedoresAsociados,
        //         'i' => ($vendedores->currentPage() - 1) * $vendedores->perPage(),
        //         'perPage' => $perPage,
        //         'sortBy' => $sortBy,
        //         'sortOrder' => $sortOrder,
        //         'vendedorNombre' => $vendedorNombre,
        //         'estatus' => $estatus,
        //     ]);
        // }


        $limite_cuentas_creadas = $user->cuentas_restantes;
        $tienda_id = $user->tienda_id;
        $tiendas = Tiendas::find($tienda_id);
        $estatusTienda = $tiendas->estatus;


        $vendedores->appends([
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'vendedorNombre' => $vendedorNombre,
            'estatus' => $estatus,
        ]);

        return view('vendedoresAsociados.index', [
            'vendedores' => $vendedores,
            'i' => ($vendedores->currentPage() - 1) * $vendedores->perPage(),
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'vendedorNombre' => $vendedorNombre,
            'estatus' => $estatus,
            'user' => $user,
            'limite_cuentas_creadas' => $limite_cuentas_creadas,
            'estatusTienda' => $estatusTienda
        ]);






        // $limite_cuentas_creadas = $user->cuentas_restantes;
        // $tienda_id = $user->tienda_id;
        // $tiendas = Tiendas::find($tienda_id);
        // $estatusTienda = $tiendas->estatus;

        // dd($estatusTienda);

        // $data = User::where('tipo', 2)->where('distribuidor_id', $user->id)->orderBy('id','DESC')->paginate(5);
        // return view('vendedoresAsociados.index',compact('data', 'user', 'limite_cuentas_creadas','estatusTienda'))
        //     ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        $distribuidor= $user;


        // dd($idTienda);
        $idTienda = $distribuidor->tienda_id;
        $tiendas = Tiendas::where('estatus', '!=' , 0)->get();

        $id_direcciones = $distribuidor->id;
        $direcciones = Direcciones::where('estatus', '!=', '0')->where('user_id', $id_direcciones)->get();
        $estados = Estados::all();
        return view('vendedoresAsociados.create',compact('tiendas','estados', 'distribuidor', 'direcciones' , 'idTienda'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Client $client)
    {
        dd('aca no es');
        $this->validate($request, [
            'email' => 'required|min:3|email|unique:users,email',
            'password' => 'required|min:5|same:confirm-password',
            'confirm-password' => 'required|min:5|same:password',
            'name' => 'required|min:2',
            'apellido_paterno' => 'required|min:2',
            'numero_afiliacion' => 'required|unique:users,numero_afiliacion',
            'tienda_id' => 'required',
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

        if ($request->has('estatus')) {
            $request->merge(['estatus' => 1]);
        } else {
            $request->merge(['estatus' => 0]);
        }
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        //Consumo de Servicio Creacion de Contactos en el erp CROL
        $request->merge([
            'tipo_contacto' => $input['isAsociate']
                                ? UserType::Asociado->value
                                : UserType::Independiente->value
        ]);

        $statusCreaContactoCrol = $this->CROL_createContact($request, $client);

        if($statusCreaContactoCrol == 200) {
            $vendedores = User::create($input);
            $vendedores->assignRole($request->input('roles'));
            $id = $vendedores->id;

            $direccion = new Direcciones();
            $direccion->user_id = $id; // Asigna el ID del usuario
            $direccion->alias = $request->input('domicilio');
            $direccion->estado_id = $request->input('estado_id');
            $direccion->municipio_id = $request->input('municipio_id');
            $direccion->localidad_id = $request->input('localidad_id');
            $direccion->calle = $request->input('calle_numero');
            $direccion->tipo = 1;
            $direccion->cp = $request->input('codigo_postal');
            // $direccion->colonia = $request->input('ciudad');
            $direccion->estatus = 1;
            $direccion->save();


            return redirect()->route('vendedoresAsociados.index')
                            ->with('success','Vendedor creado correctamente')
                            ->withInput($request->input());
        }
        else {
            return redirect()->route('vendedoresAsociados.index')
            ->with('error','Hubo un problema al crear el Vendedor')
            ->withInput($request->input());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vendedores = User::find($id);

        if ($vendedores) {
            $idRol = $vendedores->rol;

            if ($idRol !== 0) {
                $roles = Role::where('id', $idRol)->first();
            } else {
                $roles = (object) ['name' => 'Sin Rol'];
            }
        } else {
            $roles = null;
        }


        $idTienda = $vendedores->tienda_id;
        $tiendas = Tiendas::where('id', $idTienda)->first();

        $idUser = $vendedores->id;
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

        $distribuidor = User::find($vendedores->distribuidor_id);

        return view('vendedoresAsociados.show',compact('distribuidor', 'vendedores','roles','tiendas','estados','municipios','localidad','direcciones'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vendedores = User::find($id);

        $idTienda = $vendedores->tienda_id;
        $tiendas = Tiendas::pluck("nombre", "id");

        $estados = Estados::all();
        $distribuidor = User::find($vendedores->distribuidor_id);
        $direcciones = Direcciones::where('user_id', $vendedores->id)->first();
        $distribuidores = User::where('tipo', '3')->where('estatus', 1)->get();
        // dd($direcciones);
        return view('vendedoresAsociados.edit',compact('direcciones','estados','vendedores', 'distribuidor', 'tiendas','idTienda','distribuidores'));
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

        $distribuidorAsociado = $input['distribuidor_asociado'];

        // if ($request->has('estatus') == true) {
        //     $input['estatus'] = 1;
        // } else {
        //     $input['estatus'] = 0;
        // }


        $vendedores = User::find($id);
        $vendedores->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $vendedores->assignRole($request->input('roles'));

        $direccion = Direcciones::where('user_id', $id)->first();

        if ($direccion) {
            $direccion->alias = $request->input('domicilio_name');
            $direccion->estado_id = $request->input('estado_id');
            $direccion->municipio_id = $request->input('municipio_id');
            $direccion->localidad_id = $request->input('localidad_id');
            // $direccion->colonia = $request->input('ciudad');
            $direccion->calle = $request->input('calle_numero');
            $direccion->save();
        }

        return redirect()->route('vendedoresAsociados.index', $distribuidorAsociado)
                        ->with('success','Vendedor actualizado correctamente')
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
        return redirect()->route('vendedoresAsociados.index')
                        ->with('success','Vendedor eliminado correctamente');

        // User::find($id)->delete();
        // return redirect()->route('vendedores.index')
        //                 ->with('success','User deleted successfully');
    }
}
