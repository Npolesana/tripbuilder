<?php

namespace App\Http\Controllers;
use App\Http\Requests\TripRequest;
use App\Models\Flight;
use App\Models\Trip;
use App\Models\TripFlight;
use App\Transformers\TripTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class TripController extends ApiController
{

    public function create(TripRequest $request)
    {
        /** @var Builder $query */
        try
        {
            $flights = Flight::where('id', $request->input('departure_flight_id'))
                ->get();

            $includes = $this->getIncludes(['flights.departure_airport',
                'flights.arrival_airport',
                'flights.airline']);

            if ($flights->count() == 0)
            {
                return $this->error('Departing flight not found', 404);
            }

            if ($request->input('multi_city_trip_1'))
            {
                $multiCityTrip1 = Flight::where('id', $request->input('multi_city_trip_1'))
                    ->first();
                if (!$multiCityTrip1)
                {
                    return $this->error('Multi city flight 1 not found', 404);
                }
                $flights->push($multiCityTrip1);
            }
            if ($request->input('multi_city_trip_2'))
            {
                $multiCityTrip2 = Flight::where('id', $request->input('multi_city_trip_2'))
                    ->first();
                if (!$multiCityTrip2)
                {
                    return $this->error('Multi city flight 2 not found', 404);
                }
                $flights->push($multiCityTrip2);
            }
            if ($request->input('multi_city_trip_3'))
            {
                $multiCityTrip3 = Flight::where('id', $request->input('multi_city_trip_3'))
                    ->first();
                if (!$multiCityTrip3)
                {
                    return $this->error('Multi city flight 3 not found', 404);
                }
                $flights->push($multiCityTrip3);
            }

            if ($request->input('return_flight_id'))
            {
                /** @var Flight $returnFlight */

                $returnFlight = Flight::where('id', $request->input('return_flight_id'))
                    ->first();
                if (!$returnFlight )
                {
                    return $this->error('Return flight not found', 404);
                }
                if ($returnFlight->departure_time <=  $flights[0]->arrival_time ){
                    return $this->error('Return flight cannot be before the departure flight', 422);
                }
                if ($returnFlight->departure_airport_id ===  $flights[0]->departure_airport_id ){
                    return $this->error('Return flights need to have different airports.', 422);
                }
                if ($returnFlight->arrival_airport_id !==  $flights[0]->departure_airport_id && sizeof($flights) == 1 ){
                    return $this->error('Return flight needs to have the same starting destination unless multitrip', 422);
                }
                $flights->push($returnFlight);
            }

            $trip = new Trip();

            $trip->total_price = $flights->sum('price');
            $trip->departure_date = $flights->sortBy('departure_date')
                ->first()->departure_date;

            $trip->save();


            $flights->each(function ($flight, $key) use ($trip)
            {
                //create the relationship
                TripFlight::create(['trip_id' => $trip->id,
                    'flight_id' => $flight->id]);
            });

            /** @var LengthAwarePaginator $paginator */

            return fractal()
                ->item($trip)
                ->transformWith(new TripTransformer())
                ->parseIncludes($includes);

        } catch (\Exception $e)
        {
            return $this->error('Server Error', 500);
        }
    }

    public function view(int $id)
    {
        /** @var Builder $query */
        try
        {

            $includes = $this->getIncludes(['flights.departure_airport',
                'flights.arrival_airport',
                'flights.airline']);

            /* @var Trip $trip */

            $trip = Trip::with($includes)
                ->where('id', $id)
                ->first();

            if (!$trip)
            {
                return $this->error('Not Found', 404);
            }

            return fractal()
                ->item($trip)
                ->transformWith(new TripTransformer())
                ->parseIncludes($includes);

        } catch (\Exception $e)
        {
            return $this->error('Server Error', 500);
        }
    }

    public function index(Request $request)
    {
        /** @var Builder $query */
        try
        {

            $includes = $this->getIncludes(['flights.departure_airport',
                'flights.arrival_airport',
                'flights.airline']);

            $query = Trip::with($includes);
            /** @var LengthAwarePaginator $paginator */
            $paginator = $query->paginate($this->pagination['per_page'], $this->fields);

            return fractal()
                ->collection($paginator->getCollection())
                ->transformWith(new TripTransformer())
                ->paginateWith(new IlluminatePaginatorAdapter($paginator))
                ->parseIncludes($includes);


        } catch (\Exception $e)
        {
            return $this->error('Server Error', 500);
        }
    }


}
