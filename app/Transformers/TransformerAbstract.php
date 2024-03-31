<?php

namespace App\Transformers;

use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract as BaseTransformerAbstract;

abstract class TransformerAbstract extends BaseTransformerAbstract
{
    protected function item($data, $transformer, $resourceKey = 'no-data'): Item
    {
        return is_null($data) ? $this->null() : parent::Item($data, $transformer, $resourceKey);
    }

    protected function collection($data, $transformer, $resourceKey = 'no-data'): Collection
    {
        return parent::collection($data, $transformer, $resourceKey);
    }
}
