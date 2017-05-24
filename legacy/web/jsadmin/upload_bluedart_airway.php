<?php
	include_once("connect.inc");
	$db2 = connect_db();

	if(authenticated($cid))
	{
		$user = getname($cid);
		$privilage = explode("+",getprivilage($cid));

		if(in_array("IA",$privilage))
		{
			if($upload)
			{
				if(substr($_FILES['uploaded_csv']['name'],-3,3) != "csv")
					$smarty->assign("INVALID_FILE",1);
				else
				{
					$curmonth = strtoupper(date("M"));
					$curyear = date("Y");
					$now = date("Y-m-d G:i:s");

					$sql="TRUNCATE TABLE billing.BLUEDART_AIRWAY_TMP";
					mysql_query($sql,$db) or die(mysql_error1($sql,$db));					

					$sql_upload_file = "LOAD DATA LOCAL INFILE '".TRIM($_FILES['uploaded_csv']['tmp_name'])."' INTO TABLE billing.BLUEDART_AIRWAY_TMP FIELDS TERMINATED BY ',' ENCLOSED BY '\"'";
					mysql_query_decide($sql_upload_file,$db2) or die("$sql_upload_file".mysql_error_js($db2));

					$sql_up="UPDATE billing.BLUEDART_AIRWAY_TMP SET ENTRY_DT='$now'";
					mysql_query_decide($sql_up,$db2) or die("$sql_up".mysql_error_js($db2));
					$smarty->assign("SUCCESSFUL",1);

					$sql="SELECT * FROM billing.BLUEDART_AIRWAY_TMP";
					$res= mysql_query($sql,$db) or die(mysql_error1($sql,$db));
					if(mysql_num_rows($res))
					{
						$sql2= "INSERT IGNORE INTO billing.BLUEDART_AIRWAY (AIRWAY_NUMBER,ENTRY_DT) VALUES";
						while($row= mysql_fetch_array($res))
						{
							$air=trim($row['AIRWAY_NUMBER'],'\r');
							$edate=trim($row['ENTRY_DT'],'\r');
							if($values!='')
								$values.=", ";
							$values.= "(".$air.", '".$edate."')";
						}

						if($values!='')
						{
							$sql1=$sql2.$values;
							mysql_query($sql1,$db) or die(mysql_error1($sql1,$db));
						}

					}

					$sql="TRUNCATE TABLE billing.BLUEDART_AIRWAY_TMP";
					mysql_query($sql,$db) or die(mysql_error1($sql,$db));

				}
			}
		}
		else
			$smarty->assign("UNAUTHORIZED",1);

		$smarty->assign("cid",$cid);
		$smarty->display("upload_bluedart.htm");
	}
	else
	{
		$smarty->assign("user",$user);
		$smarty->display("jsconnectError.tpl");
	}

        function mysql_error1($sql,$db)
	{
		die($sql.mysql_error($db));
		send_mail("anurag.gautam@jeevansathi.com","Error in BlueDart Populating csv :: Table BLUEDART_AIRWAY",mysql_error($db));
	}

?>
