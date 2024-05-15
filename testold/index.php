<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Slider</title>
    <style>
        *,
        html,
        body {
            margin: 0;
            padding: 0;
        }

        .slider-container {
            z-index: 5;
            display: none;
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
            bottom: 24%;
            left: 47%;
            padding: 5px 10px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            background-color: white;
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
        <img class="background" src="img/1.png" alt="" onerror="this.src='test.0091/test.0001.png'">
        <div class="billboard-con">
            <div class="billboard-img">
            <img src="img/1.jpg" alt="">
            </div>
        <p class="billboard"><marquee behavior="" direction="">welcome</marquee></p>
        </div>
    </div>


    <button id="startButton">
        <p data-text="Click to Enter">Enter Virtual Mall</p>
    </button>

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
                    sliderContainer.style.display = "flex";

                    const slides = document.querySelectorAll(".slide");

                    slides[currentImageIndex].classList.remove("current-slide");

                    currentImageIndex = (currentImageIndex + 1) % totalImages;

                    slides[currentImageIndex].classList.add("current-slide");

                    if (currentImageIndex === totalImages - 1) {
                        stopSlider();
                    }
                }, 1000 / 24);
            }

            function stopSlider() {
                clearInterval(intervalId);
                window.location.href = "done.php";
            }

            loadImages();
        });

    </script>
</body>

</html>