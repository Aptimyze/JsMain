<?php
//include ("connect.inc");
//put_csv("csv_files/","new.csv");

function put_csv($path,$file)
{
        $handle=fopen($path.$file,"r") or die("Cannot open");
        if($handle == "ERROR")
        {
                echo "ERROR";
                exit;
        }
	$strfields=fgets($handle);
	$strfields=str_replace("\"","",$strfields);
	$str_arr=explode(",",$strfields);
	while(!feof($handle))
	{
		$strtemp=fgets($handle);
		$strtemp=str_replace("\"","",$strtemp);
		$val_temp=explode(",",$strtemp);
		for($j=0;$j<count($str_arr);$j++)
		{
			$val_arr[$str_arr[$j]][]=$val_temp[$j];
		}
	}

//	print_r($val_arr);

	for($j=0;$j<count($val_arr[$str_arr[0]])-1;$j++)
	{
		list($time,$date)=explode(" ",$val_arr["Start"][$j]);
	        list($mm,$dd,$yy)=explode("/",$date);
        	list($hr,$min,$sec)=explode(":",$time);
                                                                                                 
	        $ts=mktime($hr,$min,$sec,$mm,$dd,$yy);
//        	$new_dt_ist=date("Y-m-d H:i:s",$ts);
//		$new_dt=strftime("%Y-%m-%d %H:%M:%S",JSstrToTime("$new_dt_ist - 10 hours 30 minutes"));
		$hourdiff = "+10.5";
		$timeadjust = ($hourdiff * 60 * 60);

		$new_dt=date("Y-m-d H:i:s",$ts - $timeadjust);
		// or use time() - based on server time, adjust $hourdiff accordingly
//		$new_dt_ist = date("Y-m-d H:i:s",time() + $timeadjust); 

		$val_temp= $val_arr["Operator"][$j]."','".addslashes($val_arr["PCQ q1"][$j])."','".$new_dt."','".$val_arr["Duration"][$j];
		$mail_arr[]=$val_temp;
		$sql="REPLACE INTO incentive.CHAT(OPERATOR,USERID,START,DURATION) VALUES('".$val_temp."')";
//		mail("alok@jeevansathi.com","mail from crm/put_csv.php","TIME is : $val_arr[Start][$j]\nSQL is : $sql");
		mysql_query_decide($sql) or die($sql."<br>".mysql_error_js());
	}

	return($mail_arr);	
	fclose($handle) or die("Cannot close");
}
?>
