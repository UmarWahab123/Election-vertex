<?php

namespace App\Http\Requests\Admin\BusinessAccount;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateBusinessAccount extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.business-account.edit', $this->businessAccount);
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
            'ref_id' => ['nullable', 'string'],
            'credit' => ['nullable', 'string'],
            'details' => ['nullable', 'string'],
            'debit' => ['nullable', 'string'],
            'balance' => ['nullable', 'string'],
            'img_url' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'meta' => ['nullable', 'string'],

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
