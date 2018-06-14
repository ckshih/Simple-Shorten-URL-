<?php
	include_once('sqliconfig.php');
	include_once('shortenURL.php');
	
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$hostname = $protocol . $_SERVER['HTTP_HOST'] . "/" ;

	
	// Get original url from POST
	$url = $_POST['url'];
	while(true)
	{
		$shortUrl = shortenURL($url);
		$sql = "SELECT `ID` FROM $tbName WHERE shortURL = ?";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param('s',$url);
		$stmt->execute();
		$stmt->bind_result($id);
		$stmt->fetch();

		if($id == NULL)
			break;
		$stmt->close();
	}
	$sql = "INSERT INTO `$tbName` (`ID`, `oriURL`, `shortURL`) VALUES (NULL, ?, ?)";
	$newURL = $hostname . $shortUrl;
	
	//$result = mysql_query($sql, $connect);
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param('ss', $url, $shortUrl);
	$stmt->execute();
	$newURL = $hostname . $shortUrl;

	echo "Shortened url is : "; 
	echo '<a href="' . $newURL . '">' . $newURL . '</a><br>';	
	
	$stmt->close();
	$mysqli->close();
	
?>
