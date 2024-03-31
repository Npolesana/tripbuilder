<?php

namespace App\Http\Controllers;
use App\Models\Airport;
use App\Transformers\AirportTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class AirportController extends ApiController
{

    public function index(Request $request)
    {
        /** @var Builder $query */
        try
        {
            $query = Airport::orderBy('id', 'asc');
            //apply filters
            $query = $this->applyApiFilters($query, $request);

            /** @var LengthAwarePaginator $paginator */
            $paginator = $query->paginate($this->pagination['per_page'], $this->fields);

            return fractal()
                ->collection($paginator->getCollection())
                ->transformWith(new AirportTransformer())
                ->paginateWith(new IlluminatePaginatorAdapter($paginator));

        } catch (\Exception $e)
        {
            return $this->error('Server Error', 500);
        }
    }

    public function view(int $id)
    {
        try
        {
            /* @var Airport $airport */
            $airport = Airport::where('id', $id)
                ->first();

            if (!$airport)
            {
                return $this->error('Not Found', 404);
            }


            return fractal()
                ->item($airport)
                ->transformWith(new AirportTransformer());

        } catch (\Exception $e)
        {
            return $this->error('Server Error', 500);
        }
    }


}
