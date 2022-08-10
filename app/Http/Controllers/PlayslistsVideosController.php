<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Http\Request;
use App\Http\Resources\VideoPreview;

class PlayslistsVideosController extends Controller
{
    public function index(Playlist $playlist) {
        return VideoPreview::collection($playlist->videos);
    }
}
