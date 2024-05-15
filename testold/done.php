<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Slider</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .slider-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .slide {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: none;
        }

        .current-slide {
            display: block;
        }

        #startButton {
            display: none;
            position: absolute;
            bottom: 46%;
            left: 42%;
            padding: 5px 10px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            background-color: transparent;
            border: 5px solid transparent;
            cursor: pointer;
        }

        #back {
            position: absolute;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center
        }

        .background {
            z-index: -1;
            width: 100%;
            height: 100%;
            object-fit: contain;
            ;
            /* position: relative; */

            /* width: 100vw;
            height: auto;
            aspect-ratio: 16/9;
            object-fit: cover; */
        }

        .billboard-con{
            position: absolute;
            z-index: 8;
            top: 15%;
            left: 42%;
            height: 125px;
            width:345px ;
            overflow: hidden;
            background-color: #000;
        }

        .billboard-img{
            height: 100px;
            width:345px ;
        }

        .billboard-img>img{
            width: 100%;
            height: 100%;
        }
        
        .billboard{
            position: relative;
            font-size: 16px;
            font-weight: 400;
            width:345px ;
            text-align: center;
            overflow: hidden;
            color: white;
        }


        p:hover::after {
            content: attr(data-text);
            position: absolute;
            top: 110%;
            right: -10%;
            font-size: 14px;
            padding: 5px;
            border-radius: 5px;
            color: white;
            background-color: lightgrey;
            
        }
    </style>
</head>

<body>

<div id="back">
        <img class="background" src="img/2.png" alt="" onerror="this.src='test.0091/test.0100.png'">
        <!-- <div class="billboard-con">
            <div class="billboard-img">
            <img src="img/1.jpg" alt="">
            </div>
        <p class="billboard"><marquee behavior="" direction="">welcome</marquee></p>
        </div> -->
    </div>

    <div id='startButton'>
        <button id="stream">stream</button>
        <button id="virtual">virtual</button>
        <button id="meeting">b2b</button>
    </div>
    <div class="slider-container" id="sliderContainer"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const sliderContainer = document.getElementById("sliderContainer");
            const startButton = document.getElementById("startButton");
            const background = document.getElementById("back");

            let intervalId;

            const imageFolder = "test.0091/";
            const totalImages = 120;
            let currentImageIndex = 0;

            function loadImages() {
                const imagesData = [];
                renderImages(imagesData);
                showStartButton();
            }

            function renderImages(imagesData) {
                for (let i = 1; i <= totalImages; i++) {
                    const img = document.createElement("img");
                    const paddedIndex = i.toString().padStart(4, '0');
                    img.src = `${imageFolder}test.${paddedIndex}.png`;
                    img.alt = `Image ${i}`;
                    img.classList.add("slide");
                    sliderContainer.appendChild(img);
                }
            }

            function showStartButton() {
                startButton.style.display = "block";
            }

            startButton.addEventListener("click", startSlider);

            function startSlider() {
                clearInterval(intervalId);
                startButton.style.display = "none";
                background.style.display = "none";

                intervalId = setInterval(() => {
                    const slides = document.querySelectorAll(".slide");

                    slides[currentImageIndex].classList.remove("current-slide");

                    currentImageIndex = (currentImageIndex + 1) % totalImages;

                    slides[currentImageIndex].classList.add("current-slide");
                    if (currentImageIndex === totalImages - 1) {
                        // If the slideshow is completed, navigate to the next page based on the button clicked
                        stopSlider();
                    }
                }, 1000 / 24); // Change the frame rate as needed (30 frames per second in this example)
            }

            function stopSlider() {
                clearInterval(intervalId);

                // Get the button ID that was clicked
                const clickedButtonId = sessionStorage.getItem('clickedButtonId');

                // Define a mapping between button IDs and corresponding PHP pages
                const pageMapping = {
                    'stream': 'stream.php',
                    'virtual': 'virtual.php',
                    'meeting': 'meeting.php'
                };

                // Navigate to the corresponding PHP page
                window.location.href = pageMapping[clickedButtonId];
            }

            // Additional logic to handle different button clicks
            document.getElementById("stream").addEventListener("click", function () {
                // Set the clicked button ID to sessionStorage
                sessionStorage.setItem('clickedButtonId', 'stream');
            });

            document.getElementById("virtual").addEventListener("click", function () {
                // Set the clicked button ID to sessionStorage
                sessionStorage.setItem('clickedButtonId', 'virtual');
            });

            document.getElementById("meeting").addEventListener("click", function () {
                // Set the clicked button ID to sessionStorage
                sessionStorage.setItem('clickedButtonId', 'meeting');
            });

            loadImages();
        });

    </script>
</body>

</html>
