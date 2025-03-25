<?php
##!!! needs permissions for logs/users.json


include 'functions.php';
include 'on_connect.php';

$logged_in = (isset($_SESSION['session_token']));

$form_id = $_GET['form'] ?? "";

$page="";
if ($form_id === "sign_in") {

    //include signin sublet
    include 'sublets/sign_in_sublet.php';

} elseif ($form_id === "collection_settings") {
    //include collection settings sublet
    include 'sublets/collection_settings_sublet.php';

} elseif ($form_id === "collection_modify") {
    //include collection modify sublet
    include 'sublets/collection_modify_sublet.php';
}

elseif ($form_id === "create_collection") {
    //include collection create sublet
    include 'sublets/collection_create_sublet.php';
}

elseif ($form_id === "account_settings") {
    //include account settings sublet
    include 'sublets/account_settings_sublet.php';
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
