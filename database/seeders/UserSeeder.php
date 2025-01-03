<?php namespace Database\Seeders;
/**
 * @copyright (c) 2024 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use App\Models\User;
use Illuminate\Database\Seeder;
use Notsoweb\LaravelCore\Supports\UserSecureSupport;

/**
 * Usuarios predeterminados del sistema
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $developer = UserSecureSupport::create('developer@notsoweb.com');

        User::create([
            'name' => 'Developer',
            'paternal' => 'Notsoweb',
            'maternal' => 'Software',
            'email' => $developer->email,
            'password' => $developer->hash,
        ])->assignRole(__('developer'));

        $admin = UserSecureSupport::create('admin@notsoweb.com');

        User::create([
            'name' => 'Admin',
            'paternal' => 'Notsoweb',
            'maternal' => 'Software',
            'email' => $admin->email,
            'password' => $admin->hash,
        ])->assignRole(__('admin'));

        $demo = UserSecureSupport::create('demo@notsoweb.com');

        User::create([
            'name' => 'Demo',
            'paternal' => 'Notsoweb',
            'maternal' => 'Software',
            'email' => $demo->email,
            'password' => $demo->hash,
        ]);
    }
}
