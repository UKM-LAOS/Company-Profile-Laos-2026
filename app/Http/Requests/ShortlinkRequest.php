<?php

namespace App\Http\Requests;

use App\Models\Shortlink;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ShortlinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(
            $this->route('shortlink') instanceof Shortlink ? 'edit_shortlinks' : 'create_shortlinks'
        ) ?? false;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('short_code'))) {
            $this->merge(['short_code' => Str::lower(trim($this->input('short_code')))]);
        }
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $uniqueCode = Rule::unique(Shortlink::class, 'short_code');
        $shortlink = $this->route('shortlink');

        if ($shortlink instanceof Shortlink) {
            $uniqueCode->ignore($shortlink);
        }

        return [
            'destination_url' => ['required', 'string', 'max:2048', 'url:http,https'],
            'short_code' => ['required', 'string', 'max:50', 'regex:/\A[a-z0-9_-]+\z/', $uniqueCode],
            'is_active' => ['required', 'boolean'],
            // Match the UTC range of the existing MySQL TIMESTAMP column.
            'expires_at' => [
                'nullable',
                'date',
                'after_or_equal:1970-01-01T00:00:01+00:00',
                'before_or_equal:2038-01-19T03:14:07+00:00',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'expires_at.after_or_equal' => 'Kedaluwarsa paling awal 1 Januari 1970 pukul 00:00:01 UTC.',
            'expires_at.before_or_equal' => 'Kedaluwarsa paling akhir 19 Januari 2038 pukul 03:14:07 UTC.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function attributesForStorage(): array
    {
        $attributes = $this->validated();

        // SQL timestamps do not retain the offset supplied by the client.
        if (isset($attributes['expires_at'])) {
            $attributes['expires_at'] = Date::parse($attributes['expires_at'])
                ->setTimezone(config('app.timezone'))
                ->format('Y-m-d H:i:s');
        }

        return $attributes;
    }
}
