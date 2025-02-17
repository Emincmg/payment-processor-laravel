<?php

namespace Emincmg\PaymentProcessorLaravel\Payments\DTO;

class PaymentResponseDTO
{
    public string $status;
    public ?string $transactionId;
    public ?string $approvalUrl;
    public ?string $message;

    public function __construct(
        string $status,
        ?string $transactionId = null,
        ?string $approvalUrl = null,
        ?string $message = null
    ) {
        $this->status = $status;
        $this->transactionId = $transactionId;
        $this->approvalUrl = $approvalUrl;
        $this->message = $message;
    }

    public static function success(string $transactionId, ?string $approvalUrl = null): self
    {
        return new self('success', $transactionId, $approvalUrl, 'Payment successful.');
    }

    public static function failure(string $message): self
    {
        return new self('failed', null, null, $message);
    }
}