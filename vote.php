<?php 
include_once('php_src/CAS-1.3.2/CAS.php');

$baseInclude = $_SERVER['DOCUMENT_ROOT'] . '/include';
$page_title = "WWU CS Proff of the year";

include_once "$baseInclude/functions.inc";

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
			</div>
			<div id="footer">
				<?php include_once "$baseInclude/footer.inc"; ?>
			</div>
		</div>
	</body>
</html>