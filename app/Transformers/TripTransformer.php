<?php
namespace App\Transformers;
use App\Models\Flight;
use App\Models\Trip;
use Carbon\Carbon;
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
            'departure_date' => Carbon::parse($trip->departure_date)->toDateString(),
            'total_price' => round($trip->total_price, 2)
        ];


    }


    public function includeFlights(Trip $trip): Collection
    {

        return $this->collection($trip->flights, new FlightTransformer());
    }
}
