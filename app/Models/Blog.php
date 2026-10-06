<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'divisi_id',
        'author_id',
        'judul',
        'slug',
        'kategori',
        'konten',
        'meta_description',
        'is_unggulan',
        'status',
        'published_at',
        'views',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_unggulan' => 'boolean',
            'published_at' => 'datetime',
            'views' => 'integer',
        ];
    }

    /**
     * Get the division associated with the blog post.
     *
     * @return BelongsTo<Divisi, $this>
     */
    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }

    /**
     * Get the author (user) who wrote the blog post.
     *
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
