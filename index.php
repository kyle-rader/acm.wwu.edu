<?php 
$baseInclude = $_SERVER['DOCUMENT_ROOT'] . '/include';
$page_title = $_SERVER['REQUEST_URI'];

include_once "$baseInclude/functions.inc";

switch($page_title) {
	default:
		$page_title = 'WWU ACM';
}
?>

<!DOCTYPE html>
<html>
<?php include_once "$baseInclude/header.inc"; ?>
<body>
	<div id="wrapper">
		<div id="header">
			<?php include_once "$baseInclude/header_topbar.inc"; ?>
		</div>
		<div id="content">
		</div>
		<div id="footer">
			<?php include_once "$baseInclude/footer.inc"; ?>
		</div>
	</div>
</body>
<script>
<?php include_once "$baseInclude/index.js"; ?>
</script>
</html>
