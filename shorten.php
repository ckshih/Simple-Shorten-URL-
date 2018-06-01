<?php
	include_once('sqlconfig.php');
	include_once('shortenURL.php');
	
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$hostname = $protocol . $_SERVER['HTTP_HOST'] . "/" ;

	
	// Get original url from POST
	$url = mysql_real_escape_string($_POST['url']); 
	while(true)
	{
		$shortUrl = shortenURL($url);
		$sql = "SELECT * FROM '$tbName' WHERE shortURL = '$shortUrl'";
		$result = mysql_query($sql, $connect);
		if(mysql_num_rows($result) == NULL)
			break;
	}
	$sql = "INSERT INTO `$tbName` (`ID`, `oriURL`, `shortURL`) VALUES (NULL,'$url','$shortUrl')";
#	echo $sql;
	mysql_query($sql, $connect);
	$newURL = $hostname . $shortUrl;
	echo "Shortened url is : "; 
	echo '<a href="' . $newURL . '">' . $newURL . '</a><br>';	
	mysql_close($connect);
	
?>
