<?php
/*************************bms_booking.php***********************************/
  /*
   *  Created By         : Shobha Kumari
   *  Last Modified By   : 
   *  Description        : This file is used to enter the camapign details(booking campaign)
			   which include number of banners , company name etc.
   *  Includes/Libraries : ./includes/bms_connect.php
*/
/***************************************************************************/
include_once("./includes/bms_connect.php");
$db205 = getConnection205();
$dbbms = getConnectionBms();
if($js_flag=='Y')
{
	$data=authenticated($id);
	$smarty->assign("js_flag",'Y');
	$smarty->assign("sitename",'JS');
	$smarty->assign("ref_id",$ref_id);
	$ref_id_arr=explode("-",$ref_id);
	$sql="SELECT COMP_NAME,TOTAL_AMT,SALE_BY from billing.REV_MASTER where SALEID=$ref_id_arr[1]";
	$res=mysql_query($sql,$db205) or die($sql.mysql_error());
	$row=mysql_fetch_array($res);
        $amount=$row["TOTAL_AMT"];
	$companyname=$row["COMP_NAME"];
	$sale_by=$row["SALE_BY"];
	$sql="SELECT EMP_ID from jsadmin.PSWRDS where USERNAME='$sale_by'";
	$res=mysql_query($sql,$db205) or die($sql.mysql_error());
        $row=mysql_fetch_array($res);
        $sales_empid=$row["EMP_ID"];
	$smarty->assign("companyname",$companyname);
	$smarty->assign("amount",$amount);
}
else
{
	$data=authenticatedBms($id,$ip,"banadmin");

}
$id=$data["ID"];
$site = $data["SITE"];
if($data)
{
	$bmsheader=fetchHeaderBms($data);
	$bmsfooter=fetchFooterBms();
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);

	/*************************************************************************************
		This function defines an array of the different campaign types
	/**************************************************************************************/

	function getCampaignTypes()
	{
		$campaigntype=array("0"=>array("campaigntype"=>"Duration","campaignvalue"=>"duration"),
				    "1"=>array("campaigntype"=>"Impression","campaignvalue"=>"impression")
				    );
		return $campaigntype;
	}

	/*************************************************************************************
		This function calculates the default end date of a campaign(1 month from startdate)
	/**************************************************************************************/

	function getEndDate()
	{
		global $dbbms;
		$sql="select DATE_ADD(CURDATE(),INTERVAL 1 MONTH) as enddate";
		$res=mysql_query($sql,$dbbms) or logErrorBms("bms_sums.php:getEndDate :5: Could not get end date. <br>  <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "continue");
		$myrow=mysql_fetch_array($res);
		$enddate=$myrow["enddate"];
		return $enddate;
																     
	}

	/*************************************************************************************
		This function shows the form for booking campaign
	/**************************************************************************************/

	function showForm()

	{
		global $dbbms , $smarty,$_TPLPATH , $sales_empid;
		$j = 0;
		for ($i = 0; $i < 100; $i++ )
		{
			$qtyarr[$i][1]['opt'] = $qtyarr[$i][2]['opt'] = $qtyarr[$i][3]['opt'] = $qtyarr[$i][4]['opt'] = $i+1;
			$qtyarr[$i][1]['val'] = $qtyarr[$i][2]['val'] = $qtyarr[$i][3]['val'] = $qtyarr[$i][4]['val'] = $i+1;
		}
		$misoption	= create_dd("","misopt");
		$days		= getDaysBms();
		$months		= getMonthsBms();
		$years		= getYearsBms();
		$campaigntypearr= getCampaignTypes();
		$enddate	= getEndDate();
		$enddatearr	= explode("-",$enddate);
		$curdate	= date("Y-m-d");
		$salesexec	= getSalesExec();
		
		$smarty->assign("campaigntypearr",$campaigntypearr);
		$smarty->assign("qtyarr",$qtyarr);
		$smarty->assign("days",$days);
		$smarty->assign("months",$months);
		$smarty->assign("years",$years);
		$smarty->assign("startday",date("d"));
		$smarty->assign("startyear",date("Y"));
		$smarty->assign("startmonth",date("m"));
		$smarty->assign("endday",$enddatearr[2]);
		$smarty->assign("endmonth",$enddatearr[1]);
		$smarty->assign("endyear",$enddatearr[0]);
		$smarty->assign("mis",$misoption);
		$smarty->assign("salesexec",$salesexec);
		$smarty->assign("saleby",$sales_empid);
		$smarty->assign("transactionid",$transactionid);
		$smarty->assign("misuser",$misuser);
		$smarty->assign("password",$password);
		$smarty->display("./$_TPLPATH/bms_booking.htm");                                                                   
	}

	/*************************************************************************************
		This function performs form validation
	/**************************************************************************************/

	function checkForm($campaignname,$companyname,$companyemail,$bannerqty,$popupqty,$popunderqty,$advmailerqty,
			$campaigntype,$campaignduration="",$campaignimpression="",$sitename,$campaignstartdt,$campaignenddt,$showmis,$misdetails,$amount,$saleby,$misuser,$password)
	{
		global $smarty,$_TPLPATH;
		global $dbbms,$dbsums;

		$check			= 1;
		$campaignname		= trim(addslashes($campaignname));
		$companyname		= trim(addslashes($companyname));
		$companyemail		= trim(addslashes($companyemail));
		$campaignimpression 	= trim(addslashes($campaignimpression));
		$amount                 = trim(addslashes($amount));
		$saleby			= trim(addslashes($saleby));
		$misuser		= trim(addslashes($misuser));
		$password		= trim(addslashes($password));

		if ($campaignname == "")
		{
			$campaignclr 	= "red";
			$check 		= 0;
			$smarty->assign('campaignclr',$campaignclr);
		}
		if ($companyname == "")
		{
			$companyclr 	= "red";
			$check 		= 0;
			$smarty->assign('companyclr',$companyclr);
		}
		if (($companyemail == "") || (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $companyemail)))
		{
			$companyeclr 	= "red";
			$emailerrormsg 	= "Please enter a valid company email address.";
			$check		= 0;
			$smarty->assign('companyeclr',$companyeclr);
			$smarty->assign('emailerrormsg',$emailerrormsg);
		}
		if ($misuser== "")
                {
                        $usrclr     = "red";
                        $check          = 0;
                        $smarty->assign('usrclr',$usrclr);
                }
		if ($password == "")
                {
                        $passwdclr     = "red";
                        $check          = 0;
                        $smarty->assign('passwdclr',$passwdclr);
                }
		if ((!$bannerqty || $bannerqty == 0) && (!$popupqty || $popupqty == 0) && (!$popunderqty || $popunderqty == 0) && (!$advmailerqty || $advmailerqty == 0))
		{
			$qtyclr 	= "red";
			$check		= 0;
			$smarty->assign('qtyclr',$qtyclr);
		}
		if (!$campaignenddt || !$campaignstartdt || ($campaignenddt<$campaignstartdt))
		{
			$dateclr 	= "red";
			$dateerrormsg	= "Please enter the duration correctly.";
			$check		= 0;
			$smarty->assign('dateclr',$dateclr);
			$smarty->assign('dateerrormsg',$dateerrormsg);
		}
		if ($campaigntype == "impression" && ($campaignimpression == "" || $campaignimpression == "0" ))
		{
			$campaigntypeiclr = "red";
			$check		  = 0;
			$smarty->assign('campaigntypeiclr',$campaigntypeiclr);
		}
		/*if ((strcmp($campaigntype,"duration") == 0) && ($campaignduration == "" || $campaignduration == "0"))
		{
			$campaigntypedclr = "red";
			$check		  = 0;
			$smarty->assign('campaigntypedclr',$campaigntypedclr);
		}*/
		if ($campaigntype == "duration" && ($campaignimpression!=""))
		{
			$campaigntypedclr = "red";
			$durationmsg	  = "You cannot enter impressions with duration type banners.";
			$check            = 0;
			$smarty->assign('campaigntypedclr',$campaigntypedclr);
			$smarty->assign('durationmsg',$durationmsg);
		}
		if ($campaigntype == "impression" && ($campaignduration!=""))
		{
			$campaigntypeiclr = "red";
			$impmsg           = "You cannot enter duration with impressions type banners.";
			$check            = 0;
			$smarty->assign('campaigntypeiclr',$campaigntypeiclr);
			$smarty->assign('impmsg',$impmsg);
		}
		if(!$sitename)
		{
			$siteclr = "red";
			$sitemsg="To continue, please choose site name.";
			$check= 0;
			$smarty->assign('sitemsg',$sitemsg);
			$smarty->assign('siteclr',$siteclr);
		}
		if ($amount == "" || $amount == 0 || !is_numeric($amount))
		{
			$amountclr = "red";
			$check     = 0;
			$smarty->assign('amountclr',$amountclr);
		}
	/*	if ($saleby == "")
		{
			$salebyclr = "red";
			$check     = 0;
			$smarty->assign('salebyclr',$salebyclr);	
		}*/
		if ($campaignname != "")
		{	
			// this query checks whether campaign name already exists or not 
			$sql = "SELECT COUNT(*) as cnt FROM bms2.CAMPAIGN WHERE CampaignName='".addslashes($campaignname)."'";
			$result = mysql_query($sql,$dbbms) or logErrorBms("bms_booking.php:checkform :1: Could not select transaction details. <br>     <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql, "continue");
																     
			$myrow = mysql_fetch_array($result);
			if($myrow['cnt'] > 0)
			{
				$campaignclr = "red";
				$check       = 0;
				$smarty->assign('campaignclr',$campaignclr);
				$cnameerrormsg="This campaign name already exists with your company.Please select a different name.";
				$smarty->assign("cnameerrormsg",$cnameerrormsg);
			}
		}
		if($misuser != "")
		{
			$sql="SELECT COUNT(*) as cnt FROM bms2.USERS WHERE USERNAME='$misuser'";
			$res=mysql_query($sql,$dbbms) or die("$sql".mysql_error());
			$row=mysql_fetch_array($res);
			if($row['cnt']>0)
			{
				$usrclr = "red";
                                $check       = 0;
                                $smarty->assign('usrclr',$usrclr);
                                $cnameerrormsg="This username already exists .Please select a different username.";
                                $smarty->assign("usrerrormsg",$usrerrormsg);

			}
		}

		if (!$showmis || ($showmis == 'no' && count($misdetails) > 0))
		{
			$showmisclr = "red";
			$check       = 0;
			$showmiserrormsg = "You need to select 'yes' to customise mis options";
			$smarty->assign("showmiserrormsg",$showmiserrormsg);
			$smarty->assign('showmisclr',$showmisclr);
		}
		if ($check == 0)
		{
			$errormsg = "To continue , please correct the errors first ";
			$smarty->assign("errormsg",$errormsg);
			return 0;
		}
		else
			return 1;
	}
			/**************action taken after form is submitted ***************/

	//if($savedetails)
	if($submit)
	{
		$qtyarr[][] = array(); // array to create dropdown for number of popups , popunder etc..
		for($i=0;$i<count($arr);$i++)
		{
			$sql="SELECT LABEL FROM bms2.INCOME WHERE VALUE='$arr[$i]'";
			$res = mysql_query($sql,$dbbms) or die("$sql".mysql_error());
			$row = mysql_fetch_row($res);
			$ctclabel[$i]=$row[0];
		}
		
		for ($i = 0; $i < 100; $i++ )
		{
			$qtyarr[$i][1]['opt'] = $qtyarr[$i][2]['opt'] = $qtyarr[$i][3]['opt'] = $qtyarr[$i][4]['opt'] = $i+1;
			$qtyarr[$i][1]['val'] = $qtyarr[$i][2]['val'] = $qtyarr[$i][3]['val'] = $qtyarr[$i][4]['val'] = $i+1;
		}

		$days			= getDaysBms();
		$months			= getMonthsBms();
		$years			= getYearsBms();
		$campaigntypearr	= getCampaignTypes();
		$salesexec		= getSalesExec();
		$currentdate		= date("Y-m-d");

		$smarty->assign("salesexec",$salesexec);
		$smarty->assign("qtyarr",$qtyarr);
		$smarty->assign("campaigntypearr",$campaigntypearr);
		$smarty->assign("days",$days);
		$smarty->assign("months",$months);
		$smarty->assign("years",$years);
			
		$campaignstartdt = $startyear."-".$startmonth."-".$startday;
		$campaignenddt = $endyear."-".$endmonth."-".$endday;

		if(checkForm($campaignname,$companyname,$companyemail,$bannerqty,$popupqty,$popunderqty,$advmailerqty,$campaigntype,$campaignduration,$campaignimpression,$sitename,$campaignstartdt,$campaignenddt,$showmis,$misdetails,$amount,$saleby,$misuser,$password))
		{
			$totqty = $bannerqty;  // total of all the banners including popup etc..
			if (count($misdetails) > 1)
				$misoptions = implode(',',$misdetails);		
			else
				$misoptions = $misdetails[0];
			
			$sql="select company_id from clientprofile.company where company_name='".addslashes($companyname)."'";
                        $res=mysql_query($sql,$dbbms) or die($sql.mysql_error());
                        $row=mysql_fetch_array($res);
                        if($row["company_id"]>0)
                                $companyid=$row["company_id"];
                        else
                        {
                        	$sql="INSERT IGNORE INTO clientprofile.company(company_name,master_email) VALUES ('$companyname','$companyemail')";
                                mysql_query($sql,$dbbms) or die($sql.mysql_error());
                                $companyid=mysql_insert_id($dbbms);
                        }
			if($ref_id)
			{	
				$saleid=explode("-",$ref_id);
				$sql = "UPDATE billing.REV_MASTER SET BMS_COMP_ID='$companyid' WHERE SALEID='$saleid[1]'";
				mysql_query($sql,$db205)  or die("$sql".mysql_error());
			}


			$campaign_det=addslashes(stripslashes($banner_details));
			// entering banner details into transaction table to get transaction_id 	
			$sql="INSERT INTO clientprofile.transaction(BannerQty,PopupQty,PopunderQty,AdvmailerQty,amount,REF_ID) VALUES ('$bannerqty','$popupqty','$popunderqty','$advmailerqty','$amount','$ref_id')";
			mysql_query($sql,$dbbms) or die($sql.mysql_error($dbbms));
			$transid=mysql_insert_id($dbbms);
			/*if($js_flag=='Y')
				$camp_status='pending';
			else*/
				$camp_status='new';
			// entering campaign details into table CAMPAIGN to get campaignid
			$sql="INSERT INTO bms2.CAMPAIGN(CompanyId,CampaignName,CampaignExecutiveId,TransactionId,CampaignEmail,CampaignImpressions,CampaignEntryDate,CampaignStartDt,CampaignEndDt,CampaignType,CampaignStatus,CampaignException,Showmis,Misoption,SITE,COMMENTS,REF_ID) VALUES ('$companyid','$campaignname','$saleby','$transid','$companyemail','$campaignimpression','$currentdate','$campaignstartdt','$campaignenddt','$campaigntype','$camp_status','N','$showmis','$misoptions','$sitename','$campaign_det','$ref_id')";
			mysql_query($sql,$dbbms) or die($sql.mysql_error());
			$campaignid=mysql_insert_id($dbbms);
			
			// update transaction table to reflect campaign name and campaign id
			$sql = "UPDATE clientprofile.transaction SET CampaignId ='$campaignid' , CampaignName='$campaignname' WHERE transaction_id = '$transid'";
			mysql_query($sql,$dbbms) or die($sql.mysql_error());

			// make entries in BANNER table corresponding to the number of banners , popup etc booked

			if ($popupqty)
			{
				for ($i = 0; $i < $popupqty; $i++)
				{
					$sql="INSERT INTO bms2.BANNER(BannerBookDate,BannerStartDate,BannerEndDate,CampaignId,BannerClass) VALUES ('$currentdate','$campaignstartdt','$campaignenddt','$campaignid','PopUp')";
					mysql_query($sql,$dbbms) or die($sql.mysql_error());
				}
			}
			
			if ($popunderqty)
			{
				for ($i = 0; $i < $popunderqty; $i++)
				{
				       $sql="INSERT INTO bms2.BANNER(BannerBookDate,BannerStartDate,BannerEndDate,CampaignId,BannerClass) VALUES ('$currentdate','$campaignstartdt','$campaignenddt','$campaignid','PopUnder')";
				       mysql_query($sql,$dbbms) or die($sql.mysql_error());
				}
			}
			if ($advmailerqty)
			{
				for ($i = 0; $i < $advmailerqty; $i++)
				{
				       $sql="INSERT INTO bms2.BANNER(BannerBookDate,BannerStartDate,BannerEndDate,CampaignId,BannerClass) VALUES ('$currentdate','$campaignstartdt','$campaignenddt','$campaignid','MailerImage')";
				       mysql_query($sql,$dbbms) or die($sql.mysql_error());
				} 
			}

			for ($i = 0; $i < $totqty; $i++)
			{	
				$sql="INSERT INTO bms2.BANNER(BannerBookDate,BannerStartDate,BannerEndDate,CampaignId) VALUES ('$currentdate','$campaignstartdt','$campaignenddt','$campaignid')";
				mysql_query($sql,$dbbms) or die($sql.mysql_error());
			}
			
			$sql_priv = "SELECT VALUE FROM bms2.PRIVILEGES WHERE VALUE ='client'";
                        $res_priv = mysql_query($sql_priv,$dbbms) or die(mysql_error());
                        $myrow=mysql_fetch_array($res_priv);
                        $PRIVILAGE= $myrow["VALUE"];
			$sql = "INSERT INTO bms2.USERS (USERNAME,PASSWORD,USER_PRIVILEGE,EMAIL, ACTIVE,SITE) VALUES ('$misuser','$password','$PRIVILAGE','$companyemail','Y','$sitename') ";
                        mysql_query($sql,$dbbms) or die("$sql".mysql_error());
                        $userid=mysql_insert_id($dbbms);

                        $sql = "INSERT INTO clientprofile.client_reg (user_id , user_name , pswd , company_id , last_access , email) VALUES ('$userid','$misuser','$password','$companyid',NOW(),'$companyemail')";
                        mysql_query($sql,$dbbms) or die("$sql".mysql_error());


			if($js_flag=='Y')
			{
				$chksum=md5($companyid)."i".$companyid;
				$message = "Your campaign has been booked by the name:-".$campaignname."<br>Username for mis is:- ".$misuser."  and Password is:- ".$password."<br><a href=\"http://www.jeevansathi.com/jsadmin/mainpage.php?cid=$id\">Continue</a>";
			}
			else
				$message = "Booking procedure completed.<br>Username for mis is:- ".$misuser."  and Password is:- ".$password."<br><a href=\"bms_adminindex.php?id=$id\">Continue</a>";
			$smarty->assign("cnfrmmsg",$message);
			$smarty->assign("id",$id);
			$smarty->assign("site",$site);
			$smarty->display("./$_TPLPATH/bms_confirmation.htm");
		}
		else // if there are errors in the form , form is re-displayed
		{	
			$misoption=create_dd($misdetails,"misopt");
			if ($campaigntype == "duration")
				$smarty->assign("campaignduration",$campaignduration);
			else
				$smarty->assign("campaignimpression",$campaignimpression);
			$smarty->assign("campaignname",$campaignname);
			$smarty->assign("companyname",$companyname);
			$smarty->assign("companyemail",$companyemail);
			$smarty->assign("startyear",$startyear);
			$smarty->assign("startmonth",$startmonth);
			$smarty->assign("startday",$startday);
			$smarty->assign("endyear",$endyear);
			$smarty->assign("endmonth",$endmonth);
			$smarty->assign("endday",$endday);
			$smarty->assign("campaigntype",$campaigntype);
			$smarty->assign("sitename",$sitename);
			$smarty->assign("bannerqty",$bannerqty);
			$smarty->assign("popupqty",$popupqty);
			$smarty->assign("popunderqty",$popunderqty);
			$smarty->assign("advmailerqty",$advmailerqtyty);
			$smarty->assign("amount",$amount);
			$smarty->assign("showmis",$showmis);
			$smarty->assign("mis",$misoption);
			$smarty->assign("saleby",$saleby);
			$smarty->assign("transactionid",$transactionid);
			$smarty->assign("reverturl",$reverturl);
			$smarty->assign("message",$message);
			$smarty->assign("site",$site);
			$smarty->assign("id",$id);
			$smarty->assign("banner_details",$banner_details);
			$smarty->assign("misuser",$misuser);
			$smarty->assign("password",$password);
			$smarty->display("./$_TPLPATH/bms_booking.htm");
		}
	}
	else
	{
		$smarty->assign("site",$site);
		$smarty->assign("id",$id);
		showForm();
	}
	if ($reset)  // form is re-displayed
	{
		$smarty->assign("site",$site);
		$smarty->assign("id",$id);
		showForm();
	}

}
else
	TimedOutBms();

?>
