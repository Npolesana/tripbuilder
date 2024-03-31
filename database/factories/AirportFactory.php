<?php

namespace Database\Factories;

use App\Models\Airport;
use Illuminate\Database\Eloquent\Factories\Factory;

class AirportFactory extends Factory
{

    protected $model = Airport::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */

    public function definition()
    {
        $city = $this->faker->unique()->city;
        return [
            'code' => strtoupper($this->faker->unique()->lexify('???')),
            'city' => $city,
            'city_code' => strtoupper($this->faker->unique()->lexify('??')),
            'country_code' => $this->faker->countryCode(),
            'region_code' => strtoupper($this->faker->lexify('??')),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'timezone' => $this->faker->timezone(),
        ];
    }
}
