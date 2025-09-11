<?php

use Database\Seeders\DevSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Solo ejecutar en desarrollo y si no existen datos
        if ($this->shouldSeed()) {
            (new DevSeeder)->run();

            $this->createPassportClient();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }

    /**
     * Verificar si debe ejecutar el seeding
     */
    private function shouldSeed(): bool
    {
        // Verificar si ya existen usuarios del sistema
        $usersExist = DB::table('users')
            ->whereIn('email', ['developer@notsoweb.com', 'admin@notsoweb.com'])
            ->exists();

        return !$usersExist;
    }

    /**
     * Crear cliente Passport si no existe
     */
    private function createPassportClient(): void
    {
        // Verificar si ya existe un cliente personal con el nombre "Holos"
        $clientExists = DB::table('oauth_clients')
            ->where('name', 'Holos')
            ->where('personal_access_client', true)
            ->exists();

        if (!$clientExists) {
            Artisan::call('passport:client', [
                '--personal' => true,
                '--name' => 'Holos',
                '--no-interaction' => true
            ]);
        }
    }
};
