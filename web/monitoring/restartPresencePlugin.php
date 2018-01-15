<?php


$username = 'admin';
$password = 'admin';
$domain = 'http://10.10.18.67:9090/';
$loginUrl = $domain.'login.jsp';
$param = 'url=%2Findex.jsp&login=true&username='.$username.'&password='.$password;
$fetchPagePath = $domain.'index.jsp';
$savePagePath = '/home/developer/download.html';
$searchPattern = ') used';
$alertThreshold = 80;
$sleepTimeout = 1;
$environment = "prod";
$cookieFile = 'cookie.txt';

/*
$username = 'admin';
$password = 'admin';
$domain = 'http://localhost:9090/';
$loginUrl = $domain.'login.jsp';
$param = 'url=%2Findex.jsp&login=true&username='.$username.'&password='.$password;
$fetchPagePath = $domain.'index.jsp';
$savePagePath = '/home/nitish/Desktop/download.html';
$searchPattern = ') used';
$alertThreshold = 1;
$sleepTimeout = 1;
$environment = "dev";
$cookieFile = 'cookie.txt';
*/

function disableEnablePlugin($flag){
    global $loginUrl, $param, $fetchPagePath, $savePagePath, $domain;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $loginUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $store = curl_exec($ch);

    $POST = "presenceenabled=".$flag."&presenceapi=http%3A%2F%2F10.10.18.67%3A8290%2Fprofile%2Fv1%2Fpresence&save=Save+settings";
    $page_ = $domain."plugins/jspresencemanager/jspresencemanager-props-edit-form.jsp";
    curl_setopt($ch, CURLOPT_URL, $page_);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $POST);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    //curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    $data = curl_exec($ch);
    curl_close($ch);
	//mail("nitish.sharma@jeevansathi.com","Plugin Restarted ".$domain."@ ".$usagePercentage."%","Please check");
}

disableEnablePlugin("false"); //Disable
disableEnablePlugin("true"); //Enable
?>
