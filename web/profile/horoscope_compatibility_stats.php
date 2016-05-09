<?php
        //this script is used to find show the DATE,VIEW link ,USERNAME of the person whom the logged in user has matched horoscope compatibility 

        include_once("connect.inc");
        $db=connect_db();

        $data=authenticated($checksum);
        if($data)
        {
                $smarty->assign("CHECKSUM",$checksum);
		$sql="SELECT * FROM HOROSCOPE_COMPATIBILITY where PROFILEID='$data[PROFILEID]'";
                $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                while($row = mysql_fetch_array($result))
		{
			$sql_jprofile = "SELECT USERNAME FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$row[PROFILEID_OTHER]'";
			$result_jprofile=mysql_query_decide($sql_jprofile) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_jprofile,"ShowErrTemplate");
			$row_jprofile = mysql_fetch_array($result_jprofile);

			$res_username_arr[]=$row_jprofile['USERNAME'];
			$res_date_arr[]=$row['DATE'];
			$res_profilechecksum_arr[]=md5($row['PROFILEID_OTHER'])."i".$row['PROFILEID_OTHER'];
		}
		$smarty->assign("USERNAME_ARR",$res_username_arr);
		$smarty->assign("DATE_ARR",$res_date_arr);
		$smarty->assign("PROFILECHECKSUM_ARR",$res_profilechecksum_arr);
		$smarty->display("horoscope_compatibility_stats.htm");
	}
	else
	{
		Timedout();
	}


?>
