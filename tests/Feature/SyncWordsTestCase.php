<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SyncWordsTestCase extends TestCase
{
    use RefreshDatabase;

    /**
     * refresh seeders since every time tests are running database is clearing up
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed');
    }

    /**
     * Return the user instance
     *
     * @return User
     */
    protected function user(): User
    {
        return User::factory()
            ->create([
                'password' => Hash::make('password'),
            ]);
    }

    /**
     * Return the user instance with events
     *
     * @param int $countEvents
     * @return User
     */
    protected function userWithEvents(int $countEvents = 1): User
    {
        return User::factory()
            ->hasEvents($countEvents)
            ->create([
                'password' => Hash::make('password'),
            ]);
    }

    /**
     * get bearer token for given user
     *
     * @param User $user
     * @return string
     */
    protected function getBearerToken(User $user): string
    {
        return 'Bearer ' . $user->createToken('auth_token')->plainTextToken;
    }

    /**
     * get event instance
     *
     * @param User $user
     * @return Event
     */
    protected function event(User $user): Event
    {
        return Event::factory()->create([
            'organization_id' => $user->id,
        ]);
    }
}
