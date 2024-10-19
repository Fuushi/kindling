<?php
// index.php
include 'functions.php';



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
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
        </div>
    </header>

    <div class="content container">
        <section id="features">
            <div class="content container">
                <section id="gallery">
                    <div class="grid">
                        <?php echo loadImageGrid()?>
                    </div>
                </section>
            </div>
        </section>
    </div>

    <footer>
        <div class="container">
            <p><?php echo test()?></p>
        </div>
    </footer>
</body>
</html>
