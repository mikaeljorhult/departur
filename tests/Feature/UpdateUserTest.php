<?php

namespace Tests\Feature;

use Departur\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A user can update a user.
     *
     * @return void
     */
    public function testUserCanUpdateUser()
    {
        $user = factory(User::class)->create([
            'name' => 'Test User',
        ]);

        $this->actingAs(factory(User::class)->create());

        $response = $this->put('/users/' . $user->id, [
            'name'  => 'Updated User',
            'email' => $user->email,
        ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'name' => 'Updated User',
        ]);
    }

    /**
     * A visitor can not update a user.
     *
     * @return void
     */
    public function testVisitorCanNotUpdateUser()
    {
        $user = factory(User::class)->create([
            'name' => 'Test User',
        ]);

        $response = $this->put('/users/' . $user->id, [
            'name'  => 'Updated User',
            'email' => $user->email,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', [
            'name' => 'Updated User',
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
        $user = factory(User::class)->create();

        $response = $this->put('/users/' . $user->id, [
            'name'  => '',
            'email' => $user->email,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'name' => $user->name,
        ]);
    }

    /**
     * Users must have an e-mail address.
     *
     * @return void
     */
    public function testUserMustHaveAnEmail()
    {
        $this->actingAs(factory(User::class)->create());
        $user = factory(User::class)->create();

        $response = $this->put('/users/' . $user->id, [
            'name'  => $user->name,
            'email' => '',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'email' => $user->email,
        ]);
    }

    /**
     * E-mail address must be valid.
     *
     * @return void
     */
    public function testEmailMustAValidEmail()
    {
        $this->actingAs(factory(User::class)->create());
        $user = factory(User::class)->create();

        $response = $this->put('/users/' . $user->id, [
            'name'  => $user->name,
            'email' => 'not-a-valid-email',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'email' => $user->email,
        ]);
    }

    /**
     * User e-mail address must be unique.
     *
     * @return void
     */
    public function testEmailMustBeUnique()
    {
        $this->actingAs(factory(User::class)->create());

        factory(User::class)->create(['email' => 'test@departur.se']);
        $user = factory(User::class)->create();

        $response = $this->put('/users/' . $user->id, [
            'name'  => 'Updated User',
            'email' => 'test@departur.se',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('users', [
            'name' => 'Updated User',
        ]);
    }

    /**
     * User password is set if supplied.
     *
     * @return void
     */
    public function testPasswordIsSetIfSupplied()
    {
        $this->actingAs(factory(User::class)->create());
        $user = factory(User::class)->create();

        $response = $this->put('/users/' . $user->id, [
            'name'     => 'Updated User',
            'email'    => $user->email,
            'password' => 'new-password'
        ]);

        $response->assertRedirect();
        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }
}
