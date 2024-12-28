<?php namespace Database\Seeders;
/**
 * @copyright (c) 2024 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

use App\Models\PermissionType;
use Illuminate\Database\Seeder;
use Notsoweb\LaravelCore\Traits\MySql\RolePermission;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Roles y permisos
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
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
        ] = $this->onCRUD('users', $users, 'api');

        $userSettings = $this->onPermission('users.settings', 'Configuración de usuarios', $users, 'api');
        $userOnline   = $this->onPermission('users.online', 'Usuarios en linea', $users, 'api');

        $roles = PermissionType::create([
            'name' => 'Roles'
        ]);

        [
            $roleIndex,
            $roleCreate,
            $roleEdit,
            $roleDestroy
        ] = $this->onCRUD('roles', $roles, 'api');

        // Desarrollador
        Role::create([
            'name' => 'developer',
            'description' => 'Desarrollador',
            'guard_name' => 'api'
        ])->givePermissionTo(Permission::all());

        

        // Administrador
        Role::create([
            'name' => 'admin',
            'description' => 'Administrador',
            'guard_name' => 'api'
        ])->givePermissionTo(
            $userIndex,
            $userCreate,
            $userEdit,
            $userDestroy,
            $userSettings,
            $userOnline,
            $roleIndex,
            $roleCreate,
            $roleEdit,
            $roleDestroy
        );
    }
}
