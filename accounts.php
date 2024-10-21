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
            <input id="password" name="password" type="password" placeholder="Password" style="color: gray; margin:40px; width: 80%; background-color: #0f0f0f; margin-top: -30px;" />
            <input type="submit" style="position:relative; color: gray; margin:40px; width: 20%; background-color: #0f0f0f; margin-top: -30px;" />
        </form>

        <p>
        <a href="register.php">Need an account? Sign up</a>
    </center>
</div>
';


if ($logged_in) {
    $page = '
<div class="account-page">
    <center>
        <br><br>
        <p>Welcome, '. $_SESSION["user_id"] . '</p>
        <br>

        <img src="src/pfp.JPG" alt="" style="width:100px;height:100px;border-radius:50px; margin-bottom:350px;">
        <br>
        <a href="actions.php?action=logout" style="font-size:10px; color:gray; text-decoration:none; margin-top:300px;">Logout</a>
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
        <div class="container">
            <p><?php echo test()?></p>
        </div>
    </footer>
</body>
</html>
