<?php
// index.php

include 'on_connect.php';
include 'functions.php';

$album_id = $_GET['album_id'];
$page_id = $_GET['page_id'];
$sort = $_GET['sort'];
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

    <br><br>

    <div class="img_array">
        <?php echo load_album_images($album_id, $page_id, $sort=$sort) ?>
    </div>


    <div style="margin-left: 38%;">
            <a href="albums.php?album_id=<?php echo $album_id ?>&page_id=<?php echo max(0, $page_id-1) ?>&sort=<?php echo $sort ?>" style="float:left; margin-right: 10px;">prev</a>
            <p style="float:left; color: white;"> <?php echo $page_id ?></p>
            <a href="albums.php?album_id=<?php echo $album_id ?>&page_id=<?php echo max(0, $page_id+1) ?>&sort=<?php echo $sort ?>" style="float:left; margin-left: 10px;">next</a>
    </div>
    <br><br>


    <footer>

    </footer>
</body>
</html>
