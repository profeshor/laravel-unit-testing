<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Video;
use Carbon\Carbon;

class GetVideoListTest extends TestCase
{

    use RefreshDatabase;
    /**
     * Test for checking if the video list API count Json is equals to the number of videos that someone creates
     * @test
     * @return void
     */
    public function testvideoListIsEqualsToCreatedCount () {
        //$this->withoutExceptionHandling();
        Video::factory()->count(5)->create(); // Creates multiple instances of Video
        $this->getJson('/api/videos')
            ->assertOk()
            ->assertJsonCount(5);
    }

    /**
     * Tests the content of the video list API is equals to created content
     * @test
     * @return void
     */
    public function testVideoJsonContentCheck () {
        $videos = Video::factory()->count(5)->create();
        $this->getJson('/api/videos')
            ->assertOk()
            ->assertJson($videos->toArray());
    }

    /**
     * Checks the order of the videos from newest to oldest
     * @test
     * @return void
     */
    public function testVideosAreSortedNewestFirst() {
        // Create video with date of one month ago
        $oneMonthAgoVideo = Video::factory()->create(['created_at' => Carbon::now()->subDays(30)]);
        //Create video with current date
        $todaysVideo = Video::factory()->create(['created_at' => Carbon::now()]);
        //Create video with date of yesterday
        $yesterdaysVideo = Video::factory()->create(['created_at' => Carbon::now()->subDays(1)]);

        $response = $this->getJson('/api/videos')
            ->assertJsonPath('0.id', $todaysVideo['id'])
            ->assertJsonPath('1.id', $yesterdaysVideo['id'])
            ->assertJsonPath('2.id', $oneMonthAgoVideo['id']);

        //[$firstVideo, $secondVideo, $thirdVideo] = $response->json();

        /*
        // Check today
        $this->assertEquals($todaysVideo->id, $firstVideo['id']);
        // Check yesterdays
        $this->assertEquals($yesterdaysVideo->id, $secondVideo['id']);
        // Check One month ago
        $this->assertEquals($oneMonthAgoVideo->id, $thirdVideo['id']);*/
    }
}
