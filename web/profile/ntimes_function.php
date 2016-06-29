<?php
	function ntimes_insert($jprofile_profileid, $affiliate_id)
	{
		//function not in use
		mail("kunal.test02@gmail.com","ntimes_insert() in USE",print_r($_SERVER,true));

		/*$sql_ntimes_insert = "INSERT IGNORE INTO newjs.JP_NTIMES(PROFILEID,NTIMES) SELECT $jprofile_profileid, NTIMES FROM newjs.JPROFILE_AFFILIATE WHERE ID='$affiliate_id'";
		mysql_query_decide($sql_ntimes_insert) or logError("Due to some temporary problem your request could not be processed. Please try after some time.".mysql_error_js(),$sql_ntimes_insert,"ShowErrTemplate");*/
	}

	function ntimes_count($profileid,$string)
	{
		if("UPDATE" == $string)
		{
			$sql_u = "UPDATE newjs.JP_NTIMES SET NTIMES=NTIMES+1 WHERE PROFILEID='$profileid'";
//			mysql_query_optimizer($sql_u) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_u,"ShowErrTemplate");
//
//			if(0 == mysql_affected_rows_js())
//			{
//				$sql = "SELECT NTIMES FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
//				$res = mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
//				$row = mysql_fetch_array($res);
//				$new_ntimes = $row['NTIMES'] + 1;
//
//				$sql_i = "INSERT IGNORE INTO newjs.JP_NTIMES(PROFILEID,NTIMES) VALUES('$profileid','$new_ntimes')";
//				mysql_query_optimizer($sql_i) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_i,"ShowErrTemplate");
//			}

			include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
			$objUpdate = JProfileUpdateLib::getInstance();
			$result = $objUpdate->updateProfileViews($profileid);
			if(false === $result) {
				logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_u,"ShowErrTemplate");
			}
		}
		elseif("SELECT" == $string)
		{
			$sql = "SELECT NTIMES FROM newjs.JP_NTIMES WHERE PROFILEID='$profileid'";
			$res = mysql_query_decide($sql) or error_opt($sql);

			if($row = mysql_fetch_array($res))
				return $row['NTIMES'];
			else
			{
				$sql = "SELECT NTIMES FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
				$res = mysql_query_decide($sql) or error_opt($sql);
				$row = mysql_fetch_array($res);
				$new_ntimes = $row['NTIMES'];

				include_once(JsConstants::$docRoot."/classes/ProfileInsertLib.php");
				$objInsert = ProfileInsertLib::getInstance();
				$objInsert->insertNTimeCount($profileid, $new_ntimes);
//				$sql_i = "INSERT IGNORE INTO newjs.JP_NTIMES(PROFILEID,NTIMES) VALUES('$profileid','$new_ntimes')";
//				mysql_query_decide($sql_i) or die or error_opt($sql_i);

				return $new_ntimes;
			}
		}
	}

	function error_opt($sql)
	{
		if(function_exists("logError"))
			logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		else
			die($sql.mysql_error_js());
	}
?>
