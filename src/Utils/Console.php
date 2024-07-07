<?php declare(strict_types=1);

namespace App\Utils;

class Console
{
    public static function printColored(string $text, string $color): void
    {
        $colors = [
            'black' => "\033[0;30m",
            'red' => "\033[0;31m",
            'green' => "\033[0;32m",
            'yellow' => "\033[0;33m",
            'blue' => "\033[0;34m",
            'magenta' => "\033[0;35m",
            'cyan' => "\033[0;36m",
            'white' => "\033[0;37m",
        ];

        $reset = "\033[0m";
        if (array_key_exists($color, $colors)) {
            echo $colors[$color] . $text . $reset;
        } else {
            echo $text;
        }
    }

    public static function getInput(string $message, int $min, int $max): int
    {
        do {
            $input = (int)readline($message);
            if ($input < $min || $input > $max) {
                echo "Invalid input. Please enter a number between $min and $max." . PHP_EOL;
            } else {
                return $input;
            }
        } while (true);
    }
}
