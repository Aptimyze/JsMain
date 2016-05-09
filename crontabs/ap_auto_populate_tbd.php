<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

$flag_using_php5=1;
include("connect.inc");
include($_SERVER['DOCUMENT_ROOT']."/jsadmin/ap_common.php");

$db=connect_db();

$sql="SELECT PROFILEID FROM Assisted_Product.AP_SERVICE_TABLE WHERE NEXT_SERVICE_DATE=CURDATE()";
$res=mysql_query($sql,$db) or die("Error while fetching profiles  ".$sql."  ".mysql_error($db));
if(mysql_num_rows($res))
{
	while($row=mysql_fetch_assoc($res))
	{
		$profileid=$row["PROFILEID"];
		$profileArray=array($profileid);
		$listArray=array('TBD');
		$countArray=getNumberInList($profileArray,$listArray);
		if($countArray[$profileid]['TBD']<5)
		{
			$sqlUsername="SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
                        $resUsername=mysql_query($sqlUsername,$db) or die("Error while fetching username   ".$sqlUsername."  ".mysql_error($db));
                        $rowUsername=mysql_fetch_assoc($resUsername);
                        $username=$rowUsername["USERNAME"];

			$matchArray=getList($profileid,'SL','','',$username);
			if(is_array($matchArray))
			{
				foreach($matchArray as $key=>$value)
				{
					if($value["LEAD_ID"])
						$leadsArr[]=$value["LEAD_ID"];
					elseif($value["PROFILEID"] && is_numeric($value["PROFILEID"]))
						$profilesArr[]=$value["PROFILEID"];
				}
				moveProfiles("AUTO_POPULATE",$profileid,$profilesArr,$leadsArr,'','SL','TBD');
			}
		}
	}
}
mail('nikhil.dhiman@jeevansathi.com','ap_auto_populate_tbd',date("y-m-d"));
?>
