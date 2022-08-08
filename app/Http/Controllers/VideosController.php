<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideosController extends Controller
{
    public function index() {
        return Video::orderBy('created_at', 'DESC')->get();
    }

    /**
     * Gets single video
     */
    public function get(Video $video) {
        return $video;
    }
}
