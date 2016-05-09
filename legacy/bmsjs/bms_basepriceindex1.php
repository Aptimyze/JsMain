<?PHP

/************************************************************************************************************************
*    FILENAME           : bms_basepriceindex1.php
*    DESCRIPTION        : Edit/Enter bannerbase price on basis of Region/Zone & bannertype.
*    CREATED BY         : lavesh
*    Live On            : 20 july 2007
***********************************************************************************************************************/

include ("./includes/bms_connect.php");
$ip=FetchClientIP();
$data=authenticatedBms($id,$ip,"banadmin");
global $dbbms;
if($data)
{
	if($submit3_submit2)
	{
		if(!$submit3_x)
			$submit2_x=1;
	}

	$id=$data["ID"];
	$bmsheader=fetchHeaderBms($data);
	$bmsfooter=fetchFooterBms();
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);
	if($submit1)
	{
		$i=0;
		$sql = "select ZoneId,ZoneName from bms2.ZONE where RegId='$region'";
		$result = mysql_query($sql,$dbbms) or die(mysql_error()); 
                while($myrow=mysql_fetch_array($result))
                {
			$zones[$i]["sno"]=$i+1;
			$zones[$i]["zoneid"]=$myrow["ZoneId"];
                        $zones[$i]["zonename"]=$myrow["ZoneName"];	

			$sql1="select BasePriceIndex from bms2.BASE_PRICE WHERE REGION='$region' AND ZONE='$myrow[ZoneId]' AND BannerType='$bannertype'";
			$result1 = mysql_query($sql1,$dbbms) or die(mysql_error());
			$row=mysql_fetch_array($result1);
			if($row["BasePriceIndex"])
				$zones[$i]["bpi"]=$row["BasePriceIndex"];
			else
				$zones[$i]["bpi"]=500;

			$i++;
		}
                $sql="SELECT RegName from bms2.REGION WHERE RegId='$region'";
                $result = mysql_query($sql,$dbbms) or die(mysql_error());
                $row=mysql_fetch_array($result);
                if($row["RegName"]!='')
                        $region_name=$row["RegName"];

		$smarty->assign("region_name",$region_name);
		$smarty->assign("region",$region);
		$smarty->assign("bannertype",$bannertype);
		$smarty->assign("tot",$i);
                $smarty->assign("zones",$zones);
		$smarty->display("./$_TPLPATH/bms_basepriceindex2.htm");
	}
	elseif($submit2_x)
	{
		//action on submit	
		$bpi_key=array_keys($BPI);
		for($l=0;$l<count($bpi_key);$l++)
		{
			$zone=$bpi_key[$l];
			$bpi=$BPI[$zone];
			$sql="REPLACE INTO bms2.BASE_PRICE VALUES('$region','$zone','$bannertype','$bpi')";
			mysql_query($sql) or die(mysql_error());	
		}
		$sql="SELECT RegName from bms2.REGION WHERE RegId='$region'";
		$result = mysql_query($sql,$dbbms) or die(mysql_error());
		$row=mysql_fetch_array($result);
		if($row["RegName"]!='')
			$region=$row["RegName"];

		$smarty->assign("region",$region);
		$smarty->assign("bannertype",$bannertype);
		$smarty->assign("confirmation",1);
		$smarty->display("./$_TPLPATH/bms_basepriceindex2.htm");
	}
	else
	{
		if($submit3_x)
		{
			$smarty->assign("region",$region);
			$smarty->assign("bannertype",$bannertype);
		}
                $sql = "select Type FROM bms2.BANNER_COST";
                $res=mysql_query($sql)or die(mysql_error());
                while($row=mysql_fetch_array($res))
                {
                        $banner_type[]=$row["Type"];
                }
                $smarty->assign("banner_type",$banner_type);
		$regions=getRegions();
		$smarty->assign("regions",$regions);
		$smarty->display("./$_TPLPATH/bms_basepriceindex1.htm");
	}
}
else
	TimedOutBms();
?>
