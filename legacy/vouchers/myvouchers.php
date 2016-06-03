<?php
/******************************************************************************************************************
Filename    : myvouchers.php
Created By  : Sadaf Alam 
Created On  : 08 August 2007
Description : To display the e vouchers assigned to the user with options for download/print/email for voucher revamp
*********************************************************************************************************************/
header("Cache-Control: public");
include("connect.inc");
include("../crm/func_sky.php");
$db=connect_db();
$data=authenticated($checksum);

if($data)
{
	/*****************Portion of Code added for display of Banners*******************************/
        //$data=authenticated($checksum);
        //if($data)
        login_relogin_auth($data);
        $smarty->assign("data",$data["PROFILEID"]);
        $smarty->assign("bms_topright",18);
        $smarty->assign("bms_bottom",19);
        $smarty->assign("bms_left",24);
        $smarty->assign("bms_right",28);
        $smarty->assign("bms_new_win",32);
	/***********************End of Portion of Code*****************************************/
        //login_relogin_auth($data);//For contact detail on left panel.
        $smarty->assign("CHECKSUM",$checksum);
        $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
        $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
        $smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
	$profileid=$data["PROFILEID"];

	if($Email)
	{
		if($client)
		{
			$sql="SELECT NAME FROM billing.VOUCHER_OPTIN WHERE PROFILEID='$profileid' ORDER BY ID DESC";
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes",$sql,"ShowErrTemplate");
			$row=mysql_fetch_assoc($result);
			$name=$row["NAME"];
			$sql="SELECT EMAIL FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$row=mysql_fetch_assoc($result);
			$email=$row["EMAIL"];
			$clients=implode("','",$client);
			$num=count($client);
			$sql="UPDATE MIS.VOUCHER_DOWNLOAD SET EMAIL=EMAIL+1 WHERE ENTRY_DATE=CURDATE() AND NUM='$num'";
			mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			if(mysql_affected_rows_js()==0)
			{
				$sql="INSERT INTO MIS.VOUCHER_DOWNLOAD(ENTRY_DATE,NUM,EMAIL) VALUES(CURDATE(),'$num','1')";
				mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			}
			$sql="SELECT TEMPLATE,CLIENTID,CLIENT_NAME,HYPERLINK FROM billing.VOUCHER_CLIENTS WHERE CLIENTID IN ('$clients')";
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			while($row=mysql_fetch_assoc($result))
			{
				$sqlno="SELECT VOUCHER_NO FROM billing.VOUCHER_NUMBER WHERE CLIENTID='$row[CLIENTID]' AND PROFILEID='$profileid' AND ISSUED='Y' AND SOURCE!='SUCCESS' ORDER BY ID DESC";
				$resultno=mysql_query_decide($sqlno) or logError("Due to a temporary error, your request could not be processed. Please try again after a couple of minutes",$sqlno,"ShowErrTemplate");
				$rowno=mysql_fetch_assoc($resultno);
				$smarty->assign("Name",$name);
				$smarty->assign("voucher_no",$rowno["VOUCHER_NO"]);
				if($row["TEMPLATE"])
				$msg=$smarty->fetch("$row[TEMPLATE]");
				else
				{
					$smarty->assign("client_name",$row["CLIENT_NAME"]);
					$smarty->assign("clientid",$row["CLIENTID"]);
					if($row["HYPERLINK"])
					$smarty->assign("hyperlink",$row["HYPERLINK"]);
					else
					$smarty->assign("hyperlink","");
					$msg=$smarty->fetch("evoucher.htm");
				}
				send_mail($email,"","",$msg,"Gift Vouchers","promotions@jeevansathi.com");
				$sqlmis="UPDATE MIS.VOUCHER_CLIENT_NO SET NUM_MAIL=NUM_MAIL+1 WHERE CLIENTID='$row[CLIENTID]' AND ENTRY_DATE=CURDATE()";
				mysql_query_decide($sqlmis) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlmis,"ShowErrTemplate");
				if(mysql_affected_rows_js()==0)
				{
					$sqlmis="INSERT INTO MIS.VOUCHER_CLIENT_NO(ENTRY_DATE,CLIENTID,NUM_MAIL) VALUES(CURDATE(),'$row[CLIENTID]','1')";
					mysql_query_decide($sqlmis) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlmis,"ShowErrTemplate");
				}
				
			}
			$sql="SELECT ID,CLAIM FROM billing.VOUCHER_VIEWED WHERE PROFILEID='$profileid' ORDER BY ID DESC";
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$row=mysql_fetch_assoc($result);
			if($row["CLAIM"]=='')
			{
				$sqlmis="UPDATE billing.VOUCHER_VIEWED SET CLAIM='M' WHERE ID='$row[ID]'";
				mysql_query_decide($sqlmis) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlmis,"ShowErrTemplate");
			}
			$smarty->assign("MSG","E-Vouchers selected have been successfully mailed to you.");
		}
		else
		$smarty->assign("MSG","Please select at least one voucher");
	}
	$sql="SELECT OPTIONS_AVAILABLE FROM billing.VOUCHER_OPTIN WHERE PROFILEID='$profileid' ORDER BY ID DESC";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row=mysql_fetch_assoc($result);
	$options=explode(',',$row["OPTIONS_AVAILABLE"]);
	$optionsexp=implode("','",$options);
	$sqlexp="SELECT DISTINCT(CLIENTID),TYPE FROM billing.VOUCHER_NUMBER  WHERE (EXPIRY_DATE = '0000-00-00' OR EXPIRY_DATE > CURDATE( )) AND CLIENTID IN ('$optionsexp') AND PROFILEID='$profileid' AND SOURCE!='SUCCESS'";
	$resultexp=mysql_query_decide($sqlexp) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlexp,"ShowErrTemplate");
	if(mysql_num_rows($resultexp))
	{
		while($rowexp=mysql_fetch_assoc($resultexp))
		{
			if($rowexp["TYPE"]=="E")
			$eclient[]=$rowexp["CLIENTID"];
			else
			$pclient[]=$rowexp["CLIENTID"];
		}
		if($eclient)
		{
			$eclients=implode("','",$eclient);
			$sqlclient="SELECT CLIENTID FROM billing.VOUCHER_CLIENTS WHERE CLIENTID IN ('$eclients')";
			$resultclient=mysql_query_decide($sqlclient) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlclient,"ShowErrTemplate");
			
			
			$i=mysql_num_rows($resultclient);
                        $j=$i;
                        if($i%3!=0)
                        $j=$j-($i%3)+3;
                        while($j>0)
                        {
	                        $rowclient=mysql_fetch_assoc($resultclient);
                                $client1=$rowclient["CLIENTID"];
				$rowclient=mysql_fetch_assoc($resultclient);
				$client2=$rowclient["CLIENTID"];
				$rowclient=mysql_fetch_assoc($resultclient);
				$client3=$rowclient["CLIENTID"];
				$availoptions[]=array($client1,$client2,$client3);
				$j-=3;
			}
			$smarty->assign("availoptions",$availoptions);
			$smarty->display("myvouchers.htm");
		}
		elseif($pclient)
		{
			$smarty->assign("MSG","Sorry but you have no more active e-vouchers to redeem.  Printed Vouchers that you are eligible for have been dispatched to your contact address");
			$smarty->assign("NODISP","1");
			$smarty->display("myvouchers.htm");
		}
	}
	else
	{
		$smarty->assign("NODISP","1");
		$smarty->assign("MSG","Sorry, but you have no more active vouchers to redeem.");
		$smarty->display("myvouchers.htm");
	}
}
else
{
	TimedOut();
}
?>
