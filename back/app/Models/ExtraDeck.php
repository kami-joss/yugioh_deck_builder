<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraDeck extends Model
{
    use HasFactory;

    protected $fillable = [
        'deck_id',
        'card_id',
        'quantity',
        'type',
    ];

    public function deck()
    {
        return $this->belongsTo(Deck::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }
}
