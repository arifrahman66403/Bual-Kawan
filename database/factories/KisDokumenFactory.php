<?php

namespace Database\Factories;

use App\Models\KisDokumen;
use App\Models\KisPengunjung;
use App\Models\KisUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KisDokumen>
 */
class KisDokumenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = KisDokumen::class;
    
    public function definition(): array
    {
        return [
            'uid' => (string) Str::uuid(),
            'pengunjung_id' => KisPengunjung::inRandomOrder()->first()?->uid,
            'file_spt' => 'spt/' . $this->faker->uuid() . '.pdf',
            'created_by' => KisUser::inRandomOrder()->first()?->id,
        ];
    }
}
