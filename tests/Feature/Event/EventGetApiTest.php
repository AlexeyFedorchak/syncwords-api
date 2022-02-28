<?php

namespace Tests\Feature\Event;

use Tests\Feature\SyncWordsTestCase;

class EventGetApiTest extends SyncWordsTestCase
{
    /**
     * Only authorized user can get event
     *
     * @test
     * @return void
     */
    public function assert_only_user_can_get_event()
    {
        $this->getJson(route('api.event.get', ['id' => 1]))
            ->assertStatus(401);
    }

    /**
     * Validation enabled, so correct data needs to be passed
     *
     * @test
     * @return void
     */
    public function assert_validation_enabled()
    {
        $this->actingAs($this->user())
            ->getJson(route('api.event.get', ['id' => 1]))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
            ]);
    }

    /**
     * Response should be {'message' => '...', 'data' => '...'}
     *
     * @test
     * @return void
     */
    public function assert_correct_json_structure_returned()
    {
        $user = $this->userWithEvents();
        $event = $user->events()->first();

        $this->actingAs($user)
            ->getJson(route('api.event.get', ['id' => $event->id]))
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
