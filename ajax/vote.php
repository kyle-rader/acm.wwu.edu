<?php
include_once "include/top_ajax.inc";
include_once "$baseInclude/db.inc";
include_once "../php_src/CAS-1.3.2/CAS.php";

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	header("HTTP/1.0 400 Wrong Request Type.");
	exit();
}
else if (!isset($_POST['action'])) {
	header("HTTP/1.0 400 Missing data in post ('action').");
	exit();
}

phpCAS::client(CAS_VERSION_2_0, 'websso.wwu.edu', 443, '/cas');
phpCAS::setNoCasServerValidation();
phpCAS::forceAuthentication();

$success = false;

switch($_POST['action']) {
case 'vote':
	if (!isset($_POST['proff_id']) || ! isset($_POST['user'])) {
		header("HTTP/1.0 400 Missing data in post ('proff_id' or 'user').");
		exit();
	}
	$proff_id = $_POST['proff_id'];
	$user = $_POST['user'];
	
	$check_sql = "SELECT count(user) from voters where user = ?";
	if($check_stmt = $mysqli->prepare($check_sql)) {
		$check_stmt->bind_param("s", $user);
		$check_stmt->execute();
		$check_stmt->bind_result($count);
		if($check_stmt->fetch()) {
			if($count > 0) {
				print json_encode(array('success' => false, 'msg' => 'You have already voted!'));
				exit();
			}
		}
		$check_stmt->close();
	}
	$vote_sql = "INSERT INTO voters (user) VALUES (?); UPDATE votes SET VOTES = VOTES + 1 WHERE ID = ?";
	if($vote_stmt = $mysqli->prepare($vote_sql)) {
		$vote_stmt->bind_param("si", $user, $proff_id);
		if($vote_stmt->execute()) {
			$vote_stmt->commit();
		}
		$vote_stmt->close();
	}
	
case 'list_proffs':
default:
	header("HTTP/1.0 400 Missing data in post ('action').");
	exit();
}

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
