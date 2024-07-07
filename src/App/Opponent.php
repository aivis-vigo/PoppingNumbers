<?php declare(strict_types=1);

namespace App\App;

final class Opponent {
    public static function comeUpWithNumber(int $maxNumber): int
    {
        return rand(1, $maxNumber);
    }
}