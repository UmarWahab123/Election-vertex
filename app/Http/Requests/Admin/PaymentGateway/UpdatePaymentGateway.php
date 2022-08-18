<?php

namespace App\Http\Requests\Admin\PaymentGateway;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdatePaymentGateway extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.payment-gateway.edit', $this->paymentGateway);
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
            'service_charges' => ['nullable', 'string'],
            'expiry_date' => ['nullable', 'string'],
            'on_demand_cloud_computing' => ['sometimes', 'string'],
            'multi_bit_visual_redux' => ['sometimes', 'string'],
            'scan_reading' => ['sometimes', 'string'],
            'googly' => ['sometimes', 'string'],
            'img_url' => ['nullable', 'string'],
            'status' => ['sometimes', 'string'],
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
