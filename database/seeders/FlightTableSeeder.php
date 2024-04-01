<?php

namespace Database\Seeders;

use App\Models\Flight;
use Database\Factories\ReturnFlightFactory;
use Illuminate\Database\Seeder;

class FlightTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Flight::factory()->count(500)->create();

    }
}
