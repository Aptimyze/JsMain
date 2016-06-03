<?php

$_SERVER['DOCUMENT_ROOT']=substr(dirname(__FILE__),0,strpos(dirname(__FILE__),"/mailers/visitoralert"));
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");

include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

function mails_visitor()
{
	$mysqlObj=new Mysql;
	$db=connect_737();
	$myDb1=$mysqlObj->connect("11Master");
	mysql_query("set session wait_timeout = 1000",$db);
	mysql_query("set session wait_timeout = 1000",$myDb1);
	
	mysql_ping($myDb1);	
	$sql="SELECT * FROM visitoralert.MAILER_VISITORS where SENT<>'Y' and VISITOR1<>0 AND VISITOR1 IS NOT NULL";
	$result=mysql_query($sql,$myDb1);		

	while($myrow=mysql_fetch_array($result))
	{
		$receiver=$myrow[PROFILEID];
		$email_sender=new EmailSender(15,1764);
		$variableDiscountObj = new VariableDiscount;
		$variableDiscount = $variableDiscountObj->getDiscDetails($receiver);
		
		$tpl = $email_sender->setProfileId($receiver);
		
		$profileObj=$tpl->getSenderProfile();
		$profileState=JsCommon::getProfileState($profileObj);
				
		$p_list = new PartialList;
		$p_list->addPartial('jeevansathi_contact_address','jeevansathi_contact_address');
		$p_list->addPartial('va_match_1','dpp_matches',$myrow['VISITOR1']);
		
		unset($visitor_arr);
		
		for($visitor_count=2;$visitor_count<=20;$visitor_count++)
		{
			if($myrow["VISITOR$visitor_count"]!="" && $myrow["VISITOR$visitor_count"]!="0")
				$visitor_arr[]=$myrow["VISITOR$visitor_count"];
		}
		if(is_array($visitor_arr))
			$p_list->addPartial('va_matches_2to20','dpp_matches',$visitor_arr);
		
		$tpl->setPartials($p_list);
		$smartyObj = $tpl->getSmarty();
		$smartyObj->assign("profileid1",$myrow['VISITOR1']);
		$smartyObj->assign("profileState",$profileState);
		if(!empty($variableDiscount))
		{
			$smartyObj->assign("variableDiscount",$variableDiscount["DISCOUNT"]);
			$smartyObj->assign("VD_END_MONTH",date("M",JSstrToTime($variableDiscount["EDATE"])));
			$smartyObj->assign("VD_END_YEAR",date("Y",JSstrToTime($variableDiscount["EDATE"])));
			$smartyObj->assign("VD_END_DAY",date("d",JSstrToTime($variableDiscount["EDATE"])));
			$smartyObj->assign("VD_END_DAY_SUFFIX",date("S",JSstrToTime($variableDiscount["EDATE"])));
			$tpl->getSmarty()->assign("topSource","VDVA1".$variableDiscount["DISCOUNT"]);
			$tpl->getSmarty()->assign("BottomSource","VDVA2".$variableDiscount["DISCOUNT"]);
		}
		else
		{
			$tpl->getSmarty()->assign("BottomSource","VA2");
		}
		
		$email_sender->send();
		
		@mysql_ping($myDb1);
		$sql="UPDATE visitoralert.MAILER_VISITORS SET SENT='Y' WHERE PROFILEID='$receiver'";
		mysql_query($sql,$myDb1);
	}
	
}

?>
