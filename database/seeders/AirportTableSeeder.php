<?php

namespace Database\Seeders;

use App\Models\Airport;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AirportTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $airportData = file_get_contents('airports.json');

        $airports = json_decode($airportData);

        foreach ($airports as $airport){


            DB::table('airports')->insert([
                'code' => $airport->iata ?? '',
                'city' => $airport->city,
                'city_code' => $airport->icao,
                'country_code' => $airport->country,
                'region_code' => strtoupper($airport->state),
                'latitude' => $airport->lat,
                'longitude' => $airport->lon,
                'timezone' => $airport->tz,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()

            ]);

        }


    }
}
