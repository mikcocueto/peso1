<?php
session_start();
require "../includes/db_connect.php"; // Database connection
include "../includes/nav_index.php"; // Database connection
// ?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    
.parent {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    grid-template-rows: repeat(8, 1fr);
    gap: 8px;
    border: 1px solid #000;
    margin: 0 auto;
    width: 80%;
}
    
.div1, .div2, .div3, .div4, .div5 {
    border: 1px solid #000;
    padding: 10px;
    text-align: center;
}


.display_search_bar{
    grid-column: span 5 / span 5;
    grid-row-start: 2;
}

.div3 {
    grid-row: span 5 / span 5;
    grid-column-start: 1;
    grid-row-start: 4;
}

.div4 {
    grid-column: span 5 / span 5;
    grid-column-start: 1;
    grid-row-start: 3;
}

.div5 {
    grid-column: span 4 / span 4;
    grid-row: span 5 / span 5;
    grid-row-start: 4;
}
        
</style>
<body>
<div class="parent">
    <div class="display_search_bar">2</div><!--search-->
    <div class="div3">3</div><!--recommended job-->
    <div class="div4">4</div><!--job filtering-->
    <div class="div5">5</div><!--job description-->
</div>
</body>
</html>