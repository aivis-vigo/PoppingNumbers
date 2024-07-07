<?php declare(strict_types=1);

namespace App\Utils;

namespace App\Utils;

class Colors
{
    public static function getColorForNumber(string $value): string
    {
        $numberColors = [
            '0' => 'red',
            '1' => 'green',
            '2' => 'yellow',
            '3' => 'blue',
            '4' => 'magenta',
            '5' => 'cyan',
            '6' => 'white',
            '7' => 'red',
            '8' => 'green',
            '9' => 'yellow',
        ];

        if (array_key_exists($value, $numberColors)) {
            return $numberColors[$value];
        } else {
            return 'white';
        }
    }
}
