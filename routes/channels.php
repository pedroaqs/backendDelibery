<?php

use Illuminate\Support\Facades\Log;
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

// This is only for testing purposes
Broadcast::channel('pedidosreload', function () {
    return true;
});

Broadcast::channel('pedidoentregado', function () {
    return true;
});

// This is probably closer to what most would use in production
Broadcast::channel('user.{id}', function ($user, $id) {
    //return true if api user is authenticated
    return (int) $user->id === (int) $id;
});
