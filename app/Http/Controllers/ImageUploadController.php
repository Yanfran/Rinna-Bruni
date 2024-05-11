<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;

class ImageUploadController extends Controller
{
    public function index()
    {
        return view('image-upload');
    }

    public function store(Request $request)
    {
        $image = $request->file('image');
        $filename = time() . '.' . $image->getClientOriginalExtension();
        $path = public_path('uploads/' . $filename);

        Image::make($image->getRealPath())->save($path);

        // Realiza acciones adicionales si es necesario

        return response()->json(['success' => $filename]);
    }
}
