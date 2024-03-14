<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Modules\Mini\Models\SimpleEmail;
use Illuminate\Support\Facades\Log;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $recipient;
    protected $orderData;

    /**
     * @param $recipient
     * @param $orderData
     */
    public function __construct($recipient, $orderData)
    {
        $this->recipient = $recipient;
        $this->orderData = $orderData;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        Log::debug(json_encode($this->orderData));
        Mail::to($this->recipient)->send(new SimpleEmail($this->orderData));
    }
}
