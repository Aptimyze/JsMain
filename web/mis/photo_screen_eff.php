<?php
/***********************************************************************************************************************
* FILE NAME     : photo_screen_eff.php 
* DESCRIPTION   : Create MIS forDisplays No.of user submitted photos on a particular day and when did they get live.
* INCLUDES      : connect.inc
* CREATION DATE : 13 June 2007
* CREATED BY    : Sadaf Alam
************************************************************************************************************************/
include_once("connect.inc");
$db=connect_rep();

if(authenticated($cid) || $JSIndicator==1)
{
	if(!$month&&!$year)
	{
		if(!$today)
		$today=date("Y-m-d");
	        list($year,$month,$d)=explode("-",$today);
	}
	if($outside)
        {
                $CMDGo='Y';
		$type="new";
        }

	if($CMDGo)
	{
		$smarty->assign("flag",1);
		if(!$type)
		$type="new";
		$mmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
			$ddarr1[$i]=$i+1;
			$total[$i]=0;
			$done[$i]=0;
			$allcnt[$i]=0;
		}
		$j=1;
		// 5 extra days are provided for taking care of end-month days.
		for($i=31;$i<36;$i++)
		{
			$ddarr1[$i]=$j;	
			$j++;
			$total[$i]=0;
		}
		$st_date=$year."-".$month."-01 00:00:00";
		$end_date=$year."-".$month."-31 23:59:59";
		//$st_date='2004-11-01';
		//$end_date='2004-11-31';
			if($type=="new")
			{
 			$sql="SELECT NEW,DAYOFMONTH(RECEIVE_DATE) as dd2,MONTH(RECEIVE_DATE) as mm2,DAYOFMONTH(SUBMITED_DATE) as dd3,MONTH(SUBMITED_DATE) as mm3 from MIS.SCREEN_EFFICIENCY  WHERE RECEIVE_DATE BETWEEN '$st_date' AND '$end_date' ORDER BY dd2";
			$sqldate="SELECT DAYOFMONTH(PHOTODATE) as dd2,MONTH(PHOTODATE) as mm2 FROM newjs.JPROFILE WHERE PHOTODATE BETWEEN '$st_date' AND '$end_date' AND HAVEPHOTO='U' ORDER BY dd2";
			}
		elseif($type=="edit")
			{
			 	 $sql="SELECT EDIT,DAYOFMONTH(RECEIVE_DATE) as dd2,MONTH(RECEIVE_DATE) as mm2,DAYOFMONTH(SUBMITED_DATE) as dd3,MONTH(SUBMITED_DATE) as mm3 from MIS.SCREEN_EFFICIENCY  WHERE RECEIVE_DATE BETWEEN '$st_date' AND '$end_date' ORDER BY dd2";
                        $sqldate="SELECT DAYOFMONTH(PHOTODATE) as dd2,MONTH(PHOTODATE) as mm2 FROM newjs.JPROFILE WHERE PHOTODATE BETWEEN '$st_date' AND '$end_date' AND HAVEPHOTO='Y' AND PHOTOSCREEN=0 ORDER BY dd2";
			}
		elseif($type=="appPic")
		{
			 	 $sql="SELECT APP_PIC,DAYOFMONTH(RECEIVE_DATE) as dd2,MONTH(RECEIVE_DATE) as mm2,DAYOFMONTH(SUBMITED_DATE) as dd3,MONTH(SUBMITED_DATE) as mm3 from MIS.SCREEN_EFFICIENCY  WHERE RECEIVE_DATE BETWEEN '$st_date' AND '$end_date' ORDER BY dd2";
                        $sqldate="SELECT DAYOFMONTH(SUBMIT_DATE) as dd2,MONTH(SUBMIT_DATE) as mm2 FROM jsadmin.SCREEN_PHOTOS_FOR_APP WHERE SUBMIT_DATE BETWEEN '$st_date' AND '$end_date' ORDER BY dd2";
		}
		else
			{
				 $sql="SELECT MAIL,DAYOFMONTH(RECEIVE_DATE) as dd2,MONTH(RECEIVE_DATE) as mm2,DAYOFMONTH(SUBMITED_DATE) as dd3,MONTH(SUBMITED_DATE) as mm3 from MIS.SCREEN_EFFICIENCY WHERE RECEIVE_DATE BETWEEN '$st_date' AND '$end_date' ORDER BY dd2";
				 $sqldate="SELECT DAYOFMONTH(DATE) as dd2,MONTH(DATE) as mm2 FROM jsadmin.PHOTOS_FROM_MAIL LEFT JOIN jsadmin.SCREEN_PHOTOS_FROM_MAIL ON PHOTOS_FROM_MAIL.ID=SCREEN_PHOTOS_FROM_MAIL.MAILID  WHERE (SCREEN_PHOTOS_FROM_MAIL.STATUS IS NULL OR SKIP='Y') AND  DATE BETWEEN '$st_date' AND '$end_date' AND ATTACHMENT='Y' ORDER BY dd2 ";
				$sqlall="SELECT DAYOFMONTH(DATE) as dd2,MONTH(DATE) as mm2 FROM jsadmin.PHOTOS_FROM_MAIL WHERE DATE BETWEEN '$st_date' AND '$end_date' ORDER BY dd2";
				$resultall=mysql_query_decide($sqlall,$db) or die("$sqlall".mysql_error_js($db));
				while($rowall=mysql_fetch_assoc($resultall))
				{
					$dd2=$rowall["dd2"]-1;
					$allcnt[$dd2]++;
					$allgrand++;
				}
			}
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		if($type=="new")
		$flag="NEW";
		elseif($type=="edit")
		$flag="EDIT";
		elseif($type=="appPic")
		$flag = "APP_PIC";
		else
		$flag="MAIL";
		while($row=mysql_fetch_array($res))
		{
			$dd2=$row['dd2']-1;		
//			$cnt1[$dd2]++;
			$dd3=$row['dd3']-1;
			//$extra[$dd2]+=$row["$flag"];				
			if($row['mm2']==$row['mm3'])
			{
				if($row["$flag"]!=0)
				{
					$cnt2[$dd2][$dd3]=$row["$flag"];
					$total[$dd3]+=$row["$flag"];
					$done[$dd2]+=$row["$flag"];
					$donetotal+=$row["$flag"];
				}
				else
				{
					$cnt2[$dd2][$dd3]="";
				}
			}
			else
			{
				if($row["$flag"]!=0)
				{	
					$cnt2[$dd2][$dd3+31]=$row["$flag"];//need to select values(1-5) from the end of the month
					$total[$dd3+31]+=$row["$flag"];
					$done[$dd2]+=$row["$flag"];
					$donetotal+=$row["$flag"];
				}
				else
				{
				 	$cnt2[$dd2][$dd3]="";
				}
			}
		}
		$resdate=mysql_query_decide($sqldate,$db) or die("$sqldate".mysql_error_js($db));
		while($rowdate=mysql_fetch_array($resdate))
		{
			$dd2=$rowdate['dd2']-1;
			$cnt1[$dd2]++;
		}
		
		for($i=0;$i<31;$i++)
		{	
			$cnt1[$i]+=$done[$i];
			$grandtotal+=$cnt1[$i];
		}
		for($i=0;$i<31;$i++)
		{
			if($cnt1[$i]!='0')
			$cntpercent[$i]=round(($done[$i]/$cnt1[$i]*100),0);
			else
			$cntpercent[$i]=0;
			$cntpercent[$i].="%";
		}
			
                if($JSIndicator==1)
                {
                        return;
                }
		$smarty->assign("cntpercent",$cntpercent);
		$smarty->assign("donetotal",$donetotal);
		$smarty->assign("done",$done);
		$smarty->assign("allgrand",$allgrand);
		$smarty->assign("allcnt",$allcnt);
		$smarty->assign("cnt1",$cnt1);
		$smarty->assign("cnt2",$cnt2);
                $smarty->assign("tot1",$tot1);	
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("ddarr1",$ddarr1);
		$smarty->assign("year",$year);
		$smarty->assign("month",$month);
		$smarty->assign("cid",$cid);
		$smarty->assign("type",$type);	
		$smarty->assign("total",$total);
		$smarty->assign("grandtotal",$grandtotal);
                $smarty->display("photo_screen_eff.htm");
	}
	else
	{
		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }

                for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}

		$smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->assign("cid",$cid);
		$smarty->display("photo_screen_eff.htm");
	}
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
