<?php

namespace Tests\Feature\v1;

use App\Models\Thread;
use App\Models\User;
use App\Notifications\ThreadAnswered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;
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

    public function testSendNotificationToUsers()
    {
        $this->withoutExceptionHandling();

        Notification::fake();
        $subscribeResponse = $this->actingAs($this->user)->postJson(route('v1.subscribe.thread',$this->thread));
        $subscribeResponse->assertStatus(200);

        $response = $this->actingAs($this->user)->postJson(route('v1.answer.store'),[
            'thread_id' => $this->thread->id,
            'content' => 'some content',
        ]);

        $response->assertStatus(201);
        Notification::assertSentTo($this->user,ThreadAnswered::class);
    }
}
