<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        // Mengambil data terbaru dengan pagination
        $galleries = Gallery::latest()->paginate(10);
        return view('admin.gallery.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:galleries,slug',
            'description' => 'nullable|string',
            'image' => 'required|image|max:2048', // Max 2MB
        ]);

        $imagePath = $request->file('image')->store('galleries', 'public');

        Gallery::create([
            'title' => $request->title,
            'slug' => $request->slug ?? \Str::slug($request->title),
            'description' => $request->description,
            'image' => $imagePath
        ]);

        return redirect()->route('admin.gallery.index')->with('success', 'Gambar berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        if (Storage::disk('public')->exists($gallery->image)) {
            Storage::disk('public')->delete($gallery->image);
        }

        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Gambar berhasil dihapus!');
    }
}