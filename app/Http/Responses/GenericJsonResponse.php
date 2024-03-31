<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class GenericJsonResponse extends JsonResponse
{
    /**
     * Constructor.
     *
     * @param  array|string $message
     * @param  int          $status
     * @param  array        $headers
     * @param  int          $options
     */
    public function __construct($message, $status = 200, $headers = [], $options = 0)
    {
        if (is_string($message)) {
            $message = [$message];
        }

        $data = [
            'response_status' => [
                'message' => $message,
                'code'    => $status,
            ],
        ];
        parent::__construct($data, $status, $headers, $options);
    }
}
