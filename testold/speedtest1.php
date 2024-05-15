<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    set_time_limit(0); 

    function handleError($error) {
        header('Content-Type: application/json');
        echo json_encode(['error' => $error]);
        exit;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://192.168.1.102/cyclesavvy/photo/gallery.mp4');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOPROGRESS, false);

    $startTime = microtime(true);
    $downloadedBytes = 0;

    curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function($resource, $downloadSize, $downloaded, $uploadSize, $uploaded) use (&$startTime, &$downloadedBytes) {
        $downloadedBytes = $downloaded;
        $elapsedTime = microtime(true) - $startTime;

        if ($elapsedTime > 0 && $downloadSize > 0) {
            $downloadSpeed = ($downloaded / $elapsedTime) * 8; // Bits per second
            echo json_encode(['downloadSpeed' => $downloadSpeed]);
            flush();
        }
    });

    $result = curl_exec($ch);

    // Check if the result is valid JSON
    $data = json_decode($result); 
    if (json_last_error() === JSON_ERROR_NONE) { 
        // If valid JSON, it's likely the success response
        echo $result; 
    } else {
        // If not valid JSON, assume an error
        handleError("Failed to download the test file."); 
    }

    curl_close($ch);
    exit; 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internet Speed Test</title>
</head>
<body>
    <h1>Internet Speed Test</h1>
    <div id="speed-results">
        <p id="status">Checking connection...</p>
        <p id="download-speed">Download Speed: <span id="download-speed-value">N/A</span> Mbps</p>
    </div>

    <script>
        // Function to fetch speed test results
        function fetchSpeedTestResults() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', '<?php echo $_SERVER['PHP_SELF']; ?>', true);
            xhr.setRequestHeader('Content-Type', 'application/json');

            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.error) {
                            console.error("Speed test error:", data.error);
                            document.getElementById('status').textContent = 'Error during speed test: ' + data.error; 
                        } else {
                            const downloadSpeed = data.downloadSpeed / 1000000;
                            document.getElementById('download-speed-value').textContent = downloadSpeed.toFixed(2);
                            document.getElementById('status').textContent = 'Speed test complete';
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        document.getElementById('status').textContent = 'Invalid response from server.';
                    }
                }
            };

            xhr.send();
        }

        window.onload = fetchSpeedTestResults;
    </script>
</body>
</html>
