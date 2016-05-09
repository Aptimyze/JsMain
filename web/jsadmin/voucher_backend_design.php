<?php
/*****************************************************************************************************
Filename    : voucher_backend_design.php
Description : Design end for voucher backend module [2177]
Created On  : 6 September 2007
Created B   : Sadaf Alam
******************************************************************************************************/
include("connect.inc");
include("../crm/func_sky.php");
$db=connect_db();

if(authenticated($cid))
{
	if($formsubmit)
	{
		$sql_status="SELECT SERVICE,STATUS FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$clientid'";
                $res_status=mysql_query_decide($sql_status) or die("$sql_status".mysql_error_js());
                $row_status=mysql_fetch_assoc($res_status);
		if($row_status["SERVICE"]=="N")
                {
                        $msg="The deal has been stopped by the sales team. Please wait until furthur intimation";
                        $msg .="&nbsp;&nbsp;";
                        $msg .="<a href=\"voucher_backend_design.php?name=$name&cid=$cid\">";
                        $msg .="Click here to continue&gt;&gt;</a>";
                        $smarty->assign("MSG",$msg);
                        $smarty->assign("name",$name);
                        $smarty->assign("cid",$cid);
                        $smarty->display("jsadmin_msg.tpl");
                        die;
                }
		if(!$editdeal)
		{
			if($row_status["STATUS"]=="RD")
			{
				$sql_status="UPDATE billing.VOUCHER_CLIENTS SET STATUS='D' WHERE CLIENTID='$clientid'";
				mysql_query_decide($sql_status);
				$msg="The deal has been updated for design details again. Please select the deal from the Design Pending Deals section";
				$msg .="&nbsp;&nbsp;";
				$msg .="<a href=\"voucher_backend_design.php?name=$name&cid=$cid\">";
				$msg .="Click here to Continue&gt;&gt;</a>";
				$smarty->assign("MSG",$msg);
				$smarty->assign("name",$name);
				$smarty->assign("cid",$cid);
				$smarty->display("jsadmin_msg.tpl");
				die;

			}
		}
		if(!$vlogo && !$editdeal)
		$error["file"]["Vlogo"]=1;
		if($vlogo)
		{
			$file=$_FILES['vlogo'];
			if(!checktype($file['type']))
			$error["type"]["Vlogo"]=1;

		}
		if(!$logo && !$editdeal)
		$error["file"]["Logo"]=1;
		if($logo)
		{
			$file=$_FILES['logo'];
			if(!checktype($file['type']))
			$error["type"]["Logo"]=1;
			
		}
		if(!$creative1 && !$editdeal)
		$error["file"]["Creative1"]=1;		
		if($creative1)
		{
			$file=$_FILES['creative1'];
			if(!checktype($file['type']))
			$error["type"]["Creative1"]=1;
		}
		if(!$creative2 && !$editdeal)
		$error["file"]["Creative2"]=1;
	 	if($creative2)
		{
			$file=$_FILES['creative2'];
			if(!checktype($file['type']))
			$error["type"]["Creative2"]=1;
			
		}
		if($type=="E")
		{
			if(!$voucher && !$editdeal && !$oldvoucher)
			$error["file"]["Voucher"]=1;
			if($voucher)
			{
				$file=$_FILES['voucher'];
				if(!checktype($file['type']))
				$error["type"]["Voucher"]=1;
			}
		}
		if($editdeal && !$voucher && !$logo && !$vlogo && !$creative1 && !$creative2)
		{
			$error["nochange"]=1;
		}
		if(($editdeal && ($error["type"] || $error["nochange"])) || (!$editdeal && $error))
		{
			$sql="SELECT IMAGE_FILE FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$clientid'";
			$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$row=mysql_fetch_assoc($result);
			if($row["IMAGE_FILE"])
			{
				$filename=$row["IMAGE_FILE"];
				$link="<a href=\"voucher_backend_download.php?clientid=$clientid&filename=$filename&design=1\">Download</a>";
				$smarty->assign("link",$link);
			}
			$smarty->assign("error",$error);
			$smarty->assign("EDIT","1");
			$smarty->assign("cname",$cname);
			$smarty->assign("headline",$headline);
			$smarty->assign("vsummary",$vsummary);
			$smarty->assign("vdetails",$vdetails);
			$smarty->assign("cdetails",$cdetails);
			$smarty->assign("comments",$comments);
			$smarty->assign("sdate",$sdate);
			$smarty->assign("clientid",$clientid);
			if($design)
			{
				$smarty->assign("design",$design);
				if($oldvoucher)
				$smarty->assign("oldvoucher",$oldvoucher);
			}
			if($editdeal)
			$smarty->assign("editdeal",$editdeal);
		}
		else
		{	
			if($vlogo)
			{
				$file=$_FILES['vlogo'];
				$fp=fopen($file["tmp_name"],"rb");
				if($fp)
				{
					$vlogocon=fread($fp,filesize($file['tmp_name']));
					fclose($fp);
				$vlogocon=addslashes($vlogocon);
				}
				else
				{
					die("Some error occured while reading vlogo file. Please try again");
				}
			}
			if($logo)
			{
				$file=$_FILES['logo'];
				$fp=fopen($file["tmp_name"],"rb");
				if($fp)
				{
					$logocon=fread($fp,filesize($file["tmp_name"]));
					fclose($fp);
					$logocon=addslashes($logocon);
				}
				else
				{
					die("Some error occured during reading the logo file. Please try again");
				}
			}
			if($creative1)
			{
				$file=$_FILES['creative1'];
				$fp=fopen($file["tmp_name"],"rb");
				if($fp)
				{	
					$creative1con=fread($fp,filesize($file["tmp_name"]));
					fclose($fp);
					$creative1con=addslashes($creative1con);
				}
				else
				{
					die("Some error occured during reading the creative 1 file. Please try again");
				}
			}
			if($creative2)
			{
				$file=$_FILES['creative2'];
				$fp=fopen($file["tmp_name"],"rb");
				if($fp)
				{
					$creative2con=fread($fp,filesize($file["tmp_name"]));
					fclose($fp);
					$creative2con=addslashes($creative2con);
				}
				else
				{
					die("Some error occured during reading the creative 2 file. Please try again");
				}
			}
			if($type=="E")
			{
				if($voucher)
				{
					$file=$_FILES['voucher'];
					$fp=fopen($file["tmp_name"],"rb");
					if($fp)
					{
						$vouchercon=fread($fp,filesize($file["tmp_name"]));
						fclose($fp);
						$vouchercon=addslashes($vouchercon);
					}
					else
					{
						die("Some error occured during reading the voucher file. Please try again");
					}
				}
			}
			if($editdeal)
			{
				$sql="UPDATE billing.VOUCHER_CLIENTS SET ";
				if($vlogocon)
				$sql.="VLOGO='$vlogocon',";
				if($logocon)
				$sql.="LOGO_FILE='$logocon',";
				if($creative1con)
				$sql.="CREATIVE1='$creative1con',";
				if($creative2con)
				$sql.="CREATIVE2='$creative2con',";
				if($vouchercon)
                                $sql.="VOUCHER='$vouchercon',";
				$sql=substr($sql,0,strlen($sql)-1);
				$sql.=" WHERE CLIENTID='$clientid'";
				$msg="A  deal has been updated by the design team. The client details are as follows :";
                                $msg.="<br> Client Name : $cname";
                                $msg.="<br> Client Details : $cdetails";
                                $msg.="<br> Voucher Details : $vdetails";
				$subject="Deal Edited by design team ".$cname;
                                send_mail('shweta.bahl@naukri.com','lotika.sharma@naukri.com','',$msg,$subject,"promotions@jeevansathi.com");

			}
			else
			{
				$sql="UPDATE billing.VOUCHER_CLIENTS SET VLOGO='$vlogocon',LOGO_FILE='$logocon',CREATIVE1='$creative1con',CREATIVE2='$creative2con',";
				if($type=="E")
				$sql.="VOUCHER='$vouchercon',";
				if($row_status["SERVICE"]=="Y")
				$sql.="STATUS='L'";
				else
				$sql.="STATUS='C'";
				$sql.=" WHERE CLIENTID='$clientid'";
				$msg="Design details for the following deal has been completed by the design team : $cname";
				$subject="Deal updated by design team ".$cname;
				send_mail('shweta.bahl@naukri.com','lotika.sharma@naukri.com','',$msg,$subject,'promotions@jeevansathi.com');
			}
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if($editdeal)
			$smarty->assign("editdeal","1");
			$smarty->assign("cname",$cname);
			$smarty->assign("DONE","1");		
		}		
		$smarty->assign("clientid",$clientid);
		$smarty->assign("type",$type);
	}
	elseif($dealsubmit)
	{
		$sql="SELECT CLIENT_NAME,TYPE,CDETAILS,HEADLINE,VSUMMARY,VDETAILS,COMMENTS,START_DATE,IMAGE_FILE,VLOGO,VOUCHER FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$deal'";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$row=mysql_fetch_assoc($result);
		if($row["IMAGE_FILE"])
		{
			$filename=$row["IMAGE_FILE"];
			$link="<a href=\"voucher_backend_download.php?clientid=$deal&filename=$filename&design=1\">Download</a>";
			$smarty->assign("link",$link);
		}
		$smarty->assign("clientid",$deal);
		$smarty->assign("cname",$row["CLIENT_NAME"]);
		$smarty->assign("type",$row["TYPE"]);
		$smarty->assign("sdate",$row["START_DATE"]);
		$smarty->assign("cdetails",html_entity_decode($row["CDETAILS"],ENT_QUOTES));
		$smarty->assign("headline",html_entity_decode($row["HEADLINE"],ENT_QUOTES));
		$smarty->assign("vsummary",html_entity_decode($row["VSUMMARY"],ENT_QUOTES));
		$smarty->assign("vdetails",html_entity_decode($row["VDETAILS"],ENT_QUOTES));
		$smarty->assign("comments",html_entity_decode($row["COMMENTS"],ENT_QUOTES));
		$smarty->assign("EDIT","1");
		if($editdeal)
		{
			$smarty->assign("editdeal","1");
		}
		if($row["VLOGO"])
		{
			if($row["TYPE"]=="E" && !$row["VOUCHER"])
			$smarty->assign("oldvoucher","1");
			$smarty->assign("design","1");
		}
	}
	else
	{
		if($editdeal)
		$sql="SELECT CLIENTID,CLIENT_NAME FROM billing.VOUCHER_CLIENTS WHERE STATUS!='D' AND STATUS!='RD' AND SERVICE!='N'";
		else
		$sql="SELECT CLIENTID,CLIENT_NAME,STATUS FROM billing.VOUCHER_CLIENTS WHERE STATUS IN('D','RD') AND SERVICE!='N'";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		if(mysql_num_rows($result))
		{
			while($row=mysql_fetch_assoc($result))
			{
				$value=$row["CLIENTID"];
				$label=$row["CLIENT_NAME"];
				$options.="<option value=$value>$label</option>";
				if($row["STATUS"]=="RD")
				{
					$sql_status="UPDATE billing.VOUCHER_CLIENTS SET STATUS='D' WHERE CLIENTID='$row[CLIENTID]'";
					mysql_query_decide($sql_status) or die("$sql_status".mysql_error_js());
				}
			}
			$smarty->assign("options",$options);
		}
		else
		$smarty->assign("NODEALS","1");
		if($editdeal)
		$smarty->assign("editdeal","1");
	}
	$smarty->assign("name",$name);
	$smarty->assign("cid",$cid);
	$smarty->display("voucher_backend_design.htm");
}
else
{
	$msg="Your session has been timed out";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}
function checktype($type)
{
	if(strpos($type,"jpg") || strpos($type,"gif") || strpos($type,"jpeg"))
	return 1;
	else
	return 0;
}
?>
