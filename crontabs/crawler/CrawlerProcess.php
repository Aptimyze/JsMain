<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include_once("CrawlerCommon.php");

//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
//for preventing timeout to maximum possible

$mysqlObj=new Mysql;
$db=$mysqlObj->connect('crawler');
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);

$siteId=$_SERVER['argv'][1];

$lock = get_lock('/tmp/crawlerprocess_'.$siteId);

while(1)
{
	$sql="SELECT PROCESS_ID FROM crawler.crawler_process WHERE SITE_ID=$siteId";
	$res=$mysqlObj->executeQuery($sql,$db);
	$row=mysql_fetch_assoc($res);
	$id=$row["PROCESS_ID"];
	$processRunning=checkProcessRunning($id,$siteId);
	if(!$processRunning)
	{
		switch($id)
		{
			case 1 : $cmd="php -q $_SERVER[DOCUMENT_ROOT]/crontabs/crawler/CrawlerSearch.php $siteId";
				break;
		
			case 2 : $cmd="php -q $_SERVER[DOCUMENT_ROOT]/crontabs/crawler/CrawlerDetailView.php $siteId";
				break;

			case 3 : $cmd="php -q $_SERVER[DOCUMENT_ROOT]/crontabs/crawler/CrawlerDeDupe.php $siteId";
				break;

			case 4 : $cmd="php -q $_SERVER[DOCUMENT_ROOT]/crontabs/crawler/CrawlerContactDetailView.php $siteId";
				break;
		}
		if($cmd)
			passthru($cmd);
		if($id==4)
			break;
	}
}
?>
