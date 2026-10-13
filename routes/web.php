<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\PengurusController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\PublicBlogController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ShortlinkController;
use App\Http\Controllers\UserController;
use App\Models\Divisi;
use App\Models\Program;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'divisis' => Divisi::all(),
        'programs' => Program::with('divisi')->get(),
    ]);
})->name('home');

Route::get('/program-kerja', [ProgramController::class, 'publicIndex'])->name('program-kerja.index');
Route::get('/program-kerja/{identifier}', [ProgramController::class, 'publicShow'])->name('program-kerja.show');
Route::get('/blog', [PublicBlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [PublicBlogController::class, 'show'])->name('blog.show');
Route::get('/tentang-kami', [AboutController::class, 'index'])->name('tentang-kami');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'can:view_users'])->group(function () {
    Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
});

Route::middleware(['auth', 'can:view_roles'])->group(function () {
    Route::resource('roles', RoleController::class)->except(['create', 'show', 'edit']);
    Route::put('/roles/{role}/permissions', [RoleController::class, 'syncPermissions'])->name('roles.permissions.sync');
});

Route::middleware(['auth', 'can:view_shortlinks'])->group(function () {
    Route::resource('shortlinks', ShortlinkController::class)->only(['index', 'store', 'update', 'destroy']);
});

Route::middleware(['auth', 'can:view_committee'])->group(function () {
    Route::resource('pengurus', PengurusController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['pengurus' => 'pengurus']);
});

Route::middleware(['auth', 'can:view_work_programs'])->group(function () {
    Route::resource('programs', ProgramController::class)
        ->only(['index', 'store', 'update', 'destroy']);
});

Route::middleware(['auth', 'can:view_news'])->group(function () {
    Route::resource('blogs', BlogController::class);
});

Route::middleware(['auth', 'can:view_divisions'])->group(function () {
    Route::resource('divisis', DivisiController::class)->except(['create', 'show', 'edit']);
});

require __DIR__.'/auth.php';

// Public Shortlink Redirection
Route::get('/{code}', [ShortlinkController::class, 'redirect'])->where('code', '[A-Za-z0-9_\-]+')->name('shortlinks.redirect');
Route::get('/s/{code}', [ShortlinkController::class, 'redirect'])->name('shortlinks.alias');
