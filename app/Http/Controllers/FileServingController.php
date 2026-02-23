<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileServingController extends Controller
{
    public function serveFile($path)
    {
        // Prevent directory traversal
        $path = str_replace(['..', '~'], '', $path);

        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }

        $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($path);

        return response()->file($fullPath);
    }
}
