<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Video;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
     * Tests the content if the video list items contains thumbnail and Id only
     * @test
     * @return void
     */
    public function testVideoListItemContainsIdAndThumbnailOnly () {
        $video_id = 20;
        $video_thumbnail = "https://thetest.org/12332.jpg";
        Video::factory()->create([
            'id' => $video_id,
            'thumbnail' => $video_thumbnail,
        ]);
        $this->getJson('/api/videos')
            ->assertOk()
            ->assertExactJson([
                [
                    'id' => $video_id,
                    'thumbnail' => $video_thumbnail,
                ]
            ]);
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
    }

    /**
     * Tests if video list is able to limit the number of items
     * @test
     * @return void
     */
    public function testVideoListIsAbleToLimit() {
        Video::factory()->count(5)->create();

        $this->getJson('/api/videos?limit=3')
            ->assertJsonCount(3);
    }

    /**
     * Default limit should be 30
     * @test
     * @return void
     */
    public function testGetOnly30VideosByDefault() {
        Video::factory()->count(40)->create();

        $this->getJson('/api/videos')
            ->assertJsonCount(30);
    }


    /**
     * Data provider for unprocessable errors
     *
     * @return array
     */
    public function providerNotValidLimits() : array {
        return[
            "The minimum of retrieved videos is 1" => [3, '-1'],
            "The maximum of retrieved videos is 50" => [51, '51'],
            'It is not valid to pass not numeric characters on limit' => [5, 'abc1234'],
        ];
    }

    /**
     * Tests the not valid limits which generate an unprovessable error
     *
     * @dataProvider providerNotValidLimits
     * @param integer $countVideosToCreate
     * @param integer $limit
     * @return void
     */
    public function testGetsUnprocessableIfErrorOnLimit (int $countVideosToCreate, string $limit) {
        Video::factory()->count($countVideosToCreate)->create();
        $this->getJson(sprintf('/api/videos?limit=%s', $limit))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Data provider with valid pages
     * @return array
     */

    public function validPagesDataProvider(): array {
        return [
            "When 9 items are created, page 2 should return 4 items" => [9, 5, 2, 4],
            "Page 20 should be empty when 5 items are in database" => [5, 5, 20, 0],
        ];
    }

    /**
     * Tests if video list can paginate with valid parameters
     * @dataProvider  validPagesDataProvider
     * @return void
     */

    public function testVideoListCanPaginate($itemsToCreate, $limit, $page, $expectedCount) {
        Video::factory()->count($itemsToCreate)->create();
        $this->getJson(sprintf('/api/videos?limit=%s&page=%s', $limit, $page))
            ->assertJsonCount($expectedCount);
    }

    /**
     * Test that default page shoud be 1
     *
     * @return void
     */
    public function testDefaultPageIsOne() {
        Video::factory()->count(9)->create();
        $this->getJson('/api/videos?limit=5')
            ->assertJsonCount(5);
    }

    /**
     * Invalid pages numbers data probider
     *
     * @return array
     */
    public function invalidPagesDataProvider(): array {
        return [
            "Page parameter can't be a string" => ['abc123'],
            "Page parameter can't less than 1" => ['-2'],
        ];
    }

    /**
     * Tests all invalid page cases
     * @dataProvider invalidPagesDataProvider
     * @param [type] $invalidPage
     * @return void
     */
    public function testGetsUnprocessableIfErrorOnPage($invalidPage) {
        Video::factory()->count(5)->create();
        $this->getJson(sprintf('/api/videos?page=abce', $invalidPage))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
