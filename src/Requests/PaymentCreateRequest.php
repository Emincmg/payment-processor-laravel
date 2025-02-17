<?php

namespace Emincmg\PaymentProcessorLaravel\Requests;

use Emincmg\PaymentProcessorLaravel\Payments\DTO\PaymentRequestDTO;

class PaymentCreateRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // make your authorization here
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string|size:3',
            'payment_method' => 'required|string',
            'card_number' => 'required_if:payment_method,credit_card|digits:16',
            'expiry_date' => 'required_if:payment_method,credit_card|date_format:m/y|after:today',
            'cvv' => 'required_if:payment_method,credit_card|digits:3',
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'amount.required' => 'The payment amount is required.',
            'amount.numeric' => 'The payment amount must be a numeric value.',
            'amount.min' => 'The payment amount must be at least 1.',
            'currency.required' => 'The currency is required.',
            'currency.size' => 'The currency code must be exactly 3 characters.',
            'payment_method.required' => 'The payment method is required.',
            'card_number.required_if' => 'The card number is required for credit card payments.',
            'card_number.digits' => 'The card number must be exactly 16 digits.',
            'expiry_date.required_if' => 'The expiry date is required for credit card payments.',
            'expiry_date.date_format' => 'The expiry date must be in mm/yy format.',
            'expiry_date.after' => 'The expiry date must be in the future.',
            'cvv.required_if' => 'The CVV code is required for credit card payments.',
            'cvv.digits' => 'The CVV code must be exactly 3 digits.',
        ];
    }

    /**
     * Create a DTO instance from validated input.
     *
     * @return PaymentRequestDTO
     */
    public function toDTO(): PaymentRequestDTO
    {
        $validated = $this->validated();
        return new PaymentRequestDTO(
            $validated['payment_method'],
            $validated['amount'],
            $validated['currency'],
            'Payment request initiated',
            optional($this->user())->name,
            optional($this->user())->email,
            optional($this->user())->phone,
            $validated['payment_method'],
            null
        );
    }
}