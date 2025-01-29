<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderDetails extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $customMessage;

    public function __construct(Order $order, string $customMessage)
    {
        $this->order = $order;
        $this->customMessage = $customMessage;
    }

    public function build()
    {
        return $this->subject('Votre commande #' . $this->order->uuid)
                    ->markdown('emails.order-details');
    }
} 