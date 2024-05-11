<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use App\Models\Notification;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;



class NotificacionesController extends BaseController
{


    public function getNotifications($id)
    {
        //dd($id);
        $user = User::find($id);

        $notifications = $user->unreadNotifications; // Convertir las notificaciones en una matriz

        $notificationCount = count($notifications); // Contar las notificaciones

        return response()->json([
            'notifications' => $notifications,
            'count' => $notificationCount
        ]);
    }
    public function markAsRead($id)
    {
        // Marcar las notificaciones como leídas
        Notification::where('user_id', $id)->update(['read' => true]);

        return response()->json(['success' => true]);
    }
}
