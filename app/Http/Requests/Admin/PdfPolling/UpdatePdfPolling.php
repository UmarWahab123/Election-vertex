<?php

namespace App\Http\Requests\Admin\PdfPolling;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdatePdfPolling extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.pdf-polling.edit', $this->pdfPolling);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['nullable', 'email', 'string'],
            'block_code' => ['nullable', 'string'],
              'status' => ['nullable', 'string'],
            'record_type' => ['nullable', 'string'],
            'type' => ['nullable', 'string'],
            'meta' => ['nullable', 'string'],
            'party_type' => ['nullable', 'string'],
            'cron_status' => ['nullable', 'integer'],

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
