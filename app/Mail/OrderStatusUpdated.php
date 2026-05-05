<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $statusText;

    /**
     * Create a new message instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
        
        $statuses = [
            'PENDING'   => 'Đang chờ xử lý',
            'CONFIRMED' => 'Đã xác nhận đơn hàng',
            'SHIPPING'  => 'Đang giao hàng',
            'COMPLETED' => 'Đã giao hàng thành công',
            'CANCELLED' => 'Đã hủy đơn hàng',
        ];

        $this->statusText = $statuses[$order->status] ?? $order->status;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cập nhật trạng thái đơn hàng #' . $this->order->id . ' - ' . $this->statusText,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_status_updated',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
