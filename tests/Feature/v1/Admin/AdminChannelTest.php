<?php
namespace Tests\Feature\v1\Admin;

use App\Models\Channel;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
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
        $this->seed(DatabaseSeeder::class);
        $this->user = User::factory()->create([
            'level' => 'admin',
            //'email' => config('permission.default_super_admin_email'),
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
        $response = $this->actingAs($this->user)->getJson(\route('v1.admin.channel.index'));
        $response->assertStatus(404);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetAllChannels()
    {
        $response = $this->actingAs($this->user)->getJson(route('v1.admin.channel.index'));
        $response->assertStatus(200);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreateChannel()
    {
        $response = $this->actingAs($this->user)->postJson(route('v1.admin.channel.store'),[
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
        $response = $this->actingAs($this->user)->postJson(route('v1.admin.channel.store'));
        $response->assertStatus(422);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function tesUpdateChannelValidation()
    {
        $response = $this->actingAs($this->user)->postJson(route('v1.admin.channel.update',$this->channel));
        $response->assertStatus(422);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testUpdateChannel()
    {
        //dd($this->channel);
        $response = $this->actingAs($this->user)->patchJson(url('/api/v1/admin/channel/'.$this->channel->id),[
            'title' => 'davood'
        ]);

        $updatedChannel = Channel::find($this->channel->id);
        //dd($updatedChannel->title);
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
        $response = $this->actingAs($this->user)->deleteJson(route('v1.admin.channel.destroy',$this->channel));
        $response->assertStatus(200);
    }
}
