<?php
	include_once('sqlconfig.php');
	
	mysql_select_db($dbName, $connect);

	$short= mysql_real_escape_string($_GET["shortURL"]);

	$sql = "select * from $tbName where shortURL='$short'";
	$result = mysql_query($sql);
	
	if(mysql_num_rows($result) == NULL)
	{
		$index = "http://" . $_SERVER['HTTP_HOST'];
		header( "Location:" . $index);
	}

	while($row = mysql_fetch_array($result))
	{
		$geturl = $row['oriURL'];
		if(strpos($geturl, "://")== NULL)
			header( "Location:http://" . $geturl);
		else
			header( "Location:" . $geturl);
		die();
	}
?>
