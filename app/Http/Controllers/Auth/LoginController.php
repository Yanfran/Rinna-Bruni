<?php

namespace App\Http\Controllers\Auth;

use App;
use App\Http\Controllers\Controller;
use App\Models\Empresas;
use App\Models\Slider;
use App\Models\User;
use Config;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */
    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    public function LoginForm($id = null)
    {

        //dd($id);
        if ($id == null) {


            return redirect()->route('inicio');
        }
        if (empty($id)) {
            $id = Empresa::where('id', "!=", 0)->inRandomOrder()->first()->id;
        }

        $empresa = Empresas::find($id);


        if ($empresa) {
            $slider  = Slider::where('empresas_id', $empresa->getID())->where('estatus', 1)->get();

            return view('auth.login', compact('empresa', 'slider'));
        } else {

            return view('errors.404');
        }
    }

    public function logout(Request $request)
    {
        $dir = '/';

        if (\Auth::user()) {
            $dir = '/';
        }
        $this->guard()->logout();
        $sessionLifetime = intval(DB::table('empresas')->value('inactividad')) ?: 120;
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect($dir)->withCookie(cookie('locale', Session::get('locale', App::getLocale()),  config(['session.lifetime' => $sessionLifetime])));
    }

    public function username()
    {
        $loginValue = request()->input('email'); // Cambia 'email' por el nombre del campo de entrada de correo electrónico en tu formulario de inicio de sesión
        $field = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'usuario';
        request()->merge([$field => $loginValue]);
        return $field;
    }
    public function login(Request $request)
    {
        $input = $request->only($this->username(), 'password');
        $usuario = DB::table('users')
                   ->where('email', $input['email'])
                   ->where('tipo','!=',2) //limita acceso a vendedores asociados
                   ->where('tipo','!=',4) //limita acceso a vendedores independientes
                   ->get();

        if(count($usuario) == 0){
            return redirect()->back()
                    ->withInput($request->only($this->username(), 'remember'))
                    ->with('error','Las credenciales ingresadas son incorrectas.');
        }

        if ($usuario[0]->email == 'userapprinna@gmail.com') {
            return redirect()->back()
                    ->withInput($request->only($this->username(), 'remember'))
                    ->with('error','Usuario restringido para el panel administratico.');
        }

        if ($usuario[0]->distribuidor_id) {

            $distribuidor_id = $usuario[0]->distribuidor_id;
            $usuarioVerificarEstatus = DB::table('users')->where('id', $distribuidor_id)->get();

            if ($usuarioVerificarEstatus[0]->estatus == 0) {
                return redirect()->back()
                    ->withInput($request->only($this->username(), 'remember'))
                    ->with('error','El distribuidor principal está deshabilitado.');
            }
        }


        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request, $input)) {
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    protected function attemptLogin(Request $request, array $credentials)
    {
        $field = $this->username();

        return $this->guard()->attempt(
            array_merge($credentials, [$field => $request->input($this->username())]),
            $request->filled('remember')
        );
    }




    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->with('error','Las credenciales proporcionadas no son válidas.');
    }

    protected function authenticated(Request $request, $user)
    {

        $empresa = (int) $request->empresas_id;
        $e_u     = $user->empresas_id;


        if ($empresa != 0) {
            $sessionLifetime = intval(DB::table('empresas')->value('inactividad')) ?: 120;


            if ($user->CanLoginFromWeb()) {
                if ($user->getEstatusValue() != 0) {
                    return redirect()->intended('/home')->withCookie(cookie('locale', Session::get('locale', App::getLocale()), config(['session.lifetime' => $sessionLifetime])));
                } else {

                    $this->guard()->logout();
                    return redirect()->back()->with('error', trans('auth.inactive'));
                }
            } else {
                $this->guard()->logout();
                return redirect()->back()->with('error', trans('auth.web'));
            }



            if ($empresa != $e_u) {
                $this->guard()->logout();
                $request->session()->invalidate();
                // return view('auth-error');
                return redirect()->back()->with('error', trans('auth.empresa'));
            }
        }
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect()->back()->with('error', trans('registro.msg_error'));
    }
}
