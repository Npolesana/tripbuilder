<?php

namespace App\Http\Controllers;
use App\Models\Airline;
use App\Transformers\AirlineTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class AirlineController extends ApiController
{

    public function index(Request $request)
    {
        /** @var Builder $query */
        try
        {
            $query = Airline::orderBy('id', 'asc');
            //apply filters
            $query = $this->applyApiFilters($query, $request);

            /** @var LengthAwarePaginator $paginator */
            $paginator = $query->paginate($this->pagination['per_page'], $this->fields);

            return fractal()
                ->collection($paginator->getCollection())
                ->transformWith(new AirlineTransformer())
                ->paginateWith(new IlluminatePaginatorAdapter($paginator));

        } catch (\Exception $e)
        {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function view(int $id)
    {
        try
        {
            /* @var Airline $airline */
            $airline = Airline::where('id', $id)
                ->first();

            if (!$airline)
            {
                return $this->error('Not Found', 404);
            }


            return fractal()
                ->item($airline)
                ->transformWith(new AirlineTransformer());

        } catch (\Exception $e)
        {
            return $this->error('Server Error', 500);
        }
    }


}
