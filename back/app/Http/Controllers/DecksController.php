<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use Illuminate\Http\Request;

class DecksController extends Controller
{
    //
    public function store()
    {
        $this->handle();
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
            $deck->cards()->sync(request()->cards);
        }
    }
}
