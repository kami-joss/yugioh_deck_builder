<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deck extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'cards',
        'image_id',
        'public',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function cards()
    {
        return $this->belongsToMany(Card::class, 'decks_cards');
    }

    public function scopePublic($query)
    {
        return $query->where('public', true);
    }

    public function scopeFiltered($query, $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) use ($filters) {
            switch ($filters['searchBy']) {
                case 'Author':
                    $query->whereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', $search . '%');
                    });
                    break;
                case 'Name':
                    $query->where('name', 'like', $search . '%');
                    break;
            }
        });

        $query->when($filters['illegal'] == 'false', function ($query) {
            $query->where('illegal', false);
        });

        return $query;
    }
}
