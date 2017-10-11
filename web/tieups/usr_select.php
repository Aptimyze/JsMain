<?php
/****
	File 	      : usr_select.php
	Description   : This file handles the group aaloted to a particular user
	Modification  : Authentication on master , mis queries on slave
	Date	      : 2013-09-17 by Nitesh  
****/
include("connect.inc");
$db2=connect_db();
if(authenticated($cid))
{
	$group = get_group($cid);
	$gp=explode(",",$group);
	if($gp)
	{
		if(count($gp)==1)
		{
			$smarty->assign("MULTIPLE","N");
			$smarty->assign("SOURCEGP",$gp[0]);
		}
		elseif(count($gp)>1)
		{
			$smarty->assign("MULTIPLE","Y");
			$smarty->assign("gparr",$gp);
		}
	}
	else
	{
		$smarty->assign("NO_SOURCEGP","Y");
	}
	//Date Dropdown
	for($i=0;$i<31;$i++)
	{
		$rangeDate[$i]=$i+1;
	}
	$smarty->assign("rangeDate",$rangeDate);
	
	//month dropdown
	$monArray=array("0"=>"Jan","1"=>"Feb","2"=>"Mar","3"=>"Apr","4"=>"May","5"=>"Jun","6"=>"Jul","7"=>"Aug","8"=>"Sep","9"=>"Oct","10"=>"Nov","11"=>"Dec");
	for($i=0;$i<12;$i++)
	{
		$rangeMon[$i]=$monArray[$i];
	}
	$smarty->assign("rangeMon",$rangeMon);
	
	//year dropdown
	for($i=2004,$j=0;$j<20;$j++)
	{
		$rangeYear[$j]=$i;
		$i++;
	}
	$smarty->assign("rangeYear",$rangeYear);
	//Current year 
	$cYear=date('Y');
	//current date
	$cDate=date('j');
	//current month
	$cMon=date('M');
	
	//30 day before date values
	 $cYear30= date("Y", strtotime("-30 day"));
	 $cDate30= date("j", strtotime("-30 day"));
	 $cMon30= date("M", strtotime("-30 day"));
	
	
	//current Default values
	$smarty->assign("cYear",$cYear);
	$smarty->assign("cDate",$cDate);
	$smarty->assign("cMon",$cMon);
	$smarty->assign("cYear30",$cYear30);
	$smarty->assign("cDate30",$cDate30);
	$smarty->assign("cMon30",$cMon30);
	
	$mtongueArr=FieldMap::getFieldLabel('community',"",1);
	$i=0;
	foreach($mtongueArr as $K=>$V)
	{
		$displayMtongueArr[$i]["value"]=$K;
		$displayMtongueArr[$i]["name"]=$V;
		$i++;		
	}
	$smarty->assign("mtongueArr",$displayMtongueArr);
	
	$cityStateArr=FieldMap::getFieldLabel('city_india',"",1);
	$i=0;
	foreach($cityStateArr as $K=>$V)
	{
		if(strlen($K)==2 && !is_int($K))
		{
			$stateArr[$i]["value"]=$K;
			$stateArr[$i]["name"]=$V;
			$i++;
		}
	}
	//print_r($stateArr);die;
	$smarty->assign("stateArr",$stateArr);
	
//	$smarty->assign("SOURCEGP",$group);
	$smarty->assign("CID",$cid);
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->display("usr_select.htm");
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
