<?php

namespace App\Models;

use Database\Factories\DivisiFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $nama
 * @property string $slug
 * @property string|null $deskripsi
 * @property string|null $logo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Divisi extends Model
{
    /** @use HasFactory<DivisiFactory> */
    use HasFactory;

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'logo',
    ];

    /**
     * Get all blogs written under this division.
     *
     * @return HasMany<Blog, $this>
     */
    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'divisi_id');
    }

    /**
     * Get all committee members assigned to this division.
     *
     * @return HasMany<Pengurus, $this>
     */
    public function pengurus(): HasMany
    {
        return $this->hasMany(Pengurus::class, 'divisi_id');
    }

    /**
     * Get all work programs organized by this division.
     *
     * @return HasMany<Program, $this>
     */
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class, 'divisi_id');
    }
}
