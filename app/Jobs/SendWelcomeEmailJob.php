<?php

namespace App\Jobs;

use App\Notifications\WelcomeNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWelcomeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

     /**
     * Create a new job instance.
     */
    private $customer;
    public function __construct($customer)
    {
        //
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $this->customer->notify((new WelcomeNotification([
            'token' => $this->customer->confirm_code,
            'code' => $this->customer->activation_code,
            'name' => $this->customer->name,
        ])));
    }
}
