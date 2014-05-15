<?php
include_once "include/top_ajax.inc";
include_once "$baseInclude/functions.inc";
include_once "$baseInclude/db.inc";

if ($_SERVER['REQUEST_METHOD'] != 'GET')
{
	header("HTTP/1.0 400 Wrong Request Type.");
	exit();
}
else if (!isset($_GET['page']))
{
	header("HTTP/1.0 400 Missing 'page' Query String.");
	exit();
}

$page = $_GET['page'];

if (file_exists("$baseAppInclude/$page.php"))
{
	include_once "$baseAppInclude/$page.php";
}
else 
{
	header("HTTP/1.0 404 Page Not Found");
	exit();
}
?>
