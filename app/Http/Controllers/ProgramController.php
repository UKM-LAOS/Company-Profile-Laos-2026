<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProgramRequest;
use App\Models\Divisi;
use App\Models\Program;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProgramController extends Controller
{
    private const FOTO_DIRECTORY = 'programs';

    public function index(Request $request): Response|RedirectResponse
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'divisi_id' => ['nullable', 'string'],
            'status' => ['nullable', 'in:all,mendatang,berjalan,selesai,panitia_open,peserta_open,upcoming,closed,sedang_berjalan,persiapan,terencana'],
        ]);

        $filters = [
            'search' => trim($request->string('search')->toString()),
            'divisi_id' => $request->string('divisi_id')->toString(),
            'status' => $request->string('status')->toString(),
        ];

        $today = now()->format('Y-m-d');

        $programs = Program::query()
            ->with(['divisi:id,nama,slug', 'pengurus:id,nama,jabatan,foto'])
            ->when($filters['search'] !== '', function (Builder $query) use ($filters): void {
                $query->where(function (Builder $q) use ($filters): void {
                    $q->where('judul_program', 'like', "%{$filters['search']}%")
                        ->orWhere('location_name', 'like', "%{$filters['search']}%")
                        ->orWhere('deskripsi', 'like', "%{$filters['search']}%")
                        ->orWhereHas('divisi', function (Builder $dq) use ($filters): void {
                            $dq->where('nama', 'like', "%{$filters['search']}%");
                        });
                });
            })
            ->when($filters['divisi_id'] !== '' && is_numeric($filters['divisi_id']), function (Builder $query) use ($filters): void {
                $query->where('divisi_id', (int) $filters['divisi_id']);
            })
            ->when($filters['status'] !== '' && $filters['status'] !== 'all', function (Builder $query) use ($filters, $today): void {
                match ($filters['status']) {
                    'mendatang', 'terencana', 'upcoming', 'persiapan' => $query->whereDate('open_regis_peserta', '>', $today),
                    'berjalan', 'sedang_berjalan', 'peserta_open' => $query->whereDate('open_regis_peserta', '<=', $today)
                        ->whereDate('close_regis_peserta', '>=', $today),
                    'selesai', 'closed' => $query->whereDate('close_regis_peserta', '<', $today),
                    default => null,
                };
            })
            ->orderByDesc('open_regis_peserta')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        if ($programs->currentPage() > $programs->lastPage() && $programs->lastPage() > 0) {
            return redirect()->route('programs.index', [
                ...$request->query(),
                'page' => $programs->lastPage(),
            ]);
        }

        $divisis = Divisi::query()
            ->select(['id', 'nama', 'slug'])
            ->orderBy('nama')
            ->get();

        $penguruses = \App\Models\Pengurus::query()
            ->select(['id', 'nama', 'jabatan', 'foto'])
            ->orderBy('urutan')
            ->get();

        $stats = [
            'total' => Program::count(),
            'panitia_open' => Program::whereNotNull('open_regis_panitia')
                ->whereNotNull('close_regis_panitia')
                ->whereDate('open_regis_panitia', '<=', $today)
                ->whereDate('close_regis_panitia', '>=', $today)
                ->count(),
            'peserta_open' => Program::whereDate('open_regis_peserta', '<=', $today)
                ->whereDate('close_regis_peserta', '>=', $today)
                ->count(),
        ];

        return Inertia::render('Programs/Index', [
            'programs' => $programs,
            'divisis' => $divisis,
            'penguruses' => $penguruses,
            'filters' => $filters,
            'stats' => $stats,
        ]);
    }

    public function store(ProgramRequest $request): RedirectResponse
    {
        $program = new Program($request->attributesForStorage());

        $foto = $request->file('foto');
        if ($foto instanceof UploadedFile) {
            $program->foto = $foto->store(self::FOTO_DIRECTORY, 'public') ?: null;
        }

        $program->save();

        return redirect()->back()->with('success', 'Program kerja berhasil ditambahkan.');
    }

    public function update(ProgramRequest $request, Program $program): RedirectResponse
    {
        $program->fill($request->attributesForStorage());
        $oldFoto = $program->getOriginal('foto');

        $foto = $request->file('foto');
        if ($foto instanceof UploadedFile) {
            $program->foto = $foto->store(self::FOTO_DIRECTORY, 'public') ?: null;
        } elseif ($request->boolean('hapus_foto')) {
            $program->foto = null;
        }

        $program->save();

        if (is_string($oldFoto) && $oldFoto !== $program->foto) {
            Storage::disk('public')->delete($oldFoto);
        }

        return redirect()->back()->with('success', 'Program kerja berhasil diperbarui.');
    }

    public function destroy(Program $program): RedirectResponse
    {
        Gate::authorize('delete_work_programs');

        $program->delete();

        return redirect()->back()->with('success', 'Program kerja berhasil dihapus.');
    }
}
