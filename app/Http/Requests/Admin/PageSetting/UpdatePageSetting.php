<?php

namespace App\Http\Requests\Admin\PageSetting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdatePageSetting extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.page-setting.edit', $this->pageSetting);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'business_id' => ['nullable', 'string'],
            'tag_name' => ['nullable', 'string'],
            'businessHome_H1' => ['nullable', 'string'],
            'businessHome_H2' => ['nullable', 'string'],
            'businessHome_H3' => ['nullable', 'string'],
            'reg_title' => ['nullable', 'string'],
            'reg_img_title' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],

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
