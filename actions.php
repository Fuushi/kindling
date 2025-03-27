<?php
require 'on_connect.php';
require 'functions.php';

$delay = 0.1; //0.1
$fail_delay = 3; //3

function set_redirect($target,$delay) {
  //ensure function is only called once
  if ($target === null) {
    return;
  }
  echo "<meta http-equiv='refresh' content='{$delay}; url={$target}' />";
}

//validate inputs
if (! (isset($_GET['action']) and isset($_GET['redirect']))) {
  echo "Invalid Action";
  $redirect="index.php";
  return;
}

//get user
$user = get_login_state();

//get action
$action=$_GET['action'];

//set redirect (can be overwritten inline)
$redirect=urldecode($_GET['redirect']);

//log
log_form($action, $_POST);

//perform action
if ($action === "logout") {
  //log user out
  setcookie("auth_token", "empty", time()-3600, "/", $secure=true);
  session_destroy();
}

elseif ($action === "logout_all") {
  //invalidate all tokens
  flush_user_tokens();

  //log user out
  setcookie("auth_token", "empty", time()-3600, "/", $secure=true);
  session_destroy();

}

elseif ($action === "signin") {
  //perform sign up
  $username= $_POST['username'] ?? "";
  $password= $_POST['password'] ?? "";
  $password2= $_POST['password2'] ?? "";
  create_account($username, $password, $password2, "localhost");

  //log user in
  #authenticate login
  $authenticated = authenticate_login($username, $password, "localhost");


} elseif ($action === "modify_collection") {
  //extract args
  if (!($user === "admin")) {return 401;}
  $collection=urldecode($_GET['collection'] ?? null);
  $novel=urldecode($_GET['novel'] ?? null);
  $value = $_GET['value'] ?? null;

  //validate args
  if (in_array(null, [$collection, $novel, $value])) {
    echo "invalid args";
    $delay=10;
    return;
  }

  //echo
  echo $collection;
  echo $novel;
  echo $value;

  //call func
  set_collection_status($collection, $novel, $value);
} elseif ($action === "remove_collection") {
  if (!($user === "admin")) {return 401;}
  //remove collection

  //decode
  $collection_id = urldecode($_GET['collection'] ?? "None");

  //call function
  remove_collection($collection_id);


} elseif ($action == "create_collection") {
  if (!($user === "admin")) {return 401;}

  echo "Creating Collection";
  $collection_id = $_POST['collection_name'] ?? null;
  if ($collection_id == null) {
    echo "null collection";
    return;
  }

  $collection = sanitize_text($collection_id);

  create_collection($collection);

  echo "Collection Created";

  
} elseif ($action == "change_password") {

  //authenticate user
  if ($user === null) {
    echo "Not Logged In";
    set_redirect("./index.php", $fail_delay);
    //TODO flag ip for potential abuse..
    return;
  }

  //get password data from request
  $p1 = $_POST['p1'] ?? null;
  $p2 = $_POST['p2'] ?? null;

  //validate password data
  if (empty($p1) || empty($p2)) {
    echo "Invalid Password Data";
    set_redirect("./forms_page.php?form=change_password", $fail_delay);
    return;
  }

  //ensure passwords match
  if ($p1 !== $p2) {
    echo "Passwords Do Not Match";
    set_redirect("./forms_page.php?form=change_password", $fail_delay);
    return;
  }

  //hash password using a persistent sha256
  $password_hash = hash("sha256", $p1);

  //call auth.php change_password function
  $auth_pass = change_password($password_hash);

  if ($auth_pass === false) {
    echo "Password Change Failed";
    set_redirect("./forms_page.php?form=change_password", $fail_delay);
    return;
  }

  echo "Password Changed Successfully";

}

//if no fail conditions, set redirect
set_redirect($redirect, $delay);

?>


<!DOCTYPE html>
<html>
  <head>
  </head>
  <body style="background-color: black; color: white; font-size:medium;">
    <p>You will be redirected home soon!</p>
  </body>
</html>