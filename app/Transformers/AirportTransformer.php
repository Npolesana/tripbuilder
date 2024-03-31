<?php

namespace App\Transformers;


use App\Models\Airport;

class AirportTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
    ];

    /**
     * A Fractal transformer.
     *
     * @param Airport $airport
     * @return array
     */
    public function transform(Airport $airport)
    {
        return [
            'id'          => $airport->id,
            'code'        => $airport->code,
            'city'        => $airport->city,
            'city_code'   => $airport->city_code,
            'region_code' => $airport->region_code,
            'country_code'=> $airport->country_code,
            'latitude'    => $airport->latitude,
            'longitude'   => $airport->longitude,

        ];
    }








}
