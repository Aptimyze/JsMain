<?php
/*****************************************************************************************************************************           FILE NAME      : yes_no_mail.php
*           DESCRIPTION    : Allows sendingOn Demand yes-no mailer from CRM
*           FILES INCLUDED : connect.inc ; functions used : authenticated()(for authentication of the user) 
*           CREATION DATE  : 12 June, 2008
*           CREATED BY     : Neha Verma
*           Copyright  2005, InfoEdge India Pvt. Ltd.
****************************************************************************************************************************/

$flag_using_php5=1;
include("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once(JsConstants::$docRoot."/classes/globalVariables.Class.php");
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
$mysqlObj=new Mysql;

include_once("../profile/contacts_functions.php");
$db=connect_db();

if(authenticated($cid))
{
	$name= getuser($cid);
	
	if($submit)
	{
		$msg='';$wrong=0;
		$sql= "SELECT PROFILEID,USERNAME,PASSWORD,GENDER,SUBSCRIPTION,EMAIL,COUNTRY_RES FROM newjs.JPROFILE WHERE USERNAME IN ('$caller','$recipient')";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			if($row["USERNAME"]==$recipient)
			{
				$receiver=$row["PROFILEID"];
				$USERNAME=$row['USERNAME'];
				$PASSWORD=$row['PASSWORD'];
		                $GENDER=$row['GENDER'];
				$SUBSCRIPTION=$row["SUBSCRIPTION"];
				$EMAIL=$row["EMAIL"];
				$COUNTRY_RES=$row["COUNTRY_RES"];
			}
			else
			{
				$sender=$row["PROFILEID"];
			}
			$uname[]=$row['USERNAME'];
			
		}
		if(is_array($uname))
		{
			if(!in_array($caller,$uname))
			{
				$wrong++;
				$msg="No such Caller exists!!";
			}
			elseif(!in_array($recipient,$uname))
			{
	        	        $wrong++;
				$msg="No such Recipient exists!!";
			}
			else
			{
				$curdate = date("Y-m-d");
			        list($yy,$mm,$dd)=explode("-",$curdate);
			        $today_timestamp=mktime(0,0,0,$mm,$dd,$yy);
				$fifteen_next_timestamp=$today_timestamp+(15*24*60*60);
			        $fifteen_next_date=date("Y-m-d",$fifteen_next_timestamp);
				
				$sql="SELECT COUNT(*) AS CNT FROM jsadmin.OnDEMAND_MAIL_SMS WHERE RECIPIENT='$receiver' AND DATE='$curdate' AND SOURCE='mail'";
				$res= mysql_query_decide($sql) or die(mysql_error_js());
				$row=mysql_fetch_array($res);
				if($row['CNT']>0)
				{
					$wrong++;
					$msg= "On-Demand Mailer cannot be sent more than once a day to a Recipient";
				}
				else
				{
					$i=0;

					$contactResult=getResultSet("SENDER,TIME,DATEDIFF(now(),TIME) as TIME_DIFF","$sender","",$receiver,"","'I'","","TIME >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
					if(is_array($contactResult))
			                {
			                        $TIME_DIFF=$contactResult[0]["TIME_DIFF"];
						$TIME=$contactResult[0]["TIME"];
			                }
			                unset($contactResult);
					if($TIME)
					{
						$dt=explode(" ",$TIME);
                                                list($y1,$m1,$d1)=explode("-",$dt[0]);
                                                $data_timestamp=mktime(0,0,0,$m1,$d1,$y1);
						$data_date=date("jS M Y",$data_timestamp);
						$smarty->assign("data_date",$data_date);
						if(!@mysql_ping($db))
				                {
				                        $db=connect_db();
				                        @mysql_select_db("mmmjs",$db);
				                }


						//using new path.. change for live
					        include_once(JsConstants::$alertDocRoot."/mmmjs/standard/payment_array.php");
					        $smarty->assign("PAY_ERISHTA",$pay_erishta);
					        $smarty->assign("PAY_ECLASSIFIED",$pay_eclassified);
					        $smarty->assign("PAY_EVALUE",$pay_evalue);
						//Recipient Details

						if(strstr($SUBSCRIPTION,"F"))
				                {
				                        $sql_paid= "SELECT EXPIRY_DT FROM billing.SERVICE_STATUS WHERE PROFILEID='$receiver' ORDER BY EXPIRY_DT desc limit 1";
				                        $res_paid=mysql_query_decide($sql_paid,$db) or logerror1("came5 of mailac",$sql_paid);
				                        $row_paid=mysql_fetch_assoc($res_paid);
				                        $expiry_dt=$row_paid['EXPIRY_DT'];
				                        if($fifteen_next_date<=$expiry_dt)
				                                $smarty->assign("no_tariff_table",1);
				                        $smarty->assign("RECEIVER_IS_PAID","1");
				                }
				                else
				                        $smarty->assign("RECEIVER_IS_PAID","0");
                				$smarty->assign("PASSWORD",$PASSWORD);
				                $email=$EMAIL;
						if($COUNTRY_RES=='51')
		                                        $smarty->assign("COUNTRY",'I'); 
		                                $smarty->assign("RECEIVER",$USERNAME);
						$smarty->assign("PASSWORD",$PASSWORD);

						//End of Recipient details

						//Callers details
						 $sql1="SELECT USERNAME,SOURCE,HEIGHT,YOURINFO,MTONGUE,CASTE,AGE,OCCUPATION,CITY_RES,COUNTRY_RES,HAVEPHOTO,PHOTO_DISPLAY,GOTHRA,NAKSHATRA,EDU_LEVEL_NEW,INCOME,SUBCASTE,MSTATUS,MANGLIK FROM  newjs.JPROFILE  WHERE PROFILEID='$sender'";
			                        $result1=mysql_query($sql1,$db) or die(mysql_error());
			                        $myrow1= mysql_fetch_array($result1);

						$height=$myrow1['HEIGHT'];
						$mtongue=$myrow1['MTONGUE'];
						$caste=$myrow1['CASTE'];
						$occupation=$myrow1['OCCUPATION'];
						$city=$myrow1['CITY_RES'];
						$country=$myrow1['COUNTRY_RES'];
			                        $username=$myrow1['USERNAME'];
			                        $havephoto=$myrow1['HAVEPHOTO'];
			                        $photo_display=$myrow1['PHOTO_DISPLAY'];
			                        $age=$myrow1['AGE'];
			                        $GOTHRA=$myrow1['GOTHRA'];
			                        $NAKSHATRA=$myrow1['NAKSHATRA'];
                			        $SUBCASTE=$myrow1['SUBCASTE'];
						if($myrow1["SOURCE"]=="ofl_prof")
							$source="O";
						$time_left=30-$TIME_DIFF;
						if($time_left>30 || $time_left<0)
				                        $time_left=0;

			                        

						unset($temp);
			                         $temp=label_select("HEIGHT",$height);
			                        if($temp)
		                                	$newsendersHEIGHT2=implode(",",$temp);
			                        $newsendersHEIGHT=explode(" (",$newsendersHEIGHT2);
			                        $HEIGHT=$newsendersHEIGHT[0];
			                        unset($temp);
			                        $temp=label_select("MTONGUE",$mtongue);
			                        $MTONGUE=$temp[0];
			                        unset($temp);

			                        $temp=label_select("CASTE",$caste);
			                        $CASTE=$temp[0];
			                        unset($temp);

			                        $temp=label_select("OCCUPATION",$occupation);
			                        $OCCUPATION=$temp[0];
			                        unset($temp);

			                        $temp=label_select("COUNTRY",$country);
			                        $COUNTRY=$temp[0];
			                        unset($temp);
						
						if($country=='51')
			                        {
			                                $temp=label_select("CITY_INDIA",$city);
			                        }
			                        else
			                        {
			                                $temp=label_select("CITY_USA",$city);
			                        }
			                        $CITY=$temp[0];
			                        unset($temp);

			                        if(!$CITY=="")
			                                $RESIDENCE=$CITY;
			                        else
			                                $RESIDENCE=$COUNTRY;
			                        unset($CITY);
						$DATA[$i]["profileid"]=$sender;
						$DATA[$i]["username"]=$username;
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
			                        $DATA[$i]["time_left"]=$time_left;
						$DATA[$i]["source"]=$source;
			                        if($GENDER=='F')
			                                $DATA[$i]["INCOME"]=$income_map[$myrow1['INCOME']];

						$USER=$sender;
			                        if($havephoto=='Y')
			                        {
			                                if(($photo_display=='H')||($photo_display=='C'))
				                        {
			                                        $DATA[$i]["havephoto"]="H";
			                                }
			                                else
			                                {
								//Symfony Photo Modification - start
        							$profilePicObjs = SymfonyPictureFunctions::getProfilePicObjs($USER);
        							$profilePicObj = $profilePicObjs[$USER];
        							if ($profilePicObj)
        							{
                							$thumbnailUrl = $profilePicObj->getThumbailUrl();
        							}
        							else
        							{
                							$thumbnailUrl = null;
        							}
								$DATA[$i]["ThumbnailUrl"]=$thumbnailUrl;
								//Symfony Photo Modification - end
								
								$photochecksum_new = md5($USER+5)."i".($USER+5);	
			                                        $DATA[$i]["photochecksum_new"]=$photochecksum_new;
			                                        $DATA[$i]["havephoto"]="Y";
			                                }
			                        }
			                        else
			                                $DATA[$i]["PROFILECHECKSUM"]=md5($USER) . "i" . $USER;

						//end of callers details
				                $smarty->assign("DATA",$DATA);
				                unset($DATA);
				                unset($user);
				                unset($USER);
						
						 if($GENDER=='M')
				                        $msg=$smarty->fetch("yes_no_female.htm");
				                else
					                $msg=$smarty->fetch("yes_no_male.htm");
						
						$from="info@jeevansathi.com";
						$subject="There is a special someone waiting for you";	
                                		send_email($email,$msg,$subject,$from);
						$msg="Mail Sent";
						$sql= "INSERT INTO jsadmin.OnDEMAND_MAIL_SMS(RECIPIENT,DATE,SOURCE) values('$receiver','$curdate','mail')";
                                                mysql_query_decide($sql) or die(mysql_error_js());
					}
					else
					{
						$wrong++;
						$msg="There is no EOI from Caller to Recipient";
						
					}
				}
			}
		}
		else
		{
			$msg="No such Caller and Recipient exists!!";
			$wrong++;
		}
		
		$smarty->assign("wrong",$wrong);	
		$smarty->assign("msg",$msg);
	}
	$smarty->assign("user",$name);	
	$smarty->assign("cid",$cid);
	$smarty->display("yes_no_mail.htm");
}
else
{
        $msg = "Your session has been timed out<br>  ";
        $msg.= "<a href=\"index.htm\">";
        $msg.= "Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

?>
