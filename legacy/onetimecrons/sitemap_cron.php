<?php
  $curFilePath = dirname(__FILE__)."/";
 include_once("/usr/local/scripts/DocRoot.php");

chdir("$_SERVER[DOCUMENT_ROOT]/profile");

include_once("connect.inc");
include_once("dropdowns.php");
$db_slave=connect_db();


ini_set('max_execution_time','0');
ini_set("memory_limit","-1");
mysql_query('set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000',$db_slave);


global $profile_url,$REGISTER_DATE,$count;
$count=0;

$daily=$argv[1];

$end_dt=date("Y-m-d 23:59:59", time()-86400);
$st_dt=date("Y-m-d 00:00:00", time()-86400*3);
$st_dt_2=date("Y-m-d 23:59:59", time()-86400*4);

$x=35000; // Declare the Number of Records you want to write in a single fiile
$ce=1;
	
	// Start Writing in Index Files

	
	try
	{
		$fname="sitemap_index_seopages";
		$fhandle=fopen("$_SERVER[DOCUMENT_ROOT]/$fname.xml","a");
	}
	catch(Exception $e)
	{
		echo 'ERROR IN CREATING FILE '.$fName;	
	}
	
	$fhead="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
	try
	{
		fwrite($fhandle, $fhead);
	}
	
	catch(Exception $e)
	{
		echo "ERROR IN WRITING END IN FILE ".$fName;
	}

	if(1)
	{
		UpdateXls("COMMUNITY_PAGES",$fhandle);
		updateXls("COMMUNITY_PAGES_MAPPING",$fhandle);
		
	}

	$fhead='</urlset>';
	try
	{
		fwrite($fhandle, $fhead);
	}
	catch(Exception $e)
	{
		echo "ERROR IN WRITING END IN FILE ".$fName;
	}
	
	fclose($fhandle);
	//die;
	if(1){
		
		$ping=urlExists('www.google.com/webmasters/tools/ping?sitemap=http%3A%2F%2Fwww.jeevansathi.com%2F'.$fname.'.xml');
		while(!$ping){
			sleep(10);      /* Make a delay of 10 second for another request for submission */
			$ping=urlExists('www.google.com/webmasters/tools/ping?sitemap=http%3A%2F%2Fwww.jeevansathi.com%2F'.$fname.'.xml');
			if($ping_count==10){
				mail("vivek.rathore@naukri.com,anirban.das@naukri.com,nikhil.dhiman@jeevansathi.com","Alert: Jeevansathi.com Daily Sitemap has not been Submitted. Kindly Check.", date("Y-m-d"));
				exit();
			}
			$ping_count++;
		}
		if($ping)
			mail("vivek.rathore@naukri.com,anirban.das@naukri.com,nikhil.dhiman@jeevansathi.com","Jeevansathi.com Daily Sitemap has been Submitted", date("Y-m-d"));
	}
	
function UpdateXls($table,$fhandle)
{
	
	global $SITE_URL;
	$sql="select concat_ws('','$SITE_URL',URL) as URL from newjs.$table where ACTIVE='Y' and FOLLOW='Y'";
	$res=mysql_query_decide($sql) or die(mysql_error_js());
	while($row=mysql_fetch_assoc($res))
	{
		$url=$row[URL];
		$str="<url>\n<loc>$url</loc>\n<lastmod>".date('Y-m-d')."</lastmod>\n<changefreq>daily</changefreq>\n<priority>1</priority></url>\n";
		try
	{
		fwrite($fhandle, $str);
	}
	catch(Exception $e)
	{
		echo "ERROR IN WRITING END IN FILE ".$fName;
	}
	
	}

}
function urlExists($url)
  
{
	return true;  
    if($url==NULL)
    	return false;  
    $ch = curl_init($url);  
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);  
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);  
    if($httpcode>=200 && $httpcode<300)
    {  
            return true;  
    }
    else 
    {  
            return false;  
    }  
}

?>
