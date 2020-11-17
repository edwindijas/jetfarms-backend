<?php

namespace App\Http\Controllers;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class Images extends Controller
{
    function upload (Request $request) {
        $image = new Image();
        $image->uuid = (String) Str::orderedUuid();
        $image->mime = $request->input("mime");
        $file = $request->file('image');
        $binary = addslashes(file_get_contents($file->getRealPath()));
        $file->storeAs('public/images/', $image -> uuid);
        
        $image->save();
        return ["image" => $image];
    }
}
