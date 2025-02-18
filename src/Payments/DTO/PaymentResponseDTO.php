<?php

namespace Emincmg\PaymentProcessorLaravel\Payments\DTO;

class PaymentResponseDTO
{
    public string $status;
    public ?string $transactionId;
    public ?string $approvalUrl;
    public ?string $message;

    /**
     * @var array|null Key field for dynamically obtaining various keys. It can be an array of keys or a single key.
     */
    public ?array $keys;

    public function __construct(
        string $status,
        ?string $transactionId = null,
        ?string $approvalUrl = null,
        ?string $message = null,
        ?array $keys = null
    ) {
        $this->status = $status;
        $this->transactionId = $transactionId;
        $this->approvalUrl = $approvalUrl;
        $this->message = $message;
        $this->keys = $keys;
    }

    public static function success(string $transactionId, ?string $approvalUrl = null): self
    {
        return new self('success', $transactionId, $approvalUrl, 'Payment successful.');
    }

    /**
     * @param string $message
     * @return self
     */
    public static function failure(string $message): self
    {
        return new self('failed', null, null, $message);
    }

    /**
     * Set a specific key-value pair inside the keys array.
     */
    public function setKey(string $key, string $value): void
    {
        $this->keys[$key] = $value;
    }

    /**
     * Get a key value from the keys array.
     */
    public function getKey(string $key): ?string
    {
        return $this->keys[$key] ?? null;
    }
}