<?php

namespace App\Http\Requests\Admin\PollingScheme;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdatePollingScheme extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.polling-scheme.edit', $this->pollingScheme);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ward' => ['nullable', 'string'],
            'polling_station_area' => ['nullable', 'string'],
            'polling_station_area_urdu' => ['nullable', 'string'],
            'block_code_area' => ['nullable', 'string'],
            'block_code' => ['nullable', 'integer'],
            'latlng' => ['nullable', 'string'],
            'status' => ['sometimes', 'string'],
            'serial_no' => ['nullable', 'integer'],
            'image_url' => ['nullable', 'string'],
            'gender_type' => ['nullable', 'string'],
            'male_both' => ['nullable', 'integer'],
            'total_both' => ['nullable', 'integer'],
            'female_both' => ['nullable', 'integer'],



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
