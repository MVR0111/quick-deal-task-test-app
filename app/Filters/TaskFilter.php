<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class TaskFilter
{
    public static function apply(Builder $query, array $filters): void
    {
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['date'])) {
            $query->whereDate('created_at', $filters['date']);
        }
    }
}
