<?php
    include_once('sqlconfig.php');


    $sql = "SELECT `oriURL` FROM `$tbName` WHERE 1";
    $result = mysql_query($sql, $connect);
    $sizeOfUrlArr = 0;

    $urlArr = array();
    $response_map = array();

    // Get Url from db and store back to array
    while(($row = mysql_fetch_row($result)) != NULL)
    {
        $url = $row[0];
        
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

    curl_multi_close($multiHandler);
    
?>