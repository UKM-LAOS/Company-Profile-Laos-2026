<?php

namespace App\Http\Requests;

use App\Models\Pengurus;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class PengurusRequest extends FormRequest
{
    public const SOSMED_KEYS = ['instagram', 'linkedin', 'github'];

    public function authorize(): bool
    {
        return $this->user()?->can(
            $this->route('pengurus') instanceof Pengurus ? 'edit_committee' : 'create_committee'
        ) ?? false;
    }

    protected function prepareForValidation(): void
    {
        $trimmed = [];

        foreach (['nama', 'jabatan', 'periode'] as $field) {
            if (is_string($this->input($field))) {
                $trimmed[$field] = trim($this->input($field));
            }
        }

        if (isset($trimmed['periode']) && is_string($trimmed['periode'])) {
            $p = $trimmed['periode'];
            if (preg_match('/^(\d{2})[-\/](\d{2})$/', $p, $m)) {
                $trimmed['periode'] = "20{$m[1]}/20{$m[2]}";
            } elseif (preg_match('/^(\d{4})-(\d{4})$/', $p, $m)) {
                $trimmed['periode'] = "{$m[1]}/{$m[2]}";
            }
        }

        $this->merge($trimmed);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'jabatan' => ['required', 'string', 'max:255'],
            'periode' => [
                'required',
                'string',
                'regex:/\A\d{4}\/\d{4}\z/',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (! is_string($value) || ! preg_match('/\A(\d{4})\/(\d{4})\z/', $value, $matches)) {
                        return;
                    }

                    if ((int) $matches[2] !== (int) $matches[1] + 1) {
                        $fail('Periode harus dua tahun berurutan, contoh 2025/2026.');
                    }
                },
            ],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hapus_foto' => ['sometimes', 'boolean'],
            'sosmed' => ['nullable', 'array:'.implode(',', self::SOSMED_KEYS)],
            'sosmed.*' => ['nullable', 'string', 'max:255', 'url:http,https'],
            'urutan' => ['required', 'integer', 'min:0', 'max:9999'],
            'aktif' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'periode.regex' => 'Format periode harus YYYY/YYYY, contoh 2025/2026.',
            'sosmed.array' => 'Sosial media hanya mendukung Instagram, LinkedIn, dan GitHub.',
            'sosmed.*.url' => 'Link sosial media harus berupa URL http:// atau https://.',
        ];
    }

    /**
     * Validated column values, excluding file handling fields.
     *
     * @return array<string, mixed>
     */
    public function attributesForStorage(): array
    {
        $attributes = collect($this->validated())->except(['foto', 'hapus_foto'])->all();

        $sosmed = array_filter(
            is_array($attributes['sosmed'] ?? null) ? $attributes['sosmed'] : [],
            fn (mixed $url): bool => is_string($url) && $url !== ''
        );
        $attributes['sosmed'] = $sosmed === [] ? null : $sosmed;

        return $attributes;
    }
}
