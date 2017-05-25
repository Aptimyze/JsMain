<?php
include("./includes/bms_connect.php");

$ip	= FetchClientIP();
//$db99 = getConnection99acres();

$data	= authenticatedBms($id,$ip,"banadmin");


/***********************************************************************************
	fetches the name of the company corresponding to a campaign 
	input:	company id
	output: company name  									
************************************************************************************/
function getCompanyName($companyid)
{
 	$sql = "select company_name from clientprofile.company where company_id='$companyid'";
	$res = mysql_query($sql) or logErrorBms("bms_campaign.php:getcompanyName:1: Could not get name of company <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	$myrow = mysql_fetch_array($res);
	$companyname = $myrow["company_name"];
	return $companyname;
}

/************************************************************************************
	fetches the name of the salesexecutive from corresponding to a campaign
	input:	salesexecutive id
	output: salesexecutive name  
************************************************************************************/
function getSaleExectiveName($executiveid)
{
	$sql = "select name from clientprofile.sales_exec where emp_id='$executiveid'";
	$res = mysql_query($sql) or logErrorBms("bms_campaign.php:getSaleExectiveName:2: Could not get name of salesexecutive <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	$myrow = mysql_fetch_array($res);
	$salesexecutivename = $myrow["name"];
	return $salesexecutivename;
}

/************************************************************************************
	fetches the details of all campaigns corresponding to a status
	input:	campaignstatus
	output:	array of all campaigns with above status
*************************************************************************************/
function FormatCampaignDetails(&$campaignarr)
{
	global $dbbms;
	for ($i = 0;$i < count($campaignarr);$i++)
	{
		$campaignarr[$i]["companyname"]		= getCompanyName($campaignarr[$i]["companyid"]);
 		$campaignarr[$i]["executivename"]	= getSaleExectiveName($campaignarr[$i]["executiveid"]);
	}
}

/************************************************************************************
	checks if the campaign can be made active or not
	input : campaign id 
	output: true  - if campaign can be made active
		false - if campaign cannot  be made active
*************************************************************************************/
function checkMakeCampaignActive($campaignid)
{
	global $dbbms;
	$sql = "select BannerId from bms2.BANNER  where CampaignId='$campaignid' and (BannerStatus='booked' or BannerStatus='live' or BannerStatus='ready')";
	$res = mysql_query($sql,$dbbms) or logErrorBms("bms_campaign.php:checkMakeCampaignActive:5: Could not check if the campaign is allowed to be made active or not <br>	<!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	if (mysql_num_rows($res))
		return 1;
	else 
		return 0;
}

if($data)
{
	//campaign status passed from form else default status(hold) is assumed.

	global $dbbms,$smarty;
	$id		= $data["ID"];
	$site		= $data["SITE"];
	$bmsheader	= fetchHeaderBms($data);
	$bmsfooter	= fetchFooterBms();

	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);
	$smarty->assign("site",$site);
	$smarty->assign("id",$id);

	if($Submit)
	{
		if (is_array($send_mail))
		{
		$campainidstr = implode("','",$send_mail);
		$sql= "SELECT CampaignName , SITE , REF_ID , CampaignExecutiveId FROM CAMPAIGN WHERE CampaignId IN ('$campainidstr')";
		$res=mysql_query($sql,$dbbms) or logErrorBms("bms_incomplete_bannerdetails.php: Could not get list of campaigns. <br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
		$db99 = getConnection99acres();
		while($row = mysql_fetch_array($res))
		{
			if ($row["SITE"] == 'JS')
        		{
                		$sql_exec = "SELECT EMAIL FROM jsadmin.PSWRDS WHERE EMP_ID = $row[CampaignExecutiveId]";
                		$res_exec=mysql_query($sql_exec) or logErrorBms("bms_expiryalert.php: Could not retrieve email-id of the sales executive. <br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql_exec, "ShowErrTemplate");
                		$row_exec=mysql_fetch_array($res_exec);
                		$em = $row_exec['EMAIL'];
        		}
        		else
        		{
                		$sql_exec = "SELECT EMAIL FROM sums.PSWRDS WHERE EMP_ID = $row[CampaignExecutiveId]";
                		$res_exec=mysql_query($sql_exec,$db99) or logErrorBms("bms_expiryalert.php: Could not retrieve email-id of the sales executive.<br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql_exec, "ShowErrTemplate");
                		$row_exec=mysql_fetch_array($res_exec);
                		$em = $row_exec['EMAIL'];
        		}
			//$em = "shobha.solanki@gmail.com";
			$cc = "lijuv@naukri.com";
			if ($em)
			{
				//$em = "shobha.solanki@gmail.com";
				$send = 1;
				$announce_from="webmaster@ieplads.com";
				$announce_subject="BMS Reminder for furnishing Campaign Details";
				$MP = "/usr/sbin/sendmail -t";
				$MP .= " -f $announce_from";
				$fd = popen($MP,"w"); //write the mail from here
																    
				fputs($fd, "To: $em \n");
				fputs($fd, "Cc: $cc\n");
				fputs($fd, "From: $announce_from \n");
				fputs($fd, "Subject: $announce_subject \n");
				fputs($fd, "X-Mailer: PHP3\n");
				fputs($fd, "Content-type: text/html; charset=us-ascii \n");
				fputs($fd, "Content-Transfer-Encoding: 7bit \n");
				fputs($fd, "\n\n");
																    
				$smarty->assign ("campaignname",$row["CampaignName"]);
				$output = $smarty->fetch("./$_TPLPATH/bms_reminder.htm");
				fputs($fd, "$output \n\n");
				pclose($fd);
			}
			
		}
		if ($send)
		{
			$message = "Reminder has been sent to the email ids";
                        $smarty->assign("cnfrmmsg",$message);
                        $smarty->assign("id",$id);
                        $smarty->assign("site",$site);
                        $smarty->display("./$_TPLPATH/bms_confirmation.htm");
		}
		}
		else
		{
			echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/bmsjs/bms_adminindex.php?id=$id\"></body></html>";                         
			exit;
		}
	}
	else
	{
	$i = 0;
	$sql = "SELECT CampaignId , CampaignName , CampaignEntryDate , CampaignStartDt , CampaignEndDt , SITE,  CampaignExecutiveId FROM CAMPAIGN WHERE CampaignStatus='new'";
	$res=mysql_query($sql,$dbbms) or logErrorBms("bms_incomplete_bannerdetails.php: Could not get list of campaigns. <br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "ShowErrTemplate");
	while($row = mysql_fetch_array($res))
	{
		$campaigndetails[$i]["CampaignId"]= $row["CampaignId"];
		$campaigndetails[$i]["CampaignName"]= $row["CampaignName"];
		$campaigndetails[$i]["CampaignEntryDate"]=$row["CampaignEntryDate"];
		$campaigndetails[$i]["CampaignStartDt"]=$row["CampaignStartDt"];
		$campaigndetails[$i]["CampaignEndDt"]=$row["CampaignEndDt"];
		$campaigndetails[$i]["site"]=$row["SITE"];
		$campaigndetails[$i]["ExecutiveName"]=getSaleExectiveName($row["CampaignExecutiveId"]);
		$i++;
	}
	$smarty->assign("waitcampaigndetails",$campaigndetails);
        $smarty->display("./$_TPLPATH/bms_incomplete_bannerdetails.htm");
	}

}
else
{
	TimedOutBms();
}

