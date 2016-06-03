<?php
/****
        File          : usr_hits.php
        Description   : This file shows a particular user mis of hits and members according to group alloted to him
        Modification  : Authentication on master , mis queries on slave
        Date          : 2013-09-17 by Nitesh
****/

include("connect.inc");

$db2=connect_db();
$db=connect_slave();

if(authenticated($cid))
{
	$name=getname($cid);
	$privilage = getprivilage($cid);
	$priv = explode("+",$privilage);
	/*
	if (in_array('TH',$priv))
                $total_hits = 1 ;
	
	if (in_array('CP',$priv))
		$complete_profile = 1 ;

	if (in_array('IP',$priv))
                $incomplete_profile = 1 ;
	if (in_array('PAID',$priv))
                $paidPrivilage = 1;
	$smarty->assign("TH",$total_hits);
	$smarty->assign("CP",$complete_profile);
	$smarty->assign("IP",$incomplete_profile);*/
	if (in_array('PAID',$priv) || in_array('admin',$priv))
		$paidPrivilage=1;
	else
		$paidPrivilage=0;
	$smarty->assign("paidPrivilage",$paidPrivilage);
	if($sourcegp)
	{
		$sql1="SOURCEID";
		$gpstr="'".$sourcegp."'";
	}
	else
	{
		if($group)
		{
			$sql1="SOURCEID";
			$sourcegp=$group;
			$gpstr="'".$sourcegp."'";
		}
		else
		{
			$sql1="SOURCEGP";
		}
	}

	if($sourcegp)
	{
		$i=0;
		$sql="SELECT SourceID,CPC FROM MIS.SOURCE WHERE GROUPNAME='$sourcegp'";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$srcarr[$i]=$row['SourceID'];
			$cpcarr[$i]=$row['CPC'];
			$i++;
		}
		$src_str="'".implode("','",$srcarr)."'";
	}
	else
	{
		$i=0;
		if($group)
		{
			$sql="SELECT SourceID,CPC FROM MIS.SOURCE WHERE GROUPNAME='$group'";
			$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$srcarr[$i]=$row['SourceID'];
				$cpcarr[$i]=$row['CPC'];
				$i++;
			}
		 $src_str="'".implode("','",$srcarr)."'";
		}
		$gpstr="'".implode("','",$srcarr)."'";
	}
	//flag for checking whether we need to calculate for the latest data
	$currentFlag=false;
	//flag for checking whether we need to calculate for the paid latest data
	$currentFlagPaid45=false;
	$currentFlagPaid90=false;
	
	unset($cnt);
	unset($tota);
	unset($totb);
	
//checking whether duration range is selected or normal 
	if($submitMis){
		$ddate_mon1=$ddate_mon+1;
		$ddate_mon2=$ddate_mon+1;
		$ddate_date1=1;
		$ddate_date2=31;
		$ddate_yyyy1=$ddate_yyyy;
		$ddate_yyyy2=$ddate_yyyy;
		$month1Str=date("F", mktime(0, 0, 0, $ddate_mon1, 10));
		$month2Str=date("F", mktime(0, 0, 0, $ddate_mon2, 10));
		$duration=$month1Str." - $ddate_yyyy";
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]["date"]=$i+1;
			$ddarr[$i]["mon"]=$ddate_mon1;
		}
		$smarty->assign("colSpanMonth1",$i);
		$smarty->assign("month1",$month1Str);
	}
	else{
		$ddate_mon1=$ddate_mon1+1;
		$ddate_mon2=$ddate_mon2+1;
		
		$month1Str=date("F", mktime(0, 0, 0, $ddate_mon1, 10));
		$month2Str=date("F", mktime(0, 0, 0, $ddate_mon2, 10));
		$duration=$ddate_date1."-".$month1Str."-".$ddate_yyyy1."  &nbsp; TO &nbsp;  ".$ddate_date2."-".$month2Str."-".$ddate_yyyy2;
		$i=0;
		if((($ddate_mon2-$ddate_mon1>0) &&($ddate_yyyy2==$ddate_yyyy1)) ||($ddate_yyyy2>$ddate_yyyy1)) {
			$maxDateOfMonth=31;
		}
		else
			$maxDateOfMonth=$ddate_date2;
		for($k=$ddate_date1;$k<=$maxDateOfMonth;$k++)
		{
			$ddarr[$i]["date"]=$k;
			$ddarr[$i]["mon"]=$ddate_mon1;
			$i++;
		}
		//if 3 month range is selected
		if(($ddate_mon2-$ddate_mon1>2)&&($ddate_yyyy2==$ddate_yyyy1)||($ddate_yyyy2>$ddate_yyyy1) &&(12-$ddate_mon1+$ddate_mon2-0)>2)
			die("please select the month range to maximum of three months");
		
		if(($ddate_mon2-$ddate_mon1>1)&&($ddate_yyyy2==$ddate_yyyy1)||($ddate_yyyy2>$ddate_yyyy1) &&(12-$ddate_mon1+$ddate_mon2-0)>1)
		{
			if($ddate_mon1==12)
				$ddate_mon_between=1;
			else
				$ddate_mon_between=$ddate_mon1+1;
			for($k=1;$k<=31;$k++)
			{
				$ddarr[$i]["date"]=$k;
				$ddarr[$i]["mon"]=$ddate_mon_between;
				$i++;
			}
			$month_between_Str=date("F", mktime(0, 0, 0, $ddate_mon_between, 10));
			$smarty->assign("between_month",1);
			$smarty->assign("colSpanMonthBetween",31);
			$smarty->assign("month_between",$month_between_Str);
		}
		if(($ddate_mon2-$ddate_mon1>0)&&($ddate_yyyy2==$ddate_yyyy1)||($ddate_yyyy2>$ddate_yyyy1)&&(12-$ddate_mon1+$ddate_mon2-0)>0){
			for($j=1;$j<=$ddate_date2;$j++)
			{
				$ddarr[$i]["date"]=$j;
				$ddarr[$i]["mon"]=$ddate_mon2;
				$i++;
			}
		
			$smarty->assign("colSpanMonth1",32-$ddate_date1);
			$smarty->assign("month1",$month1Str);
			$smarty->assign("colSpanMonth2",$ddate_date2);
			$smarty->assign("month2",$month2Str);
		}
		else{
			$smarty->assign("colSpanMonth1",32-$ddate_date1);
			$smarty->assign("month1",$month1Str);
		}
		
	}
		//Past 3 days data->date variables
		
		$testStart = new DateTime($ddate_mon2.'/'.$ddate_date2.'/'.$ddate_yyyy2);
		$selectedDate= date_format($testStart, 'Y-m-d');
		
		$testEnd = new DateTime($ddate_mon1.'/'.$ddate_date1.'/'.$ddate_yyyy1);
		$selectedEndDate= date_format($testEnd, 'Y-m-d');
		if(strtotime(date("Y-m-d"))-strtotime($selectedDate)<=2*24*60*60 || $submitMis )
		{
			$currentFlag=true;
			$currentFlagPaid45=true;
			$currentFlagPaid90=true;
			$ts=time();
			$tsPaid45=$ts-44*24*60*60;
			$tsPaid90=$ts-89*24*60*60;
			$ts3days=$ts-2*24*60*60;
-			$threeDaysBefore=date("Y-m-d",$ts3days);
			
			$currentDate=$selectedDate;
			$start_date=$threeDaysBefore;
			
			if(strtotime($selectedDate)==strtotime(date("Y-m-d")))
			{
				$currentHitsFlag=true;
				$start_date_hits=date("Y-m-d H:i:s",time()-24*60*60);
			}
			//paid dates to caluclate data from jProfile.
			$paid45Date=date("Y-m-d",$tsPaid45);
			$paid90Date=date("Y-m-d",$tsPaid90);
			
			$paid45EndDate=$selectedDate;
			$paid90EndDate=$selectedDate;

			if(strtotime($selectedEndDate)>=strtotime($paid45Date))
			{
				$paid45StartDate=$selectedEndDate;
				$paid90StartDate=$selectedEndDate;
			}
			else
			{
				$paid45StartDate=$paid45Date;
				if(strtotime($selectedEndDate)>=strtotime($paid90Date))
					$paid90StartDate=$selectedEndDate;
				else
					$paid90StartDate=$paid90Date;
				
			}
		}
		else
		{
			$ts=time();
			$tsPaid45=$ts-44*24*60*60;
			$paid45Date=date("Y-m-d",$tsPaid45);
			$tsPaid90=$ts-89*24*60*60;
			$paid90Date=date("Y-m-d",$tsPaid90);

			if(strtotime($selectedDate)>=strtotime($paid45Date))
			{
				$currentFlagPaid45=true;
				$currentFlagPaid90=true;
				$paid45EndDate=$selectedDate;
				$paid90EndDate=$selectedDate;
				if(strtotime($selectedEndDate)>=strtotime($paid45Date))
					$paid45StartDate=$selectedEndDate;
				else
					$paid45StartDate=$paid45Date;
				if(strtotime($selectedEndDate)<=strtotime($paid90Date))
				{
					$paid90StartDate=$paid90Date;
				}
				else
				{
					$paid90StartDate=$selectedEndDate;
				}
				
			}
			else
			{
				$currentFlagPaid45=false;
				if(strtotime($selectedDate)>=strtotime($paid90Date))
				{									
					$currentFlagPaid90=true;
					$paid90EndDate=$selectedDate;
					if(strtotime($selectedEndDate)>=strtotime($paid90Date))
					{
						$paid90StartDate=$selectedEndDate;
					}
					else
					{
						$paid90StartDate=$paid90Date;
					}
				}
			}
			
		}
		
/*
echo $selectedDate."*********\n\n\n**";
echo $selectedEndDate."********\n\n\n\n";
echo $start_date."********\n\n\n\n";
echo $currentDate."********\n\n\n\n";
echo $paid90StartDate."********\n\n\n\n";
echo $paid90EndDate."********\n\n\n\n";
echo $paid45StartDate."********\n\n\n\n";
echo $paid90EndDate."********\n\n\n\n";
*/

	if($profileActivArr[0]){
		
		$profileConditionArr=explode(",",$profileActivArr[0]);
		foreach($profileConditionArr as $K => $V)
		{
			if($V=="A")
			{
				$displayActiveStr.="COMPLETE,";
				$profiledCompleteStr="INCOMPLETE='N'";
			}
			else
			{
				if($V=="Y")
				$displayActiveStr.="YES,";
				if($V=="N")
				$displayActiveStr.="NO,";
				if($V=="H")
				$displayActiveStr.="HIDDEN,";
				if($V=="D")
				$displayActiveStr.="DELETED,";
				$activeStr.="'$V',";
			}
		}
		$activeStr=trim($activeStr,",");
		$displayActiveStr=trim($displayActiveStr,",");
		//Activated yes/no,Hidden,Deleted condtion
		if($activeStr){
			$whrStr.="ACTIVATED IN (".$activeStr.")";
			//Complete Profiles
			if($profiledCompleteStr){
			$whrStr.=" AND ".$profiledCompleteStr;
			}
		}
		else{
		//Complete Profiles
			if($profiledCompleteStr){
			$whrStr.=" ".$profiledCompleteStr;
			}
		}
	}
	else
	{
		//do nothing
		$displayActiveStr="ALL";
	}
	
	//have photo conditon
	if($photo)
	{
		if($photo=="Y"){
			$displayPhotoStr.="YES";
			if($whrStr)
			$whrStr.=" AND HAVEPHOTO='".$photo."'";
			else
			$whrStr.=" HAVEPHOTO='".$photo."'";
		}
		elseif($photo=="N"){
			$displayPhotoStr.="NO";
			if($whrStr)
			$whrStr.=" AND HAVEPHOTO !='Y'";
			else
			$whrStr.=" HAVEPHOTO !='Y'";
		}
		else
			$displayPhotoStr.="ALL";
	}
	
	//mtongue condtion
	if($allMtongueSelected)
	{
		//do nothing
		$displayMtongueStr.=" ALL MTONGUES";
	}
	elseif($preferredMtongueSelected)
	{
		$mtongueStr="'7','10','13','14','19','20','27','28','30','33','34'";
		if($whrStr)
			$whrStr.=" AND MTONGUE IN (".$mtongueStr.")";
		else
			$whrStr.=" MTONGUE IN (".$mtongueStr.")";
		$displayMtongueStr.="Preferred Mtongues";
	}
	elseif($mtongueArr[0])
	{
		$mtongueStr=$mtongueArr[0];
		$mtongueStr=preg_replace("/,/","','",$mtongueStr);
		if($mtongueStr){
			if($whrStr)
			$whrStr.=" AND MTONGUE IN ('".$mtongueStr."')";
			else
			$whrStr.=" MTONGUE IN ('".$mtongueStr."')";
		}
		//display Mtongue
		$totalMtongueArr=FieldMap::getFieldLabel('community',"",1);
		$mtongueSelectedArr=explode("','",$mtongueStr);
		foreach($totalMtongueArr as $K=>$V){
			if(in_array($K,$mtongueSelectedArr))
				$displayMtongueStr.="$V,";
		}
		$displayMtongueStr=trim($displayMtongueStr,",");
	}

	
	//location
	if($outsideLocation && $allIndiaSelected){
		//do nothing
		$displayLocationStr.="ALL(INDIA AND OUTSIDE)";
	}
	elseif($allIndiaSelected)
	{
		if($whrStr)
			$whrStr.=" AND COUNTRY_RES = '51'";
		else
			$whrStr.=" COUNTRY_RES = '51'";
		
		$displayLocationStr.="ALL INDIA";
	}
	elseif($preferredStatesSelected){
		$cityStr="'PU11','UP01','GU01','MH30','RA01','MH01','UP02','UP03','RA02','HA01','UP04','AP13','PU01','UP32','GU02','AP14','KA01','BI08','MH02','UP05','WB07','WB08','KA02','WB09','WB10','UP06','GU04','WB11','WB12','PU12','PU02','RA12','KA10','BI09','GU13','PU02','GU14','CH04','RA04','MP13','MH14','HA07','MP02','OR01','GU15','MH15','BI10','KA11','RA05','CH03','JH04','UP34','UP35','WB02','MP14','WB16','KE06','WB17','PH00','MH16','BI11','AP16','WB18','OR02','HP01','BI12','WB20','UK05','MP15','JH03','KA05','MH17','CH02','WB03','GU16','UP36','UP08','UP09','HA02','PU03','UP10','PU14','PU14','UP11','GU17','GU05','RA06','BI03','UP12','GO','GU18','MH18','UP13','JK01','MP16','PU05','HA03','MP07','WB22','WB23','WB24','UP14','UK02','UP37','HA08','PU06','AP03','MP08','MP09','RA07','RA11','PU10','MH20','MH21','RA13','GU19','JK04','JH02','UP16','RA08','GU06','GU06','AP04','GU20','GU07','UP17','UP18','HA09','HP02','BI13','WB04','MH03','WB05','RA09','KE06','WB27','MH22','UP19','PU07','MH23','UP20','UP38','WB29','UP21','UP39','UP40','PU15','UP22','MP18','GU21','MH04','BI14','MP19','UK01','UP24','BI05','KA09','WB30','GU22','MH05','WB31','MH06','MH24','MH24','GU23','DE00','UP25','GU08','RA14','WB32','HA06','MH25','PU08','PU09','BI06','UP41','GU24','MH08','OR07','BI15','UP26','WB33','CH01','GU09','UP42','JH01','WB34','MP21','OR04','MP22','HA10','UK04','HA04','UK03','OR04','MP23','UP29','BI16','OR09','UP43','MH09','PU16','MP24','WB36','UP44','HP03','MH10','MP25','RA15','HA05','UP45','MH11','HA11','WB38','JK03','GU10','MH12','WB39','RA16','RA10','KA26','MP11','MH13','UP46','GU04','GU25','GU12','UP30','AP09','MH26','HA12','MH27'";
		if($whrStr)
			$whrStr.=" AND (COUNTRY_RES='51' AND CITY_RES IN (".$cityStr."))";
		else
			$whrStr.=" (COUNTRY_RES='51' AND CITY_RES IN (".$cityStr."))";
			$displayLocationStr.="Preffered States";
	}
	elseif($indianLocationArr[0]){
		$stateArr=explode(",",$indianLocationArr[0]);
		$cityStateArr=FieldMap::getFieldLabel('city_india',"",1);
		foreach($cityStateArr as $K=>$V)
		{
			if(strlen($K)==4 && (!is_numeric($K)))
			{
				if(in_array(substr($K,0,2),$stateArr))
					$cityStr.="'$K',";
			}
			if(strlen($K)==2 && !is_int($K))
			{
				if(in_array(substr($K,0,2),$stateArr))
				$displayLocationStr.="$V,";
			}
		}
		$cityStr=trim($cityStr,",");
		$displayLocationStr=trim($displayLocationStr,",");
			
		if($outsideLocation){
			if($whrStr)
				$whrStr.=" AND (COUNTRY_RES != '51' OR (COUNTRY_RES='51' AND CITY_RES IN (".$cityStr.")))";
			else
				$whrStr.=" (COUNTRY_RES != '51' OR (COUNTRY_RES='51' AND CITY_RES IN (".$cityStr.")))";
				$displayLocationStr.=" AND Outside INDIA ";
		}
		else{
			if($whrStr)
				$whrStr.=" AND COUNTRY_RES = '51' AND CITY_RES IN (".$cityStr.")";
			else
				$whrStr.=" (COUNTRY_RES != '51' OR (COUNTRY_RES='51' AND CITY_RES IN (".$cityStr.")))";

		}
		
	}
	elseif($outsideLocation)
	{
		if($whrStr)
			$whrStr.=" AND COUNTRY_RES!= '51' ";
		else
			$whrStr.=" COUNTRY_RES != '51' ";
		$displayLocationStr.="OUTSIDE INDIA ";
	}
	
	//CurrentData whereString 
		
		$currentWhrStr=$whrStr;
	
	
	//verified mobile condition
	if($mobileVerify)
	{
		if($mobileVerify=="Y"){
			$displayMobileVerifyStr.="YES";
			if($whrStr){
				$whrStr.=" AND PHONE_STATUS='".$mobileVerify."'";
				$currentWhrStr.=" AND (MOB_STATUS='Y' OR LANDL_STATUS='Y')";
			}
			else{
				$whrStr.=" PHONE_STATUS='".$mobileVerify."'";
				$currentWhrStr.=" (MOB_STATUS='Y' OR LANDL_STATUS='Y')";
			}
		}
		elseif($mobileVerify=="N"){
			$displayMobileVerifyStr.="NO";
			if($whrStr){
				$whrStr.=" AND PHONE_STATUS='".$mobileVerify."'";
				$currentWhrStr.=" AND (MOB_STATUS!='Y' AND LANDL_STATUS!='Y')";
			}
			else{
				$whrStr.=" PHONE_STATUS='".$mobileVerify."'";
				$currentWhrStr.=" (MOB_STATUS!='Y' AND LANDL_STATUS!='Y')";
			}
		}
		else
		$displayMobileVerifyStr.="ALL";
		
	}
	//duplicate condition
	if($duplicate)
	{
		if($duplicate=="Y"){
			$displayDuplicateStr.="YES";
			if($whrStr)
			$whrStr.=" AND DUPLICATE='".$duplicate."'";
			else
			$whrStr.=" DUPLICATE='".$duplicate."'";
		}
		elseif($duplicate=="N"){
			$displayDuplicateStr.="NO";
			if($whrStr)
			$whrStr.=" AND DUPLICATE ='".$duplicate."'";
			else
			$whrStr.=" DUPLICATE ='".$duplicate."'";
		}
		else
			$displayDuplicateStr.="ALL";
	}

/*****
	Modified on Jun 13, 2013 by Nitesh Sethi.
	//using preivous code-> username astech added for a single source affiliate testing.
*****/

	if($name=='astech')
	{
		unset($srcarr);
		$srcarr[]='aft_astinc';

		$sql_h="SELECT $sql1 as src, SUM(COUNT) as cnt,DAYOFMONTH(ENTRY_DT) as dd,MONTH(ENTRY_DT) as mon FROM MIS.SOURCE_HITS WHERE ENTRY_DT BETWEEN '$ddate_yyyy1-$ddate_mon1-$ddate_date1' AND '$ddate_yyyy2-$ddate_mon2-$ddate_date2' AND SOURCEID='afl_astinc' GROUP BY src,dd,mon";

		$sql_profile_complete = "SELECT AGE,GENDER,SOURCEID as src,SUM(COUNT) as cnt,DAYOFMONTH(ENTRY_DT) as dd,MONTH(ENTRY_DT) as mon,INCOMPLETE FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$ddate_yyyy1-$ddate_mon1-$ddate_date1' AND '$ddate_yyyy2-$ddate_mon2-$ddate_date2'  AND (SEC_SOURCE = 'S' OR SEC_SOURCE IS NULL) AND ENTRY_MODIFY='E' AND SOURCEID='afl_astinc' AND $whrStr GROUP BY AGE,GENDER,src,dd,mon,INCOMPLETE";
		if($currentFlag)
		{
			if($currentHitsFlag)
			{
				$sql_current="SELECT DAYOFMONTH(Date) as dd,MONTH(Date) as mon,SourceID as src,COUNT(*) as cnt FROM MIS.HITS WHERE Date BETWEEN '$start_date_hits' AND '$currentDate' AND SOURCEID='afl_astinc' GROUP BY dd,SourceID";
			}
			
			$sql_subscription_complete="SELECT DAYOFMONTH(ENTRY_DT) as dd,MONTH(ENTRY_DT) as mon,SOURCE as src,AGE,GENDER,COUNT(*) as cnt,IF(D.PROFILEID is NULL,'N','Y') as DUPLICATE FROM newjs.JPROFILE as J left join duplicates.DUPLICATE_PROFILES as D ON J.PROFILEID=D.PROFILEID WHERE (ENTRY_DT BETWEEN '$start_date' AND '$currentDate') AND (SEC_SOURCE = 'S' OR SEC_SOURCE IS NULL) AND SOURCE ='afl_astinc' AND $currentWhrStr GROUP BY AGE,GENDER,src,dd,mon,DUPLICATE";
			
		}
		
		if($paidPrivilage)
		{			
			$sql_subscription_45="SELECT SOURCEID as src,SUM(COUNT45) as cnt,DAYOFMONTH(ENTRY_DT)) as dd,MONTH(ENTRY_DT) as mon,INCOMPLETE FROM MIS.SOURCE_MEMBERS_PAID WHERE ENTRY_DT BETWEEN '$ddate_yyyy1-$ddate_mon1-$ddate_date1' AND '$ddate_yyyy2-$ddate_mon2-$ddate_date2' AND (SEC_SOURCE = 'S' OR SEC_SOURCE IS NULL) AND ENTRY_MODIFY='E' AND  SOURCEID='afl_astinc' AND COUNT45 !=0 AND COUNT90=0 AND $whrStr GROUP BY src,dd,mon,INCOMPLETE";
			
			$sql_subscription_90="SELECT SOURCEID as src,SUM(COUNT90) as cnt,DAYOFMONTH(ENTRY_DT) as dd,MONTH(ENTRY_DT) as mon,INCOMPLETE FROM MIS.SOURCE_MEMBERS_PAID WHERE ENTRY_DT BETWEEN '$ddate_yyyy1-$ddate_mon1-$ddate_date1' AND '$ddate_yyyy2-$ddate_mon2-$ddate_date2' AND (SEC_SOURCE = 'S' OR SEC_SOURCE IS NULL) AND ENTRY_MODIFY='E' AND  SOURCEID='afl_astinc' AND COUNT45 =0 AND COUNT90!=0  AND $whrStr GROUP BY src,dd,mon,INCOMPLETE";
			
			if($currentFlagPaid45)
			{
				$sql_current_subscription45="SELECT DAYOFMONTH(J.ENTRY_DT) as dd,MONTH(J.ENTRY_DT) as mon,SOURCE,COUNT(*) as cnt,IF(D.PROFILEID is NULL,'N','Y') as DUPLICATE FROM newjs.JPROFILE as J left join duplicates.DUPLICATE_PROFILES as D  ON J.PROFILEID=D.PROFILEID left join billing.PURCHASES as S ON J.PROFILEID=S.PROFILEID WHERE  (J.ENTRY_DT BETWEEN '$paid45StartDate' AND '$paid45EndDate') AND (SEC_SOURCE = 'S' OR SEC_SOURCE IS NULL) AND SOURCE ='afl_astinc' AND $currentWhrStr  AND S.STATUS='DONE' AND S.SERVEFOR LIKE '%F%' AND DATEDIFF(S.ENTRY_DT,J.ENTRY_DT)<=45 GROUP BY dd,mon,SOURCE,DUPLICATE";
			}
			if($currentFlagPaid90)
			{	
				$sql_current_subscription90="SELECT DAYOFMONTH(J.ENTRY_DT) as dd,MONTH(J.ENTRY_DT) as mon,SOURCE,COUNT(*) as cnt,IF(D.PROFILEID is NULL,'N','Y') as DUPLICATE FROM newjs.JPROFILE as J left join duplicates.DUPLICATE_PROFILES as D  ON J.PROFILEID=D.PROFILEID left join billing.PURCHASES as S ON J.PROFILEID=S.PROFILEID WHERE (J.ENTRY_DT BETWEEN '$paid90StartDate' AND '$paid90EndDate') AND (SEC_SOURCE = 'S' OR SEC_SOURCE IS NULL)  AND S.STATUS='DONE' AND S.SERVEFOR LIKE '%F%' AND DATEDIFF(S.ENTRY_DT,J.ENTRY_DT)<=90 AND SOURCE ='afl_astinc' AND $currentWhrStr GROUP BY dd,mon,SOURCE,DUPLICATE";
			}
			
		}
		
	}
	else
	{
		if($src_str)
		{
			$endDate=$selectedDate;
			$startDate=$selectedEndDate;
			
			$sql_h="SELECT $sql1 as src, SUM(COUNT) as cnt,DAYOFMONTH(ENTRY_DT) as dd,MONTH(ENTRY_DT) as mon FROM MIS.SOURCE_HITS WHERE ENTRY_DT BETWEEN '$startDate' AND '$endDate' AND SOURCEID IN ($src_str) GROUP BY src,dd,mon";
			
			$sql_profile_complete = "SELECT AGE,GENDER,SOURCEID as src,SUM(COUNT) as cnt,DAYOFMONTH(ENTRY_DT) as dd,MONTH(ENTRY_DT) as mon,INCOMPLETE FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$startDate' AND '$endDate' AND (SEC_SOURCE = 'S' OR SEC_SOURCE IS NULL) AND ENTRY_MODIFY='E' AND SOURCEID IN ($src_str) AND $whrStr GROUP BY AGE,GENDER,src,dd,mon,INCOMPLETE";

			if($currentFlag)
			{
				if($currentHitsFlag)
				{
					$sql_current="SELECT DAYOFMONTH(Date) as dd,MONTH(Date) as mon,SourceID as src,COUNT(*) as cnt FROM MIS.HITS WHERE Date BETWEEN '$start_date_hits' AND '$currentDate' AND SOURCEID IN ($src_str) GROUP BY dd,SourceID";
				}
			 	
				$sql_current_profile_complete="SELECT DAYOFMONTH(J.ENTRY_DT) as dd,MONTH(J.ENTRY_DT) as mon,SOURCE as src,AGE,GENDER,COUNT(*) as cnt,IF(D.PROFILEID is NULL,'N','Y') as DUPLICATE
				FROM  newjs.JPROFILE as J left join duplicates.DUPLICATE_PROFILES as D ON J.PROFILEID=D.PROFILEID WHERE (J.ENTRY_DT BETWEEN '$start_date' AND '$currentDate') AND (SEC_SOURCE = 'S' OR SEC_SOURCE IS NULL) AND SOURCE IN ($src_str) AND $currentWhrStr GROUP BY AGE,GENDER,src,dd,mon,DUPLICATE";
			}
			
			if($paidPrivilage)
			{
				$sql_subscription_45="SELECT SOURCEID as src,SUM(COUNT45) as cnt,DAYOFMONTH(ENTRY_DT) as dd,MONTH(ENTRY_DT) as mon,INCOMPLETE FROM MIS.SOURCE_MEMBERS_PAID WHERE ENTRY_DT BETWEEN '$startDate' AND '$endDate' AND (SEC_SOURCE = 'S' OR SEC_SOURCE IS NULL) AND ENTRY_MODIFY='E' AND SOURCEID IN ($src_str) AND COUNT45 !=0 AND COUNT90=0 AND $whrStr GROUP BY src,dd,mon,INCOMPLETE";
			
				$sql_subscription_90="SELECT SOURCEID as src,SUM(COUNT90) as cnt,DAYOFMONTH(ENTRY_DT) as dd,MONTH(ENTRY_DT) as mon,INCOMPLETE FROM MIS.SOURCE_MEMBERS_PAID WHERE ENTRY_DT BETWEEN '$startDate' AND '$endDate' AND (SEC_SOURCE = 'S' OR SEC_SOURCE IS NULL) AND ENTRY_MODIFY='E' AND SOURCEID IN ($src_str) AND COUNT45 =0 AND COUNT90!=0  AND $whrStr GROUP BY src,dd,mon,INCOMPLETE";
				//for Activated column using alias for query
				$currentWhrStr1=str_replace("ACTIVATED","J.ACTIVATED",$currentWhrStr);
				if($currentFlagPaid45)
				{
					
					$sql_current_subscription45="SELECT DAYOFMONTH(P.REG_DATE) as dd,MONTH(P.REG_DATE) as mon,J.SOURCE as src,COUNT( DISTINCT S.PROFILEID ) as cnt,IF(D.PROFILEID is NULL,'N','Y') as DUPLICATE FROM newjs.INCOMPLETE_PROFILES as P 
LEFT JOIN newjs.JPROFILE as J ON P.PROFILEID=J.PROFILEID
LEFT JOIN duplicates.DUPLICATE_PROFILES as D ON J.PROFILEID=D.PROFILEID 
LEFT JOIN billing.PAYMENT_DETAIL AS S ON J.PROFILEID = S.PROFILEID
LEFT JOIN billing.SERVICE_STATUS AS B ON B.BILLID = S.BILLID
 WHERE (P.REG_DATE BETWEEN '$paid45StartDate' AND '$paid45EndDate') AND (SEC_SOURCE = 'S' OR SEC_SOURCE IS NULL) AND J.SOURCE IN ($src_str) AND $currentWhrStr1  AND S.STATUS='DONE' AND S.AMOUNT >0
AND (B.SERVEFOR LIKE  '%F%' OR B.SERVEFOR LIKE  '%D%' OR B.SERVEFOR LIKE  '%X%') AND DATEDIFF(S.ENTRY_DT,P.REG_DATE)<=45 GROUP BY dd,mon,src,DUPLICATE";
				}

				if($currentFlagPaid90)
				{
					$sql_current_subscription90="SELECT DAYOFMONTH(P.REG_DATE) as dd,MONTH(P.REG_DATE) as mon,J.SOURCE as src,COUNT( DISTINCT S.PROFILEID ) as cnt,IF(D.PROFILEID is NULL,'N','Y') as DUPLICATE FROM newjs.INCOMPLETE_PROFILES as P 
LEFT JOIN newjs.JPROFILE as J ON P.PROFILEID=J.PROFILEID
LEFT JOIN duplicates.DUPLICATE_PROFILES as D ON J.PROFILEID=D.PROFILEID 
LEFT JOIN billing.PAYMENT_DETAIL AS S ON J.PROFILEID = S.PROFILEID
LEFT JOIN billing.SERVICE_STATUS AS B ON B.BILLID = S.BILLID
 WHERE (P.REG_DATE BETWEEN '$paid90StartDate' AND '$paid90EndDate') AND (SEC_SOURCE = 'S' OR SEC_SOURCE IS NULL)  AND J.SOURCE IN ($src_str) AND $currentWhrStr1  AND S.STATUS='DONE' AND S.AMOUNT >0
AND (B.SERVEFOR LIKE  '%F%' OR B.SERVEFOR LIKE  '%D%' OR B.SERVEFOR LIKE  '%X%') AND DATEDIFF(S.ENTRY_DT,P.REG_DATE)<=90 GROUP BY dd,mon,src,DUPLICATE";
				}
			}
		}

		/*
		else
		{
			$sql_h="SELECT $sql1 as src, SUM(COUNT) as cnt,DAYOFMONTH(ENTRY_DT) as dd,MONTH(ENTRY_DT) as mon FROM MIS.SOURCE_HITS WHERE ENTRY_DT BETWEEN '$ddate_yyyy1-$ddate_mon1-$ddate_date1' AND '$ddate_yyyy2-$ddate_mon2-$ddate_date2' AND SOURCEGP IN ($gpstr) GROUP BY src,dd,mon";
 
			$sql_profile_complete = "SELECT AGE,GENDER,SOURCEID as src,SUM(COUNT) as cnt,DAYOFMONTH(ENTRY_DT) as dd,MONTH(ENTRY_DT) as mon,INCOMPLETE FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$ddate_yyyy1-$ddate_mon1-$ddate_date1' AND '$ddate_yyyy2-$ddate_mon2-$ddate_date2' AND (SEC_SOURCE = 'S' OR SEC_SOURCE IS NULL) AND ENTRY_MODIFY='E' AND SOURCEGP IN($gpstr) AND $whrStr GROUP BY AGE,GENDER,src,dd,mon,INCOMPLETE";

			
			if($paidPrivilage)
			{
				$sql_subscription_45="SELECT SOURCEID as src,SUM(COUNT45) as cnt,DAYOFMONTH(ENTRY_DT) as dd,MONTH(ENTRY_DT) as mon,INCOMPLETE FROM MIS.SOURCE_MEMBERS_PAID WHERE ENTRY_DT BETWEEN '$ddate_yyyy1-$ddate_mon1-$ddate_date1' AND '$ddate_yyyy2-$ddate_mon2-$ddate_date2' AND (SEC_SOURCE = 'S' OR SEC_SOURCE IS NULL) AND ENTRY_MODIFY='E' AND SOURCEGP IN($gpstr) AND COUNT45 !=0 AND COUNT90=0 AND $whrStr GROUP BY src,dd,mon,INCOMPLETE";
				$sql_subscription_90="SELECT SOURCEID as src,SUM(COUNT90) as cnt,DAYOFMONTH(ENTRY_DT) as dd,MONTH(ENTRY_DT) as mon,INCOMPLETE FROM MIS.SOURCE_MEMBERS_PAID WHERE ENTRY_DT BETWEEN '$ddate_yyyy1-$ddate_mon1-$ddate_date1' AND '$ddate_yyyy2-$ddate_mon2-$ddate_date2' AND (SEC_SOURCE = 'S' OR SEC_SOURCE IS NULL) AND ENTRY_MODIFY='E' AND SOURCEGP IN($gpstr) AND COUNT45 =0 AND COUNT90!=0  AND $whrStr GROUP BY src,dd,mon,INCOMPLETE";
			}
		}*/
	}

	$res_h=mysql_query_decide($sql_h,$db) or die(mysql_error_js());
	if($row_h=mysql_fetch_array($res_h))
	{
		do
		{
			$src=$row_h['src'];
			$counter=$row_h['cnt'];
			$i=array_search($src,$srcarr);
			$dd=$row_h['dd'];
			$mon=$row_h['mon'];
			
			$countHitsSourceDayWise[$i][$mon][$dd]["hit"]+=$counter;
			$totalCountHitsSourceWise[$i]["hit"]+=$counter;			
			$totalCountHitsDayWise[$mon][$dd]["hit"]+=$counter;
			$totalCountHits["hit"]+=$counter;
			
		}while($row_h=mysql_fetch_array($res_h));
	}
	if($currentHitsFlag)
	{
		$res_h_current=mysql_query_decide($sql_current,$db) or die(mysql_error_js());
		if($row_h_current=mysql_fetch_array($res_h_current))
		{
			do
			{
				$src=$row_h_current['src'];
				$counter=$row_h_current['cnt'];
				$i=array_search($src,$srcarr);
				$dd=$row_h_current['dd'];
				$mon=$row_h_current['mon'];
				
				$countHitsSourceDayWise[$i][$mon][$dd]["hit"]+=$counter;
				$totalCountHitsSourceWise[$i]["hit"]+=$counter;			
				$totalCountHitsDayWise[$mon][$dd]["hit"]+=$counter;
				$totalCountHits["hit"]+=$counter;
				
			}while($row_h_current=mysql_fetch_array($res_h_current));
		}
	}
	$res_e=mysql_query_decide($sql_profile_complete,$db) or die(mysql_error_js());
	if($row_e=mysql_fetch_array($res_e))
	{
		do
		{
			$src=$row_e['src'];
			$counter=$row_e['cnt'];
			$age=$row_e['AGE'];
			$gender=$row_e['GENDER'];
			$dd=$row_e['dd'];
			$mon=$row_e['mon'];
			$i=array_search($src,$srcarr);
			
			if($gender=="M"){
				if($age>=21 && $age<=24){
					$countSourceDayCriteriaWise[$i][$mon][$dd]["regM1"]+=$counter;
					$totalSourceCriteriaWise[$i]["regM1"]+=$counter;
					$totalCriteriaDateWise[$mon][$dd]["regM1"]+=$counter;
					$totalAll["regM1"]+=$counter;
				}
				elseif($age>=25 && $age<=35){
					$countSourceDayCriteriaWise[$i][$mon][$dd]["regM2"]+=$counter;
					$totalSourceCriteriaWise[$i]["regM2"]+=$counter;
					$totalCriteriaDateWise[$mon][$dd]["regM2"]+=$counter;
					$totalAll["regM2"]+=$counter;
					$countSourceDayCriteriaWise[$i][$mon][$dd]["validReg"]+=$counter;
					$totalCriteriaDateWise[$mon][$dd]["validRegTotal"]+=$counter;
					$totalSourceCriteriaWise[$i]["validReg"]+=$counter;
					$totalAll["validReg"]+=$counter;
				}
				if($age>=36){
					$countSourceDayCriteriaWise[$i][$mon][$dd]["regM3"]+=$counter;
					$totalSourceCriteriaWise[$i]["regM3"]+=$counter;
					$totalCriteriaDateWise[$mon][$dd]["regM3"]+=$counter;
					$totalAll["regM3"]+=$counter;
					$countSourceDayCriteriaWise[$i][$mon][$dd]["validReg"]+=$counter;
					$totalCriteriaDateWise[$mon][$dd]["validRegTotal"]+=$counter;
					$totalSourceCriteriaWise[$i]["validReg"]+=$counter;
					$totalAll["validReg"]+=$counter;
				}
				
			}
			else{
				if($age>=18 && $age<=21){
					$countSourceDayCriteriaWise[$i][$mon][$dd]["regF1"]+=$counter;
					$totalSourceCriteriaWise[$i]["regF1"]+=$counter;
					$totalCriteriaDateWise[$mon][$dd]["regF1"]+=$counter;
					$totalAll["regF1"]+=$counter;
				}
				elseif($age>=22 && $age<=35){
					$countSourceDayCriteriaWise[$i][$mon][$dd]["regF2"]+=$counter;
					$totalSourceCriteriaWise[$i]["regF2"]+=$counter;
					$totalCriteriaDateWise[$mon][$dd]["regF2"]+=$counter;
					$totalAll["regF2"]+=$counter;
					$countSourceDayCriteriaWise[$i][$mon][$dd]["validReg"]+=$counter;
					$totalCriteriaDateWise[$mon][$dd]["validRegTotal"]+=$counter;
					$totalSourceCriteriaWise[$i]["validReg"]+=$counter;
					$totalAll["validReg"]+=$counter;
				}
				if($age>=36){
					$countSourceDayCriteriaWise[$i][$mon][$dd]["regF3"]+=$counter;
					$totalSourceCriteriaWise[$i]["regF3"]+=$counter;
					$totalCriteriaDateWise[$mon][$dd]["regF3"]+=$counter;
					$totalAll["regF3"]+=$counter;
					$countSourceDayCriteriaWise[$i][$mon][$dd]["validReg"]+=$counter;
					$totalCriteriaDateWise[$mon][$dd]["validRegTotal"]+=$counter;
					$totalSourceCriteriaWise[$i]["validReg"]+=$counter;
					$totalAll["validReg"]+=$counter;
				}
				
			}
			
			$totalDateWise[$mon][$dd]["reg"]+=$counter;
			
		}while($row_e=mysql_fetch_array($res_e));
	}
	
	if($paidPrivilage)
	{
		$res_p90=mysql_query_decide($sql_subscription_90,$db) or die(mysql_error_js());
		if($row_p90=mysql_fetch_array($res_p90))
		{
			do{
				
				$src=$row_p90['src'];
				$counter=$row_p90['cnt'];
				$i=array_search($src,$srcarr);
				$dd=$row_p90['dd'];
				$mon=$row_p90['mon'];
				
				$countPaidSourceDayWise[$i][$mon][$dd]["90"]+=$counter;
				$totalCountPaidSourceWise[$i]["90"]+=$counter;			
				$totalCountPaidDayWise[$mon][$dd]["90"]+=$counter;
				$totalCountPaid["90"]+=$counter;
			}while($row_p90=mysql_fetch_array($res_p90));
		}
		$res_p45=mysql_query_decide($sql_subscription_45,$db) or die(mysql_error_js());
		if($row_p45=mysql_fetch_array($res_p45))
		{
			do{
				$src=$row_p45['src'];
				$counter=$row_p45['cnt'];
				$i=array_search($src,$srcarr);
				$dd=$row_p45['dd'];
				$mon=$row_p45['mon'];
				
				$countPaidSourceDayWise[$i][$mon][$dd]["45"]+=$counter;
				$totalCountPaidSourceWise[$i]["45"]+=$counter;			
				$totalCountPaidDayWise[$mon][$dd]["45"]+=$counter;
				$totalCountPaid["45"]+=$counter;
			}while($row_p45=mysql_fetch_array($res_p45));
		}
		
		if($currentFlagPaid45)
		{
			$res_current_paid45=mysql_query_decide($sql_current_subscription45,$db) or die(mysql_error_js());
			if($row_current_paid45=mysql_fetch_array($res_current_paid45))
			{
				do{
					if(($duplicate!="A" && $row_current_paid45["DUPLICATE"]==$duplicate) || ($duplicate=="A"))
					{
						$src=$row_current_paid45['src'];
						$counter=$row_current_paid45['cnt'];
						$i=array_search($src,$srcarr);
						$dd=$row_current_paid45['dd'];
						$mon=$row_current_paid45['mon'];
						
						$countPaidSourceDayWise[$i][$mon][$dd]["45"]+=$counter;
						$totalCountPaidSourceWise[$i]["45"]+=$counter;			
						$totalCountPaidDayWise[$mon][$dd]["45"]+=$counter;
						$totalCountPaid["45"]+=$counter;

					}
				}while($row_current_paid45=mysql_fetch_array($res_current_paid45));
			}
		}
		if($currentFlagPaid90)
		{
			$res_current_paid90=mysql_query_decide($sql_current_subscription90,$db) or die(mysql_error_js());
			if($row_current_paid90=mysql_fetch_array($res_current_paid90))
			{
				do{
					if(($duplicate!="A" && $row_current_paid90["DUPLICATE"]==$duplicate) || ($duplicate=="A"))
					{
						$src=$row_current_paid90['src'];
						$counter=$row_current_paid90['cnt'];
						$i=array_search($src,$srcarr);
						$dd=$row_current_paid90['dd'];
						$mon=$row_current_paid90['mon'];
						
						$countPaidSourceDayWise[$i][$mon][$dd]["90"]+=$counter;
						$totalCountPaidSourceWise[$i]["90"]+=$counter;			
						$totalCountPaidDayWise[$mon][$dd]["90"]+=$counter;
						$totalCountPaid["90"]+=$counter;

					}
				}while($row_current_paid90=mysql_fetch_array($res_current_paid90));
			}
		}
	}
	
	//current previous 3 days calculations:
	if($currentFlag)
	{
		
		$res_current=mysql_query_decide($sql_current_profile_complete,$db) or die(mysql_error_js());
		if($row_current=mysql_fetch_array($res_current))
		{
			do{
				if(($duplicate!="A" && $row_current["DUPLICATE"]==$duplicate) || ($duplicate=="A"))
				{	
					$src=$row_current['src'];
					$counter=$row_current['cnt'];
					$i=array_search($src,$srcarr);
					$dd=$row_current['dd'];
					$mon=$row_current['mon'];
					$age=$row_current['AGE'];
					$gender=$row_current['GENDER'];
					
					if($gender=="M"){
						if($age>=21 && $age<=24){
							$countSourceDayCriteriaWise[$i][$mon][$dd]["regM1"]+=$counter;
							$totalSourceCriteriaWise[$i]["regM1"]+=$counter;
							$totalCriteriaDateWise[$mon][$dd]["regM1"]+=$counter;
							$totalAll["regM1"]+=$counter;
						}
						elseif($age>=25 && $age<=35){
							$countSourceDayCriteriaWise[$i][$mon][$dd]["regM2"]+=$counter;
							$totalSourceCriteriaWise[$i]["regM2"]+=$counter;
							$totalCriteriaDateWise[$mon][$dd]["regM2"]+=$counter;
							$totalAll["regM2"]+=$counter;
							$countSourceDayCriteriaWise[$i][$mon][$dd]["validReg"]+=$counter;
							$totalCriteriaDateWise[$mon][$dd]["validRegTotal"]+=$counter;
							$totalSourceCriteriaWise[$i]["validReg"]+=$counter;
							$totalAll["validReg"]+=$counter;
						}
						if($age>=36){
							$countSourceDayCriteriaWise[$i][$mon][$dd]["regM3"]+=$counter;
							$totalSourceCriteriaWise[$i]["regM3"]+=$counter;
							$totalCriteriaDateWise[$mon][$dd]["regM3"]+=$counter;
							$totalAll["regM3"]+=$counter;
							$countSourceDayCriteriaWise[$i][$mon][$dd]["validReg"]+=$counter;
							$totalCriteriaDateWise[$mon][$dd]["validRegTotal"]+=$counter;
							$totalSourceCriteriaWise[$i]["validReg"]+=$counter;
							$totalAll["validReg"]+=$counter;
						}
						
					}
					else{
						if($age>=18 && $age<=21){
							$countSourceDayCriteriaWise[$i][$mon][$dd]["regF1"]+=$counter;
							$totalSourceCriteriaWise[$i]["regF1"]+=$counter;
							$totalCriteriaDateWise[$mon][$dd]["regF1"]+=$counter;
							$totalAll["regF1"]+=$counter;
						}
						elseif($age>=22 && $age<=35){
							$countSourceDayCriteriaWise[$i][$mon][$dd]["regF2"]+=$counter;
							$totalSourceCriteriaWise[$i]["regF2"]+=$counter;
							$totalCriteriaDateWise[$mon][$dd]["regF2"]+=$counter;
							$totalAll["regF2"]+=$counter;
							$countSourceDayCriteriaWise[$i][$mon][$dd]["validReg"]+=$counter;
							$totalCriteriaDateWise[$mon][$dd]["validRegTotal"]+=$counter;
							$totalSourceCriteriaWise[$i]["validReg"]+=$counter;
							$totalAll["validReg"]+=$counter;
						}
						if($age>=36){
							$countSourceDayCriteriaWise[$i][$mon][$dd]["regF3"]+=$counter;
							$totalSourceCriteriaWise[$i]["regF3"]+=$counter;
							$totalCriteriaDateWise[$mon][$dd]["regF3"]+=$counter;
							$totalAll["regF3"]+=$counter;
							$countSourceDayCriteriaWise[$i][$mon][$dd]["validReg"]+=$counter;
							$totalCriteriaDateWise[$mon][$dd]["validRegTotal"]+=$counter;
							$totalSourceCriteriaWise[$i]["validReg"]+=$counter;
							$totalAll["validReg"]+=$counter;
						}
						
					}
					$totalDateWise[$mon][$dd]["reg"]+=$counter;		
				}
			}while($row_current=mysql_fetch_array($res_current));
		}
	}
	if($formatType=="excel"){
		$data.="\tOnline Marketing Tie-Up Registrations MIS\n";
		$data.="\t".$ddate_date1." - ".$month1Str."-".$ddate_yyyy1."  TO ".$ddate_date2." - ".$month2Str." -".$ddate_yyyy2."\t\t\n";
		$data.="\tActivated:".$displayActiveStr."\tPhone Verified:".$displayMobileVerifyStr."\tPhoto Present?:".$displayPhotoStr."\tMother Tongue:".$displayMtongueStr."\tState:".$displayLocationStr."\tDuplicate?:".$displayDuplicateStr."\n";
		$data.="Source ID\tCategory\t";
		$data.=$month1Str."\t";
		for($i=0;$i<31-$ddate_date1;$i++){
			 $data.="\t";
		}
		if($ddate_mon2-$ddate_mon1>1){
			$data.=$month_between_Str;
			for($i=0;$i<31;$i++){
				 $data.="\t";
			}
		}
		$data.=$month2Str."\n";
		$data.="\t\t";
		foreach($ddarr as $key=>$val){
			 $data.=$val["date"]."\t";
		}
		$data.="Total\t";
		
		foreach($srcarr as $key=>$val){
			$i=array_search($val,$srcarr);
		   $data.="\n".$val."\t";
			$data.="Male 21 - 24\t";
			foreach($ddarr as $k=>$v){
				$data.=$countSourceDayCriteriaWise[$i][$v["mon"]][$v["date"]]["regM1"]."\t";
			}
			$data.=$totalSourceCriteriaWise[$i]["regM1"]."\t";
			$data.="\n\tMale 25 - 35\t";
			foreach($ddarr as $k=>$v){
				$data.=$countSourceDayCriteriaWise[$i][$v["mon"]][$v["date"]]["regM2"]."\t";
			}
			$data.=$totalSourceCriteriaWise[$i]["regM2"]."\t";
			$data.="\n\tMale 36 and above\t";
			foreach($ddarr as $k=>$v){
				$data.=$countSourceDayCriteriaWise[$i][$v["mon"]][$v["date"]]["regM3"]."\t";
			}
			$data.=$totalSourceCriteriaWise[$i]["regM3"]."\t";
			$data.="\n\tFemale 18 - 21\t";
			foreach($ddarr as $k=>$v){
				$data.=$countSourceDayCriteriaWise[$i][$v["mon"]][$v["date"]]["regF1"]."\t";
			}
			$data.=$totalSourceCriteriaWise[$i]["regF1"]."\t";
			$data.="\n\tFemale 22 - 35\t";
			foreach($ddarr as $k=>$v){
				$data.=$countSourceDayCriteriaWise[$i][$v["mon"]][$v["date"]]["regF2"]."\t";
			}
			$data.=$totalSourceCriteriaWise[$i]["regF2"]."\t";
			$data.="\n\tFemale 36 and above\t";
			foreach($ddarr as $k=>$v){
				$data.=$countSourceDayCriteriaWise[$i][$v["mon"]][$v["date"]]["regF3"]."\t";
			}
			$data.=$totalSourceCriteriaWise[$i]["regF3"]."\t";
			$data.="\n\tTotal valid registrations\t";
			foreach($ddarr as $k=>$v){
				$data.=$countSourceDayCriteriaWise[$i][$v["mon"]][$v["date"]]["validReg"]."\t";
			}
			$data.=$totalSourceCriteriaWise[$i]["validReg"]."\t";
			$data.="\n\tTotal hits\t";
			foreach($ddarr as $k=>$v){
				$data.=$countHitsSourceDayWise[$i][$v["mon"]][$v["date"]]["hit"]."\t";
			}
			$data.=$totalCountHitsSourceWise[$i]["hit"]."\t";
			if($paidPrivilage){
				$data.="\n\tPaid in first 45 days\t";
				foreach($ddarr as $k=>$v){
					$data.=$countPaidSourceDayWise[$i][$v["mon"]][$v["date"]]["45"]."\t";
				}
				$data.=$totalCountPaidSourceWise[$i]["45"]."\t";
				$data.="\n\tPaid in first 90 days\t";
				foreach($ddarr as $k=>$v){
					$data.=$countPaidSourceDayWise[$i][$v["mon"]][$v["date"]]["90"]."\t";
				}
				$data.=$totalCountPaidSourceWise[$i]["90"]."\t";
			}			
	   }
	   
		$data.="\nTotal\t";
		$data.="Male 21 - 24\t";
		foreach($ddarr as $k=>$v){
				$data.=$totalCriteriaDateWise[$v["mon"]][$v["date"]]["regM1"]."\t";
			}
		$data.=$totalAll["regM1"]."\t";
		$data.="\n\tMale 25 - 35\t";
		foreach($ddarr as $k=>$v){
				$data.=$totalCriteriaDateWise[$v["mon"]][$v["date"]]["regM2"]."\t";
			}
		$data.=$totalAll["regM2"]."\t";
		$data.="\n\tMale 36 and above\t";
		foreach($ddarr as $k=>$v){
				$data.=$totalCriteriaDateWise[$v["mon"]][$v["date"]]["regM3"]."\t";
			}
		$data.=$totalAll["regM3"]."\t";
		$data.="\n\tFemale 18 - 21\t";
		foreach($ddarr as $k=>$v){
				$data.=$totalCriteriaDateWise[$v["mon"]][$v["date"]]["regF1"]."\t";
			}
		$data.=$totalAll["regF1"]."\t";
		$data.="\n\tFemale 22 - 35\t";
		foreach($ddarr as $k=>$v){
				$data.=$totalCriteriaDateWise[$v["mon"]][$v["date"]]["regF2"]."\t";
			}
		$data.=$totalAll["regF2"]."\t";
		$data.="\n\tFemale 36 and above\t";
		foreach($ddarr as $k=>$v){
				$data.=$totalCriteriaDateWise[$v["mon"]][$v["date"]]["regF3"]."\t";
			}
		$data.=$totalAll["regF3"]."\t";
		$data.="\n\tTotal valid registrations\t";
		foreach($ddarr as $k=>$v){
				$data.=$totalCriteriaDateWise[$v["mon"]][$v["date"]]["validRegTotal"]."\t";
			}
		$data.=$totalAll["validRegTotal"]."\t";
		$data.="\n\tTotal hits\t";
		foreach($ddarr as $k=>$v){
				$data.=$totalCountHitsDayWise[$v["mon"]][$v["date"]]["hit"]."\t";
			}
		$data.=$totalCountHits["hit"]."\t";
		if($paidPrivilage){
			$data.="\n\tPaid in first 45 days\t";
			foreach($ddarr as $k=>$v){
				$data.=$totalCountPaidDayWise[$v["mon"]][$v["date"]]["45"]."\t";
			}
			$data.=$totalCountPaid["45"]."\t";
			$data.="\n\tPaid in first 90 days\t";
			foreach($ddarr as $k=>$v){
				$data.=$totalCountPaidDayWise[$v["mon"]][$v["date"]]["90"]."\t";
			}
			$data.=$totalCountPaid["90"]."\t";
		}
		
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Registration_MIS_".$sourcegp.".csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		print chr(255) . chr(254) . mb_convert_encoding($data, 'UTF-16LE', 'UTF-8');
		exit;
	}
	else{
	
	//otherVsriables:
		$smarty->assign("durationCheck",$submitMis);
		$smarty->assign("duration",$duration);
		
		$month =  date("M",mktime(0,0,0,$ddate_mon+1,0,0));
		$smarty->assign("MONTH",$month);
		$smarty->assign("YEAR",$ddate_yyyy);
		$smarty->assign("dflag",1);
		$smarty->assign("dt","$ddate_mon-$ddate_yyyy");
		$monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
	//Filters Criteria Assigning
		$smarty->assign("Activate",$displayActiveStr);
		$smarty->assign("mobileVerified",$displayMobileVerifyStr);
		$smarty->assign("photo",$displayPhotoStr);
		$smarty->assign("mTongue",$displayMtongueStr);
		$smarty->assign("state",$displayLocationStr);
		$smarty->assign("duplicate",$displayDuplicateStr);

	//registrations assigning 
		$smarty->assign("countSourceDayCriteriaWise",$countSourceDayCriteriaWise);
        $smarty->assign("totalSourceCriteriaWise",$totalSourceCriteriaWise);
        $smarty->assign("totalDateWise",$totalDateWise);
        $smarty->assign("totalAll",$totalAll);
        $smarty->assign("totalValidRegistrationSource",$totalValidRegistrationSource);
        $smarty->assign("totalCriteriaDateWise",$totalCriteriaDateWise);
    //source hits assigning     
		$smarty->assign("countHitsSourceDayWise",$countHitsSourceDayWise);
		$smarty->assign("totalCountHitsSourceWise",$totalCountHitsSourceWise);			
		$smarty->assign("totalCountHitsDayWise",$totalCountHitsDayWise);
		$smarty->assign("totalCountHits",$totalCountHits);
      //subscription assigning  
        $smarty->assign("countPaidSourceDayWise",$countPaidSourceDayWise);
		$smarty->assign("totalCountPaidSourceWise",$totalCountPaidSourceWise);			
		$smarty->assign("totalCountPaidDayWise",$totalCountPaidDayWise);
		$smarty->assign("totalCountPaid",$totalCountPaid);
        
        $smarty->assign("source",$sourcegp);
        $smarty->assign("srcarr",$srcarr);

        $smarty->assign("ddarr",$ddarr);
       
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
        $smarty->display("usr_hits.htm");
	}
}
else
{
	$msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"login.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
