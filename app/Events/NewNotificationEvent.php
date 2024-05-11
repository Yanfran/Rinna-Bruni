<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class NewNotificationEvent implements ShouldBroadcast
{
  use SerializesModels;

  public $notification;

  public function __construct($notification)
  {
    $this->notification = $notification;
  }

  public function broadcastOn()
  {
    return new Channel('notifications');
  }
}
