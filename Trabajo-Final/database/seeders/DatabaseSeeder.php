<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\Role;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Alen Misael',
            'apellido' => 'Morillo Meneses',
            'username' => 'amorillo',
            'activo' => true,
            'role' => Role::SuperAdmin,
            'email' => 'alenmisaelmorillomeneses@gmail.com',
            'password' => 'admin',
            'debe_cambiar_password' => false
        ]);

      



    }
}
