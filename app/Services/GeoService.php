<?php

namespace App\Services;

class GeoService
{
    public function distanceInKilometers(
        float $fromLatitude,
        float $fromLongitude,
        float $toLatitude,
        float $toLongitude
    ): float {
        $earthRadius = 6371;

        $latFrom = deg2rad($fromLatitude);
        $lonFrom = deg2rad($fromLongitude);
        $latTo = deg2rad($toLatitude);
        $lonTo = deg2rad($toLongitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
        ));

        return round($earthRadius * $angle, 3);
    }

    public function speedInKilometersPerHour(
        float $distanceInKilometers,
        int $minutes
    ): ?float {
        if ($minutes <= 0) {
            return null;
        }

        $hours = $minutes / 60;

        return round($distanceInKilometers / $hours, 2);
    }
}
