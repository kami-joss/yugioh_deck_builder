<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function upload($image)
    {
        try {
            $original_filename = $image->getClientOriginalName();

            switch ($image->type) {
                case 'avatar':
                    $fileName = 'image_' . time() . '.' . $original_filename;
                    Storage::putFileAs('public' . DIRECTORY_SEPARATOR . 'avatars', $image, $fileName);
                    $absolute_path = url(Storage::url('public' . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR . $fileName));
                    $path = 'avatars' . DIRECTORY_SEPARATOR . $fileName;
                    break;
            }

            $image = Image::create([
                'path' => $path,
                'name' => $fileName,
                'extension' => $image->extension(),
                'type' => $image->type,
                'size' => $image->getSize(),
            ]);

            return $image->id;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
