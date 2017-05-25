<?php

/************************************************************************************************************************
*    FILENAME           : maillyn.php 
*    DESCRIPTION        : Contain function mailyn, which sends mail to subscribers that do not responds to other user 
			  request.  
*    CREATED BY         : lavesh
***********************************************************************************************************************/

include_once(JsConstants::$docRoot."/classes/authentication.class.php");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/profile/dropdowns.php");

$protect_obj=new protect;

function mailyn($mailer_id)
{
	global $db,$income_map;
	global $smarty;
	global $slave_activeServers;
        global $noOfActiveServers;
        global $mysqlObj,$protect_obj;
	global $SITE_URL;

        if(!$mysqlObj)
                $mysqlObj=new Mysql;

        for($serverId=0;$serverId<$noOfActiveServers;$serverId++)
        {
              $myDbName=$slave_activeServers[$serverId];
              $myDb=$mysqlObj->connect("$myDbName");
              $slaveDbArray[$myDbName]=$myDb;
              mysql_query('set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000',$slaveDbArray[$myDbName]);
              unset($myDb);
              unset($myDbName);
        }

	@mysql_ping();


	$smarty->assign("PAY_ERISHTA",$pay_erishta);
	$smarty->assign("PAY_ECLASSIFIED",$pay_eclassified);
	$smarty->assign("PAY_EVALUE",$pay_evalue);

	$curdate = date("Y-m-d");
	list($yy,$mm,$dd)=explode("-",$curdate);
	$today_timestamp=mktime(0,0,0,$mm,$dd,$yy);

	//Added by Neha
        $fifteen_next_timestamp=$today_timestamp+(15*24*60*60);
        $fifteen_next_date=date("Y-m-d",$fifteen_next_timestamp);
        //end
	
	//-- SUBJECT to be fetched in Round Robin Fashion--
	/*$sql2="SELECT * FROM mmmjs.MAIL_SUBJECT WHERE COUNT<2 ORDER BY ID LIMIT 1";
	$res=mysql_query($sql2,$db) or logerror1("maileryn - 1",$sql2);
	if (mysql_num_rows ($res) )
	{
	        $my_row=mysql_fetch_array($res);
		$subject =$my_row['SUBJECT'];
		if($subject=="These boys would love to hear from you")
                        $check_subject=1;
		$id1 =$my_row['ID'];
		$count_s= $my_row['COUNT'];
		$sql_up="UPDATE mmmjs.MAIL_SUBJECT SET COUNT=COUNT+1 WHERE ID=$id1";
		$res_up=mysql_query($sql_up,$db) or logerror1("maileryn - 1",$sql_up);	
		if($count_s==1)
		{
			if($id1=='7')
				$id1=1;
			else
				$id1++;
			$sql_up="UPDATE mmmjs.MAIL_SUBJECT SET COUNT=0 WHERE ID=$id1";     
                	$res_up=mysql_query($sql_up,$db) or logerror1("maileryn - 1",$sql_up);
		}
	}*/

	$sql="SELECT ID,RECEIVER,USER1,USER2,USER3,USER4,USER5,USER6,USER7,USER8,COUNTS,DATE FROM mmmjs.MAILERYN WHERE SENT=''";
	$result=mysql_query($sql,$db) or logerror1("came1 of mailac",$sql);
	while($myrow=mysql_fetch_array($result))
	{
		$id=$myrow['ID'];
 		$receiver=$myrow['RECEIVER'];
		//Added by Neha Verma for mailer revamp
                $dt=$myrow['DATE'];
                list($y1,$m1,$d1)=explode("-",$dt);
                $data_timestamp=mktime(0,0,0,$m1,$d1,$y1);
                $data_date=date("jS M Y",$data_timestamp);
                $smarty->assign("data_date",$data_date);
                //End
		$sql1="SELECT USERNAME,EMAIL,PASSWORD,ENTRY_DT,GENDER,SUBSCRIPTION,COUNTRY_RES FROM newjs.JPROFILE WHERE PROFILEID='$receiver'";
		$result1=mysql_query($sql1,$db) or logerror1("came3 of mailac",$sql1);
		$myrow1= mysql_fetch_array($result1);
		$USERNAME=$myrow1['USERNAME'];
		$ENTRY_DT=$myrow1['ENTRY_DT'];
		$GENDER=$myrow1['GENDER'];

		/*if($check_subject)
                {
                        if($GENDER=='M')
                                $subject ="These girls would love to hear from you";
                        else
                                $subject ="These boys would love to hear from you";
                }*/

		if($myrow1['COUNTRY_RES']=='51')
			$smarty->assign("COUNTRY",'I');

		$smarty->assign("RECEIVER",$USERNAME);

		if(strstr($myrow1["SUBSCRIPTION"],"F"))
                {
                        $sql_paid= "SELECT EXPIRY_DT FROM billing.SERVICE_STATUS WHERE PROFILEID='$receiver' ORDER BY EXPIRY_DT desc limit 1";
                        $res_paid=mysql_query($sql_paid,$db) or logerror1("came5 of mailac",$sql_paid);
                        $row_paid=mysql_fetch_assoc($res_paid);
                        $expiry_dt=$row_paid['EXPIRY_DT'];
                        if($fifteen_next_date<=$expiry_dt)
                        {
                                $smarty->assign("no_tariff_table",1);
                        }
                        else
                                $smarty->assign("no_tariff_table",0);
                        $smarty->assign("RECEIVER_IS_PAID","1");
                }
                else
                        $smarty->assign("no_tariff_table",0);

		$smarty->assign("PASSWORD",$myrow1['PASSWORD']);
		$smarty->assign("SITE_URL",$SITE_URL);
		$email=$myrow1['EMAIL'];

		// For Auto Login -Priyanka
		$checksum=md5($receiver)."i".$receiver;
                $smarty->assign("CHECKSUM",$checksum);
                $echecksum=$protect_obj->js_encrypt($checksum,$email);
                $smarty->assign("echecksum",$echecksum);
	
		unset($user);
		if($myrow['USER1'])
			$user[]=$myrow['USER1'];
		if($myrow['USER2'])
                        $user[]=$myrow['USER2'];
		if($myrow['USER3'])
                        $user[]=$myrow['USER3'];
		if($myrow['USER4'])
                        $user[]=$myrow['USER4'];
		if($myrow['USER5'])
                        $user[]=$myrow['USER5'];
		if($myrow['USER6'])
                        $user[]=$myrow['USER6'];
                if($myrow['USER7'])
                        $user[]=$myrow['USER7'];
                if($myrow['USER8'])
                        $user[]=$myrow['USER8'];
		
		$counts=$myrow['COUNTS'];
		$smarty->assign("COUNTS",$counts);
		$count=count($user);
		for($i=0;$i<$count;$i++)
		{
			$USER=$user[$i];

                        $sql1="SELECT SOURCE,USERNAME,HEIGHT,YOURINFO,MTONGUE,CASTE,AGE,OCCUPATION,CITY_RES,COUNTRY_RES,HAVEPHOTO,PHOTO_DISPLAY,GOTHRA,NAKSHATRA,EDU_LEVEL_NEW,INCOME,SUBCASTE,MSTATUS,MANGLIK,RELIGION FROM  newjs.JPROFILE  WHERE PROFILEID='$USER'";
                        $result1=mysql_query($sql1,$db) or logerror1("came41 of mailac",$sql1);
                        $myrow1= mysql_fetch_array($result1);
                        $username=$myrow1['USERNAME'];
                        $havephoto=$myrow1['HAVEPHOTO'];
                        $photo_display=$myrow1['PHOTO_DISPLAY'];
			$age=$myrow1['AGE'];
			$GOTHRA=$myrow1['GOTHRA'];
                        $NAKSHATRA=$myrow1['NAKSHATRA'];

			if($NAKSHATRA=="Don't Know")
				$NAKSHATRA="";

			$SUBCASTE=$myrow1['SUBCASTE'];
			$source=$myrow1['SOURCE'];
			$RELIGION=$myrow1['RELIGION'];

                        //Added by Neha Verma
                        $thirty_next_timestamp=$today_timestamp-(30*24*60*60);
                        $thirty_next_date=date("Y-m-d",$fifteen_next_timestamp);

			unset($temp);
			$temp=label_select_mmmjs("HEIGHT",$myrow1['HEIGHT']);
			if($temp)
                        	$newsendersHEIGHT2=implode(",",$temp);
                        $newsendersHEIGHT=explode(" (",$newsendersHEIGHT2);
                        $HEIGHT=$newsendersHEIGHT[0];
                        unset($temp);

                        $temp=label_select_mmmjs("MTONGUE",$myrow1['MTONGUE']);     
	                $MTONGUE=$temp[0];
			unset($temp);

			$temp=label_select_mmmjs("CASTE",$myrow1['CASTE']);
                        $CASTE=$temp[0];
                        unset($temp);

			$temp=label_select_mmmjs("OCCUPATION",$myrow1['OCCUPATION']);
                        $OCCUPATION=$temp[0];
                        unset($temp);
			
			$temp=label_select_mmmjs("EDUCATION_LEVEL_NEW",$myrow1['EDU_LEVEL_NEW']);
                        $EDUCATION=$temp[0];
                        unset($temp);	

			$temp=label_select_mmmjs("COUNTRY",$myrow1['COUNTRY_RES']);
                        $COUNTRY=$temp[0];
                        unset($temp);

			$temp=label_select_mmmjs("RELIGION",$myrow1['RELIGION']);
                        $RELIGION=$temp[0];
                        unset($temp);

                        $temp=label_select_mmmjs("CITY_NEW",$myrow1['CITY_RES']);
                        $CITY=$temp[0];
                        unset($temp);

			if(!$CITY=="")
                                $RESIDENCE=$CITY;
                        else
                                $RESIDENCE=$COUNTRY;
			unset($CITY);

			$temp=label_select_mmmjs("OCCUPATION",$myrow1['OCCUPATION']);
                        $OCCUPATION=$temp[0];
                        unset($temp);
			
			$DATA[$i]["username"]=$username;
			$DATA[$i]["profileid"]=$USER;
			$DATA[$i]["age"]=$age;
			$DATA[$i]["HEIGHT"]=$HEIGHT;
			$DATA[$i]["MTONGUE"]=$MTONGUE;
			$DATA[$i]["CASTE"]=$CASTE;
			$DATA[$i]["EDUCATION"]=$EDUCATION;
			$DATA[$i]["RESIDENCE"]=$RESIDENCE;
			$DATA[$i]["OCCUPATION"]=$OCCUPATION;
			$DATA[$i]["GOTHRA"]=$GOTHRA;
			$DATA[$i]["NAKSHATRA"]=$NAKSHATRA;
			$DATA[$i]["SUBCASTE"]=$SUBCASTE;

                        $DATA[$i]["source"]=$source;
			$DATA[$i]["profilechecksum"]=md5($USER) . "i" . $USER;
			$DATA[$i]["INCOME"]=$income_map[$myrow1['INCOME']];
			$DATA[$i]["RELIGION"]=$RELIGION;

			$is_album=0;
			if($havephoto=='Y')
                        {
				$is_album = SymfonyPictureFunctions::checkMorePhotos($user[$i],1,$db);	 //Symfony Photo Modification
                                if($photo_display=='H')
                                {
                                        $DATA[$i]["havephoto"]="H";
                                }
                                else
                                {
					//Symfony Photo Modification
					$profilePicUrls = SymfonyPictureFunctions::getPhotoUrls_nonSymfony($USER,"ThumbailUrl",$db);
					$profilePicUrlArr = $profilePicUrls[$USER];
        				if ($profilePicUrlArr)
        				{
                				$thumbnailUrl = $profilePicUrlArr["ThumbailUrl"];
        				}
        				else
        				{
                				$thumbnailUrl = null;
        				}
					$DATA[$i]["ThumbnailUrl"] = $thumbnailUrl;
					//Symfony Photo Modification
                                        $DATA[$i]["havephoto"]="Y";
                                }
                        }
			else 
			{
				$DATA[$i]["profilechecksum"]=md5($USER) . "i" . $USER;
				$is_album=0;
			}

			$DATA[$i]['album']=$is_album;
                        $DATA[$i]["album_link"] = urlencode($SITE_URL.'/profile/layer_photocheck.php?checksum=&profilechecksum='.$DATA[$i]['profilechecksum'].'&seq=1');

		}

		if($count==1)
			$subject = "Respond to ".$DATA[0]["username"]." who is waiting for your response";
		elseif($count==2)
			$subject = "Respond to ".$DATA[0]["username"]." and ". $DATA[1]["username"]." who are waiting for your response";
		elseif($count==3)
			$subject = "Respond to ".$DATA[0]["username"].", ".$DATA[1]["username"]." and ".($count-2)." more member waiting for your response.";
		else
			$subject = "Respond to ".$DATA[0]["username"].", ".$DATA[1]["username"]." and ".($count-2)." more members waiting for your response.";
		$smarty->assign("DATA",$DATA);
		unset($DATA);
		unset($user);
		unset($USER);
		//response Tracking -- pankaj
		$smarty->assign("ResponseTracking",JSTrackingPageType::YN_MAILER);
		$from="contacts@jeevansathi.com";

		if($GENDER=='M')
			$msg=$smarty->fetch("Yes_No_Men_Audience.html");
		else
			$msg=$smarty->fetch("Yes_No_Women_Audience.html");
		
		//*******Tracking Mails Sent****************//
		$date=date("Y-m-j");
		$sql1="select MAILER_ID from mmmjs.MAIL_SENT_STANDARD where DATE='$date' and MAILER_ID='$mailer_id'";
		$result1=mysql_query($sql1,$db) or logerror1("maileryn - 2",$sql1);//die( " SQL :$sql1  \n Error : ".mysql_error());
		if(mysql_num_rows($result1)>0)
		{
			$sql2="UPDATE mmmjs.MAIL_SENT_STANDARD SET SENT=(SENT+1) WHERE MAILER_ID ='$mailer_id' AND DATE='$date'";
			mysql_query($sql2,$db) or logerror1("maileryn - 3",$sql2);//die( " SQL :$sql2  \n Error : ".mysql_error());
		}
		else
		{
			$sql2="INSERT INTO mmmjs.MAIL_SENT_STANDARD(DATE,MAILER_ID,SENT) VALUES('$date','$mailer_id',1)";
			mysql_query($sql2,$db) or logerror1("maileryn - 4",$sql2);//die( " SQL :$sql2  \n Error : ".mysql_error());
		}

                $sql2="UPDATE mmmjs.MAILERYN SET SENT='Y' WHERE ID='$id'";
	        mysql_query($sql2,$db) or logerror1("came2 of mailac",$sql2);

		if($count)
		{
			send_email($email,$msg,$subject,$from,'','','','','','',$html="1","","Jeevansathi Contacts");
		}
	}
}

function label_select_mmmjs($columnname,$value)
{
	global $db;
        $sql = "select SQL_CACHE LABEL from newjs.$columnname WHERE VALUE='$value'";
        $res = mysql_query($sql,$db) or logerror1("error",$sql) ;
        $myrow= mysql_fetch_row($res);
        return $myrow;
                                                                                                                             
}

?>
