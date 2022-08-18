<?php

namespace App\Http\Requests\Admin\ParchiImage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreParchiImage extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.parchi-image.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'string'],
            'Party' => ['nullable', 'string'],
            'image_url' => ['nullable', 'string'],
            'candidate_image_url' => ['nullable', 'string'],
            'candidate_name' => ['nullable', 'string'],
            'block_code' => ['nullable', 'string'],
            'status' => ['required', 'string'],

        ];
    }

    /**
    * Modify input data
    *
    * @return array
    */
    public function getSanitized(): array
    {
        $sanitized = $this->validated();

        //Add your code for manipulation with request data here

        return $sanitized;
    }
}
