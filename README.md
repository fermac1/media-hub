# PDF and Video Upload & View Example

This project demonstrates how to upload and view PDF files as well as video files using a simple web interface with HTML, JavaScript, and API calls to a server (running locally). The following features are included:

- **PDF Upload and View**: Users can upload a PDF file, and after successful upload, the PDF is displayed on the webpage.
- **Video Playback**: Users can check if a video file is available and view/download it.

## Features

- **PDF Upload**: Upload a PDF using an HTML form and display it on the page after uploading.
- **Video Playback**: Check if a video is available on the server and play it in a video player.

## Prerequisites

- **API Server**: This assumes you have an API running on `http://127.0.0.1:8000`. The API should have the following endpoints:
  - `POST /api/upload-pdf`: For uploading the PDF file.
  - `GET /api/view-pdf/{filename}`: For viewing the uploaded PDF file.
  - `GET /api/watch-video/{filename}`: For video playback.
  - `GET /api/download-video/{filename}`: For downloading the video file.

Make sure your API is set up and running on the specified base URL.

## How to Use

1. **Upload PDF**:
   - Choose a PDF file from your local machine using the file input.
   - Click the **Upload** button.
   - The PDF will be uploaded and displayed using an `<iframe>` element.

2. **View Video**:
   - The script checks if a video file (`guide.mp4`) is available.
   - If the video is available, a video player will be shown, and the user can play or download the video.

## Code Example

Below is the HTML and JavaScript for the interface:

```html
<!DOCTYPE html>
<html>
<head>
    <title>PDF Upload & View</title>
</head>
<body>
    <h2>Upload PDF</h2>
    <form id="uploadForm">
        <input type="file" name="pdf" id="pdf" accept="application/pdf">
        <button type="submit">Upload</button>
    </form>

    <h2>View Uploaded PDF</h2>
    <iframe src="http://127.0.0.1:8000/api/view-pdf/guide.pdf" id="pdfViewer" width="1000" height="500"></iframe>

    <script>
        document.getElementById('uploadForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData();
            const file = document.getElementById('pdf').files[0];
            formData.append('pdf', file);

            try {
                const response = await fetch('http://127.0.0.1:8000/api/upload-pdf', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                if (response.ok) {
                    alert('Upload successful!');
                    document.getElementById('pdfViewer').style.display = 'block';
                    document.getElementById('pdfViewer').src = result.url + '?t=' + new Date().getTime();
                } else {
                    alert('Upload failed: ' + result.message || result.error);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        });
    </script>

    <script>
        window.addEventListener('DOMContentLoaded', function () {
            const filename = 'guide.mp4';
            const baseUrl = 'http://127.0.0.1:8000/api';

            const videoPlayer = document.getElementById('videoPlayer');
            const downloadLink = document.getElementById('downloadLink');

            fetch(`${baseUrl}/watch-video/${filename}`, { method: 'HEAD' })
                .then((res) => {
                    if (res.ok) {
                        const bust = '?t=' + new Date().getTime();
                        videoPlayer.src = `${baseUrl}/watch-video/${filename}${bust}`;
                        downloadLink.href = `${baseUrl}/download-video/${filename}`;
                        videoPlayer.style.display = 'block';
                        downloadLink.style.display = 'inline';
                    } else {
                        videoPlayer.style.display = 'none';
                        downloadLink.style.display = 'none';
                    }
                })
                .catch(() => {
                    videoPlayer.style.display = 'none';
                    downloadLink.style.display = 'none';
                });
        });
    </script>

</body>
</html>
