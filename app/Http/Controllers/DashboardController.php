<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Divisi;
use App\Models\Pengurus;
use App\Models\Program;
use App\Models\Shortlink;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request to display the dynamic CMS dashboard.
     */
    public function __invoke(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        $counts = [];
        $recent = [];

        // 1. Program Kerja
        if ($user->can('view_work_programs')) {
            $counts['programs'] = Program::count();
            $recent['programs'] = Program::query()
                ->with('divisi:id,nama')
                ->latest()
                ->take(5)
                ->get()
                ->map(fn ($p) => [
                    'id' => $p->id,
                    'title' => $p->judul_program,
                    'badge' => $p->divisi ? $p->divisi->nama : 'Umum',
                    'url' => route('programs.index'),
                ]);
        }

        // 2. Berita & Artikel
        if ($user->can('view_news')) {
            $counts['blogs'] = Blog::count();
            $recent['blogs'] = Blog::query()
                ->with('divisi:id,nama')
                ->latest()
                ->take(5)
                ->get()
                ->map(fn ($b) => [
                    'id' => $b->id,
                    'title' => $b->judul,
                    'badge' => $b->kategori,
                    'url' => route('blogs.index'),
                ]);
        }

        // 3. Pengurus
        if ($user->can('view_committee')) {
            $counts['pengurus'] = Pengurus::count();
            $recent['pengurus'] = Pengurus::query()
                ->with('divisi:id,nama')
                ->latest()
                ->take(5)
                ->get()
                ->map(fn ($item) => [
                    'id' => $item->id,
                    'title' => $item->nama,
                    'badge' => $item->jabatan ?: ($item->divisi ? $item->divisi->nama : 'Pengurus'),
                    'url' => route('pengurus.index'),
                ]);
        }

        // 4. Divisi
        if ($user->can('view_divisions')) {
            $counts['divisis'] = Divisi::count();
            $recent['divisis'] = Divisi::query()
                ->withCount(['programs', 'pengurus'])
                ->latest()
                ->take(5)
                ->get()
                ->map(fn ($d) => [
                    'id' => $d->id,
                    'title' => $d->nama,
                    'badge' => $d->programs_count.' Program',
                    'url' => route('divisis.index'),
                ]);
        }

        // 5. Shortlink
        if ($user->can('view_shortlinks')) {
            $counts['shortlinks'] = Shortlink::count();
            $recent['shortlinks'] = Shortlink::query()
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($s) {
                    $host = parse_url($s->destination_url, PHP_URL_HOST);
                    $suffix = $host ? " ({$host})" : '';

                    return [
                        'id' => $s->id,
                        'title' => '/'.$s->short_code.$suffix,
                        'badge' => $s->click_count.' Klik',
                        'url' => route('shortlinks.index'),
                    ];
                });
        }

        // 6. Pengguna / Users
        if ($user->can('view_users')) {
            $counts['users'] = User::count();
            $recent['users'] = User::query()
                ->with('roles:id,name')
                ->latest()
                ->take(5)
                ->get()
                ->map(fn (User $u) => [
                    'id' => $u->id,
                    'title' => $u->name.' ('.$u->email.')',
                    'badge' => $u->roles->first() ? $u->roles->first()->name : 'User',
                    'url' => route('users.index'),
                ]);
        }

        return Inertia::render('Dashboard', [
            'counts' => $counts,
            'recent' => $recent,
        ]);
    }
}
