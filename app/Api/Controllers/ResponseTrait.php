<?php

namespace App\Api\Controllers;

use App\Exceptions\BaseException;

trait ResponseTrait
{
    public function success($data, $httpCode = 200)
    {
        return response()->json($data, $httpCode);
    }

    public function failure(BaseException $exception)
    {
        return response()->json([
            'success' => false,
            'message' => $exception->getMessage(),
            'details' => [
                'http_code' => $exception->getCode(),
                'internal_code' => $exception->getInternalCode(),
                'link' => $exception->getLink(),
                'instructions' => $exception->getInstructions()
            ],
        ], $exception->getCode());
    }
}
