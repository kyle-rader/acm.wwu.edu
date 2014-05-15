<?php
include_once "include/top_ajax.inc";
include_once "$baseInclude/db.inc";

if ($_SERVER['REQUEST_METHOD'] != 'POST')
{
	header("HTTP/1.0 400 Wrong Request Type.");
	exit();
}
else if (!isset($_POST['class']) ||
		 !isset($_POST['major']) ||
		 !isset($_POST['message']) ||
		 !isset($_POST['lab']))
{
	header("HTTP/1.0 400 Missing data in post.");
	exit();
}

$success = false;

$class   = $_POST['class'];
$major   = $_POST['major'];
$message = $_POST['message'];
$lab     = $_POST['lab'];
$ip      = (string)$_SERVER['REMOTE_ADDR'];

$sql = <<<EOT
INSERT INTO feedback (
 CLASS,
 MAJOR,
 MESSAGE,
 LAB,
 IP_ADDRESS)
VALUES (?,?,?,?,?);
EOT;
if($stmt = $mysqli->prepare($sql))
{
	$stmt->bind_param('sssss', $class, $major, $message, $lab, $ip);
	if($stmt->execute())
	{
		$mysqli->commit();
		$success = true;
	}
	$stmt->close();
}

print json_encode(Array('success' => $success));
?>
