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
	// connect to database
	$db=connect_db();

	$smarty->assign("head_tab","my jeevansathi");   //flag for headnew.htm tab

	$data=authenticated($checksum);
	if($data)
		login_relogin_auth($data);

	$lang=$_COOKIE['JS_LANG'];
	if($lang=="deleted")
		$lang="";
	
	/*****************Portion of Code added for display of Banners*******************************/
	$smarty->assign("data",$data["PROFILEID"]);
        $smarty->assign("bms_topright",18);
        $smarty->assign("bms_right",28);
        $smarty->assign("bms_bottom",19);
        $smarty->assign("bms_left",24);
        $smarty->assign("bms_new_win",32);	// for popunder/pop up in My Jeevansathi
	$smarty->assign("bms_mainmenu",43);

        /***********************End of Portion of Code*****************************************/
                                                                                                                            
	/**************************Added By Shakti for link tracking**********************/
	link_track("profile_stats.php");
	/*********************************************************************************/
 
        
	if(isset($data) || $show_details)
	{
		$checksum=$data["CHECKSUM"];
		$profileid=$data["PROFILEID"];
	
		//section added by Gaurav on 25th May 2007 for horoscope compatibilty 
		$sql_compatibility="select COUNT(*) as cnt_compatibility from  HOROSCOPE_COMPATIBILITY where PROFILEID='$profileid'";
                $result_compatibility=mysql_query_decide($sql_compatibility) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_compatibility,"ShowErrTemplate");
                $myrow_compatibility=mysql_fetch_array($result_compatibility);
                //if($myrow_compatibility["cnt_compatibility"] >0)
                {
	                $smarty->assign("HOROSCOPE_COMPATIBILITY",$myrow_compatibility["cnt_compatibility"]);
                }
	          //      $smarty->assign("HOROSCOPE_COMPATIBILITY","10");
		//end of section added by Gaurav on 25th May 2007 for horoscope compatibilty 
	
		// query changed on 22.11.2005 for 'things to do' section
		$sql="select MOD_DT,GENDER,INCOMPLETE,DATE_SUB(left(ENTRY_DT,10), INTERVAL 20 DAY) as ENT_DT,PHONE_MOB,HAVEPHOTO , SOURCE from JPROFILE where PROFILEID='$profileid'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                
                $sql_no="SELECT COUNT(*) as CNT from HOROSCOPE_REQUEST_BLOCK where PROFILEID=$profileid";
                $result_no=mysql_query_decide($sql_no) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_no,"ShowErrTemplate");
                $myrow_no=mysql_fetch_array($result_no);
                if($myrow_no["CNT"] >0)
                {
                 $smarty->assign("REQUESTHOROSCOPE","N");
                }

	        //$res= viewlink($profileid);
	        $res= check_astro_details($profileid);
                $smarty->assign("nolink",$res);
                  	
		if(mysql_num_rows($result) > 0)
		{
			$myrow=mysql_fetch_array($result);

        // Added by Rahul Tara on 7 April for checking sms service availablity
			$afl_source = $myrow["SOURCE"];
                        $phone_mob = $myrow["PHONE_MOB"];

			$smarty->assign("HAVEPHOTO",$myrow["HAVEPHOTO"]);
			
			if($myrow["INCOMPLETE"]=="Y")
			{
				$callValidate=1;
				//include("editprofile1.php");
			}
			else 
			{
				$entry_dt=$myrow["ENT_DT"];
				$mydate=substr($myrow["MOD_DT"],0,10);
				$mydateArr=explode("-",$mydate);
				
				$mydate=my_format_date($mydateArr[2],$mydateArr[1],$mydateArr[0]);
				
				include_once("ntimes_function.php");
				$ntimes = ntimes_count($profileid,"SELECT");

				$smarty->assign("VIEWS",$ntimes);
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
							$membership="eValue Pack";
						elseif(in_array("F",$sub_rights))
							$membership="eRishta";
						elseif(in_array("D",$sub_rights))
							$membership="eClassified";
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
                                                        $addon_membership_arr[]="Profile Highlighting";
                                                $addon_membership=implode("<br>&nbsp;",$addon_membership_arr);
                                        }
                                }
				/*$sql_p="SELECT BILLID FROM billing.PURCHASES p WHERE p.PROFILEID = '$profileid' ORDER BY p.BILLID desc limit 1";
				$res_p=mysql_query_decide($sql_p) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				$myrow_p=mysql_fetch_array($res_p);

				$sql_p2="SELECT ss.ACTIVATED_ON,ss.EXPIRY_DT FROM billing.SERVICE_STATUS ss WHERE ss.BILLID='$myrow_p[BILLID]' ORDER BY ss.EXPIRY_DT desc limit 1";
				$res_p2=mysql_query_decide($sql_p2) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                                $myrow_p2=mysql_fetch_array($res_p2);

				if(!mysql_num_rows($res_p))
				{
					$sql_p3="select EXPIRY_DT from billing.SUBSCRIPTION_EXPIRE where PROFILEID = '$profileid' AND EXPIRY_DT>=CURDATE()";
					$res_p3=mysql_query_decide($sql_p3) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
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
				elseif(in_array("F",$sub_rights) || in_array("D",$sub_rights))
				{
					$show_expiry="yes";
					list($year,$month,$day) = explode("-",$myrow_p2['EXPIRY_DT']);
					$ssexpiry_dt=my_format_date($day,$month,$year);
				}
				else
				{
					$show_expiry="no";
					$ssexpiry_dt="";
				} */

				//code added to avoid JOIN query	
				//Sharding On Contacts done by Neha Veram
				$contactResult=getResultSet("RECEIVER",$data['PROFILEID'],"","","","'I'");
				if(is_array($contactResult))
                                {
	                                foreach($contactResult as $key=>$value)
        	                        {
                	                        $arr_rec[]=$contactResult[$key]["RECEIVER"];
                                        }
                        	}
                                unset($contactResult);
                                //end 

	                        if($arr_rec)
        	                {
					$num=count($arr_rec)/100 + 1;

        	                        $j2=0;
                	                for($i=0;$i<$num;$i++)
                        	        {
						for($k=$j2;$k<$j2+100;$k++)
						{
							if($arr_rec[$k])
								$temp_arr[]=$arr_rec[$k];
						}

						if($temp_arr)
						{
							$db_211 = connect_211();
							$arr_rec_str="'".implode("','",$temp_arr)."'";
							
							$sql="select count(*) as cnt from VIEW_LOG where VIEWED='$data[PROFILEID]' AND VIEWER IN ($arr_rec_str)";
							$result=mysql_query_decide($sql,$db_211) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
							$row=mysql_fetch_array($result);
							$MADE_D_R+=$row['cnt'];
							//mysql_close($db_211);
							$db = connect_db();
						}
						$j2+=100;
						$arr_rec_str='';
						unset($temp_arr);
					}
				}
				else
				{
					$MADE_D_R=0;
				}
				//end of code added to avoid JOIN query	

                                $smarty->assign("MADE_D_R",$MADE_D_R);
				/*CODE ADDED BY PUNEETMAKKAR on feb 13 2006 for chat stats*/
				//$sql="SELECT COUNT(DISTINCT SENDER) as cnt FROM userplane.CHAT_REQUESTS WHERE RECEIVER = '$profileid'";
				$sql="SELECT count(DISTINCT C.SENDER) as cnt FROM userplane.CHAT_REQUESTS AS C LEFT JOIN userplane.LOG_AD AS L ON C.SENDER = L.SENDER AND C.RECEIVER = L.RECEIVER WHERE C.RECEIVER ='$profileid' AND L.SENDER IS NULL";
				$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				$row=mysql_fetch_array($result);
				//$CHAT_RECEIVEDSUM=$row['cnt'];
				$CHAT_RECEIVED_I=$row['cnt'];
				$CHAT_RECEIVED_D=0;
				$CHAT_RECEIVED_A=0;
				$sql="SELECT SENDER,STATUS FROM userplane.LOG_AD WHERE RECEIVER='$profileid' ORDER BY TIMEOFINSERTION desc";
				$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				$chat_arr=Array();
				while($row=mysql_fetch_array($result))
				{
					if(!in_array($row['SENDER'],$chat_arr))
                                        {
                                                $chat_arr[]=$row['SENDER'];
                                        	if($row['STATUS']=='a')
							$CHAT_RECEIVED_A++;
						else
							$CHAT_RECEIVED_D++;
					}
				}
				unset($chat_arr);

				$CHAT_RECEIVEDSUM=$CHAT_RECEIVED_I+$CHAT_RECEIVED_A+$CHAT_RECEIVED_D;
                                $smarty->assign("CHAT_RECEIVEDSUM",$CHAT_RECEIVEDSUM);
                                $smarty->assign("CHAT_RECEIVED_I",$CHAT_RECEIVED_I);
                                $smarty->assign("CHAT_RECEIVED_D",$CHAT_RECEIVED_D);
                                $smarty->assign("CHAT_RECEIVED_A",$CHAT_RECEIVED_A);
				
				//$sql="SELECT COUNT(DISTINCT RECEIVER) as cnt FROM userplane.CHAT_REQUESTS WHERE SENDER = '$profileid'";
				$sql="SELECT count(DISTINCT C.RECEIVER) as cnt FROM userplane.CHAT_REQUESTS AS C LEFT JOIN userplane.LOG_AD AS L ON C.SENDER = L.SENDER AND C.RECEIVER = L.RECEIVER WHERE C.SENDER ='$profileid' AND L.RECEIVER IS NULL";
                                $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                                $row=mysql_fetch_array($result);
                                //$CHAT_MADESUM=$row['cnt'];
				$CHAT_MADE_I=$row['cnt'];
				$CHAT_MADE_D=0;
				$CHAT_MADE_A=0;
                                $sql="SELECT RECEIVER,STATUS FROM userplane.LOG_AD WHERE SENDER='$profileid' ORDER BY TIMEOFINSERTION desc";
                                $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                                $chat_arr2=Array();
                                while($row=mysql_fetch_array($result))
                                {
					if(!in_array($row['RECEIVER'],$chat_arr2))
                                        {
                                                $chat_arr2[]=$row['RECEIVER'];
                                                if($row['STATUS']=='a')
                                                        $CHAT_MADE_A++;
                                                else
                                                        $CHAT_MADE_D++;
                                        }
                                }
				unset($chat_arr2);
                                $CHAT_MADESUM=$CHAT_MADE_I+$CHAT_MADE_A+$CHAT_MADE_D;
                                $smarty->assign("CHAT_MADESUM",$CHAT_MADESUM);
                                $smarty->assign("CHAT_MADE_I",$CHAT_MADE_I);
                                $smarty->assign("CHAT_MADE_D",$CHAT_MADE_D);
                                $smarty->assign("CHAT_MADE_A",$CHAT_MADE_A);
				/*CODE ends ADDED BY PUNEETMAKKAR on feb 13 2006 for chat details*/
		
				if($showChat)
				{
					// variable to display the chat popup only when the person logs in
					$smarty->assign("CHATPOPUP","1");
					$smarty->assign("CHATUNIQID",uniqid("C"));
				}

				$mem1_rights=explode(",",$data["SUBSCRIPTION"]);
				if(!in_array("F",$mem1_rights))
				{
					$smarty->assign("FREE_MEM","Y");
				}	
				
				$smarty->assign("NAME",$data["USERNAME"]);
				
				$smarty->assign("GENDER",$gender);
				$smarty->assign("MEMBERSHIP",$membership);
				$smarty->assign("SUBSCRIPTION",$subscription);
                                $smarty->assign("ADDON_MEMBERSHIP",$addon_membership);
                                $smarty->assign("ADDON_SUBSCRIPTION",$addon_subscription);
				$smarty->assign("SHOWEXPIRY",$show_expiry);
				$smarty->assign("SSEXPIRYDT",$ssexpiry_dt);
				$smarty->assign("CHECKSUM",$checksum);
				
				if($lang)
				{
				       $smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
				       $smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
				       $smarty->assign("SUBFOOTER",$smarty->fetch($lang."_subfooternew.htm"));
				       $smarty->assign("LEFTPANEL",$smarty->fetch($lang."_leftpanelnew.htm"));
				       $smarty->assign("RIGHTPANEL",$smarty->fetch($lang."_rightpanel.htm"));
				}
				else
				{
				       $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
				       $smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
				       $smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
				       $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
				       $smarty->assign("RIGHTPANEL",$smarty->fetch("rightpanel.htm"));
				}
				
				$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
				$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));

				$smarty->assign("source",$source);
				$smarty->assign("USERNAME",$data["USERNAME"]);
				$smarty->display("stats.htm");
			}
		}
	}
	else 
	{
		// added to remove naukri banner from login page
		$smarty->assign("NOBOTTOMBANNER","1");
		$smarty->assign("CAME_FROM_NORMAL_LOGIN","1");
		$smarty->assign("SEARCHONLINE",$searchonline);

		login_register();

		if($lang)
		{
			$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
			$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch($lang."_subfooternew.htm"));
			$smarty->display($lang."_login_register.htm");
		}
		else
		{
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		        $smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		        $smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
                        $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));

			$smarty->display("login_register.htm");
		}
	}
?>
