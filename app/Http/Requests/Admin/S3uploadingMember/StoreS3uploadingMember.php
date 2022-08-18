<?php

namespace App\Http\Requests\Admin\S3uploadingMember;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreS3uploadingMember extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.s3uploading-member.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string'],
            'email' => ['required', 'email', Rule::unique('s3uploading_members', 'email'), 'string'],
             'password' => ['required', 'confirmed', 'min:7', 'string'],
            'phone' => ['nullable', 'string'],
            'party' => ['nullable', 'string'],
            'last_login' => ['nullable', 'string'],
            'ip_address' => ['nullable', 'string'],
            'is_loggedin' => ['nullable', 'string'],
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
