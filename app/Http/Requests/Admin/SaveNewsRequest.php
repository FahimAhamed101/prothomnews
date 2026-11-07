<?php

namespace App\Http\Requests\Admin;

use App\Models\Subcategory;
use App\Support\YouTube;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class SaveNewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $requiresUploadedVideo = $this->input('video_type') === 'upload'
            && (! $this->route('news') || ! $this->route('news')->video_path);

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:subcategories,id'],
            'title' => ['required', 'string', 'max:255'],
            'image' => [$this->route('news') ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'details' => ['required', 'string'],
            'tags' => ['nullable', 'string', 'max:255'],
            'video_type' => ['nullable', 'in:youtube,upload'],
            'video_url' => ['nullable', 'required_if:video_type,youtube', 'url:http,https', 'max:255'],
            'video_file' => [$requiresUploadedVideo ? 'required' : 'nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:102400'],
            'status' => ['nullable', 'boolean'],
            'breaking_news' => ['nullable', 'boolean'],
            'top_slider' => ['nullable', 'boolean'],
            'first_section_three' => ['nullable', 'boolean'],
            'first_section_nine' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($this->filled('subcategory_id')) {
                    $belongsToCategory = Subcategory::query()
                        ->whereKey($this->integer('subcategory_id'))
                        ->where('category_id', $this->integer('category_id'))
                        ->exists();

                    if (! $belongsToCategory) {
                        $validator->errors()->add('subcategory_id', 'The subcategory must belong to the selected category.');
                    }
                }

                if ($this->input('video_type') === 'youtube' && ! YouTube::videoId($this->string('video_url')->toString())) {
                    $validator->errors()->add('video_url', 'Enter a valid YouTube video URL.');
                }
            },
        ];
    }
}
