<?php

namespace App\Transformers;


use App\Models\Airline;

class AirlineTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
    ];

    /**
     * A Fractal transformer.
     *
     * @param Airline $airline
     *
     * @return array
     */
    public function transform(Airline $airline)
    {
        return [
            'id' => $airline->id,
            'code' => $airline->code,
            'name' => $airline->name,

        ];
    }








}
