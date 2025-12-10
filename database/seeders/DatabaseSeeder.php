<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    KisUser,
    KisPengunjung,
    KisPesertaKunjungan,
    KisDokumen,
    KisQrCode,
    KisTracking,
    KisLog,
    Gallery,
    Slider
};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Buat user terlebih dahulu
        KisUser::factory(5)->create();

        // Buat pengunjung (utama)
        KisPengunjung::factory(50)->create();

        // Buat relasi lainnya
        KisPesertaKunjungan::factory(20)->create();
        KisDokumen::factory(50)->create();
        KisQrCode::factory(50)->create();
        KisTracking::factory(15)->create();
        KisLog::factory(30)->create();
        Gallery::factory(10)->create();
        Slider::factory(5)->create();
    }
}
