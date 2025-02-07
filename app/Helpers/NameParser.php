<?php

namespace App\Helpers;

use App\Models\Person;
use Illuminate\Support\Collection;

class NameParser
{
    public static function parseNames(string $input): array
    {
        $input = str_replace('.', '', $input);
        $titles = ["Mr", "Mister", "Mrs", "Miss", "Ms", "Dr", "Prof", "Sir", "Lady"];
        $words = preg_split('/\s+/', trim($input));

        $separators = array_filter($words, fn($word) => in_array(strtolower($word), ["and", "&"]));
        $expectedPeople = count($separators) + 1;
        $lastName = "";
        $remainingWords = collect($words)->reject(fn($word) => in_array(strtolower($word), array_merge($titles, ["and", "&"])));

        return Collection::times($expectedPeople, function () use (&$words, &$remainingWords, &$lastName, $titles) {
            $title = "";
            $firstName = "";
            $initial = "";

            // Extract title if present
            foreach ($words as $key => $word) {
                if (in_array($word, $titles)) {
                    $title = $word;
                    unset($words[$key]);
                    break;
                }
            }

            // Set last name (once, shared across multiple people if applicable)
            if ($lastName === "") {
                $lastName = $remainingWords->pop();
            }

            // Extract first name or initial
            foreach ($remainingWords as $key => $word) {
                if (
                    preg_match('/^[A-Z]$/', $word) && // Ensure it's a single uppercase letter
                    empty($firstName) &&             // No first name assigned yet
                    !in_array($word, $titles)        // Make sure it's not a title
                ) {
                    $initial = $word;
                    $remainingWords->forget($key);
                    break;
                } elseif (
                    !empty($word) &&
                    !in_array(strtolower($word), ["and", "&"]) &&
                    !in_array($word, $titles) && // Ensure first name isn't a title
                    $word !== $lastName
                ) {
                    $firstName = $word;
                    $remainingWords->forget($key);
                    break;
                }
            }

            return new Person($title, $lastName, $firstName, $initial);
        })->map(fn($person) => $person->toArray())->all();
    }
}
