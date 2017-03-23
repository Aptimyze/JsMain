<?php
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it

	include("connect.inc");	
//	include("../profile/contact.inc");
	require_once("../profile/display_result.inc");
//	$db=connect_db();
//	$self_details=authenticated_pro($checksum);

//print_r($self_details);	
/*if(authenticated($cid))
{*/
	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("FOOT",$smarty->fetch("../jeevansathi/foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("../jeevansathi/headnew.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("../jeevansathi/subfooternew.htm"));
	//$smarty->assign("SUBHEADER",$smarty->fetch("../jeevansathi/subheader.htm"));
	//$smarty->assign("TOPLEFT",$smarty->fetch("../jeevansathi/topleft.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("../jeevansathi/leftpanelnew.htm"));
	//$smarty->assign("SCRIPTNAME",$scriptname);
	//$smarty->assign("PAGENO",$pageno);
//	if($self_details)
//	{								
//		echo "spid : ".$self_profileid=$self_details["PROFILEID"];
		list($chk,$pid)=explode("i",$checksum);
		$self_profileid=$pid;

		$path=JsConstants::$docRoot;

	        include_once("$path/classes/globalVariables.Class.php");
	        include_once("$path/profile/contacts_functions.php");
        	include_once("$path/classes/Mysql.class.php");
	        include_once("$path/classes/Memcache.class.php");

        	$mysql=new Mysql;
	        $myDbName=getProfileDatabaseConnectionName($pid,'',$mysql);
        	$myDb=$mysql->connect("$myDbName");

		//if no flag is set then by default the I case is shown,i.e. members who have not responded
		//or members waiting for response
		if(!$flag)
			$flag="I";
		if($type=="M")
		{
			$self="SENDER";
			$contact="RECEIVER";
		}
		elseif($type=="R" || $type=='RC')
		{
			$self="RECEIVER";
			$contact="SENDER";
		}
		$PAGELEN=10;
		if(!$j)
			$j=0;
		
		 $sql="select USERNAME,ACTIVATED from newjs.JPROFILE where PROFILEID='$self_profileid'";
	         $result=mysql_query_decide($sql) or die("1".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$myrow_ACT=mysql_fetch_array($result);
                                                                                                                             
                if($myrow_ACT["ACTIVATED"]=="D")
                {
                        //$table_name="newjs.DELETED_PROFILE_CONTACTS";
                        $table_name="DELETED_PROFILE_CONTACTS";
                }
                else
                {
                        //$table_name="newjs.CONTACTS";
                        $table_name="CONTACTS";
                }


		//$sql="select SQL_CALC_FOUND_ROWS ".$contact.",MESSAGE,MSG_DEL,TIME,COUNT from $table_name where ".$self."='$self_profileid' and TYPE='$flag' order by TIME desc limit $j,$PAGELEN";

		if($self == "RECEIVER")
		{
			if($type == "R" && $flag == "I")
			{
				$contactResult = getResultSet("SENDER,MSG_DEL,TIME,COUNT","","","$self_profileid","","'I'","","TIME BETWEEN DATE_SUB(NOW(),INTERVAL ".CONTACTS::INTEREST_RECEIVED_UPPER_LIMIT." DAY) AND NOW()","","TIME DESC","","","$j,$PAGELEN","","$table_name","","","","'Y'");
				$count_contactResult = getResultSet("COUNT(*) AS CNT","","","$self_profileid","","'I'","","TIME BETWEEN DATE_SUB(NOW(),INTERVAL ".CONTACTS::INTEREST_RECEIVED_UPPER_LIMIT." DAY) AND NOW()","","","","","","","$table_name","","","","'Y'");
			}
			elseif($type == "RC" && $flag=="I")
			{
				$contactResult = getResultSet("SENDER,MSG_DEL,TIME,COUNT","","","$self_profileid","","'I'","","TIME BETWEEN DATE_SUB(NOW(),INTERVAL ".CONTACTS::INTEREST_RECEIVED_UPPER_LIMIT." DAY) AND DATE_SUB(NOW(),INTERVAL 30 DAY)","","TIME DESC","","","$j,$PAGELEN","","$table_name");
				$count_contactResult = getResultSet("COUNT(*) AS CNT","","","$self_profileid","","'I'","","TIME BETWEEN DATE_SUB(NOW(),INTERVAL ".CONTACTS::INTEREST_RECEIVED_UPPER_LIMIT." DAY) AND DATE_SUB(NOW(),INTERVAL 30 DAY)","","","","","","","$table_name");
			}
			else
			{
				$contactResult = getResultSet("$contact,MSG_DEL,TIME,COUNT","","","$self_profileid","","'$flag'","","","","TIME DESC","","","$j,$PAGELEN","","$table_name");
				$count_contactResult = getResultSet("COUNT(*) AS CNT","","","$self_profileid","","'$flag'","","","","","","","","","$table_name");
			}
		}
		elseif($self == "SENDER")
		{
			if($type == "R" && $flag == "I")
			{
				$contactResult = getResultSet("SENDER,MSG_DEL,TIME,COUNT","$self_profileid","","","","'I'","","","","TIME DESC","","","$j,$PAGELEN","","$table_name");
				$count_contactResult = getResultSet("COUNT(*) AS CNT","$self_profileid","","","","'I'","","","","","","","","","$table_name");
			}
			elseif($type == "RC" && $flag=="I")
			{
				$contactResult = getResultSet("SENDER,MSG_DEL,TIME,COUNT","$self_profileid","","","","'I'","","TIME BETWEEN DATE_SUB(NOW(),INTERVAL ".CONTACTS::INTEREST_RECEIVED_UPPER_LIMIT." DAY) AND DATE_SUB(NOW(),INTERVAL 30 DAY)","","TIME DESC","","","$j,$PAGELEN","$table_name");
				$count_contactResult = getResultSet("COUNT(*) AS CNT","$self_profileid","","","","'I'","","TIME BETWEEN DATE_SUB(NOW(),INTERVAL ".CONTACTS::INTEREST_RECEIVED_UPPER_LIMIT." DAY) AND DATE_SUB(NOW(),INTERVAL 30 DAY)","","","","","","","$table_name");
			}
			else
			{
				$contactResult = getResultSet("$contact,MSG_DEL,TIME,COUNT","$self_profileid","","","","'$flag'","","","","TIME DESC","","","$j,$PAGELEN","$table_name");
				$count_contactResult = getResultSet("COUNT(*) AS CNT","$self_profileid","","","","'$flag'","","","","","","","","","$table_name");
			}
		}

		$MY_COUNT = count($contactResult);
		$countrow = $count_contactResult[0]['CNT'];
		
		/*$sql="select FOUND_ROWS() as cnt";
		$resultcount=mysql_query_decide($sql) or die("c2".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		
		$countrow=mysql_fetch_row($resultcount);*/
		
		if($MY_COUNT > 0)
		{
			$curcount=$j;
			//$totalrec=$countrow[0];
			$totalrec=$countrow;
			$scriptname=$_SERVER['PHP_SELF'];
			$links_to_show=10;
			if( $curcount )
				$cPage = ($curcount/$PAGELEN) + 1;
			else
				$cPage = 1;
			$checksum.="&type=$type&flag=$flag";	
			pagelink($PAGELEN,$totalrec,$cPage,$links_to_show,$checksum,$scriptname);
			$smarty->assign("RECORDCOUNT",$totalrec);
			$smarty->assign("NO_OF_PAGES",ceil($totalrec/$PAGELEN));
			$smarty->assign("CURPAGE",$cPage);
			$smarty->assign("BACK_TO_SEARCH_PAGE",$curcount);
			$smarty->assign("SCRIPTNAME",$scriptname);
		}
		else 
		{
			$smarty->assign("RECORDCOUNT","0");
			$smarty->assign("NORESULTS","1");
			$smarty->assign("NO_OF_PAGES","0");
			$smarty->assign("CURPAGE","0");
		}	
		
		//while($myrow=mysql_fetch_array($result))
		$pidShard=JsDbSharding::getShardNo($pid);
		$dbMessageLogObj=new NEWJS_MESSAGE_LOG($pidShard);
		for($i=0;$i<count($contactResult);$i++)
		{
			//section added by Gaurav on 30 May 2006 to select MESSAGE from MESSAGE_LOG table
			//Changes made by Sadaf on 10 July 2006 to select messages from MESSAGES table
			 //$sql_msg="select ID from newjs.MESSAGE_LOG where $contact='$myrow[$contact]' and $self='$self_profileid' AND IS_MSG='Y' order by ID limit 1";
			 if($self=="SENDER"){
				$sender=$self_profileid;
				$receiver=$contactResult[$i][$contact];
			}
			else{
				$receiver=$self_profileid;
				$sender=$contactResult[$i][$contact];
			}
			$result=$dbMessageLogObj->getMessageLogIDCMR($sender,$receiver);
			
			//$sql_msg="select ID from newjs.MESSAGE_LOG where $contact='".$contactResult[$i][$contact]."' and $self='$self_profileid' AND IS_MSG='Y' order by ID limit 1";
			//$result_msg=$mysql->executeQuery($sql_msg,$myDb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_msg,"ShowErrTemplate");
			//$myrow_msg=$mysql->fetchArray($result_msg);
			$msg_id=$result;
			$msg='';
			if($msg_id)
			{
				$sql_msg="SELECT MESSAGE FROM newjs.MESSAGES WHERE ID='$msg_id'";
				$result_msg=$mysql->executeQuery($sql_msg,$myDb)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_msg,"ShowErrTemplate");
				$myrow_msg=$mysql->fetchArray($result_msg);
				$msg=$myrow_msg['MESSAGE'];
			}
			//end of changes by Sadaf	
			//end of section added by Gaurav on 30 May 2006 to select MESSAGE from MESSAGE_LOG table

			//$contact_details=get_profile_details($myrow[$contact]);
			$contact_details=get_profile_details($contactResult[$i][$contact]);
			//Determine whether this user has a photo
			//$sql1="select HAVEPHOTO from newjs.JPROFILE where PROFILEID='$myrow[$contact]'";
			$sql1="select HAVEPHOTO from newjs.JPROFILE where PROFILEID='$contactResult[$i][$contact]'";
			$result1=mysql_query_decide($sql1) or die("c3".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql1,"ShowErrTemplate");
			$myrow1=mysql_fetch_array($result1);
			if($myrow1["HAVEPHOTO"]=="Y")
				$photo=1;
			else 
				$photo=0;
			//Determine whether this user has been bookmarked  
			//$sql2="select count(*) as count from newjs.BOOKMARKS where BOOKMARKER='$self_profileid' and BOOKMARKEE='$myrow[$contact]'";		
			$sql2="select count(*) as count from newjs.BOOKMARKS where BOOKMARKER='$self_profileid' and BOOKMARKEE='$contactResult[$i][$contact]'";		
			$result2=mysql_query_decide($sql2) or die("c4".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql2,"ShowErrTemplate");
			$myrow2=mysql_fetch_array($result2);
			if($myrow2["count"]>0)
				$bookmark=1;
			else
				$bookmark=0;
			//Get the contact date in the desired format	
			//$date_arr=explode("-",substr($myrow["TIME"],0,10));
			$date_arr=explode("-",substr($contactResult[$i]["TIME"],0,10));
			$date_display=my_format_date($date_arr[2],$date_arr[1],$date_arr[0]);
			
			/*if($flag!="I")
				$seemessage=nl2br($myrow["MESSAGE"]);
			else 
				$seemessage="";*/
				
			//if($myrow["COUNT"] <=0)
			if($contactResult[$i]["COUNT"] <=0)
				$contactcount=0;
			else 
				$contactcount=($contactResult[$i]["COUNT"] - 1);
				//$contactcount=($myrow["COUNT"] - 1);

			//if($myrow["MSG_DEL"] == "D")
			if($contactResult[$i]["MSG_DEL"] == "D")
			{
                                $message = "";
                        }
                        else
                                $message = nl2br($msg);
                                //$message = nl2br($myrow["MESSAGE"]);
                        	
			$contacts[]=array( "NAME" =>$contact_details["NAME"],
					"AGE" => $contact_details["AGE"],
					"HEIGHT" => $contact_details["HEIGHT"],
					"CASTE" => $contact_details["CASTE"],
					"OCCUPATION" => $contact_details["OCCUPATION"],
					"RESIDENCE" => $contact_details["RESIDENCE"],
					//"PROFILECHECKSUM" => md5($myrow[$contact])."i".$myrow[$contact],
					"PROFILECHECKSUM" => md5($contactResult[$i][$contact])."i".$contactResult[$i][$contact],
					"MESSAGE" => $message,
					"TIME" =>$date_display,
					"PHOTO" =>$photo,
					"BOOKMARK" =>$bookmark,
					"COUNT" =>$contactcount);
		}		
		$smarty->assign("CONTACT_TYPE",$type.$flag);
		$smarty->assign("TYPE",$type);
		$smarty->assign("CONTACTS_ARR",$contacts);				
		$smarty->display("contacts_made_received.htm");
//	}
//	else 
//	{
//		Timedout();
//	}

        //function to get the profile details such as username,age etc. that are to be displayed while showing contacts made or received.
        function get_profile_details($profileid)
        {
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");

                $sql="select USERNAME,AGE,HEIGHT,CASTE,OCCUPATION,COUNTRY_RES,CITY_RES from newjs.JPROFILE where PROFILEID='$profileid'";
                $result=mysql_query_decide($sql) or die("cp1".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate") ;
                $myrow=mysql_fetch_array($result);
                $country_code=$myrow["COUNTRY_RES"];
                if($country_code==128)
                {
                        /*$temp=label_select("CITY_USA",$myrow["CITY_RES"]);
                        $city=$temp[0];*/
                        $city=$myrow["CITY_RES"];
                        $city=$CITY_USA_DROP["$city"];
                        $country="USA";
                        if($city!="")
                                $residence=$city.", ".$country;
                        else
                                $residence=$country;
                }
                elseif ($country_code==51)
                {
                        /*$temp=label_select("CITY_INDIA",$myrow["CITY_RES"]);
                        $city=$temp[0];*/
                        $city=$myrow["CITY_RES"];
                        $city=$CITY_INDIA_DROP["$city"];
                        $country="India";
                        if($city!="")
                                $residence=$city.", ".$country;
                        else
                                $residence=$country;
                }
                else
                {
                        /*$sql_country="select SQL_CACHE LABEL from COUNTRY where VALUE='$country_code'";
                        $result_country=mysql_query_decide($sql_country) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_country,"ShowErrTemplate") ;
                        $myrow_country=mysql_fetch_array($result_country);
                        $residence=$myrow_country["LABEL"];*/
                        $residence=$COUNTRY_DROP["$country_code"];
                }

                //$caste=label_select("CASTE",$myrow["CASTE"]);
                $caste=$myrow["CASTE"];
                $caste=$CASTE_DROP["$caste"];

                $caste1=explode(":",$caste);

                if(trim($caste1[1])=="")
                        $mycaste=$caste1[0];
                else
                        $mycaste=$caste1[1];

                //$height=label_select("HEIGHT",$myrow["HEIGHT"]);
                $height=$myrow["HEIGHT"];
                $height=$HEIGHT_DROP["$height"];

                $height1=explode("(",$height);
                //$occupation=label_select("OCCUPATION",$myrow["OCCUPATION"]);
                $occupation=$myrow["OCCUPATION"];
                $occupation=$OCCUPATION_DROP["$occupation"];

                $profile_details=array ( "NAME" => $myrow["USERNAME"],
                                                                        "AGE" => $myrow["AGE"],
                                                                        "HEIGHT" => $height1[0],
                                                                        "CASTE" => $mycaste,
                                                                        "OCCUPATION" => $occupation,
                                                                        "RESIDENCE" => $residence
                                                                );
                return $profile_details;
        }
/*}
else
{
        $msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}*/

	
	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
