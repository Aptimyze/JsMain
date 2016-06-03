<?php
include_once("connect.inc");
include_once("../profile/pg/functions.php");

$db=connect_misdb();
$db2=connect_master();

	$searchType='';
	//$searchFlag=0;
	$searchMonth='';
	$searchYear='';
	$monthDays=0;
	//$searchKeyArray1=array('ALL'=>'ALL','N'=>'New','E'=>'Edit'); 
	//$searchKeyArray2=array('ALL'=>'ALL','P'=>'Paid','F'=>'Unpaid');
	//$searchKeyArray3=array('ALL'=>'ALL','S'=>'Separated','A'=>'Annulled');

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
		if($searchMonth=='')
			$searchMonth=$monthEntered;
		if($searchYear=='')
			$searchYear=$yearEntered;
	/*	if($searchType1=='')
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
                $searchTypePrint3='ALL';*/


		if($monthDays==0)
		{
		if(($searchMonth=='01')||($searchMonth=='03')||($searchMonth=='05')||($searchMonth=='07')
		   ||($searchMonth=='08')||($searchMonth=='10')||($searchMonth=='12'))
			$monthDays=31;
		elseif(($searchMonth=='04')||($searchMonth=='06')||($searchMonth=='09')||($searchMonth=='11'))
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
		
		$sql_1="SELECT COUNT(*) AS CNT FROM newjs.JPROFILE WHERE SOURCE='dgm_07' AND INCOMPLETE='Y' AND ENTRY_DT BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' ";
		$sql_2="SELECT COUNT(*) AS CNT FROM newjs.JPROFILE WHERE SOURCE='dgm_07' AND ACTIVATED='D' AND ENTRY_DT BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' ";
		$sql_3="SELECT COUNT AS CNT FROM MIS.SOURCE_MEMBERS  WHERE SOURCEID='dgm_07' AND ENTRY_MODIFY='E' AND ENTRY_DT BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' ";
		

		$sqlnew="SELECT PROFILEID,USERNAME,PASSWORD,EMAIL,IPADD,ENTRY_DT FROM  newjs.JPROFILE  WHERE  SOURCE='dgm_07' AND ACTIVATED='Y'  AND SUBSCRIPTION='' AND ENTRY_DT BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays'";
		
		/*if($searchType1!="ALL")
			$sqlnew.=" AND MOD_TYPE='$searchType1' ";
		if($searchType2!="ALL")
			$sqlnew.=" AND SUBS_TYPE='$searchType2' ";
		if($searchType3!="ALL")
                        $sqlnew.=" AND MSTATUS='$searchType3' ";*/
		$result_1=mysql_query_decide($sql_1,$db) or die(mysql_error_js());	
		$result_2=mysql_query_decide($sql_2,$db) or die(mysql_error_js()); 
		$result_3=mysql_query_decide($sql_3,$db) or die(mysql_error_js());
		$resultnew=mysql_query_decide($sqlnew,$db) or die(mysql_error_js());
		$myrow_1=mysql_fetch_array($result_1);
		$myrow_2=mysql_fetch_array($result_2);

		
		$total_hits=0;
		while($myrow_3=mysql_fetch_array($result_3))
		{
			$total_hits+=$myrow_3['CNT'];
		}

		$incomplete_profiles=$myrow_1['CNT'];
		$deleted_profiles=$myrow_2['CNT'];
		$i=0;
		while($myrownew=mysql_fetch_assoc($resultnew))
		{
			$dataarr[$i]["username"]=$myrownew["USERNAME"];
                        $dataarr[$i]["password"]=$myrownew["PASSWORD"];
			$dataarr[$i]["email"]=$myrownew["EMAIL"];
                        $dataarr[$i]["ipadd"]=$myrownew["IPADD"];
			$dataarr[$i]["entry_dt"]=$myrownew["ENTRY_DT"];
			$dataarr[$i]["profilechecksum"]=$myrownew["PROFILEID"];
			//$totSearchMonthnew[$s][$d][$p]+=$myrownew["CNT"];
			//$dataArraynew1[$t]=$myrownew["CNT"];
			//$totSearchDaynew[$t]+=$myrownew["CNT"];
			//$dataArraynew[$s][$d][$p][$t]=$myrownew["CNT"];
			//$grandTotalnew+=$myrownew["CNT"];
			$i++;
				
		}
		$smarty->assign('dataarr',$dataarr);
		$smarty->assign('incomplete_profiles',$incomplete_profiles);
		$smarty->assign('deleted_profiles',$deleted_profiles);
		$smarty->assign('complete_profiles',$i);
		$smarty->assign('total_hits',$total_hits);
		//$smarty->assign('grandTotalnew',$grandTotalnew);
		//$smarty->assign('totSearchDaynew',$totSearchDaynew);
		//$smarty->assign('searchTypePrint1',$searchTypePrint1);
		//$smarty->assign('searchTypePrint2',$searchTypePrint2);
		//$smarty->assign('searchTypePrint3',$searchTypePrint3);
		
	        $smarty->assign('monthDaysArray',$monthDaysArray);
		$smarty->assign('searchMonth',$searchMonth);
		$smarty->assign('searchYear',$searchYear);
		unset($dataarr);
                unset($i);
                unset($total_hits);
		
	}
		$k=-4;
		while($k<=5)
		{
			$yearArray[]=$todYear+$k;
			$k++;
		}
		$monthArray=array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
		$smarty->assign("typeEntered1",$typeEntered1);
		$smarty->assign("typeEntered2",$typeEntered2);
		$smarty->assign("typeEntered3",$typeEntered3);
		$smarty->assign('yearArray',$yearArray);
		$smarty->assign('monthArray',$monthArray);
		$smarty->assign('todYear',$todYear);
		$smarty->assign('todMonth',$todMonth);
		$smarty->assign('monthDaysArray',$monthDaysArray);
                $smarty->assign('searchMonth',$searchMonth);
                $smarty->assign('searchYear',$searchYear);
		$smarty->assign('CHECKSUM',$checksum);
		//$smarty->assign('searchKeyArray1',$searchKeyArray1);
		//$smarty->assign('searchKeyArray2',$searchKeyArray2);
		//$smarty->assign('searchKeyArray3',$searchKeyArray3);
		$smarty->display("dgm_affiliate.htm");
?>
