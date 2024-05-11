<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Empresas;
use App\Models\Slider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */
    use RegistersUsers;
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationFormByCompany($id = null)
    {
        $empresa = Empresas::find($id);
        if (!empty($empresa->id)) {
            return view('auth.register', compact(
                'empresa'
            ));
        }
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return Response
     */
    public function register(Request $request)
    {
        $user     = new User();
        $data     = new DataUsuario();
        $contacto = new DataContacto();

        $vend       = [];
        $vendEstado = [];
        $vendTam    = [];
        $vendGiro   = [];
        $final      = [];

        $vendedores = User::where('empresas_id', $request->empresa_id)->where('rol', 8)->get();

        foreach ($vendedores as $k) {
            $d = DataUsuario::where('usuario_id', $k->id)->first();
            if ($d->getPaisId() == $request->pais_id) {
                //asignacion de vendedores del mismo pais
                array_push($vend, $d->getUsuarioId());
            }
        }
        foreach ($vend as $v => $value) {
            $d     = DataUsuario::where('usuario_id', $value)->first();
            $zonas = $d->getZonasValue();
            foreach ($zonas as $z) {
                $estados = Estado::where('zona_id', $z)->get();
                foreach ($estados as $e) {
                    if ($request->estado_id == $e->getID()) {
                        array_push($vendEstado, $d->getUsuarioId());
                    }
                }
            }
        }
        foreach ($vendEstado as $v => $value) {
            $f   = DataUsuario::where('usuario_id', $value)->first();
            $tam = $f->getTamValue();
            foreach ($tam as $t) {
                if ($request->tam_id == $f->getTam($t)->getID()) {
                    array_push($vendTam, $f->getUsuarioId());
                }
            }
        }
        foreach ($vendTam as $v => $value) {
            $f    = DataUsuario::where('usuario_id', $value)->first();
            $giro = $f->getGiro();
            if ($request->giro_id == $giro->getID()) {
                array_push($vendGiro, $f->getUsuarioId());
            }
        }
        if ($vendGiro != null) {
            foreach ($vendGiro as $key => $value) {
                $count_1 = DataUsuario::where('vendedor_id', $value)->count();
                $array   = [
                    'user'  => $value,
                    'count' => $count_1,
                ];
                array_push($final, $array);
            }
        } elseif ($vendTam != null) {
            foreach ($vendTam as $key => $value) {
                $count_1 = DataUsuario::where('vendedor_id', $value)->count();
                $array   = [
                    'user'  => $value,
                    'count' => $count_1,
                ];
                array_push($final, $array);
            }
        } elseif ($vendEstado != null) {
            foreach ($vendEstado as $key => $value) {
                $count_1 = DataUsuario::where('vendedor_id', $value)->count();
                $array   = [
                    'user'  => $value,
                    'count' => $count_1,
                ];
                array_push($final, $array);
            }
        } else {
            foreach ($vend as $key => $value) {
                $count_1 = DataUsuario::where('vendedor_id', $value)->count();
                $array   = [
                    'user'  => $value,
                    'count' => $count_1,
                ];
                array_push($final, $array);
            }
        }
        $count = 0;
        foreach ($final as $key => $value) {
            $count = $value['count'];
            if ($count >= $value['count']) {
                $vendedor_id = $value['user'];
            }
        }

        $this->validatorRegistro($request->all())->validate();
        $user->setName($request->nombre)
            ->setEmpresaId($request->empresa_id)
            ->setPass($request->password)
            ->setRol(10)
            ->setEstatus(1)
            ->setEmail($request->email)
            ->push();

        //insercion de datos de usuarios
        $data->setRazonSocial($request->razonSocial)
            ->setDescripcion($request->descripcion)
            ->setProductosFabricacion($request->productosFabricacion)
            ->setHorarioDesde($request->horarioDesde)
            ->setHorarioHasta($request->horarioHasta)
            ->setHorarioHasta($request->horarioHasta)
            ->setCompetidores($request->competidores)
            ->setGiro($request->giro_id)
            ->setSubgiro($request->subgiro_id)
            ->setSubgiro($request->subgiro_id)
            ->setTamEmpresa($request->tam_id)
            ->setEstado($request->estado_id)
            ->setUsuarioId($user->id)
            ->setPaisId($request->pais_id)
            ->setProductosCotizacion($request->productosCotizacion)
            ->setDescripcion($request->descripcionGiro)
            ->setCompetidores($request->competidores)
            ->setVendedor($vendedor_id)
            ->push();

        // registro de vendedor en match en tabla data usuario

        $vendedor              = new Vendedores();
        $vendedor->usuario_id  = $user->id;
        $vendedor->match       = 1;
        $vendedor->vendedor_id = $vendedor_id;
        $vendedor->push();

        $contacto
            ->setPrimerNombre($request->primerNombre)
            ->setSegundoNombre($request->segundoNombre)
            ->setApellido($request->apellido)
            ->setPosicion($request->posicion)
            ->setTelefono($request->telefonoContacto)
            ->setExt($request->extContatco)
            ->setMovil($request->movilContatco)
            ->setEmail($request->emailContacto)
            ->setUsuarioId($user->id)
            ->push();

        return redirect()->route('login', ['id' => $request->empresa_id])->with('success', trans('empresas.succes_insert_user'));
        $this->guard()->login($user);
        return $this->registered($request, $user)
        ?: redirect($this->redirectPath());
    }

    protected function validatorRegistro(array $data)
    {
        return Validator::make($data, [
            'nombre'          => 'required|string|min:2',
            'email'           => 'required|string|email|unique:users',
            'password'        => 'required|string|min:6|confirmed',
            'razonSocial'     => 'required|string|min:3',
            'descripcionGiro' => 'required|string|min:6',
            'primerNombre'    => 'required|string|min:2',
            'apellido'        => 'required|string|min:2',
            'emailContacto'   => 'required|email|string|min:6',
        ]);
    }
}
