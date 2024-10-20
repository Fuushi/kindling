<?php
function authenticate_login($username, $password, $ip) {
    
    //load database ;)
    $str = file_get_contents("users.json");
    $json = json_decode($str, true); // decode the JSON into an associative array

    foreach($json as $key => $user) { // Use $key to reference the position in the array
        if (($username == $user['username']) and ($password == $user['password_hash'])) {
            ##create session token
            $token = generateRandomString(40);

            #assign in memory
            $_SESSION['session_token'] = $token;
            $_SESSION['user_id'] = $username;

            #update the original json array
            $json[$key]['token'] = $token;

            #dump token to file
            $encode = json_encode($json, JSON_PRETTY_PRINT);
            file_put_contents("users.json", $encode);

            return true;
        }
    }

    return false;
}


?>