<?php
require 'on_connect.php';
setcookie("auth_token", "empty", time()-3600, "/");
session_destroy();

?>


<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="refresh" content="1; url='index.php" />
  </head>
  <body style="background-color: black; color: white; font-size:medium;">
    <p>You will be redirected home soon!</p>
  </body>
</html>