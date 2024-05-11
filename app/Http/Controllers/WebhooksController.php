<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Empresas;
use App\Helpers\NotificationHelper;

class WebhooksController extends Controller
{
    public function handleMercadoPagoWebhook(Request $request) {
        // return $request->all();

        // $paymentId = $request->get('payment_id');
        // $empresa = Empresas::first();
        // $mp_access_token = $empresa->mp_access_token;

        // $response = Http::get("https://api.mercadopago.com/v1/payments/$paymentId". "?access_token=$mp_access_token");

        $msg = "prueba de webhooks con exito";
        NotificationHelper::notificacionUsuario(397, 1, $msg, 'Pedido Pagado');

        return response()->json(['estatus' => '200']);
    }
}
