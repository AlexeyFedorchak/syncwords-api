<?php

namespace Tests\Feature\Event;

use Tests\Feature\SyncWordsTestCase;

class EventIndexApiTest extends SyncWordsTestCase
{
    /**
     * Only authorized user can get all events
     *
     * @test
     * @return void
     */
    public function assert_guest_cannot_see_events()
    {
        $this->getJson(route('api.events.index'))
            ->assertStatus(401);
    }

    /**
     * Response should be {'message' => '...', 'data' => '...'}
     *
     * @test
     * @return void
     */
    public function assert_correct_json_structure_returned_on_event_index()
    {
        $this->actingAs($this->userWithEvents())
            ->getJson(route('api.events.index'))
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'event_title',
                        'event_start_date',
                        'event_end_date',
                    ]
                ]
            ]);
    }
}
