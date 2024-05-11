<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;
use Pusher\Pusher;

class NotificationHelper
{
    public static function notificacionUsuario($pedidoID, $userId, $message, $titulo = 'Pedido Abierto')
    {        

        $notification = new Notification();
        $notification->pedido_id = $pedidoID;
        $notification->user_id = $userId;
        $notification->mensaje = $message;
        $notification->titulo  = $titulo;
        $notification->save();
    }

    public static function notificacionAdmin($message, $pedidoID, $titulo = 'Pedido Solicitado', $tiendaID)
    {
        $typeOneUsers = User::where('tipo', 1)->where('tienda_id', $tiendaID)->cursor();

        foreach ($typeOneUsers as $user) {
            self::notificacionUsuario($pedidoID, $user->id, $message, $titulo);
        }
    }



    public static function notificacionUsuarioCupon($userId, $message)
    {
        $notification = new Notification();
        $notification->pedido_id = null;
        $notification->user_id = $userId;
        $notification->mensaje = $message;
        $notification->titulo = 'Cupon Disponible';
        $notification->save();
    }

    public static function notificacionUsuarioAll($message)
    {
        $typeOneUsers = User::where(function ($query) {
            $query->where('tipo', 3)
                ->orWhere(function ($query) {
                    $query->where('tipo', 2)
                        ->whereNull('distribuidor_id');
                });
        })->get();

        foreach ($typeOneUsers as $user) {
            self::notificacionUsuarioCupon($user->id, $message);
        }
    }
    public static function ejecutarNotificaciones()
    {

        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true
        ]);

        // Enviar la notificación a través de Pusher
        $pusher->trigger('notifications', 'new-notification', ['message' => 'Tienes una nueva notificación.']);
    }
}
