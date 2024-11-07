<?php
session_start(); // Start the session

function isUserLoggedIn() {
    return isset($_SESSION['user_id']); // Adjust based on your session setup
}

function userHasAccess($imageId) {
    // Implement your logic to check if the user has access to the image
    // This could involve checking the database for permissions

    //load albums.json
    $str = file_get_contents("albums.json");
    $albums = json_decode($str, true); // decode the JSON into an associative array 

    //extract album id from url
    $array_explode = explode("/", $imageId);
    $album_id = $array_explode[1];
    

    foreach ($albums as $album) {
        if ($album['name'] === $album_id) {
            //album found
            //authenticate

            // check if signed in
            if (isset($_SESSION['user_id'])) {
                if (in_array($_SESSION['user_id'], $album['access'])) {
                    //return true if so
                    return true;
                }
            }
        }
    }

    return false; // Change this to your actual permission logic
}

// Get the image ID from the request
$imageId = urldecode($_GET['image_id']); // Sanitize the input to prevent directory traversal
if (isUserLoggedIn() && userHasAccess($imageId)) {
    // Set the content type header based on the file type
    $imagePath = $imageId; // Adjust path as necessary
    //echo $imagePath;
    if (file_exists($imagePath)) {
        header('Content-Type: image/jpeg'); // Adjust based on your image type
        readfile($imagePath);
        exit; // Ensure no further output is sent
    } else {
        http_response_code(404);
        echo "Image not found.";
    }
} else {
    http_response_code(403);
    echo "Access denied.";
}
?>
