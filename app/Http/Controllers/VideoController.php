<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    // Handle video submission
    public function upload(Request $request)
    {
        // Validate and store the uploaded video
        $request->validate([
            'video' => 'required|mimetypes:video/mp4|max:100240',
        ]);

        $video = $request->file('video');
        $videoName = Str::random(20) . '.' . $video->getClientOriginalExtension();

        $path = $video->storeAs('public/videos', $videoName);

        // You can save the transcript here if needed
        // $transcript = $request->input('transcript');

        return response()->json(['message' => 'Video uploaded successfully']);
    }

    // Render the page to play the video
    public function play($video)
    {
        $videoUrl = asset('storage/videos/' . $video);
        return view('video', ['videoUrl' => $videoUrl]);
    }

}
