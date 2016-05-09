<?php

/*************************************************************************************************************************
* FILE NAME     : actionAds.php
* DESCRIPTION   : Performs action according to whether the Advertiser has been approved or disapproved
* INCLUDES	: connect.inc
* CREATION DATE : 3 September, 2005
* CREATED BY	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*************************************************************************************************************************/
include("connect.inc");
//$db=connect_db();
include("common_func_inc.php");

$data=authenticated($cid);
$smarty->assign("cid",$cid);

$sql_dd="SELECT LABEL,ID FROM wedding_classifieds.CATEGORY";
$res_dd=mysql_query_decide($sql_dd) or die(mysql_error_js()."<BR>".$sql_dd);
while($row=mysql_fetch_array($res_dd))
{
	$dd_data[]=array("ID"=>$row['ID'],
			 "LABEL"=>$row['LABEL']);
}
$smarty->assign("dd",$dd_data);

$sql_city="SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 ORDER BY SORTBY";
$res_city=mysql_query_decide($sql_city) or die(mysql_error_js()."<BR>".$sql_city);
while($row=mysql_fetch_array($res_city))
{
	$dd_city[]=array("VALUE"=>$row['VALUE'],
			 "LABEL"=>$row['LABEL']);
}
$smarty->assign("city",$dd_city);

if(isset($data))
{
	$iserror=0;

	if($submit)
	{
		maStripVARS("addslashes");
		$expiry=$yr."-".$mnth."-".$day;

		if($NAME=="")
		{
			$iserror=$iserror+1;
			$smarty->assign("NAME_ERR","1");
		}
		if($CONTACT_PERSON=="")
		{
                        $iserror++;
                        $smarty->assign("CPERSON_ERR","1");
                }
		if($ADDRESS=="")
                {
                        $iserror++;
                        $smarty->assign("ADDRESS_ERR","1");
                }
		if($PHONE=="")
                {
                        $iserror++;
                        $smarty->assign("PHONE_ERR","1");
                }
		if($EMAIL=="")
                {
                        $iserror++;
                        $smarty->assign("EMAIL_ERR","1");
                }

		if($iserror>0)
		{
			maStripVARS("stripslashes");
			$city=label_select("CITY_INDIA",$CITY);

			$det[]=array(   "ADV_ID"=>$ADV_ID,
                        	        "NAME"=>$NAME,
                                	"CONTACT_PERSON"=>$CONTACT_PERSON,
	                                "ADDRESS"=>$ADDRESS,
        	                        "PHONE"=>$PHONE,
                	                "EMAIL"=>$EMAIL,
                        	        "CITY"=>$city[0],
	                                "DESCPN"=>$DESCPN,
        	                        "LOGO"=>$LOGO,
                	                "PAID"=>$PAID,
                        	        "MICROSITE_URL"=>$MICROSITE_URL,
                                	"STATUS"=>$STATUS,
	                                "CATEGORY"=>get_wedding_category($CATEGORY));

			$smarty->assign("det",$det);
			$smarty->display("actionAds.htm");
		}
		else
		{
			if($SUBSCPN=='BA')
			{
				$paid='N';
			}
			else
			{
				$paid='Y';
			}

			if($act=='app')
			{	
				$sql_updt="UPDATE wedding_classifieds.LISTINGS SET NAME='$NAME',CONTACT_PERSON='$CONTACT_PERSON',ADDRESS='$ADDRESS',PHONE='$PHONE',EMAIL='$EMAIL',CITY='$CITY',DESCPN='$DESCPN',PAID='$paid',SUBSCRIPTION_TYPE='$SUBSCPN',LOGO='$LOGO',APPROVE_DT=now(),EXPIRY_DT='$expiry',CATEGORY='$CATEGORY',STATUS='A' WHERE ADV_ID='$ADV_ID'";
				mysql_query_decide($sql_updt) or die(mysql_error_js()."<BR>".$sql_updt);	
				$msg="The Advertiser has been Approved<br>  ";
				$msg .="<a href=\"mainAds.php?cid=$cid\">";
				$msg .="Go to Main Page </a>";
				$smarty->assign("MSG",$msg);
				$smarty->display("jsadmin_msg.tpl");
			}
			else if($act=="disap")
			{
				$sql_updt2="UPDATE wedding_classifieds.LISTINGS SET NAME='$NAME',CONTACT_PERSON='$CONTACT_PERSON',ADDRESS='$ADDRESS',PHONE='$PHONE',EMAIL='$EMAIL',CITY='$CITY',DESCPN='$DESCPN',PAID='$paid',SUBSCRIPTION_TYPE='$SUBSCPN',LOGO='$LOGO',CATEGORY='$CATEGORY',STATUS='D' WHERE ADV_ID='$ADV_ID'";
				mysql_query_decide($sql_updt2) or die(mysql_error_js()."<BR>".$sql_updt2);	
				$msg="The Advertiser has been disapproved<br>  ";
			        $msg .="<a href=\"mainAds.php?cid=$cid\">";
			        $msg .="Go to Main Page </a>";
			        $smarty->assign("MSG",$msg);
		        	$smarty->display("jsadmin_msg.tpl");
			}
		}
	}	
	else
	{
		$sql_aff="select * from wedding_classifieds.LISTINGS where ADV_ID=$ID";
		$res_aff=mysql_query_decide($sql_aff) or die(mysql_error_js()."<BR>".$sql_aff);
		$row=mysql_fetch_array($res_aff);

		$city=label_select("CITY_INDIA",$row['CITY']);
		$det[]=array(	"ADV_ID"=>$row['ADV_ID'],
				"NAME"=>$row['NAME'],
				"CONTACT_PERSON"=>$row['CONTACT_PERSON'],
				"ADDRESS"=>$row['ADDRESS'],
				"PHONE"=>$row['PHONE'],
				"EMAIL"=>$row['EMAIL'],
				"CITY"=>$city[0],
				"DESCPN"=>$row['DESCPN'],
				"LOGO"=>$row['LOGO'],
				"PAID"=>$row['PAID'],
				"SUBS"=>$row['SUBSCRIPTION_TYPE'],
				"MICROSITE_URL"=>$row['MICROSITE_URL'],
				"STATUS"=>$row['STATUS'],
				"CATEGORY"=>get_wedding_category($row['CATEGORY']));

		$smarty->assign("det",$det);
		$smarty->display("actionAds.htm");
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
