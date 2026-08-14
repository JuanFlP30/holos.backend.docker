<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'Estas credenciales no coinciden con nuestros registros.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Demasiados intentos de acceso. Por favor inténtelo de nuevo en :seconds segundos.',
    'forgot' => [
        'subject' => 'Recuperación de contraseña',
        'description' => 'Por favor, haga clic en el siguiente enlace para restaurar su contraseña. El enlace expira en 15 minutos.',
        'reset' => 'Restaurar contraseña',
    ],
    'token' => [
        'not_exists' => 'El token no existe.',
        'expired' => 'El token caducado.',
        'both' => 'El token no existe o ha caducado.',
    ]
];
