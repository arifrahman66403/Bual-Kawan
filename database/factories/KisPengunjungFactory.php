<?php

namespace Database\Factories;

use App\Models\KisPengunjung;
use App\Models\KisUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KisPengunjung>
 */
class KisPengunjungFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = KisPengunjung::class;

    public function definition(): array
    {
        return [
            'uid' => (string) Str::uuid(),
            'tipe_pengunjung' => $this->faker->randomElement(['instansi pemerintah', 'masyarakat umum']),
            'kode_daerah' => strtoupper($this->faker->lexify('DA??')),
            'nama_instansi' => $this->faker->company(),
            'satuan_kerja' => $this->faker->company(),
            'tujuan' => $this->faker->sentence(),
            'tgl_kunjungan' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'nama_perwakilan' => $this->faker->name(),
            'email_perwakilan' => $this->faker->safeEmail(),
            'wa_perwakilan' => $this->faker->numerify('08##########'),
            'jabatan_perwakilan' => $this->faker->jobTitle(),
            'status' => $this->faker->randomElement(['pengajuan', 'disetujui', 'kunjungan', 'selesai']),
            'created_by' => KisUser::inRandomOrder()->first()?->id,
        ];
    }
}
