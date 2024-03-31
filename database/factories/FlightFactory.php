<?php

namespace Database\Factories;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlightFactory extends Factory
{

    protected $model = Flight::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = $this->faker->dateTimeBetween('+1 day', '2 years');
        $time = $this->faker->time;
        $carbonTime = Carbon::parse($time);

        return [
            'airline_id' => Airline::all()->random()->id,
            'flight_number' => 'AAA-' . $this->faker->unique()->numberBetween(100000000, 999999999),
            'departure_airport_id' => Airport::where('code', 'YVR')->first()->id,
            'arrival_airport_id' => Airport::where('code', 'YUL')->first()->id,
            'departure_date' => $date,
            'arrival_date' => $time < '15:00' ? $date : Carbon::parse($date)->addDay()->toDateString(),
            'departure_time' => $time,
            'arrival_time' => $carbonTime->addHours(8),
            'price' => $this->faker->randomFloat(2, 200, 2000),
        ];
    }
}
