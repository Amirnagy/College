<?php

namespace App\Traits;
use Carbon\Carbon;
use Seshac\Otp\Otp;
use App\Models\User;
use App\Mail\SendOTP;
use App\Jobs\MyEmailJob;
use App\Models\OtpRest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Seshac\Otp\Models\Otp as ModelsOtp;
use Illuminate\Support\Facades\Validator;

trait OtpVarifed
{

    function sendOTP($user) {

        $otp = $this->generateOTP($user->id);
        // Mail::to($user->email)->send(new SendOTP($user->name,$otp->token,$otp->validity));
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
        $expires = Otp::expiredAt($identifier);
        if ($expires <= now()) {
            return false;
        }

        $verify = ModelsOtp::where('identifier',$identifier)->first();
        if($verify)
        {
            if ($verify->token == $token)
            {
                $verify->delete();
                return true;
            }
        }
        return false;
    }



}
