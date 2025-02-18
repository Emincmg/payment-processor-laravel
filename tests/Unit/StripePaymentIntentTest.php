<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\Unit\UnitTest;

class StripePaymentIntentTest extends UnitTest
{
    use WithFaker, RefreshDatabase;
    #[Test] public function test_it_processes_payment_and_returns_response_dto()
    {
        // Arrange: Fake a request DTO
        $paymentDTO = new PaymentRequestDTO(
            channel: 'paypal',
            amount: 100.00,
            currency: 'USD',
            description: 'Test payment',
            userName: 'John Doe',
            userEmail: 'john@example.com',
            userPhone: '123456789'
        );

        // Act: Call the service method
        $responseDTO = PaymentService::processPayment($paymentDTO);

        // Assert: Validate response
        $this->assertInstanceOf(PaymentResponseDTO::class, $responseDTO);
        $this->assertEquals('success', $responseDTO->status);

        // Check if JSON response is properly formatted
        $jsonResponse = $responseDTO->toJson();
        $this->assertJson($jsonResponse);
    }
}