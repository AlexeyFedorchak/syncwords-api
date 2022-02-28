<?php

namespace Tests\Feature\Event;

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
        $this->deleteJson(route('api.event.delete', ['id' => 1]))
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
            ->deleteJson(route('api.event.delete', ['id' => 1]))
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
            ->deleteJson(route('api.event.delete', ['id' => $event->id]))
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
            ]);
    }
}
