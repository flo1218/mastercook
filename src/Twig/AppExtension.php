<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('min_to_hour', [$this, 'minutesToHours']),
            new TwigFilter('stars_rating', [$this, 'starsRating']),
            new TwigFilter('localized_date', [$this, 'formatDate']),
        ];
    }

    public function minutesToHours($value)
    {
        if (!$value) {
            return $value;
        }

        $hours = floor($value / 60);
        $minutes = $value % 60;

        if ($minutes < 10) {
            $minutes = "0{$minutes}";
        }

        return sprintf('%sh%s', $hours, $minutes);
    }

    public function starsRating($value, $showNumeric = false, $color = 'orange')
    {
        $rawString = '<span class="ms-2 inline" style="color: ' . $color . ';">';
        for ($i = 0; $i <= 4; ++$i) {
            if ($value - $i >= 0.8) {
                $rawString .= '<i class="bi bi-star-fill"></i>';
            } elseif ($value - $i <= 0.2) {
                $rawString .= '<i class="bi bi-star"></i>';
            } else {
                $rawString .= '<i class="bi bi-star-half"></i>';
            }
        }
        if ($showNumeric) {
            $rawString .= '<span class="ps-1">' . round($value, 2) . '</span>';
        }

        $rawString .= '</span>';

        return new Markup($rawString, 'UTF-8');
    }

    public function formatDate(\DateTimeInterface $date, string $locale = 'en'): string
    {
        if ($locale !== 'en') {
            return $date->format('d/m/Y H:i');
        }

        return $date->format('m-d-Y h:i A');
    }
}
