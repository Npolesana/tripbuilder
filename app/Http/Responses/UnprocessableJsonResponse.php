<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UnprocessableJsonResponse extends JsonResponse
{
    /**
     * Constructor.
     *
     * @param  array|string $message
     * @param  string       $field
     * @param  array        $headers
     * @param  int          $options
     */
    public function __construct($message, $field, $headers = [], $options = 0)
    {
        if (is_string($message)) {
            $message = [$message];
        }
        
        if (is_string($field)) {
            $field = [$field];
        }

        $data = [
            'response_status' => [
                'message' => $message,
                'code'    => Response::HTTP_UNPROCESSABLE_ENTITY,
                'field'   => $field,
            ],
        ];
        parent::__construct($data, Response::HTTP_UNPROCESSABLE_ENTITY, $headers, $options);
    }
}
