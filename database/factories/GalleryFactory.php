<?php

namespace Database\Factories;

use App\Models\Gallery; // Pastikan Anda mengimpor Model Gallery
use Illuminate\Database\Eloquent\Factories\Factory;

class GalleryFactory extends Factory
{
    /**
     * Nama model yang sesuai.
     *
     * @var string
     */
    protected $model = Gallery::class;

    /**
     * Definisikan state default model.
     *
     * @return array
     */
    public function definition()
    {
        // Mendapatkan URL gambar placeholder
        $imageUrl = 'https://picsum.photos/640/480?random=' . rand(1, 1000);
        
        return [
            'title' => $this->faker->sentence(4), // Judul acak
            'slug' => $this->faker->slug(), // Slug acak
            'description' => $this->faker->paragraph(2), // Deskripsi acak
            'image' => $imageUrl, // Path gambar dummy (URL)
        ];
    }
}