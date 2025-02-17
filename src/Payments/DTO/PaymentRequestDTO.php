<?php

namespace Emincmg\PaymentProcessorLaravel\Payments\DTO;


class PaymentRequestDTO
{
    public string $channel;
    public float $amount;
    public string $currency;
    public string $description;
    public ?string $userName;
    public ?string $userEmail;
    public ?string $userPhone;
    public ?string $paymentMethod;
    public ?string $confirmationMethod;

    public function __construct(
        string $channel,
        float $amount,
        string $currency,
        string $description,
        ?string $userName = null,
        ?string $userEmail = null,
        ?string $userPhone = null,
        ?string $paymentMethod = null,
        ?string $confirmationMethod = null
    ) {
        $this->channel = $channel;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->description = $description;
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->userPhone = $userPhone;
        $this->paymentMethod = $paymentMethod;
        $this->confirmationMethod = $confirmationMethod;
    }

    /**
     * Create instance from VALIDATED request array.
     *
     * @param array $data
     * @return self
     */
    public static function fromRequest(array $data): self
    {
        return new self(
            $data['channel'],
            $data['amount'],
            $data['currency'],
            $data['description'],
            $data['user_name'] ?? null,
            $data['user_email'] ?? null,
            $data['user_phone'] ?? null,
            $data['payment_method'] ?? null,
            $data['confirmation_method'] ?? null
        );
    }
}