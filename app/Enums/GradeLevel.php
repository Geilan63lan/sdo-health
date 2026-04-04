<?php

namespace App\Enums;

enum GradeLevel: string
{
    case Kinder = 'Kinder';
    case Grade1 = 'Grade 1';
    case Grade2 = 'Grade 2';
    case Grade3 = 'Grade 3';
    case Grade4 = 'Grade 4';
    case Grade5 = 'Grade 5';
    case Grade6 = 'Grade 6';
    case Grade7 = 'Grade 7';
    case Grade8 = 'Grade 8';
    case Grade9 = 'Grade 9';
    case Grade10 = 'Grade 10';
    case Grade11 = 'Grade 11';
    case Grade12 = 'Grade 12';

    public function label(): string
    {
        return $this->value;
    }

    public static function ordered(): array
    {
        return [
            'Kinder', 'Grade 1', 'Grade 2', 'Grade 3',
            'Grade 4', 'Grade 5', 'Grade 6', 'Grade 7',
            'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12',
        ];
    }

    public static function indexOf(string $grade): int|false
    {
        return array_search($grade, self::ordered(), true);
    }

    public static function next(string $grade): ?string
    {
        $index = self::indexOf($grade);

        if ($index === false) {
            return null;
        }

        $grades = self::ordered();

        if ($index + 1 >= count($grades)) {
            return null;
        }

        return $grades[$index + 1];
    }

    public static function upTo(string $grade): array
    {
        $index = self::indexOf($grade);

        if ($index === false) {
            return self::ordered();
        }

        return array_slice(self::ordered(), 0, $index + 1);
    }

    public static function asSelectOptions(): array
    {
        return array_combine(self::ordered(), self::ordered());
    }
}
