<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class BadRequest extends Exception
{
    /**
     * render response | due security reasons error has very general message
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'Bad request',
        ], 400);
    }
}
