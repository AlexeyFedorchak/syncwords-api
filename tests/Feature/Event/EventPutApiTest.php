<?php

namespace Tests\Feature\Event;

use Illuminate\Support\Str;
use Tests\Feature\SyncWordsTestCase;

class EventPutApiTest extends SyncWordsTestCase
{
    /**
     * Only authorized user can update the event
     *
     * @test
     * @return void
     */
    public function assert_only_user_can_put_events()
    {
        $this->putJson((route('api.event.put', ['id' => 1])))
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
            ->putJson(route('api.event.put', ['id' => 1]))
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
            ]);
    }

    /**
     * The duration between the event_start_date and event_end_date cannot exceed 12 hours.
     *
     * @test
     * @return void
     */
    public function assert_duration_between_end_date_and_start_date_cannot_exceed_12_hours()
    {
        $user = $this->userWithEvents();
        $event = $user->events()->first();

        $data = [
            'id' => $event->id,
            'event_title' => Str::random(10),
            'event_start_date' => now(),
            'event_end_date' => now()->addHours(13),
        ];

        $this->actingAs($this->user())
            ->putJson(route('api.event.put', ['id' => $event->id]), $data)
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
            ]);
    }

    /**
     * Response should be {'message' => '...'}
     *
     * @test
     * @return void
     */
    public function assert_correct_json_structure_returned()
    {
        $user = $this->userWithEvents();
        $event = $user->events()->first();

        $data = [
            'id' => $event->id,
            'event_title' => Str::random(10),
            'event_start_date' => now(),
            'event_end_date' => now()->addHours(5),
        ];

        $this->actingAs($user)
            ->putJson(route('api.event.put', ['id' => $event->id]), $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ]);
    }
}
