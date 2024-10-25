<?php

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomString;
}

function authenticate_login($username, $password, $ip) {
    
    //load database ;)
    $str = file_get_contents("users.json");
    $json = json_decode($str, true); // decode the JSON into an associative array

    foreach($json as $key => $user) { // Use $key to reference the position in the array
        #echo hash("SHA256", $password);
        if (($username == $user['username']) and (hash("SHA256", $password) == $user['password_hash'])) {
            ##create session token
            $token = generateRandomString(40);

            #assign in memory
            $_SESSION['session_token'] = $token;
            $_SESSION['user_id'] = $user['username'];

            #create token cookie
            setcookie("auth_token", $token, time() + 86400 * 30, "/");

            #get token array
            $tokens=$json[$key]['tokens'];
            array_push($tokens, $token);

            #update the original json array
            $json[$key]['tokens'] = $tokens;

            #dump token to file
            $encode = json_encode($json, JSON_PRETTY_PRINT);
            file_put_contents("users.json", $encode);

            return true;
        }
    }

    return false;
}

function authenticate_by_token($token, $ip) {

    //load database ;)
    $str = file_get_contents("users.json");
    $json = json_decode($str, true); // decode the JSON into an associative array

    //iterate until match found
    foreach($json as $key => $user) { // Use $key to reference the position in the array
        
        //match found?
        if (in_array($token, $user['tokens'])) {

            //assign memory
            $_SESSION['session_token'] = $token;
            $_SESSION['user_id'] = $user['username'];

            //return true (authentication succeeded)
            return true;
        }
    }

    //return false (authentication failed)
    return false;
}

?>