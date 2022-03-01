<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use Tests\Feature\SyncWordsTestCase;

class EventDeleteApiTest extends SyncWordsTestCase
{
    /**
     * Only authorized user can get list of events
     *
     * @test
     * @return void
     */
    public function assert_only_user_can_delete_event()
    {
        $this->deleteJson(
            route('api.events.destroy', [
                'event' => Event::factory()->create()
            ])
        )->assertStatus(401);
    }

    /**
     * Response should be {'message' => '...', 'data' => '...'}
     *
     * @test
     * @return void
     */
    public function assert_correct_json_structure_returned()
    {
        $event = $this->event($user = $this->user());

        $this->actingAs($user)
            ->deleteJson(
                route('api.events.destroy', [
                    'event' => $event
                ])
            )
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
            ]);

        $this->assertNull(Event::find($event->id));
    }
}
