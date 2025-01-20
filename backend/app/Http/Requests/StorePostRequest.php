<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'message' => 'required_without_all:picture,youtube_url|max:255',
            'picture' => 'nullable|image',
            'youtube_url' => 'nullable|starts_with:https://www.youtube.com,https://m.youtube.com,https://youtu.be',
        ];
    }

    public function messages()
    {
        return [
            'message.required_without_all' => '投稿が未記入です。',
            'message.max' => '投稿は最大255文字までです。',
            // 'picture.image' => 'こちらの写真の拡張子は非対応です。',
            'youtube_url.starts_with' => '動画URLの形式が誤っています。',
        ];
    }
}
