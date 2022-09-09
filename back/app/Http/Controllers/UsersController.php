<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UsersController extends BaseController
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        return $this->handle();
    }

    public function handle(User $user = null)
    {
        $validate = Request::validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'name' => ['required'],
        ]);


        if (Request::get('password') !== Request::get('password_confirmation')) {
            return request()->json(401, [
                'message' => 'The provided credentials do not match our records.',
            ]);
        }

        if ($user) {
            $user->update([
                'email' => Request::get('email'),
                'password' => Hash::make(Request::get('password')),
                'name' => Request::get('name'),
            ]);
            $user->save();
            return request()->json(200, ['message' => 'User updated']);
        } else {
            $user = User::create([
                'email' => Request::get('email'),
                'password' => Hash::make(Request::get('password')),
                'name' => Request::get('name'),
            ]);
            return request()->json(200, [
                'message' => 'User created successfully',
                'user' => $user,
            ]);
        }
    }
}
