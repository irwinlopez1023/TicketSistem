<?php

namespace Database\Seeders\User;

use App\Models\Ticket\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
class UserSeeder extends Seeder
{

    public function run(array $departments = []): void
    {

        $users = [
            ['name' => "Administrador", 'code' => 'admin', 'role' => 'admin'],
            ['name' => "Manager", 'code' => 'manager', 'role' => 'manager'],
            ['name' => "Usuario de prueba", 'code' => 'test', 'role' => 'user'],
        ];

        $departments = array_merge($users, $departments);



        if(config('app.env') === "local") {

            foreach ($departments as $department) {

                $user = User::firstOrCreate(['email' => $department['code'] . "@example.com"],
                    [
                        'name' => $department['name'],
                        'password' => bcrypt($department['code']),
                        'department_id' => Department::where('name', $department['name'])->first()?->id
                    ]);

                $user->assignRole($department['role']);

                $this->command->info("Se ha creado el usuario: {$user->name} con rango: {$department['role']}, contraseña: {$department['code']}");


            }
        }
    }
}
