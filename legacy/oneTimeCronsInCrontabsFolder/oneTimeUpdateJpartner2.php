<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");

$jpartnerObj=new Jpartner;
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
	mysql_query('set session wait_timeout=50000',$shDbS);
	$sqlm = "SELECT p.PROFILEID FROM PROFILEID_SERVER_MAPPING AS p LEFT JOIN JPARTNER AS j ON p.PROFILEID = j.PROFILEID WHERE j.PROFILEID IS NULL";
        $resm = mysql_query($sqlm,$shDbS) or die($sqlm.mysql_error($shDbS));
        while($rowm = mysql_fetch_array($resm))
        {
		$profileid = $rowm['PROFILEID'];
		$sql1 = "SELECT AGE,HEIGHT,GENDER,MTONGUE,RELIGION,MSTATUS,CASTE FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
		$res1 = mysql_query($sql1,$myDb) or die($sql1.mysql_error($myDb));
		if($row1 = mysql_fetch_array($res1))
		{
			$age = $row1['AGE'];
			$height = $row1['HEIGHT'];
			$gender = $row1['GENDER'];
			$mtongue = $row1['MTONGUE'];
			$religion = $row1['RELIGION'];
			$mstatus = $row1['MSTATUS'];
			$caste = $row1['CASTE'];
		}
                $jpartnerObj->setPROFILEID($profileid);
                if($gender=='M')
                     $jpartnerObj->setGENDER('F');
                else
                     $jpartnerObj->setGENDER('M');
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
		$jpartnerObj->setLAGE($lage);
                $jpartnerObj->setHAGE($hage);

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

                $jpartnerObj->setLHEIGHT($lheight);
                $jpartnerObj->setHHEIGHT($hheight);

		$MTONGUE=array(10,7,33,19,28,13);
	
		if(in_array($mtongue,$MTONGUE))
                {
                        foreach($MTONGUE as $key=>$val)
                                $mtongue_val.="'".$val."',";
                        $mtongue_val=substr($mtongue_val,0,strlen($mtongue_val)-1);
                }
                else
                        $mtongue_val="'".$mtongue."'";

                $jpartnerObj->setPARTNER_MTONGUE($mtongue_val);

                $religion_partner="'".$religion."'";
                $jpartnerObj->setPARTNER_RELIGION($religion_partner);

                $caste_community = $caste."-".$mtongue;
                $sql = "SELECT MAP FROM newjs.CASTE_COMMUNITY_MAPPING WHERE CASTE_COMMUNITY = '$caste_community'";
                $res = mysql_query($sql,$myDb) or die($sql.mysql_error($myDb));
                $row = mysql_fetch_assoc($res);
                if($row)
                {
                        $caste_community_arr = @explode(",",$row['MAP']);
                        for($i=0;$i<count($caste_community_arr);$i++)
                        {
                                $temp_caste_arr = @explode("-",$caste_community_arr[$i]);
                                if(!@in_array($temp_caste_arr[0],$mapped_caste_arr))
                                        $mapped_caste_arr[] = $temp_caste_arr[0];
                        }
                }

                if(is_array($mapped_caste_arr))
                {
                        if(!in_array($caste,$mapped_caste_arr))
                              $mapped_caste_arr[]=$caste;
                }
                else
                              $mapped_caste_arr[]=$caste;

                $mapped_caste="'".@implode("','",$mapped_caste_arr)."'";
		$jpartnerObj->setPARTNER_CASTE($mapped_caste);

                if($mstatus=="N")
                        $jpartnerObj->setPARTNER_MSTATUS("'".$mstatus."'");
                $jpartnerObj->updatePartnerDetails($shDbM,$mysqlObj);
		unset($mapped_caste_arr);
		unset($mtongue_val);
        }
}
?>
