<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'created_at',
        'updated_at',
        'illegal',
    ];

    protected $appends = [
        'image_path',
    ];

    public function getImagePathAttribute()
    {
        return asset(Storage::url($this->image?->path));
    }


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
        return $this->belongsToMany(Card::class, 'decks_cards')->orderBy('type');
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

        if (isset($filters['illegal'])) {
            if ($filters['illegal'] == 'false') {
                $query->where('illegal', $filters['illegal']);
            }
        }

        return $query;
    }
}
