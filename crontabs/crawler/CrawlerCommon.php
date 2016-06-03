<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

$SITE_URL="http://crawler.jeevansathi.com";
$leadCreationSiteURL="http://crawler.jeevansathi.com";
$mappingFileBasePath="/home/sadaf/work/crawler/mapping";
$errorResponseFileBasePath=$_SERVER['DOCUMENT_ROOT']."/crontabs/crawler/error_response";
$sleepTime=10;
$maxSearchPagesPerUser=15;
$maxDetailViewsPerUser=40;
$maxContactViewsPerUser=20;
$errorReporting=array();

include_once($_SERVER['DOCUMENT_ROOT']."/classes/Crawler.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/CrawlerSite.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/CrawlerUser.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/CrawlerCompetitionProfile.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/CrawlerPriorityCommunity.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/CrawlerURL.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/CrawlerErrorHandler.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/CrawlerConnection.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");

function checkProcessRunning($id,$siteId)
{
	exec('ps ax | grep php',$processArr);
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
	{
		foreach($processArr as $processString)
		{
			$position=stripos($processString,$cmd);
			if(is_numeric($position))
				return 1;
		}
		return 0;
	}
}

function startProcess($processId,$siteId)
{
	echo "\nstarting process $processId";
	setProcess($processId,$siteId);
}

function endProcess($processId,$siteId)
{
	if($processId)
	{
		echo "\n ending prociess $processId";
		switch($processId)
		{
			case 1 : $newProcessId=2;
				break;
			
			case 2 : $newProcessId=3;
				break;

			case 3 : $newProcessId=4;
				break;

			case 4 : $newProcessId=1;
				break;
		}
		echo "\n new process $newProcessId";
		if($newProcessId)
			setProcess($newProcessId,$siteId);
	}
}

function setProcess($processId,$siteId)
{
	if($processId)
	{
		$mysqlObj=new Mysql;
                $db=$mysqlObj->connect('crawler') or die(mysql_error());
		//@mysql_ping($db);

                $sql="UPDATE crawler.crawler_process SET PROCESS_ID='$processId' WHERE SITE_ID='$siteId'";
		echo "\n".$sql;
                $res=$mysqlObj->executeQuery($sql,$db);
	}
}

function getUserAge($lage,$hage,$profilesGender)
{
	if($profilesGender=='M')
	{
		$ageArr["LAGE"]=$lage-5>=18?$lage-5:18;
		$ageArr["HAGE"]=$lage;
	}
	if($profilesGender=='F')
	{
		$ageArr["LAGE"]=$hage;
		$ageArr["HAGE"]=$hage+5;
	}
	return $ageArr;
}

function getFormattedDate($date,$format)
{
        if($date)
        {
                switch($format)
                {
                        case 'dd-mmm-yyyy' :
                                list($dt,$month,$year)=explode("-",$date);
                                $mon=getMonthNum($month);
				if(strlen($dt)==1)
					$dt='0'.$dt;
                                if($mon && $year && $dt)
                                        return $year."-".$mon."-".$dt;
                                break;
                }
        }
}

function getMonthNum($month)
{
        if($month)
        {
                switch($month)
		{
                        case 'Jan':
                                $mon='01';
                                break;
                        case 'Feb':
                                $mon='02';
                                break;
                        case 'Mar':
                                $mon='03';
                                break;
                        case 'Apr':
                                $mon='04';
                                break;
                        case 'May':
                                $mon='05';
                                break;
                        case 'Jun':
                                $mon='06';
                                break;
                        case 'Jul':
                                $mon='07';
                                break;
                        case 'Aug':
                                $mon='08';
                                break;
                        case 'Sep':
                                $mon='09';
                                break;
                        case 'Oct':
                                $mon='10';
                                break;
                        case 'Nov':
                                $mon='11';
                                break;
			case 'Dec':
                                $mon='12';
                                break;
                }
                if($mon)
                {
                        return $mon;
                }
                else
                        return 0;
        }

}

function generateErrorReport($action)
{
	global $errorReporting;
	$errorReportMail=1;

	//var_dump($errorReporting);
	
	if(is_array($errorReporting) && count($errorReporting))
	{
		$subject="Crawler Error Report for $action on ".date("d-m-Y");
		foreach($errorReporting as $category=>$valueArr)
		{
			if($category=='FAILED_SWITCHES')
			{
				$errorReport.="\n\nFAILED CONNECTION SWITCHES ";
				foreach($valueArr as $connection=>$switchArr)
				{
					if($switchArr[1])
						$errorReport.="\nConnecting $connection : ".$switchArr[1];
					if($switchArr[0])
						$errorReport.="\nDisconnecting $connection : ".$switchArr[0];
				}
			}
			elseif($category=='FAILED_SESSIONS')
			{
				$errorReport.="\n\nFailed sessions due to no connection to data card : ".$valueArr;
			}
			elseif($category=='NO_USER')
			{
				$errorReport.="\n\nNo users found on following sites, community wise ";
				foreach($valueArr as $siteId=>$communityIdArr)
					$errorReport.="\nFor site $siteId : ".implode(",",$communityIdArr);
			}
			elseif($category=='USER_CREDITS_OVER')
			{
				$errorReport.="\n\nCredits for following users have expired";
				foreach($valueArr as $siteId=>$accountIdArr)
					$errorReport.="\nFor Site $siteId : ".implode(",",$accountIdArr);
			}
			elseif($category=='CRAWL_ERROR')
			{
				$errorReport.="\n\nCRAWLER ERRORS : The following urls encountered crawler errors";
				foreach($valueArr as $siteId=>$actionSessionIdArr)
				{
					$errorReport.="\nFor Site $siteId : ";
					foreach($actionSessionIdArr as $siteAction=>$sessionIdArr)
						$errorReport.="\n$siteAction : ".implode(",",$sessionIdArr);
				}
			}
			elseif($category=='UNEXPECTED_RESPONSE')
			{
				$errorReport.="\n\nUNEXPECTED RESPONSE  : The following urls returned unexpected responses";
				foreach($valueArr as $siteId=>$actionSessionIdArr)
				{
					$errorReport.="\nFor Site $siteId : ";
					foreach($actionSessionIdArr as $action=>$sessionIdArr)
						$errorReport.="\n$action : ".implode(",",$sessionIdArr);
				}
			}
                        elseif($category=='NO_REG_MATCH')
			{
				$errorReport.="\n\nNO REGULAR EXPRESSION MATCH : ";
				foreach($valueArr as $siteId=>$sessionArr)
				{
					if(is_array($sessionArr) && count($sessionArr))
					{
						$errorReport.="\n\nFor Site $siteId";
						unset($fieldNameCountArr);
						foreach($sessionArr as $sessionId=>$fieldNameArr)
						{
							if($sessionId)
								$errorReport.="\n\nSession Id $sessionId : ".implode(",",$fieldNameArr);
							foreach($fieldNameArr as $fieldName)
								$fieldNameCountArr[$fieldName]++;
						}
						$errorReport.="\n\nTotal number of failed regular expressions match per field for site $siteId : ";
						foreach($fieldNameCountArr as $fieldName=>$fieldNameCount)
							$errorReport.="\n$fieldName  :  $fieldNameCount";
					}
				}
			}
			elseif($category=='NO_MAPPING')
			{
				$errorReport.="\n\nNO MAPPINGS FOUND : ";
				foreach($valueArr as $siteId=>$sessionArr)
				{
					if(is_array($sessionArr) && count($sessionArr))
					{
						$errorReport.="\n\nFor Site $siteId";
						unset($fieldLabelCountArr);
						foreach($sessionArr as $sessionId=>$fieldNameArr)
						{
							$errorReport.="\n\nSession Id $sessionId : ";
							foreach($fieldNameArr as $fieldName=>$fieldLabelArr)
							{
								$errorReport.="\n$fieldName  :  ".implode(",",$fieldLabelArr);
								foreach($fieldLabelArr as $key=>$fieldLabel)
									$fieldLabelCountArr[$fieldName][$fieldLabel]++;
							}
		
						}
						$errorReport.="\n\nTotal number of failed mappings per field for site $siteId : ";
                                        	foreach($fieldLabelCountArr as $fieldName=>$fieldLabelArr)
						{
							$errorReport.="\n\nField Name : $fieldName";
							foreach($fieldLabelArr as $label=>$labelCount)
		                                                $errorReport.="\n$label  :  $labelCount";
						}
					}
				}
			}
		}
		if($errorReport && $errorReportMail)
		{
			echo $errorReport;
			mail("prinka.wadhwa@jeevansathi.com , vikas.jayna@jeevansathi.com",$subject,$errorReport);
		}
	}
}

function get_lock($file)
{
	$fp=fopen($file . ".lock","w+");

	if($fp)
	{
		$gotlock=flock($fp,LOCK_EX + LOCK_NB);
		if(!$gotlock)
		{
			echo "cannot get lock. exiting";
			fclose($fp);
			exit;
		}
	}
	else
	{
		echo "cannot get lock. exiting";
		exit;
	}

	return $fp;
}

?>
