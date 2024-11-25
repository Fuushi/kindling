<?php

function get_login_state_auth() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    }
    return null;
}

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
    $str = file_get_contents("logs/users.json");
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
            setcookie("auth_token", $token, time() + 86400 * 30, "/", $secure=true);

            #get token array
            $tokens=$json[$key]['tokens'];
            array_push($tokens, $token);

            #update the original json array
            $json[$key]['tokens'] = $tokens;

            #dump token to file
            $encode = json_encode($json, JSON_PRETTY_PRINT);
            file_put_contents("logs/users.json", $encode);

            return true;
        }
    }

    return false;
}

function authenticate_by_token($token, $ip) {

    //load database ;)
    $str = file_get_contents("logs/users.json");
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

function log_connection() {
    // Start session if it hasn't already been started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Prepare log data
    $data = [
        'ip' => $_SERVER['REMOTE_ADDR'],
        'src' => basename($_SERVER['SCRIPT_NAME']),
        'time' => round(microtime(true) * 1000)
    ];

    // Load logs db once
    $str_logs = file_get_contents("logs/access_logs.json");
    $json_logs = json_decode($str_logs, true) ?? []; // decode the JSON into an associative array

    // Check if user is logged in
    if (isset($_SESSION['session_token'])) {
        // User is logged in
        $userId = $_SESSION['user_id'];

        // Log as user
        if (!isset($json_logs[$userId])) {
            $json_logs[$userId] = [];
        }
        array_push($json_logs[$userId], $data);

    } else {
        // Not logged in, log as guest
        if (!isset($json_logs['guest'])) {
            $json_logs['guest'] = [];
        }
        array_push($json_logs['guest'], $data);
    }

    // Write to disk
    $encode = json_encode($json_logs, JSON_PRETTY_PRINT);
    file_put_contents("logs/access_logs.json", $encode);
}

function log_form ($form_id, $form_data) {// Start session if it hasn't already been started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    //prepare form log data
    $form = [
        "form" => $form_id,
        "form_data" => sanitize_text(json_encode($form_data))
    ];

    // Prepare log data
    $data = [
        'ip' => $_SERVER['REMOTE_ADDR'],
        'src' => basename($_SERVER['SCRIPT_NAME']),
        'time' => round(microtime(true) * 1000),
        'form' => $form
    ];

    // Load logs db once
    $str_logs = file_get_contents("logs/access_logs.json");
    $json_logs = json_decode($str_logs, true) ?? []; // decode the JSON into an associative array

    // Check if user is logged in
    if (isset($_SESSION['session_token'])) {
        // User is logged in
        $userId = $_SESSION['user_id'];

        // Log as user
        if (!isset($json_logs[$userId])) {
            $json_logs[$userId] = [];
        }
        array_push($json_logs[$userId], $data);

    } else {
        // Not logged in, log as guest
        if (!isset($json_logs['guest'])) {
            $json_logs['guest'] = [];
        }
        array_push($json_logs['guest'], $data);
    }

    // Write to disk
    $encode = json_encode($json_logs, JSON_PRETTY_PRINT);
    file_put_contents("logs/access_logs.json", $encode);
}

function firewall() {
    //constants
    $now = round(microtime(true) * 1000);
    $sample = 900*1000;#(ms) (900*1000)
    $get_rate = 150; #10 requests / second (150)
    $post_rate = 10; #1 request / second (15)
    $src_ip = $_SERVER['REMOTE_ADDR'];


    //filter by account
    //
    //
    //get active user
    $user = get_login_state_auth();

    //load access_logs
    $str = file_get_contents("logs/access_logs.json");
    $json = json_decode($str, true); // decode the JSON into an associative array 

    //get user logs
    $user_logs = $json[$user] ?? [];

    //
    #find GET rate and POST rate
    $get_count=0;
    $post_count=0;
    foreach($user_logs as $entry) {
        if (($now - $entry['time']) <= $sample) {
            //entry is in sample time

            //detect post or get
            $form = $entry['form'] ?? null;
            if (!$form) {
                //detect get
                $get_count = $get_count + 1;
            } else {
                $post_count = $post_count + 1;
            }
        }
    }

    //get rate limit
    if ($get_count > $get_rate) {return false;}

    //post rate limit
    if ($post_count > $post_rate) {return false;}



    ///filter by IP
    //
    //
    
    #apply same targets by ip
    $src_get_count=0;
    $src_post_count=0;
    foreach($json as $user_logs) {
        foreach($user_logs as $entry) {
            // if request is from matching source
            if ($entry['ip'] === $src_ip) {
                //check time to sample
                if (($now - $entry['time']) <= $sample) {
                    //assign to get or post
                    $form = $entry['form'] ?? null;
                    if (!$form) {
                        //detect get
                        $src_get_count = $src_get_count + 1;
                    } else {
                        $src_post_count = $src_post_count + 1;
                    }
                }
            }
        }
    }

    //get rate limit for src
    if ($src_get_count > $get_rate) {return false;}

    //post rate limit for src
    if ($src_post_count > $post_rate) {return false;}
    

    return true;
}
?>