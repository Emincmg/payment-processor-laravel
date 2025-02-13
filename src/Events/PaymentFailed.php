<?php

namespace Emincmg\PaymentProcessorLaravel\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Emincmg\PaymentProcessorLaravel\Classes\Payment;
class PaymentFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(\Exception $exception, Payment $payment)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('payments.'.$this->payment->id),
        ];
    }


    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith() : array
    {
        return ['id'=>$this->payment->id];
    }
}
