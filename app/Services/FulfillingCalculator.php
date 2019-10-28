<?php

namespace App\Services;

class FulfillingCalculator
{
    public function percent(int $complete, int $remaining): array
    {
        if (0 === $complete && 0 === $remaining) {
            return [];
        }

        $total = $complete + $remaining;
        $percent = round($complete / $total * 100);

        return [
            'total' => $total,
            'complete' => $complete,
            'remaining' => $remaining,
            'percent' => $percent
        ];
    }
}