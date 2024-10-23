<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        Role::create(['name' => 'employee']);
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'hod']);
        Role::create(['name' => 'chief_editor']);
        Role::create(['name' => 'hr']);
        Role::create(['name' => 'admin']);
    }
}
