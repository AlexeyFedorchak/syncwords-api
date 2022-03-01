<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventController extends Controller
{
    /**
     * get events based on particular authorized organization
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return EventResource::collection(
            Event::where('organization_id', auth()->id())
                ->get(),
        );
    }

    /**
     * Get event for organization
     *
     * @param Event $event
     * @return EventResource
     */
    public function show(Event $event): EventResource
    {
        return new EventResource($event);
    }

    /**
     * Get event for organization
     *
     * @param Event $event
     * @param UpdateEventRequest $request
     * @return EventResource
     */
    public function update(Event $event, UpdateEventRequest $request): EventResource
    {
        $event->update(
            $request->validated()
        );

        return new EventResource($event->refresh());
    }

    /**
     * Delete event
     *
     * @param Event $event
     * @return JsonResponse
     */
    public function destroy(Event $event): JsonResponse
    {
        $event->delete();

        return response()->json([
            'message' => 'Success',
        ]);
    }
}
