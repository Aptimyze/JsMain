<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

$handle = curl_init();
curl_setopt($handle, CURLOPT_POST      ,1);
curl_setopt($handle, CURLOPT_RETURNTRANSFER    , true);
curl_setopt($handle, CURLOPT_HEADER, 1);
curl_setopt($handle, CURLOPT_MAXREDIRS        , 5);
curl_setopt($handle, CURLOPT_FOLLOWLOCATION    , true);
curl_setopt($handle, CURLOPT_USERAGENT        , 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.9.1.4) Gecko/20091016 Firefox/3.5.4');                 
curl_setopt($handle,CURLOPT_COOKIEJAR,"/var/www/htmlrevamp/branches/crawler/crontabs/crawler/cookiefile_run.txt");
curl_setopt($handle, CURLOPT_URL, "http://www.shaadi.com/registration/user/login2.php");
curl_setopt($handle, CURLOPT_POSTFIELDS    ,"login=smita12_1987%40yahoo.com&password=smita12");
curl_exec($handle);
curl_setopt($handle, CURLOPT_URL, "http://ww2.shaadi.com/search/matrimonial/result");
curl_setopt($handle, CURLOPT_POSTFIELDS    ,"agefrom=23&ageto=25&countryofresidence=India&gender=Female&mothertonguearray%5B%5D=Hindi");
curl_setopt($handle,CURLOPT_COOKIEFILE,"/var/www/htmlrevamp/branches/crawler/crontabs/crawler/cookiefile_run.txt");
$response=curl_exec($handle);
curl_setopt($handle, CURLOPT_URL, "http://www.shaadi.com/registration/user/logout.php");
curl_setopt($handle,CURLOPT_COOKIEFILE,"/var/www/htmlrevamp/branches/crawler/crontabs/crawler/cookiefile_run.txt");
curl_exec($handle);
curl_close($handle);
echo $response;
?>
