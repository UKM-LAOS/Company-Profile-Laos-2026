<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shortlink extends Model
{
    protected $fillable = [
        'user_id',
        'destination_url',
        'short_code',
        'click_count',
        'is_active',
        'expires_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'click_count' => 'integer',
            'is_active' => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Get the user who created this shortlink.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
