<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cupons;
use Illuminate\Support\Str;
use App\Events\NewNotificationEvent;

class NotificationController extends Controller
{
  public function sendNotification()
  {
    // Lógica para enviar la notificación

    // Emitir el evento de notificación a través de Pusher
    event(new NewNotificationEvent($notificationData));
  }
}
