<?php

namespace Database\Seeders\Ticket;

use App\Models\Ticket\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'Soporte Técnico',
            'Ventas',
            'Facturación',
            'Recursos Humanos',
            'Desarrollo',
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(['name' => $department]);
        }
    }
}
