<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\NameParser; // Import the helper

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
        // - Replace newlines with commas (to treat each line as an entry)
        // - Remove extra spaces and unnecessary commas
        $data = preg_replace('/[\r\n]+/', ',', $data);  // Replace new lines with commas
        $data = preg_replace('/\s*,\s*/', ',', $data);  // Remove spaces around commas
        $data = trim($data); // Remove leading/trailing spaces
        
        // Split the data into individual names by commas
        $namesArray = explode(',', $data);

        // Clean the array further by trimming each name and filtering out invalid names
        $namesArray = array_map('trim', $namesArray);
        $namesArray = array_filter($namesArray, function($name) {
            return !empty($name) && strtolower($name) !== 'homeowner'; // Ignore "homeowner"
        });

        // Initialize an array to store parsed names
        $parsedNames = [];

        foreach ($namesArray as $name) {
            // Handle "Mr and Mrs" or similar cases
            if (preg_match('/\b(Mr|Mrs)\s+and\s+(Mr|Mrs)\b/', $name)) {
                // If both Mr and Mrs are present, keep them together as a couple
                $parsedNames[] = NameParser::parseNames($name);
            } elseif (strpos($name, 'and') !== false) {
                // If it contains "and", split it into multiple names
                $splitNames = preg_split('/\s+and\s+/', $name);
                foreach ($splitNames as $splitName) {
                    // Call the name parser for each part
                    $parsedNames[] = NameParser::parseNames($splitName);
                }
            } else {
                // Otherwise, parse the name normally
                $parsedNames[] = NameParser::parseNames($name);
            }
        }

        // Return parsed names as a response
        return response()->json([
            'success' => true,
            'names' => $parsedNames
        ]);
    }

    



}
