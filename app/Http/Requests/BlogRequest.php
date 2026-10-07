<?php

namespace App\Http\Requests;

use App\Models\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can(
            $this->route('blog') instanceof Blog ? 'edit_news' : 'create_news'
        ) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $blogId = $this->route('blog') ? $this->route('blog')->id : null;

        return [
            'divisi_id' => ['required', 'exists:divisis,id'],
            'judul' => ['required', 'string', 'max:255', Rule::unique(Blog::class)->ignore($blogId)],
            'kategori' => ['required', 'string', 'max:100'],
            'konten' => ['required', 'string'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'is_unggulan' => ['boolean'],
            'status' => ['required', 'string', 'in:draft,published'],
        ];
    }
}
