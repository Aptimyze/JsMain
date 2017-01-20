<?php
/**************************************************************************************************************************
Filename     : offline_verify_user.php
Description  : Module to let offline operator to verify mobile and residence numbers of offline profiles [JS Offline Product]
	     : Verified status : phone numbers has been verified phone flag(PHONERES/PHONEMOB) is up 
	     : Invalid status  : Its treated in the unverified state the phone flag(PHONERES/PHONEMOB) is down  	
Created On   : 28 February 2008
Modified On  : 4 Aug 2010
***************************************************************************************************************************/

include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");
include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

$db = connect_db();
$name = getname($cid);

$message = 'OPS';


if(authenticated($cid))
{
	if($submitchange)
	{
		if(is_array($profiles))
		{
			$temp=explode("i",$cid);
			$CRMuserid=$temp[1];
			$opsPhoneReportObj = new jsadmin_OPS_PHONE_VERIFIED_LOG();

			$profileslist=implode("','",$profiles);
			$sql="SELECT J.SCREENING,J.PROFILEID,J.PHONE_MOB,J.PHONE_RES,J.STD,J.ISD,J.PHONE_WITH_STD,JC.ALT_MOBILE FROM newjs.JPROFILE J LEFT JOIN newjs.JPROFILE_CONTACT JC ON  J.PROFILEID=JC.PROFILEID WHERE J.PROFILEID IN('$profileslist')";//query to fetch numbers and their verification status
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_assoc($res))
			{	
				$profileObj = new Profile();
				$profileObj->getDetail($row["PROFILEID"],"PROFILEID","*");

				$rcheck		=$row["PROFILEID"]."rcheck";
				$mcheck		=$row["PROFILEID"]."mcheck";
				$acheck		=$row["PROFILEID"]."acheck";//trac 745
				$rcheck		=$_POST[$rcheck];
				$mcheck		=$_POST[$mcheck];
				$acheck		=$_POST[$acheck];//trac 745
				$screening	=$row["SCREENING"];

				$profileid      =$row['PROFILEID'];
				$phone_num_mob	=$row['PHONE_MOB'];
				$phone_num_res	=$row['STD']."-".$row['PHONE_RES'];
				$phone_num_alt	=$row['ALT_MOBILE'];
				$isd = $profileObj->getISD();
				$phoneStatus = '';
				if($rcheck=="valid")
				{
					// Residence phone number is marked in verified state
					$screening=setFlag("PHONERES",$screening);
					$phoneVerObj=new PhoneVerification($profileObj,'L');
					$phoneStatus = 'Y';
					if($phoneVerObj->isVerified()!='Y')
						$phoneVerObj->phoneUpdateProcess('OPS');	
				}
				elseif($rcheck=="invalid")
				{
					// Residence phone number is marked in Unverified state (when received  Invalid status by OPS)
					$actionStatus ='D';
					$phoneType ='L';
					$phoneStatus = 'N';
					phoneUpdateProcess($profileid,$phone_num_res,$phoneType,$actionStatus,$message,$name,$isd);
					UnverifyNum($profileid, $phoneType, $phone_num_res);
					//markInvalidProfile($row['PROFILEID'],'R');	
				}
				elseif($rcheck == 'noAction')
				{
					$phoneStatus = 'B';
				}

				if($rcheck && $phoneStatus) 
					$opsPhoneReportObj->insertOPSPhoneReport($name,$profileid,$row['ISD'].$row['PHONE_WITH_STD'],'L',$phoneStatus);

				if($mcheck=="valid")
                                {
					// Mobile number is marked in verified state	
                    $screening=setFlag("PHONEMOB",$screening);
					$phoneVerObj=new PhoneVerification($profileObj,'M');
					if($phoneVerObj->isVerified()!='Y')
						$phoneVerObj->phoneUpdateProcess('OPS');
					$phoneStatus = 'Y';

                                }
                elseif($mcheck=="invalid")
                                {
					// Mobile number is marked in Unverified state (when received  Invalid status by OPS)
					$actionStatus ='D';
					$phoneType ='M';
					phoneUpdateProcess($profileid,$phone_num_mob,$phoneType,$actionStatus,$message,$name,$isd);
					UnverifyNum($profileid, $phoneType, $phone_num_mob);
					$phoneStatus = 'N';
                                }
                 elseif($mcheck=='noAction')
					$phoneStatus = 'B';

//trac 745 start

                	if($mcheck && $phoneStatus) 
                		$opsPhoneReportObj->insertOPSPhoneReport($name,$profileid,$row['ISD'].$row['PHONE_MOB'],'M',$phoneStatus);
                

				if($acheck=="valid")
                     {
					// Alternate number is marked in verified state	
                        $screening=setFlag("ALTMOB",$screening);
						$phoneVerObj=new PhoneVerification($profileObj,'A');
						if($phoneVerObj->isVerified()!='Y')
							$phoneVerObj->phoneUpdateProcess('OPS');  
						$phoneStatus = 'Y';

					}
                     elseif($acheck=="invalid")
                     {
					// Alternate number is marked in Unverified state (when received  Invalid status by OPS)
					$actionStatus ='D';
					$phoneType ='A';
					phoneUpdateProcess($profileid,$phone_num_alt,$phoneType,$actionStatus,$message,$name,$isd);//updates status in JPROFILE_CONTACTS table in newjs
					$phoneStatus = 'N';

                      }//trac 745 ends
                     elseif($acheck=='noAction')
						$phoneStatus = 'B';
                	if($acheck && $phoneStatus) 
                		$opsPhoneReportObj->insertOPSPhoneReport($name,$profileid,$row['ISD'].$row['ALT_MOBILE'],'A',$phoneStatus);
	
				$jprofileUpdateObj = JProfileUpdateLib::getInstance(); 
				$profileid=$row[PROFILEID];
				$arrFields = array('SCREENING'=>$screening);
				$exrtaWhereCond = "";
				$jprofileUpdateObj->editJPROFILE($arrFields,$profileid,"PROFILEID",$exrtaWhereCond);
				//$sqlup="UPDATE newjs.JPROFILE SET SCREENING='$screening' WHERE PROFILEID='$row[PROFILEID]'";
				//mysql_query_decide($sqlup) or die("$sqlup".mysql_error_js());
				//echo "update query".$sqlup."<br>";
				unset($profileObj);

			}
			$smarty->assign("changesdone",1);
		}
	
	}
	if($submitlist)
	{
		$userlist=trim($userlist);
		if($userlist)
		{
		$userlistarr=explode(",",$userlist);
		$profileidlist='';
		$usernamelist='';
		if(is_array($userlistarr))
		{
			foreach($userlistarr as $profile)
			{
				$profile=trim($profile);
				if($profile)
				{
				if(is_numeric($profile))
				{
					if(valid_profileid($profile))
						$profileid[]=$profile;
					else
						$invalidpid[]=$profile;
				}
				else
				{
					if(valid_username($profile))
						$username[]=$profile;
					else
						$invalidname[]=$profile;
				}
				}
			}
			if(is_array($profileid))
			$profileidlist=implode("','",$profileid);
			if(is_array($username))
			$usernamelist=implode("','",$username);
			if($profileidlist && $usernamelist)
			{
				$sql="SELECT STD,PHONE_MOB,PHONE_RES,SCREENING,USERNAME,PROFILEID FROM newjs.JPROFILE WHERE USERNAME IN('$usernamelist') UNION SELECT STD,PHONE_MOB,PHONE_RES,SCREENING,USERNAME,PROFILEID,MOB_STATUS,LANDL_STATUS,PHONE_FLAG FROM newjs.JPROFILE WHERE PROFILEID IN('$profileidlist')";
			}
			elseif($profileidlist)
			{
				$sql="SELECT STD,PHONE_MOB,PHONE_RES,SCREENING,USERNAME,PROFILEID,MOB_STATUS,LANDL_STATUS,PHONE_FLAG FROM newjs.JPROFILE WHERE PROFILEID IN('$profileidlist')";
			}
			elseif($usernamelist)
			{
				$sql="SELECT STD,PHONE_MOB,PHONE_RES,SCREENING,USERNAME,PROFILEID,MOB_STATUS,LANDL_STATUS,PHONE_FLAG FROM newjs.JPROFILE WHERE USERNAME IN('$usernamelist')";
			}
			if($sql)
			{
				$pArray=array();//esha 745
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				if(mysql_num_rows($res))
				{
					while($row=mysql_fetch_assoc($res))
					{
						$phoneres=0;
						$phonemob=0;
						$mob_status =$row['MOB_STATUS'];
						$landl_status =$row['LANDL_STATUS'];
		
						$pid=$row["PROFILEID"];
						$pArray[]=$pid;//esha trac 745 array with profileid from profile list and username list both
						if($row["STD"]!='' && $row["PHONE_RES"]!='')
							$std_phoneres=$row["STD"]."-".$row["PHONE_RES"];
						else
							$std_phoneres=$row["PHONE_RES"];

						$table[$pid]=array("username"=>$row["USERNAME"],
								   "phoneres"=>$std_phoneres,
							   	   "phonemob"=>$row["PHONE_MOB"],
								   "mob_status"=>$row['MOB_STATUS'],
								   "landl_status"=>$row['LANDL_STATUS'],
								   "phone_flag"=>$row['PHONE_FLAG']);
					}
//trac 745 start
					if(is_array($pArray))
						$pidlist=implode("','",$pArray);
					if($pidlist)//list of profileids
					{
						$sqlAlt="SELECT ALT_MOBILE,ALT_MOB_STATUS,ALT_MOBILE_ISD,PROFILEID FROM newjs.JPROFILE_CONTACT WHERE PROFILEID IN ('$pidlist')";//scan for alternate numbers for entered profiles
						$resAlt=mysql_query_decide($sqlAlt) or die("$sqlAlt".mysql_error_js());
						while($rowAlt=mysql_fetch_assoc($resAlt))
						{
							$table[$rowAlt['PROFILEID']]["altmob"]=$rowAlt['ALT_MOBILE'];//update in table array to be passed to the template file
							$table[$rowAlt['PROFILEID']]["alt_mob_status"]=$rowAlt['ALT_MOB_STATUS'];
						}
					}
					if(is_array($profileid))
					{
						foreach($profileid as $key)
						{
							if(!array_key_exists($key,$table))
							$invalidpid[]=$key;
						}
					}
					if(is_array($username))
					{
						foreach($username as $key)
						{
							$flag=1;
							foreach($table as $k=>$v)
							{
								if($table[$k]["username"]==$key)
								$flag=0;
							}
							if($flag)
						$invalidname[]=$key;
					}	
					}
					$smarty->assign("table",$table);
					if(is_array($invalidpid))
					$smarty->assign("invalidpid",implode(",",$invalidpid));
					if(is_array($invalidname))
					$smarty->assign("invalidname",implode(",",$invalidname));
					mysql_free_result($res);
				}
				else
				{
					$smarty->assign("invalidlist",1);
				}
			}
			else                                
			{
                                        $smarty->assign("invalidlist",1);
                        }
		}
		}
		else
		{
			$smarty->assign("invalidlist",1);
		}
	}
	$smarty->assign("cid",$cid);
	$smarty->display("offline_verify_user.htm");
}
else
{
	$msg="Your session has been timed out  ";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

?>
