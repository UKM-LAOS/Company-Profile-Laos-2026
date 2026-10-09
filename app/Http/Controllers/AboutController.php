<?php

namespace App\Http\Controllers;

use App\Models\Pengurus;
use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    public function index(): Response
    {
        $pengurus = Pengurus::with('divisi')
            ->orderBy('urutan')
            ->orderBy('nama')
            ->get();

        return Inertia::render('About/Index', [
            'pengurus' => $pengurus,
        ]);
    }
}
