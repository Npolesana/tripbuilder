<?php
namespace App\Transformers;
use App\Models\Trip;

class TripFlightTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [];

    /**
     * A Fractal transformer.
     *
     * @param Trip $trip
     * @return array
     */
    public function transform(Trip $trip): array
    {

        return ['id' => $trip->id,
            'flight_id' => $trip->flight_id,
            'trip_id' => $trip->departure_date,
            'total_price' => $trip->total_price,
            'created_at' => $trip->created_at,
            'updated_at' => $trip->updated_at,

        ];


    }
}
