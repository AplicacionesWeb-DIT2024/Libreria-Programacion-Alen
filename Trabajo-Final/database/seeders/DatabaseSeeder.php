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

        User::factory()->create([
            'name' => 'Prueba',
            'apellido' => 'Prueba',
            'username' => 'prueba',
            'activo' => true,
            'role' => Role::Cliente,
            'email' => 'prueba@gmail.com',
            'password' => 'prueba',
            'debe_cambiar_password' => false,
            'domicilio'=> 'Mitre 222',
            'telefono' => '28041231421'
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'apellido' => 'admin',
            'username' => 'admin2',
            'activo' => true,
            'role' => Role::Admin,
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
            'debe_cambiar_password' => false
        ]);


    }
}
