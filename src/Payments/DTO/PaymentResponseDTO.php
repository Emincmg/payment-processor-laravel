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
     * Convert the response to a JSON format.
     */
    public function toJson(): string
    {
        return json_encode([
            'status' => $this->status,
            'transactionId' => $this->transactionId,
            'approvalUrl' => $this->approvalUrl,
            'message' => $this->message,
            'keys' => $this->keys,
        ], JSON_PRETTY_PRINT);
    }

    /**
     * Convert the response to an XML format.
     */
    public function toXml(): string
    {
        $xml = new \SimpleXMLElement('<PaymentResponse/>');
        $xml->addChild('status', $this->status);
        $xml->addChild('transactionId', $this->transactionId ?? '');
        $xml->addChild('approvalUrl', $this->approvalUrl ?? '');
        $xml->addChild('message', $this->message ?? '');

        if (!empty($this->keys)) {
            $keysNode = $xml->addChild('keys');
            foreach ($this->keys as $key => $value) {
                $keysNode->addChild($key, htmlspecialchars($value));
            }
        }

        return $xml->asXML();
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