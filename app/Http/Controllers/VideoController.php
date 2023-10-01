<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    private $videoChunks = [];

    // Handle single file video submission
    public function upload(Request $request)
    {
        // Validate and store the uploaded video
        $request->validate([
            'video' => 'required|file',
        ]);

        $video = $request->file('video');
        $uniqueIdentifier = now()->timestamp;
        $originalNameWithoutExtension = pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME);
        $originalExtension = $video->getClientOriginalExtension();

        $videoName = "{$uniqueIdentifier}_{$originalNameWithoutExtension}.{$originalExtension}";
        $path = $video->storeAs('public/videos', $videoName);

        $response = [
            'name' => $videoName,
            'size' => $video->getSize(),
            'type' => $video->getMimeType(),
            'path' => $path,
            'uploaded_time' => now()->format('Y-m-d H:i:s'), 
        ];

        return response()->json(['message' => 'Video uploaded successfully', 'video_info' => $response]);
    }

    // Merge video chunks into a single video and save it
    private function mergeChunks($chunks, $videoIdentifier)
    {
        // Sort chunks by their index
        ksort($chunks);

        // Create a unique video name
        $uniqueIdentifier = now()->timestamp;
        $videoName = "{$uniqueIdentifier}_{$videoIdentifier}.mp4";
        $outputPath = public_path('storage/videos/' . $videoName);

        // Ensure the directory for the output video file exists
        if (!file_exists(dirname($outputPath))) {
            Storage::makeDirectory(dirname($outputPath));
        }

        $outputFile = fopen($outputPath, 'wb');

        foreach ($chunks as $chunk) {
            $chunkData = file_get_contents(storage_path('app/' . $chunk['path']));
            fwrite($outputFile, $chunkData);
        }
        fclose($outputFile);

        // Delete the video chunks after merging
        foreach ($chunks as $chunk) {
            Storage::delete($chunk['path']);
        }

        // Create the video information response
        $video = new \Illuminate\Http\UploadedFile($outputPath, $videoName);
        $response = [
            'name' => $videoName,
            'size' => $video->getSize(),
            'type' => $video->getMimeType(),
            'path' => $outputPath,
            'uploaded_time' => now()->format('Y-m-d H:i:s'),
        ];

        return $response;
    }


    // Handle video chunk upload
    public function uploadChunk(Request $request)
    {
        $chunk = $request->file('video_chunk');
        $chunkIndex = $request->input('chunk_index');
        $totalChunks = $request->input('total_chunks');
        $videoIdentifier = $request->input('video_identifier');

        // Validate and store the uploaded chunk
        $request->validate([
            'video_chunk' => 'required|file',
            'chunk_index' => 'required|numeric',
            'total_chunks' => 'required|numeric',
            'video_identifier' => 'required|string',
        ]);

        $chunkName = "{$videoIdentifier}_chunk_{$chunkIndex}";
        $path = $chunk->storeAs('public/video_chunks', $chunkName);

        $this->videoChunks[$chunkIndex] = [
            'path' => $path,
        ];

        // Check if all chunks have been received
        if (count($this->videoChunks) == $totalChunks) {
            $videoPath = $this->mergeChunks($this->videoChunks, $videoIdentifier);

            return response()->json(['message' => 'Video chunks merged successfully', 'video_path' => $videoPath]);
        } else {
            return response()->json(['message' => 'Video chunk uploaded successfully']);
        }
    }

    // Render the page to play the video
    public function play($video)
    {
        $videoUrl = asset('storage/videos/' . $video);
        return view('video', ['videoUrl' => $videoUrl]);
    }


    public function videoList()
    {
        // Get a list of video files from the storage directory
        $videos = Storage::files('public/videos');

        // You can format the file list as needed
        $videoList = [];
        foreach ($videos as $video) {
            $videoName = basename($video);
            $videoList[] = $videoName;
        }

        // Return the list of video files
        return response()->json(['video_list' => $videoList]);
    }

}