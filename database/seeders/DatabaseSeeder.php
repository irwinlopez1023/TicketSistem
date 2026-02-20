<?php

namespace Database\Seeders;

use App\Models\Ticket\Department;
use App\Models\User;
use Database\Seeders\Permission\RoleSeeder;
use Database\Seeders\Ticket\DepartmentSeeder;
use Database\Seeders\User\UserSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Console\Command;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    try {

        $departments = [
            [
                'name' => 'Manager',
                'code' => 'manager',
                'role' => 'manager'
            ],
            [
                'name' => 'Soporte Técnico',
                'code' => 'soporte',
                'role' => 'support'
            ],
            [
                'name' => 'Ventas',
                'code' => 'ventas',
                'role' => 'support'
            ],
            [
                'name' => 'Recursos Humanos',
                'code' => 'rh',
                'role' => 'support'
            ],
            [
                'name' => 'Otros',
                'code' => 'otros',
                'role' => 'support'
            ],

        ];

        $this->call([
            RoleSeeder::class,
        ]);
        $this->callWith(DepartmentSeeder::class,[
            'departments' => $departments
        ]);

        if(config('app.env') === "local") {
            $confirm = $this->command->confirm("Estas por crear usuarios de prueba, confirma la acción con Y: ",true);
            if ($confirm){
                $this->callWith(UserSeeder::class,[
                    'departments' => $departments
                ]);
            }else{
                $this->command->warn('Creacion de usuarios concelado por el administrador.');
            }
        }


        //  User::factory(10)->create();



    }catch (\Exception $exception){
        $this->command->error("Database seeding failed: {$exception->getMessage()}");
    }

    }
}
