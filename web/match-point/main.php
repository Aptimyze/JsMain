<?php
if($submit=="change city")	
{
        $sql4="SELECT NAME,LATITUDE,LONGITUDE,ADDRESS FROM newjs.CONTACT_US WHERE state='$city' and Match_Point_Service='Y' order by name";
        $result = mysql_query_decide($sql4) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql4,"ShowErrTemplate");
        $i=0;
        while($row=mysql_fetch_array($result))
        {
                $details[$i]['NAME'] = $row['NAME'];
                $details[$i]['LATITUDE'] = $row['LATITUDE'];
                $details[$i]['LONGITUDE'] = $row['LONGITUDE'];
                $details[$i]['ADDRESS'] = nl2br($row['ADDRESS']);
                $i++;

        }
	$details['i']=$i;
	die(json_encode($details));

}
else
{
	if($sub=='Submit')
	{	$landline="$state_code-$phone";
		if(!is_numeric($phone)||strlen($phone)<6||strlen($state_code)<3||!is_numeric($state_code))
		{
			$phone = "";
			$state_code = "";
			$landline="";
		}
		if(!is_numeric($mobile)||strlen($mobile)<10)
			$mobile = "";

		$flag=checkemail($email);
		if(($_FILES['biod']['error']!=0 && $_FILES['biod']['error']!=4)||($flag!='0'))
		{
			$smarty->assign("first_name",$first_name);
			$smarty->assign("last_name",$last_name);
			$smarty->assign("email",$email);
			$smarty->assign("location",$location);
			$smarty->assign("state_code",$state_code);
			$smarty->assign("phone",$phone);
			$smarty->assign("mobile",$mobile);
			$smarty->assign("start_time",$start_time);
			$smarty->assign("start_day",$start_day);
			$smarty->assign("end_time",$end_time);
			$smarty->assign("end_day",$end_day);
			$smarty->assign("file",$_FILES['biod']['name']);
			
			if($flag!='0')
			{
				if($_FILES['biod']['error']==4)
					$error=2.5;
				else
					$error=2;				
				$smarty->assign("emailerror",$flag);
			}
			else
				$error=1;
			$smarty->assign("error",$error);
		}
		if(!$error)
		{
			if($_FILES['biod']['error']==0 && $_FILES['biod']['size'] >  0)
			{
				$fileName = $_FILES['biod']['name'];
				$tmpName  = $_FILES['biod']['tmp_name'];
				$fileSize = $_FILES['biod']['size'];
				$fileType = $_FILES['biod']['type'];

				$fp      = fopen($tmpName, 'rb') or $flag_error=1;
				$content = fread($fp, filesize($tmpName));
				$content = addslashes($content);
				fclose($fp);

				if(!get_magic_quotes_gpc())
				{
				    $fileName = addslashes($fileName);
				}

				$query = "INSERT INTO newjs.MATCH_POINT(FIRSTNAME,SECONDNAME,EMAIL,LOCATION,PHONE,MOBILE,CALLTIME_START,CALLTIME_END,BIODATA) VALUES('$first_name','$last_name','$email','$location','$landline','$mobile','$start_time $start_day','$end_time $end_day','$content')";
				mysql_query_decide($query) or die('Error, query failed 1');
				$smarty->assign("noerror",1);
			} 
			else if($_FILES['biod']['error']==4)
			{
				$query = "INSERT INTO newjs.MATCH_POINT(FIRSTNAME,SECONDNAME,EMAIL,LOCATION,PHONE,MOBILE,CALLTIME_START,CALLTIME_END) VALUES('$first_name','$last_name','$email','$location','$landline','$mobile','$start_time $start_day','$end_time $end_day')";
				mysql_query_decide($query) or die('Error, query failed 2');
				$smarty->assign("noerror",2);
			}
		}
	}
        $default_city='Delhi';
	//$checksum=;
	$epid_arr=explode("i",$checksum);
        $profileid=$epid_arr[2];

        if($profileid)
        {
                $sql1 = "SELECT city_res FROM newjs.JPROFILE WHERE PROFILEID=$profileid";
                $result = mysql_query_decide($sql1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql1,"ShowErrTemplate");
                $ROW=mysql_fetch_array($result);
                $sql2 = "SELECT label from newjs.CITY_NEW where value='$ROW[city_res]'";
                $result = mysql_query_decide($sql2) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql2,"ShowErrTemplate");
                $ROW=mysql_fetch_array($result);
                $sql3 = "SELECT COUNT(*) as cnt FROM newjs.CONTACT_US WHERE state='$ROW[label]' and Match_Point_Service='Y'";
                $result = mysql_query_decide($sql3) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql3,"ShowErrTemplate");
                $ROWZZ=mysql_fetch_array($result);
                if($ROWZZ['cnt']>0)
                        $default_city=$ROW[label];
        }
        $smarty->assign("default_city",$default_city);
        $sql4="SELECT NAME,LATITUDE,LONGITUDE,ADDRESS FROM newjs.CONTACT_US WHERE state='$default_city' and Match_Point_Service='Y'  order by name";
        $result = mysql_query_decide($sql4) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql4,"ShowErrTemplate");
        $i=0;
        while($row=mysql_fetch_array($result))
        {
                $details[$i]['NAME'] = $row['NAME'];
                $details[$i]['LATITUDE'] = $row['LATITUDE'];
                $details[$i]['LONGITUDE'] = $row['LONGITUDE'];
                $details[$i]['ADDRESS'] = nl2br($row['ADDRESS']);
                $i++;

        }
        //print_r($details);
        $sql5 = "SELECT STATE FROM newjs.CONTACT_US WHERE Match_Point_Service='Y' group by state";
        $result = mysql_query_decide($sql5) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql5,"ShowErrTemplate");
        while($myrow=mysql_fetch_array($result))
        {
                $city[]=$myrow[STATE];
		$sql6="SELECT STD_CODE FROM newjs.CITY_NEW WHERE LABEL='$myrow[STATE]'";
		$res = mysql_query_decide($sql6) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql6,"ShowErrTemplate");	
		$row=mysql_fetch_array($res);
		$code[]="0".$row["STD_CODE"];
        }
        //print_r($city);
        $smarty->assign("details",$details);
        $smarty->assign("std_code",$code);
        $smarty->assign("STATES",$city);
	$smarty->assign("googleApiKey",$googleApiKey);

	if(!$error)
	{
		$smarty->assign("start_time",'9');
		$smarty->assign("start_day",'AM');
		$smarty->assign("end_time",'9');
		$smarty->assign("end_day",'PM');

	}
}
function checkemail($email)     
{
        $flag='0';

        $email=trim($email);

        if($email=="")
        {
                $flag="Please enter a valid email address";
        }
        elseif (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $email))
        {
                $flag="Please enter a valid email address";
        }
        else
        {
                $result = mysql_query_decide("SELECT count(*) FROM newjs.MATCH_POINT where EMAIL='$email'") or logError("error",$sql);
                $myrow = mysql_fetch_row($result);

                if($myrow[0] > 0)
                        $flag="$email already exists in our database";
        }

        $part=explode("@",$email);
        if(!$flag)
        {
                if(strtolower($part[1])=="jeevansaathi.com"||strtolower($part[1])=="jeevansathi.com")
                        $flag="Jeevansathi email address can not be entered";
        }

        if(!$flag)
        {
                $dotpos = strrpos($part[1],".");
                $middle = substr($part[1],0,$dotpos);
                $sql = "SELECT DOMAIN FROM newjs.INVALID_DOMAINS";
                $res = mysql_query_decide($sql) or logError("error",$sql);
                while($row = mysql_fetch_array($res))
                {
                        if(strstr($middle,$row['DOMAIN']))
                        {
                                $flag = "This domain doesn't exists";
                                break;
                        }
                }
        }
        return $flag;
}

?>
