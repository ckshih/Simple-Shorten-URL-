<?php
    include_once('sqliconfig.php');

    $sizeOfUrlArr = 0;
    $urlArr = array();
    $response_map = array();

    $sql = "SELECT `oriURL` FROM $tbName WHERE 1";
	$stmt = $mysqli->prepare($sql);
	$stmt->execute();
	$stmt->bind_result($ori);

    // Get Url from db and store back to array
    //while(($row = mysql_fetch_row($result)) != NULL)
    while($stmt->fetch())
    {
        $url = $ori;
        
        if(strpos($url, "://")== NULL)
            $url = "http://" . $url;    

        $urlArr[]  = $url;
        $sizeOfUrlArr ++;
    }
    
    $multiHandler = curl_multi_init();
    $i = 0;
    // Use Curl to get http code
    foreach($urlArr as $url) 
    {
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'HEAD');
        curl_multi_add_handle($multiHandler, $ch);    

        $response_map[$i++] = $ch;
    }

    $still_running = 0;
    curl_multi_exec($multiHandler, $still_running);

    // Waiting for Curl response
    $running = 0;
    do {
        curl_multi_exec($multiHandler, $running);
        curl_multi_select($multiHandler);
    } while ($running > 0);

    // Check HTTP code
    for($i=0; $i<$sizeOfUrlArr; $i++)
    {
        $response = $response_map[$i];

        $httpCode = curl_getinfo($response, CURLINFO_HTTP_CODE);
        if($httpCode >= 400 && $httpCode < 500 || $httpCode == 0)
            echo " [" . $urlArr[$i] . "]". " is broken url with http code: " .$httpCode . "<br>";

        curl_multi_remove_handle($multiHandler, $response);

    }
    
    $stmt->close();
	$mysqli->close();
    curl_multi_close($multiHandler);
    
?>