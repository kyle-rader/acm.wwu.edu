<?php 
include_once('php_src/CAS-1.3.2/CAS.php');

phpCAS::client(CAS_VERSION_2_0, 'websso.wwu.edu', 443, '/cas');
phpCAS::setNoCasServerValidation();
phpCAS::forceAuthentication();
if(isset($_REQUEST['logout'])) {
    phpCAS::logout();
}
?>
<html>
        <head>
                <title>phpCAS simple client</title>
        </head>
        <body>
                <h1>Successfull Authentication!</h1>
                <p>The user's login is <strong><?php echo phpCAS::getUser(); ?></strong></p>
                <p>The phpCAS version is <strong><?php echo phpCAS::getVersion(); ?></strong></p>
                <p><a href="?logout=">Logout</a></p>
        </body>
</html>