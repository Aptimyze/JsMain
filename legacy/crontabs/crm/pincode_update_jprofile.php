<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");



ini_set("max_execution_time","0");
include($_SERVER['DOCUMENT_ROOT']."/crm/connect.inc");
$db = connect_db();

$file_arr[0] =$_SERVER['DOCUMENT_ROOT']."/crm/csv_files/NaukriforJS_1.csv";
$file_arr[1] =$_SERVER['DOCUMENT_ROOT']."/crm/csv_files/NaukriforJS_2.csv";

$row=1;
for($i=0;$i<2; $i++)
{
	$file_path=$file_arr[$i];
	$handle =fopen("$file_path", "r");
	if ($handle !== FALSE) 
	{
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
	    	{
			/*
        		$num = count($data);
        		echo "<p> $num fields in line $row: <br /></p>\n";
        		$row++;
        		for ($c=0; $c < $num; $c++) 
			{
        		    $data[$c] . "<br />\n";
        		}
			*/
			$row++;

			$profileid	=$data[0];
			$pincode1 	=$data[45];
			$pincode2	=$data[46];
			$pincode3	=$data[47];

			$pincode ='';
			if($pincode1 && check_pincode($pincode1))
				$pincode =$pincode1;
			else if($pincode2 && check_pincode($pincode2))
				$pincode =$pincode2;
			else if($pincode3 && check_pincode($pincode3))
				$pincode =$pincode3;

			// Normal Update pincode query
			/*
			if($pincode && $profileid){
				$sql ="update newjs.JPROFILE SET PINCODE='$pincode' WHERE `PROFILEID`='$profileid' and `PINCODE`=''";
				$myres=mysql_query($sql,$db) or die("$sql".mysql_error($db));	
			}
			*/	

			// Check pincode and update 
			if($profileid && $pincode)
			{
				$sql ="select `PINCODE` from newjs.JPROFILE WHERE PROFILEID='$profileid'";
				$myres=mysql_query($sql,$db) or die("$sql".mysql_error($db));
				$myrow =mysql_fetch_array($myres);
				$pincode_val =$myrow['PINCODE'];
				if($pincode_val)
				{	
					$pincode_status  =check_pincode($pincode_val);	
					if(!$pincode_status)
					{
						$sql1 ="update newjs.JPROFILE SET PINCODE='$pincode' WHERE `PROFILEID`='$profileid'";
						mysql_query($sql1,$db) or die("$sql1".mysql_error($db));
					}	
				}
				else
				{	
					$sql2 ="update newjs.JPROFILE SET PINCODE='$pincode' WHERE `PROFILEID`='$profileid'";
					mysql_query($sql2,$db) or die("$sql2".mysql_error($db));
				}
			}

			//if($row=='1000')
			//	die;

	    	}
    		fclose($handle);
	}
}

function check_pincode($number)
{
        if(!$number)
                return false;
        if(substr($number,0,1)=='0')
                $number = substr($number,1);
        if(is_numeric($number) && (strlen($number)=='6'))
                return $number;
	else
        	return false;
}	


?>
