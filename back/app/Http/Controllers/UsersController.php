<?php

namespace App\Http\Controllers;

use App\Models\Deck;
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

    /**
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json($user->load('favorites'), 200);
    }

    public function edit(User $user)
    {
        if (auth('sanctum')->user()) {
            $user = User::where('id', auth('sanctum')->user()->id)->first();

            if ($user->cannot('update', $user)) {
                return response()->json('Unauthorized', 403);
            }

            return $this->show($user);
        }
        return response()->json('No user auth', 403);
    }

    /**
     * Create a new user instance after a valid registration.
     * @param  \Illuminate\Http\Request  $request
     */
    public function store()
    {
        return $this->handle();
    }

    public function update(User $user)
    {
        if (auth('sanctum')->user()) {
            if ($user->cannot('update', $user)) {
                return response()->json('Unauthorized', 403);
            }

            return $this->handle($user);
        }
    }

    /**
     * Add favorite deck to user
     * @param Deck $deck, User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function addFavorite(User $user)
    {
        $deck = Deck::where('id', request()->deck_id)->first();

        if ($deck) {
            $user->favorites()->attach($deck->id);
            $user->save();
            return response()->json($user->favorites, 201);
        }

        return response()->json('Deck not found', 404);
    }

    /**
     * Remove favorite deck from user
     * @param User $user, Deck $deck
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFavorite(User $user)
    {
        $deck = Deck::find(request()->deck_id);

        if ($deck) {
            $user->favorites()->detach($deck->id);
            $user->save();
            return response()->json($user->favorites, 201);
        }

        return response()->json('Deck not found', 404);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * Handle update and create user
     * @return \Illuminate\Http\Response
     */
    public function handle(User $user = null)
    {

        if(Request::get('password')) {
            if (Request::get('password') !== Request::get('password_confirmation')) {
                return request()->json(401, [
                    'message' => 'Passwords do not match',
                ]);
            }
        }

        if ($user) {
            Request::validate([
                'email' => 'email|unique:users,email,' . $user->id,
                'name' => 'unique:users,name,' . $user->id,
                'password' => 'min:4|nullable|must_match:password_confirmation',
                'password_confirmation' => 'min:4|nullable|must_match:password',
            ]);

            $password = Request::get('password') ? Hash::make(Request::get('password')) : $user->password;

            $user->fill([
                'email' => Request::get('email') ?? $user->email,
                'name' => Request::get('name') ?? $user->name,
                'password' => $password,
            ]);
            $user->save();

            return request()->json(200, [
                'message' => 'User updated',
                'user' => $user,
            ]);
        } else {
            Request::validate([
                'email' => 'required|email|unique:users',
                'password' => 'required|min:4|must_match:password_confirmation',
                'password_confirmation' => 'required|min:4|must_match:password',
                'name' => 'required|unique:users',
            ]);

            $user = User::create([
                'email' => Request::get('email'),
                'password' => Hash::make(Request::get('password')),
                'name' => Request::get('name'),
            ]);
            return request()->json(201, [
                'message' => 'User created successfully',
                'user' => $user,
            ]);
        }
    }
}
