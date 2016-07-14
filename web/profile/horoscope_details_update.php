<?php
include_once("connect.inc");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
function horoscope_details_update($checksum)
{
	$db=connect_db();
	$data=authenticated($checksum);
	if($data)
	{
		$astro_detail_exists=0;
		$profileid = $data['PROFILEID'];
		$today = date("Y-m-d");

		$sql_mtongue = "SELECT MTONGUE FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
		$res_mtongue = mysql_query_decide($sql_mtongue) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_mtongue,"ShowErrTemplate");
		$row_mtongue = mysql_fetch_array($res_mtongue);
		$mtongue = $row_mtongue['MTONGUE'];

		//section to update TYPE in newjs.ASTRO_DETAILS when the user has switched from System generated horoscope to uploaded horoscope or the other way round
		if($type)
		{
			$objUpdate = JProfileUpdateLib::getInstance();
			$result = $objUpdate->updateASTRO_DETAILS($profileid, array('TYPE'=>$type));
			if(false === $result) {
				$sql_update = "update newjs.ASTRO_DETAILS set TYPE='$type' WHERE PROFILEID='$profileid'";
				logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_update,"ShowErrTemplate");
			}
//			$sql_update = "update newjs.ASTRO_DETAILS set TYPE='$type' WHERE PROFILEID='$profileid'";
//			mysql_query_decide($sql_update) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_update,"ShowErrTemplate");
		}
		//end of section to update TYPE in newjs.ASTRO_DETAILS when the user has switched from System generated horoscope to uploaded horoscope or the other way round

		$sql = "SELECT * FROM newjs.ASTRO_DETAILS WHERE PROFILEID='$profileid'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if(mysql_num_rows($result) > 0)
		{
			$row1 = mysql_fetch_array($result);
			$astro_detail_exists=1;
		}
		//if user has already entered his astro details once, then show him his chart details and a button to update the horoscope.
		if($astro_detail_exists)
		{
			//storing in ASTRO_PULLING_REQUEST, incase the user fills the entire details but does not save the details then we use this table to save his details from cron.
			$sql = "INSERT INTO newjs.ASTRO_PULLING_REQUEST (PROFILEID,ENTRY_DT,PENDING,TYPE) VALUES('$profileid',NOW(),'Y','C')";
			mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			//storing the click on update button.

			$sql = "INSERT INTO MIS.ASTRO_CLICK_COUNT(PROFILEID,TYPE,ENTRY_DT,MTONGUE) VALUES('$profileid','C',NOW(),'$mtongue')";
			mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		//if the user is creating the horoscope for first time.
		else
		{
			//storing in ASTRO_PULLING_REQUEST, incase the user fills the entire details but does not save the details then we use this table to save his details from cron.
			$sql = "INSERT INTO newjs.ASTRO_PULLING_REQUEST (PROFILEID,ENTRY_DT,PENDING,TYPE) VALUES('$profileid',NOW(),'Y','A')";
			mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

			//storing the click on add button.
			$sql = "INSERT INTO MIS.ASTRO_CLICK_COUNT(PROFILEID,TYPE,ENTRY_DT,MTONGUE) VALUES('$profileid','A',NOW(),'$mtongue')";
			mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
	}
}
?>
