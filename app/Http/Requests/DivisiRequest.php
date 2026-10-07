<?php

namespace App\Http\Requests;

use App\Models\Divisi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DivisiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(
            $this->route('divisi') instanceof Divisi ? 'edit_divisions' : 'create_divisions'
        ) ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $divisiId = $this->route('divisi') instanceof Divisi
            ? $this->route('divisi')->id
            : null;

        return [
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('divisis', 'nama')->ignore($divisiId),
            ],
            'deskripsi' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.unique' => 'Nama divisi sudah digunakan, silakan gunakan nama lain.',
        ];
    }
}
