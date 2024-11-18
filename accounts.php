<?php
include 'functions.php';
include 'on_connect.php';

$logged_in = (isset($_SESSION['session_token']));

$login_page = '
    <div class="login">
        <center>
            <p style="margin-top: 200px;">Login</p>
            <form action="index.php" method="POST">
                <input id="username" name="username" type="text" placeholder="Username" style="color: gray; margin:40px; width: 80%; background-color: #0f0f0f;" />
                <input id="password" name="password" type="password" placeholder="Password" style="color: gray; margin:40px; width: 80%; background-color: #0f0f0f; margin-top: -30px; margin-bottom: 8px; " />
                <div>
                <center>
                <input type="submit" style="position:relative; color: gray; width: 20%; background-color:#0f0f0f; padding-top: -80px; margin-bottom: 40px;" />
                </center>
                </div>
            </form>

            <p>
            <a href="forms_page.php?form=sign_in">Need an account? Sign up</a>
        </center>
    </div>
';


if ($logged_in) {
    $page = '
    <div class="account-page">
        <center>
            <br><br>
            <p style="color: gray;">Welcome, '. $_SESSION["user_id"] . '</p>
            <br>

            <img src="src/pfp.JPG" alt="" style="width:100px;height:100px;border-radius:50px; margin-bottom:30px;">
            <br>
            <br>

            <div>
                <a style="text-decoration:none;" href=""><p style="color:gray; font-size: 15px;">Account Settings</p></a>
                <div style="width: 60%; height: 3px; background-color:#0f0f0f; margin: 10px auto; border-radius:2px;"></div>
                <a style="text-decoration:none;" href=""><p style="color:gray; font-size: 15px;">Admin Settings</p></a>
                <div style="width: 60%; height: 3px; background-color:#0f0f0f; margin: 10px auto; border-radius:2px;"></div>
                <a style="text-decoration:none;" href=""><p style="color:gray; font-size: 15px;">Collection Settings</p></a>
                <div style="width: 60%; height: 3px; background-color:#0f0f0f; margin: 10px auto; border-radius:2px;"></div>
                <a style="text-decoration:none;" href=""><p style="color:gray; font-size: 15px;">Album Settings</p></a>
                <div style="width: 60%; height: 3px; background-color:#0f0f0f; margin: 10px auto; border-radius:2px;"></div>
                <a style="text-decoration:none;" href=""><p style="color:gray; font-size: 15px;">About Us</p></a>
                <div style="width: 60%; height: 3px; background-color:#0f0f0f; margin: 10px auto; border-radius:2px;"></div>
                <a style="text-decoration:none;" href="actions.php?action=logout&redirect=accounts.php"><p style="color:gray; font-size: 15px;">Logout</p></a>
            </div>

        </center>
    </div>
';
} else {
    $page = $login_page;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
        <?php echo $page?>
    </div>
    </body>
    

    <footer>
    </footer>
</body>
</html>
