<?php

namespace App\Http\Requests\Admin\VoterDetail;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreVoterDetail extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.voter-detail.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id_card' => ['required', 'string'],
            'serial_no' => ['required', 'string'],
            'family_no' => ['required', 'string'],
            'name' => ['required', 'string'],
            'father_name' => ['required', 'string'],
            'address' => ['required', 'string'],
            'cron' => ['required', 'string'],
            'status' => ['required', 'string'],
            'meta' => ['required', 'string'],
            
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
