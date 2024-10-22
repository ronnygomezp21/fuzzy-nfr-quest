<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Docente', 'description' => 'Rol de docente']);
        Role::create(['name' => 'Estudiante', 'description' => 'Rol de estudiante']);
    }
}
