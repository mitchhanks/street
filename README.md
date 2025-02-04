# Street NameParser

This is a simple PHP-based project that includes a helper function (`NameParser`) to parse names and return structured information like title, first name, initial, and last name. The helper handles different name formats and cases such as initials, and multiple names connected by "and" or "&".

## Requirements

- PHP >= 7.4
- Composer (for managing dependencies)
- PHPUnit (for running tests)

## Installation

1. Clone the repository to your local machine:

    ```bash
    git clone git@github.com:mitchhanks/street.git
    ```

2. Navigate to the project directory:

    ```bash
    cd street
    ```

3. Install the required dependencies using Composer:

    ```bash
    composer install
    ```

## Usage

### Parsing Names

You can use the `NameParser` class to parse names. Here's an example:

```php
use App\Helpers\NameParser;

$input = "Mr John Smith";
$output = NameParser::parseNames($input);

print_r($output);
 ```

### API
You can use the API end point to send it a csv file by calling: 
Method: POST
Endpoint: /api/parse-names
Content-Type: multipart/form-data; 
Key: file
Value: CSV file

### Tests

Run tests with:

php artisan test