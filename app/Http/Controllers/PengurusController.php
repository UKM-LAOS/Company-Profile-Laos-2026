<?php

namespace App\Http\Controllers;

use App\Http\Requests\PengurusRequest;
use App\Models\Pengurus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PengurusController extends Controller
{
    private const FOTO_DIRECTORY = 'pengurus';

    public const OPTIONS_CACHE_KEY = 'pengurus_filter_options';

    public function index(Request $request): Response|RedirectResponse
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'periode' => ['nullable', 'string', 'max:255'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:aktif,nonaktif'],
        ]);

        $filters = [
            'search' => trim($request->string('search')->toString()),
            'periode' => $request->string('periode')->toString(),
            'jabatan' => $request->string('jabatan')->toString(),
            'status' => $request->string('status')->toString(),
        ];

        $penguruses = Pengurus::query()
            ->select(['id', 'nama', 'jabatan', 'periode', 'foto', 'sosmed', 'urutan', 'aktif'])
            ->when($filters['search'] !== '', function (Builder $query) use ($filters): void {
                $query->where(function (Builder $query) use ($filters): void {
                    $query->where('nama', 'like', "%{$filters['search']}%")
                        ->orWhere('jabatan', 'like', "%{$filters['search']}%");
                });
            })
            ->when($filters['periode'] !== '', fn (Builder $query) => $query->where('periode', $filters['periode']))
            ->when($filters['jabatan'] !== '', function (Builder $query) use ($filters): void {
                if (mb_strtolower($filters['jabatan']) === 'ketua') {
                    $query->where(function (Builder $q): void {
                        $q->where('jabatan', 'Ketua')
                            ->orWhere('jabatan', 'like', '%Ketua%');
                    });
                } else {
                    $query->where('jabatan', $filters['jabatan']);
                }
            })
            ->when($filters['status'] !== '', fn (Builder $query) => $query->where('aktif', $filters['status'] === 'aktif'))
            ->orderByDesc('periode')
            ->orderBy('urutan')
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        if ($penguruses->currentPage() > $penguruses->lastPage()) {
            return redirect()->route('pengurus.index', [
                ...$request->query(),
                'page' => $penguruses->lastPage(),
            ]);
        }

        /** @var array{periode: array<int, string>, jabatan: array<int, string>} $options */
        $options = Cache::remember(self::OPTIONS_CACHE_KEY, 3600, function (): array {
            /** @var array<int, string> $jabatanOptions */
            $jabatanOptions = Pengurus::query()
                ->select('jabatan')
                ->groupBy('jabatan')
                ->orderByRaw('MIN(urutan) ASC, jabatan ASC')
                ->pluck('jabatan')
                ->all();

            if (collect($jabatanOptions)->contains(fn ($j) => str_contains(mb_strtolower($j), 'ketua')) && ! in_array('Ketua', $jabatanOptions, true)) {
                array_unshift($jabatanOptions, 'Ketua');
            }

            $periodeOptions = Pengurus::query()
                ->distinct()
                ->orderByDesc('periode')
                ->pluck('periode')
                ->all();

            return [
                'periode' => $periodeOptions,
                'jabatan' => $jabatanOptions,
            ];
        });

        return Inertia::render('Pengurus/Index', [
            'penguruses' => $penguruses,
            'filters' => $filters,
            'options' => $options,
        ]);
    }

    public function store(PengurusRequest $request): RedirectResponse
    {
        $pengurus = new Pengurus($request->attributesForStorage());

        $foto = $request->file('foto');
        if ($foto instanceof UploadedFile) {
            $pengurus->foto = $foto->store(self::FOTO_DIRECTORY, 'public') ?: null;
        }

        $pengurus->save();
        Cache::forget(self::OPTIONS_CACHE_KEY);

        return redirect()->back()->with('success', 'Pengurus berhasil ditambahkan.');
    }

    public function update(PengurusRequest $request, Pengurus $pengurus): RedirectResponse
    {
        $pengurus->fill($request->attributesForStorage());
        $oldFoto = $pengurus->getOriginal('foto');

        $foto = $request->file('foto');
        if ($foto instanceof UploadedFile) {
            $pengurus->foto = $foto->store(self::FOTO_DIRECTORY, 'public') ?: null;
        } elseif ($request->boolean('hapus_foto')) {
            $pengurus->foto = null;
        }

        $pengurus->save();
        Cache::forget(self::OPTIONS_CACHE_KEY);

        // Remove the previous file only after the new state is persisted.
        if (is_string($oldFoto) && $oldFoto !== $pengurus->foto) {
            Storage::disk('public')->delete($oldFoto);
        }

        return redirect()->back()->with('success', 'Data pengurus berhasil diperbarui.');
    }

    public function destroy(Pengurus $pengurus): RedirectResponse
    {
        Gate::authorize('delete_committee');

        // Soft delete: the photo is kept so the record can still be restored.
        $pengurus->delete();
        Cache::forget(self::OPTIONS_CACHE_KEY);

        return redirect()->back()->with('success', 'Pengurus berhasil dihapus.');
    }
}
