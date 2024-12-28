<?php

use Illuminate\Support\Facades\Broadcast;

// Notificación usuario especifico
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('App.Models.Role.{id}', function ($user, $id) {
    return $user->hasRole(intval($id));
});

// Usuarios en linea
Broadcast::channel('online', function ($user) {
    return $user;
});

// Notificación global
Broadcast::channel('Global', function ($user) {
    return $user->id !== null;
});