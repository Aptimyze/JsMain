<?php

$dirname=dirname(__FILE__);
chdir($dirname);

include_once("connect.inc");

$db_slave=connect_slave();

ini_set('max_execution_time','0');
ini_set("memory_limit","-1");
mysql_query('set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000',$db_slave);

$file_path=JsConstants::$docRoot.'/seopages/';

global $profile_url,$REGISTER_DATE,$count;
$count=0;

$sql="SELECT URL FROM newjs.SEO";
$res=mysql_query($sql,$db_slave) or die("Error on the SiteMap seopages Cron 1st sql Query");
while($row=mysql_fetch_array($res))
{
		$serUrl=$row['URL'];
		$subDomain_array=explode(".",$serUrl);
		$subDomain=$subDomain_array[0];

		$fName="sitemap_temp_$subDomain".".xml";
		
		try
		{	
			$fhandle=fopen($GLOBALS['file_path'].$fName,"wb");
		} 
		catch(Exception $e)
		{
			echo 'ERROR IN CREATING FILE '.$fName;
		}

		$fhead="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
		try
		{ 
			fwrite($fhandle, $fhead);
		}
		catch(Exception $e)
		{
			echo "ERROR IN WRITING HEADER IN FILE ".$fName;
		}
		
		$pri="0.8";
		$url="http://".$serUrl;
		
		$fbody="\n<url>\n<loc>$url</loc>\n<lastmod>".date("Y-m-d")."</lastmod>\n<changefreq>weekly</changefreq>\n <priority>$pri</priority>\n </url>";
		try
		{
			fwrite($fhandle, $fbody);
		}
		catch(Exception $e)
		{
			echo "ERROR IN WRITING BODY cnt".$recCount." IN FILE ".$fName;
		}

		$fend="\n</urlset>";

		try
		{
			fwrite($fhandle, $fend);
		}
		catch(Exception $e)
		{
			echo "ERROR IN WRITING END IN FILE ".$fName;
		}
		fclose($fhandle);
	
		rename($file_path.$fName,$file_path."sitemap_$subDomain.xml");
		
}
		mail("anurag.gautam@jeevansathi.com","Jeevansathi Seo Sitemap has been generated", date("Y-m-d"));
?>
