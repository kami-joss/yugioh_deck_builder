<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    protected $appends = [
        'image_path',
    ];

    // Attributes

    public function getImagePathAttribute()
    {
        return asset(Storage::url($this->image->path));
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function image_small()
    {
        return $this->belongsTo(Image::class, 'image_small_id', 'id');
    }

    // SCOPES

    public function scopeFiltered($query, $filters)
    {
        $query->when($filters['name'] ?? false, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        });

        $query->when($filters['types'] ?? false, function ($query, $types) {
            $query->where(function ($query) use ($types) {
                foreach ($types as $type) {
                    $query->orWhere(function ($query) use ($type) {
                        $query->orWhere('type', 'like', '%' . $type . '%');
                    });
                    $query->orWhere(function ($query) use ($type) {
                        $query->orWhere('race', $type);
                    });
                }
            });
        });

        $query->when($filters['attributes'] ?? false, function ($query, $attributes) use ($filters) {  
            $query->where(function ($query) use ($attributes, $filters) {
                $query->whereIn('attribute', $attributes);
                if($filters['types']) {
                    array_map(function($type) use ($query) {
                        if(str_contains($type, 'spell') || str_contains($type, 'trap')) {
                            $query->orWhere('attribute', null);
                        }
                    }, $filters['types']);
                }
            });
        });

        $query->when($filters['races'] ?? false, function ($query, $races) use ($filters) {
            $query->where(function ($query) use ($races, $filters) {
                $query->whereIn('race', $races);
            });
        });


        // dd($query->toSql());
    }
}
