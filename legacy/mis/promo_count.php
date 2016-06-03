<?php
include_once("connect.inc");
include_once("../profile/pg/functions.php");

$db=connect_misdb();
$db2=connect_master();

$data=authenticated($checksum);

if(isset($data)|| $JSIndicator)
{
	$searchType='';
	$searchFlag=0;
	$searchMonth='';
	$searchYear='';
	$monthDays=0;
	$searchKeyArray=array('B'=>'Banner in Contact alert','D'=>'Banner in Match Alert','E'=>'Banners post login','C'=>'Confirmation Page gif','G'=>'Gif on the Search pages pre login','H'=>'Gif on the Search pages post login','O'=>'Opted-in mailer','P'=>'Post Login Banne','T'=>'Text link in contact alert','U'=>'Text Link in match alert');
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
		$monthDays=$todDay-1;
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
		if($searchType!="ALL")
		{
			 $sqlnew="SELECT COUNT(*) AS CNT ,DAY(P.RESPONSE_TIME) AS DAYNO  FROM  newjs.PROMOTIONAL_MAIL AS P WHERE  P.SOURCE='$searchType' AND DATE(P.RESPONSE_TIME) BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' GROUP BY DATE(P.RESPONSE_TIME)";
			
			
			//$sql="SELECT TOTAL,DAY(DATE) AS DAYNO FROM MIS.DAILY_CONTACTSEARCH_TOTAL WHERE SEARCH_TYPE='$searchType' AND DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays'";
			$resultnew=mysql_query_decide($sqlnew,$db) or die(mysql_error_js());
			//$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
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
				$totDatanew+=$myrownew["CNT"];
			}
			/*while($myrow=mysql_fetch_assoc($result))
			{
				$s=$myrow["DAYNO"];
				$dataArray[$s]=$myrow["TOTAL"];
				$totData+=$myrow["TOTAL"];
			}*/
			$smarty->assign('dataArraynew',$dataArraynew);
			$smarty->assign('totDatanew',$totDatanew);
			//$smarty->assign('dataArray',$dataArray);
			//$smarty->assign('totData',$totData);
		}
		else
		{
			 $sqlnew2="SELECT COUNT(*) AS CNT ,DAY(P.RESPONSE_TIME) AS DAYNO,P.SOURCE AS SOURCE  FROM  newjs.PROMOTIONAL_MAIL AS P WHERE  DATE(P.RESPONSE_TIME) BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays' GROUP BY P.SOURCE,DATE(P.RESPONSE_TIME)";
			
			
			//$sql2="SELECT TOTAL,SEARCH_TYPE,DAY(DATE) AS DAYNO FROM MIS.DAILY_CONTACTSEARCH_TOTAL WHERE DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays'";
			$resultnew2=mysql_query_decide($sqlnew2,$db) or die(mysql_error_js());
			//$result2=mysql_query_decide($sql2,$db) or die(mysql_error_js());
			while($myrownew2=mysql_fetch_assoc($resultnew2))
                        {
                                        $s=$myrownew2["SOURCE"];
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
					$dataArraynew[$s][$d]=$myrownew2["CNT"];
                                        $totSearchMonthnew[$myrownew2["SOURCE"]]+=$myrownew2["CNT"];
                                        $totSearchDaynew[$myrownew2["DAYNO"]]+=$myrownew2["CNT"];
                                        $grandTotalnew+=$myrownew2["CNT"];

                        }
			
			/*while($myrow=mysql_fetch_assoc($result2))
			{		
					$s=$myrow["SEARCH_TYPE"];
					$d=$myrow["DAYNO"];
					$dataArray[$s][$d]=$myrow["TOTAL"];
					$totSearchMonth[$myrow["SEARCH_TYPE"]]+=$myrow["TOTAL"];
                                        $totSearchDay[$myrow["DAYNO"]]+=$myrow["TOTAL"];
					$grandTotal+=$myrow["TOTAL"];
					
			}*/
			$smarty->assign('grandTotalnew',$grandTotalnew);
                        $smarty->assign('totSearchDaynew',$totSearchDaynew);
                        $smarty->assign('dataArraynew',$dataArraynew);
                        $smarty->assign('totSearchMonthnew',$totSearchMonthnew);
			//$smarty->assign('grandTotal',$grandTotal);
			//$smarty->assign('totSearchDay',$totSearchDay);
			//$smarty->assign('dataArray',$dataArray);
			//$smarty->assign('totSearchMonth',$totSearchMonth);
		}
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
		$smarty->display("promo_count.htm");
	}
	else
	{
		$k=0;
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
		$smarty->display("promo_count.htm");
	}
}
else
{
	$smarty->assign('$user',$username);
	$smarty->display("jsconnectError.tpl");
}
?>
