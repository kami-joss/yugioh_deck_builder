<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'set_name',
        'set_code',
        'set_rarity',
        'set_rarity_code',
        'set_price',
        'card_id',
    ];
}
