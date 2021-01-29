<?php

    $date = date("d/m/Y");
    $path = "http://www.cbr.ru/scripts/XML_daily.asp?date_req=$date";

    print_r( "GET : " . $path . "\n" );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$path);
    curl_setopt($ch, CURLOPT_FAILONERROR,1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $retValue = curl_exec($ch);
    curl_close($ch);

    $data = new SimpleXMLElement( $retValue );



    foreach ($data as $currency) {
        print_r( $currency );
    }




