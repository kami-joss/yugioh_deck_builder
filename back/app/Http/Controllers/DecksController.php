<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use App\Models\User;
use Illuminate\Http\Request;

class DecksController extends Controller
{
    //

    public function index()
    {
        $decks = Deck::public()->filtered(request()->only(['search', 'searchBy', 'illegal']))
            ->with('user:id,name')
            ->paginate(25)
            ->withQueryString();
        return response()->json($decks, 200);
    }

    public function show(Deck $deck)
    {
        $deck->load('user:id,name', 'cards', 'cards.image');
        return response()->json($deck, 200);
    }

    public function edit(Deck $deck,)
    {
        if (auth('sanctum')->user()) {
            $user = User::where('id', auth('sanctum')->user()->id)->first();

            if ($user->cannot('update', $deck)) {
                return response()->json('Unauthorized', 403);
            }

            return $this->show($deck);
        }
        return response()->json('No user auth', 403);
    }

    public function store()
    {
        $this->handle();
    }

    public function update(Deck $deck)
    {
        if (auth('sanctum')->user()) {
            $user = User::where('id', auth('sanctum')->user()->id)->first();

            if ($user->cannot('update', $deck)) {
                return response()->json('Unauthorized', 403);
            }

            return $this->handle($deck);
        }
    }

    public function clone(Deck $deck)
    {
        request()->validate([
            'name' => 'required',
            'description' => 'max:255',
            'user_id' => 'required',
            'image_id' => 'nullable',
            'public' => 'required',
        ]);

        $deckCopy = Deck::create([
            'name' => request()->name,
            'description' => request()->description,
            'user_id' => request()->user_id,
            'image_id' => request()->image_id,
            'public' => request()->public
        ]);
        $deckCopy->cards()->attach($deck->cards);
        $deckCopy->save();

        return response()->json($deckCopy, 201);
    }

    public function handle(Deck $deck = null)
    {
        request()->validate([
            'name' => 'required',
            'description' => 'max:255',
            'user_id' => 'required',
            'cards' => 'required',
            'image_id' => 'nullable',
            'public' => 'required',
        ]);

        if (!$deck) {
            $deck = Deck::create([
                'name' => request()->name,
                'description' => request()->description,
                'user_id' => request()->user_id,
                'image_id' => request()->image_id,
                'public' => request()->public,
            ]);
            $deck->cards()->attach(request()->cards);
            return response()->json($deck, 201);
        }

        $deck->update([
            'name' => request()->name,
            'description' => request()->description,
            'user_id' => request()->user_id,
            'image_id' => request()->image_id,
            'public' => request()->public,
        ]);
        $deck->cards()->detach();
        $deck->cards()->attach(request()->cards);
    }

    public function delete(Deck $deck)
    {
        if (auth('sanctum')->user()) {
            $user = User::where('id', auth('sanctum')->user()->id)->first();

            if ($user->cannot('delete', $deck)) {
                return response()->json('Unauthorized', 403);
            }

            $deck->cards()->detach();
            $deck->delete();
            return response()->json('deleted', 204);
        }
    }
}
