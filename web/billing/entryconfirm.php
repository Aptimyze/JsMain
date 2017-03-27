<?php

/***********************************************************************************************
* FILENAME : entryconfirm.php
* DESCRIPTION : This file makes entry in the billing tables PURCHASES, PAYMENT_DETAIL and 
*               SERVICE_STATUS for billing details entered.
* MODIFY DATE 	     : 25 May, 2005
* MODIFIED BY        : Kush Asthana
* REASON             : change expiry and activation date for service, if the payment comes                              through AIREX  and service is started before billing
* Copyright 2005, InfoEdge India Pvt Ltd
***********************************************************************************************/

include_once(JsConstants::$docRoot."/jsadmin/connect.inc");
include_once(JsConstants::$docRoot."/profile/pg/functions.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");

$ip=FetchClientIP();
if(authenticated($cid))
{

	if($Done)
	{
		if($addon_services)
		{
			$addon_services =  stripslashes($addon_services);
			$addon_services_str = str_replace("'","",$addon_services); 
//			$addon_services_ar = explode(",",stripslashes($addon_services));
		}
		$loginname=getuser($cid);
		if($walkin=="OFFLINE" || $walkin=="ARAMEX")
		{
			$center="HO";
			$email_walkin="";
		}
		else
		{
			$sql="SELECT EMAIL,CENTER from jsadmin.PSWRDS where USERNAME='$walkin'";
			$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			$myrow=mysql_fetch_array($result);
			$center=$myrow['CENTER'];
			$email_walkin=$myrow['EMAIL'];
		}
	
		$sql="SELECT PROFILEID,EMAIL from newjs.JPROFILE where USERNAME='$username'";
		$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
		$myrow=mysql_fetch_array($result);
		$profileid=$myrow['PROFILEID'];
		$email_jprofile=$myrow['EMAIL'];
		
		if($curtype == "0")		
			$type="RS";
		elseif($curtype== "1")
			$type= "DOL";
		if($bank=="Other")
		{
			$bankfeed=$obank;
			$obank="Y";
		}
		else
		{
			$bankfeed=$bank;
			$obank="N";
		}

		$sql = "Select c.RIGHTS as RIGHTS, c.DURATION as DURATION from billing.SERVICES a, billing.PACK_COMPONENTS b, billing.COMPONENTS c where a.PACKAGE = 'Y' AND a.ADDON = 'N' AND a.PACKID = b.PACKID AND b.COMPID = c.COMPID AND a.SERVICEID = '$service_selected'";
	
		$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
		while($myrow = mysql_fetch_array($result))
		{
			$duration= $myrow["DURATION"];
			$subscription_ar[] = $myrow["RIGHTS"];
		}

		if($addon_services)
		{
			$sql = "Select c.RIGHTS as RIGHTS from billing.SERVICES a, billing.COMPONENTS c where a.PACKAGE = 'N' AND a.ADDON = 'Y' AND a.COMPID = c.COMPID AND a.SERVICEID in ($addon_services)";
        	        $result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			while($myrow = mysql_fetch_array($result))
				$subscription_ar[] = $myrow["RIGHTS"];
		}
		if(is_array($subscription_ar))
			$subscription = implode(",",$subscription_ar);
		else
			$subscription = $subscription_ar;	
		/*$sql="UPDATE newjs.JPROFILE set SUBSCRIPTION='$subscription' where PROFILEID='$profileid'";
		mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());*/
                        $jprofileObj    =JProfileUpdateLib::getInstance();
                        $paramArr      	=array("SUBSCRIPTION"=>$subscription);
                        $jprofileObj->editJPROFILE($paramArr,$profileid,'PROFILEID');

		$tax_value = $TAX_RATE;
		 /*add puneet*/
		
		user_start_paying($profileid);
		
		/*add puneet*/
		$sql="INSERT into billing.PURCHASES (SERVICEID, PROFILEID, USERNAME, NAME, ADDRESS, GENDER, CITY, PIN, EMAIL, RPHONE, OPHONE, MPHONE, COMMENT, OVERSEAS, DISCOUNT, DISCOUNT_TYPE, DISCOUNT_REASON,WALKIN, CENTER, ENTRYBY, DUEAMOUNT, DUEDATE, ENTRY_DT, STATUS,SERVEFOR,ADDON_SERVICEID,TAX_RATE,DEPOSIT_DT,DEPOSIT_BRANCH,IPADD) values ('$service_selected','$profileid','$username','$custname','$address','$gender','$city','$pin','$email','$resphone','$offphone','$mobphone','$comment','$overseas','$discount','$discount_type','$reason','$walkin','$center','$loginname','$part_payment','$due_date',now(),'DONE','$subscription','$addon_services_str','$tax_value','$deposit_date','$deposit_branch','$ip')";
		mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
		
		$billid=mysql_insert_id_js();
		
		$sql="INSERT into billing.PAYMENT_DETAIL (PROFILEID, BILLID, MODE, TYPE, AMOUNT, CD_NUM, CD_DT, CD_CITY, BANK, OBANK, STATUS, ENTRY_DT, ENTRYBY,DEPOSIT_DT,DEPOSIT_BRANCH,IPADD) values ('$profileid','$billid','$mode','$type','$amount','$cdnum','$cd_date','$cd_city','$bankfeed','$obank','DONE',now(),'$loginname','$deposit_date','$deposit_branch','$ip')"; 	
		mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
		$receiptid= mysql_insert_id_js();	

		$sql="SELECT c.COMPID as COMPID, c.DURATION as DURATION from billing.SERVICES a, billing.PACK_COMPONENTS b, billing.COMPONENTS c  where a.PACKID = b.PACKID AND b.COMPID = c.COMPID AND a.SERVICEID = '$service_selected'";		
		$result_pkg=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
		while($myrow_pkg = mysql_fetch_array($result_pkg))
		{
			$dur=$myrow_pkg['DURATION'];
			$comp_ar[]=$myrow_pkg['COMPID'];
		}
		 if(is_array($comp_ar))
                        $components = implode(",",$comp_ar);
		 else
			$components = $comp_ar;	


		$services = $service_selected.",".$addon_services_str;
//Below code added to incorporate changes thrugh Airex Module
		if($source=="A")
		{
			$sql_crm="SELECT ENTRY_DT,STATUS from incentive.PAYMENT_COLLECT where PROFILEID='$profileid' ORDER BY ENTRY_DT DESC LIMIT 1";
			$result_crm=mysql_query_decide($sql_crm) or die("$sql_crm<br>".mysql_error_js());
			$myrow_crm=mysql_fetch_array($result_crm);
			if($myrow_crm['STATUS']=="S")
				list($activation_date,$temperoray)=explode(" ",$myrow_crm['ENTRY_DT']);
			else
				$activation_date=date('Y-m-d');
		}
		else
			$activation_date=date('Y-m-d');
//*****************//
			$sql="INSERT into billing.SERVICE_STATUS (BILLID,PROFILEID,SERVICEID, COMPID, ACTIVATED, ACTIVATED_ON, ACTIVATED_BY, EXPIRY_DT)values ('$billid','$profileid','$service_selected','$components','Y','$activation_date','$loginname',ADDDATE('$activation_date', INTERVAL $dur MONTH))";
		mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());

/*		 $sql="SELECT SERVICEID,PACKAGE,COMPID,PACKID,ADDON from billing.SERVICES where SERVICEID IN ('$service_selected')";
		if($addon_services)
			$sql .= " OR SERVICEID IN ($addon_services)";


		while($myrow=mysql_fetch_array($result))
		{
			if($myrow['PACKAGE'] == 'N' && $myrow['ADDON'] == 'Y')
			{
				$sql_addon="SELECT DURATION from billing.COMPONENTS where COMPID='$myrow[COMPID]'";
				$result_addon = mysql_query_decide($sql_addon) or die(mysql_error_js());
				$myrow_addon=mysql_fetch_array($result_addon);
				
				$sql="INSERT into billing.SERVICE_STATUS (BILLID, PROFILEID,SERVICEID, COMPID, ACTIVATED, ACTIVATED_ON, ACTIVATED_BY, EXPIRY_DT) values ('$billid','$profileid','$myrow[SERVICEID]','$myrow[COMPID]','Y',now(),'$loginname',ADDDATE(now(), INTERVAL $myrow_addon[DURATION] MONTH))";
				mysql_query_decide($sql) or die(mysql_error_js());
			}
			elseif($myrow['PACKAGE'] == 'Y' && $myrow['ADDON'] == 'N')	
			{
				$packid=$myrow['PACKID'];
				$sql_pkg="SELECT COMPID from billing.PACK_COMPONENTS where PACKID='$packid'";
				$result_pkg=mysql_query_decide($sql_pkg) or die(mysql_error_js());
				while($myrow1=mysql_fetch_array($result_pkg))
				{
					$sql_comp="SELECT DURATION from billing.COMPONENTS where COMPID='$myrow1[COMPID]'";
					$result_comp = mysql_query_decide($sql_comp) or die(mysql_error_js());
					$myrow_comp=mysql_fetch_array($result_comp);
					$sql="INSERT into billing.SERVICE_STATUS (BILLID,PROFILEID,SERVICEID, COMPID, ACTIVATED, ACTIVATED_ON, ACTIVATED_BY, EXPIRY_DT)values ('$billid','$profileid','$myrow[SERVICEID]','$myrow1[COMPID]','Y',now(),'$loginname',ADDDATE(now(), INTERVAL $myrow_comp[DURATION] MONTH))";
					mysql_query_decide($sql) or die(mysql_error_js());
				}
			}

		}

*/	
		if($source=="A")
		{
			$sql="UPDATE incentive.PAYMENT_COLLECT set BILLING='Y' where PROFILEID='$profileid'";
			mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
		}
		if(strstr($service_selected,'P'))	
			$subject = "Congrats!You are now an e-Rishta Member!";
		if(strstr($service_selected,'D'))	
			$subject = "Congrats! You are now an e-Classified Member!";
		if(strstr($service_selected,'C'))	
			$subject = "Congrats! You are now an e-Value Pack Member!";

		$msg = membership_mail($service_selected,$addon_services_str,$username,$type,$amount,$duration);

		$bill = printbill($receiptid,$billid);

		if($walkin=="OFFLINE" || $walkin=="ARAMEX")
		{
			$walkinemail="";
		}
		else
		{
			$sql="SELECT EMAIL from jsadmin.PSWRDS where USERNAME='$walkin'";
			$result=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
			$myrow=mysql_fetch_array($result);
			$walkinemail= $myrow['EMAIL'];

		}
                send_email("$email",$msg,$subject,'webmaster@jeevansathi.com',$walkinemail,"payments@jeevansathi.com",$bill);
		
		if(strstr($subscription,"H") || strstr($subscription,"K"))
	        {
        	        astro_mail($username,$subscription,$email);
	        }
/***********************************************code added for matri profile questionnaire********************************/
                                                                                                                             
                if(strstr($service_selected,"M") || strstr($addon_services_str,"M"))
                {
                        $service_name=getServiceName($service_selected);
                        matri_questionnaire_mail($username,$service_name,$email,$walkinemail);
                }
                                                                                                                             
/*********************************************code ends here**************************************************************/
		$msg = "Entries successfully done<br><br>";
		if($source=="A")
			$msg .= "<a href=\"../crm/billentry.php?user=$loginname&cid=$cid\">";	
		elseif($source=="I")
			$msg .= "<a href=\"../crm/mainpage.php?cid=$cid\">";	
		else
			$msg .= "<a href=\"../jsadmin/mainpage.php?user=$loginname&cid=$cid\">";
			//$msg .= "<a href=\"billingview.php?user=$loginname&cid=$cid\">";
		$msg .= "Continue &gt;&gt;</a>";
		$smarty->assign("name",$user);
		$smarty->assign("cid",$cid);
		$smarty->assign("MSG",$msg);
		$smarty->display("../jsadmin/jsadmin_msg.tpl");
	
	}	
/*	elseif($Edit)
	{
		$smarty->assign("CUSTNAME",$custname);
		$smarty->assign("GENDER",$gender);
/*
old comments
		$smarty->assign("CONTADD1",$contadd1);
		$smarty->assign("CONTADD2",$contadd2);
		$smarty->assign("CONTADD3",$contadd3);
*/
/* new comment by Aman for using back button function instead of this code
		$smarty->assign("ADDRESS",$address);
		$smarty->assign("OCITY",$ocity);
		$smarty->assign("PIN",$pin);
		$smarty->assign("EMAIL",$email);
		$smarty->assign("RPSTD",$rpstd);
		$smarty->assign("RPHONE",$rphone);
		$smarty->assign("OPSTD",$opstd);
		$smarty->assign("OPHONE",$ophone);
		$smarty->assign("MPHONE",$mphone);
		$smarty->assign("COMMENT",$comment);
		$smarty->assign("MODE",$mode);
		$smarty->assign("CURTYPE",$curtype);
		$smarty->assign("AMOUNT",$amount);
		$smarty->assign("DUE_DATE",$due_date);
                $smarty->assign("CDNUM",$cdnum);
		$smarty->assign("CD_DATE",$cd_date);
		$smarty->assign("CD_CITY",$cd_city);
		$smarty->assign("OVERSEAS",$overseas);
		$smarty->assign("SEPERATEDS",$separateds);
		$smarty->assign("OBANK",$obank);
		$smarty->assign("DISCOUNT",$discount);
		$smarty->assign("REASON",$reason);
		$smarty->assign("DISCOUNT_TYPE",$discount_type);
		$smarty->assign("USERNAME",$username);
		$smarty->assign("BANK",$bank);
		$smarty->assign("SOURCE",$source);
		$smarty->assign("CRM_ID",$crm_id);
		$smarty->assign("BOLD_LISTING_SELECTED",$bold_listing);

		$sql="SELECT NAME,SERVICEID, PRICE_RS, PRICE_DOL from billing.SERVICES where PACKAGE = 'Y' AND ID > 6";
                $result=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
                while($myrow=mysql_fetch_array($result))
                {
                       $services_list[] = array("NAME" =>$myrow["NAME"],
                                                "SERVICEID"=>$myrow["SERVICEID"],
                                                "PRICE_RS"=>$myrow["PRICE_RS"],
                                                "PRICE_DOL"=>$myrow["PRICE_DOL"]);

                }
                $smarty->assign("SERVICES_LIST",$services_list);
		
		$smarty->assign("SERVICE_SELECTED",$service_selected);	
 
		$City_India=$city;
		$Bank=$bank;
		$Walkin=$walkin;

		$smarty->assign("city_india",create_dd($city,"City_India"));
		$smarty->assign("bank",create_dd($bank,"Bank"));
		$smarty->assign("walkin",create_dd($walkin,"Walkin"));
		$smarty->assign("LOGINNAME",$loginname);
		$smarty->assign("CID",$cid);
		$smarty->display("entryfrm.htm");
	}	*/	
	elseif($Logout)
	{
		logout($cid);
		$smarty->display("index.htm");
	}
}
else
{
        $msg="Error: Incorrect userid or password";
        $smarty->assign("name",$adminname);
        $smarty->assign("cid",$cid);
        $smarty->assign("MSG",$msg);
        $smarty->display("billing_msg.tpl");
                                                                                                 
}
?>

