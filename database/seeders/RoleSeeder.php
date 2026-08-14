<?php namespace Database\Seeders;
/**
 * @copyright (c) 2026 MCortesDev (https://mcortes.dev) - All Rights Reserved
 */

use App\Models\PermissionType;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Notsoweb\LaravelCore\Traits\MySql\RolePermission;
use Spatie\Permission\Models\Permission;

/**
 * Roles y permisos
 * 
 * @author Moisés Cortés C. <soy@mcortes.dev>
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
        $users = PermissionType::firstOrCreate([
            'name' => 'Usuarios'
        ]);

        [
            $userIndex,
            $userCreate,
            $userEdit,
            $userDestroy
        ] = $this->onCRUD('users', $users, 'api');

        $userSettings         = $this->onPermission('users.settings', 'Configuración de usuarios', $users, 'api');
        $userPermissionIndex  = $this->onPermission('users.permissions-index', 'Listar permisos de usuarios', $users, 'api');
        $userPermissionUpdate = $this->onPermission('users.permissions-update', 'Actualizar permisos de usuarios', $users, 'api');
        $userRoleIndex        = $this->onPermission('users.roles-index', 'Listar roles de usuarios', $users, 'api');
        $userRoleUpdate       = $this->onPermission('users.roles-update', 'Actualizar roles de usuarios', $users, 'api');
        $userPasswordUpdate   = $this->onPermission('users.password-update', 'Actualizar contraseña de usuarios', $users, 'api');
        $userOnline           = $this->onPermission('users.online', 'Usuarios en linea', $users, 'api');

        $roles = PermissionType::firstOrCreate([
            'name' => 'Roles'
        ]);

        [
            $roleIndex,
            $roleCreate,
            $roleEdit,
            $roleDestroy
        ] = $this->onCRUD('roles', $roles, 'api');

        $pulse = PermissionType::firstOrCreate([
            'name' => 'Sistema'
        ]);

        $systemPulse = $this->onPermission('pulse', 'Monitoreo de Pulse', $pulse, 'api');

        $pulse = PermissionType::firstOrCreate([
            'name' => 'Historial de actividades'
        ]);

        $activityIndex = $this->onIndex(
            code: 'activities', 
            type: $pulse, 
            guardName: 'api'
        );

        // Desarrollador
        Role::firstOrCreate([
            'name' => 'developer',
        ], [
            'description' => 'Desarrollador',
            'guard_name' => 'api'
        ])->syncPermissions(Permission::all());

        // Administrador
        Role::firstOrCreate([
            'name' => 'admin',
        ], [
            'description' => 'Administrador',
            'guard_name' => 'api'
        ])->syncPermissions(
            $userIndex,
            $userCreate,
            $userEdit,
            $userDestroy,
            $userSettings,
            $userOnline,
            $userPermissionIndex,
            $userPermissionUpdate,
            $userRoleIndex,
            $userRoleUpdate,
            $userPasswordUpdate,
            $roleIndex,
            $roleCreate,
            $roleEdit,
            $roleDestroy,
            $systemPulse,
            $activityIndex
        );
    }
}
