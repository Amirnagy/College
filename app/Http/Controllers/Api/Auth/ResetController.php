<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\OtpRestPassword;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetController extends Controller
{

    use OtpRestPassword;

    function checkRest(Request $request) {
        $vaildator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email',
        ]);
            if ($vaildator->fails())
            {
                return response()->json(["messege"=>false,"errors"=>$vaildator->errors()],422);
            }
            $user = User::where('email',$request->email)->first();
            if($user)
            {
                // send mail and save otp in db
                $otp = $this->generateRestOTP($user->id);
                // send mail
                // Mail::to($user->email)->send(new SendOTP($user->name,$otp->token,'20'));
                return $this->success(true,"email send successfully");
            }
            return $this->error('email not registered ');

    }

    /**
    * recive otp from reset
    */
    function checkresetOTP(Request $request){
        $vaildator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email',
            "otp" => "required|string|max:6"
        ]);
            if ($vaildator->fails())
            {
                return response()->json(["messege"=>false,"errors"=>$vaildator->errors()],422);
            }
        $user = User::where('email',$request->email)->first();
        $correct_otp = $this->checkRestOTP($user->id,$request->otp);
        if($correct_otp)
        {
            return $this->success("ok");
        }else{
            return  $this->error("wrong OTP please try again",501);
        }

    }


    function resetPassword(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
            if ($validator->fails()) {
                return response()->json(["messege"=>false,"errors"=>$validator->errors()],422);
            }
        try {
            $user = User::where('email', $request->email)->first();
            $hashedPassword = Hash::make($request->password);
            $user->update(['password' => $hashedPassword]);

            // Optionally, you can return a success response
            return response()->json(["message" => true, "data" => "Password reset successfully"], 200);
        } catch (\Exception $e) {
            // Handle the exception and return an error response
            return response()->json(["message" => false, "error" => "Password reset failed"], 500);
        }
    }
}
