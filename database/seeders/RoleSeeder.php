<?php namespace Database\Seeders;

use App\Models\PermissionType;
use Illuminate\Database\Seeder;
use Notsoweb\LaravelCore\Traits\MySql\RolePermission;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    use RolePermission;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = PermissionType::create([
            'name' => 'Usuarios'
        ]);

        [
            $userIndex,
            $userCreate,
            $userEdit,
            $userDestroy
        ] = $this->onCRUD('users', $users);

        $userSettings = $this->onPermission('users.settings', 'Configuración de usuarios', $users);

        // Desarrollador
        Role::create([
            'name' => 'developer',
            'description' => 'Desarrollador'
        ])->givePermissionTo(Permission::all());

        // Administrador
        Role::create([
            'name' => 'admin',
            'description' => 'Administrador'
        ])->givePermissionTo(
            $userIndex,
            $userCreate,
            $userEdit,
            $userDestroy,
            $userSettings
        );
    }
}
