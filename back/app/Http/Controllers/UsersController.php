<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use App\Models\Image;
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
     * @return \Illuminate\Http\JsonResponse
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
     * Return a json response with the user's favorites decks
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function favorites(User $user)
    {
        if (auth('sanctum')->user()) {
            if ($user->cannot('showFavorites', $user)) {
                return response()->json('Unauthorized', 403);
            }

            $decks = $user->favorites()
                ->filtered(request()->only(['search', 'searchBy', 'illegal']))
                ->with('user:id,name')
                ->paginate(25)
                ->withQueryString();

            return response()->json($decks, 200);
        }

        return response()->json('No user auth', 403);
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
     * @param User $user
     * Handle update or create user
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(User $user = null)
    {

        if (Request::get('password')) {
            if (Request::get('password') !== Request::get('password_confirmation')) {
                return response()->json([
                    'errors' => [
                        'password_confirmation' => ['The password confirmation does not match.']
                    ]
                ], 401);
            }
        }


        $avatar_id = request()->image_id ?? Image::where('name', 'default_avatar.jpg')->first()?->id;

        if ($user) {
            Request::validate([
                'email' => 'email|unique:users,email,' . $user->id,
                'name' => 'unique:users,name,' . $user->id . '|max:32',
                'image_id' => 'nullable|integer',
                'old_password' => 'nullable|required_with:password,',
                'password' => 'min:4|same:password_confirmation|nullable',
                'password_confirmation' => 'min:4|same:password|required_with:password',
            ]);

            if (Request::get('password')) {
                if (!Hash::check(Request::get('old_password'), $user->password)) {
                    return response()->json([
                        'errors' => [
                            'old_password' => 'Old password is incorrect',
                        ]
                    ], 401);
                }
                $password = Hash::make(Request::get('password'));
            }

            $user->fill([
                'email' => Request::get('email') ?? $user->email,
                'name' => Request::get('name') ?? $user->name,
                'password' => $password ?? $user->password,
                'image_id' => Request::get('image_id') ?? $user->image_id,
            ]);
            $user->save();

            $response = [
                'message' => 'User updated',
                'user' => $user,
                'password' => Request::get('password') ?? false,
            ];

            return response()->json($response, 200);
        } else {
            Request::validate([
                'email' => 'required|email|unique:users',
                'password' => 'min:4|same:password_confirmation|nullable',
                'password_confirmation' => 'min:4|same:password|required_with:password',
                'name' => 'required|unique:users|max:32',
                'image_id' => 'nullable|integer',
            ]);

            $user = User::create([
                'email' => Request::get('email'),
                'password' => Hash::make(Request::get('password')),
                'name' => Request::get('name'),
                'image_id' => $avatar_id,
            ]);
            return request()->json(201, [
                'message' => 'User created successfully',
                'user' => $user,
            ]);
        }
    }

    /**
     * @param User $user
     * Delete user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        if (auth('sanctum')->user()) {
            if ($user->cannot('delete', $user)) {
                return response()->json('Unauthorized', 403);
            }

            Request::validate([
                'password_delete' => 'required',
            ]);

            if (!Hash::check(Request::get('password'), $user->password)) {
                return response()->json([
                    'errors' => [
                        'password_delete' => 'Password is incorrect',
                    ]
                ], 401);
            }

            $user->tokens()->delete();
            $user->decks->map(function ($deck) {
                $deck->cards()->detach();
            });
            $user->decks()->delete();
            $user->favorites()->detach();
            $user->delete();

            return response()->json('User deleted', 200);
        }

        return response()->json('No user auth', 403);
    }

    public function updateAvatar(User $user)
    {
        Request::validate([
            'image_id' => 'required|integer',
        ]);

        $user->image_id = request()->image_id;
        $user->save();

        return response()->json($user->load(['favorites', 'image']), 200);
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * Return user decks
     */
    public function decks(User $user)
    {
        if (auth('sanctum')->user()) {
            if ($user->cannot('showDecks', $user)) {
                return response()->json('Unauthorized', 403);
            }

            $decks = $user->decks()
                ->filtered(request()->only(['search', 'searchBy', 'illegal']))
                ->with('user:id,name')
                ->paginate(25)
                ->withQueryString();

            return response()->json($decks, 200);
        }

        return response()->json('No user auth', 403);
    }
}
