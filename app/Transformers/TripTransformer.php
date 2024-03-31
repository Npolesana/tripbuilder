<?php
namespace App\Transformers;
use App\Models\Flight;
use App\Models\Trip;
use League\Fractal\Resource\Collection;

class TripTransformer extends TransformerAbstract
{
    protected array $availableIncludes = ['flights'];

    /**
     * A Fractal transformer.
     *
     * @param Trip $trip
     * @return array
     */
    public function transform(Trip $trip): array
    {

        return ['id' => $trip->id,
            'departure_date' => $trip->departure_date,
            'total_price' => $trip->total_price,
        ];


    }


    public function includeFlights(Trip $trip): Collection
    {

        return $this->collection($trip->flights, new FlightTransformer());
    }
}
