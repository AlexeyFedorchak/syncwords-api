<?php

namespace Tests\Feature\Event;

use Tests\Feature\SyncWordsTestCase;

class EventListApiTest extends SyncWordsTestCase
{
    /**
     * Only authorized user can get all events
     *
     * @test
     * @return void
     */
    public function assert_only_user_can_get_all_events()
    {
        $this->getJson(route('api.event.list'))
            ->assertStatus(401);
    }

    /**
     * Response should be {'message' => '...', 'data' => '...'}
     *
     * @test
     * @return void
     */
    public function assert_correct_json_structure_returned()
    {
        $this->actingAs($this->userWithEvents())
            ->getJson(route('api.event.list'))
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
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
