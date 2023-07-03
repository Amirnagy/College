<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Models\Student;
use App\Traits\OtpTrait;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Traits\InfoStudent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use OtpTrait,InfoStudent;



    function register(RegisterRequest $request) {

        $request->validated();
        $university = University::select('id')->where('id',$request->university)
        ->with(['faculty' => function($query) use ($request)
        {
            $query->select('id','university_id')->where('id',$request->faculty)->first();

            $query->with(['department'=>function ($query) use ($request)
            {
                $query->select('id','faculty_id')->where('id',$request->department)->first();
            }]);
        }])->first();

        if ($university && $university->faculty->isNotEmpty() && $university->faculty->first()->department->isNotEmpty()) {
            $request->merge(['password' => bcrypt($request->password)]);
            $user = User::create([
                "name"      =>  $request->username,
                "email"     => $request->email,
                "password"  => $request->password,
                "phone"     => $request->phone]);

            $students = new Student();
            $students->user_id = $user->id;
            $students->university_id = $university->id;
            $students->faculty_id = $university->faculty[0]->id;
            $students->department_id =$university->faculty[0]->department[0]->id;
            $students->save();

            return $this->success($user,'user successfull registered');
        } else {
            return $this->error('error on university , faculty and department');
        }
    }


    function login(Request $request) {

        $request->validated();
        $credentials = $request->only('email', 'password');
        if (!Auth::guard('api')->attempt($credentials)) {
            return $this->error("Invalid credentials");
        }

        $user = Auth::guard('api')->user();

        if($user->email_verified_at)
        {
            $token = Auth::guard('api')->attempt($credentials);
            $university = $this->userDataUniversity($user->id,$request->lang);
            return $this->success(['token'=>$token,'user'=>$user,"relatedData" => $university]);
        }
        {
            $this->sendOTP($user);
            return $this->success(0,'please check your mail to varify account');
        }
    }

}
