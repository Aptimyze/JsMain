<?php
/**
*       Filename        :       mainmenu.php
*       Description     :
*       Created by      :
*       Changed by      :
*       Changed on      :
        Changes         :       New Service added called Eclassified , changes done due to it.
**/
	
	include("connect.inc");
        include("sms_service.inc");
	require_once("display_result.inc");
	// connect to database
	$db=connect_db();

	$data=authenticated($checksum);
	
	/*****************Portion of Code added for display of Banners*******************************/
	$smarty->assign("data",$data["PROFILEID"]);
        $smarty->assign("bms_topright",18);
        $smarty->assign("bms_right",28);
        $smarty->assign("bms_bottom",19);
        $smarty->assign("bms_left",24);
        $smarty->assign("bms_new_win",32);	// for popunder/pop up in My Jeevansathi
	$smarty->assign("bms_mainmenu",43);

        /*$data=authenticated($checksum);
        $regionstr=8;
        include("../bmsjs/bms_display.php");
        /***********************End of Portion of Code*****************************************/
                                                                                                                             
        //$db=connect_db();

	if(isset($data) || $show_details)
	{
		$checksum=$data["CHECKSUM"];
		$profileid=$data["PROFILEID"];
		
		// make the person visible on chat
		if($make_visible_on_chat)
		{
			$idexp=explode("i",$checksum);
			$id=$idexp[1];
			
			$sql="update CONNECT set CHAT='' where ID='$id'";
			mysql_query($sql);
			
			$sql="insert ignore into userplane.users(userID,LastTimeOnline,displayName) values ('$profileid',now(),'" . addslashes($data["USERNAME"]) . "')";
			mysql_query($sql);
			
			$smarty->assign("VISIBLE_ON_CHAT","1");
			$smarty->assign("LOGGED_OUT_OF_CHAT","");
		}
		// make the person invisible on chat
		elseif($make_invisible_on_chat)
		{
			$idexp=explode("i",$checksum);
			$id=$idexp[1];
			
			$sql="update CONNECT set CHAT='N' where ID='$id'";
			mysql_query($sql);
			
			$sql="delete from userplane.users where userID='$profileid'";
			mysql_query($sql);
			
			$smarty->assign("LOGGED_OUT_OF_CHAT","1");
		}
		elseif($data["CHAT"]!="N") 
			$smarty->assign("VISIBLE_ON_CHAT","1");
		
		$sql="select NTIMES,MOD_DT,GENDER,INCOMPLETE,DATE_SUB(left(ENTRY_DT,10), INTERVAL 20 DAY) as ENT_DT,PHONE_MOB,HAVEPHOTO,SOURCE from JPROFILE where PROFILEID='$profileid'";
		$result=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		
		if(mysql_num_rows($result) > 0)
		{
			$myrow=mysql_fetch_array($result);

        // Added by Rahul Tara on 7 April for checking sms service availablity

			$afl_source = $myrow["SOURCE"];
                        $phone_mob = $myrow["PHONE_MOB"];
                        if($phone_mob == '')
                                $smarty->assign("PHONE_MOB_NULL",'Y');
                        else
                        {
                                if(check_sms_service($phone_mob))
                                        $smarty->assign("SMS_SERVICE_AVAILABLE",'Y');
                                else
                                        $smarty->assign("SMS_SERVICE_AVAILABLE",'N');
                                $smarty->assign("PHONE_MOB",$phone_mob);
                        }
        //

			$smarty->assign("HAVEPHOTO",$myrow["HAVEPHOTO"]);
			
			if($myrow["INCOMPLETE"]=="Y")
			{
				$callValidate=1;
				include("editprofile1.php");
			}
			else 
			{
				$entry_dt=$myrow["ENT_DT"];
				$mydate=substr($myrow["MOD_DT"],0,10);
				$mydateArr=explode("-",$mydate);
				
				$mydate=my_format_date($mydateArr[2],$mydateArr[1],$mydateArr[0]);
				
				$smarty->assign("VIEWS",$myrow["NTIMES"]);
				$smarty->assign("LAST_MODIFIED",$mydate);
				
				$gender=$myrow["GENDER"];
				
				// free the recordset
				mysql_free_result($result);
				
				$sub_rights=explode(",",$data["SUBSCRIPTION"]);
                                if(in_array("O",$sub_rights))
                                {
                                        if(in_array("F",$sub_rights) && in_array("B",$sub_rights))
                                        {
                                                $subscription="yes";
                                                $membership="Value Added Member";
                                        }
                                }
                                elseif(!in_array("O",$sub_rights))
                                { 
                                        // changes due to addition of new service eclassified  NEW CHANGES
					if((in_array("F",$sub_rights))||(in_array("D",$sub_rights)))
                                        {
						$subscription="yes";
                                                                                                                             
						if(in_array("F",$sub_rights) && in_array("D",$sub_rights))
							$membership="e-Value Pack";
						elseif(in_array("F",$sub_rights))
							$membership="e-Rishta";
						elseif(in_array("D",$sub_rights))
							$membership="e-Classified";
                                        }
                                        else
                                        {
                                                $subscription="no";
                                                $membership="Free Member";
                                        }
                                        if(in_array("B",$sub_rights) or in_array("V",$sub_rights) or in_array("H",$sub_rights) or in_array("K",$sub_rights))
                                        {
                                                $addon_subscription="yes";
                                                if(in_array("V",$sub_rights))
                                                        $addon_membership_arr[]="Voice mail";
                                                if(in_array("H",$sub_rights))
                                                        $addon_membership_arr[]="Horoscope";
                                                if(in_array("K",$sub_rights))
                                                        $addon_membership_arr[]="Kundali";
                                                if(in_array("B",$sub_rights))
                                                        $addon_membership_arr[]="Bold Listing";
                                                $addon_membership=implode("<br>&nbsp;",$addon_membership_arr);
                                        }
                                }
				$sql_p="SELECT BILLID FROM billing.PURCHASES p WHERE p.PROFILEID = '$profileid' ORDER BY p.BILLID desc limit 1";
				$res_p=mysql_query($sql_p) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				$myrow_p=mysql_fetch_array($res_p);

				$sql_p2="SELECT ss.ACTIVATED_ON,ss.EXPIRY_DT FROM billing.SERVICE_STATUS ss WHERE ss.BILLID='$myrow_p[BILLID]' ORDER BY ss.EXPIRY_DT desc limit 1";
				$res_p2=mysql_query($sql_p2) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                                $myrow_p2=mysql_fetch_array($res_p2);

				if(!mysql_num_rows($res_p))
				{
					$sql_p3="select EXPIRY_DT from billing.SUBSCRIPTION_EXPIRE where PROFILEID = '$profileid'";
					$res_p3=mysql_query($sql_p3) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
					$myrow_p3=mysql_fetch_array($res_p3);

					if(mysql_num_rows($res_p3))
					{
						$show_expiry="yes";
	                                        list($year,$month,$day) = explode("-",$myrow_p3['EXPIRY_DT']);
	                                        $ssexpiry_dt=my_format_date($day,$month,$year);
					}
					else
					{
						$show_expiry="no";
						$ssexpiry_dt="";
					}

				}
				elseif(in_array("F",$sub_rights))
				{
					$show_expiry="yes";
					list($year,$month,$day) = explode("-",$myrow_p2['EXPIRY_DT']);
					$ssexpiry_dt=my_format_date($day,$month,$year);
				}
				else
				{
					$show_expiry="no";
					$ssexpiry_dt="";
				} 
	
				$sql="select count(*) as cnt from CONTACTS where RECEIVER='" . $data["PROFILEID"] . "' and TYPE='I'";
				$result=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				
				$myrow=mysql_fetch_array($result);
				$smarty->assign("RECEIVED_I",$myrow["cnt"]);
				$RECEIVEDSUM=$myrow["cnt"];
				
				$onlyreceivedsum=$myrow["cnt"];
				
				mysql_free_result($result);
				
				$sql="select count(*) as cnt from CONTACTS where RECEIVER='" . $data["PROFILEID"] . "' and TYPE='A'";
				$result=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				
				$myrow=mysql_fetch_array($result);
				$smarty->assign("RECEIVED_A",$myrow["cnt"]);
				$received_acc=$myrow["cnt"];
				$RECEIVEDSUM+=$myrow["cnt"];
				
				$ACCEPTED=$myrow["cnt"];
				
				mysql_free_result($result);
				
				$sql="select count(*) as cnt from CONTACTS where RECEIVER='" . $data["PROFILEID"] . "' and TYPE='D'";
				$result=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				
				$myrow=mysql_fetch_array($result);
				$smarty->assign("RECEIVED_D",$myrow["cnt"]);
				$RECEIVEDSUM+=$myrow["cnt"];
				
				mysql_free_result($result);
				
				$sql="select count(*) as cnt from CONTACTS where SENDER='" . $data["PROFILEID"] . "' and TYPE='I'";
				$result=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				
				$myrow=mysql_fetch_array($result);
				$smarty->assign("MADE_I",$myrow["cnt"]);
				$MADESUM=$myrow["cnt"];
				$MADE_I=$myrow["cnt"];	
				mysql_free_result($result);
				
				$sql="select count(*) as cnt from CONTACTS where SENDER='" . $data["PROFILEID"] . "' and TYPE='A'";
				$result=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				
				$myrow=mysql_fetch_array($result);
				$smarty->assign("MADE_A",$myrow["cnt"]);
				$made_acc=$myrow["cnt"];
				$MADESUM+=$myrow["cnt"];
				
				$ACCEPTED+=$myrow["cnt"];
				
				mysql_free_result($result);
				
				$sql="select count(*) as cnt from CONTACTS where SENDER='" . $data["PROFILEID"] . "' and TYPE='D'";
				$result=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				
				$myrow=mysql_fetch_array($result);
				$smarty->assign("MADE_D",$myrow["cnt"]);
				$MADESUM+=$myrow["cnt"];
				//$MADE_D=$myrow["cnt"];
				mysql_free_result($result);

				//code added by: Gaurav Arora 
				// Code added on : 14 Sept 2005
				//Reason for addition: to add new links for members who have not viewed the profile and those who have viewed the profile and not responded

				//code added to avoid JOIN query	
				$sql="select RECEIVER from CONTACTS where SENDER='$data[PROFILEID]' AND TYPE='I'";
	                        $result_new=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        		        while($row=mysql_fetch_array($result_new))
                        	        $arr_rec[]=$row['RECEIVER'];
	                        if($arr_rec)
        	                {
                	                $arr_rec_str="'".implode("','",$arr_rec)."'";
                        	        //$sql="select VIEWER from VIEW_LOG where VIEWED='$data[PROFILEID]' AND VIEWER IN ($arr_rec_str)";
                        	        $sql="select count(*) as cnt from VIEW_LOG where VIEWED='$data[PROFILEID]' AND VIEWER IN ($arr_rec_str)";
                                	$result=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
					$row=mysql_fetch_array($result);
					$MADE_D_R=$row['cnt'];
				}
				else
				{
					$MADE_D_R=0;
				}
				//end of code added to avoid JOIN query	

				/*commented to avoid JOIN query
				$sql="select count(*) as cnt from CONTACTS JOIN VIEW_LOG on CONTACTS.RECEIVER=VIEW_LOG.VIEWER where CONTACTS.SENDER=VIEW_LOG.VIEWED AND SENDER='$data[PROFILEID]' AND TYPE='I'";
				//echo $sql="select count(*) as cnt from CONTACTS JOIN VIEW_LOG on CONTACTS.RECEIVER=VIEW_LOG.VIEWER where SENDER='" . $data["PROFILEID"] . "' AND TYPE='I' AND VIEWED_MMM='Y'";
                                $result=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                                $myrow=mysql_fetch_array($result);
				*/
                                $smarty->assign("MADE_D_R",$MADE_D_R);
				//$MADE_D_R=$myrow["cnt"];
                                //$MADESUM+=$myrow["cnt"];                                                                                                  
                                //mysql_free_result($result);
				$MADE_D_V=$MADE_I-$MADE_D_R;
                                $smarty->assign("MADE_D_V",$MADE_D_V);
	
				//end of code added to add new links for members who have not viewed the profile and those who have viewed the profile and not responded

				if($subscription=="no" && $ACCEPTED >= 3 && ($entry_dt < date("Y-m-d")))
				{
					$sql="select SENDER from CONTACTS where RECEIVER='" . $data["PROFILEID"] . "' and TYPE='A'";
					$mysenderresult=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
					
					while($mysenderrow=mysql_fetch_array($mysenderresult))
					{
						$accepted_arr[]=$mysenderrow["SENDER"];
					}
					
					mysql_free_result($mysenderresult);
					
					$sql="select RECEIVER from CONTACTS where SENDER='" . $data["PROFILEID"] . "' and TYPE='A'";
					$mysenderresult=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
					
					while($mysenderrow=mysql_fetch_array($mysenderresult))
					{
						$accepted_arr[]=$mysenderrow["RECEIVER"];
					}
					
					mysql_free_result($mysenderresult);
					
					$send_str=implode($accepted_arr,",");
					
					$sql="select PROFILEID,USERNAME,AGE,HEIGHT,CASTE,OCCUPATION,CITY_RES,COUNTRY_RES from JPROFILE where PROFILEID in ($send_str) order by ENTRY_DT desc limit 3";
					
					$mysenderresult=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
					include_once("dropdowns.php");
					
					while($mysenderrow=mysql_fetch_array($mysenderresult))
					{
						$tmpheight=$mysenderrow["HEIGHT"];
						$tmpcaste=$mysenderrow["CASTE"];
						$tmpoccupation=$mysenderrow["OCCUPATION"];
						$tmpcountry=$mysenderrow["COUNTRY_RES"];
						$tmpcity=$mysenderrow["CITY_RES"];
						
						if($tmpcountry==51)
							$tmpcity=$CITY_INDIA_DROP["$tmpcity"];
						elseif($tmpcountry==128)
							$tmpcity=$CITY_USA_DROP["$tmpcity"];
						else 
							$tmpcity="";
							
						$open_contacts[]=array("USERNAME" => $mysenderrow["USERNAME"],
						"HEIGHT" => $HEIGHT_DROP["$tmpheight"],
						"AGE" => $mysenderrow["AGE"],
						"CASTE" => $CASTE_DROP["$tmpcaste"],
						"OCCUPATION" => $OCCUPATION_DROP["$tmpoccupation"],
						"CITY_RES" => $tmpcity,
						"COUNTRY_RES" => $COUNTRY_DROP["$tmpcountry"],
						"PROFILECHECKSUM" => md5($mysenderrow["PROFILEID"]) . "i" . $mysenderrow["PROFILEID"],
						"PHOTOCHECKSUM" => md5($mysenderrow["PROFILEID"]+5) . "i" . ($mysenderrow["PROFILEID"]+5));
					}
					
					$smarty->assign("NO_ACCEPTED",$ACCEPTED);
					$smarty->assign("HASACCEPTEDCONTACTS",1);
					$smarty->assign("ACCEPTED_CONTACTS",$open_contacts);
					
				}
				// if there are open contacts
				elseif($onlyreceivedsum > 0)
				{
					$sql="select SENDER from CONTACTS where RECEIVER='" . $data["PROFILEID"] . "' and TYPE='I'";
					$mysenderresult=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
					
					while($mysenderrow=mysql_fetch_array($mysenderresult))
					{
						$send_arr[]=$mysenderrow["SENDER"];
					}
					
					mysql_free_result($mysenderresult);
					
					$send_str=implode($send_arr,",");
					
					$sql="select PROFILEID,USERNAME,AGE,HEIGHT,CASTE,OCCUPATION,CITY_RES,COUNTRY_RES from JPROFILE where PROFILEID in ($send_str) order by ENTRY_DT desc limit 3";
					
					$mysenderresult=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
					include_once("dropdowns.php");
					
					while($mysenderrow=mysql_fetch_array($mysenderresult))
					{
						$tmpheight=$mysenderrow["HEIGHT"];
						$tmpcaste=$mysenderrow["CASTE"];
						$tmpoccupation=$mysenderrow["OCCUPATION"];
						$tmpcountry=$mysenderrow["COUNTRY_RES"];
						$tmpcity=$mysenderrow["CITY_RES"];
						
						if($tmpcountry==51)
							$tmpcity=$CITY_INDIA_DROP["$tmpcity"];
						elseif($tmpcountry==128)
							$tmpcity=$CITY_USA_DROP["$tmpcity"];
						else 
							$tmpcity="";
							
						$open_contacts[]=array("USERNAME" => $mysenderrow["USERNAME"],
						"HEIGHT" => $HEIGHT_DROP["$tmpheight"],
						"AGE" => $mysenderrow["AGE"],
						"CASTE" => $CASTE_DROP["$tmpcaste"],
						"OCCUPATION" => $OCCUPATION_DROP["$tmpoccupation"],
						"CITY_RES" => $tmpcity,
						"COUNTRY_RES" => $COUNTRY_DROP["$tmpcountry"],
						"PROFILECHECKSUM" => md5($mysenderrow["PROFILEID"]) . "i" . $mysenderrow["PROFILEID"],
						"PHOTOCHECKSUM" => md5($mysenderrow["PROFILEID"]+5) . "i" . ($mysenderrow["PROFILEID"]+5));
					}
					
					$smarty->assign("HASOPENCONTACTS",1);
					$smarty->assign("OPEN_CONTACTS",$open_contacts);
				}
		
				if($showChat)
				{
					// variable to display the chat popup only when the person logs in
					$smarty->assign("CHATPOPUP","1");
					$smarty->assign("CHATUNIQID",uniqid("C"));
				}

			
////////Code added by Aman Sharma for  addition of details//


				$total_acc=$made_acc+$received_acc;

				$PAGELEN=15;
	        	        if(!$j)
        	        	        $j=0;

				$sql="(select SENDER,RECEIVER,TIME from CONTACTS where SENDER='" . $data["PROFILEID"] . "' and  TYPE='A') UNION (select SENDER,RECEIVER,TIME from CONTACTS where RECEIVER='" . $data["PROFILEID"] . "' and  TYPE='A') order by TIME desc limit $j,$PAGELEN";
				$result=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				$MY_COUNT=mysql_num_rows($result);
																	     
																	     
				if($MY_COUNT > 0)
				{
					$curcount=$j;
					$totalrec=$total_acc;
					$scriptname=$_SERVER['PHP_SELF'];
					$links_to_show=10;
					if( $curcount )
						$cPage = ($curcount/$PAGELEN) + 1;
					else
						$cPage = 1;
			       		// $checksum.="&type=$type&flag=$flag";
					pagelink($PAGELEN,$totalrec,$cPage,$links_to_show,$checksum,$scriptname);
					$smarty->assign("RECORDCOUNT1",$totalrec);
					$smarty->assign("NO_OF_PAGES",ceil($totalrec/$PAGELEN));

				}
				else
				{
					$smarty->assign("RECORDCOUNT1","0");
					$smarty->assign("NORESULTS","1");
					$smarty->assign("NO_OF_PAGES","0");
					$smarty->assign("CURPAGE","0");
				}

		
			

				while($myrow_ac=mysql_fetch_array($result))
				
				{
					
					if ($myrow_ac["SENDER"]==$data["PROFILEID"])
					{
						$pid=$myrow_ac["RECEIVER"];
						$acc_con="Contacted by you";
					}
					else
					{
						$pid=$myrow_ac["SENDER"] ;
						$acc_con="Accepted by you";
					}	
							$date_con=substr($myrow_ac["TIME"],0,10);
							
						$date_arr=explode("-",$date_con);
					//$date_display=my_format_date($date_arr[2],$date_arr[1],$date_arr[0]);
					$date_contact=my_format_date($date_arr[2],$date_arr[1],$date_arr[0]);

	
					$sql="select PROFILEID,USERNAME,EMAIL,AGE,HEIGHT,CASTE,OCCUPATION,CITY_RES,COUNTRY_RES,SUBSCRIPTION,CONTACT,SHOWADDRESS,PHONE_RES,PHONE_MOB,SHOWPHONE_RES,SHOWPHONE_MOB,PARENTS_CONTACT,SHOW_PARENTS_CONTACT,INCOME,EDU_LEVEL_NEW from JPROFILE where PROFILEID='$pid'";
				       $my_accresult=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

							include_once("dropdowns.php");

							 $income_map=array("1" => "< Rs. 50K",
									"2" => "Rs. 50K - 1Lac",
									"3" => "Rs. 1 - 2Lac",
									"4" => "Rs. 2 - 3Lac",
									"5" => "Rs. 3 - 4Lac",
									"6" => "Rs. 4 - 5Lac",
									"7" => "> Rs. 5Lac",
									"8" => "< $ 25K",
									"9" => "$ 25 - 50K",
									"10" => "$ 50 - 75K",
									"11" => "$ 75K - 1Lac",
									"12" => "$ 1 - 1.5Lac",
									"13" => "$ 1.5 - 2Lac",
									"14" => "> $ 2Lac",
									"15" => "No Income",
									"16" => "Rs. 5 - 7.5Lac",
                                                        "17" => "Rs. 7.5 - 10Lac",
                                                        "18" => "> Rs. 10Lac");

                                        while($my_accrow=mysql_fetch_array($my_accresult))
                                        {
						$email='';
						$phone='';
						$income_con='';
						$addr='';
						$parents_addr='';
						$SHOW_OPS='';

						$mem_rights=explode(",",$data["SUBSCRIPTION"]);
						if(in_array("F",$mem_rights) || $my_accrow["SUBSCRIPTION"]!='')
						{
							$SHOW_OPS="Y";	
						}
							$email=$my_accrow["EMAIL"];
							if($my_accrow["SHOWPHONE_RES"]=='Y')
								$phone=$my_accrow["PHONE_RES"];
							if($my_accrow["SHOWPHONE_MOB"]=='Y')
	                                                {  
							     	if($phone!='')
		        						$phone.="/".$my_accrow["PHONE_MOB"];
								else
									$phone=$my_accrow["PHONE_MOB"];

							}
							if($my_accrow["SHOWADDRESS"]=='Y')
                                                                $addr=$my_accrow["CONTACT"];
							if($my_accrow["SHOW_PARENTS_CONTACT"]=='Y')
                                                                $parents_addr=$my_accrow["PARENTS_CONTACT"];
			
						$education=label_select("EDUCATION_LEVEL_NEW",$my_accrow["EDU_LEVEL_NEW"]);
                                                $tmpheight=$my_accrow["HEIGHT"];
						$tmpheight1=$HEIGHT_DROP["$tmpheight"];
						$height1=explode("(",$tmpheight1);
                                                $tmpcaste=$my_accrow["CASTE"];
                                                $tmpoccupation=$my_accrow["OCCUPATION"];
                                                $tmpcountry=$my_accrow["COUNTRY_RES"];
                                                $tmpcity=$my_accrow["CITY_RES"];
                                                                                                                             
                                                if($tmpcountry==51)
                                                        $tmpcity=$CITY_INDIA_DROP["$tmpcity"];
                                                elseif($tmpcountry==128)
                                                        $tmpcity=$CITY_USA_DROP["$tmpcity"];
                                                else
                                	                $tmpcity="";
                		        	if($data["GENDER"]=='F')
						{
							$income=$my_accrow["INCOME"];
							$income_con=",".$income_map["$income"];
						}
						else
							$income_con="";
                                                $ACCEPTED_DETAILS[]=array("USERNAME" => $my_accrow["USERNAME"],
                                                "HEIGHT" => $height1[0],
                                                "AGE" => $my_accrow["AGE"],
        	                                "CASTE" => $CASTE_DROP["$tmpcaste"],
	                                        "OCCUPATION" => $OCCUPATION_DROP["$tmpoccupation"],
						"EDUCATION"=>$education[0],						
						"INCOME"=>$income_con,
                                                "CITY_RES" =>$tmpcity,
						"DATE_CON"=>$date_contact,
						"ACC_CON"=>$acc_con,
						"EMAIL"=>$email,
						"PHONE"=>$phone,
						"ADDRESS"=>$addr,
						"PARENTS_CON"=>$parents_addr,	
                                                "COUNTRY_RES" => $COUNTRY_DROP["$tmpcountry"],
						"SHOW_OPS"=>$SHOW_OPS,
                                                "PROFILECHECKSUM" => md5($my_accrow["PROFILEID"]) . "i" . $my_accrow["PROFILEID"],
                                                "PHOTOCHECKSUM" => md5($my_accrow["PROFILEID"]+5) . "i" . ($my_accrow["PROFILEID"]+5));
                                        }


				}			
				$mem1_rights=explode(",",$data["SUBSCRIPTION"]);
				if(!in_array("F",$mem1_rights))
				{
					$smarty->assign("FREE_MEM","Y");
				}	
				
				$smarty->assign("NAME",$data["USERNAME"]);
				$smarty->assign("ACCEPTED_ARR",$ACCEPTED_DETAILS);
				
	
				$smarty->assign("MADESUM",$MADESUM);
				$smarty->assign("RECEIVEDSUM",$RECEIVEDSUM);
				$smarty->assign("GENDER",$gender);
				$smarty->assign("MEMBERSHIP",$membership);
				$smarty->assign("SUBSCRIPTION",$subscription);
                                $smarty->assign("ADDON_MEMBERSHIP",$addon_membership);
                                $smarty->assign("ADDON_SUBSCRIPTION",$addon_subscription);
				$smarty->assign("SHOWEXPIRY",$show_expiry);
				$smarty->assign("SSEXPIRYDT",$ssexpiry_dt);
				$smarty->assign("CHECKSUM",$checksum);

				$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
				$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
				$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
				$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
				$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
				$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
				$smarty->assign("RIGHTPANEL",$smarty->fetch("rightpanel.htm"));

				if($_SERVER["SERVER_NAME"]=="www.jeevansathi.com" && $afl_source=='afaflcry')
                                	$smarty->assign("SHOW_AFF_CURRY",'Y');
				$smarty->assign("source",$source);
				$smarty->assign("USERNAME",$data["USERNAME"]);
				
				$smarty->display("mainmenu.htm");
			}
		}
	}
	else 
	{
		// added to remove naukri banner from login page
		$smarty->assign("NOBOTTOMBANNER","1");
		$smarty->assign("CAME_FROM_NORMAL_LOGIN","1");
		$smarty->assign("SEARCHONLINE",$searchonline);
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));

		login_register();
		
		$smarty->display("login_register.htm");
		//TimedOut();
	}
?>
