<?php

namespace App\Http\Requests\Admin\PollingDetail;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdatePollingDetail extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.polling-detail.edit', $this->pollingDetail);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'polling_station_id' => ['nullable', 'integer'],
            'serial_no' => ['nullable', 'integer'],
            'family_no' => ['nullable', 'string'],
            'gender' => ['nullable', 'string'],
//            'polling_station_number' => ['nullable', Rule::unique('polling_details', 'polling_station_number')->ignore($this->pollingDetail->getKey(), $this->pollingDetail->getKeyName()), 'integer'],
//            'cnic' => ['nullable', Rule::unique('polling_details', 'cnic')->ignore($this->pollingDetail->getKey(), $this->pollingDetail->getKeyName()), 'string'],
            'page_no' => ['nullable', 'string'],
            'url' => ['nullable', 'string'],
            'url_id' => ['nullable', 'integer'],
            'boundingBox' => ['nullable', 'string'],
            'polygon' => ['nullable', 'string'],
            'status' => ['sometimes', 'integer'],

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
