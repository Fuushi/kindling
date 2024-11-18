<?php
##!!! needs permissions for logs/users.json


include 'functions.php';
include 'on_connect.php';

$logged_in = (isset($_SESSION['session_token']));

if (! isset($_GET['form'])) {
    echo "Invalid Form";
    return;
}

$form_id = $_GET['form'];


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
        <?php echo $signin_page ?>
    </div>
    </body>
    

    <footer>
        <div class="container">
            <p><?php echo test()?></p>
        </div>
    </footer>
</body>
</html>
