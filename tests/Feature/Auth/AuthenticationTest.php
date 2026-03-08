<?php

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('locked users can not authenticate using the login screen', function () {
    $user = User::factory()->create([
        'is_locked' => true,
    ]);

    $response = $this->from('/login')->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response
        ->assertSessionHasErrors('email')
        ->assertRedirect('/login');

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});

test('soft deleted users can not authenticate using google login', function () {
    $user = User::factory()->create([
        'email' => 'deleted@example.com',
    ]);

    $user->delete();

    $socialiteUser = new SocialiteUser;
    $socialiteUser->map([
        'email' => 'deleted@example.com',
        'name' => 'Deleted User',
    ]);

    Socialite::shouldReceive('driver->user')
        ->once()
        ->andReturn($socialiteUser);

    $response = $this->from('/login')->get(route('google.callback'));

    $response
        ->assertRedirect('/login')
        ->assertSessionHasErrors('email');

    $this->assertGuest();
    expect(User::withTrashed()->where('email', 'deleted@example.com')->count())->toBe(1);
    expect(User::onlyTrashed()->where('email', 'deleted@example.com')->exists())->toBeTrue();
});

test('locked users can not authenticate using google login', function () {
    $user = User::factory()->create([
        'email' => 'locked@example.com',
        'is_locked' => true,
    ]);

    $socialiteUser = new SocialiteUser;
    $socialiteUser->map([
        'email' => 'locked@example.com',
        'name' => 'Locked User',
    ]);

    Socialite::shouldReceive('driver->user')
        ->once()
        ->andReturn($socialiteUser);

    $response = $this->from('/login')->get(route('google.callback'));

    $response
        ->assertRedirect('/login')
        ->assertSessionHasErrors([
            'email' => 'Your account is locked. Please contact an administrator.',
        ]);

    $this->assertGuest();
    expect(User::where('email', 'locked@example.com')->count())->toBe(1);
});
