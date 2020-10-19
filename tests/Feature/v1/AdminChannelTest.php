<?php
namespace Tests\Feature\v1;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminChannelTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected $user;
    protected $channel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'level' => 'admin'
        ]);

        $this->channel = Channel::factory()->create();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testRegularUserNotAllowedToThisRoutes()
    {
        $this->user = User::factory()->create();
        $response = $this->actingAs($this->user)->getJson(route('channel.index'));
        $response->assertStatus(404);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetAllChannels()
    {
        $response = $this->actingAs($this->user)->getJson(route('channel.index'));
        $response->assertStatus(200);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreateChannel()
    {
        $response = $this->actingAs($this->user)->postJson(route('channel.store'),[
            'title' => $this->faker->sentence(1),
        ]);
        $response->assertCreated();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreateChannelValidation()
    {
        $response = $this->actingAs($this->user)->postJson(route('channel.store'));
        $response->assertStatus(422);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function tesUpdateChannelValidation()
    {
        $response = $this->actingAs($this->user)->postJson(route('channel.update',$this->channel));
        $response->assertStatus(422);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testUpdateChannel()
    {
        $response = $this->actingAs($this->user)->patchJson(route('channel.update',$this->channel),[
            'title' => 'davood'
        ]);

        $updatedChannel = Channel::find($this->channel->id);

        $response->assertStatus(200);
        $this->assertEquals('davood', $updatedChannel->title);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testDeleteChannel()
    {
        $response = $this->actingAs($this->user)->deleteJson(route('channel.update',$this->channel));
        $response->assertStatus(200);
    }
}
