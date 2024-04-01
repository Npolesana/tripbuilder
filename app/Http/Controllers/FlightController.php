<?php

namespace App\Http\Controllers;
use App\Http\Requests\TripRequest;
use App\Models\Flight;
use App\Transformers\FlightTransformer;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class FlightController extends ApiController
{

    public function index(TripRequest $request)
    {
        /** @var Builder $query */
        try
        {
                $includes = $this->getIncludes(['departure_airport',
                    'arrival_airport',
                    'airline']);
                $query = Flight::with($includes);

                $returnQuery = Flight::with($includes);

                //apply filters
                $query = $this->applyApiFilters($query, $request);
                $returnQuery = $this->applyApiFilters($returnQuery, $request, true);

                $query = $this->addSortToQuery($request, $query);
                $returnQuery = $this->addSortToQuery($request, $returnQuery);

                /** @var LengthAwarePaginator $paginator */
                $paginator = $query->paginate($this->pagination_outbound['per_page_outbound'], $this->fields);
                $paginator1 = $returnQuery->paginate($this->pagination_inbound['per_page_inbound'], $this->fields);

                if ($request->input('one_way')){
                    return fractal()
                        ->collection($paginator->getCollection())
                        ->transformWith(new FlightTransformer())
                        ->paginateWith(new IlluminatePaginatorAdapter($paginator))
                        ->parseIncludes($includes);
                }

            return response()->json([
                'outbound_flights' => fractal()
                    ->collection($paginator->getCollection())
                    ->transformWith(new FlightTransformer())
                    ->paginateWith(new IlluminatePaginatorAdapter($paginator))
                    ->parseIncludes($includes),
                'inbound_flights' => fractal()
                    ->collection($paginator1->getCollection())
                    ->transformWith(new FlightTransformer())
                    ->paginateWith(new IlluminatePaginatorAdapter($paginator1))
                    ->parseIncludes($includes)
            ]);

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

            $includes = $this->getIncludes(['departure_airport',
                'arrival_airport',
                'airline']);

            /* @var Flight $flight */

            $flight = Flight::with($includes)
                ->where('id', $id)
                ->first();

            if (!$flight)
            {
                return $this->error('Not Found', 404);
            }


            return fractal()
                ->item($flight)
                ->transformWith(new FlightTransformer())
                ->parseIncludes($includes);

        } catch (\Exception $e)
        {
            return $this->error('Server Error', 500);
        }
    }


}
