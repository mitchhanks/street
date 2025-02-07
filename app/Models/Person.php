<?php

namespace App\Models;

class Person
{
    public function __construct(
        public string $title,
        public string $lastName,
        public string $firstName = '',
        public string $initial = ''
    ) {
        if (empty($this->title)) {
            throw new \InvalidArgumentException("Title is required.");
        }

        if (empty($this->lastName)) {
            throw new \InvalidArgumentException("Last name is required.");
        }
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'first_name' => $this->firstName,
            'initial' => $this->initial,
            'last_name' => $this->lastName,
        ];
    }
}
