<?php
/*
chdir("/var/www/svnlive/branches/release12/profile");
include("../P/connect.inc");
include("../P/dropdowns.php");
include("../P/arrays.php");
*/
include("connect.inc");
include("dropdowns.php");
include("arrays.php");
$mysqlObj=new Mysql;

$dbM=connect_db();
$mysqlObj->executeQuery("set session wait_timeout=10000",$dbM);

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId);
        $myDb[$myDbName]=$mysqlObj->connect("$myDbName","slave");
}
$from='info@jeevansathi.com';
$subject="We've changed your filters on Jeevansathi.com";

$head="Dear user, <br>In order to ensure that you get contacted by only the right set of people as defined by you, we have set filters on your profile. Setting filters would mean that only people who satisfy the below-mentioned criteria would be able to contact you. Contacts from other members will be delivered to your filtered folder. Please find below the criteria a user has to satisfy in order to contact you:<br><br>";

for($ii=0;$ii<2;$ii++)
{
	if($ii==0)
		$sql = "SELECT PROFILEID FROM newjs.SEARCH_FEMALE WHERE RELIGION='1' AND MTONGUE IN ('27','13')";
	else
		$sql = "SELECT PROFILEID FROM newjs.SEARCH_MALE WHERE RELIGION='1' AND MTONGUE IN ('27','13')";
	$res = mysql_query($sql,$dbM) or die(mysql_error($dbM));

	$rel=1;

	while($row=mysql_fetch_array($res))
	{
		$p_id=$row['PROFILEID'];
		
		$myDbName=getProfileDatabaseConnectionName($p_id);

		$sql1 = "SELECT PARTNER_RELIGION,LAGE,HAGE,PARTNER_MSTATUS,PARTNER_INCOME,PARTNER_CASTE,PARTNER_COUNTRYRES,PARTNER_CITYRES,PARTNER_MTONGUE FROM newjs.JPARTNER WHERE PROFILEID='$p_id' AND PARTNER_RELIGION = \"'1','4'\""; 
		$res1 = mysql_query($sql1,$myDb[$myDbName]) or die(mysql_error($myDb[$myDbName]));
		if($row1=mysql_fetch_array($res1))
		{
			$s="'1','4','9'";

			$sql3="UPDATE newjs.JPARTNER SET PARTNER_RELIGION=\"$s\" WHERE PROFILEID='$p_id'";
			$res3 = mysql_query($sql3,$myDb[$myDbName]) or die(mysql_error($myDb[$myDbName]));

			$sql4 = "INSERT IGNORE INTO newjs.UPDATED_PROFILES(PROFILEID, UPDATED_RELIGION, PREVIOUS_RELIGION) VALUES('$p_id',\"$s\", '$rel')";
			$res4 = mysql_query($sql4,$dbM) or die(mysql_error($dbM));

			$sql6 = "SELECT AGE,MSTATUS,RELIGION,COUNTRY_RES ,MTONGUE,CASTE,CITY_RES,INCOME from newjs.FILTERS where PROFILEID= '$p_id'";
			$res6 = mysql_query($sql6,$dbM) or die(mysql_error($dbM));
			$row6=mysql_fetch_array($res6);

			if ($row6 && in_array("Y", $row6))
			{
				$sql5 = "SELECT EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$p_id'";
				$res5 = mysql_query($sql5,$dbM) or die(mysql_error($dbM));		
				$row5=mysql_fetch_array($res5);
				$EMAIL=$row5['EMAIL'];
			
				unset($msg);
				if($row6[0]=='Y')
				{
					$lage=$row1[1];
					$hage=$row1[2];
					$msg.="Age: Between $lage and $hage <br>";
				}

				if($row6[1]=='Y')
				{	
					$part_mstatus=$row1[3];
					if($part_mstatus)
					{
						unset($mstatus);
						unset($sARR);
						$sARR=explode(",",$part_mstatus);
						foreach($sARR as $v)
						{
							$v=trim($v,"'");
							$mstatus.=$MSTATUS[$v];
							$mstatus.=', ';
						}
						$msg.="Marital Status: $mstatus<br>";
					}
				}

				if($row6[2]=='Y')
				{
					if($s)
					{
						unset($part_rel);
						unset($sARR);
						$sARR=explode(",",$s);
						foreach($sARR as $v)
						{
							$v=trim($v,"'");
							$part_rel.=$RELIGIONS[$v];
							$part_rel.=', ';
						}
					}
						$msg.="Religion: $part_rel<br>";
				}
				if($row6[3]=='Y')
				{
					$part_country=$row1[6];
					if($part_country)
					{
						unset($country_residence);
						unset($sARR);
						$sARR=explode(",",$part_country);
						foreach($sARR as $v)
						{
							$v=trim($v,"'");
							$country_residence.=$COUNTRY_DROP[$v];
							$country_residence.=', ';
						}
					}
					$msg.="Country of Residence:$country_residence<br>"; 
				}
				if($row6[4]=='Y')
				{
					$part_mtongue=$row1[8];
					if($part_mtongue)
					{
						unset($mother_tongue);
						unset($sARR);
						$sARR=explode(",",$part_mtongue);
						foreach($sARR as $v)
						{
						       $v=trim($v,"'");
						       $mother_tongue.=$MTONGUE_DROP[$v];
						       $mother_tongue.=', ';
						}
					}
					$msg.="Mother Tongue:$mother_tongue<br>"; 
				}
				if($row6[5]=='Y')
				{
					$part_caste=$row1[5];
					if($part_caste)
					{
						unset($partner_caste);
						unset($sARR);
						$sARR=explode(",",$part_caste);
						foreach($sARR as $v)
						{
							$v=trim($v,"'");
							$partner_caste.=$CASTE_DROP[$v];
							$partner_caste.=', ';
						}
					}
					$msg.="Caste:$partner_caste<br>"; 
				}
				if($row6[6]=='Y')
				{
					$part_city=$row1[7];
					if($part_city)
					{
						unset($city_res);
						unset($sARR);
						$sARR=explode(",",$part_city);
						foreach($sARR as $v)
						{
							$v=trim($v,"'");
							$city_res.=$CITY_DROP[$v];  //check
							$city_res.=', ';
						}
					}
					$msg.="City of Residence:$city_res<br>"; 
				}
				if($row6[7]=='Y')
				{
					$part_inc=$row1[4];
					if($part_inc)
					{
						unset($sARR);
						unset($income);
						$sARR=explode(",",$part_inc);
						foreach($sARR as $v)
						{
							$v=trim($v,"'");
							$income.=$INCOME_DROP[$v];
							$income.=', ';
						}
					}
					$msg.="Salary: $income<br>";
				}
				$tail="<br>Should you wish to edit the settings, please <a href='$SITE_URL/P/revamp_filter.php?from_mail=1'>click here</a>.<br> <br>Regards <br>Jeevansathi Team<br>";
				$msg=$head.$msg.$tail;
				$sent=send_email($EMAIL,$msg,$subject,$from);
			}
		}
	}
}
/*
for($ii=0;$ii<2;$ii++)
{
	if($ii==0)
		$sql = "SELECT PROFILEID,RELIGION,MTONGUE FROM newjs.SEARCH_FEMALE";
	else
		$sql = "SELECT PROFILEID,RELIGION,MTONGUE FROM newjs.SEARCH_MALE";
	$res = mysql_query($sql,$dbM) or die(mysql_error($dbM));
	while($row=mysql_fetch_array($res))
	{
		$p_id=$row['PROFILEID'];

		$rel=$row['RELIGION'];
		$mtongue=$row['MTONGUE'];

		$myDbName=getProfileDatabaseConnectionName($p_id);

		$sql1 = "SELECT PARTNER_RELIGION,LAGE,HAGE,PARTNER_MSTATUS,PARTNER_INCOME,PARTNER_CASTE,PARTNER_COUNTRYRES,PARTNER_CITYRES,PARTNER_MTONGUE FROM newjs.JPARTNER WHERE PROFILEID=$p_id AND PARTNER_RELIGION = '\'$rel\''"; 

		$res1 = mysql_query($sql1,$myDb[$myDbName]) or die(mysql_error($myDb[$myDbName]));

		if($row1=mysql_fetch_array($res1))
		{

			$s=' ';

			if($rel==1) //Hindu
			{
				if($mtongue==27 || $mtongue==13)
					$s="'1','4'";
				elseif($mtongue==20 || $mtongue==34)
					$s="'1','7'";
				else
				{
					$mt="'".$mtongue."'";

					$north_west="'7','10','14','28','30','33','12','19','16'";

					if(strstr($north_west,"$mt"))
						$s="'1','9'";
					//else
						//$s="'".$rel."'";
				}
			}
			elseif($rel==2)
				$s="'2','5'"; //muslim
			elseif($rel==3)
				$s="'3','6'"; //christian
			elseif($rel==4)
				$s="'1','4'"; //sikh
			elseif($rel==5)
				$s="'1','2','5','6'";//parsi
			elseif($rel==6)
				$s="'1','3','6'"; //jewish
			elseif($rel==7)
				$s="'1','7'";//buddhist
			elseif($rel==9)
				$s="'1','9'"; //jain
			elseif($rel==8)
				$s=""; //others                       


			if($s!=' ' || $rel==8)
			{
				$sql3="UPDATE newjs.JPARTNER SET PARTNER_RELIGION=\"$s\" WHERE PROFILEID=$p_id";
				$res3 = mysql_query($sql3,$myDb[$myDbName]) or die(mysql_error($myDb[$myDbName]));

				$sql4 = "INSERT IGNORE INTO newjs.UPDATED_PROFILES(PROFILEID, UPDATED_RELIGION, PREVIOUS_RELIGION) VALUES('$p_id',\"$s\", '$rel')";
				$res4 = mysql_query($sql4,$dbM) or die(mysql_error($dbM));

				$sql6 = "SELECT AGE,MSTATUS,RELIGION,COUNTRY_RES ,MTONGUE,CASTE,CITY_RES,INCOME from newjs.FILTERS where PROFILEID= $p_id";
				$res6 = mysql_query($sql6,$dbM) or die(mysql_error($dbM));
				$row6=mysql_fetch_array($res6);

				if ($row6 && in_array("Y", $row6))
				{
					$sql5 = "SELECT EMAIL FROM newjs.JPROFILE WHERE PROFILEID=$p_id";
					$res5 = mysql_query($sql5,$dbM) or die(mysql_error($dbM));		
					$row5=mysql_fetch_array($res5);
					$EMAIL=$row5['EMAIL'];
				
					unset($msg);
					if($row6[0]=='Y')
					{
						$lage=$row1[1];
						$hage=$row1[2];
						$msg.="Age: Between $lage and $hage <br>";
					}

					if($row6[1]=='Y')
					{	
						$part_mstatus=$row1[3];
						if($part_mstatus)
						{
							unset($mstatus);
							unset($sARR);
							$sARR=explode(",",$part_mstatus);
							foreach($sARR as $v)
							{
								$v=trim($v,"'");
								$mstatus.=$MSTATUS[$v];
								$mstatus.=', ';
							}
							$msg.="Marital Status: $mstatus<br>";
						}
					}

					if($row6[2]=='Y')
					{
						if($s)
						{
							unset($part_rel);
							unset($sARR);
							$sARR=explode(",",$s);
							foreach($sARR as $v)
							{
								$v=trim($v,"'");
								$part_rel.=$RELIGIONS[$v];
								$part_rel.=', ';
							}
						}
							$msg.="Religion: $part_rel<br>";
					}
					if($row6[3]=='Y')
					{
						$part_country=$row1[6];
						if($part_country)
						{
							unset($country_residence);
							unset($sARR);
							$sARR=explode(",",$part_country);
							foreach($sARR as $v)
							{
								$v=trim($v,"'");
								$country_residence.=$COUNTRY_DROP[$v];
								$country_residence.=', ';
							}
						}
						$msg.="Country of Residence:$country_residence<br>"; 
					}
					if($row6[4]=='Y')
					{
						$part_mtongue=$row1[8];
						if($part_mtongue)
						{
							unset($mother_tongue);
							unset($sARR);
							$sARR=explode(",",$part_mtongue);
							foreach($sARR as $v)
							{
							       $v=trim($v,"'");
							       $mother_tongue.=$MTONGUE_DROP[$v];
							       $mother_tongue.=', ';
							}
						}
						$msg.="Mother Tongue:$mother_tongue<br>"; 
					}
					if($row6[5]=='Y')
					{
						$part_caste=$row1[5];
						if($part_caste)
						{
							unset($partner_caste);
							unset($sARR);
							$sARR=explode(",",$part_caste);
							foreach($sARR as $v)
							{
								$v=trim($v,"'");
								$partner_caste.=$CASTE_DROP[$v];
								$partner_caste.=', ';
							}
						}
						$msg.="Caste:$partner_caste<br>"; 
					}
					if($row6[6]=='Y')
					{
						$part_city=$row1[7];
						if($part_city)
						{
							unset($city_res);
							unset($sARR);
							$sARR=explode(",",$part_city);
							foreach($sARR as $v)
							{
								$v=trim($v,"'");
								$city_res.=$CITY_DROP[$v];  //check
								$city_res.=', ';
							}
						}
						$msg.="City of Residence:$city_res<br>"; 
					}
					if($row6[7]=='Y')
					{
						$part_inc=$row1[4];
						if($part_inc)
						{
							unset($sARR);
							unset($income);
							$sARR=explode(",",$part_inc);
							foreach($sARR as $v)
							{
								$v=trim($v,"'");
								$income.=$INCOME_DROP[$v];
								$income.=', ';
							}
						}
						$msg.="Salary: $income<br>";
					}
                                        $tail="<br>Should you wish to edit the settings, please <a href='$SITE_URL/P/revamp_filter.php?from_mail=1'>click here</a>.<br> <br>Regards <br>Jeevansathi Team<br>";
					$msg=$head.$msg.$tail;
					$sent=send_email($EMAIL,$msg,$subject,$from);
				}
			}
		}
	}
}
*/
?>
