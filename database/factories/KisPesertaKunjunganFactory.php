<?php

namespace Database\Factories;

use App\Models\KisPesertaKunjungan;
use App\Models\KisPengunjung;
use App\Models\KisUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KisPesertaKunjungan>
 */
class KisPesertaKunjunganFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = KisPesertaKunjungan::class;
    
    public function definition(): array
    {
        return [
            'uid' => (string) Str::uuid(),
            'pengunjung_id' => KisPengunjung::inRandomOrder()->first()?->uid,
            'nama' => $this->faker->name(),
            'nip' => $this->faker->numerify('19#########'),
            'jabatan' => $this->faker->jobTitle(),
            'email' => $this->faker->safeEmail(),
            'wa' => $this->faker->numerify('08##########'),
            'file_ttd' => $this->faker->filePath(),
            'created_by' => KisUser::inRandomOrder()->first()?->id,
        ];
    }
}
