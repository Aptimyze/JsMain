<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


ini_set("max_execution_time","0");
include_once("../connect.inc");
$db = connect_db();

$file_arr[0] =$_SERVER['DOCUMENT_ROOT']."/crm/csv_files/Abusive_Numbers.csv";

$row=1;
for($i=0;$i<1; $i++)
{
	$file_path=$file_arr[$i];
	$handle =fopen("$file_path", "r");
	if ($handle !== FALSE) 
	{
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
	    	{
			$row++;
			$number		=$data[0];
			$number		=check_number($number);
			if($number)
			{
		                $sql= "INSERT IGNORE INTO newjs.ABUSIVE_PHONE (PHONE_WITH_STD) VALUES ('$number')";
        		        mysql_query($sql,$db) or die($sql1.mysql_error($db_js));
			}

	    	}
    		fclose($handle);
	}
}

function check_number($number)
{
        if(!$number)
                return false;
        $rep_values =array(" ", "-", "(", ")" ,"+" ,"." ,",", "#");
        $number =str_replace($rep_values,'',$number);
        if(substr($number,0,1)=='0')
                $number = substr($number,1);
        if(is_numeric($number))
                return $number;
	else
        	return false;
}	


?>
