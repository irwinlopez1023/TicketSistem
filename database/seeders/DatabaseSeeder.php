<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Permission\RoleSeeder;
use Database\Seeders\Ticket\CategorySeeder;
use Database\Seeders\Ticket\DepartmentSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        try {
            $this->call([
                RoleSeeder::class,
                CategorySeeder::class,
                DepartmentSeeder::class,
            ]);

            // Admin User (Sin departamento, header rojo por rol)
            $admin = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin'),
            ]);
            $admin->assignRole('admin');

            // Manager User (Puede ver todo)
            $manager = User::factory()->create([
                'name' => 'Manager User',
                'email' => 'manager@example.com',
                'password' => bcrypt('manager'),
            ]);
            $manager->assignRole('manager');

            // Support User Genérico (Sin departamento, header dark por defecto)
            $support = User::factory()->create([
                'name' => 'Support User',
                'email' => 'support@example.com',
                'password' => bcrypt('support'),
            ]);
            $support->assignRole('support');

            // Usuario Normal (Sin departamento, header dark por defecto)
            $user = User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('test'),
            ]);
            $user->assignRole('user');

            //  User::factory(10)->create();

        } catch (\Exception $exception) {
            print("Database seeding failed: {$exception->getMessage()}");
        }
    }
}
