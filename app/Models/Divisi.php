<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Divisi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'logo',
    ];

    /**
     * Get all blogs written under this division.
     */
    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'divisi_id');
    }

    /**
     * Get all work programs organized by this division.
     */
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class, 'divisi_id');
    }
}
