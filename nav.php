<?php
//
include 'on_connect.php';
include 'functions.php';

//get GETs
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
            <p>-</p>
            <p style="font-size: 0.75rem; margin: 10px;"><?php echo enforce_size_limit(get_metadata($novelID)['title'], 65) ?></p>
        </div>
    </header>
    

    <div class="content container">
        <div class="bodyText">
            <?php echo servePage($novelID, $pageID)?>
        </div>
    </div>


    <div class="popMenu">
        <center><a href="serveNovel.php?novelID=<?php echo $novelID."&pageID=".$pageID ?>">
            <img src="src/arrow_down.png" alt="" class="icon">


            <div class="slidecontainer">
                <form class="formEmpty" action="serveNovel.php" method="get">
                    <center>
                        <p class="eventSlider">Page: <span id="sliderValue"><?php echo $pageID ?></span></p>
                        <input type="hidden" name="novelID" value="<?php echo $novelID ?>">
                        <input class="slide" type="range" name="pageID" min="0" max="<?php echo get_metadata($novelID)['page_count']-1 ?>" 
                            value="<?php echo $pageID ?>" class="slider" id="myRange" oninput="updateValue(this.value)">

                        <input class="submit" type="submit" value="Go">
                    </center>
                </form>
            </div>

        </a></center>

        


    </div>
        
    <footer>
        <div class="container">
            <p style="float: left; font-size: 10px; color: gray">Estimated Time Remaining</p>
            <a href="nav.php?<?php echo "novelID=".$novelID."&pageID=".$pageID ?>" style="float: right; font-size: 10px; color: gray; text-decoration: none;">page <?php echo $pageID+1?></a>
        </div>
    </footer>

<script>
    function updateValue(val) {
        document.getElementById('sliderValue').innerText = val;
    }

    // Set initial value on page load
    document.addEventListener('DOMContentLoaded', function () {
        const rangeInput = document.getElementById('myRange');
        updateValue(rangeInput.value);
    });
</script>



</body>
</html>