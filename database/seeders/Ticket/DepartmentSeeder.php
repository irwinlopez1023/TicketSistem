<?php

namespace Database\Seeders\Ticket;

use Illuminate\Database\Seeder;
use App\Models\Ticket\Department;
class DepartmentSeeder extends Seeder
{
    /**
     * DepartmentSeeder
     * DepartmentSeeder
     * Run the database seeds.
     */
    public function run(array $departments = []): void
    {

        foreach ($departments as $department){
           Department::firstOrCreate(['name' => $department['name']]);
        }

    }
}
