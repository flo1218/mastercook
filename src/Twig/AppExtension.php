<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('min_to_hour', [$this, 'minutesToHours']),
        ];
    }

    public function minutesToHours($value)
    {
        if ($value < 60 || !$value) {
            return $value;
        }

        $hours = floor($value / 60);
        $minutes = $value % 60;

        if ($minutes < 10) {
            $minutes = '0' . $minutes;
        }

        return sprintf('%sh%s', $hours, $minutes);
    }
}
