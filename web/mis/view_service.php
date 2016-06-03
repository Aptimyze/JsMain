<?php
/*      Filename        :	view_service.php MIS.
*       Description     :  	file is used for showing various users sorted by year,month having services D or H or K.
				only those records are shown to which mail has been sent for user to finally verify their
				service.
				MIS user is given the option to select user by branch.
*       Created by      :       Puneet
*       Changed by      :
*       Changed on      :       22-8-2005
*       Changes         :
**/

include("connect.inc");
$db=connect_misdb();
$db2=connect_master();
                                                                                                                        
if(authenticated($cid))
{
	$mmarr=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
	for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}

	$smarty->assign("yyarr",$yyarr);
	$smarty->assign("mmarr",$mmarr);

	if($CMDGo)
	{
		$city_str='';	
		if($branch)
		{
			$sql="SELECT VALUE FROM incentive.BRANCH_CITY WHERE NEAR_BRANCH='$branch'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			while($row=mysql_fetch_array($res))
			{
				$cityarr[]=$row['VALUE'];
			}
		}

		if($cityarr)
		{
			$city_str=implode("','",$cityarr);
			$sql="SELECT DISTINCT p.PROFILEID,p.ENTRY_DT FROM billing.PURCHASES AS p left join newjs.JPROFILE AS j ON j.PROFILEID=p.PROFILEID WHERE p.ENTRY_DT between '".$yy."-01-01 00:00:00' AND '".$yy."-12-31 23:59:59' AND p.STATUS='DONE' AND p.VERIFY_SERVICE='A'  AND j.CITY_RES in ('$city_str')";
		}
		else
		{
			$sql="SELECT DISTINCT PROFILEID,ENTRY_DT FROM billing.PURCHASES WHERE ENTRY_DT between '".$yy."-01-01 00:00:00' AND '".$yy."-12-31 23:59:59' AND STATUS='DONE' AND VERIFY_SERVICE='A'";
		}
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		
		for($i=0;$i<12;$i++)
			$mtotal[$i]=0;
		while($row=mysql_fetch_array($res))
		{	$i=substr($row['ENTRY_DT'],5,2);
			$mtotal[$i-1]=$mtotal[$i-1]+1;
		}
		
		for($i=0;$i<12;$i++)
			$total+=$mtotal[$i];
		
		$yyp1=$yy+1;
		$smarty->assign("flag",'1');
		$smarty->assign("yy",$yy);
		$smarty->assign("yyp1",$yyp1);
		$smarty->assign("mtotal",$mtotal);
		$smarty->assign("total",$total);
		$smarty->assign("branch",$branch);
	}

	else
	{
		$sql="SELECT NEAR_BRANCH,LABEL FROM incentive.BRANCH_CITY WHERE NEAR_BRANCH<>'' AND NEAR_BRANCH=VALUE";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		if($row=mysql_fetch_array($res))
		{
			$i=0;
			do
			{
				$branch[$i]["VALUE"]=$row['NEAR_BRANCH'];
				$branch[$i]["LABEL"]=$row['LABEL'];
				$i++;
			}while($row=mysql_fetch_array($res));
		}
		$smarty->assign("branch",$branch);
	}
		
	$smarty->assign("cid",$cid);
	$smarty->display("view_service.htm");
}
else
{
	$msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsconnectError.tpl");

}
?>
