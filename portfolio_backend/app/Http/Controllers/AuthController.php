<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\Verify;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function checkVerify(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::where("email", $request->email)->get()->first();
        if(!$user){
            return response()->json(["error"=> "Invalid email"],422);
        }
        if(!$user->email_verified_at || $user->code != null){
            return response()->json([
                "error"=> "Not Verified"
            ], 422);
        }
        return response()->json([
            "status" => "success",
        ]);

    }
    public function verify(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'code' => 'required|numeric|min:100000|max:999999',
            ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::where('email', $request->email)->get()->first();
        if (!$user) {
            return response()->json(['error' => 'Invalid email'], 422);
        }
        if($user->code != $request->code){
            return response()->json(['error' => 'Invalid code'], 422);
        }
        $user->code = null;
        $user->email_verified_at = now();
        $user->save();
        return response()->json([
            'message' => 'User verified successfully',
            'user' => new UserResource($user),
            'access_token' => $user->createToken('auth_token')->plainTextToken
        ]);
    }
    public function register(Request $request){
        $req = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($req->fails()) {
            return response()->json(['errors' => $req->errors()], 422);
        }
        try {
            $code = random_int(100000, 999999);
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'code' => $code
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;
            Mail::to($user->email)->send(new Verify($user, $token, $code));
            return response()->json([
                'message' => 'User created successfully',
                'user' => new UserResource($user),
                'access_token' => $token
            ]);
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function login(Request $request){
        $req = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($req->fails()) {
            return response()->json(['errors' => $req->errors()], 422);
        }
        try {
            if(!Auth::attempt($request->only('email', 'password'))){
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
            $user = User::where('email', $request->input('email'))->first();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'User logged in successfully',
                'user' => $user,
                'access_token' => $token
            ]);
        }
        catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'user has been logged out successfully'
        ],200);
    }
}
