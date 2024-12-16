<?php namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DevSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(SettingSeeder::class);
    }
}
