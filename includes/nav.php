<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Bar</title>
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">MyLoves</div>
        <ul class="nav-links">
            <li><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="#">logout</a></li>
        </ul>
        <div class="menu-toggle">&#9776;</div>
    </nav>

    <script>
        const menuToggle = document.querySelector(".menu-toggle");
        const navLinks = document.querySelector(".nav-links");

        menuToggle.addEventListener("click", () => {
            navLinks.classList.toggle("active");
        });
    </script>
</body>
</html>
