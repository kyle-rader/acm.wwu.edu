<?php 
include_once('php_src/CAS-1.3.2/CAS.php');

$baseInclude = $_SERVER['DOCUMENT_ROOT'] . '/include';
$page_title = "WWU CS Proff of the year";

include_once "$baseInclude/functions.inc";
include_once "$baseInclude/db.inc";

phpCAS::client(CAS_VERSION_2_0, 'websso.wwu.edu', 443, '/cas');
phpCAS::setNoCasServerValidation();
phpCAS::forceAuthentication();

if(isset($_REQUEST['logout'])) {
    phpCAS::logoutWithRedirectService('http://acm.wwu.edu/thankyou.php');
}

$title = <<<EOT
			<h2>WWU CS Professor of the year 13-14</h2>
			<small></small>
EOT;

$sql = "SELECT ID, PROFF from votes;";
$proffs = array();
if($stmt = $mysqli->prepare($sql)) {
	$stmt->execute();
	$stmt->bind_result($proff_id, $proff);
	while($stmt->fetch()) {
		$proffs[] = array('id' => $proff_id, 'proff' => $proff);
	}
	$stmt->close();
}
?>

<!DOCTYPE html>
<html>
	<?php include_once "$baseInclude/header.inc"; ?>
	<body>
		<div id="wrapper">
			<div id="header">
			</div>
			<div id="content">
				<?php print PageTitle($title); ?>
				<div class="row">
					<div class="large-12 columns">
						<h4>You have Successfully Authenticated.</h4>
						You are voting as <strong><?php echo phpCAS::getUser(); ?></strong><br>
						<small>(If this is not you, please <a href="?logout">logout</a> and log back in as you!)</small>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="large-4 large-centered columns">
						<form id="vote-form" data-abide>
							<table style="width:100%;">
								<thead>
									<th>Professor/ Instructor</th><th>Vote Choice</th>
								</thead>
								<tbody>
									<?php
										foreach($proffs as $proff) {
											print <<< EOT
												<tr><td>{$proff['proff']}</td><td><input type="radio" name="proff_id" value="{$proff['id']}" required/></td></tr>
EOT;
										}
									?>
							</table>
							<br>
							<input type="submit" class="button postfix" value="Vote"/>
							<br>
							<div id="vote-alert" class="alert-box" style="display:none;">
								Thank you for voting!
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="footer">
				<?php include_once "$baseInclude/footer.inc"; ?>
			</div>
		</div>
	</body>
<script>
$('#vote-form').unbind('submit');
$('#vote-form').submit(function(event) {
	event.preventDefault();
	var url = '/ajax/vote.php';
	var form = $(this);
	var alertBox = $('#vote-alert');

	$.post(url, $(this).serialize(), function(response) {
		var info = JSON.parse(response);
		console.log(info);
		alertBox.addClass(info.success ? 'success' : 'warning');
		alertBox.text(info.msg).fadeIn(100);
		setTimeout(function() {
			alertBox.fadeOut(750);
			window.location.replace("/ajax/cas_logout.php");
		}, 3000);
	});
});
</script>
</html>