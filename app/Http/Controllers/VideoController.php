<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function uploadVideo(Request $request)
    {
        $request->validate([
            'video' => 'required|mimetypes:video/mp4,video/x-msvideo,video/x-matroska|max:512000', // max ~500MB
        ]);

        $file = $request->file('video');

        // Unique filename or fixed one
        $filename = 'guide' . '.' . $file->getClientOriginalExtension();

        // Store in 'videos' directory inside storage/app/public
        $path = $file->storeAs('videos', $filename, 'public');

        return response()->json([
            'message' => 'Video uploaded successfully',
            'filename' => $filename,
            'path' => $path,
            'watch_url' => url('/api/watch-video/' . $filename),
            'download_url' => url('/api/download-video/' . $filename),
        ]);
    }


    public function watchVideo($filename)
    {
        $path = storage_path('app/public/videos/' . $filename);

        if (!file_exists($path)) {
            return response()->json(['message' => 'Video not found'], 404);
        }

        return response()->file($path, [
            'Content-Type' => 'video/mp4' // Change if needed
        ]);
    }

}
