<?php

namespace Database\Factories;

use App\Models\KisTracking;
use App\Models\KisPengunjung;
use App\Models\KisUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KisTracking>
 */
class KisTrackingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = KisTracking::class;

    public function definition(): array
    {
        return [
            'pengajuan_id' => KisPengunjung::inRandomOrder()->first()?->uid,
            'catatan' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['disetujui','kunjungan','selesai']),
            'created_by' => KisUser::inRandomOrder()->first()?->id,
        ];
    }
}
