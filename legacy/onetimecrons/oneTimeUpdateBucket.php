<?php
include_once("connect.inc");
$db=connect_db();
$allHindiArr=array(7,10,13,19,28,33,41);

//$sql_rel_caste="select PROFILEID,PARTNER_CASTE FROM SEARCH_FEMALE_REV WHERE PARTNER_CASTE<>''";
//$sql_rel_caste="select PROFILEID,PARTNER_CASTE,PARTNER_MTONGUE,PARTNER_MSTATUS,PARTNER_COUNTRYRES FROM SEARCH_FEMALE_REV WHERE BUCKET=0";
$sql_rel_caste="select PROFILEID,PARTNER_CASTE,PARTNER_MTONGUE,PARTNER_MSTATUS,PARTNER_COUNTRYRES FROM SEARCH_FEMALE_REV";
$sql_rel_caste_result=mysql_query($sql_rel_caste,$db);
while($myrow_sql_rel_caste_result=mysql_fetch_array($sql_rel_caste_result))
{
	$pid=$myrow_sql_rel_caste_result["PROFILEID"];

	//MTONGUE SCORE
	$par_mtongue=$myrow_sql_rel_caste_result["PARTNER_MTONGUE"];
	if($par_mtongue)		
	{
		if(strstr($par_mtongue,","))
		{
			$par_mtongue=trim($par_mtongue,"'");
			$par_mtongueArr=explode("','",$par_mtongue);
			//if(count(array_diff($par_mtongueArr,$allHindiArr))==0 && count(array_diff($allHindiArr,$par_mtongueArr)==0))
			if(count(array_diff($par_mtongueArr,$allHindiArr))==0)	
				$mtongueScore=10;
			else
				$mtongueScore=2;
			
		}
		else
			$mtongueScore=10;
		
	}
	else
		$mtongueScore=2;
	//MTONGUE SCORE

	//MSTAUS
	$par_mstatus=$myrow_sql_rel_caste_result["PARTNER_MSTATUS"];
	if($par_mstatus)
	{
		if($par_mstatus=='N' or $par_mstatus=="'N'")
			$mstatusScore=10;
		elseif(!strstr($par_mstatus,"N"))
			$mstatusScore=10;
		else
			$mstatusScore=1;
	}
	else
		$mstatusScore=1;
	//MSTAUS

	//COUNTRYRES
	$par_countryres=$myrow_sql_rel_caste_result["PARTNER_COUNTRYRES"];
	if($par_countryres)
	{
		$temp=explode(",",$par_countryres);
		if(count($temp)<5)
			$countryScore=5;
		else	
			$countryScore=2;
	}	
	else	
		$countryScore=2;
	//COUNTRYRES
	

	$par_caste=$myrow_sql_rel_caste_result["PARTNER_CASTE"];
	$par_casteOrg=$myrow_sql_rel_caste_result["PARTNER_CASTE"];
	unset($par_casteArr);
	if($par_caste)
	{
		$par_caste=trim($par_caste,"'");
		$par_casteArr=explode("','",$par_caste);
		$par_caste=implode(",",$par_casteArr);
	}
	$Bucket=0;
	$religionIsCaste=0;

	if( strstr($par_casteOrg,14) || strstr($par_casteOrg,149) || strstr($par_casteOrg,154) || strstr($par_casteOrg,2) )
	{
		$Bucket=0;
		$religionIsCaste=1;
	}

	if(!$religionIsCaste)
	{
	if(count($par_casteArr)==1)
		$Bucket=1;
	if(count($par_casteArr)>1)
	{
		$flag=0;
		$flag1=0;
		$par_caste=trim($par_caste,",other");
		$sqlBucket="SELECT PARENT_CASTE,REL_CASTE FROM CASTE_COMMUNITY WHERE REL_CASTE IN ($par_caste) GROUP BY REL_CASTE,PARENT_CASTE";
		$resBucket=mysql_query($sqlBucket,$db) or die("200 $sqlBucket".mysql_error($db));

		while($rowBucket=mysql_fetch_array($resBucket))
		{
			$pc=$rowBucket['PARENT_CASTE'];
			$rc=$rowBucket['REL_CASTE'];
			$arr[$pc][]=$rc;
			$flag=1;
		}
		if($flag)
		{
			foreach($arr as $k=>$v)
			{
				if(count($v)>1)
				{
					$flag1=array_diff($par_casteArr,$v);
					if(count($flag1)==0)
						$Bucket=1;
				}
				if($Bucket==1)
					break;
			}
		}
	}
	}
	
	if($Bucket)
		$casteScore=10;
	else
		$casteScore=2;
	$Tscore=$mtongueScore+$mstatusScore+$countryScore+$casteScore;

	$sql="UPDATE SEARCH_FEMALE_REV SET BUCKET='$Tscore' WHERE PROFILEID=$pid";
	mysql_query($sql,$db) or die(mysql_error());
}

?>

