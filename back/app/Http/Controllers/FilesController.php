<?php

namespace App\Http\Controllers;

use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

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
                    Storage::putFileAs('public/avatars', $request->file_path, $fileName);
                    $paths[] = url(Storage::url('public/avatars/' . $fileName));
                    break;
            }
            return response()->json([
                'message' => 'File uploaded successfully',
                'data' => $paths
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
