<?php
require 'on_connect.php';
require 'functions.php';

$delay = 0.1; //0.1

//validate inputs
if (! (isset($_GET['action']) and isset($_GET['redirect']))) {
  echo "Invalid Action";
  $redirect="index.php";
  return;
}

//get action
$action=$_GET['action'];

//set redirect
$redirect=urldecode($_GET['redirect']);

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


} elseif ($action === "modify_collection") {
  //extract args
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
  //remove collection

  //decode
  $collection_id = urldecode($_GET['collection'] ?? "None");

  //call function
  remove_collection($collection_id);
} elseif ($action == "create_collection") {
  echo "Creating Collection";
  $collection_id = $_POST['collection_name'] ?? null;
  if ($collection_id == null) {
    echo "null collection";
    return;
  }

  $collection = sanitize_text($collection_id);

  create_collection($collection);

  echo "Collection Created";

  
}
?>


<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="refresh" content="<?php echo $delay ?>; url='<?php echo $redirect ?>" />
  </head>
  <body style="background-color: black; color: white; font-size:medium;">
    <p>You will be redirected home soon!</p>
  </body>
</html>