<?php namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Notsoweb\LaravelCore\Supports\UserSecureSupport;

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
            'email' => $developer->email,
            'password' => $developer->hash,
        ])->assignRole(__('developer'));

        $admin = UserSecureSupport::create('admin@notsoweb.com');

        User::create([
            'name' => 'Developer',
            'paternal' => 'Notsoweb',
            'email' => $admin->email,
            'password' => $admin->hash,
        ])->assignRole(__('admin'));

        $demo = UserSecureSupport::create('demo@notsoweb.com');

        User::create([
            'name' => 'Demo',
            'paternal' => 'Notsoweb',
            'email' => $demo->email,
            'password' => $demo->hash,
        ]);
    }
}
