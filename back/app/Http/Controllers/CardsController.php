<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Support\Facades\Request;

class CardsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cards = Card::with('image_small')->filtered(request()->only('name', 'types', 'attributes', 'races'))->paginate(30)->withQueryString();
        return response()->json($cards, 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param \App\Models\Card $card
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card)
    {
        return response()->json($card->load('image'), 201);
    }

    /**
     * Store a newly created resource in storage.
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        return $this->handle();
    }

    /**
     * Update the specified resource in storage.
     * @param \App\Models\Card $card
     * @return \Illuminate\Http\Response
     */
    public function update(Card $card)
    {
        return $this->handle($card);
    }

    /**
     * Update or store the specified resource in storage.
     * @param \App\Models\Card $card
     * @return \Illuminate\Http\Response
     */
    public function handle(Card $card = null)
    {

        if ($card) {
            $card->fill(request()->all());
            $card->save();
        } else {
            $data = request()->validate([
                'card_name' => 'required',
                'card_type' => 'required',
            ]);
            $card = Card::create($data);
        }

        return response()->json($card, 201);
    }

    /**
     * Remove the specified resource from storage.
     * @param \App\Models\Card $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        $card->delete();

        return response()->json(null, 204);
    }
}
