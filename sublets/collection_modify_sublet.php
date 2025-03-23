<?php
//precompute
$collection_id = urldecode($_GET['collection']) ?? null;
$fill = serve_collection_modify_list($collection_id);

?>

<div>
        <br><br>
        <center>
            <p style="color:gray; margin-bottom: 40px;"><?php echo $collection_id ?></p>
        </center>
        

        <div class="list_menu">
            <?php echo $fill ?> 
        </div>
    </div>