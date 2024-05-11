<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use App\Models\Empresas;
// ------------------ faltaba
use App\Models\Direcciones;
use Spatie\Permission\Models\Role;
use App\Models\Tiendas;
use App\Models\Estados;
use App\Models\Municipios;
use App\Models\Localidads;
// ------------------ faltaba
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use DB;
use Hash;
use Illuminate\Support\Arr;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|same:confirm-password',
            'roles' => 'required',
            'tienda_id' => 'required',
            'apellido_paterno' => 'required',
            'estado_id' => 'required',
            'municipio_id' => 'required',
            'localidad_id' => 'required',
            'codigo_postal' => 'required',
            'calle_numero' => 'required',
            'celular' => 'required',
            'telefono_fijo' => 'required',
        ]);


        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if ($request->has('estatus')) {
            $request->merge(['estatus' => 1]);
        } else {
            $request->merge(['estatus' => 0]);
        }
        $input = $request->all();
        $idDistri = isset($input['distribuidor_id']) ? $input['distribuidor_id'] : null;
        $idTienda = $input['tienda_id'];

        $input['password'] = Hash::make($input['password']);

        $vendedores = User::create($input);

        if ($input['isAsociate'] == 'true') {

            $id = $vendedores->id;
            $afiliaciones =     \App\Helpers\GlobalHelper::generateAfiliacionCode('SD', $id, $idTienda, $idDistri);
            $vendedores->numero_afiliacion = $afiliaciones;
            $vendedores->save();

        } else {

            $id = $vendedores->id;
            $afiliaciones =     \App\Helpers\GlobalHelper::generateAfiliacionCode('S', $id, $idTienda);
            $vendedores->numero_afiliacion = $afiliaciones;
            $vendedores->save();

        }



        $vendedores->assignRole($request->input('roles'));
        $id = $vendedores->id;

        $direccion = new Direcciones();
        $direccion->user_id = $id; // Asigna el ID del usuario
        $direccion->alias = $request->input('alias');
        $direccion->estado_id = $request->input('estado_id');
        $direccion->municipio_id = $request->input('municipio_id');
        $direccion->localidad_id = $request->input('localidad_id');
        $direccion->calle = $request->input('calle_numero');
        $direccion->tipo = 1;
        $direccion->cp = $request->input('codigo_postal');
        $direccion->estatus = 1;
        $direccion->save();

        $success['token'] =  $vendedores->createToken('MyApp')->plainTextToken;
        $success['name'] =  $vendedores->name;

        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {


        $email = $request->email;
        $usuarioVeri = DB::table('users')->where('email', $email)->get();


        /*Se valida el acceso solo a Vendedores Asociados e Independientes*/
        if ($usuarioVeri[0]->tipo !=2 && $usuarioVeri[0]->tipo !=4) {
            return $this->sendError('Solo se admiten usuarios vendedores', ['error' => 'Unauthorised']);
        }

        if ($usuarioVeri[0]->distribuidor_id) {

            $distribuidor_id = $usuarioVeri[0]->distribuidor_id;
            $usuarioVerificarEstatus = DB::table('users')->where('id', $distribuidor_id)->get();

            if ($usuarioVerificarEstatus[0]->estatus == 0) {
                return $this->sendResponse('error','El distribuidor principal está deshabilitado.');
            }
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $estado_nombre = null;
            $municipio_nombre = null;
            $localidad_nombre = null;
            $direccion = $user->Direcciones;
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            if ($user->tipo == 1) {

                $rolname = 'Administrador';
            } elseif ($user->tipo == 2) {
                $rolname = 'Vendedor';
            } elseif ($user->tipo == 3) {
                $rolname = 'Distribuidor';
            } elseif ($user->tipo == 4) {
                $rolname = 'Especial';
            }
            $success['DatosGenerales'] = [
                'userID' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'tipo' => $user->tipo,
                'rol' => $user->rol,
                'tipo_usuario' => $rolname,
                'usuario' => $user->usuario,
                'numero_empleado' => $user->numero_empleado,
                'tienda_id' => $user->tienda_id,
                'fecha_ingreso' => $user->fecha_ingreso,
                'fecha_nacimiento' => $user->fecha_nacimiento,
                'celular' => $user->celular,
                'telefono_fijo' => $user->telefono_fijo,
                'descuento' => $user->descuento,
                'credito' => $user->credito,
                'observaciones' => $user->observaciones,
                'apellido_paterno' => $user->apellido_paterno,
                'apellido_materno' => $user->apellido_materno,
                'numero_afiliacion' => $user->numero_afiliacion,
                'nombre_empresa' => $user->nombre_empresa,
                'rfc' => $user->rfc,
                'regimen_fiscal' => $user->regimen_fiscal,
                'dia_credito' => $user->dia_credito,
                'descuento_oferta' => $user->descuento_oferta,
                'descuento_outlet' => $user->descuento_outlet,
                'distribuidor_id' => $user->distribuidor_id,
                'descuento_clientes' => $user->descuento_clientes
            ];

            if($user->tipo == 2 AND $user->distribuidor_id != null){

                    $dis = User::find($user->distribuidor_id);
                    $success['Distribuidor'] = [
                        'distribuidorID' => $dis->id,
                        'name' => $dis->name,
                        'email' => $dis->email,
                        'apellido_paterno' => $dis->apellido_paterno,
                        'apellido_materno' => $dis->apellido_materno,
                        'numero_afiliacion' => $dis->numero_afiliacion,
                        'nombre_empresa' => $dis->nombre_empresa,
                        'rfc' => $dis->rfc,
                        'regimen_fiscal' => $dis->regimen_fiscal,

                    ];

                }else{
                    $success['Distribuidor'] = null;
                }



            if ($direccion) {
                // Obtener los nombres de estado, municipio y localidad
                $estado_nombre = $direccion->Estado->nombre;
                $municipio_nombre = $direccion->Municipio->nombre;
                $localidad_nombre = $direccion->Localidad->nombre;
                $success['Direccion'] =  [
                    'id' => $direccion->id,
                    'alias' => $direccion->alias,
                    'estado_id' => $direccion->estado_id,
                    'municipio_id' => $direccion->municipio_id,
                    'localidad_id' => $direccion->localidad_id,
                    'calle' => $direccion->calle,
                    'estatus' => $direccion->estatus,
                    'estado_nombre' => $estado_nombre,
                    'municipio_nombre' => $municipio_nombre,
                    'localidad_nombre' => $localidad_nombre,
                    'ciudad_nombre' => $direccion->Localidad->ciudad,
                    'cp' => $direccion->Localidad->cp
                ];
            } else {
                $success['Direccion'] =  null;
            }

            if ($user->tipo == 3) {
                $sucursales = $user->Sucursales;
                $success['Sucursales'] = [];

                foreach ($sucursales as $sucursal) {
                    $success['Sucursales'][] = [
                        'sucursalID' => $sucursal->id,
                        'alias' => $sucursal->alias,
                        'estado_id' => $sucursal->estado_id,
                        'municipio_id' => $sucursal->municipio_id,
                        'localidad_id' => $sucursal->localidad_id,
                        'calle' => $sucursal->calle,
                        'estatus' => $sucursal->estatus,
                        'estado_nombre' => $sucursal->Estado->nombre,
                        'municipio_nombre' => $sucursal->Estado->nombre,
                        'localidad_nombre' => $sucursal->Estado->nombre,
                        'ciudad_nombre' => $sucursal->Localidad->ciudad,
                        'cp' => $sucursal->Localidad->cp,
                        'nombre_encargado' => $sucursal->nombre_encargado,
                        'celular' => $sucursal->celular,
                        'telefono_fijo' => $sucursal->telefono_fijo
                        // Agrega aquí más elementos si deseas incluir más información de la sucursal
                    ];
                }
            }


            $tienda = $user->Tienda;
            if ($tienda) {
                $estado_nombre =    $tienda->Estado->nombre;
                $municipio_nombre = $tienda->Municipio->nombre;
                $localidad_nombre = $tienda->Localidad->nombre;

                $success['Tienda'] =  [
                    'id' => $tienda->id,
                    'codigo' => $tienda->codigo,
                    'estado_id' => $tienda->estado_id,
                    'municipio_id' => $tienda->municipio_id,
                    'localidad_id' => $tienda->localidad_id,
                    'estatus' => $tienda->estatus,
                    'estado_nombre' => $estado_nombre,
                    'municipio_nombre' => $municipio_nombre,
                    'localidad_nombre' => $localidad_nombre,
                    // 'ciudad_nombre' => $direccion->Localidad->ciudad,
                    'cp' => $tienda->cp,
                    'calle_numero' => $tienda->calle_numero
                ];
            }else{
                  $success['Tienda'] = null;
            }


            return $this->sendResponse($success, 'Usuario logueado.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    public function getColors(Request $request): JsonResponse
    {

        $data = Empresas::find(1);
        $data->logo = asset('uploads/logos/' . $data->logo);
        return $this->sendResponse($data, 'Datos de configuracion.');
    }


}
