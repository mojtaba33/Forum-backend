<?php

namespace Tests\Feature\v1;

use App\Models\Answer;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AnswerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $answer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->answer = Answer::factory()->create();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAnswerStoreAuth()
    {
        $response = $this->postJson(route('v1.answer.store'));

        $response->assertStatus(401);
    }

    public function testAnswerStoreValidate()
    {
        $response = $this->actingAs($this->user)->postJson(route('v1.answer.store'));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['content','thread_id']);
    }

    public function testAnswerStore()
    {
        $thread = Thread::factory()->create();
        $response = $this->actingAs($this->user)->postJson(route('v1.answer.store'),[
            'content' => 'some content',
            'thread_id' => $thread->id
        ]);

        $response->assertStatus(201);
        $this->assertTrue($thread->answers()->whereContent('some content')->exists());
    }

    public function testAnswerUpdateAuth()
    {
        $response = $this->actingAs($this->user)->patchJson(route('v1.answer.update',$this->answer));

        $response->assertStatus(403);
    }

    public function testAnswerUpdateValidate()
    {
        $this->setUserIdForAuth();

        $response = $this->actingAs($this->user)->patchJson(route('v1.answer.update',$this->answer));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('content');
    }

    public function testAnswerUpdate()
    {
        $this->setUserIdForAuth();

        $response = $this->actingAs($this->user)->patchJson(route('v1.answer.update',$this->answer),[
            'content' => 'update content'
        ]);

        $this->answer->refresh();

        $this->assertEquals($this->answer->content ,'update content');

        $response->assertStatus(200);
    }

    public function testAnswerDeleteAuth()
    {
        $response = $this->actingAs($this->user)->deleteJson(route('v1.answer.destroy',$this->answer));

        $response->assertStatus(403);
    }

    public function testAnswerDelete()
    {
        $this->setUserIdForAuth();

        $response = $this->actingAs($this->user)->deleteJson(route('v1.answer.destroy',$this->answer));

        $response->assertStatus(200);

        $this->assertTrue(!$this->answer->exists());
    }

    protected function setUserIdForAuth()
    {
        $this->answer->update([
            'user_id' => $this->user->id
        ]);
    }
}
