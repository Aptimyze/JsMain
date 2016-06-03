<?php
include_once("connect.inc");
include_once("../profile/pg/functions.php");

$db=connect_misdb();
$db2=connect_master();

$data=authenticated($checksum);

if(isset($data)|| $JSIndicator)
{
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
		
		

		//$sqlnew="SELECT P.BILLID AS BILLID,J.PROFILEID AS PROFILEID ,J.USERNAME AS USERNAME ,J.PASSWORD AS PASSWORD ,J.IPADD AS IPADD,J.ENTRY_DT ENTRY_DT1,P.AMOUNT AS AMOUNT,P.ENTRY_DT AS ENTRY_DT2,S.NAME  AS SERVICE  FROM  newjs.JPROFILE AS J,billing.PAYMENT_DETAIL AS P,billing.PURCHASES AS PR,billing.SERVICES AS S   WHERE  P.PROFILEID=J.PROFILEID AND PR.BILLID=P.BILLID AND S.SERVICEID=PR.SERVICEID AND J.SOURCE='dgm_07' AND J.ACTIVATED='Y'  AND J.SUBSCRIPTION!='' AND P.ENTRY_DT BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' ";
		$sqlnew="SELECT P.BILLID AS BILLID,PR.SERVICEID,PR.ADDON_SERVICEID,J.PROFILEID AS PROFILEID ,J.USERNAME AS USERNAME ,J.PASSWORD AS PASSWORD ,J.IPADD AS IPADD,J.ENTRY_DT ENTRY_DT1,P.AMOUNT AS AMOUNT,P.ENTRY_DT AS ENTRY_DT2 FROM  newjs.JPROFILE AS J,billing.PAYMENT_DETAIL AS P,billing.PURCHASES AS PR   WHERE  P.PROFILEID=J.PROFILEID AND PR.BILLID=P.BILLID AND J.SOURCE='dgm_07' AND J.ACTIVATED='Y'  AND J.SUBSCRIPTION!='' AND P.ENTRY_DT BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' ";
		
		$resultnew=mysql_query_decide($sqlnew,$db) or die(mysql_error_js());
		$i=0;
		while($myrownew=mysql_fetch_assoc($resultnew))
		{
			$dataarr[$i]["username"]=$myrownew["USERNAME"];
                        $dataarr[$i]["password"]=$myrownew["PASSWORD"];
			$dataarr[$i]["amount"]=$myrownew["AMOUNT"];
                        $dataarr[$i]["ipadd"]=$myrownew["IPADD"];
			$dataarr[$i]["entry_dt1"]=$myrownew["ENTRY_DT1"];
			$dataarr[$i]["entry_dt2"]=$myrownew["ENTRY_DT2"];
			if($myrownew["ADDON_SERVICEID"])
				$sid=$myrownew["SERVICEID"].",".$myrownew["ADDON_SERVICEID"];
			else
				$sid=$myrownew["SERVICEID"];
			$dataarr[$i]["service"]=get_servicename($sid);
		//	$dataarr[$i]["service"]=$myrownew["SERVICE"];
			$dataarr[$i]["profilechecksum"]=$myrownew["PROFILEID"];
			$billidarr[$i]=$myrownew["BILLID"];
			$i++;
				
		}
		for($j=0;$j<count($billidarr);$j++)
		{
			$bill=$billidarr[$j];
			$sql1="SELECT S.NAME AS SERVICE  FROM billing.PURCHASES AS P,billing.SERVICES AS S WHERE P.ADDON_SERVICEID=S.SERVICEID AND P.BILLID=$bill";
			$result1=mysql_query_decide($sql1) or die("$sql1".mysql_error_js());
			$myrow1=mysql_fetch_array($result1);
			$dataarr[$j]["addon_service"]=$myrow1["SERVICE"];
			unset($bill);
		}
		$smarty->assign('dataarr',$dataarr);
		$smarty->assign('complete_profiles',$i);
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
		$smarty->display("dgm_affiliate_paid.htm");
}
else
{
	$smarty->assign('$user',$username);
	$smarty->display("jsconnectError.tpl");
}


?>
