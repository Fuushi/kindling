<?php
//build metadata

?>
<div style="align-items: center; justify-content: center; width: 100%;">
    <center>
        <p style="margin-top: 200px; color:white">Sign in</p>
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
    </center>
</div>