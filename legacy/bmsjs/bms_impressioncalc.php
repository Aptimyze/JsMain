<?PHP

/*************************bms_impressioncalc.php***********************************/
  /*
   *  Created By         : Abhinav Katiyar
   *  Last Modified By   : Ruchi Chawla
   *  Description        : used for calculating impression on a certain criteria
   *  Includes/Libraries : ./includes/bms_connect.php
*/
include ("./includes/bms_connect.php");
$ip=FetchClientIP();
$data=authenticatedBms($id,$ip,"banadmin");
$validcriteriaimpressions=array("IP","AGE","GENDER","LOCATION","INCOME");
$dbsearch=getConnectionSearch();

function getMaxDateSearchDump()
{
	global $dbsearch;
	$sql="select max(Entry_dt) as maxdate from inventory.CRITLOG";
	$res=mysql_query($sql,$dbsearch) or die(mysql_error());//logErrorBms ("bms_connect.inc:getMaxDateSearchDump:1: Could not select max date from search dump<br><!--$sql<br>". mysql_error()."-->: ". mysql_errno(),$sql);
	$myrow=mysql_fetch_array($res);
	$maxdate=$myrow["maxdate"];
	$maxdate=substr($maxdate,0,10);
	list($yr,$mn,$dt)=explode("-",$maxdate);
	$maxdate=date("Y-m-d",mktime(0,0,0,$mn,$dt-1,$yr));
	return $maxdate;

}
/* assign farea ,indtype , exp dropdowns to tpl
			input:
			output:arrays of IP,AGE , INCOME assigned  to htm
*/
function showImpCalcForm($criteria)
{
	global $smarty,$_TPLPATH;
	if($criteria=="IP")
	{
		$smarty->assign("ipcityarr",getIpCity(""));
		$smarty->assign("ipctryarr",getIpCountry(""));
	}
	elseif($criteria=="AGE")
	{
		$smarty->assign("agearr",getAge());
	}
	elseif($criteria=="INCOME")
	{
		$smarty->assign("ctcarr",getCTC());
	}
	
}

/*   	  validates the form(criterias should be valid)
			input: criteria,keyword,farea,indtype,location,exp
			output: true/false
*/
function checkForm($criteria,$ipradio,$ctc,$age,$gender,$location)
{
	global $smarty,$_TPLPATH;
	echo "<!--$criteria,$keyword,$farea,$indtype,$location,$exp-->";
	
	if(($criteria=="IP"&&!$ipradio)||($criteria=="INCOME"&&$ctc=="")||($criteria=="AGE"&&$minage==""||$maxage=="")||($criteria=="GENDER"&&$gender=="")||($criteria=="LOCATION"&&$location==""))
	{
		echo $ipradio;
		$errormsg="Please enter correct value of ".$criteria;
		$smarty->assign("errormsg",$errormsg);
		return false;
	}
	
	else
		return true;
}

/*   	  Checks if the criteria is valid or not
			input: criteria
			output: true- if valid criteria
					false- if not a valid criteria
*/

function checkCriteria($criteria)
{
	global $smarty,$validcriteriaimpressions,$_TPLPATH;
	echo "<!--$criteria,$keyword,$farea,$indtype,$location,$exp-->";
	if($criteria==""||$criteria=="select")
	{
		$errormsg="Please select valid criteria";
		$smarty->assign("errormsg",$errormsg);
		return false;
	}
	elseif(!in_array($criteria,$validcriteriaimpressions))// if criteria is none of the above
	{	
		$errormsg="Impression mis not available on this criteria";
		$smarty->assign("errormsg",$errormsg);
		return false;
	}
	else
		return true;

}

/*   get sql query corresponding to criteria , corresponding value ,and field to be queried
			input: criteria,criteriavalue,field
			output: sql query
*/
function getSql($region,$zone,$criteria,$criteriavalue,$day,$fields="Value")
{
	global $days,$region,$zone;
	$strlength = 2;
	if ($criteria == "GENDER")
        {
                $whereclause=$fields."='".trim($criteriavalue)."'";
        }
	elseif ($criteria == "IP")
	{
		list($type,$id,$region) = explode(",",$criteriavalue);
		if ($type == 1)
			$field="CityId";
		else
			$field="CountryId";
		/*$sql = "SELECT Locids from bms2.IPCITIES WHERE CityId='$city'";
		$res = mysql_query($sql); 
		$row = mysql_fetch_array($res);
		echo $locid = $row["Locids"];*/
		echo $sql = "SELECT sum(Count) AS finalcount FROM inventory.LOGLOCID WHERE $field='$id' and RegId='$region' and ZoneId='$zone' and DATE_SUB(CURDATE(),INTERVAL $days DAY) <=Entry_dt";
	}
	elseif ($criteria == "INCOME")
	{
//		$whereclause=$fields."like'%".trim($criteriavalue)."%'";
		$whereclause=$fields." in ($criteriavalue)";
		
	}
	elseif ($criteria == "AGE")
        {
		list($minage,$maxage) = explode(":",$criteriavalue);
                $whereclause="(FLOOR($fields)>='$minage' and CEIL($fields)<='$maxage')";
        }
	elseif ($criteria == "LOCATION")
        {
                $whereclause=$fields."='".trim($criteriavalue)."'";
        }
	if($whereclause)
	{
		$datequery ="DATE_SUB(CURDATE(),INTERVAL $days DAY) <=Entry_dt";
		echo $sql="select sum(Count) as finalcount from inventory.CRITLOG  where Criteria = '$criteria' and ".$whereclause." and ZoneId='$zone' and RegId='$region' and ".$datequery."";
		echo "<!--$sql-->";
	}
	return $sql;
}
if($data)
{   
	$id=$data["ID"];
	$bmsheader=fetchHeaderBms($data);
	$bmsfooter=fetchFooterBms();
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);
	$smarty->assign("id",$id);
    	if($calculateimp_x)
	{	
		//if(checkForm($criteria,$ipradio,$ctc,$age,$gender,$location))
		{
			$fields = "Value";
			if ($criteria == "IP")
			{
				if ($ipradio == 'city')
				{
					$type = 1;
					$bannerip = $type.",".$bannercity.",".$region;
				}
				else
				{	$type=2;
					$bannerip = $type.",".$bannerctry.",".$region;
				}
				$sql = getSql($region,$zone,$criteria,$bannerip,$days,$fields);
			}
			elseif ($criteria == "GENDER")
			{				
				$sql = getSql($region,$zone,$criteria,$gender,$days,$fields);
				$criteriavalue = $gender;
			}
			elseif ($criteria == "AGE")
			{
				$agelt = $minage.":".$maxage;
				$sql = getSql($region,$zone,$criteria,$agelt,$days,$fields);
				$criteriavalue = $minage." "." To "." ".$maxage;
			}
			elseif ($criteria == "LOCATION")
			{
				$sql = getSql($region,$zone,$criteria,$location,$fields);
			}
			elseif ($criteria == "INCOME")
			{
				$ctc = implode(',',$ctc);
				$sql = getSql($region,$zone,$criteria,$ctc,$days,$fields);
			}
			if ($sql)
			{	
				$res=mysql_query($sql,$dbsearch) or logErrorBms("bms_impressioncalc.php: :1: Could not fetch impressions. <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
				$myrow=mysql_fetch_array($res);
				$finalcount=$myrow["finalcount"];
			}
		}
		if($finalcount>=0)
		{
			$sql = "SELECT ZoneName FROM bms2.ZONE WHERE ZoneId = '$zone'";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res);
			$resultarr["Zone"] = $row["ZoneName"];

			$sql = "SELECT RegName FROM bms2.REGION WHERE RegId = '$region'";
                        $res = mysql_query($sql);
                        $row = mysql_fetch_array($res);

                        $resultarr["Region"]	= $row["RegName"];
			$resultarr["Criteria"] 	= $criteria.":"." ".$criteriavalue;
			$resultarr["Count"] 	= $finalcount;
			$smarty->assign("days",$days);
			$smarty->assign("resultarr",$resultarr);
		}
		$smarty->display("./$_TPLPATH/bms_impressioncalc.htm");
	}
	elseif($showcriteria_x)
	{ 
		if(checkCriteria($criteria))
		{
			$region = substr($region,0,1);
			$zone = explode('|',$zone);
			$smarty->assign("criteria",$criteria);
			showImpCalcForm($criteria);
			$smarty->assign("days",$days);
			$smarty->assign("region",$region);
			$smarty->assign("zone",$zone[0]);
			$smarty->display("./$_TPLPATH/bms_impressioncriteria.htm");
		}
		else
		{	
			assignRegionZoneDropDowns("","showcriteria");
			$smarty->assign("days",$days);
			$smarty->display("./$_TPLPATH/bms_impressioncalc.htm");
		}
	}
	else
	{	
		assignRegionZoneDropDowns("","showcriteria");
		$smarty->assign("days",$days);
		$smarty->display("./$_TPLPATH/bms_impressioncalc.htm");
	}
}
else
{
	TimedOutBms();
}
