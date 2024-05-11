<?php

use App\Models\User;

Broadcast::channel('notifications.{userId}', function (User $user, $userId) {
    return (int) $user->id === (int) $userId;
});
