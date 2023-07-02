<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\University;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    function register(Request $request) {
        // try to make request vaildated
        $validator = Validator::make($request->all(), [
            'username' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'phone' => 'regex:/^9665\d{8}$/',
            'password' => 'required|min:8|confirmed',
            'university' => 'required',
            'faculty' => 'required',
            'department' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
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
                "email" => $request->email,
                "password" => $request->password,
                "phone" => $request->phone]);

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
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');
        if (!Auth::guard('api')->attempt($credentials)) {
            return $this->error("Invalid credentials");
        }

        $user = Auth::guard('api')->user();
        $token = Auth::guard('api')->attempt($credentials);
            return $this->success(['token'=>$token,'user'=>$user]);
    }
}
