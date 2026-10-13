<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $divisi_id
 * @property int|null $author_id
 * @property string $judul
 * @property string $slug
 * @property string $kategori
 * @property string|null $thumbnail
 * @property string $konten
 * @property string|null $meta_description
 * @property bool $is_unggulan
 * @property string $status
 * @property Carbon|null $published_at
 * @property int $views
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Divisi $divisi
 */
class Blog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'divisi_id',
        'author_id',
        'judul',
        'slug',
        'kategori',
        'thumbnail',
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

    protected $appends = [
        'first_image',
        'thumbnail_url',
        'ringkasan',
        'tanggal_rilis_formatted',
    ];

    /**
     * Scope a query to only include published blogs.
     *
     * @param  Builder<Blog>  $query
     * @return Builder<Blog>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Get the first image extracted from content or null.
     */
    public function getFirstImageAttribute(): ?string
    {
        $content = $this->attributes['konten'] ?? '';
        if ($content !== '' && preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $content, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Get thumbnail URL for display.
     */
    public function getThumbnailUrlAttribute(): string
    {
        $thumbnail = $this->attributes['thumbnail'] ?? null;
        if (! empty($thumbnail)) {
            if (str_starts_with($thumbnail, 'http://') || str_starts_with($thumbnail, 'https://')) {
                return $thumbnail;
            }

            return Storage::disk('public')->url($thumbnail);
        }

        return $this->first_image ?? asset('assets/blog-default.jpg');
    }

    /**
     * Get excerpt / ringkasan.
     */
    public function getRingkasanAttribute(): string
    {
        if (! empty($this->attributes['meta_description'])) {
            return $this->attributes['meta_description'];
        }
        $content = $this->attributes['konten'] ?? '';
        if ($content !== '') {
            $plain = trim(strip_tags($content));

            return Str::limit($plain, 130);
        }

        return '';
    }

    /**
     * Get formatted publication date in Indonesian locale.
     */
    public function getTanggalRilisFormattedAttribute(): string
    {
        if (! $this->published_at instanceof Carbon) {
            return '';
        }

        $date = clone $this->published_at;
        $date->locale('id');

        return $date->translatedFormat('d F Y');
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
