<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\NameParser;
use Illuminate\Support\Collection;

class NameParsingController extends Controller
{
    public function parseFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);
        
        // Get the uploaded file
        $file = $request->file('file');
        
        // Get the contents of the file
        $data = file_get_contents($file);
        
        // Clean up the data:
        // - Replace newlines with commas (so each line becomes a comma-separated entry)
        // - Remove extra spaces around commas and trim the string
        $data = preg_replace('/[\r\n]+/', ',', $data);
        $data = preg_replace('/\s*,\s*/', ',', $data);
        $data = trim($data);
        
        // Process the names using Laravel Collections
        $parsedNames = collect(explode(',', $data))
            ->map(fn($name) => trim($name))
            ->filter(fn($name) => !empty($name) && strtolower($name) !== 'homeowner')
            ->flatMap(function ($name) {
                // Handle "Mr and Mrs" (or similar) cases: if the name contains both titles together,
                // parse them as a couple. Otherwise, if it contains "and", split into parts.
                if (preg_match('/\b(Mr|Mrs)\s+and\s+(Mr|Mrs)\b/', $name)) {
                    return NameParser::parseNames($name);
                } elseif (strpos($name, 'and') !== false) {
                    return collect(preg_split('/\s+and\s+/', $name))
                        ->flatMap(fn($splitName) => NameParser::parseNames($splitName));
                } else {
                    return NameParser::parseNames($name);
                }
            })
            ->values()
            ->all();
        
        return response()->json([
            'success' => true,
            'names' => $parsedNames
        ]);
    }
}
