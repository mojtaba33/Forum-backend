<?php

namespace Tests\Feature\v1;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ThreadTest extends TestCase
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
    public function testGetThreads()
    {
        $response = $this->getJson(route('v1.thread.index'));

        $response->assertStatus(200);
    }

    public function testShowThreadBySlug()
    {
        $response = $this->getJson(route('v1.thread.show',$this->thread->slug));

        $response->assertStatus(200);
    }

    public function testStoreThreadMiddleware()
    {
        $response = $this->postJson(route('v1.thread.store'));

        $response->assertStatus(401);
    }

    public function testStoreThreadValidation()
    {
        $response = $this->actingAs($this->user)->postJson(route('v1.thread.store'));

        $response->assertStatus(422);
    }

    public function testStoreThread()
    {
        $response = $this->actingAs($this->user)->postJson(route('v1.thread.store'),[
            'title'      => 'test',
            'content'    => 'some text',
            'channel_id' => Channel::factory()->create()->id,
        ]);

        $response->assertStatus(201);

        $thread_id = $response->json('data')['id'];
        $this->assertTrue(Thread::find($thread_id)->exists());
    }

    public function testUpdateThreadValidation()
    {
        $this->setUserIdForAuth();

        $response = $this->actingAs($this->user)->patchJson(route('v1.thread.update',$this->thread->id));

        $response->assertStatus(422);
    }

    public function testUpdateThread()
    {
        $this->setUserIdForAuth();

        $response = $this->actingAs($this->user)->patchJson(route('v1.thread.update',$this->thread->id),[
            'title'      => 'my test',
            'content'    => 'some text',
            'channel_id' => Channel::factory()->create()->id,
        ]);

        $response->assertStatus(200);
    }

    public function testUpdateThreadAuth()
    {
        $response = $this->actingAs($this->user)->patchJson(route('v1.thread.update',$this->thread->id),[
            'title'      => 'my test',
            'content'    => 'some text',
            'channel_id' => Channel::factory()->create()->id,
        ]);

        $response->assertStatus(403);
    }

    public function testDeleteThread()
    {
        $this->setUserIdForAuth();

        $response = $this->actingAs($this->user)->deleteJson(route('v1.thread.destroy',$this->thread->id));

        $response->assertStatus(200);

        $this->assertTrue(!$this->thread->exists());
    }

    public function testSetBestAnswerThread()
    {
        $this->setUserIdForAuth();

        $response = $this->actingAs($this->user)->patchJson(route('v1.thread.best.answer',$this->thread->id),[
            'best_answer_id' => 1
        ]);

        $response->assertStatus(200);
    }

    protected function setUserIdForAuth()
    {
        $this->thread->update([
            'user_id' => $this->user->id
        ]);
    }
}
