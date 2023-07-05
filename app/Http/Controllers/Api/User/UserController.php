<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function update(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'phone' => 'string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

        // Update the user information
        if ($request->name) {
            $user->name = $request->name;
        }
        if($request->phone)
        {
            $user->phone = $request->phone;
        }

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $ImageName = rand(100000, 999999) . time() . $image->getClientOriginalName();
            $path = $image->storeAs('UserProfile', $ImageName, 'College');
            $user->profile_image = $path;
        }

        $user->save();

        // Return a response
        return response()->json(['message' => 'User information updated successfully']);

    }


    private function handelIamge($apartments)
    {
        $apartments = $apartments->map(function ($apartment) {
            $images = json_decode($apartment->image);
            $apartment->image = collect($images)->map(function ($image) {
                return env('APP_URL').'/public'.'/'. $image;
            });
            return $apartment;
        });
        return $apartments;
    }

}
