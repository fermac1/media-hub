<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PDFController extends Controller
{
    //upload PDF
    public function uploadPDF(Request $request)
    {
        $request->validate([
            'pdf' => 'required|mimes:pdf|max:10240', //10MB
        ]);

        // $path = $request->file('pdf')->store('public/pdf');

        $file = $request->file('pdf');

        $filename = 'guide.pdf';

        // $originalName = $file->getClientOriginalName(); // e.g., contract.pdf

        $path = $file->storeAs('pdf', $filename, 'public');

        return response()->json([
            'message' => 'PDF uploaded successfully',
            'filename' => $filename,
            'path' => $path,
            'url' => url('/api/view-pdf/' . $filename),
        ]);

        // return response()->json(['message' => 'PDF uploaded successfully', 'path' => $path, 'url' => asset('storage/' . basename($path))], 200);
    }

    // display pdf
    public function displayPDF($filename)
    {
        $path = storage_path('app/public/pdf/' . $filename);
        Log::info('file: ' . $path);
        if (!file_exists($path)) {
            return response()->json(['message' => 'File not found'], 404);
        }
        // return response()->file($path, [
        //     'Content-Type' => 'application/pdf',
        //     'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        //     'Pragma' => 'no-cache',
        //     'Expires' => '0',
        // ]);

        // stream the file to prevent caching
        return response()->stream(function () use ($path) {
            readfile($path);
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'Content-Disposition' => 'inline; filename="guide.pdf"',
        ]);
    }
}
