<?php
/*********************************************************************************************
* FILE NAME	: upload_banner.php
* DESCRIPTION	: Allows the backend people to upload banners
* CREATION DATE	: 30 June, 2005
* CREATED BY	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("connect.inc");
//$db=connect_db();

$data=authenticated($cid);
$smarty->assign("cid",$cid);

if(isset($data))
{
	$iserror=0;
	$msg='';
	if($submit)
	{
		if($type==0)
		{
			$iserror++;
                        $smarty->assign("ERRTYPE","1");
		}

		if($ht==''||$wd=='')
		{
			$iserror++;
			$smarty->assign("ERRSIZE","1");
		}

		if($type=='1'&&$url=='')
		{
			$iserror++;
                        $smarty->assign("ERRPIC","1");
		}

		if($type=='2')
		{
			if($code=='')
			{
				$iserror++;
	                        $smarty->assign("ERRCODE","1");
			}
			if(!strstr($code,'<form'))
			{
				$iserror++;
				$msg.="Your code does not contain a \"&lt;form&gt;\". ";
				$smarty->assign("ERRCODE","1");
			}

			$code=stripslashes($code);
			if(strstr($code,'name="source"')||strstr($code,"name=source"))
                        {
                                $iserror++;
                                $msg.="The code entered by you has a variable with 'name=source'. ";
                                $smarty->assign("ERRCODE","1");
				$code=stripslashes($code);
                        }
		}

		if($iserror!=0)
		{
			$code=stripslashes($code);
			$msg.="Please correct the error and re-load the file.";
			$smarty->assign("msg",$msg);
			$smarty->assign("ht",$ht);
			$smarty->assign("wd",$wd);
			$smarty->assign("url",$url);
			$smarty->assign("code",$code);
			$smarty->assign("type",$type);
			$smarty->display("upload_banner.htm");
		}
		else
		{
			$code=stripslashes(addslashes($code));
			if(!strstr($code,'target'))
			{
				$code=str_replace("<form","<form target=_blank",$code);
			}

			$new_size=$wd.'x'.$ht;
			if($type=='1')
			{
				$new_type='IMG';
				$new_url=$url;
			}
			else if($type=='2')
			{
				$replace_str='<input type="hidden" name="source" value="af$aid$bid"></form>';
				$code=str_replace("</form>",$replace_str,$code);
				$new_type='HTM';
				$new_url=$code;
			}

			$sql2="SELECT COUNT(*) AS CNT FROM affiliate.BANNERS WHERE SIZE='$new_size'";
			$res2=mysql_query_decide($sql2) or logError(mysql_error_js(),$sql2);
			$row2=mysql_fetch_array($res2);
			if($row2["CNT"]==0)
			{
				$sql3="INSERT INTO affiliate.BANNERS VALUES('','$new_type','$new_url','$new_size','Y',now(),'$landing')";
				$res3=mysql_query_decide($sql3) or logError(mysql_error_js(),$sql3);
			}
			else
			{
				$sql3="INSERT INTO affiliate.BANNERS VALUES('','$new_type','$new_url','$new_size','',now(),'$landing')";
				$res3=mysql_query_decide($sql3) or logError(mysql_error_js(),$sql3);
			}
			$msg="The Banner has been uploaded successfully<br>  ";
		        $msg .="<a href=\"mainpage.php?cid=$cid\">";
		        $msg .="Go To MainPage </a>";
		        $smarty->assign("MSG",$msg);
		        $smarty->display("jsadmin_msg.tpl");
		}
	}
	else
	{
		$smarty->display("upload_banner.htm");
	}
}
else
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
