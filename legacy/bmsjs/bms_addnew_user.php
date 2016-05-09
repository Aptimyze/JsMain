<?php
include ("includes/bms_connect.php");
$empty=1;
$ip=FetchClientIP();
if ($site == '99acres')
        $data=authenticatedBms($id,$ip,"99acresadmin");
else
        $data=authenticatedBms($id,$ip,"banadmin");
//$data=authenticatedBms($id,$ip,"banadmin");
$empty=1;
$site = $data['SITE'];
$smarty->assign("site",$site);
//$data=authenticatedBms($id,$ip,"banadmin");

if($data)
{
	$bmsheader=fetchHeaderBms($data);
	$bmsfooter=fetchFooterBms();
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);

        if($submit)
	{
		if (trim($USERNAME)=="")
		{	
			$empty=0;
                        $smarty->assign('check_name',1);
		}
		if (trim($PASSWORD)=='')
                {
                        $empty=0;
                        $smarty->assign('check_passwd','1');
                }

		if (trim($EMAIL)=="" || (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $EMAIL)))
                {
                        $empty=0;
                        $smarty->assign('check_email',1);
                }
                if(!$PRIVILAGE)
                {
                        $empty=0;
                        $smarty->assign('check_priv','1');
                }                  
		if(!$sitename)
        	{
                	$siteclr = "red";
                	$empty = 0;
                	$smarty->assign('siteclr',$siteclr);
        	}
		$sql="SELECT COUNT(*) as cnt FROM bms2.USERS WHERE USERNAME='$USERNAME'";
		$res=mysql_query($sql) or die("$sql".mysql_error());
		$row=mysql_fetch_array($res);
		if($row['cnt']>0)
		{
			$empty=0;
			$smarty->assign("user_exists","1");
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
			$smarty->assign("PRIVILAGE",$PRIVILAGE);
			$smarty->assign("privarr",$privarr);
			$smarty->assign('USERNAME',$USERNAME);
        		$smarty->assign('PASSWORD',$PASSWORD);
                        $smarty->assign('EMAIL',$EMAIL);
         	  	$smarty->assign('ACTIVE',$ACTIVE);
			$smarty->assign("sitename",$sitename);
	                $smarty->assign('id',$id);
			$smarty->display("./$_TPLPATH/bms_addnew_user.htm");
		}
                else
		{ 
			if (!$ACTIVE)
                	{
                        	$ACTIVE='N';
                	}
			$sql = "INSERT INTO bms2.USERS (USERNAME,PASSWORD,USER_PRIVILEGE , EMAIL, ACTIVE,SITE) VALUES ('$USERNAME','$PASSWORD','$PRIVILAGE','$EMAIL','$ACTIVE','$sitename') ";
			mysql_query($sql) or die("$sql".mysql_error());

			$message = "Record Inserted.<br><a href=\"bms_showuser.php?id=$id&site=$site\">Continue</a>";
                        $smarty->assign("id",$id);
                        $smarty->assign("cnfrmmsg",$message);
                        $smarty->display("./$_TPLPATH/bms_confirmation.htm");
		}
	}
	else
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
		$smarty->assign('id',$id);
		$smarty->display("./$_TPLPATH/bms_addnew_user.htm");				  
	}  
}
else
{
	TimedOutBms();
}
?>
