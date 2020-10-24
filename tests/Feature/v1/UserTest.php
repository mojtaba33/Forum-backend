<?php

namespace Tests\Feature\v1;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function testLoggedInUserMiddleware()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson(route('v1.auth.show'));
        $response->assertStatus(200);
    }

    public function testNotRegisteredUserMiddleware()
    {
        $response = $this->getJson(route('v1.auth.show'));
        $response->assertStatus(401);
    }
}
