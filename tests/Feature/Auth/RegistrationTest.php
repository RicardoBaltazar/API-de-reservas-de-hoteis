<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Livewire\Volt\Volt;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create the roles before running tests
        Role::create(['name' => 'user']);
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'admin']);
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response
            ->assertOk()
            ->assertSeeVolt('pages.auth.register');
    }

    // public function test_new_users_can_register(): void
    // {
    //     $component = Volt::test('pages.auth.register')
    //         ->set('name', 'Test User')
    //         ->set('email', 'test@example.com')
    //         ->set('password', 'password')
    //         ->set('password_confirmation', 'password')
    //         ->set('role', 'user,mananger,admin');

    //     $component->call('register');

    //     $component->assertRedirect(route('dashboard', absolute: false));
    //     // $component->assertRedirect('/dashboard');

    //     $this->assertAuthenticated();
    // }

    public function test_can_register_user_with_valid_data(): void
    {
        $component = Volt::test('pages.auth.register');

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user'
        ];

        foreach ($userData as $field => $value) {
            $component->set($field, $value);
        }

        $component->call('register');

        // Assert user was created in database
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Assert role was assigned
        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue($user->hasRole('user'));

        // Assert user is authenticated
        $this->assertTrue(Auth::check());
    }

    public function test_cannot_register_with_invalid_data(): void
    {
        $component = Volt::test('pages.auth.register');

        $invalidData = [
            'name' => '',
            'email' => 'not-an-email',
            'password' => '123',
            'password_confirmation' => '456',
            'role' => 'invalid-role'
        ];

        foreach ($invalidData as $field => $value) {
            $component->set($field, $value);
        }

        $component->call('register');

        // Assert validation errors exist
        $component->assertHasErrors([
            'name',
            'email',
            'password',
            'role'
        ]);

        // Assert no user was created
        $this->assertDatabaseCount('users', 0);

        // Assert user is not authenticated
        $this->assertFalse(Auth::check());
    }
}
