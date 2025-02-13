<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Carousel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .carousel {
            position: relative;
            max-width: 800px;
            margin: auto;
            overflow: hidden;
        }
        .carousel-images {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .carousel img {
            width: 100%;
            display: block;
        }
        .prev, .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
        .prev { left: 10px; }
        .next { right: 10px; }
    </style>
</head>
<body>
    <div class="carousel">
        <div class="carousel-images">
            <img src="https://via.placeholder.com/800x400" alt="Image 1">
            <img src="https://via.placeholder.com/800x400" alt="Image 2">
            <img src="https://via.placeholder.com/800x400" alt="Image 3">
        </div>
        <button class="prev">&#10094;</button>
        <button class="next">&#10095;</button>
    </div>

    <script>
        let index = 0;
        const images = document.querySelectorAll(".carousel-images img");
        const totalImages = images.length;

        document.querySelector(".next").addEventListener("click", () => {
            index = (index + 1) % totalImages;
            updateCarousel();
        });

        document.querySelector(".prev").addEventListener("click", () => {
            index = (index - 1 + totalImages) % totalImages;
            updateCarousel();
        });

        function updateCarousel() {
            document.querySelector(".carousel-images").style.transform = `translateX(-${index * 100}%)`;
        }
    </script>
</body>
</html>