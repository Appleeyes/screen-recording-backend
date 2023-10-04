# Video API Documentation

This documentation provides information on the endpoints and functionality of the Video API.

## Endpoints

### Upload a Video

**Endpoint:** `POST /api/v1/upload`

Upload a single video file.

#### Request

- **Method:** POST
- **Content-Type:** multipart/form-data

**Request Body:**

- `video` (file, required) - The video file to upload.

#### Response

- **Status Code:** 200 OK

```json
{
    "message": "Video uploaded successfully",
    "video_info": {
        "name": "example_video.mp4",
        "size": 1234567,
        "type": "video/mp4",
        "path": "storage/videos/example_video.mp4",
        "uploaded_time": "2023-10-02 12:34:56"
    }
}

### Upload Video Chunks

**Endpoint:** `POST /api/v1/upload_chunk`

Upload video chunks to be merged into a single video.

#### Request

- **Method:** POST
- **Content-Type:** multipart/form-data

**Request Body:**

- `video_chunk` (file, required) - The video chunk file.
- `chunk_index` (numeric, required) - The index of the video chunk.
- `total_chunks` (numeric, required) - The total number of video chunks.
- `video_identifier` (string, required) - Identifier for the video being uploaded.

#### Response

- **Status Code:** 200 OK (Chunks uploaded)

```json
{
    "message": "Video chunk uploaded successfully"
}

### Play a Video

**Endpoint:** GET /api/v1/play/{video}

Render a web page to play a video.

#### Request

- **Method:** GET
- **URL Parameter:**
   - **video** (string, required) - The name of the video to play.

#### Response

A web page for playing the specified video.

### List Video Files

**Endpoint:** GET /api/v1/list

Get a list of video files available on the server.

#### Request

- **Method:** GET

#### Response

- **Status Code:** 200 OK

```json
{
    "video_list": ["video1.mp4", "video2.mp4"]
}

## Controller

The VideoController class in the Laravel application handles video uploads and related functionality.

- **upload(Request $request):** Handles single-file video uploads.
- **uploadChunk(Request $request):** Handles video chunk uploads and merging.
- **play($video):** Renders a page to play a video.
- **videoList():** Retrieves a list of available video files.

For detailed information on controller methods and their implementation, refer to the actual code in the Laravel application.