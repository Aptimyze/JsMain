<?php
if(!$_SERVER['DOCUMENT_ROOT'])
	$_SERVER['DOCUMENT_ROOT']=realpath(dirname(__FILE__)."/../..");
				include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
		//		$Min_Age=($row['AGE']>29)?$row['AGE']-2:(($row['AGE']>26)?$row['AGE']-1:(($row['AGE']>22)?$row['AGE']:21));
		//		$Max_Age=($row['AGE']>33)?$row['AGE']+15:(($row['AGE']==33)?47:(($row['AGE']==32)?44:(($row['AGE']==31)?42:$row['AGE']+10)));
				if($income)
				{
					$new_partner_income=get_income_sortby_new($income,'','F');
					$new_partner_income=explode(",",$new_partner_income);
					$new_partner_income=implode("','",$new_partner_income);
					$DPP['Income']="'$new_partner_income'";
				}

				$sql = "SELECT DISTINCT REL_CASTE FROM newjs.CASTE_COMMUNITY WHERE PARENT_CASTE = '$caste'";
				$res = mysql_query_decide($sql) or logError("error",$sql);

				if(mysql_num_rows($res)<1)
				{
					$def="'$religion'";
				}
				else
				{       $abc="";
					while($rowed = mysql_fetch_array($res))
					{
						$abc.=$rowed[REL_CASTE].",";
					}
					$abc=rtrim($abc,",");
					$sql1="SELECT DISTINCT PARENT FROM newjs.CASTE WHERE VALUE IN ($abc,$caste)";
					$res1 = mysql_query_decide($sql1) or logError("error",$sql1);
					$def="'";
					while($row1 = mysql_fetch_assoc($res1))
					{
						$def.=$row1[PARENT]."','";
					}
					$def=rtrim(rtrim($def,"'"),",");
				}
				$partner_religion_str=$def;					
				$jpartnerObj=new Jpartner;
				$mysqlObj=new Mysql;
				if(!$myDb)
				{
					$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
					$myDb=$mysqlObj->connect("$myDbName");
				}
				$jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);
				$jpartnerObj->setPROFILEID($profileid);
				if($row['MSTATUS']=='N')
				{
					$jpartnerObj->setPARTNER_MSTATUS("'N'");
					$age/=4;
				}
				$jpartnerObj->setPARTNER_INCOME($DPP['Income']);
				$jpartnerObj->setPARTNER_RELIGION($partner_religion_str);
				$jpartnerObj->updatePartnerDetails($myDb,$mysqlObj);
				$age_filter=$mstatus_filter=$religion_filter=$country_filter=$mtongue_filter=$caste_filter=$city_filter=$income_filter='N';	
				if($age<=8.00)
					$age_filter=$mstatus_filter=$religion_filter=$income_filter='Y';
				else if($age>10.00)
					$income_filter='Y';
				else
					$mstatus_filter=$income_filter='Y';
				$sql="INSERT ignore INTO newjs.FILTERS(PROFILEID,AGE,MSTATUS,RELIGION,COUNTRY_RES,MTONGUE,CASTE,CITY_RES,INCOME) VALUES ('$profileid', '$age_filter', '$mstatus_filter', '$religion_filter', '$country_filter','$mtongue_filter','$caste_filter','$city_filter','$income_filter')";
		                mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
