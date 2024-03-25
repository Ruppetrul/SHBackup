<?php

namespace Modules\Mini\Models;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SimpleEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $orderData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orderData)
    {
        $this->orderData = $orderData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
Log::debug('data:: ' . json_encode($this->orderData));
        return $this->from(env('MAIL_USERNAME'))
            ->subject('Новый заказ!')
            ->view('emails.simple')
            ->with('orderData', $this->orderData);
    }
}
