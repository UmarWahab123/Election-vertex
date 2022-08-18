<?php

namespace App\Http\Requests\Admin\VoterDetail;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateVoterDetail extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.voter-detail.edit', $this->voterDetail);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id_card' => ['sometimes', 'string'],
            'serial_no' => ['sometimes', 'string'],
            'family_no' => ['sometimes', 'string'],
            'name' => ['sometimes', 'string'],
            'father_name' => ['sometimes', 'string'],
            'address' => ['sometimes', 'string'],
            'cron' => ['sometimes', 'string'],
            'status' => ['sometimes', 'string'],
            'meta' => ['sometimes', 'string'],
            
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
