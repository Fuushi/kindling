<?php
// functions.php



function test() {
    return "Kindling 0.6 BETA. MIT License.";
}

function serve_collection($collection_id) {
    //load collection data from ID

    //load collections
    $str = file_get_contents("collections.json");
    $collections_json = json_decode($str, true); // decode the JSON into an associative array 

    //find collection
    $collection=null;
    foreach($collections_json as $c) {
        if ($c['name'] == $collection_id) {
            $collection=$c;
        }
    }

    //if not found, throw
    if ($collection == null) {
        return "No Collection Found";
    }

    //
    $out = "";

    //iterate through collection
    foreach ($collection['contents'] as $file) {
        // Check if the file does not contain '.py' or '.pdf'
        if (!str_contains($file, '.')) {
            //Parse

            //

            $str = file_get_contents("novels/".$file."/data.json");
            $json = json_decode($str, true); // decode the JSON into an associative array

            //get path to img 0
            $img = $json['img_data'][0]['file_name'];

            #echo str_replace($img, "/", "/dist_");

            if (isset($_SESSION['user_id'])) {
                $page_id = get_progression($file, $_SESSION['user_id']);
            } else {
                $page_id=0;
            }
            $target="serveNovel.php?novelID=".$file."&pageID=".$page_id; #make page dynamic, save to cache

            //display
            $out = $out . '<a href="'. $target .'"><img src="' . "novels/". str_replace("/", "/dist_", $img) . '" alt="Image 1"></a>'; // Using htmlspecialchars for safety
        }
    }

    //return div
    return $out;
}

function loadImageGrid() {
    ##return '<img src="src/img1.jpg" alt="Image 1">';


    $path = "novels";

    $files = scandir($path);

    $files = array_diff(scandir($path), array('.', '..'));

    $out="";

    //load collections
    $str = file_get_contents("collections.json");
    $json = json_decode($str, true); // decode the JSON into an associative array

    //remove all books in collections from main array
    //iterate collections
    foreach ($json as $collection) {

        //remove from main array
        $files = array_diff($files, $collection['contents']);

    }

    //create refs for collections
    foreach ($json as $collection) {
        //get first image, from first content

        //get data json
        $str = file_get_contents("novels/".$collection['contents'][0]."/data.json");
        $json_collection = json_decode($str, true); // decode the JSON into an associative array

        //get path to img 0
        $img = $json_collection['img_data'][1]['file_name'];

        //set target
        $target="collection.php?collection_id=".$collection['name']; 

        // create div
        $div = "<a href='" . $target . "'> <img style='outline-color: gray; outline-style: dashed; outline-width: 5px; border-radius: 8px;' src='novels/" . str_replace("/", "/dist_", $img) . "' alt='Image 1'></a>";

        //append div to out
        $out = $out.$div;
    }


    foreach ($files as $file) {
        // Check if the file does not contain '.py' or '.pdf'
        if (!str_contains($file, '.')) {
            //Parse

            //

            $str = file_get_contents("novels/".$file."/data.json");
            $json = json_decode($str, true); // decode the JSON into an associative array

            //get path to img 0
            $img = $json['img_data'][0]['file_name'];

            #echo str_replace($img, "/", "/dist_");

            if (isset($_SESSION['user_id'])) {
                $page_id = get_progression($file, $_SESSION['user_id']);
            } else {
                $page_id=0;
            }
            $target="serveNovel.php?novelID=".$file."&pageID=".$page_id; #make page dynamic, save to cache

            //display
            $out = $out . '<a href="'. $target .'"><img src="' . "novels/". str_replace("/", "/dist_", $img) . '" alt="Image 1"></a>'; // Using htmlspecialchars for safety
        }
    }
    



    return $out;
}

function servePage($novelID, $pageID) {
    
    #load json
    $str = file_get_contents("novels/".$novelID."/data.json");

    #parse
    $json = json_decode($str, true); // decode the JSON into an associative array

    #search json

    #extract text
    $text = $json['text'][$pageID];

    #echo $pageID;
    
    #match images
    $dis=false;
    $images = $json['img_data'];
    foreach ($images as $img) {
        if ($img['page_number'] == $pageID) {
            $dis = $img;
        }
    }

    #echo $dis['file_name'];

    #format
    $ret = "";
    if ($dis == true) {
        $ret = $ret.'<p class="bodyText">'.$text.'</p>';
        $ret = $ret.'<img class="imgDisp" src="novels/'.$dis['file_name'].'">';
    }
    else {
        $ret = $ret.'<p>'.$text.'</p>';
    }

    #send
    return $ret;
}

function get_metadata($novelID) {

    #get path
    $path = "novels/".$novelID."/data.json";

    #get json
    $str = file_get_contents($path);
    $json = json_decode($str, true); // decode the JSON into an associative array

    #extract metadata
    $meta = $json['metadata'];

    return $meta;

}

function enforce_size_limit($string, $max_size) {
    if ($string == null) {return "No Title Found";}
    if (strlen($string) > $max_size)
    {
       $new_string = substr($string, 0, $max_size) . '...';

        return $new_string;
    } else {
        return $string;
    }
}



function bookmark($novelID, $pageID, $userID, $value) {
    // Load users.json
    $str = file_get_contents("users.json");
    $json = json_decode($str, true); // Decode the JSON into an associative array

    // With user id, set or remove bookmark
    foreach($json as $key => $user) { // Use $key to reference the position in the array
        if ($userID == $user['username']) {

            // Check if there's a bookmarks array for the novel
            if (isset($user['data']['bookmarks'][$novelID])) {
                // If $value is true, add the bookmark
                if ($value) {
                    // If pageID not in array, add it
                    if (!in_array($pageID, $user['data']['bookmarks'][$novelID])) {
                        array_push($user['data']['bookmarks'][$novelID], $pageID);
                    }
                } else {
                    // If $value is false, remove the pageID from the bookmarks array
                    if (($keyIndex = array_search($pageID, $user['data']['bookmarks'][$novelID])) !== false) {
                        unset($user['data']['bookmarks'][$novelID][$keyIndex]);

                        // Reindex the array after unset
                        $user['data']['bookmarks'][$novelID] = array_values($user['data']['bookmarks'][$novelID]);
                    }
                }
            } elseif ($value) {
                // If $value is true and no bookmark array exists, create it
                $user['data']['bookmarks'][$novelID] = [$pageID];
            }

            // Update the original json array
            $json[$key] = $user;

            // Dump to file
            $encode = json_encode($json, JSON_PRETTY_PRINT);
            file_put_contents("users.json", $encode);

            return true;
        }
    }
    return false;
}


function is_bookmarked($novelID, $pageID, $userID) {
    #echo 'Checking bookmark';

    // Load users.json
    $str = file_get_contents("users.json");
    $json = json_decode($str, true); // Decode the JSON into an associative array

    // Search for the user by userID
    foreach($json as $user) {
        if ($userID == $user['username']) {
            #echo "User Found";

            // Check if the user has bookmarks for the given novel
            if (isset($user['data']['bookmarks'][$novelID])) {
                // Check if the pageID is in the bookmarks array
                if (in_array($pageID, $user['data']['bookmarks'][$novelID])) {
                    #echo "Bookmark exists!";
                    return true;
                }
            }

            // If no bookmark was found for the given pageID
            #echo "Bookmark does not exist.";
            return false;
        }
    }
    
    #echo "User not Found";
    return false;
}

function update_progression($novelID, $pageID, $userID) {
    //updates progression for novel, page, user, returns success

    // Load users.json
    $str = file_get_contents("users.json");
    $json = json_decode($str, true); // Decode the JSON into an associative array

    // With user id, set or remove bookmark
    foreach($json as $key => $user) { // Use $key to reference the position in the array
        if ($userID == $user['username']) {
            #user found

            #modify data
            $user['data']['progression'][$novelID]=$pageID;

            // Update the original json array
            $json[$key] = $user;

            // Dump to file
            $encode = json_encode($json, JSON_PRETTY_PRINT);
            file_put_contents("users.json", $encode);

            return true;
        }
    }
    return false;
}

function get_progression($novelID, $userID) {
    //get progression for novel, user, returns page

    // Load users.json
    $str = file_get_contents("users.json");
    $json = json_decode($str, true); // Decode the JSON into an associative array

    // With user id, set or remove bookmark
    foreach($json as $key => $user) { // Use $key to reference the position in the array
        if ($userID == $user['username']) {
            //user found

            //get data
            if (isset($user['data']['progression'][$novelID])) {
                $page = $user['data']['progression'][$novelID];
            } else {
                return 0;
            }
            

            //return
            return $page;
        }
    }
    //if not logged in, returns page 0
    return 0;
}
?>
