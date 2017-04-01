<?php
//include("connect.inc");	//	commented by shakti
include_once("connect.inc");
include("../profile/arrays.php");
/*
created by Puneet Makkar on 26 Dec MIS TO track the views breakin up on 
Male Female Paid Not paid With Photo Without Photo Communities Caste
view_for_mis_main.php
*/
$db=connect_misdb();

if(authenticated($cid))
{
	if($CMDGo)
	{	
		for($i=0;$i<31;$i++)
                {
                        $ddarr[$i]=$i+1;
                }
		if($morc=='mtongue')
		{	
			$smarty->assign("morc",'mtongue');
			$x="MTONGUE";
			$z="CASTE";
		}
		else
		{	
			$smarty->assign("morc",'caste');
			$x="CASTE";
			$z="MTONGUE";
		}		
		$smarty->assign("flag",1);
		$st_date=$year."-".$month."-01";
		$end_date=$year."-".$month."-31";
		
		$sql="SELECT SUM(COUNT) as cnt,$x, DAYOFMONTH(DATE) as dd FROM MIS.VIEW_FOR_MIS_MAIN WHERE DATE BETWEEN '$st_date' AND '$end_date' AND $z=0 ";
		if($gender)
			$sql.=" AND GENDER='$gender' ";
 		if($paid)
			$sql.=" AND PAID='$paid' ";
		if($photo)
			$sql.=" AND PHOTO='$photo' ";
		if($matchalert)
			$sql.=" AND MATCHALERT='$matchalert' ";
		if($visitoralert)
                        $sql.=" AND VISITORALERT='$visitoralert' ";
		$sql.=" group by $x,dd";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		if($row=mysql_fetch_array($res))
                {
                        do
                        {
				$dd=$row['dd'];
                                $total[$row[$x]][$dd]=$row['cnt'];
				$total_day[$dd]+=$row['cnt'];
				$total_caste[$row[$x]]+=$row['cnt'];
				$finaltotal+=$row['cnt'];
			}while($row=mysql_fetch_array($res));
                }
		//print_r($total);
		//print_r($total_caste);
		$smarty->assign("total",$total);
		$smarty->assign("finaltotal",$finaltotal);
		$smarty->assign("total_day",$total_day);
		$smarty->assign("total_caste",$total_caste);
		$key=0;
		if($morc=='mtongue')
		{	
			$sql="SELECT DISTINCT VALUE,LABEL FROM newjs.MTONGUE order by VALUE asc";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			while($row=mysql_fetch_array($res))
			{	 
				$mtongue[$row['VALUE']]=$row['LABEL'];
				if($row['VALUE']>$key)
					$key=$row['VALUE'];	
			}
		}
		if($morc=='caste')
		{	
			$sql="SELECT LABEL,VALUE FROM newjs.CASTE order by VALUE asc";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			while($row=mysql_fetch_array($res))
			{	 
				$caste[$row['VALUE']]=$row['LABEL'];
				if($row['VALUE']>$key)
					$key=$row['VALUE'];	
			}
		}
		$smarty->assign("cnt",$key);
		$smarty->assign("caste",$caste);
		$smarty->assign("mtongue",$mtongue);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("year",$year);
		$smarty->assign("month",$month);
		$smarty->assign("cid",$cid);
		$smarty->display("view_for_mis_main.htm");
	}
	else
	{
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}
		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }
                for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->assign("cid",$cid);
		$smarty->display("view_for_mis_main.htm");
	}
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
