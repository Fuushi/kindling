<?php
// index.php

include 'on_connect.php';
include 'functions.php';

$collection_id = $_GET['collection_id']

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
        <section id="features">
            <div class="content container">
                <section id="gallery">
                    <div class="grid">
                        <?php echo serve_collection($collection_id) ?>
                    </div>
                </section>
            </div>
        </section>
    </div>

    <footer>
        <div class="container">
            <p style="font-size:8px;"><?php echo test()?></p>
        </div>
    </footer>
</body>
</html>
