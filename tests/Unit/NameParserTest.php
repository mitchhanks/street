<?php

use PHPUnit\Framework\TestCase;
use App\Helpers\NameParser;

class NameParserTest extends TestCase
{
    public function testParseNames()
    {
        $input = "Mr John Smith";
        $output = NameParser::parseNames($input);
        $this->assertEquals([[
            "title" => "Mr",
            "first_name" => "John",
            "initial" => "",
            "last_name" => "Smith"
        ]], $output);

        $input = "Mr J. Hunt";
        $output = NameParser::parseNames($input);
        $this->assertEquals([[
            "title" => "Mr",
            "first_name" => "",
            "initial" => "J",
            "last_name" => "Hunt"
        ]], $output);

        $input = "Mrs Jane Doe";
        $output = NameParser::parseNames($input);
        $this->assertEquals([[
            "title" => "Mrs",
            "first_name" => "Jane",
            "initial" => "",
            "last_name" => "Doe"
        ]], $output);

        $input = "Mrs Faye Hughes-Eastwood";
        $output = NameParser::parseNames($input);
        $this->assertEquals([[
            "title" => "Mrs",
            "first_name" => "Faye",
            "initial" => "",
            "last_name" => "Hughes-Eastwood"
        ]], $output);

        $input = "Dr P Gunn";
        $output = NameParser::parseNames($input);
        $this->assertEquals([[
            "title" => "Dr",
            "first_name" => "",
            "initial" => "P",
            "last_name" => "Gunn"
        ]], $output);

        $input = "Mr Smith";
        $output = NameParser::parseNames($input);
        $this->assertEquals([[
            "title" => "Mr",
            "first_name" => "",
            "initial" => "",
            "last_name" => "Smith"
        ]], $output);

        $input = "Mr J. Hunt and Mrs Jane Hunt";
        $output = NameParser::parseNames($input);
        $this->assertEquals([
            [
                "title" => "Mr",
                "first_name" => "",
                "initial" => "J",
                "last_name" => "Hunt"
            ],
            [
                "title" => "Mrs",
                "first_name" => "Jane",
                "initial" => "",
                "last_name" => "Hunt"
            ]
        ], $output);
    }
}
