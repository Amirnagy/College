<?php
namespace App\Traits;

use Carbon\Carbon;
use App\Models\OtpRest;

trait OtpRestPassword
{

    function generateRestOTP($identifier){
        // create opt on reste otp
        $otp = OtpRest::where('identifier',$identifier)->first();
        if($otp)
        {
            $otp->delete();
        }
        $otp = new OtpRest();
        $otp->identifier = $identifier;
        $otp->token = random_int(10000,99999);
        $otp->expired = Carbon::now()->addMinutes(20)->format('Y-m-d H:i:s');
        $otp->no_times_attempted = 0;
        $otp->generated_at = Carbon::now();
        $otp->save();
        return $otp;
    }

    public function checkRestOTP($identifier,$token)
    {
        $otp = OtpRest::where("identifier",$identifier)->first();
        if($otp)
        {
            if ($otp->expired <= Carbon::now()) {
                $otp->delete();
                return false;
            }
            else{
                // check the otp to return true
                if($otp->token == $token)
                {
                    $otp->delete();
                    return true;
                }
            }
        }
        return false;
    }



}
