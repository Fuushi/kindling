<?php
//precalcuate data



//below serve the document

?>

<div style="align-items: center; justify-content: center; width: 100%;">
    <center>
        <p style="margin-top: 100px; color:gray">Account Settings</p>
        <br>
        <div>
            <div style="position: relative;">
                <img class="pfp" src="src/pfp.JPG" alt="" style="border-radius: 50%;">
            </div>

            <p class="gray_text">Change Password</p>

            <br><br>

            <form action="./actions.php?action=change_password&redirect=./accounts.php" method="post">
                <center>
                    <input class="text_input_style" type="password" name="p1" placeholder="New Password">
                    <input class="text_input_style" type="password" name="p2" placeholder="Confirm Password">

                    <input class="button_input_style" type="submit"/>
                </center>
            </form>
        </div>
        
    </center>


</div>