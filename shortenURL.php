<?php
	function shortenURL($url)
	{
		// Convert by random of md5 and than base64
		$short = substr(base64_encode(md5($url.mt_rand())), 0, 4);
		return $short;
	}
?>
 