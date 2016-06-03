<?php

/*********************************************************************************************
* FILE NAME     : businesssathi_input.php
* DESCRIPTION   : Displays Business Sathi Registration page and also takes action if the form
*		: is submitted.
* INCLUDES      : connect.inc
* FUNCTIONS     : connect_db()          : To connect to the database server
*               : maStripVARS()         : To add/remove slashes
* CREATION DATE : 16 June, 2005
* CREATED BY  : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("connect.inc");
$db=connect_db();


$nameclr=$emailclr=$phoneclr=$sitenameclr=$urlclr=$payee_nameclr=$mail_nameclr=$companyclr=$addclr=$cityclr=$stateclr=$unameclr=$zipclr=$pwdclr=$repwdclr=$agreeclr="black";		

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

$iserror=0;
$smarty->assign("HEAD",$smarty->fetch("business_sathi/heads.htm"));
$smarty->assign("LEFT",$smarty->fetch("business_sathi/left.htm"));

if(!$submit)
{
	$smarty->display("business_sathi/businesssathi_input.htm");
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

	if($email=="" || checkemail($email)==1)
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

/*	
	if($has_site=='Y')
	{
*/
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
/*	}
	else
	{
		$sitename="";
		$url="";
		$desc="";
		$category="0";
	}
*/

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

	if($uname=="")
	{
		$iserror=$iserror+1;
		$unameclr="red";
		$smarty->assign("unameclr",$unameclr);
	}

	if($uname!="")
	{
		$sql_uname="SELECT * FROM affiliate.AFFILIATE_DET WHERE USERNAME='$uname'";
		$res_uname=mysql_query($sql_uname) or logError("Due to temporary problem your request could not be processed",$sql_uname);

		if(mysql_num_rows($res_uname)>0)
		{
			$iserror=$iserror+1;
			$userexist="<font size=1 color=\"red\">User Name is not available</font>";
			$smarty->assign("userexist",$userexist);
		}
	}

	if($pwd=="")
	{
		$iserror=$iserror+1;
		$pwdmsg="<font size=1 color=red>Field cannot be blank</font>";
		$smarty->assign("pwdmsg",$pwdmsg);
		$pwdclr="red";
		$smarty->assign("pwdclr",$pwdclr);
	}

	if($repwd=="")
	{
		$iserror=$iserror+1;
		$repwdmsg="<font color=red size=1>Field cannot be empty</font>";
		$smarty->assign("repwdmsg",$repwdmsg);
		$repwdclr="red";
		$smarty->assign("repwdclr",$repwdclr);
	}

	if($pwd!=$repwd)
	{
		$iserror=$iserror+1;
		$repwdmsg="<font color=red size=1>The values in Re-type password and Password fields are not same</font>";
		$repwdclr="red";
		$pwdclr="red";
		$smarty->assign("repwdmsg",$repwdmsg);
		$smarty->assign("repwdclr",$repwdclr);
		$smarty->assign("pwdclr",$pwdclr);
	}
		
	if($agree!="Y")
	{
		$iserror=$iserror+1;	
		$agreeclr="red";
                $smarty->assign("agreeclr",$agreeclr);
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
		$smarty->assign("uname",$uname);
		$smarty->assign("iserror",$iserror);
		$smarty->display("business_sathi/businesssathi_input.htm");
	}

	if($iserror==0)
	{
		$sql_insert="INSERT INTO affiliate.AFFILIATE_DET(USERNAME,PASSWORD,NAME,EMAIL,TELEPHONE,HAS_SITE,SITENAME,URL,SITE_DESCRPN,SITE_CATEGORY,PAYEE,PAYEE_NAME,MAIL_NAME,COMPANY,ADDRESS,CITY,STATE,COUNTRY,PINCODE,PAN,STATUS,REG_DATE) VALUES('$uname','$pwd','$name','$email','$phone','Y','$sitename','$url','$desc',$category,'$payee','$payee_name','$mail_name','$company','$address','$city','$state',$country,'$zip','$pan','N',NOW())";
		$res_insert=mysql_query($sql_insert) or logError("Due to temporary problem your request could not be processed",$sql_insert);
		$smarty->display("business_sathi/businesssathi_confirm.htm");
	}
}
?>
