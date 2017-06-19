<?php

/*********************************************************************************************
* FILE NAME  	: np_mail.php
* DESCRIPTION	: Selects matches for the user and sends mail
* CREATION DATE	: 19 May, 2005
* CREATED BY	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

/*********************************************************************************************
* MODIFICATION DATE	: 9 JUNE, 2005
* MODIFIED BY    	: Shakti Srivastava
* REASON		: Checks put in place to see if mysql_fetch_array returns any result at all
*********************************************************************************************/

function mainmail()
{ 
	global $smarty;

	$pickup="SELECT RECEIVER,USER1,USER2,USER3,USER4,USER5,USER6,USER7,USER8,USER9,USER10 FROM alerts.NP_MAILER WHERE SENT<>'Y' AND (USER1!='' OR USER6!='')";
	$res_pickup=mysql_query($pickup) or logerror1("Error in selecting details from alerts.NP_MAILER . ".mysql_error(),$pickup,"","");
	while($row_pickup=mysql_fetch_array($res_pickup))
	{
		$id=$row_pickup['RECEIVER'];
		$checksumu=md5($id)."i".$id;
		$smarty->assign("checksum",$checksumu);
		$user[0]=$row_pickup['USER1'];
		$user[1]=$row_pickup['USER2'];
		$user[2]=$row_pickup['USER3'];
		$user[3]=$row_pickup['USER4'];
		$user[4]=$row_pickup['USER5'];
		$user[5]=$row_pickup['USER6'];
		$user[6]=$row_pickup['USER7'];
		$user[7]=$row_pickup['USER8'];
		$user[8]=$row_pickup['USER9'];
		$user[9]=$row_pickup['USER10'];

		$name_pick="SELECT USERNAME,EMAIL FROM jsadmin.AFFILIATE_DATA WHERE PROFILEID='$id'";
//		$res_name_pick=mysql_query($name_pick) or logerror1("Error in selecting EMAIL from AFFILIATE_DATA. ".mysql_error(),$name_pick,"","");
		if($res_name_pick=mysql_query($name_pick))
		{
			$row_name=mysql_fetch_array($res_name_pick);
			$uname=$row_name['USERNAME'];
			$email=$row_name['EMAIL'];
		}
		else
		{
			logerror1("Error in selecting EMAIL from AFFILIATE_DATA. ".mysql_error(),$name_pick,"","");
		}

		for($k=0;$k<=9;$k++)
                {
                        if(!$user[$k])
                                $checksum[$k]=0;
                        else
                        {
                                $checksum[$k]=md5($user[$k] + 5)."i".($user[$k] + 5);
                        }
                }

                for($i=0;$i<=9;$i++)
                {
                        if(!$user[$i])
                                $profilechecksum[$i]=0;
                        else
                                $profilechecksum[$i]=md5($user[$i])."i".$user[$i];
                }


		for($i=0;$i<=9;$i++)
                {
                        if(!$user[$i])
                        {
                                $pix[$i]=0;
                                $username[$i]=0;
                                $age[$i]=0;
                                $HEIGHT[$i]=0;
                                $CASTE[$i]=0;
                                $CITY[$i]=0;
                                $OCCUPATION[$i]=0;
                        }
			else
			{
				$matches="SELECT USERNAME,AGE,HEIGHT,CASTE,OCCUPATION,CITY_RES,HAVEPHOTO,PHOTO_DISPLAY,PRIVACY FROM newjs.JPROFILE WHERE PROFILEID='$user[$i]'";
//                                $matchresult=mysql_query($matches) or logerror1("Error in selecting details from newjs.JPROFILE. ".mysql_error(),$matches,"","");
				if($matchresult=mysql_query($matches))
                                {
					$matchrow= mysql_fetch_array($matchresult);
	                                $pix[$i]=$matchrow['HAVEPHOTO'];
					$photo_dis[$i]=$matchrow['PHOTO_DISPLAY'];
					$privacy[$i]=$matchrow['PRIVACY'];
	
					if($pix[$i]=='N'||$photo_dis[$i]=='C'||$photo_dis[$i]=='H'||$photo_dis[$i]=='F'||$privacy[$i]=='R'||$privacy[$i]=='F')
					{
						$flag[$i]=1;
					}
					else
					{
						$flag[$i]=0;
					}
				
        	                	$username[$i]=$matchrow['USERNAME'];
	        	                $age[$i]=$matchrow['AGE'];
        	        	        $height[$i]=$matchrow['HEIGHT'];
                	        	$caste[$i]=$matchrow['CASTE'];
	                        	$occupation[$i]=$matchrow['OCCUPATION'];
        	                        $city_res[$i]=$matchrow['CITY_RES'];

	                                $sql_height="SELECT LABEL FROM newjs.HEIGHT WHERE VALUE='$height[$i]'";
//        	                        $result_height=mysql_query($sql_height) or logerror1("Error in selecting height. ".mysql_error(),$sql_height,"","");
					if($result_height=mysql_query($sql_height))
					{
                	                	$row_height=mysql_fetch_row($result_height);
		                                $HEIGHT[$i]=$row_height[0];
                	            		if($HEIGHT[$i]!="")
                        	                	$h[$i]=explode("&",$HEIGHT[$i]);
		                                $h1[$i]=$h[$i][0];
					}
					else
					{
						logerror1("Error in selecting height. ".mysql_error(),$sql_height,"","");
					}
					
	                                $sql_caste="SELECT LABEL FROM newjs.CASTE WHERE VALUE='$caste[$i]'";
//        	                        $result_caste=mysql_query($sql_caste) or logerror1("Error in selecting CASTE. ".mysql_error(),$sql_caste,"","");
					if($result_caste=mysql_query($sql_caste))
					{
                   	 	        	$row_caste=mysql_fetch_row($result_caste);
		                                $CASTE[$i]=$row_caste[0];
					}
					else
					{
						logerror1("Error in selecting CASTE. ".mysql_error(),$sql_caste,"","");
					}

                		    	$sql_job="SELECT LABEL FROM newjs.OCCUPATION WHERE VALUE='$occupation[$i]'";
//	                                $result_job=mysql_query($sql_job) or logerror1("Error in selecting occupation. ".mysql_error(),$sql_job,"","");
					if($result_job=mysql_query($sql_job))
					{
        	                        	$row_job=mysql_fetch_row($result_job);
		                                $OCCUPATION[$i]=$row_job[0];
					}
					else
					{
						logerror1("Error in selecting occupation. ".mysql_error(),$sql_job,"","");
					}

	                                if(is_numeric($city_res))
        	                        {
                	                        $sql_city="SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='$city_res[$i]'";
//                        	                $result_city=mysql_query($sql_city) or logerror1("Error in selecting CITY_USA. ".mysql_error(),$sql_city,"","");
						if($result_city=mysql_query($sql_city))
						{
                                        		$row_city=mysql_fetch_row($result_city);
		                                        $CITY[$i]=$row_city[0];
						}
						else
						{
							logerror1("Error in selecting CITY_USA. ".mysql_error(),$sql_city,"","");
						}
	                                }
					else
                	                {
                        	                $sql_city="SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='$city_res[$i]'";
//                                	        $result_city=mysql_query($sql_city) or logerror1("Error in selecting CITY_INDIA. ".mysql_error(),$sql_city,"","");
						if($result_city=mysql_query($sql_city))
						{
		                                        $row_city=mysql_fetch_row($result_city);
	        	                                $CITY[$i]=$row_city[0];
						}
						else
						{
							logerror1("Error in selecting CITY_INDIA. ".mysql_error(),$sql_city,"","");
						}
                	                }
				}
				else
				{
					logerror1("Error in selecting details from newjs.JPROFILE. ".mysql_error(),$matches,"","");
				}
                        }
			if( $age[$i] != "" )
                                $data[$i][]=$age[$i];
                        if( $HEIGHT[$i] != "" )
                                $data[$i][]=$h1[$i];
                        if( $CASTE[$i] != "" )
                                $data[$i][]=$CASTE[$i];
                        if( $CITY[$i] != "" )
                                $data[$i][]=$CITY[$i];
                        if( $OCCUPATION[$i] != "" )
                                $data[$i][]=$OCCUPATION[$i];
                        if($user[$i]!=0)
                                $DATA[$i]=implode(", ",$data[$i]);
                        else
                                $DATA[$i]=0;
                        unset($data);
		}
		$hdr="<center>You are receiving this mail because you have been referred through our referral program.<br> Please add matchalert@jeevansathi.com to your address book to ensure delivery into your inbox.<br> If you are not interested to receive this, click here to <a href=\"http://www.jeevansathi.com/unsubscribe/mailer_unsubscribe.php?mail=$email\">unsubscribe</a></center>";

		$smarty->assign("hdr",$hdr);
                $smarty->assign("DATA",$DATA);
                $smarty->assign("USERNAME",$username);
                $smarty->assign("FLAG",$flag);
                $smarty->assign("checksum",$checksum);
                $smarty->assign("PROFILECHECKSUM",$profilechecksum);
		$smarty->assign("user",$user);
		$smarty->assign("USERNAME_RECEIVER",$uname);

		$sql_mail="SELECT jsadmin.AFFILIATE_DATA.EMAIL,jsadmin.AFFILIATE_MAIN.SOURCE FROM jsadmin.AFFILIATE_DATA , jsadmin.AFFILIATE_MAIN WHERE jsadmin.AFFILIATE_DATA.PROFILEID='$id' AND jsadmin.AFFILIATE_MAIN.ID='$id'";
//		$res_sql_mail=mysql_query($sql_mail) or logerror1("Error in selecting EMAIL,SOURCE from jsadmin.AFFILIATE_DATA ".mysql_error(),$sql_mail,"","");
		if($res_sql_mail=mysql_query($sql_mail))
		{
			if($row_mail=mysql_fetch_array($res_sql_mail))
			{
				$email=$row_mail['EMAIL'];
				$src=$row_mail['SOURCE'];
				$smarty->assign("source",$src);
				$msg1 = $smarty->fetch("match.htm");
	        	        $srch="href=\"";
        	        	$repl="href=\"http://www.jeevansathi.com/g_redirect.php?source=$src&url=";
		                $msg=str_replace($srch,$repl,$msg1);
				$subject = "Match Alerts from JeevanSathi.com";
	        	        $from = "matchalert@jeevansathi.com";	
		
				if($email)
				{
					send_email($email,$msg,$subject,$from);
					$updt="UPDATE alerts.NP_MAILER SET SENT='Y' WHERE RECEIVER=$id";
					mysql_query($updt) or logerror1("Error in updating NP_MAILER table. ".mysql_error(),$updt,"","");
				}
			}
		}
		else
		{
			logerror1("Error in selecting EMAIL,SOURCE from jsadmin.AFFILIATE_DATA ".mysql_error(),$sql_mail,"","");
		}
	}

	$sql_trunc="TRUNCATE TABLE alerts.NP_TEMPLOG";
	$res_sql_trunc=mysql_query($sql_trunc) or logerror1("Error in Truncating alerts.NP_TEMPLOG. ".mysql_error(),$sql_trunc,"","");
}


/*function send_email($email,$msg,$subject,$from)
{
        $boundry = "b".md5(uniqid(time()));
        $MP = "/usr/sbin/sendmail -t  ";
        $spec_envelope = 1;
        if($spec_envelope)
        {
                $MP .= " -N never -R hdrs -f $from";
        }
        $fd = popen($MP,"w");
        fputs($fd, "X-Mailer: PHP3\n");
        fputs($fd, "MIME-Version:1.0 \n");
	fputs($fd, "To: $email\n");
        fputs($fd, "From: $from \n");
        fputs($fd, "Subject: $subject \n");
        fputs($fd, "Content-Type: text/html; boundary=$boundry\n");
        fputs($fd, "Content-Transfer-Encoding: 7bit \r\n");
        fputs($fd, "$msg\r\n");
        fputs($fd, "\r\n . \r\n");
        $p=pclose($fd);
        return $p;
}
*/
?>
