<?php
include_once("connect.inc");
/*
ini_set('upload_max_filesize',20971520);
ini_set('post_max_size',20971520);
*/

$fileName =  $_SERVER["SCRIPT_FILENAME"];
$http_msg=print_r($_SERVER,true);
mail("reshu.rajput@gmail.com,lavesh.rawat@gmail.com","For DLL Movement - $fileName",$http_msg);
$db2 = connect_db();

if($bmscsv==1)
	$title="Upload Variable Discount CSV file FOR BMS";
elseif($bmscsv==2)
	$title="Upload Contact Stats CSV file FOR BMS";

$smarty->assign("bmscsv",$bmscsv);
$smarty->assign("title",$title);

if(authenticated($cid))
{
	$user = getname($cid);
	$privilage = explode("+",getprivilage($cid));

	if(in_array("IA",$privilage))
	{
		if($upload)
		{
			if(substr($_FILES['uploaded_csv']['name'],-3,3) != "csv" && substr($_FILES['uploaded_csv']['name'],-3,3) != "CSV")
				$smarty->assign("INVALID_FILE",1);
			else
			{
				if($bmscsv==1)
				{
					$table="newjs.ANALYTICS_VARIABLE_DISCOUNT";
					$tempTable1=$table."_TEMP1";
					$tempTable2=$table."_TEMP2";
					$tempTable3=$table."_TEMP3";
				}
				elseif($bmscsv==2)
				{
					$table="newjs.ANALYTICS_EOI_STATUS";
					$tempTable1=$table."_TEMP1";
					$tempTable2=$table."_TEMP2";
					$tempTable3=$table."_TEMP3";
					
				}
				else
				{
					die("report to lavesh.rawat@gmail.com");
				}
				/*
				$tempTable1="newjs.BMS_VARIABLE_DISCOUNT_TEMP";
				$tempTable2="newjs.ANALYTICS_VARIABLE_DISCOUNT_TEMP";
				$tempTable3="newjs.ANALYTICS_VARIABLE_DISCOUNT1";
				*/
				
				$sql="CREATE TABLE $tempTable1 (PROFILEID int(11) NOT NULL,SLAB tinyint(4) NOT NULL)";
				mysql_query_decide($sql,$db2) or die("$sql".mysql_error_js($db2));

				$sql_upload_file = "LOAD DATA LOCAL INFILE '".$_FILES['uploaded_csv']['tmp_name']."' INTO TABLE $tempTable1 FIELDS TERMINATED BY ',' ENCLOSED BY '\"'";
				mysql_query_decide($sql_upload_file,$db2) or die("$sql_upload_file".mysql_error_js($db2));

				$smarty->assign("SUCCESSFUL",1);

				//---------->>>>>>>>>>>>>
				$sql="SELECT * FROM $tempTable1";
				$res= mysql_query($sql,$db2) or die(mysql_error($db2).$sql);
				if(mysql_num_rows($res))
				{
					$entry_dt=date('Y-m-d');

					$sql_1="CREATE TABLE $tempTable3 LIKE $table";
					$res_1= mysql_query($sql_1,$db2) or die(mysql_error($db2).$sql_1);					
					
					$sql2= "INSERT INTO $tempTable3(PROFILEID,SLAB) VALUES ";
					while($row= mysql_fetch_array($res))
					{
						$pid=trim($row['PROFILEID'],'\r');
						$slab=trim($row['SLAB'],'\r');

						if($pid)
						{						
							if($i<1000)
							{
								if($values!='')
									$values.=", ";

								$values.= "( '".$pid."', '".$slab."')";
								$i++;
							}
							else
							{
								$sql1=$sql2.$values;
								mysql_query($sql1,$db2) or die(mysql_error($db2).$sql1);
								$values= "( '".$pid."', '".$slab."')";
								$i=0;
							}
						}
					}
					if($values!='')
					{
						$sql1=$sql2.$values;
						mysql_query($sql1,$db2) or die(mysql_error($sql1,$db2));
					}

				}
				$sql="RENAME TABLE $table TO $tempTable2,$tempTable3 TO $table";
				$res= mysql_query($sql,$db2) or die(mysql_error($db2).$sql);					

				$sql="DROP TABLE $tempTable1";
				$res= mysql_query($sql,$db2) or die(mysql_error($db2).$sql);
					
				$sql="DROP TABLE $tempTable2";
				$res= mysql_query($sql,$db2) or die(mysql_error($db2).$sql);					
				//<<------------------------
			}
		}
	}
	else
		$smarty->assign("UNAUTHORIZED",1);

	$smarty->assign("cid",$cid);
	$smarty->display("csv_for_bms.htm");
}
else
{
	$smarty->assign("user",$user);
	$smarty->display("jsconnectError.tpl");
}
?>
