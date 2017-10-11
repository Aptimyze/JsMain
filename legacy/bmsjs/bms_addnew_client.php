<?php
include ("includes/bms_connect.php");
$empty=1;
$ip=FetchClientIP();

//$data=authenticatedBms($id,$ip,"banadmin");
if ($site == 'JS')
        $data=authenticatedBms($id,$ip,"banadmin");
else
        $data=authenticatedBms($id,$ip,"99acresadmin");

$site = $data['SITE'];
$smarty->assign("site",$site);
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
                if(!$COMPANYID)
                {
                        $empty=0;
                        $smarty->assign('check_campaign','1');
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
			$j =0;
                        $sql_priv = "SELECT VALUE , LABEL FROM bms2.PRIVILEGES WHERE VALUE ='client'";
                        $res_priv = mysql_query($sql_priv) or die(mysql_error());
                        $myrow=mysql_fetch_array($res_priv);

			$privarr["value"]= $myrow["VALUE"];
			$privarr["label"]=$myrow["LABEL"];

			if(count($COMPANYID) > 1)
				$companyarr = implode(",",$COMPANYID);
			$smarty->assign("COMPANYID",$COMPANYID);
			$smarty->assign("comparr",getCampaign($companyarr,$site));

			$smarty->assign("PRIVILAGE",$PRIVILAGE);
			$smarty->assign("privarr",$privarr);

			$smarty->assign('USERNAME',$USERNAME);
        		$smarty->assign('PASSWORD',$PASSWORD);
                        $smarty->assign('EMAIL',$EMAIL);
         	  	$smarty->assign('ACTIVE',$ACTIVE);
	                $smarty->assign('id',$id);
			$smarty->display("./$_TPLPATH/bms_addnew_client.htm");
		}
                else
		{     
			if (!$ACTIVE)
                	{
                        	$ACTIVE='N';
                	}
			$sql = "INSERT INTO bms2.USERS (USERNAME,PASSWORD,USER_PRIVILEGE,EMAIL, ACTIVE,SITE) VALUES ('$USERNAME','$PASSWORD','$PRIVILAGE','$EMAIL','$ACTIVE','$site') ";
			mysql_query($sql) or die("$sql".mysql_error());
			$userid=mysql_insert_id();

			for ($i =0;$i < count($COMPANYID);$i++)
			{
				$sql = "INSERT INTO clientprofile.client_reg (user_id , user_name , pswd , company_id , last_access , email) VALUES ('$userid','$USERNAME','$PASSWORD','$COMPANYID[$i]',NOW(),'$EMAIL')";
				mysql_query($sql) or die("$sql".mysql_error());
			}
			

			$message = "Record Inserted.<br><a href=\"bms_showuser.php?id=$id&site=$site\">Continue</a>";
                        $smarty->assign("id",$id);
                        $smarty->assign("cnfrmmsg",$message);
                        $smarty->display("./$_TPLPATH/bms_confirmation.htm");
		}
	}
	else
        {
		$sql_priv = "SELECT VALUE , LABEL FROM bms2.PRIVILEGES where VALUE = 'client'";
        	$res_priv = mysql_query($sql_priv) or die(mysql_error());
                $myrow=mysql_fetch_array($res_priv);
		$privarr["value"]= $myrow["VALUE"];
		$privarr["label"]=$myrow["LABEL"];

		$j = 0;

		$smarty->assign("comparr",getCampaign("",$site));
		//$smarty->assign("comparr",$comparr);
		$smarty->assign("privarr",$privarr);
		$smarty->assign('id',$id);
		$smarty->display("./$_TPLPATH/bms_addnew_client.htm");				  
	}  
}
else
{
	TimedOutBms();
}
function getCampaign($campaign,$site="")
{
        $comparr = explode(",",$campaign);

	if ($site == '99acres')
		$sql = "SELECT CampaignName , CompanyId FROM CAMPAIGN WHERE SITE='99acres' ORDER BY CampaignEntryDate DESC";
	else
		$sql = "SELECT CampaignName , CompanyId FROM bms2.CAMPAIGN";
        $res = mysql_query($sql) or logErrorBms("bms_connect.inc:getIncome:1: Could not select cities <br>       <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
        $j = 0;

        while($myrow=mysql_fetch_array($res))
        {
		$campaignarr[$j]["name"]= $myrow["CampaignName"];
		$campaignarr[$j]["id"]=$myrow["CompanyId"];

                if(in_array("$myrow[CompanyId]",$comparr))
                {
                        $campaignarr[$j]["selected"] = "selected";
                }
                else
                        $campaignarr[$j]["selected"] = "";
                $j++;
        }
        return $campaignarr;

} 
?>
