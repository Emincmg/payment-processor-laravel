<?php

namespace Emincmg\PaymentProcessorLaravel\Requests;

class PaymentUpdateRequest
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
            'amount' => 'nullable|numeric|min:1',
            'currency' => 'nullable|string|size:3',
            'payment_status' => 'nullable|in:pending,completed,failed,canceled',
            'payment_method' => 'nullable|string',
            'card_number' => 'nullable|required_if:payment_method,credit_card|digits:16',
            'expiry_date' => 'nullable|required_if:payment_method,credit_card|date_format:m/y|after:today',
            'cvv' => 'nullable|required_if:payment_method,credit_card|digits:3',
            'paypal_email' => 'nullable|required_if:payment_method,paypal|email',
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
            'amount.numeric' => 'The payment amount must be a numeric value.',
            'amount.min' => 'The payment amount must be at least 1.',
            'currency.size' => 'The currency code must be exactly 3 characters.',
            'payment_status.in' => 'The payment status must be one of: pending, completed, failed, or canceled.',
            'card_number.digits' => 'The card number must be exactly 16 digits.',
            'expiry_date.date_format' => 'The expiry date must be in mm/yy format.',
            'expiry_date.after' => 'The expiry date must be in the future.',
            'cvv.digits' => 'The CVV code must be exactly 3 digits.',
            'paypal_email.email' => 'The PayPal email must be a valid email address.',
        ];
    }
}