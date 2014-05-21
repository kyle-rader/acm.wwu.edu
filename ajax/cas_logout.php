<?php
include_once "include/top_ajax.inc";
include_once "../php_src/CAS-1.3.2/CAS.php";

phpCAS::client(CAS_VERSION_2_0, 'websso.wwu.edu', 443, '/cas');
phpCAS::setNoCasServerValidation();
phpCAS::forceAuthentication();

phpCAS::logout();

?>
