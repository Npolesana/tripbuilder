<?php

namespace App\Serializers;

use League\Fractal\Serializer\ArraySerializer;

class NoDataInRelationSerializer extends ArraySerializer
{
    public function collection($resourceKey, array $data): array
    {
        if ($resourceKey === 'no-data') {
            return $data;
        }

        return [$resourceKey ?: 'data' => $data];
    }

    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function item($resourceKey, array $data): array
    {
        if ($resourceKey === 'no-data') {
            return $data;
        }

        return [$resourceKey ?: 'data' => $data];
    }
}
