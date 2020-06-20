<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => "Invalid Input",
                'user' => null
            ]);
        }
        $creds = $request->only(['email', 'password']);
        if (!$token = Auth()->attempt($creds)) {
            return response()->json([
                'success' => false,
                'message' => 'username or password is invalid'
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Login Successfully',

            'token' => $token,
            'user' => new UserResource(Auth::user())
        ]);
    }

    public function register(Request $request)
    {
        $encryptPassword = Hash::make($request->password);

        $user = new User();

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:5',
                'password' => 'required|min:8 ',
                'email' => 'required|email|unique:users,email',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => "Please valdiate your registration form",
                    'user' => null
                ]);
            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $encryptPassword;
            $user->save();
            return response()->json([
                'success' => true,
                'message' => "Registration successfully completed!",
                'user' => new UserResource($user)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '' . $e,
                'user' => null
            ]);
        }
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::parseToken($request->token));
            return response()->json([
                'success' => true,
                'message' => 'logout successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '' . $e
            ]);
        }
    }

    public function profile(User $user)
    {
        $user = Auth::user();
        return response()->json([
            'success' => true,
            'data' => new UserResource($user)
        ]);
    }

    public function saveUserInfo(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $photo = '';

        if ($request->photo != '') {
            $photo = time() . '.jpg';

            file_put_contents('storage/profiles' . $photo, base64_decode($request->photo));
            $user->photo = $photo;
        }

        $user->update();

        return response()->json([
            'success' => true,
            'photo' => $photo
        ]);
    }
}
