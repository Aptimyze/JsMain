<?php

/*********************************************************************************************
* FILE NAME     : businesssathi_edit.php
* DESCRIPTION   : Allows an Affiliate to edit his details
* INCLUDES	: connect.inc
* FUNCTIONS	: connect_db()		: To connect to the database server
*		: authenticated()	: To check if the user is authenticated or not
*		: maStripVARS()		: To add/remove slashes
*		: TimedOut()		: To take action if the user is not authenticated
* CREATION DATE : 1 July, 2005
* CREATED BY  	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("connect.inc");
$db=connect_db();


$nameclr=$emailclr=$phoneclr=$sitenameclr=$urlclr=$payee_nameclr=$mail_nameclr=$companyclr=$addclr=$cityclr=$stateclr=$unameclr=$zipclr=$pwdclr=$repwdclr="black";		


$sql_country="SELECT VALUE,LABEL FROM newjs.COUNTRY";
$res1=mysql_query($sql_country);
while($row1=mysql_fetch_array($res1))
{
	$ctry[]=array("VAL"=>$row1[0],"LAB"=>$row1[1]);
}
$smarty->assign("ctry",$ctry);

$sql_sitecat="SELECT VALUE,LABEL FROM affiliate.SITE_CATEGORY";
$res2=mysql_query($sql_sitecat) or logError("Due to temporary problem your request could not be processed",$sql_sitecat,"ShowErrTemplate");
while($row2=mysql_fetch_array($res2))
{
	$cat[]=array("VAL"=>$row2[0],"LAB"=>$row2[1]);
}
$smarty->assign("cat",$cat);

$data=authenticated($checksum);
$iserror=0;
$smarty->assign("CHECKSUM",$checksum);
$smarty->assign("HEAD",$smarty->fetch("business_sathi/heads.htm"));
$smarty->assign("LEFT",$smarty->fetch("business_sathi/left.htm"));

if(isset($data))
{
	$ID=$data["AFFILIATEID"];
	if(!$submit)
	{
		$sql="SELECT * FROM affiliate.AFFILIATE_DET WHERE AFFILIATEID='$ID'";
		$res=mysql_query($sql) or logError("Due to temporary problem your request could not be processed",$sql);
		$row=mysql_fetch_array($res);

		$smarty->assign("name",$row["NAME"]);
                $smarty->assign("email",$row["EMAIL"]);
                $smarty->assign("phone",$row["TELEPHONE"]);
                $smarty->assign("has_site",$row["HAS_SITE"]);
                $smarty->assign("sitename",$row["SITENAME"]);
                $smarty->assign("url",$row["URL"]);
                $smarty->assign("desc",$row["SITE_DESCRPN"]);
                $smarty->assign("category",$row["SITE_CATEGORY"]);
                $smarty->assign("payee",$row["PAYEE"]);
                $smarty->assign("payee_name",$row["PAYEE_NAME"]);
                $smarty->assign("mail_name",$row["MAIL_NAME"]);
                $smarty->assign("company",$row["COMPANY"]);
                $smarty->assign("address",$row["ADDRESS"]);
                $smarty->assign("city",$row["CITY"]);
                $smarty->assign("state",$row["STATE"]);
                $smarty->assign("country",$row["COUNTRY"]);
                $smarty->assign("zip",$row["PINCODE"]);
                $smarty->assign("pan",$row["PAN"]);
		$smarty->display("business_sathi/businesssathi_edit.htm");
	}
	else
	{
		maStripVARS("addslashes");

		if(ereg("[0-9]",$name)||$name=="")
		{
			$iserror=$iserror+1;
			$nameclr="red";	
			$smarty->assign("nameclr","$nameclr");
		}

		if($email=="")
		{
			$iserror=$iserror+1;
			$emailclr="red";
			$smarty->assign("emailclr",$emailclr);
		}

		if(ereg("[a-zA-Z]",$phone)||$phone=="")
		{
			$iserror=$iserror+1;
			$phoneclr="red";
			$smarty->assign("phoneclr",$phoneclr);
		}
	
		if($has_site=='Y')
		{
			if($sitename=="")
			{
				$iserror=$iserror+1;
				$sitenameclr="red";
				$smarty->assign("sitenameclr",$sitenameclr);
			}

			if($url=="")
			{
				$iserror=$iserror+1;
				$urlclr="red";	
				$smarty->assign("urlclr",$urlclr);
			}
		}
		else
		{
			$sitename="";
			$url="";
			$desc="";
			$category="0";
		}

		if(ereg("[0-9]",$payee_name)||$payee_name=="")
		{
			$iserror=$iserror+1;
			$payee_nameclr="red";
			$smarty->assign("payee_nameclr",$payee_nameclr);
		}

		if(ereg("[0-9]",$mail_name)||$mail_name=="")
		{
			$iserror=$iserror+1;
			$mail_nameclr="red";
			$smarty->assign("mail_nameclr",$mail_nameclr);
		}

		if($company=="")
		{
			$iserror=$iserror+1;
			$companyclr="red";
			$smarty->assign("companyclr",$companyclr);
		}

		if($address=="")
		{
			$iserror=$iserror+1;
			$addclr="red";
			$smarty->assign("addclr",$addclr);
		}

		if($city=="")
		{
			$iserror=$iserror+1;
			$cityclr="red";
			$smarty->assign("cityclr",$cityclr);
		}

		if($state=="")
		{
			$iserror=$iserror+1;
			$stateclr="red";
			$smarty->assign("stateclr",$stateclr);
		}

		if(ereg("[a-zA-Z]",$zip)||$zip=="")
		{
			$iserror=$iserror+1;
			$zipclr="red";
			$smarty->assign("zipclr",$zipclr);
		}
	
		if($iserror!=0)
		{
			maStripVARS("stripslashes");
			$smarty->assign("name",$name);
			$smarty->assign("email",$email);
			$smarty->assign("phone",$phone);
			$smarty->assign("has_site",$has_site);
			$smarty->assign("sitename",$sitename);
			$smarty->assign("url",$url);
			$smarty->assign("desc",$desc);
			$smarty->assign("category",$category);
			$smarty->assign("payee",$payee);
			$smarty->assign("payee_name",$payee_name);
			$smarty->assign("mail_name",$mail_name);
			$smarty->assign("company",$company);
			$smarty->assign("address",$address);
			$smarty->assign("city",$city);
			$smarty->assign("state",$state);
			$smarty->assign("country",$country);
			$smarty->assign("zip",$zip);
			$smarty->assign("pan",$pan);
			$smarty->assign("iserror",$iserror);
			$smarty->display("business_sathi/businesssathi_edit.htm");
		}

		if($iserror==0)
		{
			$sql_updt="UPDATE affiliate.AFFILIATE_DET SET NAME='$name',EMAIL='$email',TELEPHONE='$phone',HAS_SITE='$has_site',SITENAME='$sitename',URL='$url',SITE_DESCRPN='$desc',SITE_CATEGORY=$category,PAYEE='$payee',PAYEE_NAME='$payee_name',MAIL_NAME='$mail_name',COMPANY='$company',ADDRESS='$address',CITY='$city',STATE='$state',COUNTRY=$country,PINCODE='$zip',PAN='$pan' WHERE AFFILIATEID='$ID'";
			$res_updt=mysql_query($sql_updt) or logError("Due to temporary problem your request could not be processed",$sql_updt);
			$smarty->display("business_sathi/businesssathi_confirm.htm");
		}
	}
}
else
{
	TimedOut();
}
?>
