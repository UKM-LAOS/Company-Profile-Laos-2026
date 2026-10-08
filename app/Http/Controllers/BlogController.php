<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use App\Models\Divisi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(Request $request): Response|RedirectResponse
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:draft,published'],
        ]);

        $filters = [
            'search' => trim($request->string('search')->toString()),
            'kategori' => $request->string('kategori')->toString(),
            'status' => $request->string('status')->toString(),
        ];

        $blogs = Blog::query()
            ->with(['author:id,name', 'divisi:id,nama'])
            ->select(['id', 'judul', 'slug', 'kategori', 'status', 'published_at', 'author_id', 'divisi_id', 'is_unggulan'])
            ->when($filters['search'] !== '', function (Builder $query) use ($filters): void {
                $query->where(function (Builder $q) use ($filters): void {
                    $q->where('judul', 'like', "%{$filters['search']}%")
                      ->orWhere('kategori', 'like', "%{$filters['search']}%")
                      ->orWhereHas('author', function (Builder $authorQuery) use ($filters): void {
                          $authorQuery->where('name', 'like', "%{$filters['search']}%");
                      });
                });
            })
            ->when($filters['kategori'] !== '', fn (Builder $query) => $query->where('kategori', $filters['kategori']))
            ->when($filters['status'] !== '', fn (Builder $query) => $query->where('status', $filters['status']))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        if ($blogs->currentPage() > $blogs->lastPage() && $blogs->lastPage() > 0) {
            return redirect()->route('blogs.index', [
                ...$request->query(),
                'page' => $blogs->lastPage(),
            ]);
        }

        $kategoriOptions = Blog::query()
            ->distinct()
            ->select('kategori')
            ->orderBy('kategori')
            ->pluck('kategori')
            ->all();

        return Inertia::render('Blogs/Index', [
            'blogs' => $blogs,
            'filters' => $filters,
            'options' => [
                'kategori' => $kategoriOptions,
                'status' => [
                    ['value' => 'published', 'label' => 'Terbit'],
                    ['value' => 'draft', 'label' => 'Draf'],
                ],
            ],
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create_news');

        $divisis = Divisi::select('id', 'nama')->orderBy('nama')->get();
        return Inertia::render('Blogs/Form', [
            'divisis' => $divisis,
        ]);
    }

    public function store(BlogRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['judul']);
        $validated['author_id'] = $request->user()->id;

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        Blog::create($validated);

        return redirect()->route('blogs.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(Blog $blog): Response
    {
        Gate::authorize('edit_news');

        $divisis = Divisi::select('id', 'nama')->orderBy('nama')->get();
        return Inertia::render('Blogs/Form', [
            'blog' => $blog,
            'divisis' => $divisis,
        ]);
    }

    public function update(BlogRequest $request, Blog $blog): RedirectResponse
    {
        $validated = $request->validated();
        
        if ($blog->judul !== $validated['judul']) {
            $validated['slug'] = Str::slug($validated['judul']);
        }

        if ($validated['status'] === 'published' && $blog->status !== 'published' && !$blog->published_at) {
            $validated['published_at'] = now();
        }

        $blog->update($validated);

        return redirect()->route('blogs.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        Gate::authorize('delete_news');

        $blog->delete();

        return redirect()->back()->with('success', 'Berita berhasil dihapus.');
    }
}
