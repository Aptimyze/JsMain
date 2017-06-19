<?php
/*********************************************************************************************
* FILE NAME     : delAds.php
* DESCRIPTION	: Deletes the Advertiser's records
* CREATION DATE : 3 September, 2005
* CREATED BY	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/


include("connect.inc");
//$db=connect_db();
include("common_func_inc.php");

$smarty->assign("cid",$cid);
$data=authenticated($cid);

$sql_cat="SELECT ID,LABEL FROM wedding_classifieds.CATEGORY";
$res_cat=mysql_query_decide($sql_cat) or die(mysql_error_js()."<BR>".$sql_cat);
while($row_cat=mysql_fetch_array($res_cat))
{
	$cat_dd[]=array("ID"=>$row_cat['ID'],
			 "LABEL"=>$row_cat['LABEL']);
}
$smarty->assign("cat",$cat_dd);

$sql_city="SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 ORDER BY SORTBY";
$res_city=mysql_query_decide($sql_city) or die(mysql_error_js()."<BR>".$sql_city);
while($row_city=mysql_fetch_array($res_city))
{
	$city_dd[]=array("VALUE"=>$row_city['VALUE'],
			 "LABEL"=>$row_city['LABEL']);
}
$smarty->assign("city",$city_dd);


if(isset($data))
{
	$iserror=0;

	if($submit)
	{
		maStripVARS("addslashes");
		if($NAME=="")
		{
			$iserror++;
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
		 	$smarty->assign("ADV_ID",$ADV_ID);
			$smarty->assign("NAME",$NAME);
			$smarty->assign("CONTACT_PERSON",$CONTACT_PERSON);
			$smarty->assign("ADDRESS",$ADDRESS);
			$smarty->assign("PHONE",$PHONE);
			$smarty->assign("EMAIL",$EMAIL);
			$smarty->assign("CITY",$CITY);
			$smarty->assign("DESCPN",$DESCPN);
			$smarty->assign("PAID",$PAID);
			$smarty->assign("CITY",$city[0]);
			$smarty->assign("EX_YEAR",$yr);
			$smarty->assign("EX_MONTH",$mnth);
			$smarty->assign("EX_DAY",$day);
			$smarty->assign("MICROSITE_URL",$MICROSITE_URL);
			$smarty->assign("STATUS",$STATUS);
			$smarty->assign("CATEGORY",$CATEGORY);
	                $smarty->assign("error",$iserror);			
			$smarty->display("modiAds.htm");
		}
		else
		{
			$expiry_date=$yr."-".$mnth."-".$day;

			if($SUBSCPN=='BA')
			{
				$paid='N';
			}
			else
			{
				$paid='Y';
			}
			
			if($logo_status=='N' && $_FILES['logo']['size']>0)
			{
				$fp      = fopen($_FILES['logo']['tmp_name'], 'r');
				$content = fread($fp, filesize($_FILES['logo']['tmp_name']));
				$content = addslashes($content);
				fclose($fp);

				$sql="UPDATE wedding_classifieds.LISTINGS SET NAME='$NAME',CONTACT_PERSON='$CONTACT_PERSON',ADDRESS='$ADDRESS',PHONE='$PHONE',EMAIL='$EMAIL',DESCPN='$DESCPN',PAID='$paid',SUBSCRIPTION_TYPE='$SUBSCPN',LOGO='$content',MICROSITE_URL='$MICROSITE_URL',EXPIRY_DT='$expiry_date'  WHERE ADV_ID='$ADV_ID'";
				mysql_query_decide($sql) or die(mysql_error_js()."<BR>".$sql);
			}
			else if($logo_status=='D')
			{
				$content="";

				$sql="UPDATE wedding_classifieds.LISTINGS SET NAME='$NAME',CONTACT_PERSON='$CONTACT_PERSON',ADDRESS='$ADDRESS',PHONE='$PHONE',EMAIL='$EMAIL',DESCPN='$DESCPN',PAID='$paid',SUBSCRIPTION_TYPE='$SUBSCPN',LOGO='$content',MICROSITE_URL='$MICROSITE_URL',EXPIRY_DT='$expiry_date'  WHERE ADV_ID='$ADV_ID'";
				mysql_query_decide($sql) or die(mysql_error_js()."<BR>".$sql);
			}
			else if($logo_status=='P')
			{
				$sql="UPDATE wedding_classifieds.LISTINGS SET NAME='$NAME',CONTACT_PERSON='$CONTACT_PERSON',ADDRESS='$ADDRESS',PHONE='$PHONE',EMAIL='$EMAIL',DESCPN='$DESCPN',PAID='$paid',SUBSCRIPTION_TYPE='$SUBSCPN',MICROSITE_URL='$MICROSITE_URL',EXPIRY_DT='$expiry_date'  WHERE ADV_ID='$ADV_ID'";
				mysql_query_decide($sql) or die(mysql_error_js()."<BR>".$sql);
			}
			

			/*$sql="UPDATE wedding_classifieds.LISTINGS SET NAME='$NAME',CONTACT_PERSON='$CONTACT_PERSON',ADDRESS='$ADDRESS',PHONE='$PHONE',EMAIL='$EMAIL',DESCPN='$DESCPN',PAID='$paid',SUBSCRIPTION_TYPE='$SUBSCPN',LOGO='$content',MICROSITE_URL='$MICROSITE_URL',EXPIRY_DT='$expiry_date'  WHERE ADV_ID='$ADV_ID'";
			mysql_query_decide($sql) or die(mysql_error_js()."<BR>".$sql);*/
			
			$msg="The Record has been updated<br>  ";
                        $msg .="<a href=\"mainAds.php?cid=$cid\">";
                        $msg .="Go to Main Page </a>";
                        $smarty->assign("MSG",$msg);
                        $smarty->display("jsadmin_msg.tpl");
		}
	}
	else
	{
		if($act=='D')
		{
			$sql_del="UPDATE wedding_classifieds.LISTINGS SET STATUS='X' WHERE ADV_ID=$ID";
			$res_del=mysql_query_decide($sql_del) or die(mysql_error_js()."<BR>".$sql_del);

			$msg="The Listing has been Deleted<br>  ";
                        $msg .="<a href=\"mainAds.php?cid=$cid\">";
                        $msg .="Go to Manage Listing Page </a>";
                        $smarty->assign("MSG",$msg);
                        $smarty->display("jsadmin_msg.tpl");
		}
		else
		{
			$sql_get_data="SELECT * FROM wedding_classifieds.LISTINGS WHERE ADV_ID=$ID ORDER BY ADV_ID DESC";
			$res_get_data=mysql_query_decide($sql_get_data) or die(mysql_error_js()."<BR>".$sql_get_data);
		
			if((mysql_num_rows($res_get_data))>0)
			{
				$row_data=mysql_fetch_array($res_get_data);
				$ex_date=explode('-',$row_data['EXPIRY_DT']);
				$city=label_select("CITY_INDIA",$row_data['CITY']);
				$smarty->assign("ADV_ID",$row_data["ADV_ID"]);
				$smarty->assign("NAME",$row_data["NAME"]);
				$smarty->assign("CONTACT_PERSON",$row_data["CONTACT_PERSON"]);
				$smarty->assign("ADDRESS",$row_data["ADDRESS"]);
				$smarty->assign("PHONE",$row_data["PHONE"]);
				$smarty->assign("EMAIL",$row_data["EMAIL"]);
				$smarty->assign("CITY",$city[0]);
				$smarty->assign("DESCPN",$row_data["DESCPN"]);
				$smarty->assign("PAID",$row_data["PAID"]);
				$smarty->assign("SUBS",$row_data["SUBSCRIPTION_TYPE"]);
				$smarty->assign("EX_YEAR",$ex_date[0]);
				$smarty->assign("EX_MONTH",$ex_date[1]);
				$smarty->assign("EX_DAY",$ex_date[2]);
				$smarty->assign("LOGO",$row_data["LOGO"]);
				$smarty->assign("MICROSITE_URL",$row_data["MICROSITE_URL"]);
				$smarty->assign("STATUS",$row_data["STATUS"]);
				$smarty->assign("CATEGORY",get_wedding_category($row_data["CATEGORY"]));
			}

			$smarty->display("modiAds.htm");
		}
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
