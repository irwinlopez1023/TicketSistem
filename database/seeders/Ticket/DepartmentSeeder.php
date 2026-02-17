<?php

namespace Database\Seeders\Ticket;

use App\Models\Ticket\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Soporte Técnico',
                'code' => 'soporte',
                'color' => 'primary', // Azul
            ],
            [
                'name' => 'Ventas',
                'code' => 'ventas',
                'color' => 'success', // Verde
            ],
            [
                'name' => 'Facturación',
                'code' => 'facturacion',
                'color' => 'warning', // Amarillo
            ],
            [
                'name' => 'Recursos Humanos',
                'code' => 'rh',
                'color' => 'info', // Cyan
            ],
            [
                'name' => 'Desarrollo',
                'code' => 'dev',
                'color' => 'danger', // Rojo
            ],
        ];

        foreach ($departments as $data) {
            $department = Department::updateOrCreate(
                ['name' => $data['name']],
                ['code' => $data['code'], 'color' => $data['color']]
            );

            // Crear usuario para este departamento
            $email = $data['code'] . '@example.com';
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'Agente ' . $data['name'],
                    'password' => Hash::make($data['code']), // Contraseña igual al código (ej: soporte)
                    'department_id' => $department->id,
                ]
            );

            // Asignar rol de soporte
            $user->assignRole('support');
        }
    }
}
