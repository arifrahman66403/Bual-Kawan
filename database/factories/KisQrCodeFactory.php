<?php

namespace Database\Factories;

use App\Models\KisQrCode;
use App\Models\KisPengunjung;
use App\Models\KisUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KisQrCode>
 */
class KisQrCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = KisQrCode::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-3 days', 'now');
        $end = (clone $start)->modify('+3 days');

        return [
            'pengunjung_id' => KisPengunjung::inRandomOrder()->first()?->uid,
            'qr_detail_path' => 'qr_codes/detail/' . $this->faker->uuid . '.png',
            'qr_scan_path' => 'qr_codes/scan/' . $this->faker->uuid . '.png',
            'berlaku_mulai' => $start,
            'berlaku_sampai' => $end,
            'created_by' => KisUser::inRandomOrder()->first()?->id,
        ];
    }
}
