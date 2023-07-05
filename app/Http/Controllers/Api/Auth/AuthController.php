<?php

namespace App\Http\Controllers\Api\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\SendOTP;
use App\Models\Student;
use App\Models\University;
use App\Traits\OtpVarifed;
use App\Traits\InfoStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\User as ResourcesUser;

class AuthController extends Controller
{
    use OtpVarifed,InfoStudent;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','varifyUser']]);
    }



    function register(Request $request)
    {

        $vaildator = Validator::make($request->all(),[
            'username' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'phone' => 'regex:/^9665\d{8}$/|unique:users,phone',
            'password' => 'required|min:8|confirmed',
            'university' => 'required',
            'faculty' => 'required',
            'department' => 'required'
        ]);
            if ($vaildator->fails()) {
                return response()->json(["messege"=>false,"errors"=>$vaildator->errors()],422);
            }
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
                "phone"     => $request->phone,
                "profile_image"=> "UserProfile/3614471688554187profile.png" ]);

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


    function login(Request $request)
    {
        $vaildator = Validator::make($request->all(),[
        'email' => 'required|email',
        'password' => 'required|min:8',
        'lang' =>'required|max:2|string']);
        if ($vaildator->fails()) {
            return response()->json(["messege"=>false,"errors"=>$vaildator->errors()],422);
        }
        $credentials = $request->only('email', 'password');
        if (!Auth::guard('api')->attempt($credentials)) {
            return $this->error("Invalid credentials");
        }

        $user = Auth::guard('api')->user();
        if($user->email_verified_at)
        {
            $token = auth('api')->setTTL(604800)->attempt($credentials);
            $user = new UserResource($user);

            $university = $this->userDataUniversity($user->id,$request->lang);
            return $this->success(["verified"=> true,'token'=>$token,'user'=>$user,"relatedData" => $university]);
        }
        {
            $this->sendOTP($user);
            return $this->success(["verified"=> false],'please check your mail to varify account');
        }
    }

    public function varifyUser(Request $request)
    {
        $vaildator = Validator::make($request->all(),[
        'email' => 'required|email|exists:users,email',
        "code" => "required|string|min:1|max:6"
        ]);
        if ($vaildator->fails())
        {
            return response()->json(["messege"=>false,"errors"=>$vaildator->errors()],422);
        }
        $user = User::where('email',$request->email)->first();
        $varifed = $this->checkOTP($user->id,$request->code);

        if($varifed)
        {
            //update email verified at time in user table.
            DB::table('users')
            ->where('id', $user->id)
            ->update(["email_verified_at"=>Carbon::now()]);
            return $this->success(0,'OTP Correct');
        }else{
            return $this->error('OTP wrong');
        }
    }


    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function deleteAccount(Request $request)
    {
        try {
            $user = $request->user();
            $user->delete();
        } catch (\Exception $e)
        {
            return response()->json(['message' => 'Error deleting account'], 500);
        }

        return response()->json(['message' => 'Account deleted successfully'],200);
    }
}
