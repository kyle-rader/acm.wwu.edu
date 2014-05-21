<?php
include_once "include/top_ajax.inc";
include_once "$baseInclude/db.inc";
include_once "../php_src/CAS-1.3.2/CAS.php";

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	header("HTTP/1.0 400 Wrong Request Type.");
	exit();
}
else if (!isset($_POST['proff_id'])) {
	header("HTTP/1.0 400 Missing data in post ('proff_id').");
	exit();
}

phpCAS::client(CAS_VERSION_2_0, 'websso.wwu.edu', 443, '/cas');
phpCAS::setNoCasServerValidation();
phpCAS::forceAuthentication();

$proff_id = $_POST['proff_id'];
$user = phpCAS::getUser();
$success = false;
$response = array('success' => false, 'msg'=> 'Failed');

$check_sql = "SELECT count(user) from voters where user = ?";
if($check_stmt = $mysqli->prepare($check_sql)) {
	$check_stmt->bind_param("s", $user);
	$check_stmt->execute();
	$check_stmt->bind_result($count);
	if($check_stmt->fetch()) {
		if($count > 0) {
			$response['msg'] = 'You have already voted! You will be logged out in 3 seconds.';
			print json_encode($response);
			exit();
		}
	}
	$check_stmt->close();
}
$vote_sql1 = "INSERT INTO voters (user) VALUES (?);";
$vote_sql2 = "UPDATE votes SET VOTES = VOTES + 1 WHERE ID = ?";
if($vote_stmt1 = $mysqli->prepare($vote_sql1)) {
	$vote_stmt1->bind_param("s", $user);
	if($vote_stmt1->execute()) {
		$mysqli->commit();
		if($vote_stmt2 = $mysqli2->prepare($vote_sql2)) {
			$vote_stmt2->bind_param("i", $proff_id);
			if($vote_stmt2->execute()) {
				$mysqli2->commit();
				$response['success'] = true;
				$response['msg'] = 'Thank you for voting! You will be logged out in 3 seconds.';
			}
			$vote_stmt2->close();
		}
	}
	$vote_stmt1->close();
}
$response['err'] = $mysqli->error;
print json_encode($response);

?>
