<?php

namespace Tests\Feature;

use Departur\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A user can destroy another user.
     *
     * @return void
     */
    public function testUserCanDestroyAnotherUser()
    {
        $this->actingAs(factory(User::class)->create());
        $user = factory(User::class)->create();

        $response = $this->delete('/users/'.$user->id);

        $response->assertRedirect('/users');
        $this->assertDatabaseMissing('users', [
            'name' => $user->name,
        ]);
    }

    /**
     * A visitor can not destroy a user.
     *
     * @return void
     */
    public function testVisitorCanNotDestroyUser()
    {
        $user = factory(User::class)->create();

        $response = $this->delete('/users/'.$user->id);

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('users', [
            'name' => $user->name,
        ]);
    }

    /**
     * Users can not destroy themselves.
     *
     * @return void
     */
    public function testUsersCanNotOwnUser()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->delete('/users/'.$user->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', [
            'name' => $user->name,
        ]);
    }
}
