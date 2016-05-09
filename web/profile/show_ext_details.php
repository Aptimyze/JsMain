<?php
	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it
	include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
	$db=connect_db();
        $data=authenticated();
	
	//$profileid ="385575";
	if($data && $profileid)
	{
		$sql ="SELECT * FROM Assisted_Product.AP_EFORM_DETAILS WHERE `PROFILEID`='$profileid'";
		$res = mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");	
	        while($row =mysql_fetch_array($res))
        	{
        	        $fname                  =$row['FNAME'];
        	        $lname                  =$row['LNAME'];
        	        $fname_contact          =$row['FNAME_CONTACT'];
        	        $lname_contact          =$row['LNAME_CONTACT'];
        	        $date_of_birth          =$row['DOB'];
        	        $occupation             =$row['OCCUPATION'];
        	        $company                =$row['COMPANY'];
        	        $degree                 =$row['EDU_LEVEL'];
        	        $college                =$row['COLLEGE'];
        	        $manglik_val            =$row['MANGLIK'];
        	        $rel_val 	        =$row['RELATION'];
        	        $family_info            =$row['FAMILY_INFO'];
        	        $city_location          =$row['CITY_LOC'];
        	        $city_loc_other         =$row['CITY_LOC_OTHER'];
        	        $city_birth             =$row['CITY_BIRTH'];
        	        $city_birth_other       =$row['CITY_BIRTH_OTHER'];
        	        $city_native            =$row['CITY_NATIVE'];
        	        $city_native_other      =$row['CITY_NATIVE_OTHER'];
        	}
		
		if($manglik_val=='D')
			$manglik ="Don't know";
		elseif($manglik_val=='M')
			$manglik ="Yes";
		elseif($manglik_val=='A')
			$manglik ="Angshik(Partial Manglik)";	
		elseif($manglik_val=='N')
			$manglik ="No";

		if($date_of_birth){
			$date_brth_arr =explode(" ",$date_of_birth);
			$date_birth =$date_brth_arr[0];
			$time_birth =$date_brth_arr[1];
			$date_time_birth =$date_birth." / ".$time_birth;
			$smarty->assign("date_time_birth",$date_time_birth);	
		}	

                if($re_val=='1')
                        $relation ="Bride";
                elseif($rel_val=='2')
                        $relation ="Groom";
                elseif($rel_val=='3')
                        $relation ="Parent";
                elseif($rel_val=='6')
                        $relation ="Sibling";
                elseif($rel_val=='7')
                        $relation ="Other";

	        $smarty->assign("fname",$fname);
	        $smarty->assign("lname",$lname);
	        $smarty->assign("fname_contact",$fname_contact);
	        $smarty->assign("lname_contact",$lname_contact);
	        $smarty->assign("occupation",$OCCUPATION_DROP[$occupation]);
	        $smarty->assign("company",$company);
	        $smarty->assign("degree",$EDUCATION_LEVEL_NEW_DROP[$degree]);
	        $smarty->assign("college",$college);
       		$smarty->assign("manglik",$manglik);
        	$smarty->assign("relationship",$relation);
	        $smarty->assign("family_info",$family_info);

	        $smarty->assign("city_location_other",$city_loc_other);
	        $smarty->assign("city_birth_other",$city_birth_other);
	        $smarty->assign("city_native_other",$city_native_other);
		$smarty->assign("city_location",$CITY_DROP[$city_location]);
		$smarty->assign("city_birth",$CITY_DROP[$city_birth]);
		$smarty->assign("city_native",$CITY_DROP[$city_native]);
		$smarty->display("show_ext_details.htm");
	}
	else{
	        TimedOut();
        	exit;
	}
?>
