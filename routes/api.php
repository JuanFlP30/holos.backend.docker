<?php
use Illuminate\Support\Facades\Route;

/**
 * Rutas del núcleo de la aplicación.
 * 
 * Se recomienda que no se modifiquen estas rutas a menos que sepa lo que está haciendo.
 */
require('core.php');

/**
 * Rutas de tu aplicación.
 * 
 * Estas rutas son de la aplicación API que desarrollarás. Siéntete libre de agregar lo que consideres necesario.
 * Procura revisar que no existan rutas que entren en conflicto con las rutas del núcleo.
 */

/** Rutas protegidas (requieren autenticación) */
Route::middleware('auth:api')->group(function() {
    // Tus rutas protegidas
});

/** Rutas públicas */
// Tus rutas públicas
