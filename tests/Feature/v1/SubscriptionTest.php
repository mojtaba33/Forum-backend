<?php

namespace Tests\Feature\v1;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use function PHPUnit\Framework\assertTrue;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    protected $thread;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->thread = Thread::factory()->create();
        $this->user = User::factory()->create();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSubscribeThread()
    {

        $response = $this->actingAs($this->user)->postJson(route('v1.subscribe.thread',$this->thread));

        $this->assertTrue($this->user->subscription()->where('thread_id',$this->thread->id)->exists());

        $response->assertStatus(200);
    }

    public function testUnsubscribeThread()
    {

        $response = $this->actingAs($this->user)->postJson(route('v1.unsubscribe.thread',$this->thread));

        $this->assertFalse($this->user->subscription()->where('thread_id',$this->thread->id)->exists());

        $response->assertStatus(200);
    }
}
