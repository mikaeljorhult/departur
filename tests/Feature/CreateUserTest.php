<?php

namespace Tests\Feature;

use Departur\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A user can create a user.
     *
     * @return void
     */
    public function testUserCanCreateUser()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/users', [
            'name'     => 'Test User',
            'email'    => 'test@departur.se',
            'password' => 'password',
        ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'name'  => 'Test User',
            'email' => 'test@departur.se',
        ]);
    }

    /**
     * A visitor can not create a user.
     *
     * @return void
     */
    public function testVisitorCanNotCreateUser()
    {
        $response = $this->post('/users', [
            'name'     => 'Test User',
            'email'    => 'test@departur.se',
            'password' => 'password',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', [
            'name'  => 'Test User',
            'email' => 'test@departur.se',
        ]);
    }

    /**
     * Users must have a name.
     *
     * @return void
     */
    public function testUserMustHaveAName()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/users', [
            'name'     => '',
            'email'    => 'test@departur.se',
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('users', [
            'name' => 'Test User',
        ]);
    }

    /**
     * Users must have an e-mail address.
     *
     * @return void
     */
    public function testUserMustHaveAEmail()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/users', [
            'name'     => 'Test User',
            'email'    => '',
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('users', [
            'name' => 'Test User',
        ]);
    }

    /**
     * Users must have a valid e-mail address.
     *
     * @return void
     */
    public function testEmailMustBeValid()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/users', [
            'name'     => 'Test User',
            'email'    => 'not-a-valid-email',
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('users', [
            'name' => 'Test User',
        ]);
    }

    /**
     * Users must have a unique e-mail address.
     *
     * @return void
     */
    public function testEmailMustBeUnique()
    {
        $this->actingAs(factory(User::class)->create());
        factory(User::class)->create(['email' => 'test@departur.se']);

        $response = $this->post('/users', [
            'name'     => 'Test User',
            'email'    => 'test@departur.se',
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('users', [
            'name' => 'Test User',
        ]);
    }
}
