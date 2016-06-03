<?php

/*************************************************************************************************************************
*    FILENAME        : edit_userlogin.php 
*    DESCRIPTION     : Edits the user information 
**************************************************************************************************************************/  
include ("includes/bms_connect.php");
$ip=FetchClientIP();
if ($site == 'JS')
        $data=authenticatedBms($id,$ip,"banadmin");
else
        $data=authenticatedBms($id,$ip,"99acresadmin");
//$data=authenticatedBms($id,$ip,"banadmin");
$empty=1;
$site = $data['SITE'];
$smarty->assign("site",$site);
if ($data)
{
	$bmsheader=fetchHeaderBms($data);
        $bmsfooter=fetchFooterBms();
        $smarty->assign("bmsheader",$bmsheader);
        $smarty->assign("bmsfooter",$bmsfooter);

	if ($submit)
	{
		if ((trim($MOD_EMAIL)=="" ) || (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $MOD_EMAIL)))
		{
			$empty=0;
			$smarty->assign('check_email',1);
		}
		if (!$MOD_PRIV)
                {
                        $empty=0;
                        $smarty->assign('check_priv',1);
                }
		if ($empty==0)
		{
			$i =0;
			$sql_priv = "SELECT VALUE , LABEL FROM bms2.PRIVILEGES";
                        $res_priv = mysql_query($sql_priv) or die(mysql_error());
                        while($myrow=mysql_fetch_array($res_priv))
                        {
                                $privarr[$i]["value"]= $myrow["VALUE"];
				$privarr[$i]["label"]=$myrow["LABEL"];
                                $i++;
                        }
                        $smarty->assign("privarr",$privarr);
			$smarty->assign('USERNAME',$USERNAME);
			$smarty->assign('EMAIL',$MOD_EMAIL);
			$smarty->assign('MOD_PRIV',$MOD_PRIV);
			$smarty->assign('ACTIVE',$MOD_ACTIVE);
			$smarty->assign('USERID',$USERID);
			$smarty->assign('id',$id);
			$smarty->display("./$_TPLPATH/bms_edit_userlogin.htm");
		}
		else
		{
			if(!$MOD_ACTIVE)
				$MOD_ACTIVE='N';

			$sql= "UPDATE bms2.USERS SET ";
			if($MOD_PASSWD)
			{
				$sql.="PASSWORD ='$MOD_PASSWD', ";
			}
			$sql.=" EMAIL ='$MOD_EMAIL', USER_PRIVILEGE ='$MOD_PRIV', ACTIVE='$MOD_ACTIVE' WHERE USERID='$USERID' " ;
			mysql_query($sql) or die("$sql".mysql_error());

			$message = "Record Updated.<br><a href=\"bms_showuser.php?id=$id&site=$site\">Continue</a>";

			$smarty->assign("id",$id);
                	$smarty->assign("cnfrmmsg",$message);
                	$smarty->display("./$_TPLPATH/bms_confirmation.htm");
		}
	}
	else
	{  
		if($act)
		{
			$sql="update bms2.USERS set ACTIVE='$act' where USERID='$USERID'";
			mysql_query($sql) or die(mysql_error());
			$message = "Record Updated.<br><a href=\"bms_showuser.php?id=$id&site=$site\">Continue</a>";
                        $smarty->assign("id",$id);
                        $smarty->assign("cnfrmmsg",$message);
                        $smarty->display("./$_TPLPATH/bms_confirmation.htm");
		}
		else
		{
			$i = 0;
			$sql = "SELECT USERNAME,EMAIL,USER_PRIVILEGE,ACTIVE FROM bms2.USERS WHERE USERID='$USERID'" ;
			$result = mysql_query($sql) or die("$sql".mysql_error());
			$row=mysql_fetch_array($result);

			$sql_priv = "SELECT VALUE , LABEL FROM bms2.PRIVILEGES";
			$res_priv = mysql_query($sql_priv) or die(mysql_error());
			while($myrow=mysql_fetch_array($res_priv))
			{
				$privarr[$i]["value"]= $myrow["VALUE"];
				$privarr[$i]["label"]=$myrow["LABEL"];
				$i++;
			}
			$smarty->assign("privarr",$privarr);
			$smarty->assign('MOD_PRIV',$row["USER_PRIVILEGE"]);
			$smarty->assign('USERNAME',$row["USERNAME"]);
			$smarty->assign('EMAIL',$row["EMAIL"]);
			$smarty->assign('ACTIVE',$row["ACTIVE"]);
			$smarty->assign("id",$id);	
			$smarty->assign("USERID",$USERID);
			$smarty->display("./$_TPLPATH/bms_edit_userlogin.htm");
		}   
	}
}
else 
{
	TimedOutBms();
}
?>
