<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\GenericJsonResponse;
use App\Models\Airport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Sanctum\PersonalAccessToken;

class ApiController extends Controller
{
    /**
     * The pagination array
     * Specific case (inbound and outbound) for pagination where there are two payloads.
     *
     * @var array
     */
    protected array $pagination = ['per_page' => 50,
        'offset' => 0,
        'limit' => 1000,
        'page' => 1,];

    protected array $pagination_inbound = ['per_page_inbound' => 50,
        'offset_inbound' => 0,
        'limit_inbound' => 1000,
        'page_inbound' => 1,];

    protected array $pagination_outbound = ['per_page_outbound' => 50,
        'offset_outbound' => 0,
        'limit_outbound' => 1000,
        'page_outbound' => 1,];


    /**
     * The array of field attribute to be returned on the model
     *
     * @var array
     */
    protected array $fields = [];

    /**
     * The array of relations to be eager/lazy loaded provided from the client
     *
     * @var array
     */
    protected array $include = [];

    /**
     * The default array of relations to be eager/lazy loaded defined in the controller
     *
     * @var array
     */
    protected array $includes = [];

    /**
     * The array of filters to be used for a query
     *
     * @var array
     */
    protected array $filter = [];

    /**
     * ApiController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request, $multiplePayload = false)
    {
        $this->setPagination($request);
        $this->setFields($request);
        $this->setInclude($request);
    }

    /**
     * @param $message
     *
     * @return JsonResponse
     */
    public function notFound($message)
    {
        return $this->error($message, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param $message
     *
     * @return JsonResponse
     */
    public function invalidData($message)
    {
        return $this->error($message, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param string $message
     * @param string $field
     *
     * @return JsonResponse
     */
    public function unprocessableEntity($message, $field)
    {
        return new UnprocessableJsonResponse($message, $field);
    }

    /**
     * @param $message
     *
     * @return JsonResponse
     */
    public function unauthorized($message)
    {
        return $this->error($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * @param $message
     *
     * @return JsonResponse
     */
    public function serverIssuesTryAgain($message)
    {
        return $this->error($message, Response::HTTP_SERVICE_UNAVAILABLE);
    }

    /**
     * @param $message
     *
     * @return JsonResponse
     */
    public function internalIssues($message)
    {
        return $this->error($message, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param $message
     *
     * @return JsonResponse
     */
    public function duplicate($message)
    {
        return $this->error($message, Response::HTTP_CONFLICT);
    }

    /**
     * @param $message
     * @param $code
     *
     * @return JsonResponse
     */
    public function error($message, $code)
    {
        return new GenericJsonResponse($message, $code);
    }

    /**
     * Success response
     *
     * @return JsonResponse
     */
    public function ok()
    {
        return response()->json();
    }

    /**
     * Success response with message
     *
     * @param $message
     * @param $code
     *
     * @return JsonResponse
     */
    public function success($message, $code = Response::HTTP_OK)
    {
        return new JsonResponse($message, $code);
    }

    /**
     * Object or array returned from api to respect data structure
     *
     * @param $data
     *
     * @return JsonResponse
     */

    /**
     * Return pagination array [$perPage, $offset, $limit, $page];
     *
     * @param Request $request
     *
     * @return array
     */
    protected function getPagination(Request $request, $multiple = false)
    {
        $perPage = (int)$request->input('per_page', $this->pagination['per_page']);
        $limit = (int)$request->input('limit', $this->pagination['limit']);
        $page = (int)$request->input('page', $this->pagination['page']);
        $offset = (int)($page * $perPage) - $perPage;

        return [$perPage,
            $offset,
            $limit,
            $page];
    }

    protected function getPaginationOutbound(Request $request)
    {
        $perPage = (int)$request->input('per_page_outbound', $this->pagination_outbound['per_page_outbound']);
        $limit = (int)$request->input('limit_outbound', $this->pagination_outbound['limit_outbound']);
        $page = (int)$request->input('page_outbound', $this->pagination_outbound['page_outbound']);
        $offset = (int)($page * $perPage) - $perPage;

        return [$perPage,
            $offset,
            $limit,
            $page];
    }

    protected function getPaginationInbound(Request $request)
    {
        $perPage = (int)$request->input('per_page_inbound', $this->pagination_inbound['per_page_inbound']);
        $limit = (int)$request->input('limit_inbound', $this->pagination_inbound['limit_inbound']);
        $page = (int)$request->input('page_inbound', $this->pagination_inbound['page_inbound']);
        $offset = (int)($page * $perPage) - $perPage;

        return [$perPage,
            $offset,
            $limit,
            $page];
    }

    /**
     * Set the pagination array to be used on queries producing multiple results
     *
     * @param Request $request
     */
    private function setPagination(Request $request): void
    {
        list($perPage, $offset, $limit, $page) = $this->getPagination($request);
        $this->pagination = ['per_page' => $perPage,
            'offset' => $offset,
            'limit' => $limit,
            'page' => $page,];

        if ($request->input('per_page_inbound'))
        {
            list($perPageInbound, $offsetInbound, $limitInbound, $pageInbound) = $this->getPaginationInbound($request);
            $this->pagination_inbound = ['per_page_inbound' => $perPageInbound,
                'offset_inbound' => $offsetInbound,
                'limit_inbound' => $limitInbound,
                'page_inbound' => $pageInbound,];
        }
        if ($request->input('per_page_outbound'))
        {
            list($perPageOutbound, $offsetOutbound, $limitOutbound, $pageOutbound) = $this->getPaginationOutbound($request);
            $this->pagination_outbound = ['per_page_outbound' => $perPageOutbound,
                'offset_inbound' => $offsetOutbound,
                'limit_outbound' => $limitOutbound,
                'page_outbound' => $pageOutbound,];
        }


    }

    /**
     * Set the fields array to be used on queries producing multiple results
     *
     * @param Request $request
     */
    private function setFields(Request $request): void
    {
        $fields = ['*'];
        if ($request->has('fields'))
        {
            $fields = array_values($request->input('fields'));
            $fields = explode(',', $fields[0]);
        }

        $this->fields = $fields;
    }

    /**
     * Set the include array for eager/lazy loading
     *
     * @param Request $request
     */
    private function setInclude(Request $request): void
    {
        $include = [];
        if ($request->has('include'))
        {
            $unprocessedRequestInclude = $request->input('include');
            if (is_array($unprocessedRequestInclude))
            {
                $include = $unprocessedRequestInclude;
            } else
            {
                $include = explode(',', $unprocessedRequestInclude);
            }
        }

        $this->include = $include;
    }

    /**
     * Return the array of relationships to be included with model
     *
     * @param array $overrides
     *
     * @return array
     */
    protected function getIncludes(array $overrides = []): array
    {
        $includes = array_merge($overrides, $this->include);
        if (empty($overrides))
        {
            $includes = array_merge($this->includes, $this->include);
        }

        return $includes;
    }


    /**
     *
     * Returns a 200 response and continues the code execution.
     * Used by webhooks
     *
     */
    protected function respondOK(): void
    {
        // check if fastcgi_finish_request is callable
        if (is_callable('fastcgi_finish_request'))
        {
            /*
             * This works in Nginx but the next approach not
             */
            session_write_close();
            fastcgi_finish_request();

            return;
        }

        ignore_user_abort(true);

        ob_start();
        $serverProtocol = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL', FILTER_SANITIZE_STRING);
        header($serverProtocol . ' 200 OK');
        header('Content-Encoding: none');
        header('Content-Length: ' . ob_get_length());
        header('Connection: close');

        ob_end_flush();
        ob_flush();
        flush();
        //connection is closed and response is sent.
    }


    protected function getApiToken(string $token)
    {


        /** @var PersonalAccessToken $token */

        $token = PersonalAccessToken::findToken($token);

        if (!$token)
        {
            return null;

        }

        return $token->tokenable;

    }


    /**
     * Success response with message
     *
     * @param $message
     * @param $code
     *
     *
     */
    public function deleted($message, $code = Response::HTTP_NO_CONTENT): JsonResponse
    {
        return new JsonResponse($message, $code);
    }

    /**
     * Apply API filters to the flight query.
     *
     * @param Builder $query
     * @param Request $request
     * @param bool $isReturn
     * @param bool $isFlights
     * @return Builder
     */
    public function applyApiFilters(Builder $query, Request $request, bool $isReturn = false, bool $isFlights = false): Builder
    {
        if ($isFlights) {
            if (!$isReturn) {
                $query = $this->applyOutboundFilters($query, $request);
            } else {
                $query = $this->applyInboundFilters($query, $request);
            }
            $query->select('flights.*');
        }

        else{
            if ($request->filled('airport_code')) {
                $query->whereIn('airports.code', explode(",", $request->input('airport_code')));
            }

            if ($request->filled('airport_city')) {
                $query->whereIn('airports.city', explode(",", $request->input('airport_city')));
            }

            if ($request->input('airline_code')) {
                $query->where('airlines.code', $request->input('airline_code'));
            }

        }

        return $query;
    }

    /**
     * Apply outbound flight filters to the query.
     *
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    private function applyOutboundFilters(Builder $query, Request $request): Builder
    {
        $query->join('airports AS df', 'df.id', 'departure_airport_id')
            ->join('airports AS at', 'at.id', 'arrival_airport_id')
            ->where('flights.departure_date', $request->input('departure_date'));

        if ($request->filled('airport_code')) {
            $query->whereIn('airports.code', explode(",", $request->input('airport_code')));
        }

        if ($request->filled('airport_city')) {
            $query->whereIn('airports.city', explode(",", $request->input('airport_city')));
        }

        if ($request->filled('allow_nearby_airports')) {
            $query = $this->applyNearbyAirportsFilters($query, $request, 'df', 'at');
        } else {
            $query->where('df.code', $request->input('departure_from'))
                ->where('at.code', $request->input('arrival_to'));
        }

        if ($request->filled('airline_code')) {
            $query->join('airlines AS airline_dep', 'airline_dep.id', 'flights.airline_id')
                ->where('airline_dep.code', $request->input('airline_code'));
        }

        return $query;
    }

    /**
     * Apply inbound flight filters to the query.
     *
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    private function applyInboundFilters(Builder $query, Request $request): Builder
    {
        $query->join('airports AS return_airport_departure', 'return_airport_departure.id', 'departure_airport_id')
            ->join('airports AS return_airport_arrival', 'return_airport_arrival.id', 'arrival_airport_id')
            ->where('departure_date', $request->input('return_date'));


        if ($request->filled('allow_nearby_airports')) {
            $query = $this->applyNearbyAirportsFilters($query, $request, 'return_airport_departure', 'return_airport_arrival', true);
        } else {
            $query->where('return_airport_arrival.code', $request->input('departure_from'))
                ->where('return_airport_departure.code', $request->input('arrival_to'));
        }

        if ($request->filled('airline_code')) {
            $query->join('airlines AS airline_return', 'airline_return.id', 'flights.airline_id')
                ->where('airline_return.code', $request->input('airline_code'));
        }

        return $query;
    }

    /**
     * Apply filters for nearby airports.
     *
     * @param Builder $query
     * @param Request $request
     * @param string $departureAirportAlias
     * @param string $arrivalAirportAlias
     * @return Builder
     */
    private function applyNearbyAirportsFilters(Builder $query, Request $request, string $departureAirportAlias, string $arrivalAirportAlias, bool $inbound = false): Builder
    {
        $departureAirport = Airport::where('code', $inbound ? $request->input('arrival_to') : $request->input('departure_from'))->first();
        $arrivalAirport = Airport::where('code', $inbound ? $request->input('departure_from') : $request->input('arrival_to'))->first();

        $query->whereBetween($departureAirportAlias . '.latitude', [$departureAirport->latitude - 1, $departureAirport->latitude + 1])
            ->whereBetween($departureAirportAlias . '.longitude', [$departureAirport->longitude - 1, $departureAirport->longitude + 1])
            ->whereBetween($arrivalAirportAlias . '.latitude', [$arrivalAirport->latitude - 1, $arrivalAirport->latitude + 1])
            ->whereBetween($arrivalAirportAlias . '.longitude', [$arrivalAirport->longitude - 1, $arrivalAirport->longitude + 1]);

        return $query;
    }

    /**
     * @throws \Exception
     */
    public function addSortToQuery(Request $request, Builder $query): Builder
    {
        if ($request->filled("sort"))
        {
            $property = $request->input('sort');
            //defaulting to
            $direction = 'ASC';
            $allowed = ['price',
                'arrival_time'];

            if (in_array($property, $allowed))
            {
                $query->orderBy($property, $direction);
            } else
            {
                throw new \Exception("Not allowed to sort {$property}");
            }
        }
        return $query;
    }

}
