<?php

namespace Database\Seeders\Permission;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();

        Permission::firstOrCreate(['name' => 'tickets.view']);
        Permission::firstOrCreate(['name' => 'tickets.view.all']);
        Permission::firstOrCreate(['name' => 'tickets.create']);
        Permission::firstOrCreate(['name' => 'tickets.delegate']);
        Permission::firstOrCreate(['name' => 'tickets.climb']);
        Permission::firstOrCreate(['name' => 'tickets.reply']);
        Permission::firstOrCreate(['name' => 'tickets.update']);
        Permission::firstOrCreate(['name' => 'tickets.delete']);
        Permission::firstOrCreate(['name' => 'users.manage']);

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $support = Role::firstOrCreate(['name' => 'support']);
        $user = Role::firstOrCreate(['name' => 'user']);

        $admin->givePermissionTo(Permission::all());

        // Manager ve todo y gestiona, similar a admin pero quizás sin gestión de usuarios del sistema
        $manager->givePermissionTo(['tickets.view.all', 'tickets.delegate', 'tickets.climb', 'tickets.reply', 'tickets.update', 'tickets.create']);

        // Soporte ve tickets de su área (controlado por lógica, no solo permiso)
        $support->givePermissionTo(['tickets.delegate', 'tickets.reply', 'tickets.view', 'tickets.create']);

        $user->givePermissionTo(['tickets.view', 'tickets.create', 'tickets.reply']);
    }
}
