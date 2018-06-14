<?php
	include_once('sqliconfig.php');

	$short= $_GET["shortURL"];

	$sql = "SELECT `oriURL` FROM $tbName WHERE shortURL = ?";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param('s',$short);
	$stmt->execute();
	$stmt->bind_result($ori);
	$stmt->fetch();
	
	if($ori == NULL)
	{
		$index = "http://" . $_SERVER['HTTP_HOST'];
		header( "Location:" . $index);
	}

	$geturl = $ori;
	if(strpos($geturl, "://")== NULL)
		header( "Location:http://" . $geturl);
	else
		header( "Location:" . $geturl);
	die();
	
?>
