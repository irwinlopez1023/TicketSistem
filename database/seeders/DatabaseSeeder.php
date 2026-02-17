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

            $admin = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin'),
            ]);
            $admin->assignRole('admin');

            $support = User::factory()->create([
                'name' => 'Support User',
                'email' => 'support@example.com',
                'password' => bcrypt('support'),
            ]);
            $support->assignRole('support');

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
