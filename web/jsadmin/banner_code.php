<?php
/*********************************************************************************************
* FILE NAME	: banner_code.php
* DESCRIPTION	: Allows the backend people to Edit banners
* CREATION DATE	: 1 July, 2005
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
		addslashes($code);
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
                                $msg.="There is an error in your code. ";
                                $smarty->assign("ERRCODE","1");
                        }
 
                        if(strstr($code,"name=\"source\"")||strstr($code,"name=\'source\'")||strstr($code,"name=source"))
                        {
                                $iserror++;
                                $msg.="The code entered by you has a variable with name=source. ";
                                $smarty->assign("ERRCODE","1");
                                $code=stripslashes($code);
                        }
                }
 
                if($iserror!=0)
                {
                        stripslashes($code);
                        $msg.="There has been an error. Please correct it and re-load the file.";
                        $smarty->assign("msg",$msg);
                        $smarty->assign("ht",$ht);
                        $smarty->assign("wd",$wd);
                        $smarty->assign("url",$url);
                        $smarty->assign("code",$code);
                        $smarty->assign("type",$type);
                        $smarty->display("banner_code.htm");
                }
                else
                {
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

			$sql_updt="UPDATE affiliate.BANNERS SET TYPE='$new_type',URL_CODE='$new_url',SIZE='$new_size',LANDPAGE='$landpage' WHERE BANNERID='$bid'";
			$res_updt=mysql_query_decide($sql_updt) or logError(mysql_error_js(),$sql_updt); 
                        $msg="The Banner has been edited successfully<br>  ";
                        $msg .="<a href=\"mainpage.php?cid=$cid\">";
                        $msg .="Go To MainPage </a>";
                        $smarty->assign("MSG",$msg);
                        $smarty->display("jsadmin_msg.tpl");
		}
	}
	else
	{
		$sql="SELECT * FROM affiliate.BANNERS WHERE BANNERID='$bid' ORDER BY TYPE";
	        $res=mysql_query_decide($sql) or logError(mysql_error_js(),$sql);
	        $row=mysql_fetch_array($res);

		if($row["TYPE"]=='IMG')
		{
			$type='1';
		}
		else
		{
			$type='2';
		}

		if($row["TYPE"]=='IMG')
		{
			$smarty->assign("url",$row["URL_CODE"]);
		}
		else
		{
			$smarty->assign("code",$row["URL_CODE"]);
		}

		$sizes=explode("x",$row["SIZE"]);
        	$smarty->assign("bid",$row["BANNERID"]);
		$smarty->assign("type",$type);
		$smarty->assign("wd",$sizes[0]);
		$smarty->assign("ht",$sizes[1]);
		$smarty->assign("LANDPAGE",$row['LANDPAGE']);
		$smarty->display("banner_code.htm");
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
