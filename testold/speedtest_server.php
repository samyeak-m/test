<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    ob_start();
    set_time_limit(0); 

    function handleError($error, $ch = null) {
        ob_end_clean(); 
        header('Content-Type: application/json');

        $errorData = ['error' => $error];
        if ($ch) {
            $errorData['curl_errno'] = curl_errno($ch);
            $errorData['curl_error'] = curl_error($ch);
            $info = curl_getinfo($ch); 
            $errorData['curl_info'] = $info;
        }

        echo json_encode($errorData);
        exit;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://speed.cloudflare.com/__down?bytes=20000000');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOPROGRESS, false);

    $startTime = microtime(true);
    $downloadedBytes = 0;

    curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function($resource, $downloadSize, $downloaded, $uploadSize, $uploaded) use (&$startTime, &$downloadedBytes) {
        $downloadedBytes = $downloaded;
        $elapsedTime = microtime(true) - $startTime;

        if ($elapsedTime > 0 && $downloadSize > 0) {
            $downloadSpeed = ($downloaded / $elapsedTime) * 8;
            echo json_encode(['downloadSpeed' => $downloadSpeed]) . "\n";
        }
    });

    $result = curl_exec($ch);

    $elapsedTime = microtime(true) - $startTime;
    $downloadSpeed = ($downloadedBytes / $elapsedTime) * 8; 
    echo json_encode(['downloadSpeed' => $downloadSpeed]); 

    if ($result === false) {
        handleError("Failed to download the test file.", $ch); 
    }

    curl_close($ch);
    ob_end_flush(); 
    exit; 
}
