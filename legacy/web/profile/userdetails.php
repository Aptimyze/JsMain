<?php
/**********************************************************************************************
  FILENAME    : userdetails.php
  DESCRIPTION : Ask the user to edit some personal data once after this script is live. 
  INCLUDE     : connect.inc,flag.php
  CREATED BY  : Lavesh Rawat
  CREATED ON  : 5 May,2006
**********************************************************************************************/
include_once("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");
$msg = print_r($_SERVER,true);
mail("kunal.test02@gmail.com","userdetails.php in USE",$msg);
$db=connect_db();

$data=authenticated($checksum);

//Bms code
$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("bms_topright",18);
$smarty->assign("bms_right",28);
$smarty->assign("bms_bottom",19);
$smarty->assign("bms_left",24);
$smarty->assign("bms_new_win",32);
//Ends here.

if($data)
{
	$today=date("Y-m-d");
	login_relogin_auth($data);
	$profileid=$data['PROFILEID'];

	$smarty->assign("CHECKSUM",$checksum);
	//$submitted is when we want to update info.

	if(!$submitted)
	{
		$sql="REPLACE INTO newjs.USERDETAILS_PROFILES VALUES('',$profileid,now())";
		$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	}


	if($submitted)
	//If userdetail page is submitted
	{
		//Self address contact cant be empty.
		if(trim($contact)=="")
		{
			$is_error++;
			$smarty->assign("contact_msg","Field cannot be empty");
			$smarty->assign("check_address","Y");
		}

		//Person Name can only only be alphabets and can include space.
		if(trim($Name))
		{
			if (!preg_match("/^[a-z ]+$/i",$Name))
			{
				$is_error++;
				$smarty->assign("name_error",'Y');
				$smarty->assign("name_msg","Only alphabets allowed.");
			}
		}

		$country=$Country_Residence;

		if(!$country=='')
		{
			$Country_Code=get_code('COUNTRY',$country);
			$city_res=$City_India;
		}
		
		//State code is shown only for indian users.
		if($country=='51')
			$State_Code=get_code('CITY_INDIA',$city_res);
												     
		$check_country_code=0;
		$check_country_code_mob=0;
		$check_state_code=0;

		if($phone_res!='')
			$check_country_code=checkrphone($Country_Code);
		if($country==51)
			$check_state_code=checkrphone($State_Code);
												     
		if($check_state_code==1)
		{
			$is_error++;
			$smarty->assign("check_phone",'Y');
			$smarty->assign("phone_msg","State Code  has invalid characters");
		}
												     
		if($check_country_code==1)
		{
			$is_error++;
			$smarty->assign("check_phone",'Y');
			$smarty->assign("phone_msg","Country Code  has invalid characters");
		}

		if(trim($phone_res)=="" && trim($phone_mob)=="")
		{
			$is_error++;
			$smarty->assign("check_phone","Y");
			$smarty->assign("phone_msg","Please fill one of the two phone numbers.");
		}
		else
		{
			if(trim($phone_res)!='')
			{
				if(checkrphone($phone_res))
				{
					if(!trim($phone_res)=="")
					{
						$is_error++;
						$smarty->assign("check_phone","Y");
						$smarty->assign("phone_msg","Phone no. has invalid characters");
					}
				}
			}

			if(trim($phone_mob)!='')
			{
				if(checkmphone($phone_mob)&&(!trim($phone_mob)==""))
				{
					$phone_error=1;
					$is_error++;
					$smarty->assign("check_mob","Y");
					$smarty->assign("mob_msg","Mobile no. has invalid characters");
				}
					
				if((redo_mobile_no($phone_mob)=='')&&($phone_mob!=''))
				{
					if(!$phone_error)
					{
						$is_error++;
						$smarty->assign("check_mob","Y");
						$smarty->assign("mob_msg","Mobile no. Must be atleast of 6 characters");
					}
				}
			}
		}

		if($country=="")
		{
			$is_error++;
			$smarty->assign("check_countryres","Y");
		}
		elseif($country=="51")
		{
			if($City_India == "")
			{
				$is_error++;
				$smarty->assign("check_cityres","Y");
			}
			else
				$city_res = $City_India;
		}
		elseif($country =="128")
		{
			if($City_USA == "")
			{
				$is_error++;
				$smarty->assign("check_cityres","Y");
			}
			else
				$city_res = $City_USA;
		}
		else
		{
			$city_res = "";
		}

		$smarty->assign("tick_checkbox",$show_others);

		if($is_error > 0)
		{
			//list country and associated phone code.
	                $ccc=create_code("COUNTRY");
                                                                                                                             
        	        //list city and associated phone code.
                	$csc=create_code("CITY_INDIA");
                                                                                                                             
	                $smarty->assign("country_isd_code",$ccc);
        	        $smarty->assign("india_std_code",$csc);

			$smarty->assign("NO_OF_ERROR",$is_error);
			$smarty->assign("COUNTRY_RES",create_dd($country,"Country_Residence"));
			$smarty->assign("CITY_INDIA",create_dd($city_res,"City_India"));
			$smarty->assign("CITY_USA",create_dd($city_res,"City_USA"));
			$smarty->assign("contact",$contact);
			$smarty->assign("parent_contact",$parent_contact);
			$smarty->assign("phone_res",$phone_res);
			$smarty->assign("phone_mob",$phone_mob);
			$smarty->assign("Name",$Name);
			$smarty->assign("checksum",$checksum);
			$smarty->assign("State_Code",$State_Code);
			$smarty->assign("Country_Code",$Country_Code);
			$smarty->assign("invalid_phone",$invalid_phone);
			$smarty->assign("head_tab",'my jeevansathi');
			$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
			$smarty->assign("case",$case);
			$smarty->display("userdetails.htm");
			exit;
		}
		else
		{
			//For checking screening for which data item is required.
			$sql="select CONTACT,PARENTS_CONTACT,PHONE_RES,PHONE_MOB,SCREENING from JPROFILE where PROFILEID='$profileid'";
                        $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			$row=mysql_fetch_array($result);
			
			 $curflag=$row["SCREENING"];
			
			if(!$contact)
				$curflag=setFlag("CONTACT",$curflag);
			elseif($row["CONTACT"]!=$contact)
                        	$curflag=removeFlag("CONTACT",$curflag);
			
			if(!$parent_contact)
				$curflag=setFlag("PARENTS_CONTACT",$curflag);
			elseif($row["PARENTS_CONTACT"]!=$parent_contact)
				$curflag=removeFlag("PARENTS_CONTACT",$curflag);

			$phone_updated=0;//If mobile/phone no. is updated.
			if(!$phone_res)
				$curflag=setFlag("PHONERES",$curflag);
			elseif($row["PHONE_RES"]!=$phone_res)
			{
                                $curflag=removeFlag("PHONERES",$curflag);	
				$phone_updated=1;
			}

			if(!$phone_mob)
				$curflag==setFlag("PHONEMOB",$curflag);
			elseif($row["PHONE_MOB"]!=$phone_mob)
			{
                                $curflag=removeFlag("PHONEMOB",$curflag);
				$phone_updated=1;
			}

			$sql="select NAME from newjs.PROFILE_NAME where PROFILEID='$profileid'";
			$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			$row=mysql_fetch_array($result);

			if($row["NAME"]!=$Name)
				$screen_name='Y';
			else
				$screen_name='N';	

			if($show_others)
				$show_others='N';
			else
				$show_others='Y';

			 // code added by neha for archiving contact information
			$contact = trim($contact);
                        $parent_contact = trim($parent_contact);
			$date_now=date("Y-m-d H:i:s");
                       	$ip=FetchClientIP();//Gets ipaddress of user
                        if(strstr($ip, ","))
                        {
                        	$ip_new = explode(",",$ip);
                                $ip = $ip_new[1];
                        }
			$sql_sel= "SELECT PARENTS_CONTACT,STD,ISD, CONTACT, PHONE_RES, PHONE_MOB, MESSENGER_ID, MESSENGER_CHANNEL FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
                        $res_sel= mysql_query_decide($sql_sel) or die(mysql_error_js());
                        $row_sel= mysql_fetch_assoc($res_sel);
	                $arr_fields=array(0=>"PARENTS_CONTACT,".$parent_contact,
                                          1=>"CONTACT,$contact",
                                          2=>"PHONE_RES,".$Country_Code."-".$State_Code."-".$phone_res,
                                          3=>"PHONE_MOB,".$Country_Code."-".$phone_mob);
			foreach($arr_fields as $key=>$value)
                        {
				$archive=0;
                        	$val1=explode(',',$value);
                                $field=array_shift($val1);
                                $val=implode(',',$val1);

				if($field=="PHONE_RES")
                                {
                                	if($row_sel['PHONE_RES']==$phone_res && $phone_res=='');
                                        else
                                        {
                                        	$ph_row=$row_sel['ISD']."-".$row_sel['STD']."-".$row_sel['PHONE_RES'];
                                                if($ph_row!=$val)
                                                {
                                                	$archive=1;
                                                        $ph_arr=explode("-",$val);
                                                        if($ph_arr[2]=='')
                                                        	$val='';
                                                }
					}
				}
                                elseif($field=="PHONE_MOB")
                                {
                                	if(($row_sel['PHONE_MOB']==$phone_mob) && ($phone_mob==''));
                                        else
                                        {
                                        	$mob_row=$row_sel['ISD']."-".$row_sel['PHONE_MOB'];
                                                if($mob_row!=$val)
                                                {
                                                	$archive=1;
                                                        $mob_arr=explode("-",$val);
                                                        if($mob_arr[1]=='')
                                                        	$val='';
                                       		}
					
					}
                                }
                        	else
                                {
                                	if($row_sel[$field]!=$val)
                                        	$archive=1;
                                }
                                if($archive)
                                {
					 if($field=="PHONE_RES")
					 {
						if($row_sel['PHONE_RES']!='')
	                                         $old_val=$row_sel['ISD']."-".$row_sel['STD']."-".$row_sel['PHONE_RES'];
						else
						 $old_val='';
					 }
                                         elseif($field=="PHONE_MOB")
					 {
						if($row_sel['PHONE_MOB']!='')
                                                 $old_val=$row_sel['ISD']."-".$row_sel['PHONE_MOB'];
						else
						 $old_val='';
					 }
                                         else
                                                 $old_val=$row_sel[$field];
	                                $sql_search="SELECT CHANGEID FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID='$profileid' AND FIELD='$field'";
         	                        $res_search=mysql_query_decide($sql_search) or die(mysql_error_js());
					if(mysql_num_rows($res_search)>0)
                                        {
	                                        $old_val=addslashes(stripslashes($old_val));
                                                $val=addslashes(stripslashes($val));
                                                $row_search=mysql_fetch_assoc($res_search);
                                                $changeid=$row_search['CHANGEID'];
                                                $sql_add= "INSERT INTO CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,OLD_VAL,NEW_VAL) VALUES($changeid,'$date_now','$ip','$old_val','$val') ";
                                                $res_add= mysql_query_decide($sql_add) or die(mysql_error_js());
                                         }
                                         else
                                         {
        	                                $sql_insert= "INSERT INTO CONTACT_ARCHIVE(PROFILEID,FIELD) VALUES($profileid,'$field')";
                                                $res_insert= mysql_query_decide($sql_insert) or die(mysql_error_js());
                                                $sql_search="SELECT CHANGEID FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID='$profileid' AND FIELD='$field'";
                	                        $res_search=mysql_query_decide($sql_search) or die(mysql_error_js());
                                                $row_search=mysql_fetch_assoc($res_search);
                                                $changeid=$row_search['CHANGEID'];
		                                $sql_add= "INSERT INTO CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,OLD_VAL,NEW_VAL) VALUES($changeid,'$date_now','$ip','$old_val','$val') ";
                                                $res_add= mysql_query_decide($sql_add) or die(mysql_error_js());
                                          }
				}
			}
                        //end of code added by neha

			$parent_contact=addslashes(stripslashes($parent_contact));
			$contact=addslashes(stripslashes($contact));
			
			$phone_with_std=$State_Code.$phone_res;

			if($phone_res==""){
				$phone_with_std="";
				$State_Code="";
			}

			//Reflect changes in JPROFILE table.
			$sql = "UPDATE newjs.JPROFILE set CONTACT='$contact',COUNTRY_RES='$country',CITY_RES='$city_res',PARENTS_CONTACT='$parent_contact',PHONE_RES='$phone_res',PHONE_MOB='$phone_mob',STD='$State_Code',PHONE_WITH_STD='$phone_with_std',ISD='$Country_Code',SHOWPHONE_RES='$show_others',SHOWPHONE_MOB='$show_others',SCREENING='$curflag',LAST_LOGIN_DT='$today',MOD_DT=now() where PROFILEID='$profileid'";

			$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

			//Reflect changes in newjs.PROFILE_NAME table if he change his profilename.
			//If he enter for the 1st time then enter profilename.
			$sql="select count(*) as cnt from newjs.PROFILE_NAME where PROFILEID='$profileid'";
			$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			$row=mysql_fetch_array($result);

			if($row['cnt']>0)
			{	
				if(!(trim($Name)==''))
				{
					if($screen_name=='Y')	
				
						$sql= "UPDATE newjs.PROFILE_NAME set NAME='$Name',SCREENING='Y' where PROFILEID='$profileid'";
					else
						$sql= "UPDATE newjs.PROFILE_NAME set NAME='$Name' where PROFILEID='$profileid'";
				}
				else
					$sql= "DELETE from newjs.PROFILE_NAME where PROFILEID='$profileid'";

				$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			}
			else
			{
				if(!(trim($Name)==''))
				{

					$sql= "INSERT INTO newjs.PROFILE_NAME VALUES ('','$profileid','$Name','Y')";
					$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
				}
			}

			//Reflect changes in incentive.MAIN_ADMIN_POOL and delete entry from incentive.INVALID_PHONE ,If having entry in incentive.INVALID_PHONE.
			$cookie_value=$_COOKIE['INVALID_PHONE'];
                        settype($cookie_value,"integer");
                        if($cookie_value!=2)
			{	
				if($invalid_phone)
				{
					
					$sql="SELECT count(*) as cnt from incentive.MAIN_ADMIN where PROFILEID='$profileid'";
					$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
					$row=mysql_fetch_array($result);
					$sql="UPDATE incentive.MAIN_ADMIN_POOL set TIMES_TRIED=0";
					if($row['cnt']==0)
						$sql.=",ALLOTMENT_AVAIL='Y'";
					$sql.=" where PROFILEID='$profileid'";
					$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

					$sql=" DELETE FROM incentive.INVALID_PHONE  where PROFILEID='$profileid'";
					$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			
					$sql=" DELETE FROM newjs.INVALID_PHONE_MAILER where PROFILEID='$profileid'";
					$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
				}

				$sql= "UPDATE MIS.USERDETAILS set COUNT=COUNT+1 ";
				if($phone_updated)
					$sql.=",PHONE_MOB_UPDATE=PHONE_MOB_UPDATE+1";	
				$sql.=" where ENTRY_DATE='$today'";
				mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
				if(!mysql_affected_rows_js())
				{
					$sql= "INSERT INTO MIS.USERDETAILS VALUES('','','1','$phone_updated','$today')";
					mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
				}
				setcookie("INVALID_PHONE","2",0,"/",$domain);
			}
			

			echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/mainmenu.php?CHECKSUM=$checksum\"></body></html>";
                        exit;
		}
	}

	//$submit_skip is ehen we r skipping the page.
	/*elseif($submit_skip)
	{
		if($invalid_phone)
		{
			$sql="SELECT count(*) as cnt from incentive.MAIN_ADMIN where PROFILEID='$profileid'";
			$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			$row=mysql_fetch_array($result);
			if($row['cnt']>0)
			{
				$sql="UPDATE incentive.MAIN_ADMIN_POOL set ALLOTMENT_AVAIL='Y',TIMES_TRIED='0' where PROFILEID='$profileid'";
				$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			}
														     
			$sql=" DELETE FROM incentive.INVALID_PHONE  where PROFILEID='$profileid'";
			$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}

		echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/mainmenu.php?CHECKSUM=$checksum\"></body></html>";
                exit;
	}*/	

	else
        //If userdetail page is not submitted
        {
		if($case==3)
		{
			$case=1;
			$sql_cont_stat = "SELECT ACC_BY_ME,ACC_ME FROM CONTACTS_STATUS WHERE PROFILEID='$profileid'";
	                $res_cont_stat = mysql_query_decide($sql_cont_stat) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_cont_stat,"ShowErrTemplate");
        	        if(mysql_num_rows($res_cont_stat) > 0)
                	{
                        	$row_cont_stat = mysql_fetch_array($res_cont_stat);
	                        $accepted=$row_cont_stat['ACC_BY_ME']+$row_cont_stat['ACC_ME'];
				if($accepted>0)
					$case=2;
			}
	                mysql_free_result($res_cont_stat);
		}

		if(!$_COOKIE['INVALID_PHONE'])
		{
			if($invalid_phone=='y')
			{
				$sql="INSERT IGNORE INTO newjs.INVALID_PHONE_MAILER VALUES ('$profileid',now(),1)";
				$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			
			}

			$sql= "UPDATE MIS.USERDETAILS set PAGE_DISPLAY=PAGE_DISPLAY+1 where ENTRY_DATE='$today'";//no. of times page is displayed.
			mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			if(!mysql_affected_rows_js())
			{
				$sql= "INSERT INTO MIS.USERDETAILS VALUES('','1','','','$today')";
				mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			}
			setcookie("INVALID_PHONE","1",0,"/",$domain);
		}

                $sql="select CONTACT,COUNTRY_RES,CITY_RES,PARENTS_CONTACT,PHONE_RES,PHONE_MOB,STD,ISD,SHOWPHONE_MOB,SCREENING from JPROFILE where PROFILEID='$profileid'";
                $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                                                                                                                             
                $row=mysql_fetch_array($result);
                                                                                                                             
                $contact=$row["CONTACT"];
                $country=$row["COUNTRY_RES"];
                $city_res=$row["CITY_RES"];
                $parent_contact=$row["PARENTS_CONTACT"];
                $phone_res=$row["PHONE_RES"];
                $phone_mob=$row["PHONE_MOB"];
                                                                                                                             
                if($row["SHOWPHONE_MOB"]=='N')
                        $smarty->assign("tick_checkbox",'1');
                                                                                                                             
                //Getting ISD code for logged in user.
                if($myrow["ISD"]=='')
                        $Country_Code=get_code('COUNTRY',$row['COUNTRY_RES']);
                else
                        $Country_Code=$row["ISD"];
                                                                                                                             
                //Getting STD code for logged in user(Indian users only).
                if($myrow["STD"]=='' && $row['COUNTRY_RES']==51)
                        $State_Code=get_code('CITY_INDIA',$row['CITY_RES']);
                else
                        $State_Code=$row["STD"];
                                                                                                                             
                $smarty->assign("Country_Code",$Country_Code);
                $smarty->assign("State_Code",$State_Code);
                                                                                                                             
                //list country and associated phone code.
                $ccc=create_code("COUNTRY");
                                                                                                                             
                //list city and associated phone code.
                $csc=create_code("CITY_INDIA");
                                                                                                                             
                $smarty->assign("country_isd_code",$ccc);
                $smarty->assign("india_std_code",$csc);
                                                                                                                             
                $sql="select NAME from newjs.PROFILE_NAME where PROFILEID='$profileid'";
                $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                $row=mysql_fetch_array($result);
                $Name=$row["NAME"];
                                                                                                                             
                //List all countries with $country as selected.
                $smarty->assign("COUNTRY_RES",create_dd($country,"Country_Residence"));
                //List all cities(india/usa) with $city_res as selected.
                $smarty->assign("CITY_INDIA",create_dd($city_res,"City_India"));
                $smarty->assign("CITY_USA",create_dd($city_res,"City_USA"));
                                                                                                                             
                $smarty->assign("contact",$contact);
                $smarty->assign("parent_contact",$parent_contact);
                $smarty->assign("phone_res",$phone_res);
                $smarty->assign("phone_mob",$phone_mob);
                $smarty->assign("Name",$Name);
                $smarty->assign("checksum",$checksum);
                $smarty->assign("invalid_phone",$invalid_phone);
		$smarty->assign("head_tab",'my jeevansathi');
                $smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
                $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
		$smarty->assign("case",$case);
                $smarty->display("userdetails.htm");
        }
}
else
{
	TimedOut();
}

function get_code($tablename,$value)
{
        $sql = "select CODE from newjs.$tablename where VALUE='$value'";
        $res = mysql_query_decide($sql) or logError("Error in getting code value",$sql);
        $myrow = mysql_fetch_array($res);
        $code=$myrow['CODE'];
        return $code;
}

?>
