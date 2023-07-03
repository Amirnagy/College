<?php

namespace App\Traits;
use Carbon\Carbon;
use Seshac\Otp\Otp;
use App\Models\User;
use App\Mail\SendOTP;
use App\Jobs\MyEmailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Seshac\Otp\Models\Otp as ModelsOtp;
use Illuminate\Support\Facades\Validator;

trait OtpTrait
{

    function sendOTP($user) {

        $otp = $this->generateOTP($user->id);
        $jop = (new MyEmailJob($otp->token,$user->email,$user->name,$otp->validity))->delay(Carbon::now()->addSeconds(2));
            dispatch($jop);
    }


    function generateOTP($identifier){
        $otp = ModelsOtp::where('identifier',$identifier)->first();
        if($otp)
        {
            $otp->delete();
        }
        $otp = Otp::generate($identifier);
        $otp = ModelsOtp::where('identifier',$identifier)->first();
        return $otp;
    }



    public function checkOTP($identifier,$token)
    {
        $verify = ModelsOtp::where('identifier',$identifier)->first();
        if ($verify->token == $token)
        {
            return true;
        }

        return false;
    }






}
