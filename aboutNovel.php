<?php
// index.php
include 'functions.php';
include 'on_connect.php';

$novelID=$_GET['novelID'];
$pageID = $_GET['pageID'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kindling</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div style="display: flex; align-items: center; justify-content: centre;">
            <a href="index.php" style="height: 40px;">
                <img src="src/icon.png" alt="Icon" style="height: 30px; width: 35px; margin-left: 10px; margin-top: 4px">
            </a>
            <form action="users.php" method="GET">
                <input id="search" type="text" placeholder="Search Kindling">
            </form>
            <a href="accounts.php">
                <img src="src/more.png" alt="ICON" style="position: absolute; right:10px; top:10px; height:30px; width:35px;">
            </a>

        </div>
    </header>

    <div class="content container">
        
        <br><br>
        <h1 style="color: grey; font-size: 1.2rem;">

            <center><?php echo str_replace("_", " ", $novelID) ?></center>

            <a href="./serveNovel.php?novelID=<?php echo $novelID?>&pageID=<?php echo $pageID?>">
                <img class="imgDispAbout" src="./novels/<?php echo $novelID?>/<?php echo str_replace(" ", "", $novelID)?>_page1_img1.jpeg" alt="Oops! The image ran away!">
            </a>
        </h1>
        <p class="descriptor">Author: Lorem ipsum</p>
        <p class="descriptor">Page Count: i</p>
        <p class="descriptor">Language: Sengali</p>
    </div>

    <footer>
        <div class="container">
            <p style="font-size:8px;"><?php echo test()?></p>
        </div>
    </footer>
</body>
</html>
