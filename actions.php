<?php
require 'on_connect.php';
require 'functions.php';

//validate inputs
if (! (isset($_GET['action']) and isset($_GET['redirect']))) {
  echo "Invalid Action";
  $redirect="index.php";
  return;
}

//get action
$action=$_GET['action'];

//set redirect
$redirect=$_GET['redirect'];

//perform action
if ($action === "logout") {
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


}
?>


<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="refresh" content="1; url='<?php echo $redirect ?>" />
  </head>
  <body style="background-color: black; color: white; font-size:medium;">
    <p>You will be redirected home soon!</p>
  </body>
</html>