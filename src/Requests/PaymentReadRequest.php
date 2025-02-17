<?php

namespace Emincmg\PaymentProcessorLaravel\Requests;

class PaymentReadRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // make your authorization here
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:payments,id',
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id.required' => 'The payment ID is required.',
            'id.exists' => 'The specified payment does not exist.',
        ];
    }
}