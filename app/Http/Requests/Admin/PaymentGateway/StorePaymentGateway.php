<?php

namespace App\Http\Requests\Admin\PaymentGateway;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StorePaymentGateway extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.payment-gateway.create');
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
            'on_demand_cloud_computing' => ['required', 'string'],
            'multi_bit_visual_redux' => ['required', 'string'],
            'scan_reading' => ['required', 'string'],
            'googly' => ['required', 'string'],
            'img_url' => ['nullable', 'string'],
            'status' => ['required', 'string'],
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
