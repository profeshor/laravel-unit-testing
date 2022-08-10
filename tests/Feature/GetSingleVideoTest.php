<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Video;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetSingleVideoTest extends TestCase
{
    /**
     * A basic feature test example.
     * @test
     * @return void
     */
    use RefreshDatabase;

    public function getSingleVideoById() {
        // Create video
        $video = Video::factory()->create();
        // API callback
        $result = $this->get(
            sprintf('/api/videos/%s', $video->id));
        // Review result
        $result->assertExactJson([
            'id' => $video->id,
            'title' => $video->title,
            'description' => $video->description,
            'video_url' => $video->video_url,
            'thumbnail' => $video->thumbnail,
        ]);
    }
}
