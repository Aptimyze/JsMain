<?php
	include_once("connect.inc");

	$db = connect_misdb();
	$db2 = connect_ddl();

	if(authenticated($cid))
	{
		$user = getname($cid);
		$privilage = explode("+",getprivilage($cid));
		$center = getcenter_for_operator($user);

		if(in_array("IA",$privilage) && "NOIDA"==$center)
		{
			if($upload)
			{
				if(substr($_FILES['uploaded_csv']['name'],-3,3) != "csv")
					$smarty->assign("INVALID_FILE",1);
				else
				{
					$curmonth = strtoupper(date("M"));
					$curyear = date("Y");

					$table_exists = check_table_existance("MIS","PERFORMANCE_TRACK_".$curmonth."_".$curyear);

					if($table_exists)
						$smarty->assign("ERROR_MSG",1);
					else
					{
						$table_name = "MIS.PERFORMANCE_TRACK_".$curmonth."_".$curyear;

						//$sql_drop = "DROP TABLE IF EXISTS $table_name";
						//mysql_query_decide($sql_drop) or die("$sql_drop".mysql_error_js($db));

						$sql_create = "CREATE TABLE $table_name (USERNAME VARCHAR(40) NOT NULL, TARGET DOUBLE NOT NULL, LEAVE_DATES TEXT, KEY(USERNAME))";
						mysql_query_decide($sql_create,$db2) or die("$sql_create".mysql_error_js($db2));

						$sql_upload_file = "LOAD DATA LOCAL INFILE '".$_FILES['uploaded_csv']['tmp_name']."' INTO TABLE $table_name FIELDS TERMINATED BY ',' ENCLOSED BY '\"'";
						mysql_query_decide($sql_upload_file,$db2) or die("$sql_upload_file".mysql_error_js($db2));

						$sql_alter = "ALTER TABLE $table_name ADD LEAVES MEDIUMINT(3) NOT NULL";
						mysql_query_decide($sql_alter,$db2) or die("$sql_alter".mysql_error_js($db2));

						$sql = "SELECT USERNAME, LEAVE_DATES FROM $table_name";
						$res = mysql_query_decide($sql,$db2) or die($sql.mysql_error_js($db2));
						while($row = mysql_fetch_array($res))
						{
							$no_of_leaves = count(explode(",",$row['LEAVE_DATES']));
							$sql_upd = "UPDATE $table_name SET LEAVES='$no_of_leaves' WHERE USERNAME='$row[USERNAME]'";
							mysql_query_decide($sql_upd,$db2) or die($sql_upd.mysql_error_js($db2));
						}

						$smarty->assign("SUCCESSFUL",1);
					}
				}
			}
		}
		else
			$smarty->assign("UNAUTHORIZED",1);

		$smarty->assign("cid",$cid);
		$smarty->display("performance_track_upload.htm");
	}
	else
	{
		$smarty->assign("user",$user);
		$smarty->display("jsconnectError.tpl");
	}
?>
