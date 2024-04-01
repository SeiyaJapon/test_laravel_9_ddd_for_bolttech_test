<?php

declare(strict_types=1);

namespace DDD\Infrastructure\UI\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait CommonJSONResponse
{
    /**
     * @param array $response
     *
     * @return JsonResponse
     */
    public function commonJSONResponse(array $response): JsonResponse
    {
        return response()->json(
            [
                array_key_first($response) => $response[array_key_first($response)]
            ],
            key_exists('code', $response) ? $response['code'] : Response::HTTP_UNAUTHORIZED
        );
    }
}
