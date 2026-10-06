<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShortlinkRequest;
use App\Models\Shortlink;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class ShortlinkController extends Controller
{
    public function index(Request $request): Response|RedirectResponse
    {
        $request->validate(['search' => ['nullable', 'string', 'max:255']]);
        $search = trim($request->string('search')->toString());

        $shortlinks = Shortlink::with('user:id,name')
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('short_code', 'like', "%{$search}%")
                        ->orWhere('destination_url', 'like', "%{$search}%");
                });
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        if ($shortlinks->currentPage() > $shortlinks->lastPage()) {
            return redirect()->route('shortlinks.index', [
                ...$request->query(),
                'page' => $shortlinks->lastPage(),
            ]);
        }

        return Inertia::render('Shortlinks/Index', [
            'shortlinks' => $shortlinks,
            'filters' => ['search' => $search],
        ]);
    }

    public function store(ShortlinkRequest $request): RedirectResponse
    {
        $shortlink = new Shortlink([
            ...$request->attributesForStorage(),
            'user_id' => $request->user()?->id,
        ]);
        $this->persistShortlink($shortlink);

        return redirect()->back()->with('success', 'Shortlink berhasil ditambahkan.');
    }

    public function update(ShortlinkRequest $request, Shortlink $shortlink): RedirectResponse
    {
        $shortlink->fill($request->attributesForStorage());
        $this->persistShortlink($shortlink);

        return redirect()->back()->with('success', 'Shortlink berhasil diperbarui.');
    }

    public function destroy(Shortlink $shortlink): RedirectResponse
    {
        Gate::authorize('delete_shortlinks');
        $shortlink->delete();

        return redirect()->back()->with('success', 'Shortlink berhasil dihapus.');
    }

    private function persistShortlink(Shortlink $shortlink): void
    {
        try {
            $shortlink->save();
        } catch (UniqueConstraintViolationException $exception) {
            // Validation cannot prevent another request from claiming the same code.
            if ($exception->columns !== ['short_code'] && $exception->index !== 'shortlinks_short_code_unique') {
                throw $exception;
            }

            throw ValidationException::withMessages([
                'short_code' => 'Kode shortlink sudah digunakan. Gunakan kode lain.',
            ]);
        }
    }
}
