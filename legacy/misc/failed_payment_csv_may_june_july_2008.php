<?php
	$month_array = array("05","06","07");
	$file_name2 = "bulk_csv_crm_data_2008-may-june-july_failed_payments.txt";
	$fp2 = fopen($file_name2,"w");
	for($x = 0;$x<count($month_array);$x++)
	{
		for($i=1;$i<=31;$i++)
		{
			if($i<10)
				$j = "0".$i;
			else
				$j=$i;

			unset($file_name);
			$file_name = "bulk_csv_crm_data_2008-"."$month_array[$x]-$j"."_failed_payments.txt";
			if(file_exists($file_name))
			{
				$fp = fopen($file_name,"r");
				if($fp)
				{
					while(!feof($fp))
					{
						$data = fgets($fp);
						if(!strstr($data,"PROFILEID"))
							fwrite($fp2,$data);
					}
				}
				fclose($fp);
			}
		}
	}
	fclose($fp2);
?>
