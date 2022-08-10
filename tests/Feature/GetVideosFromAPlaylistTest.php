<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Video;
use App\Models\Playlist;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetVideosFromAPlaylistTest extends TestCase
{
    use RefreshDatabase;

    public function testCanGetVideosFromAPlaylist(){
        //$this->withoutExceptionHandling();
        $playlist = Playlist::factory()->create();
        $playlist->videos()->attach(
            Video::factory()->count(2)->create()->pluck('id')->toArray()
        );

        $this->getJson(sprintf('/api/playlists/%s/videos', $playlist->id))
            ->assertOk()
            ->assertJsonCount(2);
    }

    public function testVideoContentIsValid() {
        $video = Video::factory()->create();
        $playlist = Playlist::factory()->create();
        $playlist->videos()->attach(
            $video->id
        );

        $this->getJson(sprintf('/api/playlists/%s/videos', $playlist->id))
            ->assertOk()
            ->assertExactJson([
                [
                    'id' => $video->id,
                    'thumbnail' => $video->thumbnail,
                ]
            ]);
    }
}
