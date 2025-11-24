<?php

namespace Database\Factories;

use App\Models\Slider; // Pastikan Anda mengimpor Model Slider
use Illuminate\Database\Eloquent\Factories\Factory;

class SliderFactory extends Factory
{
    /**
     * Nama model yang sesuai.
     *
     * @var string
     */
    protected $model = Slider::class;

    /**
     * Definisikan state default model.
     *
     * @return array
     */
    public function definition()
    {
        // Mendapatkan URL gambar placeholder dari Picsum
        $imageUrl = 'https://picsum.photos/1920/1080?random=' . rand(1, 1000);

        return [
            'title' => $this->faker->sentence(5), // Judul acak
            'slug' => $this->faker->slug(), // Slug acak
            'description' => $this->faker->paragraph(3), // Deskripsi acak
            'image' => $imageUrl, // Path gambar dummy (URL)
        ];
    }
}