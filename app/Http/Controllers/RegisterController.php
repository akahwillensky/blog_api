<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // $this->validate($request, [
        //     'name' => 'required|string|min:2|max:255',
        //     'email' => 'required|string|email|unique:users',
        //     'password' => 'required|min:6|max:100',
        //     'confirm_password' => 'required|same:password',
        // ]);
        /// validate user info
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:6|max:100',
            'confirm_password' => 'required|same:password',
        ]);
        /// return failed form post requests
        if($validator->fails()){
            return response()->json([
                'message'=>'Validations failed',
                'errors' => $validator->errors()
            ], 400);
        }
        /// add new user to the db
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        /// create token for new user .
        $token = JWTAuth::fromUser($user);

        //return success info for new user
        return response()->json([
            'message'=> 'Your account was created',
            'token' => $token,
            'user' => $user,
        ]);
    }
}