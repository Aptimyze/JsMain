<?php
include_once("connect.inc");
$db=connect_misdb();
$db2=connect_master();
include_once("../profile/pg/functions.php");

$data=authenticated($checksum);

if(isset($data)|| $JSIndicator)
{
	$searchType='';
	$searchFlag=0;
	$searchMonth='';
	$searchYear='';
	$monthDays=0;
	$searchKeyArray1=array('ALL'=>'ALL','N'=>'New','E'=>'Edit'); 
	$searchKeyArray2=array('ALL'=>'ALL','P'=>'Paid','F'=>'Unpaid');
	$searchKeyArray3=array('ALL'=>'ALL','S'=>'Separated','A'=>'Annulled');
	$searchKeyArray4=array('ALL'=>'ALL','Underage'=>'Underage','Fake'=>'Fake','Improper'=>'Invalid Username','other'=>'Other');

	if(!$today)
        $today=date("Y-m-d");
        list($todYear,$todMonth,$todDay)=explode("-",$today);
	if($outside)
	{
		$CMDGo='Y';
		$searchType='ALL';
		$searchMonth=$todMonth;
		$searchYear=$todYear;
		$monthDays=$todDay-1;
	}
	if($CMDGo)
	{
		if($searchMonth=='')
			$searchMonth=$monthEntered;
		if($searchYear=='')
			$searchYear=$yearEntered;
		if($searchType1=='')
		{
			 $searchType1=$typeEntered1;
		}
		if($searchType1!='ALL')
		{
			$searchTypePrint1=$searchKeyArray1[$searchType1];
		}
		else
		$searchTypePrint1='ALL';
		

		if($searchType2=='')
                {
                        $searchType2=$typeEntered2;
                }
                if($searchType2!='ALL')
                {
                        $searchTypePrint2=$searchKeyArray2[$searchType2];

                }
                else
                $searchTypePrint2='ALL';

		if($searchType3=='')
                {
                        $searchType3=$typeEntered3;
                }
                if($searchType3!='ALL')
                {
                        $searchTypePrint3=$searchKeyArray3[$searchType3];

                }
                else
                $searchTypePrint3='ALL';

		if($searchType4=='')
                {
                        $searchType4=$typeEntered4;
                }
                if($searchType4!='ALL')
                {
                        $searchTypePrint4=$searchKeyArray4[$searchType4];

                }
                else
                $searchTypePrint4='ALL';



		if($monthDays==0)
		{
		if(($searchMonth=='Jan')||($searchMonth=='Mar')||($searchMonth=='May')||($searchMonth=='Jul')
		   ||($searchMonth=='Aug')||($searchMonth=='Oct')||($searchMonth=='Dec'))
			$monthDays=31;
		elseif(($searchMonth=='Apr')||($searchMonth=='Jun')||($searchMonth=='Sep')||($searchMonth=='Nov'))
                        	$monthDays=30;
			elseif(($searchYear%4==0)&&($searchYear%100!=0)||($searchYear%400==0))
				$monthDays=29;
				else
				$monthDays=28;
		}
		$k=1;
		while($k<=$monthDays)
		{
			$monthDaysArray[]=$k;
			$k++;
		}
		

		//$sqlnew="SELECT COUNT  AS CNT,MOD_TYPE,SUBS_TYPE,MSTATUS,DAY(ENTRY_DT) AS DAYNO  FROM  MIS.TRACK_DELETED_PROFILES  WHERE  ENTRY_DT BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' ";

		if($searchType4!="ALL")
			if($searchType4!="other")
				$sql= "SELECT DISTINCT(PROFILEID), TIME FROM jsadmin.DELETED_PROFILES WHERE REASON LIKE '%$searchType4%' AND TIME BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' AND  RETRIEVED_BY= ''";
			else
				$sql= "SELECT DISTINCT(PROFILEID), TIME FROM jsadmin.DELETED_PROFILES WHERE TIME BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' AND  RETRIEVED_BY= '' AND PROFILEID NOT IN (SELECT PROFILEID FROM jsadmin.DELETED_PROFILES WHERE REASON LIKE '%Underage%' OR REASON LIKE '%Fake%' OR REASON LIKE '%Improper%')";
		else
			$sql= "SELECT DISTINCT(PROFILEID), TIME FROM jsadmin.DELETED_PROFILES WHERE TIME BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' AND  RETRIEVED_BY='' ";
		$res= mysql_query_decide($sql) or die(mysql_error_js());
		if(mysql_num_rows($res))
		{
			while($row= mysql_fetch_array($res))
			{
				$date= $row["TIME"];
				$days=explode("-",$date);
				$t= $days[2];
				if($profiles[$t]!='')
					 $profiles[$t]=$row['PROFILEID'].",".$profiles[$t];
				else
					$profiles[$t]= $row['PROFILEID'];
			}
			$i=1;
			while($i<=$monthDays)
			{
				if($i<10)
					$j="0".$i;
				else
					$j= $i;
				$pid=$profiles[$j];
				if($pid!= '')
				{
					$sql1= "SELECT COUNT(*) AS CNT FROM newjs.JPROFILE WHERE PROFILEID IN ($pid) ";
					if($searchType1!="ALL")
                			{
                        			if($searchType1== 'N')
                                			$sql1.=" AND SCREENING=0 ";
                        			elseif($searchType1=='E')
                                			$sql1.=" AND SCREENING<> 0 AND SCREENING<4094303";
	                		}
					else
						$sql1.=" AND SCREENING<>4094303";	
        	        		if($searchType2!="ALL")
        	        		{	
	                        		if($searchType2== 'P')
        	                        		$sql1.= " AND SUBSCRIPTION <> ''";
                	        		else
                        	        		$sql1.= " AND SUBSCRIPTION= ''";
               				}
	                		if($searchType3!="ALL")
        	                		$sql1.=" AND MSTATUS='$searchType3' ";
					else
						$sql1.=" AND MSTATUS IN ('A','S') ";
					
					$res1=mysql_query_decide($sql1) or die(mysql_error_js());
	                		while($row1=mysql_fetch_assoc($res1))
	                		{
						$cnt=$row1['CNT'];
	          	             		$totSearchDaynew[$i]+=$cnt;
                                        	$grandTotalnew+=$cnt;
		                	}
				}
				$i++;
			}

		}
		$smarty->assign('grandTotalnew',$grandTotalnew);
		$smarty->assign('totSearchDaynew',$totSearchDaynew);
		$smarty->assign('searchTypePrint1',$searchTypePrint1);
		$smarty->assign('searchTypePrint2',$searchTypePrint2);
		$smarty->assign('searchTypePrint3',$searchTypePrint3);
		$smarty->assign('searchTypePrint4',$searchTypePrint4);
	        $smarty->assign('monthDaysArray',$monthDaysArray);
		$smarty->assign('searchMonth',$searchMonth);
		$smarty->assign('searchYear',$searchYear);
	}
		$k=-4;
		while($k<=5)
		{
			$yearArray[]=$todYear+$k;
			$k++;
		}
		$monthArray=array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec');
		$smarty->assign("typeEntered1",$typeEntered1);
		$smarty->assign("typeEntered2",$typeEntered2);
		$smarty->assign("typeEntered3",$typeEntered3);
		$smarty->assign("typeEntered4",$typeEntered4);
		$smarty->assign('yearArray',$yearArray);
		$smarty->assign('monthArray',$monthArray);
		$smarty->assign('todYear',$todYear);
		$smarty->assign('todMonth',$todMonth);
		$smarty->assign('monthDaysArray',$monthDaysArray);
                $smarty->assign('searchMonth',$searchMonth);
                $smarty->assign('searchYear',$searchYear);
		$smarty->assign('checksum',$checksum);
		$smarty->assign('searchKeyArray1',$searchKeyArray1);
		$smarty->assign('searchKeyArray2',$searchKeyArray2);
		$smarty->assign('searchKeyArray3',$searchKeyArray3);
		$smarty->assign('searchKeyArray4',$searchKeyArray4);
		$smarty->display("deleted_profiles_count.htm");
}
else
{
	$smarty->assign('$user',$username);
	$smarty->display("jsconnectError.tpl");
}
?>
