<?php
ini_set("max_execution_time","0");
chdir(dirname(__FILE__));
/************************************************************************************************************************
 *    FILENAME           : email_once_send.php
 *    INCLUDED           : connect.inc,contact.inc,payment_array.php
 *    DESCRIPTION        : Sends All Initial-contact Request To Reciever Through One Mail Only.When This Script is run
 All Recievers will recieve appropriate mail.
 *    MODIFIED           : on 16may to check if receiver has responded to initial contact before the mail is sent
 *    CREATED BY         : lavesh
 ***********************************************************************************************************************/

$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
chdir($_SERVER["DOCUMENT_ROOT"]."/profile");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
include($_SERVER['DOCUMENT_ROOT']."/profile/payment_array.php");
//include("ads.php");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

// Adding Global vars -- (pr)
global $noOfActiveServers,$slave_activeServers,$smarty,$_SERVER,$CITY_INDIA_DROP,$COUNTRY_DROP,$EDUCATION_LEVEL_NEW_DROP,$HEIGHT_DROP,$OCCUPATION_DROP,$CASTE_DROP,$MTONGUE_DROP,$CITY_USA_DROP,$INCOME_DROP,$RELIGIONS;


$smarty->assign("SITE_URL","http://www.jeevansathi.com");

$protect_obj=new protect;

$mysqlObj=new Mysql;

for($i=0;$i<$noOfActiveServers;$i++)
{
	$myDbName=$slave_activeServers[$i];
	$myDb_arr[$myDbName]=$mysqlObj->connect("$myDbName");
	mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$myDb_arr[$myDbName]);
}

$db=connect_db();
$db2=connect_737();

mysql_query("set session wait_timeout=1000",$db);
mysql_query("set session wait_timeout=1000",$db2);
$subject="Initial contact made";

$total_mail_sent=0;
//Select No. Of Receiver To Whom Mail Need To Send.
 $sql2="SELECT DISTINCT RECEIVER FROM newjs.CONTACTS_ONCE WHERE SENT='N' ";
$resi=mysql_query($sql2,$db2) or logError($sql2,$db2); 
while($rowi=mysql_fetch_array($resi))
	//Distinct Reciever enter into the loop.
{
	$i=0;$k=0;
	@mysql_ping($db2);
	unset($CNT);
	$recr=$rowi["RECEIVER"];
	//Added Here To check if receiver is replied to initial contact before sending him initial contact mail.
	//If he had replied to one sender then that sender will not be send in initial contact message.if he had replied to all sender than mail will not send to him.
	$sql="SELECT CONTACTID,SENDER FROM newjs.CONTACTS_ONCE WHERE RECEIVER='$recr' AND SENT='N'";
	$res=mysql_query($sql,$db2) or logError($sql,$db2);
	while($row=mysql_fetch_array($res))
	{
		$sen[$k]=$row['SENDER'];
		$conarr[$k]=$row['CONTACTID'];
		$k++;
	}
	if(is_array($sen))
	{
		$SEN=implode(",",$sen);
		//Sharding of CONTACTS done by Neha Verma
		$myDbName=getProfileDatabaseConnectionName($recr,'slave',$mysqlObj);
		@mysql_ping($myDb_arr[$myDbName]);
		$myDb=$myDb_arr[$myDbName];
		
		$yday=mktime(0,0,0,date("m"),date("d")-90,date("Y"));
                $back_90_days=date("Y-m-d",$yday);
                $time_clause="TIME>='$back_90_days 00:00:00'";
                $sql01="SELECT COUNT(SENDER) as CNT FROM newjs.CONTACTS WHERE RECEIVER='$recr' and TYPE='I' and FILTERED<>'Y' and  $time_clause ";
                $result=mysql_query($sql01,$myDb) or die(mysql_error($myDb));
                $total=mysql_fetch_array($result);
                $smarty->assign("total_eoi",$total['CNT']);
	
	
		$sql="SELECT SENDER,TYPE FROM newjs.CONTACTS WHERE SENDER IN ($SEN) AND RECEIVER='$recr'";
		//$result=$mysqlObj->executeQuery($sql,$myDb);
		$result=mysql_query($sql,$myDb) or die(mysql_error($myDb));
		$type = array();
		while($row= mysql_fetch_array($result))
		{
			if($row["TYPE"]!='I')
			{
				$type[$recr][$row["SENDER"]] = $row["TYPE"];
				$k=array_search($row['SENDER'],$sen);//$k is the index of sender in the array.
				$list_check[]=$conarr[$k];

			}
			$k++;
		}
		unset($sen);
		unset($conarr);
	}
	if($list_check)
	{
		$already_sent_contactid=implode(",",$list_check);

		@mysql_ping($db);
		$sqlflag="UPDATE CONTACTS_ONCE SET SENT='Y' WHERE CONTACTID in ($already_sent_contactid)";
		mysql_query($sqlflag,$db) or logError($sqlflag,$db);
		unset($already_sent_contactid);
		unset($list_check);
	}
	//Ends here
 	$sql1="SELECT CONTACTID,SENDER,MESSAGE FROM newjs.CONTACTS_ONCE WHERE RECEIVER='$recr' AND SENT='N' ORDER BY TIME desc";
	if($res1=mysql_query($sql1,$db))
	{
		$CNT=mysql_num_rows($res1);
		if($CNT)
		{
			$from="contacts@jeevansathi.com";

			$j=0;$i=0;

			/* Two if else Loop Will Be Executed Once For One Reciever.Set Condition based on No. of Senders are selected
			   for one Reciever. */
			$smarty->assign("count",$CNT);
			if($CNT==1)
			{
				$smarty->assign("numberofsender",'A');
				$smarty->assign("text",'Jeevansathi.com Member has');
				$oldrecords=1;
				$smarty->assign("old",$oldrecords);
			}
			else
			{
				$smarty->assign("numberofsender",$CNT);
				$smarty->assign("old",'');
				$smarty->assign("text","Jeevansathi.com's Members have");
				$subject = "$CNT jeevansathi members have contacted you today";
			}

			if($CNT>10)
				$smarty->assign("more_than_limit",'Y');
			else
				$smarty->assign("more_than_limit",'N');

			$sql="SELECT USERNAME,PASSWORD,AGE,EMAIL,GENDER,HEIGHT,CASTE,OCCUPATION,CITY_RES,HAVEPHOTO,YOURINFO,FAMILYINFO,SPOUSE,SCREENING,PRIVACY,PHOTO_DISPLAY,INCOME,EDU_LEVEL_NEW,SUBSCRIPTION,MTONGUE,SUBCASTE,GOTHRA,NAKSHATRA,FATHER_INFO,SIBLING_INFO,RELIGION,PHOTOSCREEN FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$recr'  AND ACTIVATED!='D'";
			$res2=mysql_query($sql,$db2) or logError($sql,$db2);
			$contactCount = mysql_num_rows($res2);
			if($contactCount)
			{
				$row2=mysql_fetch_array($res2);

				$sql="SELECT EXPIRY_DT,SERVICEID from billing.SERVICE_STATUS where PROFILEID='$recr' AND ACTIVE='Y' AND SERVEFOR LIKE '%F%' order by ID desc";
            $result_2=$mysqlObj->executeQuery($sql,$db) or $mysqlObj->logError("In email_once_send.php while selecting EMAIL,etc - ".$sql);
            $myrow_2=$mysqlObj->fetchArray($result_2);
            $renew_status=$myrow_2["EXPIRY_DT"];
            if($renew_status && !strstr($myrow_2['SERVICEID'],'L'))
	         {
    	        $curdate=date('Y-m-d');
              $days_left_expire= getTimeDiff1($curdate,$renew_status);
              if($days_left_expire <=15)
	           {
   	           list($yy,$mm,$dd)=explode('-',$renew_status);
                 $ts=mktime(0,0,0,$mm,$dd,$yy);
                 $exp_dt=date('jS M Y',$ts);
                 $smarty->assign('exp_dt',$exp_dt);
                 $smarty->assign("RECEIVER_IS_PAID",2);
              }
              else
              {
	              $smarty->assign("RECEIVER_IS_PAID",1);
              }
            }
            else
            	$smarty->assign("RECEIVER_IS_PAID",0);

				mysql_free_result($res2);
				$to=$row2['EMAIL'];
				$recname=$row2['USERNAME'];
				$GENDER=$row2['GENDER'];

				$COUNTRY=$row2['COUNTRY_RES'];
				$CITY=$row2['CITY_RES'];
				//$ values will not be displayed in templates if COUNTRY is India.
				if($row2['COUNTRY_RES']=='51')
					$smarty->assign("COUNTRY",'I');
		
				
				$checksum=md5($recr)."i".$recr;
				$smarty->assign("CHECKSUM",$checksum);
				$echecksum=$protect_obj->js_encrypt($checksum,$to);
				$smarty->assign("echecksum",$echecksum);

				while($row=mysql_fetch_array($res1))
				{
					if($type[$recr][$row["SENDER"]]){
						$error_eoi_message = "TYPE:".$type[$recr][$row["SENDER"]]."\nRECEIVER:".$recr."\nSENDER:".$row["SENDER"]."\nCONTACTID:".$row['CONTACTID']."\nMESSAGE:".$message."\n";
						mail("nitesh.s@jeevansathi.com","EOI mail after acceptance",$error_eoi_message);
					}
					unset($contact_id);
					$rec=$recr;
					$contact_id=$row['CONTACTID'];
					$message=$row['MESSAGE'];
					$rec_sender=$row['SENDER'];
					if($i<5)
						// Maximum Of 10 Senders will be Selected For One Reciever.
					{
						unset($sender);
						$list_contactid[$i]=$contact_id;
						//$GENDER=$REC_GENDER;
						$sender=$row['SENDER'];

						$sender_rights=get_rights($sender);
						$custum_message=$row['MESSAGE'];

						$sql="SELECT COUNTRY_RES,USERNAME,PASSWORD,AGE,HEIGHT,CASTE,OCCUPATION,CITY_RES,HAVEPHOTO,YOURINFO,FAMILYINFO,SPOUSE,SCREENING,PRIVACY,PHOTO_DISPLAY,INCOME,EDU_LEVEL_NEW,SUBSCRIPTION,MTONGUE,SUBCASTE,GOTHRA,NAKSHATRA,FATHER_INFO,SIBLING_INFO,RELIGION,PHOTOSCREEN FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$sender'";

						$res=mysql_query($sql,$db2) or logError($sql,$db2);
			tmpfile();			$row=mysql_fetch_array($res);
						$havephoto=$row['HAVEPHOTO'];
						$photo_display=$row['PHOTO_DISPLAY'];
						$newsenders[$i]["profilechecksum"]=md5($sender) . "i" . $sender;


						$newsenders[$i]["HAVEPHOTO"]=$row['HAVEPHOTO'];
						$newsenders[$i]["photo_display"]=$row['PHOTO_DISPLAY'];	
						$newsenders[$i]["privacy"]=$row['PRIVACY'];
						$is_album=0;
						if($havephoto=='Y')
						{
							//Symfony Photo Modification
							$is_album = SymfonyPictureFunctions::checkMorePhotos($sender);

							//if(($photo_display=='H')||($photo_display=='C'))
							if($photo_display=='H')
							{
								
								$newsenders[$i]["newsenders_HAVEPHOTO"]="H";
							}
							else
							{
								//Symfony Photo Modification
								$profilePicObjs = SymfonyPictureFunctions::getProfilePicObjs($sender);
								$profilePicObj = $profilePicObjs[$sender];
								if ($profilePicObj)
								{
									$newsenders[$i]["ThumbnailUrl"]=$profilePicObj->getSearchPicUrl();
									
								}
								else
								{
									$newsenders[$i]["ThumbnailUrl"]=null;
								}
								//Symfony Photo Modification

								//$photochecksum_new=intval(intval($sender)/1000) . "/" . md5($sender+5);
								$photochecksum = md5($sender+5)."i".($sender+5);
								$newsenders[$i]["PHOTOCHECKSUM"]=$photochecksum;
								$newsenders[$i]["newsenders_HAVEPHOTO"]="Y";
							}
						}
						else
							$is_album=0;
						$newsenders[$i]['album']=$is_album;
						$newsenders[$i]["album_link"] = urlencode($SITE_URL.'/profile/layer_photocheck.php?checksum=&profilechecksum='.$newsenders[$i]['profilechecksum'].'&seq=1');
						$newsenders[$i]["INCOME"]=$INCOME_DROP[$row['INCOME']];
						$newsenders[$i]["RELIGION"]=$RELIGIONS[$row['RELIGION']];
						$newsenders[$i]["SENDER"]=$sender;
						$newsenders[$i]["NAME"]=$row['USERNAME'];
						$newsenders[$i]["PWD"]=$row['PASSWORD'];
						
						//modified as 1st three words of yourinfo will be bold.
						$screening=$row["SCREENING"];
						if(isFlagSet("YOURINFO",$screening))
						{
							if(trim($row["YOURINFO"]))
							{
								$yourinfo1=trim($row["YOURINFO"]);
								$len=strlen($yourinfo1);
								$flag=0;
								for($k=0;$k<$len;$k++)
								{
									if($yourinfo1[$k]==' ')
									{
										$flag++;
									}
									if($flag<3)
									{
										$subyourinfo.=$yourinfo1[$k];
									}
									else
									{
										$yourinfo.=$yourinfo1[$k];
										$flag++;
									}
								}
							}
							//150 character limit is there.
							$newsenders[$i]["INFO"]=substr("<b>".$subyourinfo."</b>"." $yourinfo",0,150);
						}
						else
							$newsenders[$i]["INFO"]='';
						unset($subyourinfo);
						unset($yourinfo);
						//$newsenders[$i]["INFO"]=substr($row['YOURINFO'],0,150);
						$newsenders[$i]["AGE"]=$row['AGE'];
						$newsenders[$i]["GOTHRA"]=$row['GOTHRA'];
						$newsenders[$i]["NAKSHATRA"]=$row['NAKSHATRA'];
						$newsenders[$i]["CITY_RES"]=$row['CITY_RES'];
						$temp=label_select("HEIGHT",$row['HEIGHT']);
						if(is_array($temp))
							$newsendersHEIGHT2=implode(",",$temp);
						else
							$newsendersHEIGHT2=$temp;
						$newsendersHEIGHT=explode("(",$newsendersHEIGHT2);
						$newsenders[$i]["HEIGHT"]=$newsendersHEIGHT[0];
						unset($temp);
						
						$temp=label_select("CASTE",$row['CASTE']);
						$tempcaste = $temp[0]; 
						$pos = strpos($tempcaste, ":");
						if($pos==0)
						{
							$caste = $tempcaste;
						}
						else 
						{
							$pos = $pos+1;
							$castelen = strlen($tempcaste);
							$caste = substr($tempcaste,$pos,$castelen-$pos);
						}
						$newsenders[$i]["CASTE"]=$caste;
						unset($temp);

						$temp=label_select("MTONGUE",$row['MTONGUE']);
						$newsenders[$i]["MTONGUE"]=$temp[0];
						unset($temp);

						$temp=label_select("EDUCATION_LEVEL_NEW",$row['EDU_LEVEL_NEW']);
						$newsenders[$i]["EDUCATION"]=$temp[0];
						unset($temp);

						$newsenders[$i]["SUBCASTE"]=$row['SUBCASTE'];
						$temp=label_select("COUNTRY",$row['COUNTRY_RES']);
						$newsenders[$i]["COUNTRY"]=$temp[0];
						unset($temp);
						if(is_numeric($newsenders[$i]['CITY_RES']))
						{
							$newsenders[$i]['CITY_RES']=$CITY_USA_DROP[$newsenders[$i]['CITY_RES']];
						}
						else
						{
							$newsenders[$i]['CITY_RES']=$CITY_INDIA_DROP[$newsenders[$i]['CITY_RES']];
						}
	/*Unnecessay code
						if($row['COUNTRY_RES']=='51')
						{
							$temp=label_select("CITY_INDIA",$row['CITY_RES']);
						}
						else
						{
							$temp=label_select("CITY_USA",$row['CITY_RES']);
						}

						//$newsenders[$i]["CITY"]=$temp[0];
						unset($temp);
	*/
						$temp=label_select("OCCUPATION",$row['OCCUPATION']);
						$newsenders[$i]["OCCUPATION"]=$temp[0];
						unset($temp);


						// If City Record Not Found Then RESIDENCE Of Sender Is Indiacated By Its Country.
						if($newsenders[$i]["CITY_RES"]!="")
							$newsenders[$i]["RESIDENCE"]=$newsenders[$i]["CITY_RES"];
						else
							$newsenders[$i]["RESIDENCE"]=$newsenders[$i]["COUNTRY"];
					//		$newsenders[$i]["INCOME"]=$income_map[$row['INCOME']];I guess that this line is unnecessary-Ankit

						/*if(in_array("F",$rec_rights))
						  $smarty->assign("RECEIVER_IS_PAID","1"); 
						  else
						  $smarty->assign("RECEIVER_IS_PAID","0");*/

						$smarty->assign("GENDER",$GENDER);
						$smarty->assign("RECEIVERNAME",$recname);
						
						$delim="...";
						$length = 160;
						$len = strlen($custum_message);
   					if ($len > $length) {
       				preg_match('/(.{' . $length . '}.*?)\b/', $custum_message, $matches);
       				$custum_message =  rtrim($matches[1]) . $delim;
   					}
   					
						$newsenders[$i]["CUSTMESSAGE"]=trim($custum_message);

						/*if($custum_message && in_array("F",$sender_rights))
						  {
						  $newsenders[$i]["PAID"]="Y";
						  }*/
						//print_r($newsenders);
						if($CNT==1)
						{
							$subject=$row['AGE'].", ".str_replace("&quot;","\"",$newsenders[$i]["HEIGHT"]).", ".$newsenders[$i]["MTONGUE"].", ".$newsenders[$i]["CASTE"];
							if($newsenders[$i]["OCCUPATION"]!="")
								$subject.=", ".$newsenders[$i]["OCCUPATION"];
							$subject.=" has contacted you";
						}

						// condition is there as $sender is required if Only 1 record is fetched from CONTACTS_ONCE
						if(!$oldrecords)
							unset($sender);
					}
					else
					{
						$list_contactid[$i]=$contact_id;
					}

					$check_contact_recommendation[$rec_sender]=$contact_id;
					$cnt_sub=$rec_sender;
					$i++;
				}

	if(is_array($check_contact_recommendation))
				{
					$start=0;
					foreach($check_contact_recommendation as $key=>$val)
					{
						if($start==0)
						{
							$score_sen=$key;
							$rec_str=$val;
						}
						else
						{
							$score_sen.="','".$key;
							$rec_str.="','".$val;
						}
						$start++;

					}	

					//$rec_str=implode("','",$check_contact_recommendation);
					$sqlss="select CONTACTID,STATUS from MIS.CONTACT_CATEGORY where CONTACTID IN('$rec_str')";
					$resss=mysql_query($sqlss,$db) or logError($sqlss,$db);
					while($rowss=mysql_fetch_array($resss))
					{
						$CONTACT_CATEGORY[$rowss['CONTACTID']]=$rowss['STATUS'];
					}

					foreach($check_contact_recommendation as $key=>$val)
					{
						$CONTACT_STATUS[$key]=$CONTACT_CATEGORY[$val];
					}

					//updating subject line if only one contact received
					if($CNT==1)
					{

						if($CONTACT_STATUS[$cnt_sub]=='H')
							$subject="Highly Recommended-$subject";
						elseif($CONTACT_STATUS[$cnt_sub]=='R')
							$subject="Recommended-$subject";
					}
				}
				$statement = "SELECT CITY,LOCALITY,AGENT,MOBILE FROM newjs.CONTACT_MAILERS WHERE CITY ='".$CITY."'";
				$result = $mysqlObj->executeQuery($statement,$db) or $mysqlObj->logError($statement);
        		while($row = $mysqlObj->fetchArray($result))
				{
					$output["CITY"] = $row["CITY"];
					$output["LOCALITY"] = $row["LOCALITY"];
					$output["AGENT"] = $row["AGENT"];
					$output["MOBILE"] = $row["MOBILE"];
				}
				$smarty->assign("Agtadd",$output["LOCALITY"]);
				$smarty->assign("Agtname",$output["AGENT"]);
				$smarty->assign("Agtmob",$output["MOBILE"]);
				//$URI="http://192.168.2.220/bmsjs/bms_display_final.php?zonestr=18&data=".$recr."&subzone=1&isTextLink=Y";
				$URI="http://www.ieplads.com/bmsjs/bms_display_final.php?zonestr=108&data=".$recr."&subzone=1&isTextLink=Y";
				//$snooper->fetch($URI);
				//$textlink =  $snooper->results;
				$smarty->assign("textlink1",$textlink);

				$URI="http://www.ieplads.com/bmsjs/bms_display_final.php?zonestr=108&data=".$recr."&subzone=2&isTextLink=Y";
				//$snooper->fetch($URI);
				//$textlink =  $snooper->results;
				$smarty->assign("textlink2",$textlink);

				$smarty->assign("PAY_ERISHTA",$pay_erishta);
				$smarty->assign("PAY_ECLASSIFIED",$pay_eclassified);
				$smarty->assign("PAY_EVALUE",$pay_evalue);	
				$smarty->assign("newsenders",$newsenders);
				$smarty->assign("senderinfo",$senderinfo);
				$smarty->assign("CONTACT_STATUS",$CONTACT_STATUS);

				unset($senderinfo);
				unset($newsenders);
				//ads($CITY,$COUNTRY);
				
				$initial_contact_msg=$smarty->fetch("Contact_Alert_MultipleProfile.html");
				//echo "-------------------$initial_contact_msg-----------------";die;
				//$to="prachi.mittal@naukri.com,priyanka.rathee@gmail.com,test11qa@yahoo.com,test1qa@gmail.com,test1qa@hotmail.com,test1qa@rediffmail.com,test1qa@sify.com";
			//$to = "priyanka.rathee@gmail.com,spamcheck@naukri.com,krishnan.ramaswami@naukri.com";	
				$total_mail_sent++;
				//	send_email($to,$initial_contact_msg,$subject,$from);			

				send_email($to,$initial_contact_msg,$subject,$from,"","","","","","","1");
				if($list_contactid)
				{
					$sent_contactid=implode(",",$list_contactid);

					@mysql_ping($db);
					$sqlflag="UPDATE CONTACTS_ONCE SET SENT='Y' WHERE CONTACTID in ($sent_contactid)";
					mysql_query($sqlflag,$db) or logError($sqlflag,$db);
				}
			}
		}
	}
	else
		logError($sql1,$db2);

	if($check_contact_recommendation)
		unset($check_contact_recommendation);
	if($CONTACT_STATUS)
		unset($CONTACT_STATUS);
	if($CONTACT_CATEGORY)
		unset($CONTACT_CATEGORY);
	if($REM_TREND_CONTACTID)
		unset($REM_TREND_CONTACTID);

	$not_allow='';
	unset($list_contactid);
	unset($sent_contactid);
	$i++;
	$db=connect_db();
}


$sql="update MIS.CONTACT_MAILER_CNT set MAIL_SENT=MAIL_SENT+$total_mail_sent";
mysql_query($sql,$db)  or logError($sql,$db);

?>
