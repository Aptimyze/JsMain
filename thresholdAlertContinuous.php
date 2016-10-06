<?php


$username = 'admin';
$password = 'admin';
$domain = 'http://10.10.18.67:9090/';
$loginUrl = $domain.'login.jsp';
$param = 'url=%2Findex.jsp&login=true&username='.$username.'&password='.$password;
$fetchPagePath = $domain.'index.jsp';
$savePagePath = '/home/developer/download.html';
$searchPattern = ') used';
$alertThreshold = 85;
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
function saveFile(){
    global $loginUrl, $param, $fetchPagePath, $savePagePath;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $loginUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $store = curl_exec($ch);
    curl_setopt($ch, CURLOPT_URL, $fetchPagePath);

    $content = curl_exec($ch);
    file_put_contents($savePagePath, $content);

}


function checkFile($alertCounter) {
    global $savePagePath, $searchPattern, $alertThreshold,$sleepTimeout;
    $handle = @fopen( $savePagePath, 'r' );
    if( $handle ) {
        while( ($line = fgets($handle)) !== false ) {
            if( stripos($line, $searchPattern) === false ) {
                continue;
            } else {
                fclose($handle);
                $start = strpos($line, '(');
                $end = strpos($line, '%');
                $temp = substr($line, $start+1); 
                $percentageVlaue = substr($temp,0,($end-$start-1));
                print_r($percentageVlaue."\n");
                if($percentageVlaue > $alertThreshold){
                    if($alertCounter >= 3){
                        sendMail($percentageVlaue);
                        clearCache();
                        return;
                    }
                    sleep($sleepTimeout);
                    $alertCounter++;
                    saveFile();
                    checkFile($alertCounter);
                }
            }
        }
    }
}

function clearCache(){
	global $domain, $cookieFile;
	for($i = -1; $i<=33; $i++){
        $cacheID[] = $i;
    }
 
	global $loginUrl, $param, $fetchPagePath, $savePagePath;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $loginUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $store = curl_exec($ch);
    //curl_setopt($ch, CURLOPT_URL, $fetchPagePath);

    //$content = curl_exec($ch);
    //file_put_contents($savePagePath, $content);


    $POST = substr(join("&cacheID=", $cacheID),3);    
    $POST = $POST."&clear=Clear+Selected";
    $page_ = $domain."system-cache.jsp";
    //print_r($POST);
    //$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $page_);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $POST);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    //curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    $data = curl_exec($ch);
    //print_r($data);
    curl_close($ch);
}

function sendMail($usagePercentage){
	global $domain, $environment,$sleepTimeout;
	if($environment == "prod"){

	     //mail("lavesh.rawat@gmail.com/*,pankaj139@gmail.com*/,nsitankita@gmail.com,nitishpost@gmail.com,vibhor.garg@jeevansathi.com","Openfire memory usage on ".$domain." after ".($sleepTimeout*3)."sec @ ".$usagePercentage."%","Please check");
	    mail("nitishpost@gmail.com","Openfire memory usage on ".$domain."@ ".$usagePercentage."%","Please check");
	}
	elseif($environment == "dev"){
		print_r("\n***Done***\n");
	}
}
while(1){
    sleep(3);
    saveFile();
    checkFile(0);
}

?>
