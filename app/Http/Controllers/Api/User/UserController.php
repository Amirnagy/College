<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use App\Models\Student;
use App\Traits\InfoStudent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    use InfoStudent;


    public function updateuser(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|regex:/^9665\d{8}$/|unique:users,phone',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'department' => 'nullable',
            'faculty' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Get the authenticated user
        $user = $request->user();
        // Check if the requested ID matches the authenticated user's ID
        if ($user->id != $request->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $data = [];

        // Update the user information
        if ($request->name) {
            $user->name = $request->name;
            $data['name'] = 'user name update successfully';
        }
        if($request->phone)
        {
            $user->phone = $request->phone;
            $data['phone'] = 'phone update successfully';

        }
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $ImageName = rand(100000, 999999) . time() . $image->getClientOriginalName();
            $path = $image->storeAs('UserProfile', $ImageName, 'College');
            $user->profile_image = $path;
            $data['profile_image'] = 'profile image update successfully';
        }
        $user->save();
        //  old data of student in university


        $student = Student::where('user_id',$user->id)->first();
        if($request->faculty && $request->department)
        {
            $check =  $this->checkchoose($request);
            if($check)
            {
                $student->faculty_id = $request->faculty;
                $student->department_id = $request->department;
                $student->save();
                $data['data_faculty'] = 'data faculty update successfully';
            }else{
                $data['data_faculty'] = 'data faculty update not matched';
            }
        }
        // Return a response
        return $this->success($data);

    }

    public function updatepassword(Request $request)  {

        $validator = Validator::make($request->all(),[
            'old_password' => 'required|min:8',
            'password' => 'required|min:8|confirmed',
        ]);
            if ($validator->fails()) {
                return response()->json(["messege"=>false,"errors"=>$validator->errors()],422);
            }
        try {
            $user = $request->user('api');
            if (!Hash::check($request->old_password,$user->password))
            {
                throw new Exception("Old password is incorrect");
            }else{
                $hashedPassword = Hash::make($request->password);
                $user->update(['password' => $hashedPassword]);
            }

            return response()->json(["message" => true, "data" => "Password reset successfully"], 200);
        } catch (Exception $e) {
            // Handle the exception and return an error response
            return response()->json(["message" => false, "error" => "Password reset failed"], 500);
        }
    }

}
