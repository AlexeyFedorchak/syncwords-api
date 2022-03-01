<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Tests\Feature\SyncWordsTestCase;

class EventUpdateApiTest extends SyncWordsTestCase
{
    /**
     * assert guest cannot update event
     *
     * @test
     * @return void
     */
    public function assert_guest_cannot_update_event()
    {
        $this->patchJson(
            route('api.events.update', [
                'event' => Event::factory()->create()
            ])
        )->assertStatus(401);
    }

    /**
     * Validation enabled, so correct data needs to be passed
     *
     * @test
     * @return void
     */
    public function assert_validation_enabled_on_put()
    {
        $this->actingAs($this->user())
            ->putJson(
                route('api.events.update', [
                    'event' => Event::factory()->create()
                ])
            )
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
            ]);
    }

    /**
     * The duration between the event_start_date and event_end_date cannot exceed 12 hours. | patch
     *
     * @test
     * @return void
     */
    public function assert_duration_between_end_date_and_start_date_cannot_exceed_12_hours_patch()
    {
        $this->actingAs($this->userWithEvents())
            ->patchJson(
                route('api.events.update', [
                    'event' => Event::factory()->create()
                ]), [
                    'event_end_date' => now(),
                    'event_start_date' => now()->subHours(13),
                ]
            )
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
            ]);
    }

    /**
     * The duration between the event_start_date and event_end_date cannot exceed 12 hours. | put
     *
     * @test
     * @return void
     */
    public function assert_duration_between_end_date_and_start_date_cannot_exceed_12_hours_put()
    {
        $this->actingAs($this->userWithEvents())
            ->patchJson(
                route('api.events.update', [
                    'event' => Event::factory()->create()
                ]), [
                    'event_title' => 'Test',
                    'event_end_date' => now(),
                    'event_start_date' => now()->subHours(13),
                ]
            )
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
    public function assert_correct_json_structure_returned_on_patch()
    {
        $event = Event::factory()->create();

        $this->actingAs($this->userWithEvents())
            ->patchJson(
                route('api.events.update', [
                    'event' => $event
                ]), [
                    'event_title' => 'Test',
                ]
            )
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ]);

        $this->assertEquals(
            'Test',
            $event->refresh()->event_title,
        );
    }

    /**
     * Response should be {'message' => '...'}
     *
     * @test
     * @return void
     */
    public function assert_correct_json_structure_returned_on_put()
    {
        $event = Event::factory()->create();

        $this->actingAs($this->userWithEvents())
            ->putJson(
                route('api.events.update', [
                    'event' => $event
                ]), [
                    'event_title' => 'Test',
                    'event_end_date' => now()->startOfDay(),
                    'event_start_date' => now()->startOfDay()->subHours(10),
                ]
            )
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ]);

        $this->assertEquals(
            'Test',
            $event->refresh()->event_title,
        );

        $this->assertEquals(
            now()->startOfDay(),
            $event->refresh()->event_end_date,
        );

        $this->assertEquals(
            now()->startOfDay()->subHours(10),
            $event->refresh()->event_start_date,
        );
    }
}
