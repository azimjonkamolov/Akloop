<?php
    ob_start();
    try
    {
	$con = new PDO("mysql:dbname=akloop; host=localhost", "root", "");
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }
    catch(PODExeption $e)
    {
        echo "Connection failed: " . $e->getMessage();
    }
?>