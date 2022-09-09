<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'api_id',
        'path',
        'name',
        'extension',
        'type',
        'size',
    ];

    public function card()
    {
        return $this->hasOne(Card::class);
    }
}
