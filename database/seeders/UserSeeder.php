<?php

namespace Database\Seeders;

use App\Enum\UserDepartment;
use App\Enum\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Kevin PPIC',
            'email' => 'kevin.staff.ppic@gmail.com',
            'role' => UserRole::STAFF,
            'department' => UserDepartment::PPIC
        ]);

        User::factory()->create([
            'name' => 'Kevin Produksi',
            'email' => 'kevin.staff.production@gmail.com',
            'role' => UserRole::STAFF,
            'department' => UserDepartment::PRODUCTION
        ]);


        User::factory()->create([
            'name' => 'Kevin Manager Produksi',
            'email' => 'kevin.manager.production@gmail.com',
            'role' => UserRole::MANAGER,
            'department' => UserDepartment::PRODUCTION
        ]);

        User::factory()->create([
            'name' => 'Kevin Manager PPIC',
            'email' => 'kevin.manager.ppic@gmail.com',
            'role' => UserRole::MANAGER,
            'department' => UserDepartment::PPIC
        ]);
    }
}
