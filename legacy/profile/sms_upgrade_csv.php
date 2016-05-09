<?php

/************************************************************************************************************************
*   FILENAME      :  sms_upgrade_csv.php
*   INCLUDE       :  connect.inc
*   DESCRIPTION   :  To Create csv file that stores information(username,city_res,phone1,phone2) of activated and non paid is                     users listed in sms_upgrade table .
*   CREATED BY    :  Lavesh
***********************************************************************************************************************/

include_once ("connect.inc");
$db = connect_db();

$header="\"USERNAME\"".","."\"CITY\"".","."\"PHONE NO.(1)\"".","."\"PHONE NO.(2)\"\n";
//$filename = "/usr/local/apache/sites/jeevansathi.com/htdocs/shiv/sms_upgrade_csv_".date('Y-m-d').".xls";
$filename = JsConstants::$docRoot."/test/sms_upgrade_csv_".date('Y-m-d').".xls";
$fp = fopen($filename,"w+");
if(!$fp)
{
        die("no file pointer");
}

fwrite($fp,$header);


$sql="SELECT PROFILEID FROM newjs.SMS_UPGRADE ";
$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
while($row=mysql_fetch_array($res))
{
        $all_profileid[]=$row['PROFILEID'];
}

$num=count($all_profileid);

for($i=0;$i<$num;$i++)
{
	$id=$all_profileid[$i];
        $sql="SELECT USERNAME,PHONE_MOB,PHONE_RES,CITY_RES,COUNTRY_RES FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$id' AND ACTIVATED IN ('Y','H') AND SUBSCRIPTION=''";
	$res=mysql_query_decide($sql) or die(mysql_error_js());
        if($row=mysql_fetch_array($res))
        {
		$username=$row["USERNAME"];
		$country=$row['COUNTRY_RES'];
		$PHONE_MOB=$row['PHONE_MOB'];
		$PHONE_RES=$row['PHONE_RES'];
		
		if($country=='51')
        	{
			$city=$row["CITY_RES"];
			if($city)
			{
				$myrow=label_select('CITY_INDIA',$city);
                		$city=$myrow[0];
			}
		}
		elseif($country=='128')
		{
			$city=$row["CITY_RES"];
			if($city)
                        {
                                $myrow=label_select('CITY_USA',$city);
                                $city=$myrow[0];
                        }
		}

                if(!$city)
                        $city='';

		if(!(($PHONE_MOB=="")||($PHONE_RES=="")))
			$line="\"$username\"".","."\"$city\"".","."\"$PHONE_MOB\"".","."\"$PHONE_RES\"";

		elseif($PHONE_MOB=="")
			 $line="\"$username\"".","."\"$city\"".","."\"$PHONE_RES\"".","."\"$PHONE_MOB\"";

		else
			$line="\"$username\"".","."\"$city\"".","."\"$PHONE_MOB\"".","."\"$PHONE_RES\"";

		$data = trim($line)."\n";
                fwrite($fp,$data);

		unset($id);
		unset($username);
		unset($city);
		unset($PHONE_RES);
		unset($PHONE_MOB);
		unset($data);
	}
}
//closing the file
fclose($fp);

?>
