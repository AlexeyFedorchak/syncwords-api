<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\Feature\SyncWordsTestCase;
use Tests\TestCase;

class AuthLogoutApiTest extends SyncWordsTestCase
{
    /**
     * assert guest cannot logout
     *
     * @test
     * @return void
     */
    public function assert_guest_cannot_logout()
    {
        $this->postJson(route('api.auth.logout'))
            ->assertStatus(401);
    }

    /**
     * assert authorized user can logout
     *
     * @test
     * @return void
     */
    public function assert_authorized_user_can_logout()
    {
        $this->postJson(route('api.auth.logout'), [], [
            'Authorization' => $this->getBearerToken($this->user())
        ])
            ->assertStatus(200);
    }

}
