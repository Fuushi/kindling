<?php 
require 'auth.php';

//run firewall before anything else
$resp = firewall();
if (!$resp) {
    http_response_code(401); // Set the HTTP response status code
    header('Content-Type: text/plain'); // Optional: Specify the response content type
    echo 'Unauthorized access'; // Optional: Send an error message to the client
    exit(); // Ensure script termination
}

##session handling
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



#is user logging in
if (isset($_POST['password']) and (! isset($_POST['password2']))) {
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

//logging
log_connection();
?>