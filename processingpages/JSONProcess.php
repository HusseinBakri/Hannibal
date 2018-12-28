<?php
session_start();
//echo $_SESSION['JSONQuery'];
include('../lib/JSON.php');

$JSONString = convertMySQLToJSON($_SESSION["JSONQuery"]);
echo  $JSONString;  
?>