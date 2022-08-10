<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Playlist;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetPlaylistListTest extends TestCase
{
    use RefreshDatabase;
     /**
     * Tests if a list of playlists can be retrieved
     * @return void
     */
    public function testCanRetriveListOfPlaylists() {
        $this->withoutExceptionHandling();
        Playlist::factory()->count(2)->create();
        $this->getJson('/api/playlists')
            ->assertOk()
            ->assertJsonCount(2);
    }

    public function testPLaylistPreviewHasCorrectFormat() {

        $id = 12345;
        $title = 'test title';
        $thumbnail = 'http://sdaasd.com';
        $description = 'This is a test description';
        Playlist::factory()->create([
            'id' => $id,
            'title' => $title,
            'thumbnail' => $thumbnail,
            'description' => $description,
        ]);

        $this->getJson('/api/playlists')
            ->assertExactJson([[
                'id' => $id,
                'title' => $title,
                'thumbnail' => $thumbnail,
                'description' => $description,
            ]]);
    }
}
