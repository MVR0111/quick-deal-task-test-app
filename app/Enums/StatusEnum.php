<?php

namespace App\Enums;

enum StatusEnum: string
{
    case ToDo = 'todo';
    case InProgress = 'in_progress';
    case Done = 'done';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
