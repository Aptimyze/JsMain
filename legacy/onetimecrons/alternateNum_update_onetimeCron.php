<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
$path=$_SERVER['DOCUMENT_ROOT'];
require_once("../connect.inc");
require_once("$path/profile/connect_reg.inc");

$db		=connect_slave();
$db_master	=connect_db();

//$cmd 		="ifconfig eth0 |grep \"inet addr\" |awk '{print $2}' |awk -F: '{print $2}'";
$ipAddress	='12.12.12.12';
$dupFieldType	='EDIT';
$dupFieldChk	='33554432';		

$databaseArr =array("sugarcrm","sugarcrm_housekeeping");
foreach($databaseArr as $key=>$val)
{
	if($val=='sugarcrm'){
		$tb_leads 	="$val".".leads";
		$tb_leads_cstm 	="$val".".leads_cstm";
	}	
	else{
	        $tb_leads       ="$val".".connected_leads";
	        $tb_leads_cstm  ="$val".".connected_leads_cstm";
	}

	$sql ="select l_c.gender_c,l_c.enquirer_mobile_no_c,l_c.jsprofileid_c,l_c.posted_by_c from $tb_leads_cstm l_c,$tb_leads l where l.id=l_c.id_c AND l_c.enquirer_mobile_no_c !='' AND l_c.jsprofileid_c !='' AND l.status in('24','26')"; 
	$res=mysql_query_decide($sql,$db);
	while($row=mysql_fetch_assoc($res))
	{
		$ignore =false;
		$username 	=$row['jsprofileid_c'];
		$mobile_no	=$row['enquirer_mobile_no_c'];
		$posted_by	=$row['posted_by_c'];
		$gender		=$row['gender_c'];
		$mobile_no	=redo_mobile_no($mobile_no);
		if($mobile_no){
			if(!is_numeric($mobile_no))
				$mobile_no ='';
			if(strlen($mobile_no)>10)	
				$mobile_no =substr($mobile_no,-10);
		}

		if($mobile_no){
			$sqlJ= "select PROFILEID,ISD,PHONE_MOB,PHONE_WITH_STD,HAVE_JCONTACT,SHOWPHONE_MOB,SHOWPHONE_RES from newjs.JPROFILE where USERNAME ='$username'";		
			$resJ=mysql_query_decide($sqlJ,$db);
			if($rowJ=mysql_fetch_assoc($resJ))
			{
		
				$profileid	=$rowJ['PROFILEID'];
				$phone_mob	=$rowJ['PHONE_MOB'];
				$phone_res	=$rowJ['PHONE_WITH_STD'];
				$haveJcontact	=$rowJ['HAVE_JCONTACT'];
				$showPhoneMob	=$rowJ['SHOWPHONE_MOB'];
				$showPhoneRes	=$rowJ['SHOWPHONE_RES'];
				$isd		=$rowJ['ISD'];

				if(strlen($mobile_no)<10 && $isd==91)
					$ignore =true;

		                //$phone_mob      =checkPhoneNum($phone_mob);
		                //$phone_res      =checkPhoneNum($phone_res);
		
				if($mobile_no==$phone_mob || $mobile_no==$phone_res)
					$ignore =true;

				if(!$ignore)
				{			
					if($showPhoneMob=='Y' && $showPhoneRes=='Y')
						$showAltMob ='Y';
					else
						$showAltMob ='N';
		
					// posted by
					if($posted_by==1){
						if($gender=='M')
							$numberOwner ='2';
					else if($gender=='F')
							$numberOwner ='1';
					}
					else if($posted_by==2)
						$numberOwner ='3';
					else if($posted_by==3 || $posted_by==5)
						$numberOwner ='6';
					else if($posted_by==4)
						$numberOwner ='7';
					else
						$numberOwner='7';
		
					if($haveJcontact=='Y'){
						$sqlContact ="update newjs.JPROFILE_CONTACT set ALT_MOBILE='$mobile_no',SHOWALT_MOBILE='$showAltMob',ALT_MOBILE_NUMBER_OWNER='$numberOwner',ALT_MOBILE_OWNER_NAME='-' where PROFILEID='$profileid'";
						mysql_query_decide($sqlContact,$db_master);	
					}
					else{
						$sqlUpdate ="update newjs.JPROFILE SET HAVE_JCONTACT='Y' where PROFILEID='$profileid'";
						mysql_query_decide($sqlUpdate,$db_master);

						$sqlIns ="insert ignore into newjs.JPROFILE_CONTACT(`PROFILEID`,`ALT_MOBILE`,`SHOWALT_MOBILE`,`ALT_MOBILE_NUMBER_OWNER`,`ALT_MOBILE_OWNER_NAME`) VALUES('$profileid','$mobile_no','$showAltMob','$numberOwner','-')";
						mysql_query_decide($sqlIns,$db_master);	
					}
					// Duplicate field handling
					$sqlDup ="insert ignore into duplicates.DUPLICATE_CHECKS_FIELDS(`PROFILEID`,`TYPE`,`FIELDS_TO_BE_CHECKED`) VALUES('$profileid','$dupFieldType','$dupFieldChk')";
					mysql_query_decide($sqlDup,$db_master);

					// Edit log entry
					$sqlLog ="insert into newjs.EDIT_LOG(`PROFILEID`,`IPADD`,`ALT_MOBILE`,`SHOWALT_MOBILE`,`ALT_MOBILE_OWNER_NAME`,`ALT_MOBILE_NUMBER_OWNER`,`SHOWPHONE_MOB`,`SHOWPHONE_RES`,`MOD_DT`) VALUES('$profileid','$ipAddress','$mobile_no','$showAltMob','-','$numberOwner','$showPhoneMob','$showPhoneRes',NOW())";
					mysql_query_decide($sqlLog,$db_master);
				}
			}	
		}
	}
}
