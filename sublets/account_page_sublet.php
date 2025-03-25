<?php


?>

<div class="account-page">
    <center>
        <br><br>
        <p style="color: gray;">Welcome, <?php echo $_SESSION["user_id"] ?></p>
        <br>

        <img src="src/pfp.JPG" alt="" style="width:100px;height:100px;border-radius:50px; margin-bottom:30px;">
        <br>
        <br>

        <div>
            <a style="text-decoration:none;" href="./forms_page.php?form=account_settings"><p style="color:gray; font-size: 15px;">Account Settings</p></a>
            <div style="width: 60%; height: 3px; background-color:#0f0f0f; margin: 10px auto; border-radius:2px;"></div>
            <a style="text-decoration:none;" href=""><p style="color:gray; font-size: 15px;">Admin Settings</p></a>
            <div style="width: 60%; height: 3px; background-color:#0f0f0f; margin: 10px auto; border-radius:2px;"></div>
            <a style="text-decoration:none;" href="forms_page.php?form=collection_settings"><p style="color:gray; font-size: 15px;">Collection Settings</p></a>
            <div style="width: 60%; height: 3px; background-color:#0f0f0f; margin: 10px auto; border-radius:2px;"></div>
            <a style="text-decoration:none;" href=""><p style="color:gray; font-size: 15px;">Album Settings</p></a>
            <div style="width: 60%; height: 3px; background-color:#0f0f0f; margin: 10px auto; border-radius:2px;"></div>
            <a style="text-decoration:none;" href=""><p style="color:gray; font-size: 15px;">About Us</p></a>
            <div style="width: 60%; height: 3px; background-color:#0f0f0f; margin: 10px auto; border-radius:2px;"></div>
            <a style="text-decoration:none;" href="actions.php?action=logout&redirect=accounts.php"><p style="color:gray; font-size: 15px;">Logout</p></a>
        </div>

    </center>
</div>