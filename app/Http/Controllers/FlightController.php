<?php

namespace App\Http\Controllers;

use App\Http\Requests\TripRequest;
use App\Models\Flight;
use App\Transformers\FlightTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Spatie\Fractal\Fractal;

class FlightController extends ApiController
{
    /**
     * Retrieve a paginated list of flights.
     *
     * @param TripRequest $request
     */
    public function index(TripRequest $request)
    {
        try {
            // Retrieve flight includes
            $includes = $this->getIncludes(['departure_airport', 'arrival_airport', 'airline']);

            // Retrieve outbound and inbound flights
            $outboundFlights = $this->retrieveFlights($request, false);
            $inboundFlights = $this->retrieveFlights($request, true);

            // If one-way trip, return outbound flights only
            if ($request->input('one_way')) {
                return $this->transformAndPaginate($outboundFlights, $includes);
            }

            // Return both outbound and inbound flights
            return response()->json([
                'outbound_flights' => $this->transformAndPaginate($outboundFlights, $includes),
                'inbound_flights' => $this->transformAndPaginate($inboundFlights, $includes)
            ]);
        } catch (\Exception $e) {
            return $this->error('Server Error', 500);
        }
    }

    /**
     * Retrieve flights based on request parameters.
     *
     * @param TripRequest $request
     * @param bool $isReturn
     * @return LengthAwarePaginator
     */
    private function retrieveFlights(TripRequest $request, bool $isReturn)
    {
        // Retrieve includes
        $includes = $this->getIncludes(['departure_airport', 'arrival_airport', 'airline']);

        // Initialize query with includes
        $query = Flight::with($includes);

        // Apply filters and sorting to the query
        $query = $this->applyApiFilters($query, $request, $isReturn, true);
        $query = $this->addSortToQuery($request, $query);

        // Paginate the query
        return $query->paginate($isReturn ? $this->pagination_inbound['per_page_inbound'] : $this->pagination_outbound['per_page_outbound'], $this->fields);
    }

    /**
     * Transform and paginate the flights collection.
     *
     * @param LengthAwarePaginator $paginator
     * @param array $includes
     * @return Fractal
     */
    private function transformAndPaginate(LengthAwarePaginator $paginator, array $includes)
    {
        return fractal()
            ->collection($paginator->getCollection())
            ->transformWith(new FlightTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->parseIncludes($includes);
    }

    /**
     * Retrieve details of a single flight.
     *
     * @param int $id
     */
    public function view(int $id)
    {
        try {
            // Retrieve flight includes
            $includes = $this->getIncludes(['departure_airport', 'arrival_airport', 'airline']);

            // Retrieve flight by ID
            $flight = Flight::with($includes)->find($id);

            // If flight not found, return error
            if (!$flight) {
                return $this->error('Not Found', 404);
            }

            // Transform and return the flight
            return fractal()->item($flight)->transformWith(new FlightTransformer())->parseIncludes($includes);
        } catch (\Exception $e) {
            return $this->error('Server Error', 500);
        }
    }
}
