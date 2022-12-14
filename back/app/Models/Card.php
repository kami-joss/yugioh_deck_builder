<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_id',
        'name',
        'type',
        'desc',
        'race',
        'archetype',
        'atk',
        'def',
        'level',
        'attribute',
        'linkval',
        'linkmarkers',
        'scale',
        'id',
        'image_id',
        'image_small_id'
    ];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
