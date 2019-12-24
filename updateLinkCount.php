<?php
include ("config.php");
if(isset($_POST["linkId"]))
{
    $query=$con->prepare("UPDATE sites SET site_clicks = site_clicks + 1 WHERE site_id=:id");
    $query->bindParam(":id", $_POST["linkId"]);
    $query->execute();
}
else
{
    echo "No link passed to page";
}

?>