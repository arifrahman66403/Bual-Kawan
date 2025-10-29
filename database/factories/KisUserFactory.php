<?php

namespace Database\Factories;

use App\Models\KisUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KisUser>
 */
class KisUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = KisUser::class;
    
    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'user' => $this->faker->unique()->userName(),
            'wa' => $this->faker->numerify('08##########'),
            'role' => $this->faker->randomElement(['superadmin', 'admin', 'operator']),
            'pass' => Hash::make('password'),
        ];
    }
}
