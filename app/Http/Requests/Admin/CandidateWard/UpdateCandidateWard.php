<?php

namespace App\Http\Requests\Admin\CandidateWard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateCandidateWard extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.candidate-ward.edit', $this->candidateWard);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
//            'user_id' => ['nullable', 'string'],
//            'ward_id' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'user_id' => ['sometimes', 'array'],
            'ward_id' => ['sometimes', 'array'],


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
