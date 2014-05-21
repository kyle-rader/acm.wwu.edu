<?php 
$mysql_ini = parse_ini_file('../.config/mysql.ini');

$mysqli = new mysqli($mysql_ini['server'], $mysql_ini['username'], $mysql_ini['password'], $mysql_ini['database']);
$mysqli2 = new mysqli($mysql_ini['server'], $mysql_ini['username'], $mysql_ini['password'], $mysql_ini['database']);
?>