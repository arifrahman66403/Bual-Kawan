<?php

namespace Database\Factories;

use App\Models\KisLog;
use App\Models\KisUser;
use App\Models\KisPengunjung;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KisLog>
 */
class KisLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = KisLog::class;

    public function definition(): array
    {
        return [
            'user_id' => KisUser::inRandomOrder()->first()?->id,
            'pengunjung_id' => KisPengunjung::inRandomOrder()->first()?->uid,
            'aksi' => $this->faker->randomElement([
                'Menambahkan pengunjung baru',
                'Mengedit data kunjungan',
                'Menyetujui permohonan',
                'Menghapus data peserta',
            ]),
        ];
    }
}
