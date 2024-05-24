<?php

namespace App\Enums;

enum CallPriority : string
{
    case LOW = 'low';
    case HIGH = 'high';

    public static function getRandomValue(): self
    {
        $values = self::cases();
        return $values[array_rand($values)];
    }
}
