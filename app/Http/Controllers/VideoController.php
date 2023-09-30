<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cloudinary\Cloudinary;


class VideoController extends Controller
{
    // Handle video submission
    public function upload(Request $request)
    {
        // Validate and store the uploaded video
        $request->validate([
            'video' => 'required|mimetypes:video/mp4|max:100240',
        ]);

        // Initialize the Cloudinary instance with your Cloudinary credentials
        $video = $request->file('video');
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
        ]);

        // Upload the video to Cloudinary
        $uploadResult = $cloudinary->uploadApi()->upload($video->getPathname(), [
            'resource_type' => 'video',
        ]);

        // Get the public URL of the uploaded video
        $videoUrl = $uploadResult['secure_url'];

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
