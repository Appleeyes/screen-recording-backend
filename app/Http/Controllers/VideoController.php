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
        $request->validate(['video' => 'required|mimetypes:video/*',
        ]);

        $video = $request->file('video');
        $uniqueIdentifier = now()->timestamp;
        $originalNameWithoutExtension = pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME);
        $originalExtension = $video->getClientOriginalExtension();

        $videoName = "{$uniqueIdentifier}_{$originalNameWithoutExtension}.{$originalExtension}";
        $path = $video->storeAs('public/videos', $videoName);

        // You can save the transcript here if needed
        // $transcript = $request->input('transcript');

        $response = [
            'name' => $videoName,
            'size' => $video->getSize(),
            'type' => $video->getMimeType(),
            'path' => $path,
            'uploaded_time' => now()->format('Y-m-d H:i:s'), 
        ];

        return response()->json(['message' => 'Video uploaded successfully', 'video_info' => $response]);
    }

    // Render the page to play the video
    public function play($video)
    {
        $videoUrl = asset('storage/videos/' . $video);
        return view('video', ['videoUrl' => $videoUrl]);
    }

    

}