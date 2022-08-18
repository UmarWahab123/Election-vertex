<?php

namespace App\Http\Requests\Admin\PollingStation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdatePollingStation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.polling-station.edit', $this->pollingStation);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'polling_station_number' => ['nullable', Rule::unique('polling_station', 'polling_station_number')->ignore($this->pollingStation->getKey(), $this->pollingStation->getKeyName()), 'integer'],
            'meta' => ['nullable', 'string'],
            'url_id' => ['nullable', 'integer'],
            
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
