<?php
	include_once('sqlconfig.php');
	include_once('shortenURL.php');
	
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$hostname = $protocol . $_SERVER['HTTP_HOST'] . "/" ;
	
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
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
		$newURL = $hostname . $shortUrl;
		
		$result = mysql_query($sql, $connect);
		
		if($result)
			$json = array("status" => 1, "msg" => "URL Shortened OK!", "shortURL" => $newURL);
		else
			$json = array("status" => 0, "msg" => "Error Shortened!");
	}		
	else
		$json = array("status" => 0, "msg" => "Request method must be POST");

	mysql_close($connect);

	header('Content-type: application/json');
	echo json_encode($json); 
?>