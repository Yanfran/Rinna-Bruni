<?php

	namespace App\Http\Controllers\Api;

	use App\Http\Controllers\Controller;
	use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Password;

	class ForgotPasswordController extends Controller
	{
		use SendsPasswordResetEmails;

		/**
		 * Create a new controller instance.
		 */
		public function __construct()
		{
			$this->middleware('guest');
		}

		public function reset(Request $request)
		{



			// We will send the password reset link to this user. Once we have attempted
			// to send the link, we will examine the response then see the message we
			// need to show to the user. Finally, we'll send out a proper response.
			$response = $this->broker()->sendResetLink(
				$request->only('email')
			);
			return $response == Password::RESET_LINK_SENT
				? response()->json(['message' => 'Correo enviado correctamente', 'status' => true], 201)
				: response()->json(['message' => 'Correo inválido', 'status' => false], 401);
		}
	}
