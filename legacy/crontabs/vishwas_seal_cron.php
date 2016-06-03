<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

		include "connect.inc";
		$db=connect_db();
		$db1=connect_slave();

		mysql_query("set session wait_timeout=100000",$db);
		mysql_query("set session wait_timeout=100000",$db1);

		//$sql ="SELECT count(j.PROFILEID) FROM newjs.JPROFILE j LEFT JOIN newjs.VISHWAS_SEAL v ON j.PROFILEID=v.PROFILEID, jsadmin.ADDRESS_VERIFICATION a, newjs.VERIFY_EMAIL e WHERE j.PROFILEID = a.PROFILEID AND j.PROFILEID = e.PROFILEID AND (j.MOB_STATUS = 'Y' OR j.LANDL_STATUS = 'Y') AND a.SCREENED = 'Y' AND e.STATUS = 'Y' and v.PROFILEID is null";

		$sql ="SELECT j.PROFILEID as PROFILEID,j.MOB_STATUS as MOB_STATUS,j.LANDL_STATUS as LANDL_STATUS FROM newjs.JPROFILE j, jsadmin.ADDRESS_VERIFICATION a, newjs.VERIFY_EMAIL e WHERE j.PROFILEID NOT IN (select PROFILEID from newjs.VISHWAS_SEAL) and j.PROFILEID = a.PROFILEID AND j.PROFILEID = e.PROFILEID AND a.SCREENED = 'Y' AND e.STATUS = 'Y'";
		$res=mysql_query_decide($sql,$db1) or logError("Due to a temporary problem your request could not be processed.",$sql);
		while($row=mysql_fetch_array($res))
		{
			$profileidArr[$row['PROFILEID']]['MOB_STATUS'] =$row['MOB_STATUS'];
			$profileidArr[$row['PROFILEID']]['LANDL_STATUS'] =$row['LANDL_STATUS'];
		}
		foreach($profileidArr as $profileid=>$profileDetails)
		{
			if($profileDetails['MOB_STATUS']=='Y' || $profielDetails['LANDL_STATUS']=='Y')
				$finalProfiles[]=$profileid;
			else
				$checkAlternateNumberForProfiles[]=$profileid;
		}
		if($checkAlternateNumberForProfiles)
		{
			$pStr=implode("','",$checkAlternateNumberForProfiles);
			$pStr="'".$pStr."'";
			$sqlAltCheck="SELECT PROFILEID FROM newjs.JPROFILE_CONTACT WHERE ALT_MOB_STATUS='Y' AND PROFILEID IN (".$pStr.")";
			$resAltCheck=mysql_query_decide($sqlAltCheck,$db1) or logError("Due to a temporary problem your request could not be processed.",$sqlAltCheck);
			while($rowAltCheck=mysql_fetch_array($resAltCheck))
			{
				$finalProfiles[]=$rowAltCheck['PROFILEID'];
			}
		}
		foreach($finalProfiles as $k=>$pid)
		{	
			$profileid=$pid;
	        	$date=date('Y-m-d');
	                $sql_vs="REPLACE INTO newjs.VISHWAS_SEAL (PROFILEID,ENTRY_DT,STATUS) VALUES ($profileid,'$date','Y')";
	                $res_vs=mysql_query_decide($sql_vs,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_vs);
	       	}
?>
