<?php

	namespace App\Http\Controllers\Auth;

	use App\Http\Controllers\Controller;
    use App\Models\Empresas;
    use App\Models\Slider;
	use Hash;
	use Illuminate\Auth\Events\PasswordReset;
	use Illuminate\Foundation\Auth\ResetsPasswords;
	use Illuminate\Http\Request;
	use Illuminate\Support\Str;
	use Password;
	use Flash;

	class ResetPasswordController extends Controller
	{
		/*
		|--------------------------------------------------------------------------
		| Password Reset Controller
		|--------------------------------------------------------------------------
		|
		| This controller is responsible for handling password reset requests
		| and uses a simple trait to include this behavior. You're free to
		| explore this trait and override any methods you wish to tweak.
		|
		*/
		use ResetsPasswords;
		/**
		 * Where to redirect users after resetting their password.
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

		public function showLinkRequestFormCompany($id = null)
		{
			$empresa = Empresas::find($id);
			$slider = Slider::where('empresas_id', $id)->where('estatus', 1)->get();
			if (!empty($empresa->id)) return view('auth.passwords.email', compact('empresa', 'slider'));
			return view('auth.passwords.email', 'slider');
		}

		public function reset(Request $request)
		{
			$this->validate($request, [				
				'password' => 'required|min:5|same:password_confirmation',
				'password_confirmation' => 'required|min:5|same:password',				
			], [				
				'password.required' => 'El campo clave es obligatorio.',
				'password.min' => 'La contraseña debe contener al menos 5 caracteres.',
				'password_confirmation.required' => 'El campo verificar contraseña es obligatorio.',
				'password_confirmation.min' => 'El campo verificar contraseña debe contener al menos 5 caracteres.',				
			]);

			// $this->validate($request, $this->rules(), $this->validationErrorMessages());
			
			// Here we will attempt to reset the user's password. If it is successful we
			// will update the password on an actual user model and persist it to the
			// database. Otherwise we will parse the error and return the response.
			$response = $this->broker()->reset(
				$this->credentials($request),
				function ($user, $password) {
					$this->resetPassword($user, $password);
				}
			);
			// If the password was successfully reset, we will redirect the user back to
			// the application's home authenticated view. If there is an error we can
			// redirect them back to where they came from with their error message.
			return $response == Password::PASSWORD_RESET
				? $this->sendResetResponse($response)
				: $this->sendResetFailedResponse($request, $response);
		}

		protected function resetPassword($user, $password)
		{
			

			$user->password = Hash::make($password);
			$user->setRememberToken(Str::random(60));
			$user->save();
			// flash('Su contraseña se ha cambiado con éxito')->success();

			$token = $user->getRememberToken();
			
			event(new PasswordReset($user));
			if ($user->CanLoginFromWeb()) {
				$this->guard()->login($user);
			} else {
				$empresa = $user->empresas_id;
				$this->redirectTo = "/login/$empresa";
			}
			
			return redirect()->route('password.reset', ['token' => $token])
				->with('success', 'Contraseña actualizada con éxito');
			

		}

		protected function sendResetResponse($response)
		{
			return redirect($this->redirectPath())
				->with('status', trans($response));
		}
	}
