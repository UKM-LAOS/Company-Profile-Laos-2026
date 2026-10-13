<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Divisi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PublicBlogController extends Controller
{
    /**
     * Display a listing of published blogs.
     */
    public function index(Request $request): Response|RedirectResponse
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:255'],
        ]);

        $filters = [
            'search' => trim($request->string('search')->toString()),
            'kategori' => $request->string('kategori')->toString(),
        ];

        $blogsQuery = Blog::query()
            ->published()
            ->with(['author:id,name', 'divisi:id,nama'])
            ->when($filters['search'] !== '', function (Builder $query) use ($filters): void {
                $query->where(function (Builder $q) use ($filters): void {
                    $q->where('judul', 'like', "%{$filters['search']}%")
                        ->orWhere('konten', 'like', "%{$filters['search']}%")
                        ->orWhere('meta_description', 'like', "%{$filters['search']}%")
                        ->orWhere('kategori', 'like', "%{$filters['search']}%")
                        ->orWhereHas('author', function (Builder $authorQuery) use ($filters): void {
                            $authorQuery->where('name', 'like', "%{$filters['search']}%");
                        });
                });
            })
            ->when($filters['kategori'] !== '', function (Builder $query) use ($filters): void {
                $query->where('kategori', $filters['kategori']);
            })
            ->orderByDesc('is_unggulan')
            ->orderByDesc('published_at');

        $blogs = $blogsQuery->paginate(9)->withQueryString();

        if ($blogs->currentPage() > $blogs->lastPage() && $blogs->lastPage() > 0) {
            return redirect()->route('blog.index', [
                ...$request->query(),
                'page' => $blogs->lastPage(),
            ]);
        }

        $kategoriOptions = Blog::query()
            ->published()
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori')
            ->all();

        $pinnedBlogs = Blog::query()
            ->published()
            ->with(['author:id,name', 'divisi:id,nama'])
            ->where('is_unggulan', true)
            ->orderByDesc('published_at')
            ->get();

        $heroBlogs = $pinnedBlogs->isNotEmpty()
            ? $pinnedBlogs
            : Blog::query()
                ->published()
                ->with(['author:id,name', 'divisi:id,nama'])
                ->orderByDesc('published_at')
                ->take(5)
                ->get();

        $divisis = Divisi::select('id', 'nama', 'slug', 'logo', 'deskripsi')->orderBy('nama')->get();

        return Inertia::render('Blog/Index', [
            'blogs' => $blogs,
            'filters' => $filters,
            'kategoriOptions' => $kategoriOptions,
            'heroBlogs' => $heroBlogs,
            'divisis' => $divisis,
        ]);
    }

    /**
     * Display the specified published blog.
     */
    public function show(string $slug): Response
    {
        $blog = Blog::query()
            ->published()
            ->where('slug', $slug)
            ->with(['author:id,name', 'divisi:id,nama'])
            ->firstOrFail();

        $blog->increment('views');

        $relatedBlogs = Blog::query()
            ->published()
            ->where('id', '!=', $blog->id)
            ->with(['author:id,name', 'divisi:id,nama'])
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        $divisis = Divisi::select('id', 'nama', 'slug', 'logo', 'deskripsi')->orderBy('nama')->get();

        return Inertia::render('Blog/Show', [
            'blog' => $blog,
            'relatedBlogs' => $relatedBlogs,
            'divisis' => $divisis,
        ]);
    }
}
