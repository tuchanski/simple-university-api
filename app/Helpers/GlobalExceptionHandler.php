<?php

namespace App\Helpers;

class GlobalExceptionHandler
{
    public function __construct()
    {
        //
    }

    public static function retrieveResponse(\Exception $exception) : \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
    {
        return response(['message' => $exception->getMessage()], $exception->getCode());
    }


}
