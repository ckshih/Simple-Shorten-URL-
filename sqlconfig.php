<?php
	$dbName = "shorten";
	$tbName = "shortentable";
	$server = "localhost";
	$userName = "grass";
	$password = "0000";
	
	$connect = mysql_connect($server, $userName, $password);
	if (!$connect)
		die('Connect Fail! ' . mysql_error());

	mysql_select_db($dbName, $connect); 
?>