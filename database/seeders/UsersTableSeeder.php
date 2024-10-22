<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Ronny Elian',
            'last_name' => 'Gomez Peñafiel',
            'username' => 'rgomezp',
            'email' => 'rgomezp@viamatica.com',
            'birth_date' => '2000-06-04',
            'password' => bcrypt('admin'),
            'role_id' => 1,
        ]);
    }
}
