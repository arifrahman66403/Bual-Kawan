<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        // Menampilkan slider terbaru
        $sliders = Slider::latest()->paginate(5);
        return view('admin.slider.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.slider.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:sliders,slug',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        $imagePath = $request->file('image')->store('sliders', 'public');

        Slider::create([
            'title' => $request->title,
            'slug' => $request->slug ?? \Str::slug($request->title),
            'description' => $request->description,
            'image' => $imagePath
        ]);

        return redirect()->route('admin.slider.index')->with('success', 'Slider berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);

        // Hapus file fisik
        if (Storage::disk('public')->exists($slider->image)) {
            Storage::disk('public')->delete($slider->image);
        }

        $slider->delete();

        return redirect()->route('admin.slider.index')->with('success', 'Slider berhasil dihapus!');
    }
}