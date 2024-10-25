<?php 

##session handling
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


#assign ID
#if (!isset($_SESSION['session_id'])) {
#    $_SESSION['session_id'] = generateRandomString(20);
#    $_SESSION['session_token'] = null;
#}

#actions

#is user logging in
if (isset($_POST['password'])) {
    ##user logging in
    $success = authenticate_login($_POST['username'], $_POST['password'], "111.111.111.111");

    if ($success) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        #echo "Logged in!";
        #echo "<br>";
        #echo $_SESSION['session_token'];
    } else {
        echo "Login Failure!";
    }
} else {
    //user not logging in
    
    //is the user already logged in?
    if (!isset($_SESSION['user_id'])) {
        
        //check for token in cookies
        if (isset($_COOKIE['auth_token'])) {

            //ensure session
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            //authenticate
            $success = authenticate_by_token($_COOKIE['auth_token'], "111.111.111.111");

        }
    }

    


}
?>