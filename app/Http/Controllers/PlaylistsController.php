<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Http\Request;
use App\Http\Resources\PlaylistPreview;

class PlaylistsController extends Controller
{
    public function index() {
        $playlists = PlayList::all();
        return PlaylistPreview::collection($playlists);
    }
}
