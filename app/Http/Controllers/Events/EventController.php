<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatchEventRequest;
use App\Http\Requests\PutEventRequest;
use Illuminate\Http\JsonResponse;
use function auth;
use function response;

class EventController extends Controller
{
    /**
     * Get all events for organization
     *
     * @return JsonResponse
     */
    public function getEvents(): JsonResponse
    {
        return response()->json([
            'message' => 'Success',
            'data' => auth()->user()->events()->get()
        ]);
    }

    /**
     * Get event for organization
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getEvent(int $id): JsonResponse
    {
        $event = auth()->user()->events()->where('id', $id)->first();

        if (!$event) {
            return response()->json([
                'message' => 'Invalid id',
            ], 422);
        }

        return response()->json([
            'message' => 'Success',
            'data' => auth()->user()->events()
        ]);
    }

    /**
     *
     * @param PutEventRequest $request
     * @return JsonResponse
     */
    public function put(PutEventRequest $request): JsonResponse
    {
        $event = auth()->user()->events()->where('id', $request->id)->first();

        if (!$event) {
            return response()->json([
                'message' => 'Invalid id',
            ], 422);
        }

        $event->update($request->except('id'));
        $event->save();

        return response()->json([
            'message' => 'Success',
        ]);
    }

    /**
     *
     * @param PatchEventRequest $request
     * @return JsonResponse
     */
    public function patch(PatchEventRequest $request): JsonResponse
    {
        $event = auth()->user()->events()->where('id', $request->id)->first();

        if (!$event) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }


        $event->update($request->except('id'));
        $event->save();

        return response()->json([
            'message' => 'Success',
        ]);
    }

    /**
     * Delete event
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $event = auth()->user()->events()->where('id', $id)->first();

        if (!$event) {
            return response()->json([
                'message' => 'Invalid id',
            ], 422);
        }

        $event->delete();

        return response()->json([
            'message' => 'Success',
        ]);
    }
}
