<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Resources\VideoPreview;
use App\Http\Requests\VideoListRequest;

class VideosController extends Controller
{
    /**
     * Retrieves video list
     *
     * @param VideoListRequest $request
     * @return void
     */
    public function index(VideoListRequest $request) {
        $videos = Video::lastVideos($request->getLimit(), $request->getPage())
                ->get();
        return VideoPreview::collection($videos);
    }

    /**
     * Gets single video
     */
    public function get(Video $video) {
        return $video;
    }
}
