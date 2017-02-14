<?php
include_once("connect.inc");
include_once("../profile/pg/functions.php");

$db=connect_misdb();

$data=authenticated($checksum);

if(isset($data)|| $JSIndicator)
{
	$searchType='';
	$searchFlag=0;
	$searchMonth='';
	$searchYear='';
	$monthDays=0;
	$sql="SELECT ID,LABEL FROM newjs.MTONGUE";
	$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
	$searchKeyArray=array();
	while($row=mysql_fetch_array($result))
	{
		$id=$row['ID'];
		$searchKeyArray[$id]=$row['LABEL'];
	}
	//print_r($searchKeyArray);
	//$index=array('A','D','C','I');
	
//	$searchTypeArray=array('View Similar Profile','My Relevant Matches','Advanced','eClassified','PG','HomePage','ISearch','Clusters','Keyword','Mailer','NRI','Online','Photo','Quick','Cosmo','Software','Next Frm HmPg','Similar Cont on Home Pg','Community');
	if(!$today)
        $today=date("Y-m-d");
        list($todYear,$todMonth,$todDay)=explode("-",$today);
	if($outside)
	{
		$CMDGo='Y';
		$searchType='ALL';
		$searchMonth=$todMonth;
		$searchYear=$todYear;
		$monthDays=$todDay;
	}
	if($CMDGo)
	{
		$searchFlag=1;
		if($searchMonth=='')
			$searchMonth=$monthEntered;
		if($searchYear=='')
			$searchYear=$yearEntered;
		if($searchType=='')
		{
			$searchType=$typeEntered;
		}
		if($searchType!='ALL')
		{
			$searchTypePrint=$searchKeyArray[$searchType];
			
		}
		else
		$searchTypePrint='ALL';
		if($monthDays==0)
		{
			$monthDays=date("t",mktime(0,0,0,$searchMonth,1,$searchYear));
		/*if(($searchMonth=='01')||($searchMonth=='03')||($searchMonth=='05')||($searchMonth=='07')
		   ||($searchMonth=='08')||($searchMonth=='10')||($searchMonth=='12'))
			$monthDays=31;
		elseif(($searchMonth=='04')||($searchMonth=='06')||($searchMonth=='09')||($searchMonth=='11'))
                        	$monthDays=30;
			elseif(($searchYear%4==0)&&($searchYear%100!=0)||($searchYear%400==0))
				$monthDays=29;
				else
				$monthDays=28;*/
		}
		$k=1;
		while($k<=$monthDays)
		{
			$monthDaysArray[]=$k;
			$k++;
		}
		if($searchType!="ALL")
		{
			 $sqlnew="SELECT COUNT(DISTINCT(M.MOBILE)) AS CNT ,DAY(M.ENTRY_DT) AS DAYNO  FROM  newjs.MOBILE_VERIFICATION_SMS AS M, newjs.JPROFILE AS J WHERE  J.PHONE_MOB=M.MOBILE AND J.MTONGUE='$searchType' AND M.ENTRY_DT BETWEEN '$searchYear-$searchMonth-01 00:00:00' AND '$searchYear-$searchMonth-$monthDays 23:59:59' GROUP BY DAYNO";
			
			
			$sql="SELECT COUNT(DISTINCT(S.MOBILE)) AS CNT ,DAY(S.ENTRY_DT) AS DAYNO FROM newjs.SENT_VERIFICATION_SMS  AS S,incentive.MAIN_ADMIN_POOL AS P WHERE P.PROFILEID=S.PROFILEID AND P.MTONGUE ='$searchType' AND S.ENTRY_DT  BETWEEN '$searchYear-$searchMonth-01 00:00:00' AND '$searchYear-$searchMonth-$monthDays 23:59:59' GROUP BY DAYNO";
			
			$resultnew=mysql_query_decide($sqlnew,$db) or die(mysql_error_js());
			$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
			while($myrownew=mysql_fetch_assoc($resultnew))
			{
			 	$day=$myrownew["DAYNO"];
				//$type=$myrownew["P.SOURCE"];
				/*if($myrownew["TYPE"]=='A')
					$acceptence+=$myrownew["CNT"];
				elseif($myrownew["TYPE"]=='D')
					$decline+=$myrownew["CNT"];
				elseif($myrownew["TYPE"]=='C')
					$cancel+=$myrownew["CNT"];
				elseif($myrownew["TYPE"]=='I')
					$initiated+=$myrownew["CNT"];*/
			
				//$dataArraynew[$day][$type]=$myrownew["CNT"];
				$dataArraynew[$day]=$myrownew["CNT"];
				$grandTotalnew+=$myrownew["CNT"];
			}
			while($myrow=mysql_fetch_assoc($result))
			{
				$s=$myrow["DAYNO"];
				$dataArray[$s]=$myrow["CNT"];
				$grandTotal+=$myrow["CNT"];
			}
			$smarty->assign('dataArraynew',$dataArraynew);
			$smarty->assign('grandTotalnew',$grandTotalnew);
			$smarty->assign('dataArray',$dataArray);
			$smarty->assign('grandTotal',$grandTotal);
		}
		else
		{
			  $sqlnew2="SELECT COUNT(DISTINCT(MOBILE)) AS CNT ,DAY(ENTRY_DT) AS DAYNO  FROM  newjs.MOBILE_VERIFICATION_SMS   WHERE  ENTRY_DT BETWEEN '$searchYear-$searchMonth-01 00:00:00' AND '$searchYear-$searchMonth-$monthDays 23:59:59' GROUP BY DAYNO";
			
			  $sql2="SELECT COUNT(DISTINCT(MOBILE)) AS TOTAL , DAY(ENTRY_DT) AS DAYNO FROM newjs.SENT_VERIFICATION_SMS WHERE  ENTRY_DT  BETWEEN '$searchYear-$searchMonth-01 00:00:00' AND '$searchYear-$searchMonth-$monthDays 23:59:59' GROUP BY DAYNO";			
			
			//$sql_total="SELECT COUNT(DISTINCT(PROFILEID)) AS TOTAL FROM newjs.SENT_VERIFICATION_SMS";
			//$sql_total_verified="SELECT COUNT(DISTINCT(J.PROFILEID)) AS CNT FROM newjs.MOBILE_VERIFICATION_SMS AS M ,newjs.JPROFILE AS J WHERE J.PHONE_MOB=M.MOBILE";
			$resultnew2=mysql_query_decide($sqlnew2,$db) or die(mysql_error_js());
			$result2=mysql_query_decide($sql2,$db) or die(mysql_error_js());
			//$result_total=mysql_query_decide($sql_total,$db) or die("$sql_total".mysql_error_js());
			//$result_total_verified=mysql_query_decide($sql_total_verified,$db) or die("$sql_total_verified".mysql_error_js());
			
			//echo $sql_total_verified;die();
			while($myrownew2=mysql_fetch_assoc($resultnew2))
                        {
                                        //$s=$myrownew2["SOURCE"];
                                        $d=$myrownew2["DAYNO"];
					//$t=$myrownew2["TYPE"];
					/*if($myrownew2["TYPE"]=='A')
                                        	$acceptence[$s]+=$myrownew2["CNT"];
                                	elseif($myrownew2["TYPE"]=='D')
                                        	$decline[$s]+=$myrownew2["CNT"];
                                	elseif($myrownew2["TYPE"]=='C')
                                        	$cancel[$s]+=$myrownew2["CNT"];
                                	elseif($myrownew2["TYPE"]=='I')
                                        	$initiated[$s]+=$myrownew2["CNT"];*/
					

                                        //$dataArraynew[$s][$d][$t]=$myrownew2["CNT"];
					$dataArraynew[$d]=$myrownew2["CNT"];
                                        //$totSearchMonthnew[$myrownew2["SOURCE"]]+=$myrownew2["CNT"];
                                        //$totSearchDaynew[$myrownew2["DAYNO"]]+=$myrownew2["CNT"];
                                        $grandTotalnew+=$myrownew2["CNT"];

                        }
			
			while($myrow=mysql_fetch_assoc($result2))
			{		
					//$s=$myrow["SOURCE"];
					$d=$myrow["DAYNO"];
					$dataArray[$d]=$myrow["TOTAL"];
					//$totSearchMonth[$myrow["SOURCE"]]+=$myrow["TOTAL"];
                                        //$totSearchDay[$myrow["DAYNO"]]+=$myrow["TOTAL"];
					$grandTotal+=$myrow["TOTAL"];
					
			}
			//$row_total=mysql_fetch_array($result_total);
			//$row_total_verified=mysql_fetch_array($result_total_verified);
			//$total_profile=$row_total['TOTAL'];
			//$total_profile_verified=$row_total_verified['CNT'];
			//$percentage_people=round(($total_profile_verified/$total_profile)*100,2);
			//assigning variables to smarty
			$smarty->assign('grandTotalnew',$grandTotalnew);
                        //$smarty->assign('totSearchDaynew',$totSearchDaynew);
                        $smarty->assign('dataArraynew',$dataArraynew);
                        //$smarty->assign('totSearchMonthnew',$totSearchMonthnew);
			$smarty->assign('grandTotal',$grandTotal);
			//$smarty->assign('totSearchDay',$totSearchDay);
			$smarty->assign('dataArray',$dataArray);
			$smarty->assign('searchTypePrint',$searchTypePrint);
			//$smarty->assign('totSearchMonth',$totSearchMonth);
			//$smarty->assign("TOTAL_PROFILE",$total_profile);
			//$smarty->assign("PERCENTAGE",$percentage_people);
		}
			$sql_total="SELECT COUNT(DISTINCT(MOBILE)) AS TOTAL FROM newjs.SENT_VERIFICATION_SMS";
                        $sql_total_verified="SELECT COUNT(DISTINCT(MOBILE)) AS CNT FROM newjs.MOBILE_VERIFICATION_SMS";
			$result_total=mysql_query_decide($sql_total,$db) or die("$sql_total".mysql_error_js());
                        $result_total_verified=mysql_query_decide($sql_total_verified,$db) or die("$sql_total_verified".mysql_error_js());
			$row_total=mysql_fetch_array($result_total);
                        $row_total_verified=mysql_fetch_array($result_total_verified);
                        $total_profile=$row_total['TOTAL'];
                        $total_profile_verified=$row_total_verified['CNT'];

			if($total_profile_verified==0 || $total_profile==0)
				$percentage_people=0;
			else
				$percentage_people=round(($total_profile_verified/$total_profile)*100,2);			 
			$smarty->assign("TOTAL_PROFILE",$total_profile);
	                $smarty->assign("PERCENTAGE",$percentage_people);

		//$smarty->assign('acceptence',$acceptence);
                //$smarty->assign('decline',$decline);
                //$smarty->assign('cancel',$cancel);
                //$smarty->assign('initiated',$initiated);
		$smarty->assign('monthDaysArray',$monthDaysArray);
		$smarty->assign('searchTypePrint',$searchTypePrint);
	        $smarty->assign('monthDaysArray',$monthDaysArray);
		$smarty->assign('searchFlag',$searchFlag);
		$smarty->assign('searchMonth',$searchMonth);
		$smarty->assign('searchYear',$searchYear);
		$smarty->assign('searchKeyArray',$searchKeyArray);
		//$smarty->assign("index",$index);
		$smarty->display("mobile_verification_count.htm");
	}
	else
	{
		$k=-4;
		while($k<=5)
		{
			$yearArray[]=$todYear+$k;
			$k++;
		}
		$monthArray=array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
		$smarty->assign('yearArray',$yearArray);
		$smarty->assign('monthArray',$monthArray);
		$smarty->assign('todYear',$todYear);
		$smarty->assign('todMonth',$todMonth);
		$smarty->assign('searchFlag',$searchFlag);
		$smarty->assign('CHECKSUM',$checksum);
		$smarty->assign('searchKeyArray',$searchKeyArray);
		$smarty->display("mobile_verification_count.htm");
	}
}
else
{
	$smarty->assign('$user',$username);
	$smarty->display("jsconnectError.tpl");
}
?>
