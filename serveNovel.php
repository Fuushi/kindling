<?php
// index.php
include 'functions.php';
include 'on_connect.php';

$novelID=$_GET['novelID'];
$pageID=$_GET['pageID'];



//bookmark event handling
$bookmark_delta = isset($_GET['bookmark']);
if ($bookmark_delta) {
    #apply changes

    //is user logged in
    if (isset($_SESSION['session_token'])) {
        //logged in

        //get user id
        $user_id=$_SESSION['user_id'];

        //string boolean conversion
        $value = ($_GET['bookmark']==="true");

        // bookmark
        bookmark($novelID, $pageID, $user_id, $value);
    } else {
        //not logged in, ignore
        #echo "NOT LOGGED IN";
        #echo implode($_SESSION);
    }

}

//user stat handling
if (isset($_SESSION['session_token'])) {
    //bookmarks
    $bookmarked=is_bookmarked($novelID, $pageID, $_SESSION['user_id']);

    //page progress
    update_progression($novelID, $pageID, $_SESSION['user_id']);
} else {
    //bookmarks
    $bookmarked = false;
}


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

        <a href="<?php echo 'serveNovel.php?novelID='.$novelID."&pageID=".$pageID?>&bookmark=<?php echo !$bookmarked ? 'true' : 'false'?>">
                <img src="<?php echo  $bookmarked ? 'src/bookmark_true.png' : 'src/bookmark_false.png'?>" alt="ICON" style="position: absolute; right:10px; top:10px; height:30px; width:35px;">
        </a>
    </header>


    <div style="position:absolute; width: 20%; float:left"></div>
    <div style="position:absolute; width:80%; float:right"></div>


    <div class="content container">
        <p>-</p>
        <div class="bodyText" style="font-family: 'Times New Roman', serif; font-size: 11pt;">
            <?php echo servePage($novelID, $pageID)?>
        </div>
        <a href="<?php echo "serveNovel.php?novelID=".$novelID."&pageID=".max($pageID-1, 0) ?>" style="position:absolute; height:100%; width: 20%; float:left">

        </a>

        <a href="<?php echo "serveNovel.php?novelID=".$novelID."&pageID=".min($pageID+1,get_metadata($novelID)['page_count']-1) ?>" style="position:absolute; height:100%; width:20%; right:0;">

        </a>


        
        </div>
    </div>

    <footer>
        <div class="container">
            <p style="float: left; font-size: 10px; color: gray">Estimated Time Remaining</p>
            <a href="nav.php?<?php echo "novelID=".$novelID."&pageID=".$pageID ?>" style="float: right; font-size: 10px; color: gray; text-decoration: none;">page <?php echo $pageID+1?></a>
        </div>
    </footer>
</body>
</html>
