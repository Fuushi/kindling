<?php 
//precompute
$redirect=urlencode('forms_page.php?form=collection_settings');
?>


<div>
    <center>
    <p style="margin-top: 250px; color:white">New Collection</p>
    <form action="actions.php?action=create_collection&redirect=<?php echo $redirect ?>" method="POST" style="width:100%;">
        <input id="collection_name" name="collection_name" type="text" placeholder="Collection Name..." style="color: gray; margin:40px; width: 80%; background-color:#0f0f0f;" />
        <center>
        <input type="submit" style="position:relative; color: gray; width: 20%; background-color:#0f0f0f; margin-bottom: 40px;" />
        </center>
        </div>
        </center>
    </form>
</div>