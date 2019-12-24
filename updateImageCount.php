<?php
include ("config.php");
if(isset($_POST["imageUrl"]))
{
    $query=$con->prepare("UPDATE images SET img_clicks = img_clicks + 1 WHERE img_imageUrl=:imageUrl");
    $query->bindParam(":imageUrl", $_POST["img_imageUrl"]);
    $query->execute();
}
else
{
    echo "No link passed to page";
}

?>