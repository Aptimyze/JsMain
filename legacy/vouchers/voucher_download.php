<?php
header("Cache-Control: public");
include("connect.inc");
$db=connect_db();

$data=authenticated($checksum);

if($data)
{
	if($client)
	{
		$client=substr($client,1,strlen($client));
		$clients=explode("^",$client);
		$list=implode("','",$clients);
		$sql="SELECT NAME FROM billing.VOUCHER_OPTIN WHERE PROFILEID='$data[PROFILEID]' ORDER BY ID DESC";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row=mysql_fetch_assoc($result);
		$Name=$row["NAME"];

		//V_NAME is the name that come from the voucher mail
		if($v_name!="")
			$Name=$v_name;

		$sql="SELECT CLIENTID,TEMPLATE,CLIENT_NAME,HYPERLINK FROM billing.VOUCHER_CLIENTS WHERE CLIENTID IN ('$list') AND TYPE='E'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$num=0;
		while($row=mysql_fetch_assoc($result))
		{
			if($v_name)
				$sqlnumber="SELECT VOUCHER_NO FROM billing.VOUCHER_NUMBER WHERE CLIENTID='$row[CLIENTID]' AND SOURCE='SUCCESS' and STORYID='$storyid' ORDER BY ID DESC";
			else
				$sqlnumber="SELECT VOUCHER_NO FROM billing.VOUCHER_NUMBER WHERE PROFILEID='$data[PROFILEID]' AND CLIENTID='$row[CLIENTID]' AND SOURCE!='SUCCESS' ORDER BY ID DESC";

			$resultnumber=mysql_query_decide($sqlnumber) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlnumber,"ShowErrTemplate");

			$rownumber=mysql_fetch_assoc($resultnumber);
			$smarty->assign("voucher_no",$rownumber["VOUCHER_NO"]);
			$smarty->assign("today",date("Y-m-d"));
			$smarty->assign("Name",$Name);
			if($row["TEMPLATE"])
				$msg.=$smarty->fetch("$row[TEMPLATE]");
			else
			{
				$smarty->assign("client_name",$row["CLIENT_NAME"]);
				$smarty->assign("clientid",$row["CLIENTID"]);
				if($row["HYPERLINK"])
                                $smarty->assign("hyperlink",$row["HYPERLINK"]);
                                else
                                $smarty->assign("hyperlink","");
				$msg.=$smarty->fetch("evoucher.htm");
			}
			$msg.="<br /><br /><hr /><br /><br />";
			$num++;
			$sqlmis="UPDATE MIS.VOUCHER_CLIENT_NO SET NUM_DOWN=NUM_DOWN+1 WHERE CLIENTID='$row[CLIENTID]' AND ENTRY_DATE=CURDATE()";
			mysql_query_decide($sqlmis) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlmis,"ShowErrTemplate");
			if(mysql_affected_rows_js()==0)
			{
				$sqlmis="INSERT INTO MIS.VOUCHER_CLIENT_NO(ENTRY_DATE,CLIENTID,NUM_DOWN) VALUES(CURDATE(),'$row[CLIENTID]','1')";
				mysql_query_decide($sqlmis) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlmis,"ShowErrTemplate");
			}
		}
		$sql="UPDATE MIS.VOUCHER_DOWNLOAD SET DOWNLOAD=DOWNLOAD+1 WHERE ENTRY_DATE=CURDATE() AND NUM='$num'";
		mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if(mysql_affected_rows_js()==0)
		{
			$sql="INSERT INTO MIS.VOUCHER_DOWNLOAD(ENTRY_DATE,NUM,DOWNLOAD) VALUES(CURDATE(),'$num','1')";
			mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}
		$sql="SELECT ID,CLAIM FROM billing.VOUCHER_VIEWED WHERE PROFILEID='$data[PROFILEID]' ORDER BY ID DESC";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row=mysql_fetch_assoc($result);
		if($row["CLAIM"]=='')
		{
			$sqlmis="UPDATE billing.VOUCHER_VIEWED SET CLAIM='D' WHERE ID='$row[ID]'";
			mysql_query_decide($sqlmis) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlmis,"ShowErrTemplate");
		}
		echo $msg;
	}
	else
	{
		echo "Please select atleast one voucher";
	}
}
else
{
        TimedOut();
}
?>
