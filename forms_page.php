<?php
##!!! needs permissions for logs/users.json


include 'functions.php';
include 'on_connect.php';

$logged_in = (isset($_SESSION['session_token']));

$form_id = $_GET['form'] ?? "";



$signin_page='
<center>
    <p style="margin-top: 200px;">Sign in</p>
    <form action="actions.php?action=signin&redirect=accounts.php" method="POST" style="width:100%;">
        <input id="username" name="username" type="text" placeholder="Username" style="color: gray; margin:40px; width: 80%; background-color: #0f0f0f;" />
        <input id="password" name="password" type="password" placeholder="Password" style="color: gray; margin:40px; width: 80%; background-color: #0f0f0f; margin-top: -30px;" />
        <input id="password2" name="password2" type="password" placeholder="Re-enter Password" style="color: gray; margin:40px; width: 80%; background-color: #0f0f0f; margin-top: -30px; margin-bottom: 8px;" />
        <div>
            <center>
            <input type="submit" style="position:relative; color: gray; width: 20%; background-color:#0f0f0f; margin-bottom: 40px;" />
            </center>
        </div>
    </form>

    <a href="accounts.php?form=sign_in">Already have an Account? Login</a>
</center>';

$page="";
if ($form_id === "sign_in") {
    $page=$signin_page;
} elseif ($form_id === "collection_settings") {
    $page='
    <div>
        <br><br>
        <center>
            <p style="color:gray; margin-bottom: 40px;">Collections</p>
        </center>
        <div class="list_menu">
            '. serve_collections_list() .'
        </div>
    </div>
';
} elseif ($form_id === "collection_modify") {
    $collection_id = urldecode($_GET['collection']) ?? null;
    $fill = serve_collection_modify_list($collection_id);
    $page = '
    <div>
        <br><br>
        <center>
            <p style="color:gray; margin-bottom: 40px;">'. $collection_id .'</p>
        </center>
        

        <div class="list_menu<<function call in here">
            '. $fill .'    
        </div>
    </div>
    ';
}

elseif ($form_id === "create_collection") {
    //
    #$collection_id = urldecode($_GET['collection']) ?? null;
    $redirect=urlencode('forms_page.php?form=collection_settings');
    $page='
        <div>
        <center>
        <p style="margin-top: 250px; color:white">New Collection</p>
        <form action="actions.php?action=create_collection'.'&redirect='.$redirect.'" method="POST" style="width:100%;">
        <input id="collection_name" name="collection_name" type="text" placeholder="Collection Name..." style="color: gray; margin:40px; width: 80%; background-color:#0f0f0f;" />
            <center>
            <input type="submit" style="position:relative; color: gray; width: 20%; background-color:#0f0f0f; margin-bottom: 40px;" />
            </center>
        </div>
        </center>
        </form>
    </div>';
}

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

    <body>
    <div class="content container">
        <?php echo $page ?>
    </div>
    </body>
    

    <footer>
    </footer>
</body>
</html>
