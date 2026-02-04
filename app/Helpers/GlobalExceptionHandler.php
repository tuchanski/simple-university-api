<?php

namespace App\Helpers;

use Illuminate\Validation\ValidationException;

class GlobalExceptionHandler
{
    public function __construct()
    {
        //
    }

    public static function retrieveResponse(\Exception $exception) : \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
    {
        return response([
            'error' =>
                [
                    'message' => $exception->getMessage(),
                ]
        ], $exception->getCode());
    }

    public static function retrieveValidationExceptionResponse(ValidationException $exception) : \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
    {
        return response(['errors' => $exception->errors()], 422);
    }


}
