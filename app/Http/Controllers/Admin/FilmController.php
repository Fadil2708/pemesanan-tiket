<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilmController extends Controller
{
    public function index()
    {
        $films = Film::latest()->get();

        return view('admin.films.index', compact('films'));
    }

    public function create()
    {
        return view('admin.films.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'age_rating' => 'nullable|string|max:10',
            'release_date' => 'nullable|date',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'title',
            'description',
            'duration',
            'age_rating',
            'release_date',
        ]);

        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')
                ->store('posters', 'public');
        }

        Film::create($data);

        return redirect()->route('films.index')
            ->with('success', 'Film berhasil ditambahkan');
    }

    public function edit(Film $film)
    {
        return view('admin.films.edit', compact('film'));
    }

    public function update(Request $request, Film $film)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'age_rating' => 'nullable|string|max:10',
            'release_date' => 'nullable|date',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'title',
            'description',
            'duration',
            'age_rating',
            'release_date',
        ]);

        if ($request->hasFile('poster')) {

            if ($film->poster) {
                Storage::disk('public')->delete($film->poster);
            }

            $data['poster'] = $request->file('poster')
                ->store('posters', 'public');
        }

        $film->update($data);

        return redirect()->route('films.index')
            ->with('success', 'Film berhasil diupdate');
    }

    public function destroy(Film $film)
    {
        $film->delete();

        return redirect()->route('films.index')
            ->with('success', 'Film berhasil dihapus');
    }
}
