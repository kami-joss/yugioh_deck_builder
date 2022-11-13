<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AuthController extends BaseController
{

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    // public function authenticate(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => ['required', 'email'],
    //         'password' => ['required'],
    //     ]);

    //     if (Auth::attempt($credentials)) {
    //         $request->session()->regenerate();

    //         return redirect()->intended('dashboard');
    //     }

    //     return request()->json(401, [
    //         'message' => 'The provided credentials do not match our records.',
    //     ]);
    // }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->with('favorites')->first();

        if (!$user) {
            return response()->json([
                'errors' => [
                    'email' => ['User not found'],
                ],
            ], 404);
        }

        if ($user && !Hash::check($request->password, $user->password)) {
            return response()->json([
                'errors' => [
                    'password' => ['Password is incorrect'],
                ],
            ], 401);
        }

        return response()->json([
            'token' => $user->createToken($request->email)->plainTextToken,
            'user' => $user
        ], 201);
    }

    public function logout()
    {
        $user = User::where('id', auth('sanctum')->user()?->id)->first();
        if ($user) {
            $user->tokens()->delete();
            return response()->json('Logged out', 200);
        }
        return response()->json('User not found', 404);
    }

    public function index()
    {
        return request()->header('Authorization');
        dd(auth('sanctum')->user());
    }
}
