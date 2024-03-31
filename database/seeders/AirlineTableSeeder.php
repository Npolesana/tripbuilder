<?php

namespace Database\Seeders;

use App\Models\Airline;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AirlineTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $airlineData = file_get_contents('airlines.json');

        $airlines = json_decode($airlineData);

        foreach ($airlines as $airline){


            DB::table('airlines')->insert([
                'code' => $airline->iata ?? '',
                'name' => $airline->name,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()

            ]);

        }





    }
}
