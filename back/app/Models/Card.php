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

        $query->when($filters['spell'] ?? false, function ($query, $spell) use ($filters) {
            $query->where(function ($query) use ($spell, $filters) {
                $query->whereIn('race', $spell);
            });
        });

        $query->when($filters['trap'] ?? false, function ($query, $trap) use ($filters) {
            $query->where(function ($query) use ($trap, $filters) {
                $query->whereIn('race', $trap);
            });
        });

        $query->when($filters['atkMax'] ?? false, function ($query, $atkMax) {
            $query->where('atk', '<=', (int) $atkMax);
        });

        $query->when($filters['atkMin'] ?? false, function ($query, $atkMin) {
            $query->where('atk', '>=', (int) $atkMin);
        });

        $query->when($filters['defMax'] ?? false, function ($query, $defMax) {
            $query->where('def', '<=', $defMax);
        });

        $query->when($filters['defMin'] ?? false, function ($query, $defMin) {
            $query->where('def', '>=', $defMin);
        });

        $query->when($filters['levelMax'] ?? false, function ($query, $levelMax) {
            $query->where('level', '<=', (int) $levelMax);
        });

        $query->when($filters['levelMin'] ?? false, function ($query, $levelMin) {
            $query->where('level', '>=', (int) $levelMin);
        });

        $query->when($filters['linkvalMax'] ?? false, function ($query, $linkvalMax) {
            $query->where('linkval', '<=', $linkvalMax);
        });

        $query->when($filters['linkvalMin'] ?? false, function ($query, $linkvalMin) {
            $query->where('linkval', '>=', $linkvalMin);
        });

        $query->when($filters['scaleMax'] ?? false, function ($query, $scaleMax) {
            $query->where('scale', '<=', $scaleMax);
        });

        $query->when($filters['scaleMin'] ?? false, function ($query, $scaleMin) {
            $query->where('scale', '>=', $scaleMin);
        });
        // dd($query->toSql());
    }

}
