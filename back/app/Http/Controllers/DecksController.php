<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use App\Models\ExtraDeck;
use App\Models\User;
use App\Models\Image;
use App\Models\MainDeck;
use App\Models\SideDeck;
use Illuminate\Http\Request;

class DecksController extends Controller
{
    //

    public function index()
    {
        $decks = Deck::public()->filtered(request()->only(['search', 'searchBy', 'illegal']))
            ->with(
                'user:id,name',
                'mainDeck',
                'extraDeck',
                'sideDeck',
                'image:id,path'
            )
            ->paginate(25)
            ->withQueryString();
        return response()->json($decks, 200);
    }

    public function show(Deck $deck)
    {
        $user = User::where('id', auth('sanctum')->user()?->id)->first();

        if(!$deck->public) {
            if($user?->can('show', $deck)) {
                $deck->load('user:id,name', 'mainDeck', 'extraDeck', 'sideDeck');
                return response()->json($deck, 200);
            } else {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        } else {
            $deck->load('user:id,name', 'mainDeck', 'extraDeck', 'sideDeck');
            return response()->json($deck, 200);
        }

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
        return $this->handle();
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

        $card_image_id = request()->image_id ?? Image::where('name', 'card_verso.jpg')->first()->id;

        $deckCopy = Deck::create([
            'name' => request()->name,
            'description' => request()->description,
            'user_id' => request()->user_id,
            'image_id' => $card_image_id,
            'public' => request()->public,
            'illegal' => $deck->illegal,
        ]);
        $deckCopy->mainDeck()->attach($deck->mainDeck);
        $deckCopy->extraDeck()->attach($deck->extraDeck);
        // $deckCopy->sideDeck()->attach(request()->side);
        $deckCopy->save();

        return response()->json($deckCopy, 201);
    }

    public function handle(Deck $deck = null)
    {

        request()->validate([
            'name' => 'required',
            'description' => 'max:255',
            'user_id' => 'required',
            'image_id' => 'nullable',
            'public' => 'required',
            'illegal' => 'required',
        ]);

        $card_image_id = request()->image_id ?? Image::where('name', 'card_verso.jpg')->first()->id;

        if (!$deck) {
            $deck = Deck::create([
                'name' => request()->name,
                'description' => request()->description,
                'user_id' => request()->user_id,
                'image_id' => $card_image_id,
                'public' => request()->public,
                'illegal' => request()->illegal
            ]);

            $deck->mainDeck()->attach(request()->main);
            $deck->extraDeck()->attach(request()->extra);
            $deck->sideDeck()->attach(request()->side);

            $deck->save();

            return response()->json($deck, 201);
        }

        $deck->update([
            'name' => request()->name,
            'description' => request()->description,
            'user_id' => request()->user_id,
            'image_id' => $card_image_id,
            'public' => request()->public,
            'illegal' => request()->illegal,
        ]);
        $deck->mainDeck()->detach();
        $deck->extraDeck()->detach();
        $deck->sideDeck()->detach();

        $deck->mainDeck()->attach(request()->main);
        $deck->extraDeck()->attach(request()->extra);
        $deck->sideDeck()->attach(request()->side);

        return response()->json($deck, 200);
    }

    public function destroy(Deck $deck)
    {
        if (auth('sanctum')->user()) {
            $user = User::where('id', auth('sanctum')->user()->id)->first();

            if ($user->cannot('delete', $deck)) {
                return response()->json('Unauthorized', 403);
            }

            $deck->mainDeck()->detach(request()->main);
            $deck->extraDeck()->detach(request()->extra);
            $deck->sideDeck()->detach(request()->side);

            $deck->delete();
            return response()->json($user->decks, 204);
        }
    }
}
