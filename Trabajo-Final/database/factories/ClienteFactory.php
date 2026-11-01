<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Enums\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
        'user_id' => User::factory()->state(['role' => Role::Cliente]),
        'telefono' => fake()->phoneNumber(),
        'domicilio_calle' => fake()->streetName(),
        'domicilio_numero' => (string) fake()->buildingNumber(),
        'domicilio_provincia' => 'Chubut',
        'domicilio_codigo_postal' => '9100',
        'domicilio_localidad' => 'Trelew'
    ];
    }
}
