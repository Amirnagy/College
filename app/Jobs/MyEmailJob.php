<?php

namespace App\Jobs;

use App\Mail\SendOTP;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class MyEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $pin_code;
    public $email;
    public $name;
    public $expirationTime;
    /**
     * Create a new job instance.
     *
     *
     */
    public function __construct($pin_code,$email,$name,$expirationTime)
    {
        $this->pin_code = $pin_code;
        $this->email = $email;
        $this->name = $name;
        $this->expirationTime = $expirationTime;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new SendOTP($this->name,$this->pin_code,$this->expirationTime));
        
    }
}
