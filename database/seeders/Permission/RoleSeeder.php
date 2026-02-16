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
        Permission::firstOrCreate(['name' => 'tickets.create']);
        Permission::firstOrCreate(['name' => 'tickets.delegate']);
        Permission::firstOrCreate(['name' => 'tickets.reply']);
        Permission::firstOrCreate(['name' => 'tickets.update']);
        Permission::firstOrCreate(['name' => 'tickets.delete']);
        Permission::firstOrCreate(['name' => 'users.manage']);

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $support = Role::firstOrCreate(['name' => 'support']);
        $user = Role::firstOrCreate(['name' => 'user']);

        $admin->givePermissionTo(Permission::all());
        $support->givePermissionTo(['tickets.delegate', 'tickets.reply', 'tickets.view']);
        $user->givePermissionTo(['tickets.view', 'tickets.create', 'tickets.reply']);


    }
}
