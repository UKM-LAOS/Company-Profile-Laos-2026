<?php

namespace App\Http\Controllers;

use App\Http\Requests\DivisiRequest;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class DivisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $divisis = Divisi::query()
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Divisis/Index', [
            'divisis' => $divisis,
            'filters' => $request->only('search'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DivisiRequest $request)
    {
        $validated = $request->validated();
        
        $validated['slug'] = Str::slug($validated['nama']);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('divisi-logos', 'public');
        }

        Divisi::create($validated);

        return redirect()->route('divisis.index')->with('success', 'Divisi berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DivisiRequest $request, Divisi $divisi)
    {
        $validated = $request->validated();
        
        if ($divisi->nama !== $validated['nama']) {
            $validated['slug'] = Str::slug($validated['nama']);
        }

        if ($request->hasFile('logo')) {
            if ($divisi->logo) {
                Storage::disk('public')->delete($divisi->logo);
            }
            $validated['logo'] = $request->file('logo')->store('divisi-logos', 'public');
        } else {
            unset($validated['logo']);
        }

        $divisi->update($validated);

        return redirect()->route('divisis.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Divisi $divisi)
    {
        if ($divisi->logo) {
            Storage::disk('public')->delete($divisi->logo);
        }
        
        $divisi->delete();

        return redirect()->route('divisis.index')->with('success', 'Divisi berhasil dihapus.');
    }
}
