<?php
        include "connect.inc";
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
        $db1=connect_db();
        $db=connect_slave();
	mysql_query_decide("set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000",$db1);
	mysql_query_decide("set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000",$db);
        mysql_select_db_js('newjs');

        $MSTATUS=array("N" => "Never Married",
                        "M" => "Married",
                        "S" => "Awaiting Divorce",
                        "D" => "Divorced",
                        "O" => "Other",
                        "W" => "Widowed",
                        "A" =>"Annulled");
	
	$sql="SELECT EMAIL,PROFILEID FROM newjs.JPROFILE WHERE GENDER='F' AND AGE < 33 AND MSTATUS = 'N' AND ACTIVATED ='Y'";
        $result=mysql_query_decide($sql,$db) or die($sql);
	while($row=mysql_fetch_array($result))	
	{
		$arr[]=$row['PROFILEID'];
		$mailji["$row[PROFILEID]"]=$row['EMAIL'];
	}	

	$mysqlObj=new Mysql;
	$jpartnerObj=new Jpartner;
	$from='contactwatch@jeevansathi.com';
	$sub ="We've changed your filters";
	$msg1 ="Dear user,<br/><br/>
			In order to ensure that you get contacted by only the right set of people as defined by you, we have set filters on your profile. Setting filters would mean that only people who satisfy the below-mentioned criteria would be able to contact you. Contacts from other members will be delivered to your filtered folder. Please find below the criteria a user has to satisfy in order to contact you:<br/><br/>";
	$msg3="<br/>Should you wish to edit the settings, please <a href='$SITE_URL/P/revamp_filter.php?from_mail=1'>click here</a>.<br/><br/>Regards<br/>Jeevansathi Team";
	foreach($arr as $var)
	{
                $myDbName=getProfileDatabaseConnectionName($var,'',$mysqlObj);
                $myDb=$mysqlObj->connect("$myDbName");
		$jpartnerObj->setPartnerDetails($var,$myDb,$mysqlObj);
		$jpartnerObj->PartnerProfileExist;
		if($jpartnerObj->PartnerProfileExist=='Y')
		{
			$sql4= "SELECT FILTERID, AGE, MSTATUS, RELIGION,INCOME from newjs.FILTERS where PROFILEID= $var";
			$result4= mysql_query_decide($sql4,$db) or die($sql4);
			$myrow4=mysql_fetch_array($result4);
			if(mysql_num_rows($result4)>0)
			{
				$Filterid=$myrow4['FILTERID'];
				$age_flag=$myrow4['AGE'];
				$mstatus_flag=$myrow4['MSTATUS'];
				$religion_flag=$myrow4['RELIGION'];
				$income_flag=$myrow4['INCOME'];
			}
		 
			$check[0]=$mstatus=display_format($jpartnerObj->getPARTNER_MSTATUS());
			$check[1]=$lage=$jpartnerObj->getLAGE();
			if($lage)
				$hage=$jpartnerObj->getHAGE();
			$check[2]=$income=display_format($jpartnerObj->getPARTNER_INCOME());
			$check[3]=$religion = display_format($jpartnerObj->getPARTNER_RELIGION());
			$cnt=0;
			$ins1="PROFILEID";
			$ins2="'$var'";
			foreach($check as $key=>$soon)
			{
				if($soon[0]!=''&& $soon[0])
				{
					if($key==0&&($mstatus_flag!='Y'||!$Filterid))
					{
						$cnt++;
					       for($ll=0;$ll<count($mstatus);$ll++)
						{
							$PARTNER_MSTATUS[]=$MSTATUS[$mstatus[$ll]];
						}
						if(is_array($PARTNER_MSTATUS))
							$mstatusji= implode(", ",$PARTNER_MSTATUS);
						$sg3="Marital Status: $mstatusji<br/>";	
						$up="MSTATUS='Y'";
						$ins1.=",MSTATUS";
						$ins2.=",'Y'";
					}
					elseif($key==1&&($age_flag!='Y'||!$Filterid))
					{
						$cnt++;
						$ageji="$lage to $hage";
						$sg1="Age: Between $ageji<br/>";
						if($cnt>1)
							$up.=",";
						$up.="AGE='Y'";	
						$ins1.=",AGE";
						$ins2.=",'Y'";
					}
					elseif($key==2&&($income_flag!='Y'||!$Filterid))
					{
						$cnt++;
						$incomeji=get_partner_string_from_array($income,"INCOME");
						$sg4="Salary: $incomeji<br/>";
						if($cnt>1)
							$up.=",";
						$up.="INCOME='Y'";	
						$ins1.=",INCOME";
						$ins2.=",'Y'";
					}
					elseif($key==3&&($religion_flag!='Y'||!$Filterid))
					{
						$cnt++;
						$religionji=get_partner_string_from_array($religion,"RELIGION");			
						$sg2="Religion: $religionji<br/>";
						if($cnt>1)
							$up.=",";
						$up.="RELIGION='Y'";	
						$ins1.=",RELIGION";
						$ins2.=",'Y'";
					}
				}
			}
			if($cnt>0)
			{
				if($Filterid)
				{
					$sql="update newjs.FILTERS SET $up where FILTERID=$Filterid";
				}
				else
				{
					$sql="insert into newjs.FILTERS ($ins1) VALUES ($ins2)";
				}	
				mysql_query_decide($sql,$db1) or die($sql);
				$msg=$msg1.$sg1.$sg2.$sg3.$sg4.$msg3;
				send_email($mailji[$var],$msg,$sub,$from);
			}
			unset($Filterid);

			unset($ageji);
			unset($religionji);
			unset($incomeji);
			unset($mstatusji);
			unset($PARTNER_MSTATUS);
			unset($mstatus_flag);
			unset($religion_flag);
			unset($income_flag);
			unset($age_flag);
			unset($up);
			unset($ins1);
			unset($ins2);
			unset($mstatus);
			unset($religion);
			unset($lage);
			unset($income);
			unset($sg1);
			unset($sg2);
			unset($sg3);
			unset($sg4);
			unset($msg2);
			
		}
	}

	function display_format($str)
	{
		if($str)
		{
			$str=trim($str,"'");

			$arr=explode("','",$str);
			return $arr;
		}

	}
        function get_partner_string_from_array($arr,$tablename)
        {
                        $str=implode("','",$arr);
                        $sql="select SQL_CACHE distinct LABEL from $tablename where VALUE in ('$str')";
			$dropresult=mysql_query_decide($sql,$db) or die($sql);
                        while($droprow=mysql_fetch_array($dropresult))
                        {
                                $str1.=$droprow["LABEL"] . ", ";
                        }

                        mysql_free_result($dropresult);

                        return substr($str1,0,-2);
        }

?>

