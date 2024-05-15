<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Continuous Internet Speed Test</title>
    <style>
        .notification {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px 20px;
            background-color: red;
            color: #fff;
            border-radius: 5px;
            z-index: 9999;
            width: 90vw;
        }

        .notification.top {
            top: 5px;
            bottom: unset;
            width: 90vw;

        }
    </style>
</head>
<body>
    <h1>Continuous Internet Speed Test</h1>
    <div id="speed-results">
        <p id="status">Checking connection...</p>
        <p id="download-speed">Download Speed: <span id="download-speed-value">N/A</span> Mbps </p>
        <p>Status: <span id="speed-category" class="status">...</span></p>
    </div>

    <script>
        function fetchSpeedTestResults() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'speedtest_server.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            let responseText = '';
            let dots = ""; 

            function updateDots() {
                dots += ".";
                if (dots.length > 3) {
                    dots = "";
                }
                document.getElementById('status').textContent = 'Checking connection' + dots;
            }

            setInterval(updateDots, 500);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.LOADING || xhr.readyState === XMLHttpRequest.DONE) {
                    responseText += xhr.responseText; 
                    const jsonObjects = responseText.split('\n');

                    for (let i = 0; i < jsonObjects.length; i++) {
                        try {
                            const data = JSON.parse(jsonObjects[i]);
                            if (data.error) {
                                console.error("Speed test error:", data.error);
                                document.getElementById('status').textContent = 'Error: ' + data.error;
                                document.getElementById('download-speed-value').textContent = 'N/A';
                                document.getElementById('speed-category').textContent = '';
                                return;
                            } else {
                                const downloadSpeed = data.downloadSpeed / 1000000;
                                const speedCategoryElement = document.getElementById('speed-category');
                                document.getElementById('download-speed-value').textContent = downloadSpeed.toFixed(2);

                                let statusClass = '';
                                let message = '';

                                switch (true) {
                                    case (downloadSpeed < 40):
                                        statusClass = "poor";
                                        message = "Your internet connection is Poor, website may not load properly.";
                                        break;
                                    case (downloadSpeed < 80):
                                        statusClass = "low";
                                        message = "Your internet connection is Low, website may not load properly.";
                                        break;
                                    case (downloadSpeed < 140):
                                        statusClass = "average";

                                        break;
                                    case (downloadSpeed < 200):
                                        statusClass = "good";

                                        break;
                                    default:
                                        statusClass = "excellent";

                                }

                                speedCategoryElement.textContent = statusClass.toUpperCase();
                                speedCategoryElement.classList.remove('poor', 'low', 'average', 'good', 'excellent');
                                speedCategoryElement.classList.add(statusClass);

                                if (message) {
                                    showNotification(message);
                                }
                            }
                        } catch (e) { }
                    }
                }
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    document.getElementById('status').textContent = 'Speed test complete';
                }
            };

            xhr.send();
        }

        function showNotification(message) {
            const notification = document.createElement('div');
            notification.classList.add('notification');
            if (window.innerWidth < 600) {
                notification.classList.add('bottom');
            } else {
                notification.classList.add('top');
            }
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        window.onload = function() {
            fetchSpeedTestResults(); 
            setInterval(fetchSpeedTestResults, 10000); 
        };
    </script>
</body>
</html>
