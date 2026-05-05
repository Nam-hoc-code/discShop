<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $cart;
    public $receiver_name;
    public $phone;
    public $address;

    /**
     * Create a new message instance.
     */
    public function __construct($cart, $receiver_name, $phone, $address)
    {
        $this->cart = $cart;
        $this->receiver_name = $receiver_name;
        $this->phone = $phone;
        $this->address = $address;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Xác nhận đơn hàng - Music Platform')
                    ->view('emails.order_confirmed');
    }
}
