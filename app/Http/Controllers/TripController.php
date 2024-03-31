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

            if ($request->input('return_flight_id'))
            {
                $returnFlight = Flight::where('id', $request->input('return_flight_id'))
                    ->first();
                if (!$returnFlight)
                {
                    return $this->error('Return flight not found', 404);
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
