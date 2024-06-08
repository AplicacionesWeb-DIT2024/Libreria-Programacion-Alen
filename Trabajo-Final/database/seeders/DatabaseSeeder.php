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
            'name' => 'Alen Morillo Meneses',
            'username' => 'alen123',
            'email' => 'alenmisaelmorillomeneses@gmail.com',
            'domicilio' => 'Avenida Siempre Viva 242',
            'admin' => True, 
            'password' => Hash::make('alen123'),
        ]);

    }
}
