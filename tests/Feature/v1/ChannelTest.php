<?php
namespace Tests\Feature\v1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChannelTest extends TestCase
{


    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetAllChannels()
    {
        $response = $this->getJson(route('v1.channel.get'));
        $response->assertStatus(200);
    }
}
