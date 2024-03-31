<?php

namespace App\Transformers;


use App\Models\Flight;
use League\Fractal\Resource\Item;

class FlightTransformer extends TransformerAbstract
{
    protected array $availableIncludes = ['departure_airport', 'arrival_airport', 'airline'];

    /**
     * A Fractal transformer.
     *
     * @param Flight $flight
     * @return array
     */
    public function transform(Flight $flight)
    {

        return ['id' => $flight->id,
            'flight_number' => $flight->flight_number,
            'departure_date' => $flight->departure_date->toDateString(),
            'departure_time' => $flight->departure_time,
            'arrival_date' => $flight->arrival_date->toDateString(),
            'arrival_time' => $flight->arrival_time,
            'price' => $flight->price,

        ];

    }

    public function includeDepartureAirport(Flight $flight): Item
    {

        return $this->item($flight->departure_airport, new AirportTransformer());
    }

    public function includeArrivalAirport(Flight $flight): Item
    {

        return $this->item($flight->arrival_airport, new AirportTransformer());
    }

    public function includeAirline(Flight $flight): Item
    {

        return $this->item($flight->airline, new AirlineTransformer());
    }




}
