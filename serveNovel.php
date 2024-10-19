<?php
// index.php
include 'functions.php';

$novelID=$_GET['novelID'];
$pageID=$_GET['pageID'];


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
            <p style="font-size: 0.75rem; margin: 10px;"><?php echo enforce_size_limit(get_metadata($novelID)['title'], 65) ?></p>
        </div>
    </header>


    <div style="position:absolute; width: 20%; float:left"></div>
    <div style="position:absolute; width:80%; float:right"></div>


    <div class="content container">
        <p>-</p>
        <div class="bodyText">
            <?php echo servePage($novelID, $pageID)?>
        </div>
        <a href="<?php echo "serveNovel.php?novelID=".$novelID."&pageID=".$pageID-1 ?>" style="position:absolute; height:100%; width: 20%; float:left">

        </a>

        <a href="<?php echo "serveNovel.php?novelID=".$novelID."&pageID=".$pageID+1 ?>" style="position:absolute; height:100%; width:20%; right:0;">

        </a>


        
        </div>
    </div>

    <footer>
        <div class="container">
            <p style="float: left; font-size: 10px; color: gray">Estimated Time Remaining</p>
            <a href="nav.php" style="float: right; font-size: 10px; color: gray; text-decoration: none;">page <?php echo $pageID?></a>
        </div>
    </footer>
</body>
</html>
