<?php
	
	include("connect.inc");
	if(isset($submit_show_IM))
	{
		
		if($submit_show_IM=="Show")
		{	
			$sql="update incentive.show_IM set Display='Y'";
			mysql_query_decide($sql);
			$smarty->assign("msg","Incentive multiplier now visible");
			$smarty->assign("msg2","Hide");
	
			$smarty->display("show_IM.htm");
		}
		if($submit_show_IM=="Hide")
		{
			$sql="update incentive.show_IM set Display='N'";
			mysql_query_decide($sql);
			$smarty->assign("msg","Incentive Multiplier now hidden");
			$smarty->assign("msg2","Show");
         
			$smarty->display("show_IM.htm");
		}
		
	}
	else
	{	
		$sql="select Display from incentive.show_IM";
		$res=mysql_query_decide($sql);
		$row=mysql_fetch_assoc($res);
	
		if($row['Display']=='Y')
		{
			$smarty->assign("msg2","Hide");
		}
		else
		{
			$smarty->assign("msg2","Show");
		}
		
		$smarty->display("show_IM.htm");
	}
	
?>
