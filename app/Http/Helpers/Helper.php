<?php

namespace App\Http\Helpers;

use App\Http\Resources\DiseaseRegistrationLgResource;
use App\Models\DiseaseRegistration;

class Helper
{
    public static function pag($allCount, $perPage, $page)
    {
        $pag['itemsCount'] = $allCount;
        $pag['perPage'] = (int) ($perPage ?? 10);
        $pag['pagesCount'] = ceil($pag['itemsCount'] / $pag['perPage']);
        $pag['currentPage'] = (int) ($page ?? 1);
        $pag['next'] = $pag['pagesCount'] > $pag['currentPage'] ? $pag['currentPage'] + 1 : null;
        $pag['prev'] = $pag['currentPage'] - 1 ?: null;
        $pag['skip'] = ($pag['currentPage'] - 1) * $pag['perPage'];
        return $pag;
    }







/**
 * Calculates the great-circle distance between two points, with
 * the Vincenty formula.
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m]
 * @return float Distance between points in [m] (same as earthRadius)
 */
public static function distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $unit = 'm', $earthRadius = 6371000)
  {
    // convert from degrees to radians
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    $lonDelta = $lonTo - $lonFrom;
    $a = pow(cos($latTo) * sin($lonDelta), 2) + pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
    $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);
    $angle = atan2(sqrt($a), $b);

    $distance = $angle * $earthRadius;
    if($unit == 'km') $distance /= 1000;
    return $distance;
  }

  public static function getNearInfections($lat, $lon)
  {
    $near_infections = [];
    $regs = DiseaseRegistration::active()->with('farmReport.location')->get();
    foreach ($regs as $reg ) {
        $distance = self::distance(
            $lat, $lon,
            $reg->farmReport->location->latitude, $reg->farmReport->location->longitude,
        'km');

        $status = $distance <= 3 ? 'red' : ($distance <= 15 ? 'orange' : ($distance <= 30 ? 'yellow' : false));

        if(!$status) continue;

        $near_infections[] = [
            'status' => $status,
            'distance' => $distance,
            'disease_registration' => DiseaseRegistrationLgResource::make($reg),
        ];

    }

    return $near_infections;
  }


}
