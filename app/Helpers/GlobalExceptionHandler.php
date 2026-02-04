<?php

namespace App\Helpers;

use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class GlobalExceptionHandler
{
    public function __construct()
    {
        //
    }

    public static function retrieveResponse(\Exception $exception) : ResponseFactory|Response
    {
        if ($exception instanceof ValidationException) {
            return response([
                'errors' => $exception->errors()
            ], 422);
        }

        return response([
            'error' =>
                [
                    'message' => $exception->getMessage(),
                ]
        ], $exception->getCode());
    }

}
