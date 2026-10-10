<?php

namespace App\Http\Requests;

use App\Models\Program;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(
            $this->route('program') instanceof Program ? 'edit_work_programs' : 'create_work_programs'
        ) ?? false;
    }

    protected function prepareForValidation(): void
    {
        $trimmed = [];

        foreach (['judul_program', 'location_name', 'deskripsi', 'gform_panitia', 'gform_peserta', 'open_regis_panitia', 'close_regis_panitia', 'open_regis_peserta', 'close_regis_peserta'] as $field) {
            if ($this->has($field) && is_string($this->input($field))) {
                $val = trim($this->input($field));
                $trimmed[$field] = $val === '' ? null : $val;
            }
        }

        $judul = $trimmed['judul_program'] ?? $this->input('judul_program');
        $slug = $this->input('slug');
        if (is_string($slug) && trim($slug) !== '') {
            $trimmed['slug'] = Str::slug(trim($slug));
        } elseif (is_string($judul) && trim($judul) !== '') {
            $trimmed['slug'] = Str::slug(trim($judul));
        }

        $this->merge($trimmed);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $program = $this->route('program');
        $programId = $program instanceof Program ? $program->id : null;

        return [
            'divisi_id' => ['required', 'integer', 'exists:divisis,id'],
            'pengurus_id' => ['nullable', 'integer', 'exists:penguruses,id'],
            'judul_program' => [
                'required',
                'string',
                'max:255',
                Rule::unique('programs', 'judul_program')
                    ->ignore($programId)
                    ->whereNull('deleted_at'),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('programs', 'slug')
                    ->ignore($programId)
                    ->whereNull('deleted_at'),
            ],
            'location_name' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'open_regis_panitia' => ['nullable', 'date'],
            'close_regis_panitia' => ['nullable', 'date', 'after_or_equal:open_regis_panitia'],
            'gform_panitia' => ['nullable', 'string', 'max:2048', 'url:http,https'],
            'open_regis_peserta' => ['required', 'date'],
            'close_regis_peserta' => ['required', 'date', 'after_or_equal:open_regis_peserta'],
            'gform_peserta' => ['nullable', 'string', 'max:2048', 'url:http,https'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hapus_foto' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'divisi_id.required' => 'Divisi penyelenggara wajib dipilih.',
            'divisi_id.exists' => 'Divisi yang dipilih tidak valid.',
            'pengurus_id.exists' => 'PIC/Pengurus yang dipilih tidak valid.',
            'judul_program.required' => 'Nama / judul program kerja wajib diisi.',
            'judul_program.unique' => 'Judul program kerja sudah terdaftar.',
            'slug.unique' => 'Slug program kerja sudah digunakan.',
            'close_regis_panitia.after_or_equal' => 'Tanggal penutupan panitia harus sama dengan atau setelah tanggal pembukaan.',
            'open_regis_peserta.required' => 'Tanggal pembukaan registrasi peserta wajib diisi.',
            'close_regis_peserta.required' => 'Tanggal penutupan registrasi peserta wajib diisi.',
            'close_regis_peserta.after_or_equal' => 'Tanggal penutupan peserta harus sama dengan atau setelah tanggal pembukaan peserta.',
            'gform_panitia.url' => 'Tautan formulir panitia harus berformat URL (http:// atau https://).',
            'gform_peserta.url' => 'Tautan formulir peserta harus berformat URL (http:// atau https://).',
            'foto.image' => 'File poster/foto harus berupa gambar.',
            'foto.mimes' => 'Format foto harus berupa JPG, JPEG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }

    /**
     * Validated attributes for model storage excluding file handling fields.
     *
     * @return array<string, mixed>
     */
    public function attributesForStorage(): array
    {
        $attributes = collect($this->validated())->except(['foto', 'hapus_foto'])->all();

        // Ensure location_name defaults cleanly if null
        $attributes['location_name'] = $attributes['location_name'] ?? '-';

        return $attributes;
    }
}
