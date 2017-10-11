<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");

$mysqlObj=new Mysql;
global $noOfActiveServers;

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
	$myDb=connect_737();
	$shDbNameM=getActiveServerName($activeServerId,"master");
	$shDbNameS=getActiveServerName($activeServerId,"slave");
	$shDbM=$mysqlObj->connect($shDbNameM);
	$shDbS=$mysqlObj->connect($shDbNameS);
	mysql_query('set session wait_timeout=50000',$myDb);
	mysql_query('set session wait_timeout=50000',$shDbM);
	$sql_main = "SELECT PROFILEID,LAGE,HAGE,LHEIGHT,HHEIGHT,PARTNER_MTONGUE,PARTNER_RELIGION FROM newjs.JPARTNER WHERE LAGE=0 OR HAGE=0 OR LHEIGHT=0 OR HHEIGHT=0 OR PARTNER_MTONGUE='' OR PARTNER_RELIGION=''";
	$res_main = mysql_query($sql_main,$shDbS) or die($sql_main.mysql_error($shDbS));
        while($row = mysql_fetch_array($res_main))
        {
		$pid = $row['PROFILEID'];
		$lage = $row['LAGE'];
		$hage = $row['HAGE'];
		$lht = $row['LHEIGHT'];
		$hht = $row['HHEIGHT'];
		$pmtongue = $row['PARTNER_MTONGUE'];
		$preligion = $row['PARTNER_RELIGION'];
		$sql1 = "SELECT GENDER,AGE,HEIGHT,MTONGUE,RELIGION FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
		$res1 = mysql_query($sql1,$myDb) or die($sql.mysql_error($myDb));
		if($row1 = mysql_fetch_array($res1))
		{
			$age = $row1['AGE'];
			$height = $row1['HEIGHT'];
			$gender = $row1['GENDER'];
			$mtongue = $row1['MTONGUE'];
			$religion = $row1['RELIGION'];
		}
		$count = 0 ;
		$sql_up = "update newjs.JPARTNER set";
		if(!$lage || !$hage)
		{
			if($gender=='M')
			{
				if($age<25)
					$lage=18;
			       	else
					$lage=$age-5;
				$hage=$age;
			}
			else
			{
				$lage=($age>29)?$age-2:(($age>26)?$age-1:(($age>22)?$age:21));
				$hage=($age>33)?$age+15:(($age==33)?47:(($age==32)?44:(($age==31)?42:$age+10)));
			}
			$sql_up .= " LAGE='$lage',HAGE='$hage'";
			$count=1;
		}
		if(!$lht || !$hht)
		{
			if($gender=='M')
			{
        	                $lheight=$height-10;
                	        $hheight=$height;
			}
			else
			{
                        	$lheight=$height;
	                        $hheight = $height+10;
			}
			if($count)
				$sql_up .= ",LHEIGHT='$lheight',HHEIGHT='$hheight'";
			else
			{
				$sql_up .= " LHEIGHT='$lheight',HHEIGHT='$hheight'";
				$count=1;
			}
		}
		if(!$pmtongue && $mtongue!='')
		{
			$MTONGUE=array(10,7,33,19,28,13);

		        if(in_array($mtongue,$MTONGUE))
                	{
                        	foreach($MTONGUE as $key=>$val)
                                	$mtongue_val.="'".$val."',";
	                        $mtongue_val=substr($mtongue_val,0,strlen($mtongue_val)-1);
        	        }
                	else
                        	$mtongue_val=$mtongue;

			if($count)
				$sql_up .= ",PARTNER_MTONGUE=\"$mtongue_val\"";
			else
			{
        	        	$sql_up .= " PARTNER_MTONGUE=\"$mtongue_val\"";
				$count=1;
			}
		}
                if(!$preligion && $religion!='')
		{
			$religion = "''".$religion."''";
			if($count)
				$sql_up .= ",PARTNER_RELIGION='$religion'";
			else
			{
				$sql_up .= " PARTNER_RELIGION='$religion'";
				$count=1;
			}
		}
		if($count)
		{
			$sql_up .= " where PROFILEID='$pid'";
                	mysql_query($sql_up,$shDbM) or die($sql_up.mysql_error($shDbM));
		}
		unset($mtongue_val);
        }
}
?>
