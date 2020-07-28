<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderProccessing extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;
    public $userMail;
    public $paid;
    public $paymentMethod;
    public $paymentStatus;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, string $userMail, bool $paid = false)
    {
        $this->order = $order;
        $this->userMail = $userMail;
        $this->paid = $paid;
        $this->paymentMethod = $order->paymentMethod === 'accept' ? 'Accept-Pay' : __('order.cashOnDelivery');
        $this->paymentStatus = $order->paymentStatus ? __('order.mail.payDone') : __('order.mail.payFail');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('order.mail.orderProcc'))
            ->markdown('emails.order-proccess');
    }
}
