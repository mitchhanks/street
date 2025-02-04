<?php

namespace App\Helpers;

class NameParser
{
    public static function parseNames($input) {
        // Remove all dots from the input
        $input = str_replace('.', '', $input);
        
        // Define titles to detect
        $titles = ["Mr", "Mister", "Mrs", "Miss", "Ms", "Dr", "Prof", "Sir", "Lady"];
        
        // Split words while preserving order
        $words = preg_split('/\s+/', trim($input));
    
        // Initialize people array
        $people = [];
        
        // Count separators ("and", "&") to determine the number of people
        $separators = array_filter($words, fn($word) => in_array(strtolower($word), ["and", "&"]));
        $expectedPeople = count($separators) + 1;
        
        // Initialize last name
        $lastName = "";
        
        // Remove titles and separators from the words
        $remainingWords = array_filter($words, fn($word) => !in_array(strtolower($word), array_merge($titles, ["and", "&"])));
        
        // Re-index the array after filtering
        $remainingWords = array_values($remainingWords);
        
        // Extract each person from the string
        for ($i = 0; $i < $expectedPeople; $i++) {
            $person = ["title" => "", "first_name" => "", "initial" => "", "last_name" => ""];
    
            // Check and remove titles if they exist anywhere in the string
            foreach ($words as $key => $word) {
                if (in_array($word, $titles)) {
                    $person["title"] = $word;
                    unset($words[$key]); // Remove the title from the words array
                    break;
                }
            }
    
            // Check if we already have a last name (set once for all people)
            if ($lastName === "") {
                // The last name is the final word of the string
                $person["last_name"] = array_pop($remainingWords);
                $lastName = $person["last_name"]; // Set last name for all people
            } else {
                $person["last_name"] = $lastName; // Apply the same last name to all people
            }
    
            // Extract first name or initial
            $first_name = "";
            $initial = "";
            
            // Loop through remaining words and assign first name or initial
            foreach ($remainingWords as $key => $word) {
                // Handle initials (e.g., "J.")
                if (preg_match('/^[A-Z]$/', $word) && !$first_name) { // Initial (e.g., "J")
                    $initial = $word;
                    unset($remainingWords[$key]); // Remove initial from remaining words
                    break; // Only one initial, break after assigning
                } 
                // Handle first name (e.g., "James")
                elseif (!in_array(strtolower($word), ["and", "&"]) && !in_array($word, $titles) && empty($first_name) && $word !== $person["last_name"]) {
                    $first_name = $word;
                    unset($remainingWords[$key]); // Remove first name from remaining words
                    break; // Stop after assigning the first name
                }
            }
    
            // Assign the values
            $person["first_name"] = $first_name;
            $person["initial"] = $initial;
    
            // Add the person to the people array
            $people[] = $person;
        }
    
        return $people;
    }    
}