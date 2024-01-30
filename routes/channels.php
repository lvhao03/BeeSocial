<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('notification.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('private.{room_id}', function ($user, $room_id) {
    if ($user->id + session('receiver_id') == $room_id){
        return true;
    }
});

Broadcast::channel('presence-chat.{sender_id}.{receiver_id}', function ($user, $sender_id, $receiver_id) {
    return ['user_id' => $user->id];
});

