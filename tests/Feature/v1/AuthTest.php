<?php

namespace Tests\Feature\v1;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test for Registration.
     *
     * @return void
     */
    public function testUserInformationNotValidate()
    {
        $response = $this->postJson(route('v1.auth.register'));

        $response->assertStatus(422);
    }

    /**
     * Test for Registration.
     *
     * @return void
     */
    public function testUserRegister()
    {
        $response = $this->postJson(route('v1.auth.register'),[
            'name' => 'javad',
            'email' => 'j@j.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $response->assertStatus(201);
    }

    /**
     * Test for Login.
     *
     * @return void
     */
    public function testLoginValidation()
    {
        $response = $this->postJson(route('v1.auth.login'));
        $response->assertStatus(422);
    }

    /**
     * Test for Login.
     *
     * @return void
     */
    public function testUserLogin()
    {
        $user = User::factory()->create();
        $response = $this->postJson(route('v1.auth.login'),[
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(200);
    }

    public function testLoggedInUserMiddleware()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson(route('v1.auth.user'));
        $response->assertStatus(200);
    }

    public function testNotRegisteredUserMiddleware()
    {
        //$user = User::factory()->create();
        $response = $this->getJson(route('v1.auth.user'));
        $response->assertStatus(401);
    }

    /**
     * Test for Logout.
     *
     * @return void
     */
    public function testUserLogout()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson(route('v1.auth.logout'));
        $response->assertStatus(200);
    }
}
