<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


include("connect.inc");

$db=connect_ddl();

$today=time();
$today-=4*24*60*60;
$today=date("Y-m-d",$today);

$sql="RENAME TABLE MIS.HITS TO MIS.HITSTEMP";
if(mysql_query($sql))
{
	$sql="CREATE TABLE MIS.HITS (
 `SourceID` varchar(10) NOT NULL default '',
 `Date` datetime NOT NULL default '0000-00-00 00:00:00',
 `PageName` varchar(255) NOT NULL default '',
 `IPADD` varchar(15) NOT NULL default '',
 KEY `SourceID` (`SourceID`),
 KEY `Date` (`Date`)
) ENGINE=MyISAM";
	if(mysql_query($sql))
	{
		$sql="INSERT INTO MIS.HITSOLD SELECT * FROM MIS.HITSTEMP WHERE DATE<='$today 23:59:59'";
		if(mysql_query($sql))
		{
			$sql="DELETE FROM MIS.HITSTEMP WHERE DATE<='$today 23:59:59'";
			mysql_query($sql) or logError($sql);
			$sql="INSERT INTO MIS.HITSTEMP SELECT * FROM MIS.HITS";
			if(mysql_query($sql))// or logError($sql);
			{
				$sql="DROP TABLE MIS.HITS";
				mysql_query($sql) or logError($sql);
				$sql="RENAME TABLE MIS.HITSTEMP TO MIS.HITS";
				mysql_query($sql) or logError($sql);
			}
			else
			{
				mail("shiv.narayan@jeevansathi.com","HITS TABLE PROBLEM","Cud not insert into HITSTEMP from HITS. Did not drop HITS.".mysql_error());
				logError($sql);
			}
		}
		else
		{
			mail("shiv.narayan@jeevansathi.com","HITS TABLE PROBLEM","Cud not insert into HITSOLD from HITSTEMP.".mysql_error());
			logError($sql);
		}
	}
	else
	{
		mail("shiv.narayan@jeevansathi.com","HITS TABLE PROBLEM","Cud not create HITSTEMP.".mysql_error());
		logError($sql);
	}
}
else
{
	mail("shiv.narayan@jeevansathi.com","HITS TABLE PROBLEM","Cud not rename HITS to HITSTEMP".mysql_error());
	logError($sql);
}
?>
