<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use Tests\Feature\SyncWordsTestCase;

class EventShowApiTest extends SyncWordsTestCase
{
    /**
     * Assert guess cannot see event
     *
     * @test
     * @return void
     */
    public function assert_guest_cannot_see_event()
    {
        $this->getJson(
            route('api.events.show', [
                'event' => Event::factory()->create(),
            ])
        )->assertStatus(401);
    }

    /**
     * Assert user can see event
     *
     * @test
     * @return void
     */
    public function assert_user_can_see_event()
    {
        $this->actingAs($this->user())
            ->getJson(
                route('api.events.show', [
                    'event' => Event::factory()->create(),
                ])
            )
            ->assertStatus(200);
    }

    /**
     * Response should be {'message' => '...', 'data' => '...'}
     *
     * @test
     * @return void
     */
    public function assert_correct_json_structure_returned_on_show_event()
    {
        $this->actingAs($this->user())
            ->getJson(
                route('api.events.show', [
                    'event' => Event::factory()->create(),
                ])
            )
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'event_title',
                    'event_start_date',
                    'event_end_date',
                ]
            ]);
    }
}
