<?php

namespace App\Jobs;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendResetPasswordEmailjob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $passwordReset;
    public function __construct($passwordReset)
    {
        //
        $this->passwordReset = $passwordReset;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $this->passwordReset->notify((new ResetPasswordNotification([
            'email' => $this->passwordReset->registeration_option,
            'code' => $this->passwordReset->code,
        ])));
    }
}
