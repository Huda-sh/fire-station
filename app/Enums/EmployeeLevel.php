<?php

namespace App\Enums;

enum EmployeeLevel: string
{
    case JUNIOR = '1';
    case SENIOR = '2';
    case MANAGER = '3';
    case DIRECTOR = '4';

    public static function getRandomValue(): self
    {
        $values = self::cases();
        return $values[array_rand($values)];
    }
}
