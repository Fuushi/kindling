<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Example action: Save to file, send an email, or save to a database
    $log = fopen("messages.txt", "a");
    fwrite($log, "Name: $name, Email: $email, Message: $message\n");
    fclose($log);

    echo "Thank you, $name! Your message has been received.";
}
?>
