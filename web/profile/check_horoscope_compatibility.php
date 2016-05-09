<?php

	//this script is used to find all the details of both the people from ASTRO_DETAILS table and pass them to htm 
	//also update the HOROSCOPE_COMPATIBILITY table with profileid's, date and MTONGUE of other person

        include_once("connect.inc");
        $db=connect_db();
	//Added by Vibhor for Astro Service of Offline Module
	if(!$via_ofm)
                $data=authenticated($checksum);
        if(MobileCommon::isDesktop() && !$data){
            header("Location:".$SITE_URL."/myjs/jspcPerform");die;
        }
	if(($data)||($via_ofm))
	//end
        {
		if($data["SOURCE"]=="ofl_prof")
                {
			$sql1="SELECT OPERATOR FROM jsadmin.OFFLINE_ASSIGNED WHERE PROFILEID='$profileid'";
		        $res1= mysql_query_decide($sql1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql1,"ShowErrTemplate");
		        $row=mysql_fetch_array($res1);
		        $op=$row["OPERATOR"];

		        $sql2="SELECT PHONE FROM jsadmin.PSWRDS WHERE USERNAME='$op'";
		        $res2= mysql_query_decide($sql2) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql2,"ShowErrTemplate");
		        $row2=mysql_fetch_array($res2);
		        $ph= $row2["PHONE"];
		        if($ph)
		                $error_msg="You cannot make online contacts. Please contact $op at $ph";
		        else
		                $error_msg="You cannot make online contacts. Please contact $op";
                        $smarty->assign("profilechecksum",$profilechecksum);
	                $smarty->assign("msg",$error_msg);
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                        $smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
                        $smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
                        $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));

			$smarty->display("horoscope_compatibility_error.htm");
                        exit;
                }

		login_relogin_auth($data);
		$smarty->assign("CHECKSUM",$checksum);

		$chkprofilechecksum=explode("i",$profilechecksum);
		//Added by Vibhor for Astro Service of Offline Module
                if(!$via_ofm)
			$profileid=$data['PROFILEID'];
		//end
		$profileid_other=$chkprofilechecksum[1]; //profileid of other person with whom logged in person is checking compatibility
		if(strstr($data['SUBSCRIPTION'],'A'))
			$sample="";
		
		if(!check_astro_details($profileid))
		{
			$no_astro_details=1;	
		}

		//if sample page it to be shown to the user which shows the benefits of compatibility
		if($sample==1)
		{
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
			$smarty->display("horoscope_compatibility_landing.htm");
		}
		else if($no_astro_details==1)
		{
			$smarty->display("horoscope_compatibility_popup.htm");
		}
		else
		{
			//if the person logged in has the same GENDER as that of whom he is matching astro compatibility
			$sql_gender = "SELECT GENDER FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid_other'";
			$result_gender=mysql_query_decide($sql_gender) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_gender,"ShowErrTemplate");
			$row_gender = mysql_fetch_array($result_gender);
			if($data['GENDER']==$row_gender['GENDER'])
			{
				$msg="Astro Compatibility cannot be matched with the same gender";
				$smarty->assign("msg",$msg);
				$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
				$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
				$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
				$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
				$smarty->display("horoscope_compatibility_error.htm");
			}
			else
			{
				//here first check if the person has taken the compatibility membership or not
				if((!strstr($data['SUBSCRIPTION'],'A'))&&($compatibility_subscription != '1'))
				{
					$smarty->assign("COMPATIBILITY_SUBSCRIPTION",'N');	
				}
				else
				{
					//find USERNAME,MTONGUE of other person
					$sql = "SELECT USERNAME FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid_other'";
					$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
					$row = mysql_fetch_array($result);

					//find astro details of logged in person and astro details of other person
					astro_details($profileid,$profileid_other);

					//find astro details of other person
					//astro_details($profileid_other);

					//call the function to save values in HOROSCOPE_COMPATIBILITY table
					if($log!='N')
						horoscope_compatibility_log($profileid,$profileid_other);
					
					//Added by Vibhor for Astro Service of Offline Module
					if($via_ofm)
					{
						$sql_ofm = "SELECT USERNAME,GENDER FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
	                                        $result_ofm=mysql_query_decide($sql_ofm) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_ofm,"ShowErrTemplate");
                                        	$row_ofm = mysql_fetch_array($result_ofm);
						$smarty->assign("USERNAME_LOGGED_IN",$row_ofm['USERNAME']);
						$smarty->assign("GENDER_LOGGED_IN",$row_ofm['GENDER']);
						$smarty->assign("via_ofm",$via_ofm);
					}
					else
					{
						$smarty->assign("USERNAME_LOGGED_IN",$data['USERNAME']);
						$smarty->assign("GENDER_LOGGED_IN",$data['GENDER']);
					}
					//end
					$smarty->assign("USERNAME_OTHER",$row['USERNAME']);
					/*
					//check the SUBSCRIPTION of the person if he has taken COMPATIBILITY membership or not

					if(strstr($data['SUBSCRIPTION'],'C'))
						$smarty->assign("COMPATIBILITY_SUBSCRIPTION",'Y');
					*/
				}
				$smarty->display("check_horoscope_compatibility.htm");	
			}
		}
	}
	else
	{
		Timedout();
	}
	function astro_details($profileid,$profileid_other)
	{
		global $smarty;
                $sql = "SELECT * FROM newjs.ASTRO_DETAILS WHERE PROFILEID='$profileid'";
                $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $row = mysql_fetch_array($result);
		//send m_UserName:m_Moon_Degrees_Full:m_Mars_Degrees_Full:m_Venus_Degrees_Full:m_Lagna_Degrees_Full:f_Moon_Degrees_Full:f_Mars_Degrees_Full:f_Venus_Degrees_Full:f_Lagna_Degrees_Full:f_UserName to htm 
		$astrodata['MOON_DEGREES_FULL'] = $row['MOON_DEGREES_FULL'];
		$astrodata['MARS_DEGREES_FULL'] = $row['MARS_DEGREES_FULL'];
		$astrodata['VENUS_DEGREES_FULL'] = $row['VENUS_DEGREES_FULL'];
		$astrodata['LAGNA_DEGREES_FULL'] = $row['LAGNA_DEGREES_FULL'];
		$smarty->assign("astrodata",$astrodata);

		unset($astrodata);
		
                $sql_other = "SELECT * FROM newjs.ASTRO_DETAILS WHERE PROFILEID='$profileid_other'";
                $result_other=mysql_query_decide($sql_other) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_other,"ShowErrTemplate");
                $row_other = mysql_fetch_array($result_other);
		//send m_UserName:m_Moon_Degrees_Full:m_Mars_Degrees_Full:m_Venus_Degrees_Full:m_Lagna_Degrees_Full:f_Moon_Degrees_Full:f_Mars_Degrees_Full:f_Venus_Degrees_Full:f_Lagna_Degrees_Full:f_UserName to htm 
		$astrodata_other['MOON_DEGREES_FULL'] = $row_other['MOON_DEGREES_FULL'];
		$astrodata_other['MARS_DEGREES_FULL'] = $row_other['MARS_DEGREES_FULL'];
		$astrodata_other['VENUS_DEGREES_FULL'] = $row_other['VENUS_DEGREES_FULL'];
		$astrodata_other['LAGNA_DEGREES_FULL'] = $row_other['LAGNA_DEGREES_FULL'];
		$smarty->assign("astrodata_other",$astrodata_other);
		unset($astrodata_other);
	}
        //function to save values in HOROSCOPE_COMPATIBILITY table
        function horoscope_compatibility_log($profileid,$profileid_other)
	{
		$sql="REPLACE into HOROSCOPE_COMPATIBILITY(PROFILEID,PROFILEID_OTHER,DATE) values ('$profileid','$profileid_other',now()) ";
                mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	}

?>
