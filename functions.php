<?php
// functions.php



function test() {
    return "Kindling 0.5, No Rights Reserved.";
}

function loadImageGrid() {
    ##return '<img src="src/img1.jpg" alt="Image 1">';


    $path = "novels";

    $files = scandir($path);

    $files = array_diff(scandir($path), array('.', '..'));

    $out="";

    foreach ($files as $file) {
        // Check if the file does not contain '.py' or '.pdf'
        if (!str_contains($file, '.')) {
            //Parse
            $str = file_get_contents("novels/".$file."/data.json");
            $json = json_decode($str, true); // decode the JSON into an associative array

            //get path to img 0
            $img = $json['img_data'][0]['file_name'];

            $target="serveNovel.php?novelID=".$file."&pageID=0"; #make page dynamic, save to cache


            //display
            $out = $out . '<a href="'. $target .'"><img src="' . "novels/" . $img . '" alt="Image 1"></a>'; // Using htmlspecialchars for safety
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
?>
