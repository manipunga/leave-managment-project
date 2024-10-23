<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;

class RoleDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create sample departments
        Department::create(['name' => 'Urdu News Desk']);
        Department::create(['name' => 'Reporting']);
        Department::create(['name' => 'Video']);
        Department::create(['name' => 'Social']);
        Department::create(['name' => 'Admin']);
        Department::create(['name' => 'IT']);
        Department::create(['name' => 'English Deptt']);

        // Create an admin user for testing
        User::create([
            'name' => 'Administrator',
            'email' => 'imran.saach@gmail.com',
            'password' => bcrypt('thegame'),
            'role' => 'admin',
            'joining_date' => '2023-01-01',
            'salary' => 50000,
            'dob' => '1980-01-01',
            'cnic' => '12345-6789012-3',
        ])->assignRole('admin');
    }
}
