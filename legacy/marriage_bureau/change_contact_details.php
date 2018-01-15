<?php

/************************************************************************************************************************
* 	FILE NAME	:	change_contact_details.php
* 	DESCRIPTION 	: 	Get details for a new Marriage Bureau account
* 	MODIFY DATE	: 	24th April 2006
* 	MODIFIED BY	: 	Nikhil Tandon
* 	REASON		: 	Marriage Bureau 			
* 	Copyright  2005, InfoEdge India Pvt. Ltd.
************************************************************************************************************************/

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it
include_once("connectmb.inc");
$smarty_flag = 'n';
$db=connect_dbmb();
$data=authenticatedmb($checksum);
if($data)
{
	if($Submit)
	{
		$error=0;
		$errortrue=1;
		$errorfalse=0;
		if($nameofbureau=="")
		{
			$error++;
			$smarty->assign("nameofbureau_error",$errortrue);
		}
		if($address=="")
		{
			$error++;
			$smarty->assign("address_error",$errortrue);
		}
		if($city=="")
		{
			$error++;
			$smarty->assign("city_error",$errortrue);
		}
		if($tel1=="")
		{
			$error++;
			$smarty->assign("tel1_error",$errortrue);
		}
		if($c_name=="")
		{
			$error++;
			$smarty->assign("c_name_error",$errortrue);
		}
		if($c_designation=="")
		{
			 $error++;
			 $smarty->assign("c_designation_error",$errortrue);        
		}
		if($c_tel=="" && $c_mob=="")
		{
			$error++;
			$smarty->assign("c_tel_error",$errortrue);
		}
		$smarty->assign("nameofbureau",$nameofbureau);
		$smarty->assign("address",$address);
		$smarty->assign("city",$city);
		$smarty->assign("state",$state);
		$smarty->assign("country",$country);
		$smarty->assign("pin",$pin);
		$smarty->assign("tel1",$tel1);
		$smarty->assign("tel2",$tel2);
		$smarty->assign("fax",$fax);
		$smarty->assign("email",$email);
		$smarty->assign("c_name",$c_name);
		$smarty->assign("c_designation",$c_designation);
		$smarty->assign("c_mob",$c_mob);
		$smarty->assign("c_tel",$c_tel);
		assign_template_pathprofile();
		$smarty->assign("source",$data['SOURCE']);
		$smarty->assign("mbchecksum",$data['CHECKSUM']);
		$HEAD=$smarty->fetch('top_band.htm');
		assign_template_pathmb();
		$smarty->assign('HEAD',$HEAD);
		$smarty->assign("editing_contact_details","1");
		if($error>0)
		{
			if($error<2)
			{
				$smarty->assign("worderror","error");
				$smarty->assign("worderror1","was");
			}
			else
			{
				$smarty->assign("worderror","errors");
				$smarty->assign("worderror1","were");
			}
			$smarty->assign("numberoferrors",$error);
			$smarty->assign("error",$errortrue);
			$smarty->display('inputprofile_1.htm');
		}
		else
		{
			$profileid=$data['PROFILEID'];
			$sql="UPDATE BUREAU_PROFILE SET NAME='$nameofbureau',ADDRESS='$address',CITY='$city',STATE='$state',COUNTRY='$country',PIN='$pin',TELEPHONE1='$tel1',TELEPHONE2='$tel2',FAX='$fax',EMAIL='$email',CONTACT_NAME='$c_name',CONTACT_DESIGNATION='$c_designation',CONTACT_PHONE='$c_tel',CONTACT_MOB='$c_mob' WHERE PROFILEID='$profileid'";
			$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate"); 
			$smarty->assign("formail","1");
			$smarty->assign("complete_edit_profile","1");
			$smarty->display('inputprofile_1.htm');
		}
	}
	else
	{
		$username=$data["USERNAME"];
		$sql="SELECT NAME,ADDRESS,CITY,STATE,COUNTRY,PIN,TELEPHONE1,TELEPHONE2,FAX,EMAIL,CONTACT_NAME,CONTACT_DESIGNATION,CONTACT_MOB,CONTACT_PHONE FROM marriage_bureau.BUREAU_PROFILE WHERE USERNAME='$username'";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row=mysql_fetch_array($res);
		
		$smarty->assign("nameofbureau",$row['NAME']);
		$smarty->assign("address",$row['ADDRESS']);
		$smarty->assign("city",$row['CITY']);
		$smarty->assign("state",$row['STATE']);
		$smarty->assign("country",$row['COUNTRY']);
		$smarty->assign("pin",$row['PIN']);
		$smarty->assign("tel1",$row['TELEPHONE1']);
		$smarty->assign("tel2",$row['TELEPHONE2']);
		$smarty->assign("fax",$row['FAX']);
		$smarty->assign("email",$row['EMAIL']);
		$smarty->assign("c_name",$row['CONTACT_NAME']);
		$smarty->assign("c_designation",$row['CONTACT_DESIGNATION']);
		$smarty->assign("c_mob",$row['CONTACT_MOB']);
		$smarty->assign("c_tel",$row['CONTACT_PHONE']);
		$errortrue=0;
		$smarty->assign("error",$errortrue);
		assign_template_pathprofile();
		$smarty->assign("source",$data['SOURCE']);
		$smarty->assign("mbchecksum",$data['CHECKSUM']);
		$HEAD=$smarty->fetch('top_band.htm');
		assign_template_pathmb();
		$smarty->assign('HEAD',$HEAD);
		$smarty->assign("editing_contact_details","1");
		$smarty->display('inputprofile_1.htm');
	}
}
else
{
	timeoutmb();
}
if($zipIt)
	ob_end_flush();
?>
