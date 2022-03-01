<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class BadRequest extends Exception
{
    /**
     * render response
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'Incorrect method used for such kind of request',
        ], 405);
    }
}
