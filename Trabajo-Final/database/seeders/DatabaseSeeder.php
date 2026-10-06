<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
            'is_admin' => true,
            'email' => 'alenmisaelmorillomeneses@gmail.com',
            'domicilio' => 'Avenida Siempre Viva 242',
            'password' => Hash::make('admin'),
        ]);

      



    }
}
