<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImagesController extends Controller
{
    //
    public function upload(Request $request)
    {
        $request->validate([
            'file_path' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'type' => 'required'
        ]);

        $original_filename = $request->file_path->getClientOriginalName();

        try {
            switch ($request->type) {
                case 'avatar':
                    $fileName = 'image_' . time() . '.' . $original_filename;
                    Storage::putFileAs('public' . DIRECTORY_SEPARATOR . 'avatars', $request->file_path, $fileName);
                    $absolute_path = url(Storage::url('public' . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR . $fileName));
                    $path = 'avatars' . DIRECTORY_SEPARATOR . $fileName;
                    break;
            }

            $image = Image::create([
                'path' => $path,
                'name' => $fileName,
                'extension' => $request->file_path->extension(),
                'type' => $request->type,
                'size' => $request->file_path->getSize(),
            ]);

            return response()->json([
                'message' => 'File uploaded successfully',
                'image' => $image
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
