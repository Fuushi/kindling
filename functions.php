<?php
// functions.php



function test() {
    return "Kindling 0.7 BETA. MIT License.";
}

//primitive functions
function get_json($json_path) {
    $str = file_get_contents($json_path);
    $json = json_decode($str, true); // decode the JSON into an associative array 
    return $json;
}

function put_json($json_path, $value) {
    $encode = json_encode($value, JSON_PRETTY_PRINT);
    file_put_contents($json_path, $encode);
}

function sanitize_text($text) {
    $trimmed_text=trim($text);
    $safe_text = htmlspecialchars($trimmed_text, ENT_QUOTES, 'UTF-8');
    return $safe_text;
}

function search_array_key($array, $key, $value) {
    foreach($array as $k) {
        if ($k[$key] == $value) {
            return $k;
        }
    }
    return null;
}

function get_login_state() {
    if (isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    }
    return null;
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

function truncateString($string, $n) {
    if (strlen($string) > $n) {
        return substr($string, 0, $n) . "...";
    }
    return $string;
}

function get_novels() {
    $path = "novels";

    $files = scandir($path);

    $files = array_diff(scandir($path), array('.', '..'));

    $novels=[];
    foreach ($files as $file) {
        if (!str_contains($file, '.')) {
            array_push($novels, $file);
        }
    }

    return $novels;
}

function get_collection($collection_id) {
    $str = file_get_contents("collections.json");
    $collections_json = json_decode($str, true); // decode the JSON into an associative array 

    //find collection
    foreach($collections_json as $c) {
        if ($c['name'] == $collection_id) {
            $collection=$c;
        }
    }

    //return collection, if exists
    return $collection ?? null;
}

function serve_collection($collection_id) {
    //load collection data from ID

    //load collections
    $collections_json = get_json("collections.json");

    //find collection
    $collection = search_array_key($collections_json, "name", $collection_id);

    //if not found, throw
    if ($collection == null) {return "No Collection Found";}


    //iterate through collection
    $out = "";
    foreach ($collection['contents'] as $file) {
        // Check if the file does not contain '.py' or '.pdf'
        if (!str_contains($file, '.')) {

            //load metadata for novel
            $json = get_json("novels/".$file."/data.json");

            //build div
            $img = $json['img_data'][0]['file_name'];
            $page_id = get_progression($file, get_login_state());
            $target="serveNovel.php?novelID=".$file."&pageID=".$page_id; #make page dynamic, save to cache

            //display
            $out = $out . '<a href="'. $target .'"><img src="' . "novels/". str_replace("/", "/dist_", $img) . '" alt="Image 1"></a>'; // Using htmlspecialchars for safety
        }
    }
    //return divs
    return $out;
}

function loadImageGrid() {
    //get novels
    $files = get_novels();

    //load collections
    $json = get_json("collections.json");

    //remove all books in all collections from main array
    foreach ($json as $collection) {
        $files = array_diff($files, $collection['contents']);
    }

    //create refs for collections
    $out="";
    foreach ($json as $collection) {
        //get data json
        $id=$collection['contents'][0] ?? null;
        if ($id === null) {continue;}
        $json_collection = get_json("novels/".$id."/data.json");

        //create div
        $img = $json_collection['img_data'][1]['file_name'];
        $target="collection.php?collection_id=".$collection['name']; 
        $div = "<a href='" . $target . "'> <img style='outline-color: gray; outline-style: dashed; outline-width: 5px; border-radius: 8px;' src='novels/" . str_replace("/", "/dist_", $img) . "' alt='Image 1'></a>";

        //append div to out
        $out = $out.$div;
    }

    //create refs for novels
    foreach ($files as $file) {

        //load novel metadata
        $json = get_json("novels/".$file."/data.json");

        //get path to img 0
        $img = $json['img_data'][0]['file_name'];

        //get page ID
        $page_id = get_progression($file, get_login_state());

        //build div
        $target="serveNovel.php?novelID=".$file."&pageID=".$page_id; #make page dynamic, save to cache

        //display
        $out = $out . '<a href="'. $target .'"><img src="' . "novels/". str_replace("/", "/dist_", $img) . '" alt="Image 1"></a>'; // Using htmlspecialchars for safety
    }

    //get albums json
    $albums_json = get_json("albums.json");
    
    //iterate through albums and build divs
    foreach ($albums_json as $album) {
        // is user auth
        if (get_login_state()) {
            if (in_array(get_login_state(), $album['access'])) {
                //user has access

                // Load all files in album dir
                $album_images = scandir($album['dir']);

                // Remove illegal files
                $illegal_paths = array('.', '..', '...');
                $album_images = array_diff($album_images, $illegal_paths);
                $album_images = array_values($album_images);

                // Select the first image
                $cover_img = $album_images[0];

                //build div
                $url_target="albums.php?album_id=".$album['name']."&page_id=0&sort=alphanumeric"; 
                $img_path = "albums/" . $album['name'] . "/" . $cover_img;
                $encoded_image_path = urlencode($img_path);
                $img_target = "img_permission_checker.php?image_id=" . $encoded_image_path;
                $div = "<a href='" . $url_target . "'><img style='outline-color: gray; outline-style: dashed; outline-width: 5px; border-radius: 8px;' src='" . $img_target . "' alt='None' /></a>";

                //append div to out
                $out = $out.$div;
            }
        }
    }
    return $out;
}

function servePage($novelID, $pageID) {
    
    #load json
    $json = get_json("novels/".$novelID."/data.json");

    #extract text
    $text = $json['text'][$pageID] ?? null;
    
    #match images
    $dis=false;
    $images = $json['img_data'];
    foreach ($images as $img) {
        if ($img['page_number'] == $pageID) {
            $dis = $img;
        }
    }

    #format page contents
    $ret="";
    $ret = $ret.'<p class="bodyText">'.$text.'</p>';
    if ($dis) {$ret = $ret.'<img class="imgDisp" src="novels/'.$dis['file_name'].'">';}

    #return text div
    return $ret;
}

function bookmark($novelID, $pageID, $userID, $value) {
    // Load logs/users.json
    $json = get_json("logs/users.json");

    // With user id, set or remove bookmark
    foreach($json as $key => $user) { // Use $key to reference the position in the array
        if ($userID == $user['username']) {

            // Check if there's a bookmarks array for the novel
            if (isset($user['data']['bookmarks'][$novelID])) {
                // If $value is true, add the bookmark
                if ($value && !in_array($pageID, $user['data']['bookmarks'][$novelID])) {
                    array_push($user['data']['bookmarks'][$novelID], $pageID);
                } elseif (($keyIndex = array_search($pageID, $user['data']['bookmarks'][$novelID])) !== false) {
                    // If $value is false, remove the pageID from the bookmarks array
                    unset($user['data']['bookmarks'][$novelID][$keyIndex]);
                    $user['data']['bookmarks'][$novelID] = array_values($user['data']['bookmarks'][$novelID]);
                }
            } elseif ($value) {
                // If $value is true and no bookmark array exists, create it
                $user['data']['bookmarks'][$novelID] = [$pageID];
            }

            // Update the original json array
            $json[$key] = $user;

            // Dump to file
            put_json("logs/users.json", $json);

            return true;
        }
    }
    return false;
}


function is_bookmarked($novelID, $pageID, $userID) {
    // Load logs/users.json
    $json = get_json("logs/users.json"); //this function could be optimized more, however it wont be

    // Search for the user by userID
    foreach($json as $user) {
        if ($userID == $user['username']) {
            // Check if the user has bookmarks for the given novel
            if (isset($user['data']['bookmarks'][$novelID])) {
                // Check if the pageID is in the bookmarks array
                if (in_array($pageID, $user['data']['bookmarks'][$novelID])) {
                    #echo "Bookmark exists!";
                    return true;
                }
            }

            // If no bookmark was found for the given pageID, return false
            return false;
        }
    }
    //user not found, return false
    return false;
}

function update_progression($novelID, $pageID, $userID) {
    //updates progression for novel, page, user, returns success
    // Load logs/users.json
    $json = get_json("logs/users.json");

    // With user id, set or remove bookmark
    foreach($json as $key => $user) { // Use $key to reference the position in the array
        if ($userID == $user['username']) {
            #user found
            //modify data
            $user['data']['progression'][$novelID]=$pageID;

            // Update the original json array
            $json[$key] = $user;

            // Dump to file
            put_json("logs/users.json", $json);
            return true;
        }
    }
    return false;
}

function get_progression($novelID, $userID) {
    //get progression for novel, user, returns page
    if (!$userID) {return 0;}

    // Load logs/users.json
    $json = get_json("logs/users.json");

    // With user id, set or remove bookmark
    foreach($json as $key => $user) { // Use $key to reference the position in the array
        if ($userID == $user['username']) {
            //get data
            return $user['data']['progression'][$novelID] ?? 0;
        }
    }
    //if not logged in, returns page 0
    return 0;
}

function load_album_images($album_id, $page_id, $sort="alphanumeric") {
    //load album(s)
    $global_albums = get_json("albums.json");

    //select album
    $album = search_array_key($global_albums, "name", $album_id);

    //validate
    if ($album === null) {return "";}

    //auth
    if (!in_array(get_login_state(), $album['access'])) {return "Not Authorized";}

    //load files
    $album_images = scandir($album['dir']);
    
    // Remove illegal files
    $illegal_paths = array('.', '..', '...');
    $album_images = array_diff($album_images, $illegal_paths);
    $album_images = array_values($album_images);

    //sort according to specified algorithm
    if ($sort === "alphanumeric") {natsort($album_images);} 
    elseif ($sort === "random") {shuffle($album_images);}

    //select
    $count=50;
    $offset=$count*$page_id;
    $album_images = array_slice($album_images, $offset, $count);

    # create divs
    $out = "";

    foreach ($album_images as $img) {
        // Construct the image path
        $img_path = "albums/" . $album_id . "/" . $img;
        
        // URL encode the image path to ensure it works correctly with GET parameters
        $encoded_image_path = urlencode($img_path);
        
        // Link to the permission-checking script, passing the image path as a parameter
        $target = "img_permission_checker.php?image_id=" . $encoded_image_path;
    
        // Create the image element, linking to the permission-checking script
        $div = "<a href='" . $target . "'><img src='" . $target . "' alt='None' /></a>";
    
        // Append to $out
        $out .= $div;
    }

    return $out;

}

function create_account($username, $password1, $password2, $ip) {
    //auth (firewall)
        #check logs if for recent requests from ip for actions.php, flag or deny

    //sanitize
    #htmlspclchars
    $username=sanitize_text($username);
    $password1=sanitize_text($password1);
    $password2=sanitize_text($password2);

    //validate
    if (!($password1===$password2)) {
        echo "Passwords Do Not Match";
        return false;
    }

    //hash password
    $password_hash = hash("SHA256", $password1);

    // Load logs/users.json
    $json = get_json("logs/users.json");

    // Check for duplicate username
    foreach ($json as $user) {
        if ($user['username'] === $username) { return false; }
    }

    //create user packet
    $user_data = [
        "username" => $username,
        "password_hash" => $password_hash,
        "tokens" => [],
        "data" => [
            "progression" => [],
            "bookmarks" => [
                "circe" => ["0"] // Predefined bookmark data
            ],
            "highlights" => [],
            "connection_ips" => []
        ]
    ];


    //append
    array_push($json, $user_data);

    //save to disc
    put_json("logs/users.json", $json);
    return true;
}

function serve_collections_list() {
    //this function may be fundamentally unfixable, lord have mercy, lord hath humbled me
    //load collections json
    $collections_json = get_json("collections.json");

    $div = "";
    //iterate and build div
    foreach($collections_json as $collection) {
        //extract name
        $name = $collection['name'];

        //built target
        $target="forms_page.php?form=collection_modify&collection=".urlencode($name);
        $remove_target="actions.php?action=remove_collection&collection=".urlencode($name)."&redirect=".urlencode("forms_page.php?form=collection_settings");
        //build sub string
        $sub='
        <div class="list_menu">
                <div class="list_item">
                    <a class="list_text" href="' . $target . '"><p>'. $name .'</p></a>
                    <a href="'. $remove_target .'"><img src="src/remove.png" alt="src/more.png" class="remove_item_style"></a>
                </div>
            </div>
        <div style="width: 80%; height: 3px; background-color:#0f0f0f; margin: 10px auto; border-radius:2px;"></div>
        ';
        
        //append to output div
        $div = $div.$sub;
    }

    // declare create new literal
    $new_target="forms_page.php?form=create_collection";
    $div_create_new = '
    <div class="list_menu">
        <div class="list_item">
            <a class="list_text" href="'. $new_target .'"><p>Create New Collection</p></a>
            <a href="'. $new_target .'"><img src="src/add.png" alt="src/more.png" class="remove_item_style"></a>
        </div>
    </div>';

    //append create new 
    $div = $div.$div_create_new;

    //return div as string 
    return $div;
}

function serve_collection_modify_list($collection_id) {
    $MAX_CHAR_LEN=35;
    //load novels
    $novels = get_novels();

    //load collection
    $collection = get_collection($collection_id);
    #validate collection
    if ($collection == null) {return "Invalid Collection";}

    //filter; remove IN from OUT 
    $novels = array_diff($novels, $collection['contents']);

    //declare div
    $div = '';    

    //construct IN
    foreach ($collection['contents'] as $collection_novel) {
        //
        $redirect=urlencode("forms_page.php?form=collection_modify&collection=".$collection_id);
        $target="actions.php?action=modify_collection&collection=".urlencode($collection_id)."&novel=".urlencode($collection_novel)."&value=false&redirect=".$redirect;
        $name=truncateString($collection_novel, $MAX_CHAR_LEN);

        $sample = '
        <div class="list_item">
            <a class="list_text" href="' . $target . '"><p>'. $name .'</p></a>
            <a href="'.$target.'"><img src="src/remove.png" alt="src/more.png" class="remove_item_style"></a>
        </div>
        <div style="width: 80%; height: 3px; background-color:#0f0f0f; margin: 10px auto; border-radius:2px;"></div>';

        $div = $div.$sample;

    }

    //construct OUT
    foreach ($novels as $novel) {
        //
        $redirect=urlencode("forms_page.php?form=collection_modify&collection=".$collection_id);
        $target="actions.php?action=modify_collection&collection=".urlencode($collection_id)."&novel=".urlencode($novel)."&value=true&redirect=".$redirect;
        $name=truncateString($novel, $MAX_CHAR_LEN);

        $sample = '
        <div class="list_item">
            <a class="list_text" href="' . $target . '"><p>'. $name .'</p></a>
            <a href="'.$target.'"><img src="src/add.png" alt="src/more.png" class="remove_item_style"></a>
        </div>
        <div style="width: 80%; height: 3px; background-color:#0f0f0f; margin: 10px auto; border-radius:2px;"></div>';

        $div = $div.$sample;

    }

    //return div
    return $div;
}

function set_collection_status($collection_id, $novelID, $value) {
    // Load collections
    $collections_json = get_json("collections.json"); // Decode the JSON into an associative array 

    // Find and update the collection
    foreach ($collections_json as &$collection) { // Use reference (&) to modify the original array
        if ($collection['name'] == $collection_id) {
            //collection found, modify
            if ($value && !in_array($novelID, $collection['contents'])) {
                // If setting true and not in array, append
                array_push($collection['contents'], $novelID);
            } else {
                // If setting false, remove
                $collection['contents'] = array_values(
                    array_diff($collection['contents'], [$novelID])
                );
            }
            break; // Exit the loop once the desired collection is updated
        }
    }

    // Save the updated collections back to the file
    put_json("collections.json", $collections_json);
    return;
}

function remove_collection($collection_id) {
    // Load collections
    $collections_json = get_json("collections.json");

    // Filter out the collection with the given name
    $collections_json = array_filter($collections_json, function($collection) use ($collection_id) {
        return $collection['name'] !== $collection_id; // Keep collections that do not match the name
    });

    // Reindex the array to maintain proper numeric keys
    $collections_json = array_values($collections_json);

    // Save the updated collections back to the file
    put_json("collections.json", $collections_json);
    return;
}

function create_collection($collection_id) {
    // Load collections
    
    $collections_json = get_json("collections.json");

    // Check if a collection with the same name already exists (TODO test)
    if (search_array_key($collections_json, "name", $collection_id)) {return;}

    // Create the new collection
    $new_collection = [
        'name' => $collection_id,
        'contents' => [] // Empty contents initially
    ];

    // Add the new collection to the collections array
    $collections_json[] = $new_collection;

    // Save the updated collections back to the file
    put_json("collections.json", $collections_json);
    return;
}

?>
